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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('idPembayaran');
            $table->foreignId('idDenda')->constrained('denda', 'idDenda')->onDelete('cascade');
            $table->dateTime('tanggalBayar');
            $table->string('metode'); // QRIS, Tunai, Transfer
            $table->decimal('nominal', 12, 2);
            $table->string('status')->default('Pending'); // Pending, Sukses, Gagal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
