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
        Schema::table('detail_peminjaman', function (Blueprint $table) {
            $table->string('kode_kembali', 50)->nullable()->unique()->after('statusBuku');
            $table->string('qr_kembali', 64)->nullable()->unique()->after('kode_kembali');
            $table->string('kondisi_laporan', 50)->nullable()->default('Baik')->after('qr_kembali');
            $table->timestamp('waktu_pengajuan_kembali')->nullable()->after('kondisi_laporan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_peminjaman', function (Blueprint $table) {
            $table->dropColumn(['kode_kembali', 'qr_kembali', 'kondisi_laporan', 'waktu_pengajuan_kembali']);
        });
    }
};
