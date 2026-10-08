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

        $batchDetails = null;
        if ($selectedDetail && ! empty($selectedDetail->kode_batch_kembali)) {
            $batchDetails = DetailPeminjaman::with([
                'peminjaman.member',
                'buku.kategori',
                'buku.barcode',
                'eksemplar',
                'denda',
            ])
                ->where('kode_batch_kembali', $selectedDetail->kode_batch_kembali)
                ->get();
        }

        return view('pengembalian.create', compact(
            'selectedDetail',
            'batchDetails',
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
                $existingDenda = $detail->id_denda ? Denda::find($detail->id_denda) : null;

                if ($existingDenda && $existingDenda->idPengembalian) {
                    $pengembalian = Pengembalian::find($existingDenda->idPengembalian);
                    if ($pengembalian) {
                        $pengembalian->update([
                            'idUserPetugas' => auth()->id(),
                            'tanggalKembali' => $tanggalKembali->toDateString(),
                            'kondisiBuku' => $validated['kondisiBuku'],
                        ]);
                    } else {
                        $pengembalian = Pengembalian::create([
                            'idPeminjaman' => $peminjaman->idPeminjaman,
                            'idUserPetugas' => auth()->id(),
                            'tanggalKembali' => $tanggalKembali->toDateString(),
                            'kondisiBuku' => $validated['kondisiBuku'],
                        ]);
                        $existingDenda->update(['idPengembalian' => $pengembalian->idPengembalian]);
                    }
                } else {
                    $pengembalian = Pengembalian::create([
                        'idPeminjaman' => $peminjaman->idPeminjaman,
                        'idUserPetugas' => auth()->id(),
                        'tanggalKembali' => $tanggalKembali->toDateString(),
                        'kondisiBuku' => $validated['kondisiBuku'],
                    ]);

                    if ($existingDenda) {
                        $existingDenda->update(['idPengembalian' => $pengembalian->idPengembalian]);
                    } elseif ($totalDenda > 0) {
                        $keteranganJenis = [];
                        if ($dendaKeterlambatan > 0) {
                            $persenTampil = min($mingguTerlambat * 10, 100);
                            $keteranganJenis[] = "Terlambat {$mingguTerlambat} Minggu ({$persenTampil}%)";
                        }
                        if ($dendaKondisi > 0) {
                            $keteranganJenis[] = $jenisKondisi;
                        }

                        $dendaBaru = Denda::create([
                            'idPengembalian' => $pengembalian->idPengembalian,
                            'jenisDenda' => implode(' + ', $keteranganJenis),
                            'jumlah' => $totalDenda,
                            'status' => 'Belum Dibayar',
                        ]);
                        $detail->update(['id_denda' => $dendaBaru->idDenda]);
                    }
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
        $pengembalian = Pengembalian::with([
            'peminjaman.member',
            'peminjaman.details.buku.barcode',
            'peminjaman.details.buku.kategori',
            'peminjaman.details.eksemplar',
            'petugas',
            'denda',
        ])->findOrFail($id);

        $detailBuku = null;
        if ($pengembalian->denda) {
            $detailBuku = DetailPeminjaman::with(['buku.barcode', 'eksemplar'])
                ->where('id_denda', $pengembalian->denda->idDenda)
                ->first();
        }

        $batchDetails = null;
        $kodeBatch = null;

        if ($detailBuku && ! empty($detailBuku->kode_batch_kembali)) {
            $kodeBatch = $detailBuku->kode_batch_kembali;
            $batchDetails = DetailPeminjaman::with(['buku.barcode', 'eksemplar', 'denda'])
                ->where('kode_batch_kembali', $kodeBatch)
                ->get();
        } else {
            $firstDetail = DetailPeminjaman::with(['buku.barcode', 'eksemplar', 'denda'])
                ->where('idPeminjaman', $pengembalian->idPeminjaman)
                ->where('statusBuku', 'Kembali')
                ->latest('updated_at')
                ->first();

            if ($firstDetail && ! empty($firstDetail->kode_batch_kembali)) {
                $kodeBatch = $firstDetail->kode_batch_kembali;
                $batchDetails = DetailPeminjaman::with(['buku.barcode', 'eksemplar', 'denda'])
                    ->where('kode_batch_kembali', $kodeBatch)
                    ->get();
            }

            if (! $detailBuku) {
                $detailBuku = $firstDetail;
            }
        }

        return view('pengembalian.show', compact('pengembalian', 'detailBuku', 'batchDetails', 'kodeBatch'));
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
            'denda',
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
            $result = DB::transaction(function () use ($idDetail, $user, $request) {
                // 1. Kunci dan validasi detail peminjaman milik member
                $detail = DetailPeminjaman::with(['peminjaman', 'buku', 'denda'])
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

                $buku = Buku::findOrFail($detail->idBuku);
                $kondisi = $request->input('kondisiBuku', 'Baik');

                // Generate kode kembali unik (format KB-YYYYMMDD-XXXX)
                $kodeKembali = $detail->kode_kembali ?: ('KB-'.Carbon::now()->format('Ymd').'-'.str_pad($detail->id, 4, '0', STR_PAD_LEFT));
                $qrKembali = $detail->qr_kembali ?: ('ret_'.bin2hex(random_bytes(16)));

                $detail->update([
                    'statusBuku' => 'Diajukan Kembali',
                    'kode_kembali' => $kodeKembali,
                    'qr_kembali' => $qrKembali,
                    'kondisi_laporan' => $kondisi,
                    'waktu_pengajuan_kembali' => Carbon::now(),
                ]);

                // Hitung denda keterlambatan (10% per minggu dari harga buku)
                $today = Carbon::now();
                $batasKembali = Carbon::parse($peminjaman->batasKembali);
                $dendaKeterlambatan = 0;
                $mingguTerlambat = 0;
                if ($today->greaterThan($batasKembali)) {
                    $hariTerlambat = max(1, $batasKembali->diffInDays($today));
                    $mingguTerlambat = (int) ceil($hariTerlambat / 7);
                    $faktorMinggu = min($mingguTerlambat, 10);
                    $persentaseDenda = $faktorMinggu * 0.10;
                    $dendaKeterlambatan = (float) $buku->harga * $persentaseDenda;
                }

                // Hitung denda kondisi (100% harga buku jika Rusak atau Hilang)
                $dendaKondisi = 0;
                $jenisKondisi = null;
                if ($kondisi === 'Rusak') {
                    $dendaKondisi = (float) $buku->harga;
                    $jenisKondisi = 'Kerusakan (100% Harga Buku)';
                } elseif ($kondisi === 'Hilang') {
                    $dendaKondisi = (float) $buku->harga;
                    $jenisKondisi = 'Kehilangan (100% Harga Buku)';
                }

                $totalDenda = $dendaKeterlambatan + $dendaKondisi;
                $denda = null;

                if ($totalDenda > 0) {
                    $keteranganJenis = [];
                    if ($dendaKeterlambatan > 0) {
                        $persenTampil = min($mingguTerlambat * 10, 100);
                        $keteranganJenis[] = "Terlambat {$mingguTerlambat} Minggu ({$persenTampil}%)";
                    }
                    if ($dendaKondisi > 0) {
                        $keteranganJenis[] = $jenisKondisi;
                    }

                    if ($detail->id_denda) {
                        $denda = Denda::find($detail->id_denda);
                    }

                    if (! $denda) {
                        // Buat record pre-pengembalian (idUserPetugas = null hingga disahkan di meja sirkulasi)
                        $pengembalian = Pengembalian::create([
                            'idPeminjaman' => $peminjaman->idPeminjaman,
                            'idUserPetugas' => null,
                            'tanggalKembali' => Carbon::now()->toDateString(),
                            'kondisiBuku' => $kondisi,
                        ]);

                        $denda = Denda::create([
                            'idPengembalian' => $pengembalian->idPengembalian,
                            'jenisDenda' => implode(' + ', $keteranganJenis),
                            'jumlah' => $totalDenda,
                            'status' => 'Belum Dibayar',
                        ]);

                        $detail->update(['id_denda' => $denda->idDenda]);
                    } else {
                        if ($denda->status === 'Belum Dibayar') {
                            $denda->update([
                                'jenisDenda' => implode(' + ', $keteranganJenis),
                                'jumlah' => $totalDenda,
                            ]);
                        }
                    }
                }

                return [
                    'detail' => $detail,
                    'totalDenda' => $totalDenda,
                    'denda' => $denda,
                ];
            });

            $detail = $result['detail'];
            $totalDenda = $result['totalDenda'];
            $denda = $result['denda'];

            // Jika ada denda dan belum dibayar, redirect terlebih dahulu ke pembayaran QRIS
            if ($denda && $denda->status === 'Belum Dibayar') {
                return redirect()->route('bayar.qr', $denda->idDenda)
                    ->with('info', 'Buku yang dikembalikan memiliki tagihan denda sebesar Rp '.number_format($totalDenda, 0, ',', '.').'. Sesuai ketentuan, denda wajib dibayar melalui QRIS terlebih dahulu sebelum tiket pengembalian diterbitkan.');
            }

            return redirect()->route('pengembalian.member')
                ->with('success', 'Pengajuan pengembalian berhasil dicatat! Silakan bawa buku ke perpustakaan dan tunjukkan Kartu / QR Anggota Anda kepada petugas di meja sirkulasi.');
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
            'denda',
        ])->findOrFail($idDetail);

        if ($user && $user->role === 'member' && (int) $detail->peminjaman->idUserMember !== (int) $user->id) {
            abort(403, 'Anda tidak memiliki hak akses melihat tiket pengembalian ini.');
        }

        // WAJIB: Jika terdapat denda yang BELUM DIBAYAR, blokir tiket dan alihkan ke pembayaran QRIS
        if ($detail->denda && $detail->denda->status === 'Belum Dibayar') {
            return redirect()->route('bayar.qr', $detail->denda->idDenda)
                ->with('error', 'Tiket pengembalian fisik belum dapat diterbitkan. Harap selesaikan pembayaran denda sebesar Rp '.number_format($detail->denda->jumlah, 0, ',', '.').' via QRIS terlebih dahulu.');
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
        $isCompleted = ($detail->statusBuku === 'Kembali');

        return view('pengembalian.member_tiket', compact(
            'detail',
            'qrCodeSvg',
            'isOverdue',
            'hariTerlambat',
            'mingguTerlambat',
            'estDenda',
            'batasKembali',
            'isCompleted'
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
            $result = DB::transaction(function () use ($request, $user) {
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
                $totalBatchDenda = 0;
                $today = Carbon::now();

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

                    $kodeKembali = $batchCode;
                    $qrKembali = $batchCode;

                    $detail->update([
                        'statusBuku' => 'Diajukan Kembali',
                        'kode_kembali' => $kodeKembali,
                        'qr_kembali' => $qrKembali,
                        'kode_batch_kembali' => $batchCode,
                        'kondisi_laporan' => $kondisiDipilih,
                        'waktu_pengajuan_kembali' => Carbon::now(),
                    ]);

                    // Hitung denda keterlambatan
                    $batasKembali = Carbon::parse($detail->peminjaman->batasKembali);
                    $dendaTelat = 0;
                    if ($today->greaterThan($batasKembali)) {
                        $hariTerlambat = max(1, $batasKembali->diffInDays($today));
                        $mingguTerlambat = (int) ceil($hariTerlambat / 7);
                        $faktorMinggu = min($mingguTerlambat, 10);
                        $dendaTelat = (float) ($detail->buku->harga ?? 0) * ($faktorMinggu * 0.10);
                    }

                    // Hitung denda kondisi
                    $dendaKondisi = 0;
                    if ($kondisiDipilih === 'Rusak' || $kondisiDipilih === 'Hilang') {
                        $dendaKondisi = (float) ($detail->buku->harga ?? 0);
                    }

                    $totalBatchDenda += ($dendaTelat + $dendaKondisi);
                }

                $denda = null;
                if ($totalBatchDenda > 0) {
                    $firstDetail = $details->first();
                    $prePengembalian = Pengembalian::create([
                        'idPeminjaman' => $firstDetail->idPeminjaman,
                        'idUserPetugas' => null,
                        'tanggalKembali' => Carbon::now()->toDateString(),
                        'kondisiBuku' => (count(array_unique($kondisiMap)) === 1 ? reset($kondisiMap) : 'Baik'),
                    ]);

                    $denda = Denda::create([
                        'idPengembalian' => $prePengembalian->idPengembalian,
                        'jenisDenda' => "Denda Pengembalian Sekaligus ({$batchCode})",
                        'jumlah' => $totalBatchDenda,
                        'status' => 'Belum Dibayar',
                    ]);

                    foreach ($details as $detail) {
                        $detail->update(['id_denda' => $denda->idDenda]);
                    }
                }

                return [
                    'batchCode' => $batchCode,
                    'totalBatchDenda' => $totalBatchDenda,
                    'denda' => $denda,
                ];
            });

            $batchCode = $result['batchCode'];
            $totalBatchDenda = $result['totalBatchDenda'];
            $denda = $result['denda'];

            if ($denda && $denda->status === 'Belum Dibayar') {
                return redirect()->route('bayar.qr', $denda->idDenda)
                    ->with('info', 'Terdapat denda pengembalian sekaligus sebesar Rp '.number_format($totalBatchDenda, 0, ',', '.').'. Sesuai ketentuan, denda wajib dibayar melalui QRIS terlebih dahulu sebelum tiket batch diterbitkan.');
            }

            return redirect()->route('pengembalian.member')
                ->with('success', 'Pengajuan pengembalian sekaligus berhasil dibuat! Silakan bawa buku ke perpustakaan dan tunjukkan Kartu / QR Anggota Anda ke petugas meja sirkulasi.');
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
            'denda',
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

        // WAJIB: Jika terdapat denda yang BELUM DIBAYAR, blokir tiket batch dan alihkan ke pembayaran QRIS
        $unpaidDetail = $details->first(function ($item) {
            return $item->denda && $item->denda->status === 'Belum Dibayar';
        });

        if ($unpaidDetail) {
            return redirect()->route('bayar.qr', $unpaidDetail->denda->idDenda)
                ->with('error', 'Tiket pengembalian fisik sekaligus belum dapat diterbitkan. Harap selesaikan pembayaran denda sebesar Rp '.number_format($unpaidDetail->denda->jumlah, 0, ',', '.').' via QRIS terlebih dahulu.');
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
        $isCompleted = $details->isNotEmpty() && $details->every(fn ($item) => $item->statusBuku === 'Kembali');

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
            'totalEstDenda',
            'isCompleted'
        ));
    }

    /**
     * Cek status realtime tiket pengembalian (polling AJAX untuk notifikasi pop-up member).
     */
    public function apiCheckStatusTiket(Request $request, string $kode)
    {
        $user = auth()->user();

        $query = DetailPeminjaman::with(['peminjaman.member', 'buku', 'eksemplar']);

        if (is_numeric($kode)) {
            $details = $query->where('id', (int) $kode)
                ->orWhere('kode_kembali', $kode)
                ->orWhere('kode_batch_kembali', $kode)
                ->get();
        } else {
            $details = $query->where('kode_batch_kembali', $kode)
                ->orWhere('kode_kembali', $kode)
                ->orWhere('qr_kembali', $kode)
                ->get();
        }

        if ($details->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket pengembalian tidak ditemukan.',
            ], 404);
        }

        $first = $details->first();
        if ($user && $user->role === 'member' && (int) $first->peminjaman->idUserMember !== (int) $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak akses melihat tiket ini.',
            ], 403);
        }

        $totalBuku = $details->count();
        $completedCount = $details->where('statusBuku', 'Kembali')->count();
        $isCompleted = ($completedCount === $totalBuku && $totalBuku > 0);

        if ($isCompleted) {
            $pengembalian = Pengembalian::with('petugas')
                ->where('idPeminjaman', $first->idPeminjaman)
                ->latest('idPengembalian')
                ->first();

            $namaPetugas = $pengembalian?->petugas?->name ?? 'Petugas Meja Sirkulasi';
            $waktuSelesai = $pengembalian?->updated_at ? $pengembalian->updated_at->translatedFormat('d M Y, H:i') : Carbon::now()->translatedFormat('d M Y, H:i');

            return response()->json([
                'success' => true,
                'completed' => true,
                'totalBuku' => $totalBuku,
                'kodeTiket' => $first->kode_batch_kembali ?: $first->kode_kembali,
                'namaPetugas' => $namaPetugas,
                'waktuSelesai' => $waktuSelesai,
                'idPengembalian' => $pengembalian?->idPengembalian,
                'message' => 'Pengembalian buku berhasil diverifikasi dan diselesaikan oleh petugas!',
            ]);
        }

        return response()->json([
            'success' => true,
            'completed' => false,
            'totalBuku' => $totalBuku,
            'completedCount' => $completedCount,
            'status' => 'Menunggu Scan Petugas',
        ]);
    }

    /**
     * Batalkan seluruh pengajuan pengembalian dalam satu batch.
     */
    public function memberBatchBatal($kodeBatch)
    {
        $user = auth()->user();

        $details = DetailPeminjaman::with(['peminjaman', 'denda'])
            ->where('kode_batch_kembali', $kodeBatch)
            ->get();

        if ($details->isEmpty()) {
            return redirect()->route('pengembalian.member')->with('error', 'Tiket batch pengembalian tidak ditemukan.');
        }

        $hasPaidDenda = $details->contains(function ($item) {
            return $item->denda && $item->denda->status === 'Lunas';
        });

        if ($hasPaidDenda) {
            return redirect()->route('pengembalian.member')
                ->with('error', 'Pengajuan pengembalian sekaligus tidak dapat dibatalkan karena denda telah dibayar secara online. Silakan hubungi petugas meja sirkulasi.');
        }

        $dendaIdsToDelete = [];

        foreach ($details as $detail) {
            if ($user && $user->role === 'member' && (int) $detail->peminjaman->idUserMember !== (int) $user->id) {
                abort(403, 'Akses ditolak.');
            }

            if ($detail->id_denda) {
                $dendaIdsToDelete[] = $detail->id_denda;
            }

            if ($detail->statusBuku === 'Diajukan Kembali') {
                $detail->update([
                    'statusBuku' => 'Dipinjam',
                    'kode_kembali' => null,
                    'qr_kembali' => null,
                    'kode_batch_kembali' => null,
                    'kondisi_laporan' => 'Baik',
                    'waktu_pengajuan_kembali' => null,
                    'id_denda' => null,
                ]);
            }
        }

        foreach (array_unique($dendaIdsToDelete) as $dendaId) {
            $denda = Denda::find($dendaId);
            if ($denda && $denda->status === 'Belum Dibayar') {
                $prePengembalian = $denda->pengembalian;
                $denda->delete();
                if ($prePengembalian && $prePengembalian->idUserPetugas === null) {
                    $prePengembalian->delete();
                }
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

        $detail = DetailPeminjaman::with(['peminjaman', 'denda'])->findOrFail($idDetail);

        if ($user && $user->role === 'member' && (int) $detail->peminjaman->idUserMember !== (int) $user->id) {
            abort(403, 'Akses ditolak.');
        }

        if ($detail->statusBuku !== 'Diajukan Kembali') {
            return back()->with('error', 'Pengajuan tidak dapat dibatalkan karena status buku saat ini: '.$detail->statusBuku);
        }

        if ($detail->denda && $detail->denda->status === 'Lunas') {
            return back()->with('error', 'Pengajuan pengembalian tidak dapat dibatalkan karena denda telah dibayar secara online. Silakan hubungi petugas perpustakaan.');
        }

        $dendaId = $detail->id_denda;

        $detail->update([
            'statusBuku' => 'Dipinjam',
            'kode_kembali' => null,
            'qr_kembali' => null,
            'kode_batch_kembali' => null,
            'kondisi_laporan' => 'Baik',
            'waktu_pengajuan_kembali' => null,
            'id_denda' => null,
        ]);

        if ($dendaId) {
            $denda = Denda::find($dendaId);
            if ($denda && $denda->status === 'Belum Dibayar') {
                $prePengembalian = $denda->pengembalian;
                $denda->delete();
                if ($prePengembalian && $prePengembalian->idUserPetugas === null) {
                    $prePengembalian->delete();
                }
            }
        }

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

    /**
     * Selesaikan pengembalian seluruh buku dalam tiket batch sekaligus oleh petugas meja sirkulasi.
     */
    public function petugasBatchStore(Request $request)
    {
        $request->validate([
            'kodeBatch' => ['required', 'string'],
            'kondisi' => ['nullable', 'array'],
            'kondisi.*' => ['nullable', 'in:Baik,Rusak,Hilang'],
        ]);

        $kodeBatch = trim($request->input('kodeBatch'));
        $kondisiMap = $request->input('kondisi', []);

        try {
            $result = DB::transaction(function () use ($kodeBatch, $kondisiMap) {
                $details = DetailPeminjaman::with(['peminjaman', 'buku', 'eksemplar', 'denda'])
                    ->where('kode_batch_kembali', $kodeBatch)
                    ->orWhere('kode_kembali', $kodeBatch)
                    ->lockForUpdate()
                    ->get();

                if ($details->isEmpty()) {
                    throw new \DomainException("Data tiket pengembalian sekaligus '{$kodeBatch}' tidak ditemukan.");
                }

                $pendingDetails = $details->where('statusBuku', '!=', 'Kembali');
                if ($pendingDetails->isEmpty()) {
                    throw new \DomainException("Seluruh buku pada tiket '{$kodeBatch}' sudah dikembalikan sebelumnya.");
                }

                // Cek jika ada denda yang BELUM DIBAYAR
                $hasUnpaidFine = $pendingDetails->contains(function ($item) {
                    return $item->denda && $item->denda->status === 'Belum Dibayar';
                });

                if ($hasUnpaidFine) {
                    throw new \DomainException('Terdapat denda pengembalian yang belum dibayar. Minta anggota menyelesaikan pembayaran denda terlebih dahulu sebelum pengembalian diselesaikan.');
                }

                $tanggalKembali = Carbon::now();
                $petugasId = auth()->id();
                $peminjamanIds = [];

                $firstPengembalianId = null;

                foreach ($pendingDetails as $detail) {
                    $peminjaman = Peminjaman::lockForUpdate()->find($detail->idPeminjaman);
                    if ($peminjaman) {
                        $peminjamanIds[$detail->idPeminjaman] = $peminjaman;
                    }

                    $buku = Buku::lockForUpdate()->find($detail->idBuku);
                    $eksemplar = $detail->idEksemplar ? BukuEksemplar::lockForUpdate()->find($detail->idEksemplar) : null;

                    $kondisiFinal = $kondisiMap[$detail->id] ?? $detail->kondisi_laporan ?? 'Baik';
                    if (! in_array($kondisiFinal, ['Baik', 'Rusak', 'Hilang'])) {
                        $kondisiFinal = 'Baik';
                    }

                    // Pengembalian record
                    $pengembalian = null;
                    if ($detail->id_denda) {
                        $denda = Denda::find($detail->id_denda);
                        if ($denda && $denda->idPengembalian) {
                            $pengembalian = Pengembalian::find($denda->idPengembalian);
                        }
                    }

                    if ($pengembalian) {
                        $pengembalian->update([
                            'idUserPetugas' => $petugasId,
                            'tanggalKembali' => $tanggalKembali->toDateString(),
                            'kondisiBuku' => $kondisiFinal,
                        ]);
                    } else {
                        $pengembalian = Pengembalian::create([
                            'idPeminjaman' => $detail->idPeminjaman,
                            'idUserPetugas' => $petugasId,
                            'tanggalKembali' => $tanggalKembali->toDateString(),
                            'kondisiBuku' => $kondisiFinal,
                        ]);

                        if ($detail->id_denda) {
                            Denda::where('idDenda', $detail->id_denda)->update(['idPengembalian' => $pengembalian->idPengembalian]);
                        }
                    }

                    if (! $firstPengembalianId && $pengembalian) {
                        $firstPengembalianId = $pengembalian->idPengembalian;
                    }

                    $detail->update(['statusBuku' => 'Kembali']);

                    if ($eksemplar) {
                        $statusBaru = ($kondisiFinal === 'Hilang') ? 'Hilang' : 'Tersedia';
                        $eksemplar->update([
                            'status' => $statusBaru,
                            'kondisi' => $kondisiFinal,
                        ]);
                    }

                    if ($buku) {
                        $buku->syncStok();
                        $buku->update(['kondisi' => $kondisiFinal]);
                    }
                }

                foreach ($peminjamanIds as $pjId => $pj) {
                    $sisaBuku = DetailPeminjaman::where('idPeminjaman', $pjId)
                        ->where('statusBuku', '!=', 'Kembali')
                        ->count();

                    if ($sisaBuku === 0) {
                        $pj->update(['status' => 'Selesai']);
                    }
                }

                return [
                    'count' => $pendingDetails->count(),
                    'idPengembalian' => $firstPengembalianId,
                ];
            });

            return redirect()->route('pengembalian.show', $result['idPengembalian'])
                ->with('success', "Pengembalian seluruh buku fisik ({$result['count']} buku) berhasil diselesaikan oleh petugas!");
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses pengembalian sekaligus: '.$e->getMessage());
        }
    }
}
