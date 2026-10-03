<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Denda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    // Tambahkan di dalam class PembayaranController

// 1. Member buka tagihan & pilih Bayar via QR -> Tampilkan halaman QRIS
    public function bayarQr($idDenda)
    {
        $denda = Denda::with(['pengembalian.peminjaman.member'])->findOrFail($idDenda);

        if ($denda->status === 'Lunas') {
            return redirect()->route('denda.show', $denda->idDenda)
                ->with('success', 'Tagihan denda ini sudah lunas.');
        }

        // Ambil atau buat record pembayaran pending untuk denda ini
        $pembayaran = Pembayaran::firstOrCreate(
            [
                'idDenda' => $denda->idDenda,
                'status'  => 'Pending',
            ],
            [
                'nominal' => $denda->jumlah,
                'metode'  => 'QRIS',
            ]
        );

        // Mock QR string (menggunakan generator QR gratis Google Chart API / QR Server)
        $qrData = "PERPUS-QRIS-" . $pembayaran->idPembayaran . "-NOMINAL-" . $pembayaran->nominal;
        $qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($qrData);

        return view('pembayaran.bayar_qr', compact('denda', 'pembayaran', 'qrImageUrl'));
    }

    // 2. Simulasi Scan QR & Konfirmasi Pembayaran dari sisi Member
    public function prosesBayarQr(Request $request, $idPembayaran)
    {
        $pembayaran = Pembayaran::with('denda')->findOrFail($idPembayaran);

        // Simulasi hasil pembayaran dari gateway (berhasil atau gagal)
        $statusInput = $request->input('simulasi_status', 'berhasil');

        if ($statusInput === 'berhasil') {
            DB::transaction(function () use ($pembayaran) {
                // Perbarui status pembayaran
                $pembayaran->update([
                    'status' => 'Sukses'
                ]);

                // Perbarui status denda menjadi Lunas
                if ($pembayaran->denda) {
                    $pembayaran->denda->update([
                        'status' => 'Lunas'
                    ]);
                }
            });

            // Tampilkan pembayaran berhasil
            return redirect()->route('denda.show', $pembayaran->idDenda)
                ->with('success', 'Pembayaran via QRIS berhasil! Status denda Anda kini telah lunas.');
        }

        // Tampilkan pembayaran gagal
        return back()->with('error', 'Pembayaran gagal atau transaksi dibatalkan oleh Payment Gateway.');
    }
    
    
    
    
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
