<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\Buku;
use App\Models\BukuEksemplar;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BukuController extends Controller
{
    // Buka menu data inventaris buku & tampilkan 1 baris per judul buku dengan rincian eksemplar
    public function index(Request $request)
    {
        $query = Buku::with([
            'kategori',
            'barcode',
            'eksemplar' => function ($q) {
                $q->with('activeDetail.peminjaman.member')->orderBy('nomor_eksemplar');
            },
        ]);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%")
                    ->orWhere('penerbit', 'like', "%{$search}%")
                    ->orWhereHas('barcode', function ($bar) use ($search) {
                        $bar->where('kodeBarcode', 'like', "%{$search}%");
                    })
                    ->orWhereHas('eksemplar', function ($eks) use ($search) {
                        if (is_numeric($search)) {
                            $eks->where('idEksemplar', (int) $search)
                                ->orWhere('nomor_eksemplar', (int) $search);
                        }
                        $eks->orWhere('qr_token', 'like', "%{$search}%")
                            ->orWhere('kode_barcode', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('kategori')) {
            $query->where('idKategori', $request->kategori);
        }

        if ($request->filled('kondisi') && in_array($request->kondisi, ['Baik', 'Rusak', 'Hilang'], true)) {
            $query->whereHas('eksemplar', function ($q) use ($request) {
                $q->where('kondisi', $request->kondisi);
            });
        }

        if ($request->filled('status') && in_array($request->status, ['Tersedia', 'Dibooking', 'Dipinjam', 'Hilang'], true)) {
            $query->whereHas('eksemplar', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->filled('lokasi') && in_array($request->lokasi, ['Rak', 'Gudang'], true)) {
            $query->whereHas('eksemplar', function ($q) use ($request) {
                $q->where('lokasi', $request->lokasi);
            });
        }

        // Cari judul buku yang display di raknya sudah kosong tetapi masih memiliki stok cadangan di gudang
        $bukuPerluRestockIds = Buku::whereDoesntHave('eksemplar', function ($q) {
            $q->where('status', 'Tersedia')->where('lokasi', 'Rak');
        })->whereHas('eksemplar', function ($q) {
            $q->where('status', 'Tersedia')->where('lokasi', 'Gudang');
        })->pluck('idBuku');

        $jumlahPerluRestock = $bukuPerluRestockIds->count();

        if ($request->boolean('perlu_restock')) {
            $query->whereIn('idBuku', $bukuPerluRestockIds);
        }

        if ($request->filled('buku_id')) {
            $query->where('idBuku', $request->buku_id);
        }

        $bukus = $query->latest('idBuku')->paginate(10)->withQueryString();
        $eksemplars = $bukus; // Alias kompatibilitas
        $kategoris = Kategori::orderBy('namaKategori')->get();

        $totalJudul = Buku::count();
        $totalEksemplar = BukuEksemplar::count();
        $totalStok = (int) BukuEksemplar::where('status', 'Tersedia')->count();
        $eksemplarTersedia = $totalStok;
        $eksemplarDiRak = BukuEksemplar::where('status', 'Tersedia')->where('lokasi', 'Rak')->count();
        $eksemplarDiGudang = BukuEksemplar::where('status', 'Tersedia')->where('lokasi', 'Gudang')->count();
        $eksemplarDipinjam = BukuEksemplar::whereIn('status', ['Dipinjam', 'Dibooking'])->count();
        $bukuBaik = BukuEksemplar::where('kondisi', 'Baik')->count();
        $bukuRusak = BukuEksemplar::whereIn('kondisi', ['Rusak', 'Hilang'])->count();

        return view('buku.index', compact(
            'eksemplars',
            'bukus',
            'kategoris',
            'totalJudul',
            'totalEksemplar',
            'totalStok',
            'eksemplarTersedia',
            'eksemplarDiRak',
            'eksemplarDiGudang',
            'eksemplarDipinjam',
            'bukuBaik',
            'bukuRusak',
            'jumlahPerluRestock'
        ));
    }

    // Tampilkan form tambah buku
    public function create()
    {
        $kategoris = Kategori::orderBy('namaKategori')->get();

        return view('buku.create', compact('kategoris'));
    }

    // Validasi & Simpan data buku baru beserta barcode & cover
    public function store(Request $request)
    {
        if ($request->has('harga')) {
            $cleanedHarga = preg_replace('/[^0-9]/', '', (string) $request->harga);
            $request->merge(['harga' => $cleanedHarga === '' ? 0 : (float) $cleanedHarga]);
        }

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
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
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
            'cover.image' => 'File cover harus berupa file gambar.',
            'cover.mimes' => 'Format file gambar harus JPEG, PNG, JPG, atau WebP.',
            'cover.max' => 'Ukuran file gambar maksimal 2 MB.',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        DB::transaction(function () use ($validated, $coverPath) {
            $buku = Buku::create([
                'idKategori' => $validated['idKategori'],
                'judul' => $validated['judul'],
                'penulis' => $validated['penulis'],
                'penerbit' => $validated['penerbit'],
                'tahunTerbit' => $validated['tahunTerbit'],
                'harga' => $validated['harga'],
                'stok' => $validated['stok'],
                'kondisi' => $validated['kondisi'],
                'cover' => $coverPath,
            ]);

            Barcode::create([
                'idBuku' => $buku->idBuku,
                'kodeBarcode' => $validated['kodeBarcode'],
            ]);
        });

        return redirect()->route('buku.index')->with('success', 'Data buku, barcode, dan cover berhasil ditambahkan.');
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

        if ($request->has('harga')) {
            $cleanedHarga = preg_replace('/[^0-9]/', '', (string) $request->harga);
            $request->merge(['harga' => $cleanedHarga === '' ? 0 : (float) $cleanedHarga]);
        }

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
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'idKategori.required' => 'Pilih kategori buku.',
            'judul.required' => 'Judul buku wajib diisi.',
            'penulis.required' => 'Nama penulis wajib diisi.',
            'penerbit.required' => 'Penerbit wajib diisi.',
            'tahunTerbit.required' => 'Tahun terbit wajib diisi (4 digit).',
            'harga.required' => 'Harga buku wajib diisi.',
            'stok.required' => 'Jumlah stok wajib diisi.',
            'kodeBarcode.required' => 'Kode barcode unik wajib diisi.',
            'cover.image' => 'File cover harus berupa file gambar.',
            'cover.mimes' => 'Format file gambar harus JPEG, PNG, JPG, atau WebP.',
            'cover.max' => 'Ukuran file gambar maksimal 2 MB.',
        ]);

        $coverPath = $buku->cover;

        if ($request->boolean('hapus_cover')) {
            if ($buku->cover && ! Str::startsWith($buku->cover, ['http://', 'https://']) && Storage::disk('public')->exists($buku->cover)) {
                Storage::disk('public')->delete($buku->cover);
            }
            $coverPath = null;
        }

        if ($request->hasFile('cover')) {
            if ($buku->cover && ! Str::startsWith($buku->cover, ['http://', 'https://']) && Storage::disk('public')->exists($buku->cover)) {
                Storage::disk('public')->delete($buku->cover);
            }
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        DB::transaction(function () use ($buku, $validated, $coverPath) {
            $currentEksemplarCount = $buku->eksemplar()->count();
            $newStok = (int) $validated['stok'];

            // Jika stok bertambah, buat eksemplar fisik baru sesuai kekurangan
            if ($newStok > $currentEksemplarCount) {
                $maxNomor = (int) ($buku->eksemplar()->max('nomor_eksemplar') ?? 0);
                $tambahan = $newStok - $currentEksemplarCount;
                for ($i = 1; $i <= $tambahan; $i++) {
                    $buku->eksemplar()->create([
                        'nomor_eksemplar' => $maxNomor + $i,
                        'qr_token' => 'bk_'.bin2hex(random_bytes(16)),
                        'kondisi' => $validated['kondisi'] ?? 'Baik',
                        'status' => 'Tersedia',
                    ]);
                }
            }

            $buku->update([
                'idKategori' => $validated['idKategori'],
                'judul' => $validated['judul'],
                'penulis' => $validated['penulis'],
                'penerbit' => $validated['penerbit'],
                'tahunTerbit' => $validated['tahunTerbit'],
                'harga' => $validated['harga'],
                'stok' => $newStok,
                'kondisi' => $validated['kondisi'],
                'cover' => $coverPath,
            ]);

            // Sinkronkan stok riil buku berdasarkan jumlah eksemplar fisik Tersedia
            $buku->syncStok();

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

        $hasActiveLoans = $buku->eksemplar()
            ->whereIn('status', ['Dipinjam', 'Dibooking'])
            ->exists();

        if ($hasActiveLoans) {
            return back()->with('error', "Buku '{$buku->judul}' tidak dapat dihapus karena masih memiliki eksemplar fisik yang sedang dipinjam atau dibooking oleh anggota.");
        }

        if ($buku->cover && ! Str::startsWith($buku->cover, ['http://', 'https://']) && Storage::disk('public')->exists($buku->cover)) {
            Storage::disk('public')->delete($buku->cover);
        }
        $buku->delete(); // Barcode & eksemplar akan otomatis terhapus karena onDelete cascade

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil dihapus.');
    }

    /**
     * Update stok fisik buku secara langsung oleh Petugas/Admin meja sirkulasi.
     */
    public function updateStok(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $validated = $request->validate([
            'stok' => ['required', 'integer', 'min:0'],
            'kondisi' => ['nullable', 'in:Baik,Rusak,Hilang'],
        ]);

        DB::transaction(function () use ($buku, $validated) {
            $newStok = (int) $validated['stok'];
            $currentEksemplarCount = $buku->eksemplar()->count();

            if ($newStok > $currentEksemplarCount) {
                $maxNomor = (int) ($buku->eksemplar()->max('nomor_eksemplar') ?? 0);
                $tambahan = $newStok - $currentEksemplarCount;
                for ($i = 1; $i <= $tambahan; $i++) {
                    $buku->eksemplar()->create([
                        'nomor_eksemplar' => $maxNomor + $i,
                        'qr_token' => 'bk_'.bin2hex(random_bytes(16)),
                        'kondisi' => $validated['kondisi'] ?? ($buku->kondisi ?? 'Baik'),
                        'status' => 'Tersedia',
                    ]);
                }
            } elseif ($newStok < $currentEksemplarCount) {
                $selisih = $currentEksemplarCount - $newStok;
                $tersedia = $buku->eksemplar()->where('status', 'Tersedia')->latest('idEksemplar')->take($selisih)->get();
                foreach ($tersedia as $eks) {
                    $eks->delete();
                }
            }

            if (! empty($validated['kondisi'])) {
                $buku->update(['kondisi' => $validated['kondisi']]);
            }

            $buku->syncStok();
        });

        return back()->with('success', 'Stok buku "'.$buku->judul.'" berhasil diperbarui.');
    }
}
