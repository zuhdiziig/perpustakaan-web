<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Denda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    // Aktor: Buka daftar pembayaran
    public function index()
    {
        $pembayarans = Pembayaran::with(['denda.pengembalian.peminjaman.member'])
            ->orderBy('idPembayaran', 'desc')
            ->get();

        return view('pembayaran.index', compact('pembayarans'));
    }

    // Aktor minta status transaksi -> Sistem kirim ke Gateway -> Evaluasi status
    public function verifikasi($idPembayaran)
    {
        $pembayaran = Pembayaran::with('denda')->findOrFail($idPembayaran);

        // 1. Sistem kirim permintaan verifikasi ke Payment Gateway
        $statusGateway = $this->cekStatusKePaymentGateway($pembayaran->idPembayaran);

        // 2. Evaluasi keputusan: Status valid/lunas?
        if ($statusGateway === 'settlement' || $statusGateway === 'paid') {
            DB::transaction(function () use ($pembayaran) {
                // Simpan status pembayaran
                $pembayaran->update([
                    'status' => 'Sukses'
                ]);

                // Update status denda terkait menjadi Lunas
                if ($pembayaran->denda) {
                    $pembayaran->denda->update([
                        'status' => 'Lunas'
                    ]);
                }
            });

            // Tampilkan pembayaran terverifikasi
            return back()->with('success', 'Pembayaran terverifikasi! Status denda telah lunas.');
        }

        // Tampilkan pembayaran belum berhasil
        return back()->with('error', 'Pembayaran belum berhasil atau belum diselesaikan di Payment Gateway.');
    }

    /**
     * Simulasi komunikasi ke API Payment Gateway
     * Di lingkungan produksi nyata, fungsi ini memanggil Http::withToken()->get(...)
     */
    private function cekStatusKePaymentGateway($idPembayaran)
    {
        // Mock simulasi: anggap pembayaran terbayar (settlement/paid)
        // Ubah jadi 'pending' jika ingin menguji skenario gagal
        return 'settlement';
    }
}
