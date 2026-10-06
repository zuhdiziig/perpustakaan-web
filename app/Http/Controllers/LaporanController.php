<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $jenisLaporan = $request->query('jenis', 'peminjaman');
        $tglMulai = $request->query('tgl_mulai', Carbon::now()->startOfMonth()->toDateString());
        $tglSelesai = $request->query('tgl_selesai', Carbon::now()->toDateString());

        $dataPeminjaman = null;
        $dataPengembalian = null;
        $dataDenda = null;

        // Metrik Ringkasan Eksekutif
        $ringkasan = [
            'total_pinjam' => Peminjaman::whereBetween('tanggalPinjam', [$tglMulai, $tglSelesai])->count(),
            'total_kembali' => Pengembalian::whereBetween('tanggalKembali', [$tglMulai, $tglSelesai])->count(),
            'total_denda' => Denda::whereBetween('created_at', [$tglMulai.' 00:00:00', $tglSelesai.' 23:59:59'])->sum('jumlah'),
            'denda_lunas' => Denda::where('status', 'Lunas')->whereBetween('created_at', [$tglMulai.' 00:00:00', $tglSelesai.' 23:59:59'])->sum('jumlah'),
        ];

        // Olah data sesuai jenis laporan yang dipilih
        if ($jenisLaporan === 'peminjaman') {
            $dataPeminjaman = Peminjaman::with(['member', 'petugas', 'details.buku'])
                ->whereBetween('tanggalPinjam', [$tglMulai, $tglSelesai])
                ->latest('idPeminjaman')
                ->get();
        } elseif ($jenisLaporan === 'pengembalian') {
            $dataPengembalian = Pengembalian::with(['peminjaman.member', 'petugas', 'denda'])
                ->whereBetween('tanggalKembali', [$tglMulai, $tglSelesai])
                ->latest('idPengembalian')
                ->get();
        } elseif ($jenisLaporan === 'denda') {
            $dataDenda = Denda::with(['pengembalian.peminjaman.member', 'pembayaran'])
                ->whereBetween('created_at', [$tglMulai.' 00:00:00', $tglSelesai.' 23:59:59'])
                ->latest('idDenda')
                ->get();
        }

        return view('laporan.index', compact(
            'jenisLaporan',
            'tglMulai',
            'tglSelesai',
            'ringkasan',
            'dataPeminjaman',
            'dataPengembalian',
            'dataDenda'
        ));
    }
}
