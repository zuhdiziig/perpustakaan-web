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
                'isbn' => '978-602-424-694-5',
                'sinopsis' => "Laut Bercerita mengisahkan Biru Laut dan kawan-kawannya, para aktivis mahasiswa yang memperjuangkan perubahan di Indonesia pada akhir 1990-an. Melalui kisah keluarga, persahabatan, dan kehilangan, Leila S. Chudori menghadirkan cerita yang menyentuh tentang keberanian, ingatan, dan harapan.\n\nCocok untuk pembaca yang menyukai fiksi sejarah Indonesia dan cerita dengan karakter yang kuat.",
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
                'isbn' => '978-979-97312-3-4',
                'sinopsis' => "Bumi Manusia mengikuti kisah Minke, pemuda pribumi terpelajar di masa kolonial Hindia Belanda, yang jatuh cinta pada Annelies dan bersinggungan dengan sosok kuat Nyai Ontosoroh. Novel ini menggambarkan benturan kelas, ras, dan hukum kolonial dengan tajam.\n\nBagian pertama dari Tetralogi Buru yang menjadi salah satu karya sastra Indonesia paling berpengaruh.",
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
                'isbn' => '978-602-412-518-9',
                'sinopsis' => "Filosofi Teras memperkenalkan filsafat Stoa (Stoisisme) dengan bahasa yang ringan dan relevan dengan kehidupan sehari-hari. Henry Manampiring membahas cara mengelola emosi negatif, kekhawatiran, dan hal-hal yang berada di luar kendali kita.\n\nCocok untuk pembaca yang ingin hidup lebih tenang dan tangguh menghadapi tekanan.",
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
                'isbn' => '978-602-082-212-9',
                'sinopsis' => "Pulang bercerita tentang Bujang, anak kampung yang tumbuh menjadi bagian dari dunia shadow economy yang keras. Di tengah pertarungan kekuasaan, ia harus berdamai dengan masa lalu dan menemukan arti pulang yang sesungguhnya.\n\nNovel aksi dengan sentuhan keluarga dan spiritualitas khas Tere Liye.",
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
                'isbn' => '978-979-420-669-8',
                'sinopsis' => "Sejarah Indonesia Modern menelusuri perjalanan Nusantara sejak kedatangan Islam, masa kolonial, pergerakan nasional, hingga era Indonesia merdeka. M. C. Ricklefs menyajikan analisis yang komprehensif dan berbasis sumber primer.\n\nRujukan penting bagi pelajar, mahasiswa, dan peminat sejarah Indonesia.",
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
                'isbn' => '978-623-01-0412-7',
                'sinopsis' => "Dasar Pemrograman membahas konsep fundamental pemrograman mulai dari algoritma, variabel, percabangan, perulangan, hingga fungsi dengan contoh yang mudah diikuti.\n\nCocok untuk pemula yang baru memulai belajar coding secara terstruktur.",
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
                'isbn' => '978-602-8478-21-5',
                'sinopsis' => "Pendidikan Karakter menjelaskan bagaimana sekolah dan keluarga dapat menanamkan nilai hormat dan tanggung jawab pada anak. Thomas Lickona memaparkan strategi praktis yang dapat diterapkan di kelas maupun di rumah.\n\nBacaan wajib bagi guru, orang tua, dan pegiat pendidikan.",
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
                'isbn' => '978-602-98394-0-6',
                'sinopsis' => "Kisah Para Nabi menghimpun riwayat para nabi dan rasul sejak Nabi Adam hingga Nabi Isa berdasarkan Al-Qur'an dan hadis. Ibnu Katsir menyajikannya secara runtut disertai hikmah yang dapat dipetik.\n\nCocok dibaca keluarga sebagai sumber teladan dan pelajaran akhlak.",
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
                    'isbn' => $data['isbn'],
                    'sinopsis' => $data['sinopsis'],
                ]
            );

            // Lengkapi ISBN & sinopsis untuk buku dummy yang sudah ada sebelumnya
            $buku->update([
                'isbn' => $buku->isbn ?: $data['isbn'],
                'sinopsis' => $buku->sinopsis ?: $data['sinopsis'],
            ]);

            // Pastikan barcode ada
            Barcode::firstOrCreate(
                ['idBuku' => $buku->idBuku],
                ['kodeBarcode' => $data['barcode']]
            );
        }
    }
}
