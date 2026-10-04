<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\BukuEksemplar;
use Illuminate\Database\Seeder;

class BukuEksemplarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bukus = Buku::with('eksemplar')->get();

        foreach ($bukus as $buku) {
            $existingCount = $buku->eksemplar->count();
            $stok = (int) $buku->stok;

            // Hanya backfill jika buku belum memiliki eksemplar fisik sama sekali dan stok > 0
            if ($existingCount === 0 && $stok > 0) {
                for ($i = 1; $i <= $stok; $i++) {
                    BukuEksemplar::create([
                        'idBuku' => $buku->idBuku,
                        'nomor_eksemplar' => $i,
                        'qr_token' => 'bk_'.bin2hex(random_bytes(16)),
                        'kode_barcode' => null,
                        'kondisi' => $buku->kondisi ?? 'Baik',
                        'status' => 'Tersedia',
                    ]);
                }
            }

            // Sinkronkan stok dengan eksemplar yang berstatus 'Tersedia'
            $buku->syncStok();
        }
    }
}
