<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KatalogController extends Controller
{
    /**
     * Jumlah buku per halaman (grid 4 kolom x 2 baris).
     */
    private const PER_HALAMAN = 8;

    /**
     * Jumlah rekomendasi "Bacaan lain yang mungkin kamu suka" di halaman detail.
     */
    private const JUMLAH_BUKU_TERKAIT = 4;

    /**
     * Menampilkan katalog buku untuk umum dan anggota perpustakaan.
     */
    public function index(Request $request): View
    {
        $keyword = trim((string) $request->query('q', ''));
        $kategoriId = $request->query('kategori');
        $status = $request->query('status');
        $lokasi = $request->query('lokasi', 'Perpustakaan Pusat');
        $sort = $request->query('sort', 'popularitas');

        $query = Buku::query()
            ->with(['kategori', 'barcode'])
            ->withCount('detailPeminjaman');

        // 1. Pencarian bebas fleksibel & tidak sensitif huruf besar/kecil (case-insensitive)
        if ($keyword !== '') {
            $likeOp = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
            $cleanKeyword = preg_replace('/\s+/', ' ', $keyword);
            $words = array_values(array_filter(explode(' ', $cleanKeyword)));

            $query->where(function (Builder $q) use ($cleanKeyword, $words, $likeOp) {
                // Prioritas 1: Cocokkan seluruh frasa
                $q->where('judul', $likeOp, "%{$cleanKeyword}%")
                    ->orWhere('penulis', $likeOp, "%{$cleanKeyword}%")
                    ->orWhere('penerbit', $likeOp, "%{$cleanKeyword}%")
                    ->orWhereHas('kategori', fn (Builder $kat) => $kat->where('namaKategori', $likeOp, "%{$cleanKeyword}%"));

                // Prioritas 2: Jika terdiri dari beberapa kata, cocokkan setiap kata di antara atribut buku
                if (count($words) > 1) {
                    $q->orWhere(function (Builder $multiQ) use ($words, $likeOp) {
                        foreach ($words as $word) {
                            $multiQ->where(function (Builder $wordQ) use ($word, $likeOp) {
                                $wordQ->where('judul', $likeOp, "%{$word}%")
                                    ->orWhere('penulis', $likeOp, "%{$word}%")
                                    ->orWhere('penerbit', $likeOp, "%{$word}%")
                                    ->orWhereHas('kategori', fn (Builder $kat) => $kat->where('namaKategori', $likeOp, "%{$word}%"));
                            });
                        }
                    });
                }
            });
        }

        // 2. Filter Kategori
        if (! empty($kategoriId) && $kategoriId !== 'semua') {
            $query->where('idKategori', $kategoriId);
        }

        // 3. Filter Status Ketersediaan
        if ($status === 'tersedia') {
            $query->where('stok', '>', 0);
        } elseif ($status === 'habis') {
            $query->where('stok', '<=', 0);
        }

        // 4. Pengurutan buku
        match ($sort) {
            'terbaru' => $query->latest('idBuku'),
            'judul_asc' => $query->orderBy('judul', 'asc'),
            'judul_desc' => $query->orderBy('judul', 'desc'),
            default => $query->orderByDesc('detail_peminjaman_count')->latest('idBuku'), // Popularitas
        };

        $bukus = $query->paginate(self::PER_HALAMAN)->withQueryString();
        $kategoris = Kategori::withCount('buku')->orderBy('namaKategori')->get();

        return view('katalog.index', [
            'bukus' => $bukus,
            'kategoris' => $kategoris,
            'keyword' => $keyword,
            'kategoriId' => $kategoriId,
            'status' => $status,
            'lokasi' => $lokasi,
            'sort' => $sort,
        ]);
    }

    /**
     * Menampilkan detail buku tertentu beserta lokasi rak, ketersediaan eksemplar, dan rekomendasi bacaan.
     */
    public function show(int|string $id): View
    {
        $buku = Buku::query()
            ->with(['kategori', 'barcode'])
            ->withCount('eksemplar')
            ->findOrFail($id);

        return view('katalog.show', [
            'buku' => $buku,
            'totalEksemplar' => max($buku->eksemplar_count, (int) $buku->stok),
            'kodeBuku' => $buku->barcode?->kodeBarcode ?? sprintf('BK-%05d', $buku->idBuku),
            'bukuTerkait' => $this->bukuTerkait($buku),
            'masaPinjamBulan' => Peminjaman::MASA_PINJAM_BULAN,
            'batasMaksimalBuku' => Peminjaman::BATAS_MAKSIMAL_BUKU,
            'urlKembali' => $this->urlKembaliKeKatalog(),
        ]);
    }

    /**
     * Mengambil rekomendasi bacaan: prioritas kategori yang sama, lalu dilengkapi buku terpopuler lainnya.
     *
     * @return Collection<int, Buku>
     */
    private function bukuTerkait(Buku $buku): Collection
    {
        $kolomKartu = ['idBuku', 'idKategori', 'judul', 'penulis', 'stok', 'cover', 'jumlahHalaman', 'rak'];

        $sekategori = Buku::query()
            ->select($kolomKartu)
            ->with('kategori:idKategori,namaKategori')
            ->where('idKategori', $buku->idKategori)
            ->whereKeyNot($buku->idBuku)
            ->withCount('detailPeminjaman')
            ->orderByDesc('detail_peminjaman_count')
            ->take(self::JUMLAH_BUKU_TERKAIT)
            ->get();

        $kekurangan = self::JUMLAH_BUKU_TERKAIT - $sekategori->count();

        if ($kekurangan <= 0) {
            return $sekategori;
        }

        $pelengkap = Buku::query()
            ->select($kolomKartu)
            ->with('kategori:idKategori,namaKategori')
            ->whereKeyNot([$buku->idBuku, ...$sekategori->modelKeys()])
            ->withCount('detailPeminjaman')
            ->orderByDesc('detail_peminjaman_count')
            ->latest('idBuku')
            ->take($kekurangan)
            ->get();

        return $sekategori->concat($pelengkap);
    }

    /**
     * URL tombol "Kembali": kembali ke katalog beserta filter sebelumnya bila pengunjung datang dari katalog.
     */
    private function urlKembaliKeKatalog(): string
    {
        $urlSebelumnya = url()->previous();
        $urlKatalog = route('katalog.index');

        return str_starts_with($urlSebelumnya, $urlKatalog) && ! str_starts_with($urlSebelumnya, $urlKatalog.'/')
            ? $urlSebelumnya
            : $urlKatalog;
    }
}
