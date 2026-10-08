<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengembalian', function (Blueprint $table) {
            $table->foreignId('idUserPetugas')->nullable()->change();
        });

        Schema::table('detail_peminjaman', function (Blueprint $table) {
            $table->foreignId('id_denda')->nullable()->after('kode_batch_kembali')->constrained('denda', 'idDenda')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_peminjaman', function (Blueprint $table) {
            $table->dropForeign(['id_denda']);
            $table->dropColumn('id_denda');
        });

        Schema::table('pengembalian', function (Blueprint $table) {
            $table->foreignId('idUserPetugas')->nullable(false)->change();
        });
    }
};
