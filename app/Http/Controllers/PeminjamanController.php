<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\Buku;
use App\Models\BukuEksemplar;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\User;
use App\Notifications\BookingReadyNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    // Halaman daftar & riwayat peminjaman
    public function index(Request $request)
    {
        // Role petugas diarahkan ke halaman operasional Barcode Peminjaman
        if (auth()->check() && auth()->user()->role === 'petugas' && ! $request->has('riwayat')) {
            return redirect()->route('peminjaman.create');
        }

        $peminjamans = Peminjaman::with(['member', 'petugas', 'details.buku'])
            ->latest('idPeminjaman')
            ->paginate(10);

        return view('peminjaman.index', compact('peminjamans'));
    }

    // Tampilkan form peminjaman (Scan barcode & input member)
    public function create(Request $request)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('dashboard')->with('error', 'Layanan meja sirkulasi peminjaman hanya diperuntukkan bagi Petugas Perpustakaan.');
        }

        $members = User::where('role', 'member')->where('status', 'aktif')->get();

        $bookingCode = trim((string) $request->query('booking', $request->query('code', '')));
        $selectedBooking = null;

        if (! empty($bookingCode)) {
            $selectedBooking = Peminjaman::with([
                'member',
                'details.buku.barcode',
                'details.eksemplar',
            ])
                ->where('kode_booking', $bookingCode)
                ->orWhere('kode_booking', strtoupper($bookingCode))
                ->orWhere('qr_token', $bookingCode)
                ->first();
        }

        if ($selectedBooking) {
            $defaultMember = $selectedBooking->member;
            $firstDetail = $selectedBooking->details->first();
            $defaultBuku = $firstDetail?->buku;
            $defaultEksemplar = $firstDetail?->eksemplar;
        } else {
            // Awalnya kosong: rincian di sebelah kanan akan terisi secara realtime saat discan atau dicari
            $defaultMember = null;
            $defaultBuku = null;
            $defaultEksemplar = null;
        }

        return view('peminjaman.create', compact('members', 'defaultMember', 'defaultBuku', 'defaultEksemplar', 'selectedBooking'));
    }

    // Konfirmasi & Simpan Transaksi Peminjaman
    public function store(Request $request)
    {
        $request->validate([
            'idUserMember' => ['required', 'exists:users,id'],
            'barcodes' => ['required', 'array', 'min:1'],
            'barcodes.*' => ['required', 'string'],
        ], [
            'idUserMember.required' => 'Pilih member yang meminjam.',
            'barcodes.required' => 'Minimal scan 1 barcode buku.',
        ]);

        // Bersihkan input barcode dari string kosong
        $inputBarcodes = array_filter(array_map('trim', $request->barcodes));

        // 1. Validasi Batas Maksimal 7 Buku
        $totalBuku = count($inputBarcodes);
        if ($totalBuku > Peminjaman::BATAS_MAKSIMAL_BUKU) {
            $pesan = 'Gagal: Batas maksimal peminjaman adalah '.Peminjaman::BATAS_MAKSIMAL_BUKU.' buku.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $pesan], 422);
            }

            return back()->withErrors(['barcodes' => $pesan])->withInput();
        }

        if ($totalBuku === 0) {
            $pesan = 'Masukkan setidaknya 1 kode barcode buku.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $pesan], 422);
            }

            return back()->withErrors(['barcodes' => $pesan])->withInput();
        }

        // Cek apakah member masih memiliki buku yang sedang dipinjam
        $member = User::findOrFail($request->idUserMember);
        $bukuSedangDipinjam = $member->jumlahBukuSedangDipinjam();

        if ($bukuSedangDipinjam >= Peminjaman::BATAS_MAKSIMAL_BUKU) {
            $pesan = "Gagal meminjam: Anggota '{$member->name}' saat ini telah meminjam {$bukuSedangDipinjam} buku (batas maksimal ".Peminjaman::BATAS_MAKSIMAL_BUKU.' buku). Anggota wajib melakukan pengembalian buku terlebih dahulu untuk dapat meminjam kembali.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $pesan], 422);
            }

            return back()->withErrors(['barcodes' => $pesan])->withInput();
        }

        if (($bukuSedangDipinjam + $totalBuku) > Peminjaman::BATAS_MAKSIMAL_BUKU) {
            $sisaKuota = max(0, Peminjaman::BATAS_MAKSIMAL_BUKU - $bukuSedangDipinjam);
            $pesan = "Gagal meminjam: Anggota '{$member->name}' saat ini sedang meminjam {$bukuSedangDipinjam} buku. Sisa kuota peminjaman hanya {$sisaKuota} buku, sedangkan buku yang akan dipinjam sebanyak {$totalBuku} buku. Anggota wajib melakukan pengembalian buku terlebih dahulu.";
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $pesan], 422);
            }

            return back()->withErrors(['barcodes' => $pesan])->withInput();
        }

        // Cek duplikasi kode scan dalam 1 transaksi
        if (count($inputBarcodes) !== count(array_unique($inputBarcodes))) {
            $pesan = 'Terdapat kode buku fisik yang di-scan lebih dari satu kali dalam transaksi yang sama.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $pesan], 422);
            }

            return back()->withErrors(['barcodes' => $pesan])->withInput();
        }

        // 2. Transaksi Atomik dengan Row Locking (lockForUpdate)
        try {
            $peminjaman = DB::transaction(function () use ($request, $inputBarcodes, $totalBuku) {
                $eksemplarItems = [];
                $selectedEksemplarIds = [];

                foreach ($inputBarcodes as $code) {
                    $code = trim($code);

                    // A. Cari physical copy di tabel buku_eksemplar dengan lockForUpdate
                    $eksemplarQuery = BukuEksemplar::with('buku')->lockForUpdate();
                    if (is_numeric($code)) {
                        $eksemplar = (clone $eksemplarQuery)->where('idEksemplar', $code)->first()
                            ?? (clone $eksemplarQuery)->where('qr_token', $code)->orWhere('kode_barcode', $code)->first();
                    } else {
                        $eksemplar = (clone $eksemplarQuery)->where('qr_token', $code)->orWhere('kode_barcode', $code)->first();
                    }

                    if (! $eksemplar) {
                        // Tolak jika yang di-scan adalah title-level QR code
                        if (Buku::where('qr_token', $code)->exists()) {
                            throw new \DomainException("Kode [{$code}] adalah QR judul buku, bukan eksemplar fisik. Silakan scan QR stiker pada buku fisik.");
                        }

                        throw new \DomainException("Buku fisik dengan QR/Barcode [{$code}] tidak ditemukan dalam database.");
                    }

                    // Re-check status fisik setelah lock didapatkan
                    if ($eksemplar->status !== 'Tersedia') {
                        $judul = $eksemplar->buku->judul ?? 'Buku';
                        throw new \DomainException("Buku '{$judul}' (Eksemplar #{$eksemplar->nomor_eksemplar}) statusnya sedang {$eksemplar->status}.");
                    }

                    if (in_array($eksemplar->idEksemplar, $selectedEksemplarIds)) {
                        $judul = $eksemplar->buku->judul ?? 'Buku';
                        throw new \DomainException("Buku '{$judul}' (Eksemplar #{$eksemplar->nomor_eksemplar}) di-scan lebih dari satu kali dalam transaksi yang sama.");
                    }

                    $selectedEksemplarIds[] = $eksemplar->idEksemplar;
                    $eksemplarItems[] = $eksemplar;
                }

                $tanggalPinjam = Carbon::now();
                $batasKembali = Carbon::now()->addDays(Peminjaman::MASA_PINJAM_HARI);

                $peminjaman = Peminjaman::create([
                    'idUserMember' => $request->idUserMember,
                    'idUserPetugas' => auth()->id(),
                    'tanggalPinjam' => $tanggalPinjam->toDateString(),
                    'batasKembali' => $batasKembali->toDateString(),
                    'status' => 'Dipinjam',
                    'totalBuku' => $totalBuku,
                ]);

                foreach ($eksemplarItems as $eksemplar) {
                    DetailPeminjaman::create([
                        'idPeminjaman' => $peminjaman->idPeminjaman,
                        'idBuku' => $eksemplar->idBuku,
                        'idEksemplar' => $eksemplar->idEksemplar,
                        'jumlah' => 1,
                        'statusBuku' => 'Dipinjam',
                    ]);

                    // Update status eksemplar fisik menjadi Dipinjam
                    $eksemplar->update(['status' => 'Dipinjam']);

                    // Sinkronkan stok tersedia pada master buku
                    $eksemplar->buku->syncStok();
                }

                return $peminjaman;
            });
        } catch (\DomainException $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->withErrors(['barcodes' => $e->getMessage()])->withInput();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi peminjaman berhasil dikonfirmasi.',
                'idPeminjaman' => $peminjaman->idPeminjaman,
                'redirect' => route('peminjaman.show', $peminjaman->idPeminjaman),
            ]);
        }

        // 4. Arahkan ke halaman bukti peminjaman
        return redirect()->route('peminjaman.show', $peminjaman->idPeminjaman)
            ->with('success', 'Transaksi peminjaman berhasil dikonfirmasi.');
    }

    // Tampilkan Bukti / Halaman Barcode Berhasil
    public function show($id)
    {
        // Pastikan hanya Petugas atau Admin yang berhak mengakses halaman hasil ini
        if (auth()->check() && ! in_array(auth()->user()->role, ['petugas', 'admin'])) {
            abort(403, 'Akses khusus petugas dan administrator.');
        }

        $peminjaman = Peminjaman::with([
            'member',
            'petugas',
            'details.buku.barcode',
            'details.eksemplar',
        ])->findOrFail($id);

        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * Helper universal untuk generate string SVG QR Code
     */
    private function generateSvgQr($text, $size = 200)
    {
        $url = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&format=svg&data=".urlencode($text);
        $svg = @file_get_contents($url);

        if ($svg) {
            return $svg;
        }

        return '<img src="'.$url.'" width="'.$size.'" height="'.$size.'" alt="QR Code">';
    }

    /**
     * Halaman konfirmasi peminjaman buku khusus member.
     */
    public function konfirmasiMember($id)
    {
        $user = auth()->user();

        $buku = Buku::with(['kategori', 'eksemplarTersedia'])->findOrFail($id);

        $totalEksemplar = $buku->eksemplar()->count();
        $stokTersedia = (int) $buku->stok;
        $isTersedia = $stokTersedia > 0 && $buku->eksemplarTersedia->isNotEmpty();

        $bukuSedangDipinjam = DetailPeminjaman::whereHas('peminjaman', function ($q) use ($user) {
            $q->where('idUserMember', $user->id)->whereIn('status', ['Booking', 'Siap Diambil', 'Dipinjam']);
        })->whereIn('statusBuku', ['Booking', 'Siap Diambil', 'Dipinjam'])->count();

        $batasMaksimalBuku = Peminjaman::BATAS_MAKSIMAL_BUKU;
        $durasiHari = Peminjaman::MASA_PINJAM_HARI;
        $masaPinjamBulan = Peminjaman::MASA_PINJAM_BULAN;
        $batasAmbilJam = Peminjaman::BATAS_AMBIL_BOOKING_JAM;

        $sisaKuota = max(0, $batasMaksimalBuku - $bukuSedangDipinjam);
        $kuotaHabis = $sisaKuota <= 0;

        $sedangPinjamBukuIni = DetailPeminjaman::whereHas('peminjaman', function ($q) use ($user) {
            $q->where('idUserMember', $user->id)->whereIn('status', ['Booking', 'Siap Diambil', 'Dipinjam']);
        })->where('idBuku', $buku->idBuku)->whereIn('statusBuku', ['Booking', 'Siap Diambil', 'Dipinjam'])->exists();

        $tanggalPinjam = Carbon::now();
        $batasKembali = Carbon::now()->addDays($durasiHari);
        $estimasiBatasAmbil = Carbon::now()->addHours($batasAmbilJam);

        return view('peminjaman.member_konfirmasi', compact(
            'buku',
            'user',
            'totalEksemplar',
            'stokTersedia',
            'isTersedia',
            'bukuSedangDipinjam',
            'batasMaksimalBuku',
            'masaPinjamBulan',
            'durasiHari',
            'batasAmbilJam',
            'sisaKuota',
            'kuotaHabis',
            'sedangPinjamBukuIni',
            'tanggalPinjam',
            'batasKembali',
            'estimasiBatasAmbil'
        ));
    }

    /**
     * Proses pengajuan booking peminjaman mandiri oleh member.
     */
    public function ajukanMember(Request $request, $id)
    {
        $user = auth()->user();

        if ($user->status !== 'aktif') {
            return back()->with('error', 'Status keanggotaan Anda saat ini tidak aktif. Silakan hubungi petugas perpustakaan.');
        }

        $request->validate([
            'opsi_pengambilan' => ['nullable', 'in:siapkan_petugas,ambil_mandiri'],
        ]);

        $opsiPengambilan = $request->input('opsi_pengambilan', 'ambil_mandiri');

        $bukuSedangDipinjam = $user->jumlahBukuSedangDipinjam();
        if ($bukuSedangDipinjam >= Peminjaman::BATAS_MAKSIMAL_BUKU) {
            return back()->with('error', 'Gagal booking: Anda telah mencapai batas maksimal '.Peminjaman::BATAS_MAKSIMAL_BUKU.' buku dengan status dipinjam. Anda harus melakukan pengembalian terlebih dahulu untuk meminjam atau membooking buku baru.');
        }

        $bukuAktif = $user->jumlahBukuAktif();
        if ($bukuAktif >= Peminjaman::BATAS_MAKSIMAL_BUKU) {
            return back()->with('error', 'Gagal booking: Anda telah mencapai batas maksimal '.Peminjaman::BATAS_MAKSIMAL_BUKU.' buku aktif (booking/pinjaman). Harap kembalikan buku terlebih dahulu.');
        }

        $sedangPinjamBukuIni = DetailPeminjaman::whereHas('peminjaman', function ($q) use ($user) {
            $q->where('idUserMember', $user->id)->whereIn('status', ['Booking', 'Siap Diambil', 'Dipinjam']);
        })->where('idBuku', $id)->whereIn('statusBuku', ['Booking', 'Siap Diambil', 'Dipinjam'])->exists();

        if ($sedangPinjamBukuIni) {
            return back()->with('error', 'Anda sudah memiliki booking atau pinjaman aktif untuk judul buku ini.');
        }

        try {
            $peminjaman = DB::transaction(function () use ($id, $user, $opsiPengambilan) {
                $buku = Buku::lockForUpdate()->findOrFail($id);

                $eksemplar = BukuEksemplar::where('idBuku', $buku->idBuku)
                    ->where('status', 'Tersedia')
                    ->lockForUpdate()
                    ->first();

                if (! $eksemplar || $buku->stok <= 0) {
                    throw new \DomainException('Maaf, eksemplar buku "'.$buku->judul.'" baru saja dibooking atau dipinjam anggota lain.');
                }

                $tanggalPinjam = Carbon::now();
                $batasKembali = Carbon::now()->addDays(Peminjaman::MASA_PINJAM_HARI);
                $batasAmbil = Carbon::now()->addHours(Peminjaman::BATAS_AMBIL_BOOKING_JAM);

                // Generate kode booking unik, contoh: BK-20261007-8A2F1B
                do {
                    $kodeBooking = sprintf('BK-%s-%s', Carbon::now()->format('Ymd'), strtoupper(bin2hex(random_bytes(3))));
                } while (Peminjaman::where('kode_booking', $kodeBooking)->exists());

                $qrToken = 'book_'.bin2hex(random_bytes(16));

                $peminjaman = Peminjaman::create([
                    'idUserMember' => $user->id,
                    'idUserPetugas' => null,
                    'tanggalPinjam' => $tanggalPinjam->toDateString(),
                    'batasKembali' => $batasKembali->toDateString(),
                    'status' => 'Booking',
                    'totalBuku' => 1,
                    'kode_booking' => $kodeBooking,
                    'qr_token' => $qrToken,
                    'batasAmbil' => $batasAmbil,
                    'opsi_pengambilan' => $opsiPengambilan,
                ]);

                DetailPeminjaman::create([
                    'idPeminjaman' => $peminjaman->idPeminjaman,
                    'idBuku' => $buku->idBuku,
                    'idEksemplar' => $eksemplar->idEksemplar,
                    'jumlah' => 1,
                    'statusBuku' => 'Booking',
                ]);

                // Update eksemplar fisik menjadi Dibooking agar tidak terambil orang lain
                $eksemplar->update(['status' => 'Dibooking']);

                // Sinkronkan stok tersedia pada master buku
                $buku->syncStok();

                return $peminjaman;
            });

            return redirect()->route('dashboard')
                ->with('success', 'Booking buku berhasil diajukan! Silakan datang ke perpustakaan dan tunjukkan Kartu / QR Anggota Anda kepada petugas di meja sirkulasi.');
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses booking: '.$e->getMessage());
        }
    }

    /**
     * Halaman Tiket QR Code Booking Peminjaman untuk Member.
     */
    public function tiketBooking($id)
    {
        $user = auth()->user();

        $peminjaman = Peminjaman::with([
            'member',
            'petugas',
            'details.buku.kategori',
            'details.buku.barcode',
            'details.eksemplar',
        ])->findOrFail($id);

        if ($user && $user->role === 'member' && (int) $peminjaman->idUserMember !== (int) $user->id) {
            abort(403, 'Anda tidak memiliki hak akses melihat tiket booking ini.');
        }

        // Teks QR yang di-encode: qr_token transaksi booking
        $qrPayload = $peminjaman->qr_token ?: $peminjaman->kode_booking;
        $qrCodeSvg = $this->generateSvgQr($qrPayload, 220);
        $isCompleted = ($peminjaman->status === 'Dipinjam');

        return view('peminjaman.booking_tiket', compact('peminjaman', 'qrCodeSvg', 'isCompleted'));
    }

    /**
     * Cek status realtime tiket booking peminjaman (polling AJAX untuk notifikasi pop-up member).
     */
    public function apiCheckStatusBooking(Request $request, $id)
    {
        $user = auth()->user();

        $peminjaman = Peminjaman::with([
            'member',
            'petugas',
            'details.buku',
            'details.eksemplar',
        ])->findOrFail($id);

        if ($user && $user->role === 'member' && (int) $peminjaman->idUserMember !== (int) $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak akses melihat tiket booking ini.',
            ], 403);
        }

        $totalBuku = $peminjaman->details->count();
        $isCompleted = ($peminjaman->status === 'Dipinjam');

        if ($isCompleted) {
            $namaPetugas = $peminjaman->petugas?->name ?? 'Petugas Meja Sirkulasi';
            $waktuSelesai = $peminjaman->updated_at ? $peminjaman->updated_at->translatedFormat('d M Y, H:i') : Carbon::now()->translatedFormat('d M Y, H:i');
            $batasKembali = $peminjaman->batasKembali ? Carbon::parse($peminjaman->batasKembali)->translatedFormat('d M Y') : Carbon::now()->addDays(30)->translatedFormat('d M Y');

            return response()->json([
                'success' => true,
                'completed' => true,
                'status' => 'Dipinjam',
                'totalBuku' => $totalBuku,
                'kodeBooking' => $peminjaman->kode_booking,
                'namaPetugas' => $namaPetugas,
                'batasKembali' => $batasKembali,
                'waktuSelesai' => $waktuSelesai,
                'message' => 'Peminjaman buku berhasil dikonfirmasi dan diserahkan oleh petugas!',
            ]);
        }

        return response()->json([
            'success' => true,
            'completed' => false,
            'status' => $peminjaman->status,
            'totalBuku' => $totalBuku,
        ]);
    }

    /**
     * Batalkan booking peminjaman oleh member.
     */
    public function batalBooking(Request $request, $id)
    {
        $user = auth()->user();

        $peminjaman = Peminjaman::with(['details.eksemplar', 'details.buku'])->findOrFail($id);

        if ($user && $user->role === 'member' && (int) $peminjaman->idUserMember !== (int) $user->id) {
            abort(403, 'Akses ditolak.');
        }

        if (! in_array($peminjaman->status, ['Booking', 'Siap Diambil'])) {
            return back()->with('error', 'Booking tidak dapat dibatalkan karena status transaksi saat ini: '.$peminjaman->status);
        }

        DB::transaction(function () use ($peminjaman) {
            foreach ($peminjaman->details as $detail) {
                if ($detail->eksemplar && $detail->eksemplar->status === 'Dibooking') {
                    $detail->eksemplar->update(['status' => 'Tersedia']);
                }
                if ($detail->buku) {
                    $detail->buku->syncStok();
                }
                $detail->update(['statusBuku' => 'Dibatalkan']);
            }

            $peminjaman->update([
                'status' => 'Dibatalkan',
            ]);
        });

        return redirect()->route('riwayat.index')
            ->with('success', 'Booking peminjaman buku "'.$peminjaman->kode_booking.'" berhasil dibatalkan. Eksemplar buku telah dikembalikan ke stok tersedia.');
    }

    /**
     * Tandai buku booking telah disiapkan oleh petugas di meja layanan.
     */
    public function siapkanBooking(Request $request, $id)
    {
        $role = auth()->user()->role ?? '';
        if ($role !== 'petugas' && $role !== 'admin') {
            abort(403, 'Akses terbatas untuk Petugas dan Administrator.');
        }

        [$peminjaman, $approved] = DB::transaction(function () use ($request, $id): array {
            $peminjaman = Peminjaman::with(['member', 'details.buku'])
                ->lockForUpdate()
                ->findOrFail($id);

            if ($peminjaman->status !== 'Booking') {
                return [$peminjaman, false];
            }

            $peminjaman->update([
                'status' => 'Siap Diambil',
                'catatan_petugas' => $request->input('catatan_petugas', 'Buku telah disiapkan di meja reservasi sirkulasi.'),
            ]);

            $peminjaman->member?->notify(new BookingReadyNotification(
                (int) $peminjaman->idPeminjaman,
                (string) ($peminjaman->kode_booking ?: $peminjaman->kode_transaksi),
                $peminjaman->details->pluck('buku.judul')->filter()->values()->all()
            ));

            return [$peminjaman, true];
        });

        if (! $approved) {
            return back()->with('error', 'Status peminjaman bukan Booking (status saat ini: '.$peminjaman->status.').');
        }

        $judulBuku = $peminjaman->details->first()?->buku?->judul ?? 'Buku';

        return back()->with('success', 'Buku "'.$judulBuku.'" berhasil ditandai SIAP DIAMBIL. Member dapat mengambil buku di meja layanan.');
    }

    /**
     * Konfirmasi serah terima buku fisik booking oleh petugas (beralih ke status Dipinjam).
     */
    public function serahTerimaBooking(Request $request, $id)
    {
        $role = auth()->user()->role ?? '';
        if ($role !== 'petugas' && $role !== 'admin') {
            abort(403, 'Akses terbatas untuk Petugas dan Administrator.');
        }

        $peminjaman = Peminjaman::with(['details.eksemplar', 'details.buku', 'member'])->findOrFail($id);

        if (! in_array($peminjaman->status, ['Booking', 'Siap Diambil'])) {
            return back()->with('error', 'Transaksi ini tidak dalam status Booking atau Siap Diambil (status: '.$peminjaman->status.').');
        }

        $member = $peminjaman->member;
        $bukuSedangDipinjam = $member ? $member->jumlahBukuSedangDipinjam() : 0;
        $jumlahBukuBooking = $peminjaman->details->count();

        if (($bukuSedangDipinjam + $jumlahBukuBooking) > Peminjaman::BATAS_MAKSIMAL_BUKU) {
            $pesanError = "Penyerahan ditolak: Anggota {$member?->name} saat ini telah meminjam {$bukuSedangDipinjam} buku. Total peminjaman setelah serah terima akan menjadi ".($bukuSedangDipinjam + $jumlahBukuBooking).' buku (Batas maksimal '.Peminjaman::BATAS_MAKSIMAL_BUKU.' buku). Member wajib mengembalikan buku terlebih dahulu.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $pesanError,
                ], 422);
            }

            return back()->with('error', $pesanError);
        }

        DB::transaction(function () use ($peminjaman) {
            $tanggalPinjam = Carbon::now();
            $batasKembali = Carbon::now()->addDays(Peminjaman::MASA_PINJAM_HARI);

            $peminjaman->update([
                'idUserPetugas' => auth()->id(),
                'tanggalPinjam' => $tanggalPinjam->toDateString(),
                'batasKembali' => $batasKembali->toDateString(),
                'status' => 'Dipinjam',
            ]);

            foreach ($peminjaman->details as $detail) {
                $detail->update(['statusBuku' => 'Dipinjam']);
                if ($detail->eksemplar) {
                    $detail->eksemplar->update(['status' => 'Dipinjam']);
                }
            }
        });

        $namaMember = $peminjaman->member?->name ?? 'Member';

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Serah terima buku kepada '.$namaMember.' berhasil! Masa pinjam 30 hari resmi aktif.',
                'redirect' => route('peminjaman.show', $peminjaman->idPeminjaman),
            ]);
        }

        return redirect()->route('peminjaman.show', $peminjaman->idPeminjaman)
            ->with('success', 'Serah terima buku kepada '.$namaMember.' berhasil! Masa pinjam 30 hari resmi aktif.');
    }
}
