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
    Schema::create('buku', function (Blueprint $table) {
        $table->id('idBuku');
        $table->foreignId('idKategori')->constrained('kategori', 'idKategori')->onDelete('cascade');
        $table->string('judul');
        $table->string('penulis');
        $table->string('penerbit');
        $table->year('tahunTerbit');
        $table->decimal('harga', 12, 2)->default(0);
        $table->integer('stok')->default(0);
        $table->string('kondisi')->default('Baik'); // Baik, Rusak, Hilang
        $table->timestamps();
    });
    }   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
