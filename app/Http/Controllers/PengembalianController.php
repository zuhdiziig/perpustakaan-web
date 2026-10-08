<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\Buku;
use App\Models\BukuEksemplar;
use App\Models\Denda;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    // Tampilkan daftar transaksi pengembalian
    public function index()
    {
        $pengembalians = Pengembalian::with(['peminjaman.member', 'petugas', 'denda'])
            ->latest('idPengembalian')
            ->paginate(10);

        return view('pengembalian.index', compact('pengembalians'));
    }

    // Tampilkan form proses pengembalian (scan barcode & cek kondisi)
    public function create(Request $request)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('dashboard')->with('error', 'Layanan meja sirkulasi pengembalian hanya diperuntukkan bagi Petugas Perpustakaan.');
        }

        $code = trim((string) $request->query('code', $request->query('barcode', $request->query('tiket', $request->query('kode', '')))));
        $selectedDetail = null;

        if (! empty($code)) {
            // 1. Cek kode kembali / qr token pengembalian
            $selectedDetail = DetailPeminjaman::with([
                'peminjaman.member',
                'buku.kategori',
                'buku.barcode',
                'eksemplar',
            ])
                ->where('qr_kembali', $code)
                ->orWhere('kode_kembali', $code)
                ->orWhere('kode_kembali', strtoupper($code))
                ->orWhere('kode_batch_kembali', $code)
                ->orWhere('kode_batch_kembali', strtoupper($code))
                ->first();

            // 2. Cek token / barcode eksemplar fisik buku
            if (! $selectedDetail) {
                $eksemplar = BukuEksemplar::where('qr_token', $code)
                    ->orWhere('kode_barcode', $code)
                    ->first();

                if ($eksemplar) {
                    $selectedDetail = DetailPeminjaman::with([
                        'peminjaman.member',
                        'buku.kategori',
                        'buku.barcode',
                        'eksemplar',
                    ])
                        ->where('idEksemplar', $eksemplar->idEksemplar)
                        ->whereIn('statusBuku', ['Dipinjam', 'Diajukan Kembali'])
                        ->first();
                }
            }

            // 3. Cek nomor transaksi peminjaman (PJ-..., TRX-..., numeric ID)
            if (! $selectedDetail) {
                $trxId = null;
                if (preg_match('/^PJ-\d{8}-(\d+)$/i', $code, $m)) {
                    $trxId = (int) $m[1];
                } elseif (preg_match('/^#?TRX-(\d+)$/i', $code, $m)) {
                    $trxId = (int) $m[1];
                } elseif (is_numeric($code)) {
                    $trxId = (int) $code;
                }

                if ($trxId) {
                    $selectedDetail = DetailPeminjaman::with([
                        'peminjaman.member',
                        'buku.kategori',
                        'buku.barcode',
                        'eksemplar',
                    ])
                        ->where('idPeminjaman', $trxId)
                        ->whereIn('statusBuku', ['Dipinjam', 'Diajukan Kembali'])
                        ->first();
                }
            }
        }

        // Jika tidak ada query atau belum ditemukan, ambil data pinjaman aktif terbaru (prioritas 'Diajukan Kembali')
        if (! $selectedDetail) {
            $selectedDetail = DetailPeminjaman::with([
                'peminjaman.member',
                'buku.kategori',
                'buku.barcode',
                'eksemplar',
            ])
                ->where('statusBuku', 'Diajukan Kembali')
                ->latest('updated_at')
                ->first()
                ?? DetailPeminjaman::with([
                    'peminjaman.member',
                    'buku.kategori',
                    'buku.barcode',
                    'eksemplar',
                ])
                    ->where('statusBuku', 'Dipinjam')
                    ->latest('id')
                    ->first();
        }

        // Kalkulasi keterlambatan & denda jika ada data detail
        $isOverdue = false;
        $hariTerlambat = 0;
        $mingguTerlambat = 0;
        $estDendaKeterlambatan = 0;
        $batasKembali = null;

        if ($selectedDetail && $selectedDetail->peminjaman) {
            $today = Carbon::now();
            $batasKembali = Carbon::parse($selectedDetail->peminjaman->batasKembali);
            $isOverdue = $today->greaterThan($batasKembali);
            $hariTerlambat = $isOverdue ? max(1, $batasKembali->diffInDays($today)) : 0;
            $mingguTerlambat = (int) ceil($hariTerlambat / 7);
            $faktorMinggu = min($mingguTerlambat, 10);
            $persenDenda = $faktorMinggu * 0.10;
            $hargaBuku = (float) ($selectedDetail->buku->harga ?? 0);
            $estDendaKeterlambatan = $isOverdue ? ($hargaBuku * $persenDenda) : 0;
        }

        return view('pengembalian.create', compact(
            'selectedDetail',
            'code',
            'isOverdue',
            'hariTerlambat',
            'mingguTerlambat',
            'estDendaKeterlambatan',
            'batasKembali'
        ));
    }

    // Proses konfirmasi & simpan pengembalian serta kalkulasi denda sesuai aturan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idPeminjaman' => ['required', 'exists:peminjaman,idPeminjaman'],
            'idBuku' => ['required', 'exists:buku,idBuku'],
            'idEksemplar' => ['nullable', 'exists:buku_eksemplar,idEksemplar'],
            'idUserMember' => ['nullable', 'exists:users,id'],
            'kondisiBuku' => ['required', 'in:Baik,Rusak,Hilang'],
        ]);

        try {
            $pengembalian = DB::transaction(function () use ($validated) {
                // 1. Lock transaksi peminjaman
                $peminjaman = Peminjaman::lockForUpdate()->findOrFail($validated['idPeminjaman']);

                // Validasi kepemilikan transaksi member jika idUserMember dikirimkan
                if (! empty($validated['idUserMember']) && (int) $peminjaman->idUserMember !== (int) $validated['idUserMember']) {
                    throw new \DomainException('Buku fisik ini sedang dipinjam oleh member lain, bukan member yang dipilih.');
                }

                // 2. Lock & cari DetailPeminjaman secara presisi
                $detailQuery = DetailPeminjaman::where('idPeminjaman', $peminjaman->idPeminjaman)->lockForUpdate();

                if (! empty($validated['idEksemplar'])) {
                    // Wajib mencari berdasarkan idEksemplar yang tepat
                    $detail = (clone $detailQuery)->where('idEksemplar', $validated['idEksemplar'])->first();
                } else {
                    // Historical fallback untuk transaksi lama yang belum memiliki idEksemplar
                    $detail = (clone $detailQuery)->where('idBuku', $validated['idBuku'])
                        ->whereNull('idEksemplar')
                        ->first();
                }

                if (! $detail) {
                    throw new \DomainException('Detail peminjaman buku fisik ini tidak ditemukan pada transaksi tersebut.');
                }

                // Tolak double-return
                if ($detail->statusBuku === 'Kembali') {
                    throw new \DomainException('Buku fisik ini sudah dikembalikan sebelumnya (double-return ditolak).');
                }

                // 3. Lock physical copy jika ada idEksemplar
                $eksemplar = null;
                if ($detail->idEksemplar) {
                    $eksemplar = BukuEksemplar::lockForUpdate()->find($detail->idEksemplar);
                    if (! $eksemplar) {
                        throw new \DomainException('Data eksemplar fisik tidak ditemukan.');
                    }
                    if ($eksemplar->status !== 'Dipinjam') {
                        throw new \DomainException("Eksemplar fisik buku ini tidak sedang berstatus Dipinjam (status saat ini: {$eksemplar->status}).");
                    }
                }

                $buku = Buku::lockForUpdate()->findOrFail($detail->idBuku);
                $tanggalKembali = Carbon::now();
                $batasKembali = Carbon::parse($peminjaman->batasKembali);
                $hargaBuku = (float) $buku->harga;

                // 1. Hitung Denda Keterlambatan (10% per minggu, maks 100% di minggu ke-10)
                $dendaKeterlambatan = 0;
                $mingguTerlambat = 0;
                $hariTerlambat = 0;

                if ($tanggalKembali->greaterThan($batasKembali)) {
                    $hariTerlambat = $batasKembali->diffInDays($tanggalKembali);
                    if ($hariTerlambat == 0) {
                        $hariTerlambat = 1;
                    }

                    $mingguTerlambat = (int) ceil($hariTerlambat / 7);
                    $faktorMinggu = min($mingguTerlambat, 10);
                    $persentaseDenda = $faktorMinggu * 0.10;
                    $dendaKeterlambatan = $hargaBuku * $persentaseDenda;
                }

                // 2. Hitung Denda Kerusakan / Kehilangan (100% dari harga buku)
                $dendaKondisi = 0;
                $jenisKondisi = null;

                if ($validated['kondisiBuku'] === 'Rusak') {
                    $dendaKondisi = $hargaBuku;
                    $jenisKondisi = 'Kerusakan (100% Harga Buku)';
                } elseif ($validated['kondisiBuku'] === 'Hilang') {
                    $dendaKondisi = $hargaBuku;
                    $jenisKondisi = 'Kehilangan (100% Harga Buku)';
                }

                $totalDenda = $dendaKeterlambatan + $dendaKondisi;

                // 3. Simpan Transaksi Pengembalian & Denda
                $pengembalian = Pengembalian::create([
                    'idPeminjaman' => $peminjaman->idPeminjaman,
                    'idUserPetugas' => auth()->id(),
                    'tanggalKembali' => $tanggalKembali->toDateString(),
                    'kondisiBuku' => $validated['kondisiBuku'],
                ]);

                if ($totalDenda > 0) {
                    $keteranganJenis = [];
                    if ($dendaKeterlambatan > 0) {
                        $persenTampil = min($mingguTerlambat * 10, 100);
                        $keteranganJenis[] = "Terlambat {$mingguTerlambat} Minggu ({$persenTampil}%)";
                    }
                    if ($dendaKondisi > 0) {
                        $keteranganJenis[] = $jenisKondisi;
                    }

                    Denda::create([
                        'idPengembalian' => $pengembalian->idPengembalian,
                        'jenisDenda' => implode(' + ', $keteranganJenis),
                        'jumlah' => $totalDenda,
                        'status' => 'Belum Dibayar',
                    ]);
                }

                // Update status detail peminjaman
                $detail->update(['statusBuku' => 'Kembali']);

                // Update status eksemplar fisik jika tercatat
                if ($eksemplar) {
                    $statusBaru = ($validated['kondisiBuku'] === 'Hilang') ? 'Hilang' : 'Tersedia';
                    $eksemplar->update([
                        'status' => $statusBaru,
                        'kondisi' => $validated['kondisiBuku'],
                    ]);
                }

                // Sinkronkan stok master buku secara akurat dari jumlah eksemplar yang Tersedia
                $buku->syncStok();
                $buku->update(['kondisi' => $validated['kondisiBuku']]);

                // Periksa apakah seluruh buku di peminjaman ini sudah tuntas dikembalikan
                $sisaBuku = DetailPeminjaman::where('idPeminjaman', $peminjaman->idPeminjaman)
                    ->where('statusBuku', '!=', 'Kembali')
                    ->count();

                if ($sisaBuku === 0) {
                    $peminjaman->update(['status' => 'Selesai']);
                }

                return $pengembalian;
            });
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('pengembalian.show', $pengembalian->idPengembalian)
            ->with('success', 'Buku berhasil dikembalikan dan denda telah dihitung.');
    }

    // Tampilkan rincian status pengembalian & total denda
    public function show($id)
    {
        $pengembalian = Pengembalian::with(['peminjaman.member', 'petugas', 'denda'])->findOrFail($id);

        return view('pengembalian.show', compact('pengembalian'));
    }

    /**
     * Tampilkan halaman utama pengembalian khusus member (layanan mandiri).
     */
    public function memberIndex(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;
        $tab = $request->query('tab', 'aktif'); // 'aktif', 'riwayat', 'semua'
        $highlightId = $request->query('highlight');

        // 1. Ambil seluruh buku yang sedang dipinjam (bisa dikembalikan)
        $pinjamanAktif = DetailPeminjaman::with([
            'buku.kategori',
            'buku.barcode',
            'eksemplar',
            'peminjaman.petugas',
        ])
            ->whereHas('peminjaman', function ($q) use ($userId) {
                $q->where('idUserMember', $userId)->where('status', 'Dipinjam');
            })
            ->whereIn('statusBuku', ['Dipinjam', 'Diajukan Kembali'])
            ->get();

        $today = Carbon::now();

        // Hitung status jatuh tempo dan estimasi denda
        $pinjamanAktif->transform(function ($item) use ($today) {
            $batasKembali = Carbon::parse($item->peminjaman->batasKembali);
            $isOverdue = $today->greaterThan($batasKembali);
            $hariTerlambat = $isOverdue ? max(1, $batasKembali->diffInDays($today)) : 0;
            $mingguTerlambat = (int) ceil($hariTerlambat / 7);
            $faktorMinggu = min($mingguTerlambat, 10);
            $persenDenda = $faktorMinggu * 0.10;
            $hargaBuku = (float) ($item->buku->harga ?? 0);
            $estDenda = $isOverdue ? ($hargaBuku * $persenDenda) : 0;
            $sisaHari = ! $isOverdue ? ceil($today->diffInDays($batasKembali, false)) : 0;

            $item->batasKembaliCarbon = $batasKembali;
            $item->isOverdue = $isOverdue;
            $item->hariTerlambat = $hariTerlambat;
            $item->mingguTerlambat = $mingguTerlambat;
            $item->estDenda = $estDenda;
            $item->sisaHari = $sisaHari;

            return $item;
        });

        // 2. Ambil riwayat pengembalian yang sudah tuntas
        $riwayatPengembalian = Pengembalian::with([
            'peminjaman.member',
            'peminjaman.details.buku',
            'peminjaman.details.eksemplar',
            'petugas',
            'denda',
        ])
            ->whereHas('peminjaman', function ($q) use ($userId) {
                $q->where('idUserMember', $userId);
            })
            ->latest('idPengembalian')
            ->paginate(10)
            ->withQueryString();

        // 3. Statistik ringkasan untuk member
        $totalPerluKembali = $pinjamanAktif->count();
        $totalSudahKembali = Pengembalian::whereHas('peminjaman', function ($q) use ($userId) {
            $q->where('idUserMember', $userId);
        })->count();

        $totalTepatWaktu = Pengembalian::whereHas('peminjaman', function ($q) use ($userId) {
            $q->where('idUserMember', $userId);
        })->whereDoesntHave('denda')->count();

        $totalDendaAktif = Denda::whereHas('pengembalian.peminjaman', function ($q) use ($userId) {
            $q->where('idUserMember', $userId);
        })->where('status', 'Belum Dibayar')->count();

        // QR Token member untuk verifikasi fisik di meja sirkulasi
        $memberQrToken = $user->qr_token;
        if (empty($memberQrToken)) {
            $memberQrToken = 'usr_'.bin2hex(random_bytes(16));
            $user->qr_token = $memberQrToken;
            $user->save();
        }

        return view('pengembalian.member', compact(
            'pinjamanAktif',
            'riwayatPengembalian',
            'totalPerluKembali',
            'totalSudahKembali',
            'totalTepatWaktu',
            'totalDendaAktif',
            'tab',
            'highlightId',
            'memberQrToken'
        ));
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
     * Pengajuan pengembalian buku mandiri oleh member (menghasilkan QR Tiket Pengembalian).
     */
    public function memberStore(Request $request, $idDetail)
    {
        $user = auth()->user();

        $request->validate([
            'kondisiBuku' => ['required', 'in:Baik,Rusak,Hilang'],
            'konfirmasi' => ['accepted'],
        ], [
            'kondisiBuku.required' => 'Pilih kondisi fisik buku yang dikembalikan.',
            'konfirmasi.accepted' => 'Harap centang konfirmasi penyerahan buku fisik.',
        ]);

        try {
            $detail = DB::transaction(function () use ($idDetail, $user, $request) {
                // 1. Kunci dan validasi detail peminjaman milik member
                $detail = DetailPeminjaman::with('peminjaman')
                    ->where('id', $idDetail)
                    ->lockForUpdate()
                    ->firstOrFail();

                $peminjaman = Peminjaman::lockForUpdate()->findOrFail($detail->idPeminjaman);

                if ((int) $peminjaman->idUserMember !== (int) $user->id) {
                    throw new \DomainException('Buku ini tidak tercatat dalam transaksi akun Anda.');
                }

                if ($detail->statusBuku === 'Kembali') {
                    throw new \DomainException('Buku ini sudah berstatus dikembalikan sebelumnya.');
                }

                // Generate kode kembali unik (format KB-YYYYMMDD-XXXX)
                $kodeKembali = $detail->kode_kembali ?: ('KB-'.Carbon::now()->format('Ymd').'-'.str_pad($detail->id, 4, '0', STR_PAD_LEFT));
                $qrKembali = $detail->qr_kembali ?: ('ret_'.bin2hex(random_bytes(16)));

                $detail->update([
                    'statusBuku' => 'Diajukan Kembali',
                    'kode_kembali' => $kodeKembali,
                    'qr_kembali' => $qrKembali,
                    'kondisi_laporan' => $request->input('kondisiBuku', 'Baik'),
                    'waktu_pengajuan_kembali' => Carbon::now(),
                ]);

                return $detail;
            });

            return redirect()->route('pengembalian.member.tiket', $detail->id)
                ->with('success', 'Pengajuan pengembalian berhasil! Tunjukkan QR Code tiket ini kepada petugas perpustakaan di meja sirkulasi.');
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses pengajuan pengembalian: '.$e->getMessage());
        }
    }

    /**
     * Halaman Tiket QR Code Pengembalian untuk Member.
     */
    public function memberTiket($idDetail)
    {
        $user = auth()->user();

        $detail = DetailPeminjaman::with([
            'peminjaman.member',
            'buku.kategori',
            'buku.barcode',
            'eksemplar',
        ])->findOrFail($idDetail);

        if ($user && $user->role === 'member' && (int) $detail->peminjaman->idUserMember !== (int) $user->id) {
            abort(403, 'Anda tidak memiliki hak akses melihat tiket pengembalian ini.');
        }

        // Pastikan kode_kembali dan qr_kembali tersedia
        if (empty($detail->kode_kembali)) {
            $detail->kode_kembali = 'KB-'.Carbon::now()->format('Ymd').'-'.str_pad($detail->id, 4, '0', STR_PAD_LEFT);
            $detail->qr_kembali = 'ret_'.bin2hex(random_bytes(16));
            $detail->statusBuku = 'Diajukan Kembali';
            $detail->waktu_pengajuan_kembali = Carbon::now();
            $detail->save();
        }

        // Kalkulasi keterlambatan & estimasi denda
        $today = Carbon::now();
        $batasKembali = Carbon::parse($detail->peminjaman->batasKembali);
        $isOverdue = $today->greaterThan($batasKembali);
        $hariTerlambat = $isOverdue ? max(1, $batasKembali->diffInDays($today)) : 0;
        $mingguTerlambat = (int) ceil($hariTerlambat / 7);
        $faktorMinggu = min($mingguTerlambat, 10);
        $persenDenda = $faktorMinggu * 0.10;
        $hargaBuku = (float) ($detail->buku->harga ?? 0);
        $estDenda = $isOverdue ? ($hargaBuku * $persenDenda) : 0;

        $qrPayload = $detail->qr_kembali ?: $detail->kode_kembali;
        $qrCodeSvg = $this->generateSvgQr($qrPayload, 220);

        return view('pengembalian.member_tiket', compact(
            'detail',
            'qrCodeSvg',
            'isOverdue',
            'hariTerlambat',
            'mingguTerlambat',
            'estDenda',
            'batasKembali'
        ));
    }

    /**
     * Pengajuan pengembalian buku sekaligus (batch) mandiri oleh member.
     */
    public function memberBatchStore(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'detail_ids' => ['required', 'array', 'min:1'],
            'detail_ids.*' => ['required', 'integer'],
            'kondisi' => ['required', 'array'],
            'kondisi.*' => ['required', 'in:Baik,Rusak,Hilang'],
            'konfirmasi' => ['accepted'],
        ], [
            'detail_ids.required' => 'Pilih minimal satu buku yang ingin dikembalikan.',
            'detail_ids.min' => 'Pilih minimal satu buku yang ingin dikembalikan.',
            'kondisi.*.in' => 'Pilihan kondisi buku harus berupa Baik, Rusak, atau Hilang.',
            'konfirmasi.accepted' => 'Harap centang konfirmasi penyerahan buku fisik.',
        ]);

        try {
            $kodeBatch = DB::transaction(function () use ($request, $user) {
                $detailIds = $request->input('detail_ids', []);
                $kondisiMap = $request->input('kondisi', []);

                $details = DetailPeminjaman::with(['peminjaman', 'buku'])
                    ->whereIn('id', $detailIds)
                    ->lockForUpdate()
                    ->get();

                if ($details->isEmpty()) {
                    throw new \DomainException('Tidak ada data buku yang valid untuk diproses.');
                }

                $batchCode = 'KB-BATCH-'.Carbon::now()->format('Ymd').'-'.strtoupper(bin2hex(random_bytes(3)));

                foreach ($details as $detail) {
                    if ((int) $detail->peminjaman->idUserMember !== (int) $user->id) {
                        throw new \DomainException('Buku "'.($detail->buku?->judul ?? 'Buku').'" tidak tercatat dalam transaksi akun Anda.');
                    }

                    if ($detail->statusBuku === 'Kembali') {
                        throw new \DomainException('Buku "'.($detail->buku?->judul ?? 'Buku').'" sudah berstatus dikembalikan sebelumnya.');
                    }

                    $kondisiDipilih = $kondisiMap[$detail->id] ?? 'Baik';
                    if (! in_array($kondisiDipilih, ['Baik', 'Rusak', 'Hilang'])) {
                        $kondisiDipilih = 'Baik';
                    }

                    $kodeKembali = $detail->kode_kembali ?: ('KB-'.Carbon::now()->format('Ymd').'-'.str_pad($detail->id, 4, '0', STR_PAD_LEFT));
                    $qrKembali = $detail->qr_kembali ?: ('ret_'.bin2hex(random_bytes(16)));

                    $detail->update([
                        'statusBuku' => 'Diajukan Kembali',
                        'kode_kembali' => $kodeKembali,
                        'qr_kembali' => $qrKembali,
                        'kode_batch_kembali' => $batchCode,
                        'kondisi_laporan' => $kondisiDipilih,
                        'waktu_pengajuan_kembali' => Carbon::now(),
                    ]);
                }

                return $batchCode;
            });

            return redirect()->route('pengembalian.member.batch-tiket', $kodeBatch)
                ->with('success', 'Pengajuan pengembalian sekaligus berhasil dibuat! Tunjukkan QR Code tiket ini ke petugas meja sirkulasi.');
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses pengembalian sekaligus: '.$e->getMessage());
        }
    }

    /**
     * Halaman Tiket QR Code Pengembalian Sekaligus (Batch) untuk Member.
     */
    public function memberBatchTiket($kodeBatch)
    {
        $user = auth()->user();

        $details = DetailPeminjaman::with([
            'peminjaman.member',
            'buku.kategori',
            'buku.barcode',
            'eksemplar',
        ])
            ->where('kode_batch_kembali', $kodeBatch)
            ->get();

        if ($details->isEmpty()) {
            abort(404, 'Tiket pengembalian sekaligus tidak ditemukan.');
        }

        $firstDetail = $details->first();
        if ($user && $user->role === 'member' && (int) $firstDetail->peminjaman->idUserMember !== (int) $user->id) {
            abort(403, 'Anda tidak memiliki hak akses melihat tiket pengembalian ini.');
        }

        $today = Carbon::now();
        $totalEstDendaTelat = 0;
        $totalEstDendaKondisi = 0;
        $totalBuku = $details->count();
        $countBaik = 0;
        $countRusak = 0;
        $countHilang = 0;

        foreach ($details as $item) {
            $batasKembali = Carbon::parse($item->peminjaman->batasKembali);
            $isOverdue = $today->greaterThan($batasKembali);
            $hariTerlambat = $isOverdue ? max(1, $batasKembali->diffInDays($today)) : 0;
            $mingguTerlambat = (int) ceil($hariTerlambat / 7);
            $faktorMinggu = min($mingguTerlambat, 10);
            $persenDenda = $faktorMinggu * 0.10;
            $hargaBuku = (float) ($item->buku->harga ?? 0);
            $dendaTelat = $isOverdue ? ($hargaBuku * $persenDenda) : 0;

            $dendaKondisi = 0;
            if ($item->kondisi_laporan === 'Rusak') {
                $dendaKondisi = $hargaBuku;
                $countRusak++;
            } elseif ($item->kondisi_laporan === 'Hilang') {
                $dendaKondisi = $hargaBuku;
                $countHilang++;
            } else {
                $countBaik++;
            }

            $item->batasKembaliCarbon = $batasKembali;
            $item->isOverdue = $isOverdue;
            $item->hariTerlambat = $hariTerlambat;
            $item->dendaTelat = $dendaTelat;
            $item->dendaKondisi = $dendaKondisi;
            $item->totalDendaItem = $dendaTelat + $dendaKondisi;

            $totalEstDendaTelat += $dendaTelat;
            $totalEstDendaKondisi += $dendaKondisi;
        }

        $totalEstDenda = $totalEstDendaTelat + $totalEstDendaKondisi;
        $qrCodeSvg = $this->generateSvgQr($kodeBatch, 220);

        return view('pengembalian.member_batch_tiket', compact(
            'details',
            'kodeBatch',
            'qrCodeSvg',
            'totalBuku',
            'countBaik',
            'countRusak',
            'countHilang',
            'totalEstDendaTelat',
            'totalEstDendaKondisi',
            'totalEstDenda'
        ));
    }

    /**
     * Batalkan seluruh pengajuan pengembalian dalam satu batch.
     */
    public function memberBatchBatal($kodeBatch)
    {
        $user = auth()->user();

        $details = DetailPeminjaman::with('peminjaman')
            ->where('kode_batch_kembali', $kodeBatch)
            ->get();

        if ($details->isEmpty()) {
            return redirect()->route('pengembalian.member')->with('error', 'Tiket batch pengembalian tidak ditemukan.');
        }

        foreach ($details as $detail) {
            if ($user && $user->role === 'member' && (int) $detail->peminjaman->idUserMember !== (int) $user->id) {
                abort(403, 'Akses ditolak.');
            }

            if ($detail->statusBuku === 'Diajukan Kembali') {
                $detail->update([
                    'statusBuku' => 'Dipinjam',
                    'kode_kembali' => null,
                    'qr_kembali' => null,
                    'kode_batch_kembali' => null,
                    'kondisi_laporan' => 'Baik',
                    'waktu_pengajuan_kembali' => null,
                ]);
            }
        }

        return redirect()->route('pengembalian.member')
            ->with('success', 'Pengajuan pengembalian sekaligus ('.$details->count().' buku) berhasil dibatalkan. Status buku kembali menjadi "Dipinjam".');
    }

    /**
     * Batalkan pengajuan pengembalian buku oleh member.
     */
    public function memberBatal(Request $request, $idDetail)
    {
        $user = auth()->user();

        $detail = DetailPeminjaman::with('peminjaman')->findOrFail($idDetail);

        if ($user && $user->role === 'member' && (int) $detail->peminjaman->idUserMember !== (int) $user->id) {
            abort(403, 'Akses ditolak.');
        }

        if ($detail->statusBuku !== 'Diajukan Kembali') {
            return back()->with('error', 'Pengajuan tidak dapat dibatalkan karena status buku saat ini: '.$detail->statusBuku);
        }

        $detail->update([
            'statusBuku' => 'Dipinjam',
            'kode_kembali' => null,
            'qr_kembali' => null,
            'kode_batch_kembali' => null,
            'kondisi_laporan' => 'Baik',
            'waktu_pengajuan_kembali' => null,
        ]);

        return redirect()->route('pengembalian.member')
            ->with('success', 'Pengajuan pengembalian buku berhasil dibatalkan. Status buku kembali "Dipinjam".');
    }

    /**
     * Tampilkan tanda terima resmi pengembalian buku untuk member.
     */
    public function memberBukti($id)
    {
        $user = auth()->user();

        $pengembalian = Pengembalian::with([
            'peminjaman.member',
            'peminjaman.details.buku.kategori',
            'peminjaman.details.eksemplar',
            'petugas',
            'denda',
        ])->findOrFail($id);

        // Validasi hak akses kepemilikan transaksi
        if ($user->role === 'member' && (int) $pengembalian->peminjaman->idUserMember !== (int) $user->id) {
            abort(403, 'Anda tidak berhak melihat tanda terima pengembalian milik member lain.');
        }

        return view('pengembalian.member_bukti', compact('pengembalian'));
    }
}
