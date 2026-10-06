<?php

namespace Database\Seeders;

use App\Models\Barcode;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun Admin
        User::firstOrCreate(
            ['email' => 'admin@perpus.com'],
            [
                'name' => 'Admin Perpustakaan',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Akun Petugas
        User::firstOrCreate(
            ['email' => 'petugas@perpus.com'],
            [
                'name' => 'Petugas Satu',
                'password' => Hash::make('password123'),
                'role' => 'petugas',
            ]
        );

        // Akun Member
        User::firstOrCreate(
            ['email' => 'rizky.pratama@email.com'],
            [
                'name' => 'Rizky Pratama',
                'nik' => '3273011505980004',
                'password' => Hash::make('password123'),
                'role' => 'member',
                'status' => 'aktif',
                'alamat' => 'Jl. Melati No. 18, Bandung',
                'noTelepon' => '0812 3456 7890',
                'tanggal_lahir' => '1998-05-15',
                'notif_jatuh_tempo' => true,
                'notif_koleksi_baru' => true,
                'created_at' => '2026-09-01 10:00:00',
            ]
        );

        $kategoriIT = Kategori::firstOrCreate(
            ['namaKategori' => 'Teknologi Informasi'],
            ['deskripsi' => 'Buku seputar pemrograman dan sistem']
        );

        $buku1 = Buku::firstOrCreate(
            ['judul' => 'Pemrograman Web dengan Laravel'],
            [
                'idKategori' => $kategoriIT->idKategori,
                'penulis' => 'Zuhdi Tech',
                'penerbit' => 'Informatika Press',
                'tahunTerbit' => 2026,
                'harga' => 95000,
                'stok' => 5,
                'kondisi' => 'Baik',
            ]
        );

        Barcode::firstOrCreate(
            ['kodeBarcode' => 'BK-IT-001'],
            ['idBuku' => $buku1->idBuku]
        );
    }
}
