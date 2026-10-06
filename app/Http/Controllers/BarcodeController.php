<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    // Halaman antarmuka scanner barcode
    public function scan(Request $request)
    {
        $kodeBarcode = trim($request->query('kodeBarcode', ''));
        $buku = null;
        $error = null;

        if (! empty($kodeBarcode)) {
            // Sistem mencari buku berdasarkan relasi kode barcode
            $barcodeModel = Barcode::with(['buku.kategori'])->where('kodeBarcode', $kodeBarcode)->first();

            if ($barcodeModel && $barcodeModel->buku) {
                $buku = $barcodeModel->buku;
            } else {
                $error = "Barcode '{$kodeBarcode}' tidak ditemukan dalam sistem.";
            }
        }

        return view('barcode.scan', compact('kodeBarcode', 'buku', 'error'));
    }
}
