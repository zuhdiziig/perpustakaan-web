<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\BukuEksemplar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukuEksemplarController extends Controller
{
    /**
     * Tambah eksemplar fisik baru untuk judul buku tertentu.
     */
    public function store(Request $request, $idBuku)
    {
        $buku = Buku::findOrFail($idBuku);

        $validated = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1', 'max:50'],
            'kondisi' => ['required', 'in:Baik,Rusak,Hilang'],
            'lokasi' => ['nullable', 'in:Rak,Gudang'],
        ], [
            'jumlah.required' => 'Jumlah eksemplar wajib diisi.',
            'jumlah.min' => 'Jumlah eksemplar minimal 1 buku.',
            'jumlah.max' => 'Maksimal penambahan 50 eksemplar sekaligus.',
            'kondisi.required' => 'Kondisi fisik eksemplar wajib dipilih.',
        ]);

        $created = DB::transaction(function () use ($buku, $validated) {
            $status = ($validated['kondisi'] === 'Hilang') ? 'Hilang' : 'Tersedia';
            $items = [];
            $maxNomor = (int) ($buku->eksemplar()->max('nomor_eksemplar') ?? 0);
            $currentInRak = $buku->eksemplarDiRak()->count();

            for ($i = 1; $i <= (int) $validated['jumlah']; $i++) {
                $lokasi = $validated['lokasi'] ?? (($currentInRak + $i <= 2) ? 'Rak' : 'Gudang');

                $items[] = $buku->eksemplar()->create([
                    'nomor_eksemplar' => $maxNomor + $i,
                    'qr_token' => 'bk_'.bin2hex(random_bytes(16)),
                    'kondisi' => $validated['kondisi'],
                    'status' => $status,
                    'lokasi' => $lokasi,
                ]);
            }

            $buku->syncStok();

            return $items;
        });

        $count = count($created);

        return back()->with('success', "Berhasil menambahkan {$count} eksemplar fisik baru untuk buku '{$buku->judul}'.");
    }

    /**
     * Pindahkan eksemplar dari Gudang ke Rak display.
     */
    public function restock(Request $request, $idEksemplar)
    {
        $eksemplar = BukuEksemplar::with('buku')->findOrFail($idEksemplar);

        if ($eksemplar->status !== 'Tersedia') {
            return back()->with('error', "Eksemplar #{$eksemplar->nomor_eksemplar} tidak berstatus Tersedia (status: {$eksemplar->status}). Hanya eksemplar yang tersedia yang dapat dipajang.");
        }

        $eksemplar->pindahKeRak();

        return back()->with('success', "Eksemplar #{$eksemplar->nomor_eksemplar} (ID: {$eksemplar->idEksemplar}) buku '{$eksemplar->buku->judul}' berhasil dipajang ke Rak Display.");
    }

    /**
     * Pindahkan eksemplar dari Rak ke Gudang penyimpanan.
     */
    public function simpanKeGudang(Request $request, $idEksemplar)
    {
        $eksemplar = BukuEksemplar::with('buku')->findOrFail($idEksemplar);

        if ($eksemplar->isSedangDipinjam()) {
            return back()->with('error', "Eksemplar #{$eksemplar->nomor_eksemplar} sedang {$eksemplar->status} oleh anggota dan belum dapat dipindahkan.");
        }

        $eksemplar->pindahKeGudang();

        return back()->with('success', "Eksemplar #{$eksemplar->nomor_eksemplar} (ID: {$eksemplar->idEksemplar}) buku '{$eksemplar->buku->judul}' berhasil disimpan ke Gudang Cadangan.");
    }

    /**
     * Restock otomatis 1 eksemplar dari Gudang ke Rak untuk judul buku tertentu.
     */
    public function restockBuku(Request $request, $idBuku)
    {
        $buku = Buku::findOrFail($idBuku);
        $restocked = $buku->restockKeRak(1);

        if ($restocked === 0) {
            return back()->with('error', "Tidak ada stok cadangan di gudang untuk buku '{$buku->judul}'.");
        }

        return back()->with('success', "Buku '{$buku->judul}' berhasil direstock ke Rak Display sebanyak {$restocked} eksemplar.");
    }

    /**
     * Perbarui kondisi fisik, status, dan/atau lokasi eksemplar individual.
     */
    public function updateKondisi(Request $request, $idEksemplar)
    {
        $eksemplar = BukuEksemplar::with('buku')->findOrFail($idEksemplar);

        $validated = $request->validate([
            'kondisi' => ['required', 'in:Baik,Rusak,Hilang'],
            'status' => ['nullable', 'in:Tersedia,Hilang'],
            'lokasi' => ['nullable', 'in:Rak,Gudang'],
        ], [
            'kondisi.required' => 'Pilih kondisi fisik eksemplar.',
        ]);

        // Cek apakah eksemplar sedang dipinjam atau dibooking
        if ($eksemplar->isSedangDipinjam()) {
            if ($request->filled('status') && $request->status !== $eksemplar->status) {
                return back()->with('error', "Eksemplar #{$eksemplar->nomor_eksemplar} sedang berstatus '{$eksemplar->status}'. Status tidak dapat diubah langsung saat buku sedang dalam transaksi aktif.");
            }
        }

        DB::transaction(function () use ($eksemplar, $validated) {
            $updates = [
                'kondisi' => $validated['kondisi'],
            ];

            if (! $eksemplar->isSedangDipinjam()) {
                if ($validated['kondisi'] === 'Hilang') {
                    $updates['status'] = 'Hilang';
                } elseif (isset($validated['status'])) {
                    $updates['status'] = $validated['status'];
                }

                if (isset($validated['lokasi'])) {
                    $updates['lokasi'] = $validated['lokasi'];
                }
            }

            $eksemplar->update($updates);
            $eksemplar->buku?->syncStok();
        });

        return back()->with('success', "Data eksemplar #{$eksemplar->nomor_eksemplar} (ID: {$eksemplar->idEksemplar}) buku '{$eksemplar->buku->judul}' berhasil diperbarui.");
    }

    /**
     * Hapus eksemplar fisik individual (hanya bila tidak sedang dipinjam).
     */
    public function destroy(Request $request, $idEksemplar)
    {
        $eksemplar = BukuEksemplar::with('buku')->findOrFail($idEksemplar);

        if ($eksemplar->isSedangDipinjam()) {
            return back()->with('error', "Gagal menghapus: Eksemplar #{$eksemplar->nomor_eksemplar} sedang {$eksemplar->status} oleh anggota.");
        }

        $hasActiveLoans = $eksemplar->detailPeminjaman()
            ->whereIn('statusBuku', ['Booking', 'Siap Diambil', 'Dipinjam', 'Diajukan Kembali'])
            ->exists();

        if ($hasActiveLoans) {
            return back()->with('error', "Eksemplar #{$eksemplar->nomor_eksemplar} masih terikat dalam transaksi sirkulasi yang sedang berjalan.");
        }

        $buku = $eksemplar->buku;
        $nomor = $eksemplar->nomor_eksemplar;
        $id = $eksemplar->idEksemplar;

        $eksemplar->delete();
        $buku?->syncStok();

        return back()->with('success', "Eksemplar #{$nomor} (ID: {$id}) untuk buku '{$buku->judul}' berhasil dihapus dari inventaris.");
    }
}
