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
            if (! Schema::hasColumn('detail_peminjaman', 'idPeminjaman')) {
                $table->foreignId('idPeminjaman')->constrained('peminjaman', 'idPeminjaman')->onDelete('cascade');
            }
            if (! Schema::hasColumn('detail_peminjaman', 'idBuku')) {
                $table->foreignId('idBuku')->constrained('buku', 'idBuku')->onDelete('cascade');
            }
            if (! Schema::hasColumn('detail_peminjaman', 'jumlah')) {
                $table->integer('jumlah')->default(1);
            }
            if (! Schema::hasColumn('detail_peminjaman', 'statusBuku')) {
                $table->string('statusBuku', 50)->default('Dipinjam');
            }
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
