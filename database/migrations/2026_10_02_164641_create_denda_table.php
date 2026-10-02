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
        Schema::create('denda', function (Blueprint $table) {
            $table->id('idDenda');
            $table->foreignId('idPengembalian')->constrained('pengembalian', 'idPengembalian')->onDelete('cascade');
            $table->string('jenisDenda'); // Keterlambatan, Kerusakan, Kehilangan
            $table->decimal('jumlah', 12, 2)->default(0);
            $table->string('status')->default('Belum Dibayar'); // Belum Dibayar, Lunas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denda');
    }
};
