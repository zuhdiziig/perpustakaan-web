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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 25)->nullable()->after('name');
            $table->date('tanggal_lahir')->nullable()->after('alamat');
            $table->string('foto')->nullable()->after('noTelepon');
            $table->boolean('notif_jatuh_tempo')->default(true)->after('qr_token');
            $table->boolean('notif_koleksi_baru')->default(true)->after('notif_jatuh_tempo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nik',
                'tanggal_lahir',
                'foto',
                'notif_jatuh_tempo',
                'notif_koleksi_baru',
            ]);
        });
    }
};
