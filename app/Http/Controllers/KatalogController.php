<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    // Aktor buka katalog & masukkan kata kunci/filter -> Sistem cari/filter & tampilkan daftar
    public function index(Request $request)
    {
        $keyword = $request->query('q');
        $kategoriId = $request->query('kategori');

        // Query buku dengan relasi kategori
        $query = Buku::with('kategori');

        // Filter kata kunci (judul, penulis, penerbit)
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                  ->orWhere('penulis', 'like', "%{$keyword}%")
                  ->orWhere('penerbit', 'like', "%{$keyword}%");
            });
        }

        // Filter berdasarkan kategori
        if (!empty($kategoriId)) {
            $query->where('idKategori', $kategoriId);
        }

        $bukus = $query->latest('idBuku')->paginate(8)->withQueryString();
        $kategoris = Kategori::orderBy('namaKategori')->get();

        return view('katalog.index', compact('bukus', 'kategoris', 'keyword', 'kategoriId'));
    }

    // Tampilkan detail buku
    public function show($id)
    {
        $buku = Buku::with(['kategori', 'barcode'])->findOrFail($id);

        return view('katalog.show', compact('buku'));
    }
}