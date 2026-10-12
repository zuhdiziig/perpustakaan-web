<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BukuSoftDeletesTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $member;

    private Kategori $kategori;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin.softdelete@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $this->member = User::create([
            'name' => 'Anggota Aktif',
            'email' => 'member.softdelete@test.com',
            'password' => bcrypt('password'),
            'role' => 'member',
            'status' => 'aktif',
            'kode_anggota' => 'AG-2026-SD001',
        ]);

        $this->kategori = Kategori::create([
            'namaKategori' => 'Sejarah',
            'deskripsi' => 'Buku Sejarah',
        ]);
    }

    public function test_penghapusan_buku_menggunakan_soft_delete_dan_menjaga_riwayat_transaksi(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Sejarah Dunia Kuno',
            'penulis' => 'Herodotus',
            'penerbit' => 'Pustaka Sejarah',
            'tahunTerbit' => 2020,
            'stok' => 2,
            'harga' => 90000,
        ]);

        $eksemplar = $buku->eksemplar()->first();

        // Buat peminjaman masa lalu yang sudah selesai
        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'status' => 'Selesai',
            'tanggalPinjam' => now()->subDays(10)->toDateString(),
            'batasKembali' => now()->subDays(3)->toDateString(),
        ]);

        $detail = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $eksemplar->idEksemplar,
            'statusBuku' => 'Selesai',
        ]);

        // Hapus buku oleh Admin
        $response = $this->actingAs($this->admin)->delete(route('buku.destroy', $buku->idBuku));
        $response->assertRedirect(route('buku.index'));

        // Pastikan buku berstatus soft deleted di DB
        $this->assertSoftDeleted('buku', [
            'idBuku' => $buku->idBuku,
        ]);

        // Buku tidak lagi muncul di katalog aktif biasa
        $this->assertNull(Buku::find($buku->idBuku));

        // Tetapi arsip transaksi peminjaman masa lalu tetap dapat mengakses relasi judul bukunya
        $detailFresh = DetailPeminjaman::find($detail->id);
        $this->assertNotNull($detailFresh->buku);
        $this->assertSame('Sejarah Dunia Kuno', $detailFresh->buku->judul);
    }

    public function test_penghapusan_buku_ditolak_jika_sedang_dipinjam(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Ensiklopedia Peradaban',
            'penulis' => 'Sejarawan',
            'penerbit' => 'Pustaka Dunia',
            'tahunTerbit' => 2021,
            'stok' => 2,
            'harga' => 120000,
        ]);

        $eksemplar = $buku->eksemplar()->first();
        $eksemplar->update(['status' => 'Dipinjam']);

        $response = $this->actingAs($this->admin)->delete(route('buku.destroy', $buku->idBuku));
        $response->assertSessionHas('error');

        // Pastikan buku TIDAK terhapus
        $this->assertNotSoftDeleted('buku', [
            'idBuku' => $buku->idBuku,
        ]);
    }
}
