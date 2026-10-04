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
        Schema::table('buku_eksemplar', function (Blueprint $table) {
            $table->unique(['idBuku', 'nomor_eksemplar']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku_eksemplar', function (Blueprint $table) {
            $table->dropUnique(['idBuku', 'nomor_eksemplar']);
        });
    }
};
