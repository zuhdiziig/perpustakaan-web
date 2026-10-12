<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Buat tabel snapshot untuk backup stok lama sebelum migrasi
        if (! Schema::hasTable('_migration_buku_stok_snapshot')) {
            Schema::create('_migration_buku_stok_snapshot', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('idBuku')->unique();
                $table->integer('original_stok');
                $table->json('created_eksemplar_ids')->nullable();
                $table->timestamps();
            });
        }

        // 2. Tambahkan index pembantu jika belum ada untuk performa query ketersediaan
        Schema::table('buku_eksemplar', function (Blueprint $table) {
            // Index status untuk mempercepat filter dan hitung stok tersedia
            if (! Schema::hasColumn('buku_eksemplar', 'status')) {
                // column already exists
            }
        });

        // 3. Backfill data eksemplar fisik sesuai nilai stok yang ada pada buku
        $bukus = DB::table('buku')->get();

        foreach ($bukus as $buku) {
            $existingCount = DB::table('buku_eksemplar')->where('idBuku', $buku->idBuku)->count();
            $maxNomor = (int) (DB::table('buku_eksemplar')->where('idBuku', $buku->idBuku)->max('nomor_eksemplar') ?? 0);
            $targetStok = (int) $buku->stok;

            $createdIds = [];

            // Jika eksemplar fisik lebih sedikit dari stok tercatat, buat eksemplar individual baru
            if ($targetStok > $existingCount) {
                $kekurangan = $targetStok - $existingCount;
                for ($i = 1; $i <= $kekurangan; $i++) {
                    $nextNomor = $maxNomor + $i;
                    $qrToken = 'bk_'.bin2hex(random_bytes(16));

                    $newId = DB::table('buku_eksemplar')->insertGetId([
                        'idBuku' => $buku->idBuku,
                        'nomor_eksemplar' => $nextNomor,
                        'qr_token' => $qrToken,
                        'kode_barcode' => null,
                        'kondisi' => $buku->kondisi ?? 'Baik',
                        'status' => 'Tersedia',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $createdIds[] = $newId;
                }
            }

            // Simpan snapshot data lama
            DB::table('_migration_buku_stok_snapshot')->updateOrInsert(
                ['idBuku' => $buku->idBuku],
                [
                    'original_stok' => $buku->stok,
                    'created_eksemplar_ids' => json_encode($createdIds),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Sinkronkan kolom buku.stok agar selalu mencerminkan jumlah eksemplar yang berstatus 'Tersedia'
            $tersediaCount = DB::table('buku_eksemplar')
                ->where('idBuku', $buku->idBuku)
                ->where('status', 'Tersedia')
                ->count();

            DB::table('buku')
                ->where('idBuku', $buku->idBuku)
                ->update(['stok' => $tersediaCount]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('_migration_buku_stok_snapshot')) {
            $snapshots = DB::table('_migration_buku_stok_snapshot')->get();

            foreach ($snapshots as $snap) {
                // Hapus eksemplar yang dibuat oleh migrasi ini
                if (! empty($snap->created_eksemplar_ids)) {
                    $ids = json_decode($snap->created_eksemplar_ids, true);
                    if (is_array($ids) && count($ids) > 0) {
                        DB::table('buku_eksemplar')->whereIn('idEksemplar', $ids)->delete();
                    }
                }

                // Kembalikan stok asli
                DB::table('buku')
                    ->where('idBuku', $snap->idBuku)
                    ->update(['stok' => $snap->original_stok]);
            }

            Schema::dropIfExists('_migration_buku_stok_snapshot');
        }
    }
};
