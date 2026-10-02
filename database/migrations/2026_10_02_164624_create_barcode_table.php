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
    Schema::create('barcode', function (Blueprint $table) {
        $table->id('idBarcode');
        $table->foreignId('idBuku')->unique()->constrained('buku', 'idBuku')->onDelete('cascade');
        $table->string('kodeBarcode')->unique();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barcode');
    }
};
