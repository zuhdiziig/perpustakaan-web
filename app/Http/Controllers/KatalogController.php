<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KatalogController extends Controller
{
    /**
     * Jumlah buku per halaman (grid 4 kolom x 2 baris).
     */
    private const PER_HALAMAN = 8;

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

        // 1. Pencarian bebas (judul, penulis, penerbit, kategori)
        if ($keyword !== '') {
            $query->where(function (Builder $q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                    ->orWhere('penulis', 'like', "%{$keyword}%")
                    ->orWhere('penerbit', 'like', "%{$keyword}%")
                    ->orWhereHas('kategori', fn (Builder $kat) => $kat->where('namaKategori', 'like', "%{$keyword}%"));
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
     * Menampilkan detail buku tertentu beserta rak dan eksemplar.
     */
    public function show(int|string $id): View
    {
        $buku = Buku::with(['kategori', 'barcode', 'eksemplar'])->findOrFail($id);

        $bukuTerkait = Buku::where('idKategori', $buku->idKategori)
            ->where('idBuku', '!=', $buku->idBuku)
            ->take(4)
            ->get();

        return view('katalog.show', [
            'buku' => $buku,
            'bukuTerkait' => $bukuTerkait,
        ]);
    }
}
