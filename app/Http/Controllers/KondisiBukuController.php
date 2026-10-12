<?php

namespace App\Http\Controllers;

use App\Models\BukuEksemplar;
use Illuminate\Http\Request;

class KondisiBukuController extends Controller
{
    // Halaman daftar pemeriksaan kondisi eksemplar fisik buku
    public function index(Request $request)
    {
        $statusFilter = $request->query('kondisi');
        $lokasiFilter = $request->query('lokasi');
        $statusInventaris = $request->query('status');
        $search = $request->query('q');

        $query = BukuEksemplar::with(['buku.kategori', 'buku.barcode']);

        if (! empty($statusFilter)) {
            $query->where('kondisi', $statusFilter);
        }

        if (! empty($lokasiFilter)) {
            $query->where('lokasi', $lokasiFilter);
        }

        if (! empty($statusInventaris)) {
            $query->where('status', $statusInventaris);
        }

        if (! empty($search)) {
            $query->where(function ($sub) use ($search) {
                $sub->where('qr_token', 'like', "%{$search}%")
                    ->orWhere('kode_barcode', 'like', "%{$search}%")
                    ->orWhere('nomor_eksemplar', 'like', "%{$search}%")
                    ->orWhereHas('buku', function ($bukuQuery) use ($search) {
                        $bukuQuery->where('judul', 'like', "%{$search}%")
                            ->orWhere('penulis', 'like', "%{$search}%")
                            ->orWhereHas('barcode', function ($barQuery) use ($search) {
                                $barQuery->where('kodeBarcode', 'like', "%{$search}%");
                            });
                    });
            });
        }

        $eksemplars = $query->orderBy('idBuku')->orderBy('nomor_eksemplar')->paginate(15)->withQueryString();

        $stats = [
            'total' => BukuEksemplar::count(),
            'baik' => BukuEksemplar::where('kondisi', 'Baik')->count(),
            'rusak' => BukuEksemplar::where('kondisi', 'Rusak')->count(),
            'hilang' => BukuEksemplar::where('kondisi', 'Hilang')->count(),
        ];

        return view('kondisi.index', compact('eksemplars', 'statusFilter', 'lokasiFilter', 'statusInventaris', 'search', 'stats'));
    }

    // Form input pemeriksaan kondisi fisik eksemplar buku
    public function edit($id)
    {
        $eksemplar = BukuEksemplar::with(['buku.kategori', 'buku.barcode'])->findOrFail($id);

        return view('kondisi.edit', compact('eksemplar'));
    }

    // Simpan hasil pemeriksaan kondisi eksemplar fisik buku
    public function update(Request $request, $id)
    {
        $eksemplar = BukuEksemplar::with('buku')->findOrFail($id);

        $validated = $request->validate([
            'kondisi' => ['required', 'in:Baik,Rusak,Hilang'],
            'lokasi' => ['nullable', 'in:Rak,Gudang'],
            'catatan' => ['nullable', 'string', 'max:255'],
        ]);

        $kondisiBaru = $validated['kondisi'];
        $lokasiBaru = $validated['lokasi'] ?? $eksemplar->lokasi;

        // Jika kondisi Rusak, otomatis karantina ke Gudang
        if ($kondisiBaru === 'Rusak') {
            $lokasiBaru = 'Gudang';
        }

        $statusUpdate = $eksemplar->status;
        if ($kondisiBaru === 'Hilang') {
            $statusUpdate = 'Hilang';
        } elseif ($eksemplar->status === 'Hilang' && in_array($kondisiBaru, ['Baik', 'Rusak'], true)) {
            $statusUpdate = 'Tersedia';
        }

        $eksemplar->update([
            'kondisi' => $kondisiBaru,
            'lokasi' => $lokasiBaru,
            'status' => $statusUpdate,
        ]);

        // Pastikan sinkronisasi stok master buku
        $eksemplar->buku?->syncStok();

        // Hitung estimasi biaya penggantian jika rusak atau hilang
        $biayaKerusakan = 0;
        if (in_array($kondisiBaru, ['Rusak', 'Hilang'], true)) {
            $biayaKerusakan = (float) ($eksemplar->buku?->harga ?? 0);
        }

        return redirect()->route('kondisi.index')->with([
            'success' => 'Kondisi fisik eksemplar buku berhasil diperbarui.',
            'judulBuku' => $eksemplar->buku?->judul,
            'nomorEksemplar' => $eksemplar->nomor_eksemplar,
            'kondisi' => $kondisiBaru,
            'lokasi' => $lokasiBaru,
            'biayaKerusakan' => $biayaKerusakan,
        ]);
    }
}
