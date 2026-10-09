<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\BukuEksemplar;
use App\Models\Denda;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Pembayaran;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Jumlah transaksi terbaru yang ditampilkan di tabel dashboard member.
     */
    private const JUMLAH_TRANSAKSI = 5;

    /**
     * Jumlah transaksi terbaru yang ditampilkan di tabel dashboard petugas.
     */
    private const JUMLAH_TRANSAKSI_PETUGAS = 4;

    /**
     * Jumlah kartu buku rekomendasi.
     */
    private const JUMLAH_REKOMENDASI = 4;

    /**
     * Tampilkan dashboard sesuai role pengguna yang sedang login.
     */
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        return match ($user->role) {
            'admin' => $this->adminDashboard($request, $user),
            'petugas' => $this->petugasDashboard($request, $user),
            default => $this->memberDashboard($request, $user),
        };
    }

    /**
     * Dashboard khusus Administrator (eksekutif & manajemen data).
     */
    private function adminDashboard(Request $request, User $admin): View
    {
        $kataKunci = trim((string) $request->query('q', ''));

        // 1. Metrik utama perpustakaan
        $totalEksemplar = BukuEksemplar::count();
        if ($totalEksemplar === 0) {
            $totalEksemplar = (int) Buku::sum('stok');
        }
        $totalJudul = Buku::count();

        $anggotaAktif = User::where('role', 'member')->where('status', 'aktif')->count();
        $anggotaBaruBulanIni = User::where('role', 'member')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $petugasAktif = User::where('role', 'petugas')->where('status', 'aktif')->count();
        $petugasBertugasHariIni = Peminjaman::whereDate('tanggalPinjam', today())
            ->whereNotNull('idUserPetugas')
            ->distinct('idUserPetugas')
            ->count('idUserPetugas');

        $pembayaranBulanIni = Pembayaran::where(function (Builder $query) {
            $query->where(function (Builder $q) {
                $q->whereMonth('tanggalBayar', now()->month)
                    ->whereYear('tanggalBayar', now()->year);
            })->orWhere(function (Builder $q) {
                $q->whereNull('tanggalBayar')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
            });
        });

        $dendaTerkumpulBulanIni = (float) (clone $pembayaranBulanIni)->sum('nominal');
        $transaksiDendaBulanIni = (clone $pembayaranBulanIni)->count();

        // 2. Metrik modul manajemen
        $totalKategori = Kategori::count();
        $kategoriPopuler = Kategori::withCount('buku')
            ->orderByDesc('buku_count')
            ->first()?->namaKategori ?? 'Fiksi';

        $totalPinjamanAktif = DetailPeminjaman::where('statusBuku', 'Dipinjam')->count();
        $pinjamHariIni = Peminjaman::whereDate('tanggalPinjam', today())->count();

        $dendaBelumLunasCount = Denda::where('status', 'Belum Dibayar')->count();
        $dendaBelumLunasNominal = (float) Denda::where('status', 'Belum Dibayar')->sum('jumlah');

        // 3. Transaksi terbaru (mendukung pencarian)
        $transaksiQuery = DetailPeminjaman::query()
            ->where('statusBuku', 'Dipinjam')
            ->with([
                'buku:idBuku,judul',
                'eksemplar:idEksemplar,kode_barcode',
                'peminjaman.member:id,name',
            ])
            ->when($kataKunci !== '', function (Builder $query) use ($kataKunci) {
                $likeOp = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
                $query->where(function (Builder $query) use ($kataKunci, $likeOp) {
                    $query->whereHas('buku', fn (Builder $buku) => $buku->where('judul', $likeOp, "%{$kataKunci}%"))
                        ->orWhereHas('eksemplar', fn (Builder $eksemplar) => $eksemplar->where('kode_barcode', $likeOp, "%{$kataKunci}%"))
                        ->orWhereHas('peminjaman.member', fn (Builder $member) => $member->where('name', $likeOp, "%{$kataKunci}%"));
                });
            });

        $transaksiList = $transaksiQuery
            ->latest('id')
            ->take(self::JUMLAH_TRANSAKSI_PETUGAS)
            ->get()
            ->map(function (DetailPeminjaman $detail): array {
                $jatuhTempo = Carbon::parse($detail->peminjaman->batasKembali)->startOfDay();

                return [
                    'judul' => $detail->buku?->judul ?? 'Buku perpustakaan',
                    'kode' => $detail->eksemplar?->kode_barcode ?? sprintf('BK-%05d', $detail->idBuku),
                    'anggota' => $detail->peminjaman?->member?->name ?? 'Anggota',
                    'jatuhTempo' => $jatuhTempo,
                    'status' => today()->greaterThan($jatuhTempo) ? 'Terlambat' : 'Dipinjam',
                ];
            });

        $periodeLaporan = sprintf('01-%02d %s', now()->endOfMonth()->day, now()->translatedFormat('F Y'));

        return view('dashboard.admin', [
            'admin' => $admin,
            'statistik' => [
                'totalEksemplar' => $totalEksemplar,
                'totalJudul' => $totalJudul,
                'anggotaAktif' => $anggotaAktif,
                'anggotaBaruBulanIni' => $anggotaBaruBulanIni,
                'petugasAktif' => $petugasAktif,
                'petugasBertugasHariIni' => $petugasBertugasHariIni,
                'dendaTerkumpulBulanIni' => $dendaTerkumpulBulanIni,
                'transaksiDendaBulanIni' => $transaksiDendaBulanIni,
            ],
            'manajemen' => [
                'buku' => [
                    'eksemplar' => $totalEksemplar,
                    'judul' => $totalJudul,
                ],
                'kategori' => [
                    'total' => $totalKategori,
                    'terpopuler' => $kategoriPopuler,
                ],
                'anggota' => [
                    'aktif' => $anggotaAktif,
                    'baruBulanIni' => $anggotaBaruBulanIni,
                ],
                'petugas' => [
                    'total' => $petugasAktif,
                    'bertugas' => $petugasBertugasHariIni,
                ],
                'transaksi' => [
                    'aktif' => $totalPinjamanAktif,
                    'pinjamHariIni' => $pinjamHariIni,
                ],
                'denda' => [
                    'belumLunasCount' => $dendaBelumLunasCount,
                    'belumLunasNominal' => $dendaBelumLunasNominal,
                ],
                'laporan' => [
                    'periode' => now()->translatedFormat('F Y'),
                ],
            ],
            'kataKunci' => $kataKunci,
            'transaksi' => $transaksiList,
            'periodeLaporan' => $periodeLaporan,
        ]);
    }

    /**
     * Dashboard khusus petugas perpustakaan (sirkulasi & operasional).
     */
    private function petugasDashboard(Request $request, User $petugas): View
    {
        $kataKunci = trim((string) $request->query('q', ''));

        // 0. Antrean Booking Aktif (Booking & Siap Diambil)
        $antreanBooking = Peminjaman::query()
            ->whereIn('status', ['Booking', 'Siap Diambil'])
            ->with([
                'member:id,name,noTelepon,email,created_at',
                'details.buku:idBuku,judul,penulis,rak,cover',
                'details.eksemplar:idEksemplar,nomor_eksemplar,kode_barcode,status',
            ])
            ->latest('idPeminjaman')
            ->get();

        $totalBookingMenunggu = Peminjaman::where('status', 'Booking')->count();
        $totalBookingSiap = Peminjaman::where('status', 'Siap Diambil')->count();
        $totalBookingPerluDisiapkan = Peminjaman::where('status', 'Booking')
            ->where('opsi_pengambilan', 'siapkan_petugas')
            ->count();

        // 1. Statistik sirkulasi
        $peminjamanHariIni = Peminjaman::whereDate('tanggalPinjam', today())
            ->where('status', 'Dipinjam')
            ->count();
        $pengembalianHariIni = Pengembalian::whereDate('tanggalKembali', today())->count();

        // Pengembalian tepat waktu (tanpa denda keterlambatan)
        $tepatWaktuHariIni = Pengembalian::whereDate('tanggalKembali', today())
            ->whereDoesntHave('denda', fn (Builder $q) => $q->where('jenisDenda', 'like', '%Terlambat%'))
            ->count();

        $totalPeminjamanAktif = DetailPeminjaman::where('statusBuku', 'Dipinjam')->count();
        $jatuhTempoHariIni = Peminjaman::where('status', 'Dipinjam')
            ->whereDate('batasKembali', today())
            ->count();

        $totalTerlambat = Peminjaman::where('status', 'Dipinjam')
            ->whereDate('batasKembali', '<', today())
            ->count();

        // 2. Daftar transaksi aktif (mendukung pencarian)
        $transaksiAktifQuery = DetailPeminjaman::query()
            ->where('statusBuku', 'Dipinjam')
            ->with([
                'buku:idBuku,judul',
                'eksemplar:idEksemplar,kode_barcode',
                'peminjaman.member:id,name',
            ])
            ->when($kataKunci !== '', function (Builder $query) use ($kataKunci) {
                $likeOp = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
                $query->where(function (Builder $query) use ($kataKunci, $likeOp) {
                    $query->whereHas('buku', fn (Builder $buku) => $buku->where('judul', $likeOp, "%{$kataKunci}%"))
                        ->orWhereHas('eksemplar', fn (Builder $eksemplar) => $eksemplar->where('kode_barcode', $likeOp, "%{$kataKunci}%"))
                        ->orWhereHas('peminjaman.member', fn (Builder $member) => $member->where('name', $likeOp, "%{$kataKunci}%"));
                });
            });

        $totalTransaksiDitemukan = (clone $transaksiAktifQuery)->count();

        $transaksiList = $transaksiAktifQuery
            ->latest('id')
            ->take(self::JUMLAH_TRANSAKSI_PETUGAS)
            ->get()
            ->map(function (DetailPeminjaman $detail): array {
                $jatuhTempo = Carbon::parse($detail->peminjaman->batasKembali)->startOfDay();

                return [
                    'judul' => $detail->buku?->judul ?? 'Buku perpustakaan',
                    'kode' => $detail->eksemplar?->kode_barcode ?? sprintf('BK-%05d', $detail->idBuku),
                    'anggota' => $detail->peminjaman?->member?->name ?? 'Anggota',
                    'jatuhTempo' => $jatuhTempo,
                    'status' => today()->greaterThan($jatuhTempo) ? 'Terlambat' : 'Dipinjam',
                ];
            });

        // 3. Aktivitas terbaru petugas
        $aktivitasTerbaru = $this->ambilAktivitasTerbaruPetugas();

        return view('dashboard.petugas', [
            'petugas' => $petugas,
            'statistik' => [
                'peminjamanHariIni' => $peminjamanHariIni,
                'pengembalianHariIni' => $pengembalianHariIni,
                'tepatWaktuHariIni' => $tepatWaktuHariIni,
                'peminjamanAktif' => $totalPeminjamanAktif,
                'jatuhTempoHariIni' => $jatuhTempoHariIni,
                'terlambat' => $totalTerlambat,
                'bookingMenunggu' => $totalBookingMenunggu,
                'bookingSiap' => $totalBookingSiap,
                'bookingPerluDisiapkan' => $totalBookingPerluDisiapkan,
                'totalBookingAktif' => $totalBookingMenunggu + $totalBookingSiap,
            ],
            'kataKunci' => $kataKunci,
            'totalTransaksiAktif' => $totalTransaksiDitemukan,
            'transaksi' => $transaksiList,
            'antreanBooking' => $antreanBooking,
            'totalBookingPerluDisiapkan' => $totalBookingPerluDisiapkan,
            'aktivitasTerbaru' => $aktivitasTerbaru,
        ]);
    }

    /**
     * Mengambil 3 log aktivitas sirkulasi terkini (peminjaman, pengembalian, pembayaran denda).
     *
     * @return \Illuminate\Support\Collection<int, array{waktu: string, anggota: string, deskripsi: string}>
     */
    private function ambilAktivitasTerbaruPetugas(): \Illuminate\Support\Collection
    {
        $aktivitas = collect();

        // Peminjaman terbaru
        DetailPeminjaman::with('buku:idBuku,judul', 'peminjaman.member:id,name')
            ->where('statusBuku', 'Dipinjam')
            ->latest('id')
            ->take(3)
            ->get()
            ->each(function ($detail) use ($aktivitas) {
                if ($detail->peminjaman?->member) {
                    $aktivitas->push([
                        'timestamp' => $detail->created_at ?? now(),
                        'waktu' => ($detail->created_at ?? now())->format('H.i'),
                        'anggota' => $detail->peminjaman->member->name,
                        'deskripsi' => ($detail->buku?->judul ?? 'Buku').' dipinjam',
                    ]);
                }
            });

        // Pengembalian terbaru
        Pengembalian::with('peminjaman.member:id,name', 'peminjaman.details.buku:idBuku,judul')
            ->latest('idPengembalian')
            ->take(3)
            ->get()
            ->each(function ($kembali) use ($aktivitas) {
                if ($kembali->peminjaman?->member) {
                    $judulBuku = $kembali->peminjaman->details->first()?->buku?->judul ?? 'Buku';
                    $aktivitas->push([
                        'timestamp' => $kembali->created_at ?? now(),
                        'waktu' => ($kembali->created_at ?? now())->format('H.i'),
                        'anggota' => $kembali->peminjaman->member->name,
                        'deskripsi' => $judulBuku.' dikembalikan',
                    ]);
                }
            });

        // Pembayaran denda terbaru
        Pembayaran::with('denda.pengembalian.peminjaman.member:id,name')
            ->latest('idPembayaran')
            ->take(3)
            ->get()
            ->each(function ($bayar) use ($aktivitas) {
                $member = $bayar->denda?->pengembalian?->peminjaman?->member;
                if ($member) {
                    $nominal = number_format((float) ($bayar->nominal ?? 0), 0, ',', '.');
                    $aktivitas->push([
                        'timestamp' => $bayar->created_at ?? now(),
                        'waktu' => ($bayar->created_at ?? now())->format('H.i'),
                        'anggota' => $member->name,
                        'deskripsi' => "Denda Rp{$nominal} · ".($bayar->metode ?? 'QRIS'),
                    ]);
                }
            });

        return $aktivitas->sortByDesc('timestamp')->values()->take(3);
    }

    /**
     * Dashboard khusus anggota perpustakaan (member).
     */
    private function memberDashboard(Request $request, User $member): View
    {
        $kataKunci = trim((string) $request->query('q', ''));
        $dendaBelumDibayar = $this->dendaBelumDibayar($member);
        $dendaTerbaru = $dendaBelumDibayar->first();

        return view('dashboard.member', [
            'member' => $member,
            'statistik' => [
                'sedangDipinjam' => $this->detailMilik($member)->where('statusBuku', 'Dipinjam')->count(),
                'batasPinjam' => Peminjaman::BATAS_MAKSIMAL_BUKU,
                'totalDibaca' => $this->detailMilik($member)->where('statusBuku', 'Kembali')->count(),
                'jatuhTempo' => $this->jatuhTempoBerikutnya($member),
                'totalDenda' => (float) $dendaBelumDibayar->sum('jumlah'),
                'dendaTerbaru' => $dendaTerbaru ? $this->ringkasDenda($dendaTerbaru) : null,
            ],
            'kataKunci' => $kataKunci,
            'transaksi' => $this->transaksiTerbaru($member, $kataKunci),
            'rekomendasi' => $this->rekomendasiBuku($member),
        ]);
    }

    /**
     * Query dasar detail peminjaman milik member.
     *
     * @return Builder<DetailPeminjaman>
     */
    private function detailMilik(User $member): Builder
    {
        return DetailPeminjaman::query()
            ->whereHas('peminjaman', fn (Builder $query) => $query->where('idUserMember', $member->id));
    }

    /**
     * Buku yang sedang dipinjam dengan jatuh tempo paling dekat.
     *
     * @return array{tanggal: Carbon, judul: string, sisaHari: int}|null
     */
    private function jatuhTempoBerikutnya(User $member): ?array
    {
        $peminjaman = Peminjaman::query()
            ->where('idUserMember', $member->id)
            ->whereHas('details', fn (Builder $query) => $query->where('statusBuku', 'Dipinjam'))
            ->with(['details' => fn ($query) => $query->where('statusBuku', 'Dipinjam')->with('buku:idBuku,judul')])
            ->orderBy('batasKembali')
            ->first();

        if (! $peminjaman) {
            return null;
        }

        $tanggal = Carbon::parse($peminjaman->batasKembali)->startOfDay();

        return [
            'tanggal' => $tanggal,
            'judul' => $peminjaman->details->first()?->buku?->judul ?? 'Buku perpustakaan',
            'sisaHari' => (int) today()->diffInDays($tanggal, false),
        ];
    }

    /**
     * Denda member yang belum dibayar, terbaru lebih dulu.
     *
     * @return Collection<int, Denda>
     */
    private function dendaBelumDibayar(User $member): Collection
    {
        return Denda::query()
            ->whereHas('pengembalian.peminjaman', fn (Builder $query) => $query->where('idUserMember', $member->id))
            ->where('status', 'Belum Dibayar')
            ->with('pengembalian.peminjaman.details.buku:idBuku,judul')
            ->latest('idDenda')
            ->get();
    }

    /**
     * @return array{idDenda: int, judul: string, tanggalKembali: Carbon, hariTerlambat: int, jumlah: float}
     */
    private function ringkasDenda(Denda $denda): array
    {
        $pengembalian = $denda->pengembalian;
        $tanggalKembali = Carbon::parse($pengembalian->tanggalKembali)->startOfDay();
        $batasKembali = Carbon::parse($pengembalian->peminjaman->batasKembali)->startOfDay();

        return [
            'idDenda' => $denda->idDenda,
            'judul' => $pengembalian->peminjaman->details->first()?->buku?->judul ?? 'Buku perpustakaan',
            'tanggalKembali' => $tanggalKembali,
            'hariTerlambat' => max(0, (int) $batasKembali->diffInDays($tanggalKembali, false)),
            'jumlah' => (float) $denda->jumlah,
        ];
    }

    /**
     * Transaksi peminjaman terbaru milik member, bisa dicari berdasarkan judul atau kode buku.
     *
     * @return \Illuminate\Support\Collection<int, array{judul: string, kode: string, anggota: string, jatuhTempo: Carbon, status: string}>
     */
    private function transaksiTerbaru(User $member, string $kataKunci): \Illuminate\Support\Collection
    {
        return $this->detailMilik($member)
            ->with([
                'buku:idBuku,judul',
                'eksemplar:idEksemplar,kode_barcode',
                'peminjaman:idPeminjaman,batasKembali',
            ])
            ->when($kataKunci !== '', function (Builder $query) use ($kataKunci) {
                $likeOp = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
                $query->where(function (Builder $query) use ($kataKunci, $likeOp) {
                    $query->whereHas('buku', fn (Builder $buku) => $buku->where('judul', $likeOp, "%{$kataKunci}%"))
                        ->orWhereHas('eksemplar', fn (Builder $eksemplar) => $eksemplar->where('kode_barcode', $likeOp, "%{$kataKunci}%"));
                });
            })
            ->latest('id')
            ->take(self::JUMLAH_TRANSAKSI)
            ->get()
            ->map(function (DetailPeminjaman $detail) use ($member): array {
                $jatuhTempo = Carbon::parse($detail->peminjaman->batasKembali)->startOfDay();

                return [
                    'judul' => $detail->buku?->judul ?? 'Buku tidak ditemukan',
                    'kode' => $detail->eksemplar?->kode_barcode ?? sprintf('BK-%05d', $detail->idBuku),
                    'anggota' => $member->name,
                    'jatuhTempo' => $jatuhTempo,
                    'status' => $this->statusTransaksi($detail->statusBuku, $jatuhTempo),
                ];
            });
    }

    private function statusTransaksi(string $statusBuku, Carbon $jatuhTempo): string
    {
        if ($statusBuku === 'Booking' || $statusBuku === 'Siap Diambil') {
            return 'Booking';
        }

        if ($statusBuku === 'Diajukan Kembali') {
            return 'Diajukan Kembali';
        }

        if ($statusBuku === 'Dibatalkan') {
            return 'Dibatalkan';
        }

        if ($statusBuku === 'Kembali') {
            return 'Dikembalikan';
        }

        if ($statusBuku === 'Dipinjam') {
            return today()->greaterThan($jatuhTempo) ? 'Terlambat' : 'Dipinjam';
        }

        return $statusBuku;
    }

    /**
     * Rekomendasi buku tersedia dari kategori yang paling sering dipinjam member,
     * dilengkapi buku terbaru jika jumlahnya belum cukup.
     *
     * @return Collection<int, Buku>
     */
    private function rekomendasiBuku(User $member): Collection
    {
        $idBukuPernahDipinjam = $this->detailMilik($member)->distinct()->pluck('idBuku');

        $idKategoriFavorit = DetailPeminjaman::query()
            ->join('peminjaman', 'peminjaman.idPeminjaman', '=', 'detail_peminjaman.idPeminjaman')
            ->join('buku', 'buku.idBuku', '=', 'detail_peminjaman.idBuku')
            ->where('peminjaman.idUserMember', $member->id)
            ->groupBy('buku.idKategori')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(3)
            ->pluck('buku.idKategori');

        $bukuTersedia = fn (): Builder => Buku::query()
            ->with('kategori:idKategori,namaKategori')
            ->where('stok', '>', 0)
            ->whereNotIn('idBuku', $idBukuPernahDipinjam)
            ->latest('idBuku');

        $rekomendasi = $idKategoriFavorit->isEmpty()
            ? new Collection
            : $bukuTersedia()->whereIn('idKategori', $idKategoriFavorit)->take(self::JUMLAH_REKOMENDASI)->get();

        $kekurangan = self::JUMLAH_REKOMENDASI - $rekomendasi->count();

        if ($kekurangan > 0) {
            $rekomendasi = $rekomendasi->merge(
                $bukuTersedia()->whereNotIn('idBuku', $rekomendasi->modelKeys())->take($kekurangan)->get()
            );
        }

        return $rekomendasi;
    }
}
