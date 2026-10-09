<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\Pembayaran;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                $minggu = (int) ceil($hariTerlambat / 7);
                $persen = min($minggu * 10, 100);
                $perhitunganText = "{$hariTerlambat} hari ({$minggu} mgg / {$persen}%)";
            } else {
                $perhitunganText = $activeDenda->jenisDenda ?: ('Rp '.number_format($activeDenda->jumlah, 0, ',', '.'));
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

    // Buka data denda & monitoring kas tagihan (Petugas/Admin)
    public function index(Request $request)
    {
        $query = Denda::with([
            'pengembalian.peminjaman.member',
            'pengembalian.peminjaman.details.buku',
            'details.peminjaman.member',
            'details.buku',
            'pembayaran',
        ])
            ->where('jumlah', '>', 0)
            ->latest('idDenda');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('idDenda', 'like', "%{$search}%")
                    ->orWhere('jenisDenda', 'like', "%{$search}%")
                    ->orWhereHas('pengembalian.peminjaman.member', function ($m) use ($search) {
                        $m->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('details.peminjaman.member', function ($m) use ($search) {
                        $m->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('pengembalian.peminjaman.details.buku', function ($b) use ($search) {
                        $b->where('judul', 'like', "%{$search}%");
                    })
                    ->orWhereHas('details.buku', function ($b) use ($search) {
                        $b->where('judul', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status') && in_array($request->status, ['Belum Dibayar', 'Lunas'], true)) {
            $query->where('status', $request->status);
        }

        $dendas = $query->paginate(10)->withQueryString();

        // Rekapitulasi Kas & Tagihan Denda Riil
        $totalTunggakan = (float) Denda::where('jumlah', '>', 0)->where('status', 'Belum Dibayar')->sum('jumlah');
        $totalKasMasuk = (float) Denda::where('jumlah', '>', 0)->where('status', 'Lunas')->sum('jumlah');
        $countBelumLunas = Denda::where('jumlah', '>', 0)->where('status', 'Belum Dibayar')->count();
        $countLunas = Denda::where('jumlah', '>', 0)->where('status', 'Lunas')->count();

        return view('denda.index', compact(
            'dendas',
            'totalTunggakan',
            'totalKasMasuk',
            'countBelumLunas',
            'countLunas'
        ));
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

    // Terima pembayaran tunai di meja sirkulasi (Petugas/Admin)
    public function bayarTunai(Request $request, $id)
    {
        $denda = Denda::findOrFail($id);

        if ($denda->status === 'Lunas') {
            return redirect()->route('denda.index')->with('info', 'Tagihan denda ini sudah berstatus Lunas.');
        }

        DB::transaction(function () use ($denda) {
            $denda->update([
                'status' => 'Lunas',
            ]);

            Pembayaran::updateOrCreate(
                ['idDenda' => $denda->idDenda],
                [
                    'nominal' => $denda->jumlah,
                    'tanggalBayar' => now(),
                    'metode' => 'Tunai',
                    'status' => 'Sukses',
                ]
            );
        });

        return redirect()->route('denda.index')
            ->with('success', 'Pembayaran denda #'.str_pad($denda->idDenda, 5, '0', STR_PAD_LEFT).' sebesar Rp '.number_format($denda->jumlah, 0, ',', '.').' secara Tunai di meja sirkulasi berhasil dicatat.');
    }
}
