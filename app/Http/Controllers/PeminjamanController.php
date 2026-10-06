<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\Buku;
use App\Models\BukuEksemplar;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\User;
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
        $members = User::where('role', 'member')->where('status', 'aktif')->get();

        // Data default / preview sesuai layout Figma (Rizky Pratama & Laut Bercerita)
        $defaultMember = User::where('role', 'member')->where('name', 'like', '%Rizky Pratama%')->first()
            ?? User::where('role', 'member')->where('status', 'aktif')->first();

        $defaultBuku = Buku::with(['barcode', 'eksemplarTersedia'])
            ->where('judul', 'like', '%Laut Bercerita%')
            ->first()
            ?? Buku::with(['barcode', 'eksemplarTersedia'])->has('eksemplarTersedia')->first();

        $defaultEksemplar = $defaultBuku?->eksemplarTersedia?->first()
            ?? $defaultBuku?->eksemplar()->first();

        return view('peminjaman.create', compact('members', 'defaultMember', 'defaultBuku', 'defaultEksemplar'));
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
        if ($totalBuku > 7) {
            return back()->withErrors(['barcodes' => 'Gagal: Batas maksimal peminjaman adalah 7 buku.'])->withInput();
        }

        if ($totalBuku === 0) {
            return back()->withErrors(['barcodes' => 'Masukkan setidaknya 1 kode barcode buku.'])->withInput();
        }

        // Cek apakah member masih memiliki buku yang sedang dipinjam
        $bukuSedangDipinjam = DetailPeminjaman::whereHas('peminjaman', function ($q) use ($request) {
            $q->where('idUserMember', $request->idUserMember)->where('status', 'Dipinjam');
        })->where('statusBuku', 'Dipinjam')->count();

        if (($bukuSedangDipinjam + $totalBuku) > 7) {
            return back()->withErrors([
                'barcodes' => "Member saat ini masih meminjam {$bukuSedangDipinjam} buku. Total pinjaman aktif tidak boleh melebihi batas 7 buku.",
            ])->withInput();
        }

        // Cek duplikasi kode scan dalam 1 transaksi
        if (count($inputBarcodes) !== count(array_unique($inputBarcodes))) {
            return back()->withErrors(['barcodes' => 'Terdapat kode buku fisik yang di-scan lebih dari satu kali dalam transaksi yang sama.'])->withInput();
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
                $batasKembali = Carbon::now()->addMonths(Peminjaman::MASA_PINJAM_BULAN);

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
            $q->where('idUserMember', $user->id)->where('status', 'Dipinjam');
        })->where('statusBuku', 'Dipinjam')->count();

        $batasMaksimalBuku = Peminjaman::BATAS_MAKSIMAL_BUKU;
        $masaPinjamBulan = Peminjaman::MASA_PINJAM_BULAN;
        $durasiHari = $masaPinjamBulan * 30;

        $sisaKuota = max(0, $batasMaksimalBuku - $bukuSedangDipinjam);
        $kuotaHabis = $sisaKuota <= 0;

        $sedangPinjamBukuIni = DetailPeminjaman::whereHas('peminjaman', function ($q) use ($user) {
            $q->where('idUserMember', $user->id)->where('status', 'Dipinjam');
        })->where('idBuku', $buku->idBuku)->where('statusBuku', 'Dipinjam')->exists();

        $tanggalPinjam = Carbon::now();
        $batasKembali = Carbon::now()->addMonths($masaPinjamBulan);

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
            'sisaKuota',
            'kuotaHabis',
            'sedangPinjamBukuIni',
            'tanggalPinjam',
            'batasKembali'
        ));
    }

    /**
     * Proses pengajuan peminjaman mandiri oleh member.
     */
    public function ajukanMember(Request $request, $id)
    {
        $user = auth()->user();

        if ($user->status !== 'aktif') {
            return back()->with('error', 'Status keanggotaan Anda saat ini tidak aktif. Silakan hubungi petugas perpustakaan.');
        }

        $bukuSedangDipinjam = DetailPeminjaman::whereHas('peminjaman', function ($q) use ($user) {
            $q->where('idUserMember', $user->id)->where('status', 'Dipinjam');
        })->where('statusBuku', 'Dipinjam')->count();

        if ($bukuSedangDipinjam >= Peminjaman::BATAS_MAKSIMAL_BUKU) {
            return back()->with('error', 'Gagal mengajukan peminjaman: Anda telah mencapai batas maksimal '.Peminjaman::BATAS_MAKSIMAL_BUKU.' buku pinjaman aktif.');
        }

        $sedangPinjamBukuIni = DetailPeminjaman::whereHas('peminjaman', function ($q) use ($user) {
            $q->where('idUserMember', $user->id)->where('status', 'Dipinjam');
        })->where('idBuku', $id)->where('statusBuku', 'Dipinjam')->exists();

        if ($sedangPinjamBukuIni) {
            return back()->with('error', 'Anda saat ini sedang meminjam buku ini. Harap selesaikan pengembalian terlebih dahulu.');
        }

        try {
            $peminjaman = DB::transaction(function () use ($id, $user) {
                $buku = Buku::lockForUpdate()->findOrFail($id);

                $eksemplar = BukuEksemplar::where('idBuku', $buku->idBuku)
                    ->where('status', 'Tersedia')
                    ->lockForUpdate()
                    ->first();

                if (! $eksemplar || $buku->stok <= 0) {
                    throw new \DomainException('Maaf, eksemplar buku "'.$buku->judul.'" baru saja habis dipinjam pengguna lain.');
                }

                $tanggalPinjam = Carbon::now();
                $batasKembali = Carbon::now()->addMonths(Peminjaman::MASA_PINJAM_BULAN);

                $peminjaman = Peminjaman::create([
                    'idUserMember' => $user->id,
                    'idUserPetugas' => null,
                    'tanggalPinjam' => $tanggalPinjam->toDateString(),
                    'batasKembali' => $batasKembali->toDateString(),
                    'status' => 'Dipinjam',
                    'totalBuku' => 1,
                ]);

                DetailPeminjaman::create([
                    'idPeminjaman' => $peminjaman->idPeminjaman,
                    'idBuku' => $buku->idBuku,
                    'idEksemplar' => $eksemplar->idEksemplar,
                    'jumlah' => 1,
                    'statusBuku' => 'Dipinjam',
                ]);

                $eksemplar->update(['status' => 'Dipinjam']);

                $buku->syncStok();

                return $peminjaman;
            });

            return redirect()->route('riwayat.index')
                ->with('success', 'Peminjaman buku berhasil diajukan! Silakan ambil buku fisik di meja layanan perpustakaan.');
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses peminjaman: '.$e->getMessage());
        }
    }
}
