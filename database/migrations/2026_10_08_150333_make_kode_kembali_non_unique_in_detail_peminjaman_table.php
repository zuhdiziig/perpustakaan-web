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
        Schema::table('detail_peminjaman', function (Blueprint $table) {
            $table->dropUnique('detail_peminjaman_kode_kembali_unique');
            $table->dropUnique('detail_peminjaman_qr_kembali_unique');
            $table->index('kode_kembali');
            $table->index('qr_kembali');
        });

        // Unify existing batch rows so all items in the same batch have identical ticket number
        DB::statement('UPDATE detail_peminjaman SET kode_kembali = kode_batch_kembali, qr_kembali = kode_batch_kembali WHERE kode_batch_kembali IS NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_peminjaman', function (Blueprint $table) {
            $table->dropIndex(['kode_kembali']);
            $table->dropIndex(['qr_kembali']);
            $table->unique('kode_kembali');
            $table->unique('qr_kembali');
        });
    }
};
