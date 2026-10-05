<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiwayatController extends Controller
{
    // Ambil riwayat peminjaman & pengembalian khusus member yang sedang login
    public function index(Request $request): View
    {
        $userId = auth()->id();
        $statusDipilih = in_array($request->query('status'), ['Dipinjam', 'Selesai'], true)
            ? $request->query('status')
            : null;

        $riwayats = Peminjaman::with([
            'details.buku.barcode',
            'pengembalians.denda',
        ])
            ->where('idUserMember', $userId)
            ->when($statusDipilih, fn ($query) => $query->where('status', $statusDipilih))
            ->latest('idPeminjaman')
            ->paginate(10)
            ->withQueryString();

        return view('riwayat.index', compact('riwayats', 'statusDipilih'));
    }
}
