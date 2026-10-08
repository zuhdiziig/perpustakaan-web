<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\BukuEksemplar;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    // Halaman antarmuka scanner barcode
    public function scan(Request $request)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('dashboard')->with('error', 'Layanan scanner meja sirkulasi hanya diperuntukkan bagi Petugas Perpustakaan.');
        }

        $kodeBarcode = trim($request->query('kodeBarcode', ''));
        $buku = null;
        $booking = null;
        $error = null;

        if (! empty($kodeBarcode)) {
            // 1. Cek apakah kode ini adalah kode booking atau QR token tiket booking
            $bookingModel = Peminjaman::with([
                'member',
                'details.buku.kategori',
                'details.buku.barcode',
                'details.eksemplar',
            ])
                ->where('kode_booking', $kodeBarcode)
                ->orWhere('kode_booking', strtoupper($kodeBarcode))
                ->orWhere('qr_token', $kodeBarcode)
                ->first();

            if ($bookingModel) {
                $booking = $bookingModel;
                $buku = $bookingModel->details->first()?->buku;
            } else {
                // 2. Sistem mencari buku berdasarkan relasi kode barcode judul
                $barcodeModel = Barcode::with(['buku.kategori'])->where('kodeBarcode', $kodeBarcode)->first();

                if ($barcodeModel && $barcodeModel->buku) {
                    $buku = $barcodeModel->buku;
                } else {
                    // 3. Cek di tabel eksemplar fisik buku
                    $eksemplar = BukuEksemplar::with(['buku.kategori', 'buku.barcode'])
                        ->where('kode_barcode', $kodeBarcode)
                        ->orWhere('qr_token', $kodeBarcode)
                        ->first();

                    if ($eksemplar && $eksemplar->buku) {
                        $buku = $eksemplar->buku;
                    } else {
                        $error = "Barcode '{$kodeBarcode}' tidak ditemukan dalam sistem.";
                    }
                }
            }
        }

        return view('barcode.scan', compact('kodeBarcode', 'buku', 'booking', 'error'));
    }
}
