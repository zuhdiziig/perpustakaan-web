<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    // Buka menu data buku & tampilkan data terbaru
    public function index()
    {
        $bukus = Buku::with(['kategori', 'barcode'])->latest('idBuku')->paginate(10);

        return view('buku.index', compact('bukus'));
    }

    // Tampilkan form tambah buku
    public function create()
    {
        $kategoris = Kategori::orderBy('namaKategori')->get();

        return view('buku.create', compact('kategoris'));
    }

    // Validasi & Simpan data buku baru beserta barcode
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idKategori' => ['required', 'exists:kategori,idKategori'],
            'judul' => ['required', 'string', 'max:255'],
            'penulis' => ['required', 'string', 'max:255'],
            'penerbit' => ['required', 'string', 'max:255'],
            'tahunTerbit' => ['required', 'numeric', 'digits:4'],
            'harga' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'kondisi' => ['required', 'in:Baik,Rusak,Hilang'],
            'kodeBarcode' => ['required', 'string', 'max:50', 'unique:barcode,kodeBarcode'],
        ], [
            'idKategori.required' => 'Pilih kategori buku.',
            'judul.required' => 'Judul buku wajib diisi.',
            'penulis.required' => 'Nama penulis wajib diisi.',
            'penerbit.required' => 'Penerbit wajib diisi.',
            'tahunTerbit.required' => 'Tahun terbit wajib diisi (4 digit).',
            'harga.required' => 'Harga buku wajib diisi.',
            'stok.required' => 'Jumlah stok wajib diisi.',
            'kodeBarcode.required' => 'Kode barcode unik wajib diisi.',
            'kodeBarcode.unique' => 'Kode barcode ini sudah terdaftar.',
        ]);

        DB::transaction(function () use ($validated) {
            $buku = Buku::create([
                'idKategori' => $validated['idKategori'],
                'judul' => $validated['judul'],
                'penulis' => $validated['penulis'],
                'penerbit' => $validated['penerbit'],
                'tahunTerbit' => $validated['tahunTerbit'],
                'harga' => $validated['harga'],
                'stok' => $validated['stok'],
                'kondisi' => $validated['kondisi'],
            ]);

            Barcode::create([
                'idBuku' => $buku->idBuku,
                'kodeBarcode' => $validated['kodeBarcode'],
            ]);
        });

        return redirect()->route('buku.index')->with('success', 'Data buku dan barcode berhasil ditambahkan.');
    }

    // Tampilkan form ubah data buku
    public function edit($id)
    {
        $buku = Buku::with('barcode')->findOrFail($id);
        $kategoris = Kategori::orderBy('namaKategori')->get();

        return view('buku.edit', compact('buku', 'kategoris'));
    }

    // Validasi & Simpan perubahan data buku
    public function update(Request $request, $id)
    {
        $buku = Buku::with('barcode')->findOrFail($id);

        $validated = $request->validate([
            'idKategori' => ['required', 'exists:kategori,idKategori'],
            'judul' => ['required', 'string', 'max:255'],
            'penulis' => ['required', 'string', 'max:255'],
            'penerbit' => ['required', 'string', 'max:255'],
            'tahunTerbit' => ['required', 'numeric', 'digits:4'],
            'harga' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'kondisi' => ['required', 'in:Baik,Rusak,Hilang'],
            'kodeBarcode' => ['required', 'string', 'max:50', 'unique:barcode,kodeBarcode,'.($buku->barcode->idBarcode ?? 'NULL').',idBarcode'],
        ]);

        DB::transaction(function () use ($buku, $validated) {
            $buku->update([
                'idKategori' => $validated['idKategori'],
                'judul' => $validated['judul'],
                'penulis' => $validated['penulis'],
                'penerbit' => $validated['penerbit'],
                'tahunTerbit' => $validated['tahunTerbit'],
                'harga' => $validated['harga'],
                'stok' => $validated['stok'],
                'kondisi' => $validated['kondisi'],
            ]);

            if ($buku->barcode) {
                $buku->barcode->update(['kodeBarcode' => $validated['kodeBarcode']]);
            } else {
                Barcode::create([
                    'idBuku' => $buku->idBuku,
                    'kodeBarcode' => $validated['kodeBarcode'],
                ]);
            }
        });

        return redirect()->route('buku.index')->with('success', 'Perubahan data buku berhasil disimpan.');
    }

    // Hapus data buku
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete(); // Barcode akan otomatis terhapus karena onDelete cascade

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil dihapus.');
    }
}
