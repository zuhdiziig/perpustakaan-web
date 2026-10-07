<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    // Fitur 17: Halaman Web Denda untuk Anggota (Sesuai Figma Frame 17)
    public function memberDenda(Request $request)
    {
        $userId = auth()->id();

        // Ambil data semua denda milik member yang sedang login
        $dendas = Denda::with(['pengembalian.peminjaman.details.buku', 'pembayaran'])
            ->whereHas('pengembalian.peminjaman', function ($query) use ($userId) {
                $query->where('idUserMember', $userId);
            })
            ->latest('idDenda')
            ->paginate(10);

        // Ambil list denda yang belum dibayar
        $dendaBelumDibayar = Denda::with(['pengembalian.peminjaman.details.buku', 'pembayaran'])
            ->whereHas('pengembalian.peminjaman', function ($query) use ($userId) {
                $query->where('idUserMember', $userId);
            })
            ->where('status', 'Belum Dibayar')
            ->latest('idDenda')
            ->get();

        // Total tunggakan belum dibayar
        $totalTunggakan = $dendaBelumDibayar->sum('jumlah');

        // Pilih denda aktif (bisa dipilih lewat query param ?selected=idDenda, default ke denda belum dibayar pertama atau denda pertama)
        $selectedId = $request->query('selected');
        $activeDenda = null;
        if ($selectedId) {
            $activeDenda = $dendaBelumDibayar->firstWhere('idDenda', $selectedId)
                ?? $dendas->firstWhere('idDenda', $selectedId);
        }

        if (! $activeDenda) {
            $activeDenda = $dendaBelumDibayar->first() ?? $dendas->first();
        }

        // Hitung rincian untuk denda aktif
        $hariTerlambat = 0;
        $rentangTanggal = '-';
        $perhitunganText = '-';
        $peminjaman = null;
        $pengembalian = null;
        $buku = null;

        if ($activeDenda && $activeDenda->pengembalian) {
            $pengembalian = $activeDenda->pengembalian;
            $peminjaman = $pengembalian->peminjaman;
            $buku = $peminjaman?->details?->first()?->buku;

            if ($peminjaman && $pengembalian->tanggalKembali && $peminjaman->batasKembali) {
                $tglKembali = Carbon::parse($pengembalian->tanggalKembali);
                $batas = Carbon::parse($peminjaman->batasKembali);

                if ($tglKembali->greaterThan($batas)) {
                    $hariTerlambat = (int) $batas->diffInDays($tglKembali);
                    if ($hariTerlambat === 0) {
                        $hariTerlambat = 1;
                    }
                    $rentangTanggal = $batas->locale('id')->translatedFormat('d M').' — '.$tglKembali->locale('id')->translatedFormat('d M Y');
                }
            }

            if ($hariTerlambat > 0) {
                $perhitunganText = "{$hariTerlambat} hari × Rp1.000";
            } else {
                $perhitunganText = 'Rp '.number_format($activeDenda->jumlah, 0, ',', '.');
            }
        }

        return view('denda.member_index', compact(
            'dendas',
            'dendaBelumDibayar',
            'totalTunggakan',
            'activeDenda',
            'hariTerlambat',
            'rentangTanggal',
            'perhitunganText',
            'peminjaman',
            'pengembalian',
            'buku'
        ));
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
