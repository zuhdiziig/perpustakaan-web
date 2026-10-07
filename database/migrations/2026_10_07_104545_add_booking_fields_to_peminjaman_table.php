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
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->string('kode_booking', 50)->nullable()->unique()->after('status');
            $table->string('qr_token', 64)->nullable()->unique()->after('kode_booking');
            $table->dateTime('batasAmbil')->nullable()->after('batasKembali');
            $table->string('opsi_pengambilan', 30)->default('siapkan_petugas')->after('batasAmbil');
            $table->text('catatan_petugas')->nullable()->after('opsi_pengambilan');
        });

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE buku_eksemplar DROP CONSTRAINT IF EXISTS buku_eksemplar_status_check');
            DB::statement("ALTER TABLE buku_eksemplar ADD CONSTRAINT buku_eksemplar_status_check CHECK (status IN ('Tersedia', 'Dibooking', 'Dipinjam', 'Hilang'))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['kode_booking', 'qr_token', 'batasAmbil', 'opsi_pengambilan', 'catatan_petugas']);
        });

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE buku_eksemplar DROP CONSTRAINT IF EXISTS buku_eksemplar_status_check');
            DB::statement("ALTER TABLE buku_eksemplar ADD CONSTRAINT buku_eksemplar_status_check CHECK (status IN ('Tersedia', 'Dipinjam', 'Hilang'))");
        }
    }
};
