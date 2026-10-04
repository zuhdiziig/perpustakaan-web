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
        Schema::create('buku_eksemplar', function (Blueprint $table) {
            $table->id('idEksemplar');
            $table->foreignId('idBuku')->constrained('buku', 'idBuku')->onDelete('cascade');
            $table->integer('nomor_eksemplar');
            $table->string('qr_token', 64)->unique();
            $table->string('kode_barcode', 50)->nullable()->unique();
            $table->string('kondisi', 50)->default('Baik');
            $table->enum('status', ['Tersedia', 'Dipinjam', 'Hilang'])->default('Tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_eksemplar');
    }
};
