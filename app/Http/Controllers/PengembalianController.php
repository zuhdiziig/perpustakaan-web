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
        $barcodeInput = $request->query('barcode');
        $transaksi = null;
        $buku = null;
        $eksemplar = null;

        if ($barcodeInput) {
            // 1. Cek pencocokan ke BukuEksemplar fisik
            $eksemplar = BukuEksemplar::with('buku')
                ->where('qr_token', $barcodeInput)
                ->orWhere('kode_barcode', $barcodeInput)
                ->first();

            if ($eksemplar) {
                $buku = $eksemplar->buku;
                $query = Peminjaman::with('member')
                    ->where('status', 'Dipinjam')
                    ->whereHas('details', function ($q) use ($eksemplar) {
                        $q->where('idEksemplar', $eksemplar->idEksemplar)->where('statusBuku', 'Dipinjam');
                    });

                if ($request->query('idUserMember')) {
                    $query->where('idUserMember', $request->query('idUserMember'));
                }

                $transaksi = $query->first();
            }
        }

        return view('pengembalian.create', compact('barcodeInput', 'buku', 'transaksi', 'eksemplar'));
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
                    ->where('statusBuku', 'Dipinjam')
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
}
