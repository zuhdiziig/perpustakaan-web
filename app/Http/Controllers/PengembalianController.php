<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Barcode;
use App\Models\Denda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    // Tampilkan daftar transaksi pengembalian
    public function index()
    {
        $pengembalians = Pengembalian::with(['peminjaman.member', 'petugas', 'denda'])
            ->latest('idPengembalian')
            ->paginate(10);

        return view('pengembalian.index', compact('pengembalian'));
    }

    // Tampilkan form proses pengembalian (scan barcode & cek kondisi)
    public function create(Request $request)
    {
        $barcodeInput = $request->query('barcode');
        $transaksi = null;
        $buku = null;

        if ($barcodeInput) {
            $barcodeModel = Barcode::with('buku')->where('kodeBarcode', $barcodeInput)->first();
            if ($barcodeModel && $barcodeModel->buku) {
                $buku = $barcodeModel->buku;
                
                // Cari transaksi peminjaman aktif yang memuat buku ini
                $transaksi = Peminjaman::with('member')
                    ->where('status', 'Dipinjam')
                    ->whereHas('details', function ($q) use ($buku) {
                        $q->where('idBuku', $buku->idBuku)->where('statusBuku', 'Dipinjam');
                    })->first();
            }
        }

        return view('pengembalian.create', compact('barcodeInput', 'buku', 'transaksi'));
    }

    // Proses konfirmasi & simpan pengembalian serta kalkulasi denda sesuai aturan baru
public function store(Request $request)
    {
        $validated = $request->validate([
            'idPeminjaman' => ['required', 'exists:peminjaman,idPeminjaman'],
            'idBuku'       => ['required', 'exists:buku,idBuku'],
            'kondisiBuku'  => ['required', 'in:Baik,Rusak,Hilang'],
        ]);

        $peminjaman = Peminjaman::with(['details.buku'])->findOrFail($validated['idPeminjaman']);
        $buku = $peminjaman->details->firstWhere('idBuku', $validated['idBuku'])->buku ?? null;

        if (!$buku) {
            return back()->with('error', 'Data buku tidak valid untuk transaksi ini.');
        }

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
                $hariTerlambat = 1; // Terhitung terlambat jika sudah lewat hari H
            }

            // Pembulatan ke atas setiap kelipatan 7 hari
            $mingguTerlambat = (int) ceil($hariTerlambat / 7);

            // Maksimal minggu ke-10 (100%)
            $faktorMinggu = min($mingguTerlambat, 10);
            $persentaseDenda = $faktorMinggu * 0.10; // 10% s.d. 100%

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
        $pengembalian = DB::transaction(function () use (
            $peminjaman, 
            $buku, 
            $validated, 
            $tanggalKembali, 
            $totalDenda, 
            $dendaKeterlambatan, 
            $mingguTerlambat, 
            $jenisKondisi, 
            $dendaKondisi
        ) {
            $pengembalian = Pengembalian::create([
                'idPeminjaman'   => $peminjaman->idPeminjaman,
                'idUserPetugas'  => auth()->id(),
                'tanggalKembali' => $tanggalKembali->toDateString(),
                'kondisiBuku'    => $validated['kondisiBuku'],
            ]);

            // Catat denda jika ada kewajiban bayar
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
                    'jenisDenda'     => implode(' + ', $keteranganJenis),
                    'jumlah'         => $totalDenda,
                    'status'         => 'Belum Dibayar',
                ]);
            }

            // Update status detail peminjaman
            DetailPeminjaman::where('idPeminjaman', $peminjaman->idPeminjaman)
                ->where('idBuku', $buku->idBuku)
                ->update(['statusBuku' => 'Kembali']);

            // Kelola stok: jika kembali (Baik/Rusak) stok bertambah, jika hilang stok tetap tidak bertambah
            if ($validated['kondisiBuku'] !== 'Hilang') {
                $buku->increment('stok', 1);
            }

            // Update kondisi fisik buku di master buku
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
