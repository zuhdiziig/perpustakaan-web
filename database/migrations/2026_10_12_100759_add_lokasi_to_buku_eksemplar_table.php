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
        Schema::table('buku_eksemplar', function (Blueprint $table) {
            $table->string('lokasi', 30)->default('Rak')->after('status')->index();
        });

        // Setel eksemplar display vs gudang untuk data yang ada:
        // Maksimal 2 eksemplar per judul ditaruh di rak display (nomor 1 & 2),
        // sedangkan eksemplar nomor 3 ke atas disimpan di gudang cadangan.
        $bukus = DB::table('buku')->get();
        foreach ($bukus as $buku) {
            // Eksemplar 1 & 2 tetap di Rak
            DB::table('buku_eksemplar')
                ->where('idBuku', $buku->idBuku)
                ->where('nomor_eksemplar', '<=', 2)
                ->update(['lokasi' => 'Rak']);

            // Eksemplar 3 ke atas disimpan di Gudang
            DB::table('buku_eksemplar')
                ->where('idBuku', $buku->idBuku)
                ->where('nomor_eksemplar', '>', 2)
                ->update(['lokasi' => 'Gudang']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku_eksemplar', function (Blueprint $table) {
            $table->dropColumn('lokasi');
        });
    }
};
