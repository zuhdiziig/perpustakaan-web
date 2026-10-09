<?php

namespace Tests\Feature;

use App\Models\Barcode;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KatalogDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_book_detail_with_complete_information(): void
    {
        $kategori = Kategori::factory()->create(['namaKategori' => 'Fiksi']);
        $buku = Buku::factory()->create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Laut Bercerita',
            'penulis' => 'Leila S. Chudori',
            'penerbit' => 'Kepustakaan Populer Gramedia',
            'tahunTerbit' => 2017,
            'isbn' => '978-602-424-694-5',
            'jumlahHalaman' => 379,
            'rak' => 'Rak F-12',
            'stok' => 8,
            'sinopsis' => "Paragraf pertama sinopsis.\n\nParagraf kedua sinopsis.",
        ]);
        Barcode::create(['idBuku' => $buku->idBuku, 'kodeBarcode' => 'BK-00417']);

        $buku->eksemplar()->limit(3)->update(['status' => 'Dipinjam']);
        $buku->syncStok();

        $response = $this->get(route('katalog.show', $buku->idBuku));

        $response->assertOk();
        $response->assertViewIs('katalog.show');
        $response->assertSeeInOrder(['Detail Buku', 'Laut Bercerita', 'Leila S. Chudori']);
        $response->assertSee('978-602-424-694-5');
        $response->assertSee('Kepustakaan Populer Gramedia');
        $response->assertSee('379 halaman');
        $response->assertSee('F-12 / BK-00417');
        $response->assertSee('5 dari 8 eksemplar');
        $response->assertSee('Paragraf pertama sinopsis.');
        $response->assertSee('Paragraf kedua sinopsis.');
        $response->assertSee('Masa pinjam '.Peminjaman::MASA_PINJAM_BULAN.' bulan');
        $response->assertSee('Maksimal '.Peminjaman::BATAS_MAKSIMAL_BUKU.' buku per anggota');
        $response->assertSee(route('login'));
        $response->assertDontSee('id="member-feedback-toasts"', false);
        $response->assertDontSee('id="globalMemberSirkulasiModal"', false);
    }

    public function test_borrow_button_is_disabled_when_all_copies_are_borrowed(): void
    {
        $buku = Buku::factory()->habis()->create(['judul' => 'Buku Habis']);

        $response = $this->get(route('katalog.show', $buku->idBuku));

        $response->assertOk();
        $response->assertSee('Sedang dipinjam');
        $response->assertSee('Semua eksemplar sedang dipinjam');
    }

    public function test_member_sees_qr_card_action_in_borrow_dialog(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $buku = Buku::factory()->create();

        $response = $this->actingAs($member)->get(route('katalog.show', $buku->idBuku));

        $response->assertOk();
        $response->assertSee('Buka Kartu / QR Saya');
        $response->assertSee(route('member.kartu-saya'));
        $this->assertSame(1, substr_count($response->getContent(), 'id="member-feedback-toasts"'));
        $this->assertSame(1, substr_count($response->getContent(), 'id="globalMemberSirkulasiModal"'));
    }

    public function test_related_books_prioritize_same_category_and_exclude_current_book(): void
    {
        $fiksi = Kategori::factory()->create(['namaKategori' => 'Fiksi']);
        $sejarah = Kategori::factory()->create(['namaKategori' => 'Sejarah']);

        $buku = Buku::factory()->create(['idKategori' => $fiksi->idKategori, 'judul' => 'Laut Bercerita']);
        $sekategori = Buku::factory()->create(['idKategori' => $fiksi->idKategori, 'judul' => 'Bumi Manusia']);
        Buku::factory()->count(4)->create(['idKategori' => $sejarah->idKategori]);

        $response = $this->get(route('katalog.show', $buku->idBuku));

        $bukuTerkait = $response->viewData('bukuTerkait');

        $this->assertCount(4, $bukuTerkait);
        $this->assertTrue($bukuTerkait->first()->is($sekategori));
        $this->assertFalse($bukuTerkait->contains($buku));
    }

    public function test_back_button_keeps_catalog_filters(): void
    {
        $buku = Buku::factory()->create();
        $urlKatalog = route('katalog.index', ['q' => 'laut', 'status' => 'tersedia']);

        $response = $this->from($urlKatalog)->get(route('katalog.show', $buku->idBuku));

        $this->assertSame($urlKatalog, $response->viewData('urlKembali'));
    }

    public function test_unknown_book_returns_not_found(): void
    {
        $this->get(route('katalog.show', 999))->assertNotFound();
    }

    public function test_katalog_cards_link_to_detail_page(): void
    {
        $buku = Buku::factory()->create();

        $this->get(route('katalog.index'))
            ->assertOk()
            ->assertSee(route('katalog.show', $buku->idBuku));
    }
}
