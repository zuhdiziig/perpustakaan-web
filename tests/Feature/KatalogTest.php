<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_katalog_and_see_books_and_auth_buttons(): void
    {
        $kategori = Kategori::factory()->create(['namaKategori' => 'Fiksi']);
        $buku = Buku::factory()->create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Laut Bercerita',
            'penulis' => 'Leila S. Chudori',
            'stok' => 5,
            'rak' => 'Rak F-12',
        ]);

        $response = $this->get('/katalog');

        $response->assertStatus(200);
        $response->assertViewIs('katalog.index');
        $response->assertSee('Katalog Buku');
        $response->assertSee('Pencarian buku');
        $response->assertSee('Masuk');
        $response->assertSee('Daftar');
        $response->assertSee('Laut Bercerita');
        $response->assertSee('Leila S. Chudori');
        $response->assertSee('Rak F-12');
        $response->assertSee('Tersedia');
    }

    public function test_user_can_search_katalog_by_keyword(): void
    {
        $kategori = Kategori::factory()->create(['namaKategori' => 'Fiksi']);
        $buku1 = Buku::factory()->create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Laut Bercerita',
            'penulis' => 'Leila S. Chudori',
        ]);
        $buku2 = Buku::factory()->create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Bumi Manusia',
            'penulis' => 'Pramoedya Ananta Toer',
        ]);

        $response = $this->get('/katalog?q=Laut');

        $response->assertStatus(200);
        $response->assertSee('Laut Bercerita');
        $response->assertDontSee('Bumi Manusia');
    }

    public function test_user_can_filter_by_category_and_availability(): void
    {
        $fiksi = Kategori::factory()->create(['namaKategori' => 'Fiksi']);
        $sejarah = Kategori::factory()->create(['namaKategori' => 'Sejarah']);

        $bukuTersedia = Buku::factory()->create([
            'idKategori' => $fiksi->idKategori,
            'judul' => 'Laut Bercerita',
            'stok' => 4,
        ]);
        $bukuHabis = Buku::factory()->create([
            'idKategori' => $fiksi->idKategori,
            'judul' => 'Pulang',
            'stok' => 0,
        ]);
        $bukuSejarah = Buku::factory()->create([
            'idKategori' => $sejarah->idKategori,
            'judul' => 'Sejarah Indonesia',
            'stok' => 2,
        ]);

        // Filter kategori Fiksi
        $resKategori = $this->get('/katalog?kategori='.$fiksi->idKategori);
        $resKategori->assertStatus(200);
        $resKategori->assertSee('Laut Bercerita');
        $resKategori->assertSee('Pulang');
        $resKategori->assertDontSee('Sejarah Indonesia');

        // Filter hanya yang Tersedia
        $resTersedia = $this->get('/katalog?kategori='.$fiksi->idKategori.'&status=tersedia');
        $resTersedia->assertStatus(200);
        $resTersedia->assertSee('Laut Bercerita');
        $resTersedia->assertDontSee('Pulang');
    }

    public function test_katalog_sorting_by_popularity(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $kategori = Kategori::factory()->create();

        $bukuPopuler = Buku::factory()->create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Buku Sangat Populer',
        ]);
        $bukuBiasa = Buku::factory()->create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Buku Belum Pernah Dipinjam',
        ]);

        $pinjam = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => today(),
            'batasKembali' => today()->addDays(7),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $pinjam->idPeminjaman,
            'idBuku' => $bukuPopuler->idBuku,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        $response = $this->get('/katalog?sort=popularitas');
        $response->assertStatus(200);
        $bukus = $response->viewData('bukus');

        $this->assertEquals('Buku Sangat Populer', $bukus->first()->judul);
    }

    public function test_authenticated_admin_sees_admin_sidebar_on_katalog(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $buku = Buku::factory()->create();

        $response = $this->actingAs($admin)->get('/katalog');

        $response->assertStatus(200);
        $response->assertSee('Panel Admin');
        $response->assertSee('Data Petugas');
        $response->assertSee('Koleksi Buku');
        $response->assertSee('Kategori Buku');
        $response->assertSee('Laporan');
    }

    public function test_authenticated_petugas_sees_petugas_sidebar_on_katalog(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);
        $buku = Buku::factory()->create();

        $response = $this->actingAs($petugas)->get('/katalog');

        $response->assertStatus(200);
        $response->assertSee('Panel Petugas');
        $response->assertSee('Scan Barcode');
        $response->assertSee('Peminjaman');
        $response->assertSee('Pengembalian');
        $response->assertSee('Kelola Denda');
    }

    public function test_authenticated_member_sees_anggota_sidebar_on_katalog(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $buku = Buku::factory()->create();

        $response = $this->actingAs($member)->get('/katalog');

        $response->assertStatus(200);
        $response->assertSee('Panel Anggota');
        $response->assertSee('Katalog Buku');
        $response->assertSee('Riwayat');
        $response->assertSee('Denda');
    }

    public function test_authenticated_actors_see_sidebar_on_katalog_detail(): void
    {
        $buku = Buku::factory()->create();

        $this->get('/katalog/'.$buku->idBuku)
            ->assertStatus(200)
            ->assertDontSee('Panel Admin')
            ->assertDontSee('Panel Petugas')
            ->assertDontSee('Panel Anggota');

        $admin = User::factory()->create(['role' => 'admin']);
        $petugas = User::factory()->create(['role' => 'petugas']);
        $member = User::factory()->create(['role' => 'member']);

        $this->actingAs($admin)->get('/katalog/'.$buku->idBuku)
            ->assertStatus(200)
            ->assertSee('Panel Admin');

        $this->actingAs($petugas)->get('/katalog/'.$buku->idBuku)
            ->assertStatus(200)
            ->assertSee('Panel Petugas');

        $this->actingAs($member)->get('/katalog/'.$buku->idBuku)
            ->assertStatus(200)
            ->assertSee('Panel Anggota');
    }
}
