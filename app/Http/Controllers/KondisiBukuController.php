<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class KondisiBukuController extends Controller
{
    // Halaman daftar pemeriksaan kondisi buku
    public function index(Request $request)
    {
        $statusFilter = $request->query('kondisi');
        $query = Buku::with(['kategori', 'barcode']);

        if (! empty($statusFilter)) {
            $query->where('kondisi', $statusFilter);
        }

        $bukus = $query->orderBy('judul')->paginate(10)->withQueryString();

        return view('kondisi.index', compact('bukus', 'statusFilter'));
    }

    // Form input pemeriksaan kondisi fisik buku
    public function edit($id)
    {
        $buku = Buku::with(['kategori', 'barcode'])->findOrFail($id);

        return view('kondisi.edit', compact('buku'));
    }

    // Terima hasil pemeriksaan -> Simpan kondisi -> Evaluasi rusak/hilang -> Tampilkan biaya
    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $validated = $request->validate([
            'kondisi' => ['required', 'in:Baik,Rusak,Hilang'],
            'catatan' => ['nullable', 'string', 'max:255'],
        ]);

        $kondisiLama = $buku->kondisi;
        $kondisiBaru = $validated['kondisi'];

        // Simpan kondisi buku
        $buku->update([
            'kondisi' => $kondisiBaru,
        ]);

        // Evaluasi: Kondisi rusak/hilang?
        $biayaKerusakan = 0;
        if (in_array($kondisiBaru, ['Rusak', 'Hilang'])) {
            // Hitung biaya sesuai harga buku (100% harga buku)
            $biayaKerusakan = (float) $buku->harga;
        }

        return redirect()->route('kondisi.index')->with([
            'success' => 'Kondisi buku berhasil diperbarui.',
            'judulBuku' => $buku->judul,
            'kondisi' => $kondisiBaru,
            'biayaKerusakan' => $biayaKerusakan,
        ]);
    }
}
