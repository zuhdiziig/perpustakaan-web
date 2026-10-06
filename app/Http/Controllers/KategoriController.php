<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // Buka menu kategori & tampilkan daftar kategori terbaru
    public function index()
    {
        $kategoris = Kategori::withCount('buku')->latest('idKategori')->paginate(10);

        return view('kategori.index', compact('kategoris'));
    }

    // Validasi & Simpan kategori baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'namaKategori' => ['required', 'string', 'max:255', 'unique:kategori,namaKategori'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ], [
            'namaKategori.required' => 'Nama kategori wajib diisi.',
            'namaKategori.unique' => 'Nama kategori sudah ada.',
        ]);

        Kategori::create($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    // Tampilkan data untuk form ubah kategori
    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);

        return view('kategori.edit', compact('kategori'));
    }

    // Validasi & Simpan perubahan kategori
    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $validated = $request->validate([
            'namaKategori' => ['required', 'string', 'max:255', 'unique:kategori,namaKategori,'.$id.',idKategori'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ], [
            'namaKategori.required' => 'Nama kategori wajib diisi.',
            'namaKategori.unique' => 'Nama kategori sudah ada.',
        ]);

        $kategori->update($validated);

        return redirect()->route('kategori.index')->with('success', 'Data kategori berhasil diperbarui.');
    }

    // Hapus kategori
    public function destroy($id)
    {
        $kategori = Kategori::withCount('buku')->findOrFail($id);

        // Proteksi jika masih ada buku yang terikat
        if ($kategori->buku_count > 0) {
            return redirect()->route('kategori.index')->with('error', 'Kategori tidak dapat dihapus karena masih memuat data buku.');
        }

        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
