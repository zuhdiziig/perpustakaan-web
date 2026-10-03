<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Buku;
use App\Models\Barcode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // Halaman daftar & riwayat peminjaman
    public function index()
    {
        $peminjamans = Peminjaman::with(['member', 'petugas', 'details.buku'])
            ->latest('idPeminjaman')
            ->paginate(10);

        return view('peminjaman.index', compact('peminjamans'));
    }

    // Tampilkan form peminjaman (Scan barcode & input member)
    public function create()
    {
        $members = User::where('role', 'member')->where('status', 'aktif')->get();
        return view('peminjaman.create', compact('members'));
    }

    // Konfirmasi & Simpan Transaksi Peminjaman
    public function store(Request $request)
    {
        $request->validate([
            'idUserMember' => ['required', 'exists:users,id'],
            'barcodes'     => ['required', 'array', 'min:1'],
            'barcodes.*'   => ['required', 'string'],
        ], [
            'idUserMember.required' => 'Pilih member yang meminjam.',
            'barcodes.required'     => 'Minimal scan 1 barcode buku.',
        ]);

        // Bersihkan input barcode dari string kosong
        $inputBarcodes = array_filter(array_map('trim', $request->barcodes));

        // 1. Validasi Batas Maksimal 7 Buku
        $totalBuku = count($inputBarcodes);
        if ($totalBuku > 7) {
            return back()->withErrors(['barcodes' => 'Gagal: Batas maksimal peminjaman adalah 7 buku.'])->withInput();
        }

        if ($totalBuku === 0) {
            return back()->withErrors(['barcodes' => 'Masukkan setidaknya 1 kode barcode buku.'])->withInput();
        }

        // Cek apakah member masih memiliki buku yang sedang dipinjam
        $bukuSedangDipinjam = DetailPeminjaman::whereHas('peminjaman', function ($q) use ($request) {
            $q->where('idUserMember', $request->idUserMember)->where('status', 'Dipinjam');
        })->where('statusBuku', 'Dipinjam')->count();

        if (($bukuSedangDipinjam + $totalBuku) > 7) {
            return back()->withErrors([
                'barcodes' => "Member saat ini masih meminjam {$bukuSedangDipinjam} buku. Total pinjaman aktif tidak boleh melebihi batas 7 buku."
            ])->withInput();
        }

        // 2. Ambil & Validasi Ketersediaan Tiap Buku berdasarkan Barcode
        $bukuItems = [];
        foreach ($inputBarcodes as $barcodeStr) {
            $barcodeModel = Barcode::with('buku')->where('kodeBarcode', $barcodeStr)->first();

            if (!$barcodeModel || !$barcodeModel->buku) {
                return back()->withErrors(['barcodes' => "Barcode [{$barcodeStr}] tidak ditemukan dalam database."])->withInput();
            }

            $buku = $barcodeModel->buku;

            if ($buku->stok < 1) {
                return back()->withErrors(['barcodes' => "Buku '{$buku->judul}' stoknya habis atau sedang dipinjam."])->withInput();
            }

            $bukuItems[] = $buku;
        }

        // 3. Simpan Transaksi Peminjaman & Kurangi Stok secara Aman
        $peminjaman = DB::transaction(function () use ($request, $bukuItems, $totalBuku) {
            $tanggalPinjam = Carbon::now();
            $batasKembali = Carbon::now()->addMonth(); // Jatuh tempo 1 bulan

            $peminjaman = Peminjaman::create([
                'idUserMember'  => $request->idUserMember,
                'idUserPetugas' => auth()->id(),
                'tanggalPinjam' => $tanggalPinjam->toDateString(),
                'batasKembali'  => $batasKembali->toDateString(),
                'status'        => 'Dipinjam',
                'totalBuku'     => $totalBuku,
            ]);

            foreach ($bukuItems as $buku) {
                DetailPeminjaman::create([
                    'idPeminjaman' => $peminjaman->idPeminjaman,
                    'idBuku'       => $buku->idBuku,
                    'jumlah'       => 1,
                    'statusBuku'   => 'Dipinjam',
                ]);

                // Kurangi stok buku
                $buku->decrement('stok', 1);
            }

            return $peminjaman;
        });

        // 4. Arahkan ke halaman bukti peminjaman
        return redirect()->route('peminjaman.show', $peminjaman->idPeminjaman)
            ->with('success', 'Transaksi peminjaman berhasil dikonfirmasi.');
    }

    // Tampilkan Bukti Peminjaman (Struk)
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['member', 'petugas', 'details.buku.barcode'])->findOrFail($id);
        return view('peminjaman.show', compact('peminjaman'));
    }
}