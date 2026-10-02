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
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id('idPengembalian');
            $table->foreignId('idPeminjaman')->constrained('peminjaman', 'idPeminjaman')->onDelete('cascade');
            $table->foreignId('idUserPetugas')->constrained('users', 'id')->onDelete('cascade');
            $table->date('tanggalKembali');
            $table->string('kondisiBuku')->default('Baik');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
