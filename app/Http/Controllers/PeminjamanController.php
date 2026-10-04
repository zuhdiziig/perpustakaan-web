<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\Buku;
use App\Models\BukuEksemplar;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'barcodes' => ['required', 'array', 'min:1'],
            'barcodes.*' => ['required', 'string'],
        ], [
            'idUserMember.required' => 'Pilih member yang meminjam.',
            'barcodes.required' => 'Minimal scan 1 barcode buku.',
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
                'barcodes' => "Member saat ini masih meminjam {$bukuSedangDipinjam} buku. Total pinjaman aktif tidak boleh melebihi batas 7 buku.",
            ])->withInput();
        }

        // Cek duplikasi kode scan dalam 1 transaksi
        if (count($inputBarcodes) !== count(array_unique($inputBarcodes))) {
            return back()->withErrors(['barcodes' => 'Terdapat kode buku fisik yang di-scan lebih dari satu kali dalam transaksi yang sama.'])->withInput();
        }

        // 2. Transaksi Atomik dengan Row Locking (lockForUpdate)
        try {
            $peminjaman = DB::transaction(function () use ($request, $inputBarcodes, $totalBuku) {
                $eksemplarItems = [];
                $selectedEksemplarIds = [];

                foreach ($inputBarcodes as $code) {
                    $code = trim($code);

                    // A. Cari physical copy di tabel buku_eksemplar dengan lockForUpdate
                    $eksemplarQuery = BukuEksemplar::with('buku')->lockForUpdate();
                    if (is_numeric($code)) {
                        $eksemplar = (clone $eksemplarQuery)->where('idEksemplar', $code)->first()
                            ?? (clone $eksemplarQuery)->where('qr_token', $code)->orWhere('kode_barcode', $code)->first();
                    } else {
                        $eksemplar = (clone $eksemplarQuery)->where('qr_token', $code)->orWhere('kode_barcode', $code)->first();
                    }

                    if (! $eksemplar) {
                        // Tolak jika yang di-scan adalah title-level QR code
                        if (Buku::where('qr_token', $code)->exists()) {
                            throw new \DomainException("Kode [{$code}] adalah QR judul buku, bukan eksemplar fisik. Silakan scan QR stiker pada buku fisik.");
                        }

                        throw new \DomainException("Buku fisik dengan QR/Barcode [{$code}] tidak ditemukan dalam database.");
                    }

                    // Re-check status fisik setelah lock didapatkan
                    if ($eksemplar->status !== 'Tersedia') {
                        $judul = $eksemplar->buku->judul ?? 'Buku';
                        throw new \DomainException("Buku '{$judul}' (Eksemplar #{$eksemplar->nomor_eksemplar}) statusnya sedang {$eksemplar->status}.");
                    }

                    if (in_array($eksemplar->idEksemplar, $selectedEksemplarIds)) {
                        $judul = $eksemplar->buku->judul ?? 'Buku';
                        throw new \DomainException("Buku '{$judul}' (Eksemplar #{$eksemplar->nomor_eksemplar}) di-scan lebih dari satu kali dalam transaksi yang sama.");
                    }

                    $selectedEksemplarIds[] = $eksemplar->idEksemplar;
                    $eksemplarItems[] = $eksemplar;
                }

                $tanggalPinjam = Carbon::now();
                $batasKembali = Carbon::now()->addMonth(); // Jatuh tempo 1 bulan

                $peminjaman = Peminjaman::create([
                    'idUserMember' => $request->idUserMember,
                    'idUserPetugas' => auth()->id(),
                    'tanggalPinjam' => $tanggalPinjam->toDateString(),
                    'batasKembali' => $batasKembali->toDateString(),
                    'status' => 'Dipinjam',
                    'totalBuku' => $totalBuku,
                ]);

                foreach ($eksemplarItems as $eksemplar) {
                    DetailPeminjaman::create([
                        'idPeminjaman' => $peminjaman->idPeminjaman,
                        'idBuku' => $eksemplar->idBuku,
                        'idEksemplar' => $eksemplar->idEksemplar,
                        'jumlah' => 1,
                        'statusBuku' => 'Dipinjam',
                    ]);

                    // Update status eksemplar fisik menjadi Dipinjam
                    $eksemplar->update(['status' => 'Dipinjam']);

                    // Sinkronkan stok tersedia pada master buku
                    $eksemplar->buku->syncStok();
                }

                return $peminjaman;
            });
        } catch (\DomainException $e) {
            return back()->withErrors(['barcodes' => $e->getMessage()])->withInput();
        }

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
