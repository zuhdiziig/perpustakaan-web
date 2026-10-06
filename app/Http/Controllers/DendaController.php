<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    // Fitur 16: Melihat Denda oleh Member
    public function memberDenda()
    {
        $userId = auth()->id();

        // Ambil data denda milik member yang sedang login
        $dendas = Denda::with(['pengembalian.peminjaman.details.buku', 'pembayaran'])
            ->whereHas('pengembalian.peminjaman', function ($query) use ($userId) {
                $query->where('idUserMember', $userId);
            })
            ->latest('idDenda')
            ->paginate(10);

        // Hitung akumulasi denda yang belum dibayar
        $totalTunggakan = Denda::whereHas('pengembalian.peminjaman', function ($query) use ($userId) {
            $query->where('idUserMember', $userId);
        })
            ->where('status', 'Belum Dibayar')
            ->sum('jumlah');

        return view('denda.member_index', compact('dendas', 'totalTunggakan'));
    }

    // TAMBAHKAN METHOD INDEX DI SINI → Buka data denda & daftar transaksi yang perlu dihitung/dikelola
    public function index()
    {
        // Daftar denda yang sudah tercatat
        $dendas = Denda::with(['pengembalian.peminjaman.member', 'pembayaran'])
            ->latest('idDenda')
            ->paginate(10);

        // Pengembalian yang belum dibuatkan tagihan denda (jika ada keterlambatan/kerusakan)
        $pengembalianTertunda = Pengembalian::with(['peminjaman.member', 'peminjaman.details.buku'])
            ->doesntHave('denda')
            ->latest('idPengembalian')
            ->get();

        return view('denda.index', compact('dendas', 'pengembalianTertunda'));
    }

    // Pilih transaksi & tinjau simulasi perhitungan denda
    public function hitung($idPengembalian)
    {
        $pengembalian = Pengembalian::with(['peminjaman.member', 'peminjaman.details.buku'])
            ->findOrFail($idPengembalian);

        $peminjaman = $pengembalian->peminjaman;
        $tanggalKembali = Carbon::parse($pengembalian->tanggalKembali);
        $batasKembali = Carbon::parse($peminjaman->batasKembali);

        // Ambil buku terkait transaksi ini
        $buku = $peminjaman->details->first()->buku ?? null;
        $hargaBuku = $buku ? (float) $buku->harga : 0;

        // 1. Hitung keterlambatan (10% per minggu hingga max 100%)
        $hariTerlambat = 0;
        $mingguTerlambat = 0;
        $persenKeterlambatan = 0;
        $dendaKeterlambatan = 0;

        if ($tanggalKembali->greaterThan($batasKembali)) {
            $hariTerlambat = $batasKembali->diffInDays($tanggalKembali);
            if ($hariTerlambat == 0) {
                $hariTerlambat = 1;
            }
            $mingguTerlambat = (int) ceil($hariTerlambat / 7);
            $faktor = min($mingguTerlambat, 10);
            $persenKeterlambatan = $faktor * 10; // 10% s.d. 100%
            $dendaKeterlambatan = $hargaBuku * ($persenKeterlambatan / 100);
        }

        // 2. Tambahkan biaya rusak/hilang bila ada (100% harga buku)
        $dendaFisik = 0;
        if (in_array($pengembalian->kondisiBuku, ['Rusak', 'Hilang'])) {
            $dendaFisik = $hargaBuku;
        }

        $totalTagihan = $dendaKeterlambatan + $dendaFisik;

        return view('denda.hitung', compact(
            'pengembalian',
            'buku',
            'hariTerlambat',
            'mingguTerlambat',
            'persenKeterlambatan',
            'dendaKeterlambatan',
            'dendaFisik',
            'totalTagihan'
        ));
    }

    // Konfirmasi perhitungan -> Simpan denda -> Tampilkan tagihan
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idPengembalian' => ['required', 'exists:pengembalian,idPengembalian', 'unique:denda,idPengembalian'],
            'jenisDenda' => ['required', 'string'],
            'jumlah' => ['required', 'numeric', 'min:0'],
        ]);

        $denda = Denda::create([
            'idPengembalian' => $validated['idPengembalian'],
            'jenisDenda' => $validated['jenisDenda'],
            'jumlah' => $validated['jumlah'],
            'status' => 'Belum Dibayar',
        ]);

        return redirect()->route('denda.show', $denda->idDenda)
            ->with('success', 'Tagihan denda berhasil dikonfirmasi dan dicatat.');
    }

    // Tampilkan tagihan denda resmi
    public function show($id)
    {
        $denda = Denda::with(['pengembalian.peminjaman.member', 'pembayaran'])->findOrFail($id);

        return view('denda.show', compact('denda'));
    }
}
