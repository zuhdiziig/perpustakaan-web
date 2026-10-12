<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiwayatController extends Controller
{
    // Ambil riwayat peminjaman & pengembalian khusus member yang sedang login
    public function index(Request $request): View
    {
        $userId = auth()->id();
        $statusDipilih = in_array($request->query('status'), ['Booking', 'Siap Diambil', 'Dipinjam', 'Selesai', 'Dibatalkan'], true)
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

        // Hitung statistik transaksi member
        $totalPinjam = Peminjaman::where('idUserMember', $userId)->count();
        $totalBooking = Peminjaman::where('idUserMember', $userId)->whereIn('status', ['Booking', 'Siap Diambil'])->count();
        $totalAktif = Peminjaman::where('idUserMember', $userId)->where('status', 'Dipinjam')->count();
        $totalSelesai = Peminjaman::where('idUserMember', $userId)->where('status', 'Selesai')->count();
        $totalTerlambat = Peminjaman::where('idUserMember', $userId)
            ->where('status', 'Dipinjam')
            ->where('batasKembali', '<', now()->toDateString())
            ->count();

        return view('riwayat.index', compact(
            'riwayats',
            'statusDipilih',
            'totalPinjam',
            'totalBooking',
            'totalAktif',
            'totalSelesai',
            'totalTerlambat'
        ));
    }

    /**
     * API Pencarian Terpadu (Universal Search) Anggota untuk Buku, Peminjaman, Pengembalian, dan Denda.
     */
    public function apiUniversalSearch(Request $request)
    {
        $userId = auth()->id();
        $q = trim((string) $request->query('q', ''));
        $tab = $request->query('tab', 'semua');

        if (empty($q)) {
            return response()->json([
                'buku' => [],
                'peminjaman' => [],
                'pengembalian' => [],
                'denda' => [],
            ]);
        }

        $qLower = mb_strtolower($q);

        $bukuResults = [];
        if (in_array($tab, ['semua', 'buku'], true)) {
            $bukus = Buku::with('kategori')
                ->where(function ($query) use ($qLower) {
                    $query->whereRaw('LOWER(judul) LIKE ?', ["%{$qLower}%"])
                        ->orWhereRaw('LOWER(penulis) LIKE ?', ["%{$qLower}%"])
                        ->orWhereRaw('LOWER(penerbit) LIKE ?', ["%{$qLower}%"]);
                })
                ->limit(5)
                ->get();

            foreach ($bukus as $b) {
                $bukuResults[] = [
                    'id' => $b->idBuku,
                    'judul' => $b->judul,
                    'penulis' => $b->penulis,
                    'kategori' => $b->kategori?->namaKategori ?? 'Umum',
                    'stok' => $b->stok,
                    'rak' => $b->rak ?? 'Rak F-12',
                    'url' => route('katalog.show', $b->idBuku),
                ];
            }
        }

        $peminjamanResults = [];
        if (in_array($tab, ['semua', 'peminjaman'], true)) {
            $peminjamans = Peminjaman::with('details.buku')
                ->where('idUserMember', $userId)
                ->where(function ($query) use ($qLower) {
                    $query->whereRaw('LOWER(COALESCE(kode_booking, \'\')) LIKE ?', ["%{$qLower}%"])
                        ->orWhereRaw('LOWER(COALESCE(status, \'\')) LIKE ?', ["%{$qLower}%"])
                        ->orWhereHas('details.buku', function ($b) use ($qLower) {
                            $b->whereRaw('LOWER(judul) LIKE ?', ["%{$qLower}%"]);
                        });
                })
                ->latest('idPeminjaman')
                ->limit(5)
                ->get();

            foreach ($peminjamans as $p) {
                $peminjamanResults[] = [
                    'id' => $p->idPeminjaman,
                    'kode' => $p->kode_booking ?? ('PJ-#'.$p->idPeminjaman),
                    'buku' => $p->details->pluck('buku.judul')->filter()->join(', ') ?: 'Peminjaman Buku',
                    'status' => $p->status,
                    'batas_kembali' => $p->batasKembali ? Carbon::parse($p->batasKembali)->translatedFormat('d M Y') : null,
                    'url' => in_array($p->status, ['Booking', 'Siap Diambil']) ? route('peminjaman.booking.tiket', $p->idPeminjaman) : route('riwayat.index'),
                ];
            }
        }

        $pengembalianResults = [];
        if (in_array($tab, ['semua', 'pengembalian'], true)) {
            $pengembalians = Pengembalian::with('peminjaman.details.buku')
                ->whereHas('peminjaman', fn ($p) => $p->where('idUserMember', $userId))
                ->where(function ($query) use ($qLower) {
                    $query->whereRaw('LOWER(COALESCE("kondisiBuku", \'\')) LIKE ?', ["%{$qLower}%"])
                        ->orWhereHas('peminjaman.details.buku', function ($b) use ($qLower) {
                            $b->whereRaw('LOWER(judul) LIKE ?', ["%{$qLower}%"]);
                        });
                })
                ->latest('idPengembalian')
                ->limit(5)
                ->get();

            foreach ($pengembalians as $ret) {
                $pengembalianResults[] = [
                    'id' => $ret->idPengembalian,
                    'tanggal' => Carbon::parse($ret->tanggalKembali)->translatedFormat('d M Y'),
                    'buku' => $ret->peminjaman?->details?->pluck('buku.judul')->filter()->join(', ') ?: 'Buku',
                    'kondisi' => $ret->kondisiBuku ?? 'Baik',
                    'url' => route('pengembalian.member'),
                ];
            }
        }

        $dendaResults = [];
        if (in_array($tab, ['semua', 'denda'], true)) {
            $dendas = Denda::with(['pengembalian.peminjaman.details.buku', 'details.peminjaman.details.buku'])
                ->where(function ($query) use ($userId) {
                    $query->whereHas('pengembalian.peminjaman', fn ($p) => $p->where('idUserMember', $userId))
                        ->orWhereHas('details.peminjaman', fn ($p) => $p->where('idUserMember', $userId));
                })
                ->where(function ($query) use ($qLower) {
                    $query->whereRaw('LOWER(COALESCE("jenisDenda", \'\')) LIKE ?', ["%{$qLower}%"])
                        ->orWhereRaw('LOWER(COALESCE(status, \'\')) LIKE ?', ["%{$qLower}%"]);
                })
                ->latest('idDenda')
                ->limit(5)
                ->get();

            foreach ($dendas as $d) {
                $bukuJudul = $d->pengembalian?->peminjaman?->details?->first()?->buku?->judul
                    ?? $d->details->first()?->peminjaman?->details?->first()?->buku?->judul ?? 'Denda Sirkulasi';

                $dendaResults[] = [
                    'id' => $d->idDenda,
                    'jenis' => $d->jenisDenda,
                    'nominal' => 'Rp '.number_format($d->jumlah, 0, ',', '.'),
                    'buku' => $bukuJudul,
                    'status' => $d->status,
                    'url' => $d->status === 'Belum Dibayar' ? route('bayar.qr', $d->idDenda) : route('denda.saya'),
                ];
            }
        }

        return response()->json([
            'buku' => $bukuResults,
            'peminjaman' => $peminjamanResults,
            'pengembalian' => $pengembalianResults,
            'denda' => $dendaResults,
        ]);
    }
}
