<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    // Ambil riwayat peminjaman & pengembalian khusus member yang sedang login
    public function index()
    {
        $userId = auth()->id();

        $riwayats = Peminjaman::with([
                'details.buku.barcode',
                'pengembalians.denda'
            ])
            ->where('idUserMember', $userId)
            ->latest('idPeminjaman')
            ->paginate(10);

        return view('riwayat.index', compact('riwayats'));
    }
}
