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
            $table->foreignId('idPeminjaman')->after('id')->constrained('peminjaman', 'idPeminjaman')->onDelete('cascade');
            $table->foreignId('idBuku')->after('idPeminjaman')->constrained('buku', 'idBuku')->onDelete('cascade');
            $table->integer('jumlah')->default(1)->after('idBuku');
            $table->string('statusBuku', 50)->default('Dipinjam')->after('jumlah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_peminjaman', function (Blueprint $table) {
            $table->dropForeign(['idPeminjaman']);
            $table->dropForeign(['idBuku']);
            $table->dropColumn(['idPeminjaman', 'idBuku', 'jumlah', 'statusBuku']);
        });
    }
};
