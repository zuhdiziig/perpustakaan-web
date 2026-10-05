<?php

namespace Database\Seeders;

use App\Models\Barcode;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KatalogBukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriList = [
            'Fiksi' => 'Koleksi novel, sastra, dan cerita rekaan',
            'Umum' => 'Buku pengembangan diri, filsafat, dan pengetahuan populer',
            'Sejarah' => 'Arsip sejarah dunia dan nusantara',
            'Teknologi' => 'Buku sains komputer, rekayasa, dan teknologi informasi',
            'Pendidikan' => 'Buku metode belajar, psikologi edukasi, dan kurikulum',
            'Agama' => 'Kajian keagamaan, akhlak, dan sejarah peradaban spiritual',
        ];

        $kategoriModels = [];
        foreach ($kategoriList as $nama => $deskripsi) {
            $kategoriModels[$nama] = Kategori::firstOrCreate(
                ['namaKategori' => $nama],
                ['deskripsi' => $deskripsi]
            );
        }

        $bukuList = [
            [
                'judul' => 'Laut Bercerita',
                'penulis' => 'Leila S. Chudori',
                'penerbit' => 'Kepustakaan Populer Gramedia',
                'tahunTerbit' => 2017,
                'harga' => 115000,
                'stok' => 5,
                'kondisi' => 'Baik',
                'jumlahHalaman' => 379,
                'rak' => 'Rak F-12',
                'cover' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=800&auto=format&fit=crop',
                'kategori' => 'Fiksi',
                'barcode' => 'BK-FIK-001',
            ],
            [
                'judul' => 'Bumi Manusia',
                'penulis' => 'Pramoedya Ananta Toer',
                'penerbit' => 'Lentera Dipantara',
                'tahunTerbit' => 2005,
                'harga' => 135000,
                'stok' => 4,
                'kondisi' => 'Baik',
                'jumlahHalaman' => 535,
                'rak' => 'Rak F-08',
                'cover' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=800&auto=format&fit=crop',
                'kategori' => 'Fiksi',
                'barcode' => 'BK-FIK-002',
            ],
            [
                'judul' => 'Filosofi Teras',
                'penulis' => 'Henry Manampiring',
                'penerbit' => 'Penerbit Buku Kompas',
                'tahunTerbit' => 2018,
                'harga' => 98000,
                'stok' => 6,
                'kondisi' => 'Baik',
                'jumlahHalaman' => 346,
                'rak' => 'Rak U-03',
                'cover' => 'https://images.unsplash.com/photo-1532012164546-f432f2e3777f?q=80&w=800&auto=format&fit=crop',
                'kategori' => 'Umum',
                'barcode' => 'BK-UMM-001',
            ],
            [
                'judul' => 'Pulang',
                'penulis' => 'Tere Liye',
                'penerbit' => 'Republika Penerbit',
                'tahunTerbit' => 2015,
                'harga' => 89000,
                'stok' => 3,
                'kondisi' => 'Baik',
                'jumlahHalaman' => 400,
                'rak' => 'Rak F-15',
                'cover' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?q=80&w=800&auto=format&fit=crop',
                'kategori' => 'Fiksi',
                'barcode' => 'BK-FIK-003',
            ],
            [
                'judul' => 'Sejarah Indonesia',
                'penulis' => 'M. C. Ricklefs',
                'penerbit' => 'Gadjah Mada University Press',
                'tahunTerbit' => 2008,
                'harga' => 175000,
                'stok' => 4,
                'kondisi' => 'Baik',
                'jumlahHalaman' => 724,
                'rak' => 'Rak S-02',
                'cover' => 'https://images.unsplash.com/photo-1461360370896-922624d12aa1?q=80&w=800&auto=format&fit=crop',
                'kategori' => 'Sejarah',
                'barcode' => 'BK-SEJ-001',
            ],
            [
                'judul' => 'Dasar Pemrograman',
                'penulis' => 'Abdul Kadir',
                'penerbit' => 'Andi Publisher',
                'tahunTerbit' => 2019,
                'harga' => 110000,
                'stok' => 5,
                'kondisi' => 'Baik',
                'jumlahHalaman' => 320,
                'rak' => 'Rak T-04',
                'cover' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=800&auto=format&fit=crop',
                'kategori' => 'Teknologi',
                'barcode' => 'BK-TEK-001',
            ],
            [
                'judul' => 'Pendidikan Karakter',
                'penulis' => 'Thomas Lickona',
                'penerbit' => 'Nusamedia',
                'tahunTerbit' => 2012,
                'harga' => 95000,
                'stok' => 4,
                'kondisi' => 'Baik',
                'jumlahHalaman' => 280,
                'rak' => 'Rak P-06',
                'cover' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?q=80&w=800&auto=format&fit=crop',
                'kategori' => 'Pendidikan',
                'barcode' => 'BK-PDK-001',
            ],
            [
                'judul' => 'Kisah Para Nabi',
                'penulis' => 'Ibnu Katsir',
                'penerbit' => 'Ummul Qura',
                'tahunTerbit' => 2014,
                'harga' => 160000,
                'stok' => 5,
                'kondisi' => 'Baik',
                'jumlahHalaman' => 512,
                'rak' => 'Rak A-01',
                'cover' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?q=80&w=800&auto=format&fit=crop',
                'kategori' => 'Agama',
                'barcode' => 'BK-AGM-001',
            ],
        ];

        foreach ($bukuList as $data) {
            $kategori = $kategoriModels[$data['kategori']] ?? null;
            if (! $kategori) {
                continue;
            }

            $buku = Buku::firstOrCreate(
                ['judul' => $data['judul']],
                [
                    'idKategori' => $kategori->idKategori,
                    'penulis' => $data['penulis'],
                    'penerbit' => $data['penerbit'],
                    'tahunTerbit' => $data['tahunTerbit'],
                    'harga' => $data['harga'],
                    'stok' => $data['stok'],
                    'kondisi' => $data['kondisi'],
                    'jumlahHalaman' => $data['jumlahHalaman'],
                    'rak' => $data['rak'],
                    'cover' => $data['cover'],
                ]
            );

            // Pastikan barcode ada
            Barcode::firstOrCreate(
                ['idBuku' => $buku->idBuku],
                ['kodeBarcode' => $data['barcode']]
            );
        }
    }
}
