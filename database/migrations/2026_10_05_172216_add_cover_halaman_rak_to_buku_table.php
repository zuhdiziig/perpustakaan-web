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
        Schema::table('buku', function (Blueprint $table) {
            $table->string('cover')->nullable()->after('kondisi');
            $table->unsignedInteger('jumlahHalaman')->nullable()->after('cover');
            $table->string('rak', 20)->nullable()->after('jumlahHalaman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->dropColumn(['cover', 'jumlahHalaman', 'rak']);
        });
    }
};
