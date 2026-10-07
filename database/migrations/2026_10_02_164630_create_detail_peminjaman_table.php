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
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idPeminjaman')->constrained('peminjaman', 'idPeminjaman')->onDelete('cascade');
            $table->foreignId('idBuku')->constrained('buku', 'idBuku')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->string('statusBuku')->default('Dipinjam');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};
