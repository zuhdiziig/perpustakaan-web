<?php

namespace Database\Seeders;

use App\Models\Barcode;
use App\Models\Buku;
use App\Models\BukuEksemplar;
use App\Models\Denda;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DendaSampleSeeder extends Seeder
{
    /**
     * Seed sample data matching Figma Frame 17 (Denda Peminjaman).
     */
    public function run(): void
    {
        // 1. Pastikan user Rizky Pratama (Member) ada
        $member = User::firstOrCreate(
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
            ]
        );

        // Petugas
        $petugas = User::firstOrCreate(
            ['email' => 'petugas@perpus.com'],
            [
                'name' => 'Petugas Satu',
                'password' => Hash::make('password123'),
                'role' => 'petugas',
            ]
        );

        // 2. Kategori & Buku Filosofi Teras
        $kategoriUmum = Kategori::firstOrCreate(
            ['namaKategori' => 'Umum'],
            ['deskripsi' => 'Buku pengembangan diri dan filsafat']
        );

        $buku = Buku::firstOrCreate(
            ['judul' => 'Filosofi Teras'],
            [
                'idKategori' => $kategoriUmum->idKategori,
                'penulis' => 'Henry Manampiring',
                'penerbit' => 'Kompas',
                'tahunTerbit' => 2019,
                'harga' => 98000,
                'stok' => 5,
                'kondisi' => 'Baik',
                'jumlahHalaman' => 320,
                'rak' => 'Rak U-05',
                'isbn' => '978-602-412-518-9',
                'sinopsis' => 'Penerapan filsafat Stoa kuno untuk kehidupan mental yang tangguh di zaman modern.',
            ]
        );

        $eksemplar = $buku->eksemplar->first() ?? BukuEksemplar::firstOrCreate(
            ['qr_token' => 'QR-FLS-001'],
            [
                'idBuku' => $buku->idBuku,
                'nomor_eksemplar' => 1,
                'status' => 'Tersedia',
                'kondisi' => 'Baik',
            ]
        );

        Barcode::updateOrCreate(
            ['idBuku' => $buku->idBuku],       // Kunci unik yang dicari di database
            ['kodeBarcode' => 'BK-FLS-001']    // Data yang dibuat atau diperbarui kodenya
        );

        // 3. Peminjaman (14 Sep 2026 - 28 Sep 2026)
        $peminjaman = Peminjaman::firstOrCreate(
            [
                'idUserMember' => $member->id,
                'tanggalPinjam' => '2026-09-14',
                'batasKembali' => '2026-09-28',
            ],
            [
                'idPeminjaman' => 276, // Sesuai kode transaksi PJ-20260914-0276 di Figma
                'idUserPetugas' => $petugas->id,
                'status' => 'Kembali',
                'totalBuku' => 1,
            ]
        );

        DetailPeminjaman::firstOrCreate(
            [
                'idPeminjaman' => $peminjaman->idPeminjaman,
                'idBuku' => $buku->idBuku,
            ],
            [
                'idEksemplar' => $eksemplar->idEksemplar,
                'jumlah' => 1,
                'statusBuku' => 'Kembali',
            ]
        );

        // 4. Pengembalian (03 Okt 2026 -> terlambat 5 hari)
        $pengembalian = Pengembalian::firstOrCreate(
            [
                'idPeminjaman' => $peminjaman->idPeminjaman,
            ],
            [
                'idUserPetugas' => $petugas->id,
                'tanggalKembali' => '2026-10-03',
                'kondisiBuku' => 'Baik',
            ]
        );

        // 5. Denda Keterlambatan Rp 5.000 (5 hari x 1.000)
        Denda::firstOrCreate(
            [
                'idPengembalian' => $pengembalian->idPengembalian,
            ],
            [
                'jenisDenda' => 'Keterlambatan',
                'jumlah' => 5000,
                'status' => 'Belum Dibayar',
            ]
        );

        // 6. Akun Member 2: Siti Aminah (untuk pengujian denda member lain)
        $member2 = User::firstOrCreate(
            ['email' => 'siti.aminah@email.com'],
            [
                'name' => 'Siti Aminah',
                'nik' => '3273012208990005',
                'password' => Hash::make('password123'),
                'role' => 'member',
                'status' => 'aktif',
                'alamat' => 'Jl. Dago No. 45, Bandung',
                'noTelepon' => '0813 9876 5432',
                'tanggal_lahir' => '1999-08-22',
                'notif_jatuh_tempo' => true,
                'notif_koleksi_baru' => true,
            ]
        );

        $buku2 = Buku::where('judul', 'Laut Bercerita')->first() ?? $buku;
        $eksemplar2 = $buku2->eksemplar->first() ?? BukuEksemplar::firstOrCreate(
            ['qr_token' => 'QR-LTB-001'],
            [
                'idBuku' => $buku2->idBuku,
                'nomor_eksemplar' => 1,
                'status' => 'Tersedia',
                'kondisi' => 'Baik',
            ]
        );

        $peminjaman2 = Peminjaman::firstOrCreate(
            [
                'idUserMember' => $member2->id,
                'tanggalPinjam' => '2026-09-10',
                'batasKembali' => '2026-09-24',
            ],
            [
                'idUserPetugas' => $petugas->id,
                'status' => 'Kembali',
                'totalBuku' => 1,
            ]
        );

        DetailPeminjaman::firstOrCreate(
            [
                'idPeminjaman' => $peminjaman2->idPeminjaman,
                'idBuku' => $buku2->idBuku,
            ],
            [
                'idEksemplar' => $eksemplar2->idEksemplar,
                'jumlah' => 1,
                'statusBuku' => 'Kembali',
            ]
        );

        $pengembalian2 = Pengembalian::firstOrCreate(
            [
                'idPeminjaman' => $peminjaman2->idPeminjaman,
            ],
            [
                'idUserPetugas' => $petugas->id,
                'tanggalKembali' => '2026-09-27',
                'kondisiBuku' => 'Baik',
            ]
        );

        Denda::firstOrCreate(
            [
                'idPengembalian' => $pengembalian2->idPengembalian,
            ],
            [
                'jenisDenda' => 'Keterlambatan',
                'jumlah' => 3000,
                'status' => 'Belum Dibayar',
            ]
        );
    }
}
