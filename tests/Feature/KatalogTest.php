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
}
