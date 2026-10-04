<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\User;
use Illuminate\Database\Seeder;

class QrTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (User::whereNull('qr_token')->get() as $user) {
            $user->qr_token = 'usr_'.bin2hex(random_bytes(16));
            $user->save();
        }

        foreach (Buku::whereNull('qr_token')->get() as $buku) {
            $buku->qr_token = 'bk_'.bin2hex(random_bytes(16));
            $buku->save();
        }
    }
}
