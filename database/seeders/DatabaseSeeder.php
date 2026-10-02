<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Buku;
use App\Models\Barcode;
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
        User::create([
            'name' => 'Admin Perpustakaan',
            'email' => 'admin@perpus.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Akun Petugas
        User::create([
            'name' => 'Petugas Satu',
            'email' => 'petugas@perpus.com',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
        ]);

        // Akun Member
        User::create([
            'name' => 'Member Baca',
            'email' => 'member@perpus.com',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'alamat' => 'Jl. Buku No. 10',
            'noTelepon' => '08123456789',
        ]);

        $kategoriIT = Kategori::create([
            'namaKategori' => 'Teknologi Informasi',
            'deskripsi' => 'Buku seputar pemrograman dan sistem'
        ]);

        $buku1 = Buku::create([
            'idKategori' => $kategoriIT->idKategori,
            'judul' => 'Pemrograman Web dengan Laravel',
            'penulis' => 'Zuhdi Tech',
            'penerbit' => 'Informatika Press',
            'tahunTerbit' => 2026,
            'harga' => 95000,
            'stok' => 5,
            'kondisi' => 'Baik',
        ]);

        Barcode::create([
            'idBuku' => $buku1->idBuku,
            'kodeBarcode' => 'BK-IT-001',
        ]);
    }
}
