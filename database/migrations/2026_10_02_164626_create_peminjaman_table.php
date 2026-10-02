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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id('idPeminjaman');
            $table->foreignId('idUserMember')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('idUserPetugas')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->date('tanggalPinjam');
            $table->date('batasKembali');
            $table->string('status')->default('Dipinjam');
            $table->integer('totalBuku')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
