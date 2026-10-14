<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use Database\Seeders\KatalogBukuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BukuSearchAndFilterTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $petugas;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $this->petugas = User::factory()->create([
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $this->seed(KatalogBukuSeeder::class);
    }

    public function test_total_buku_is_thirty_five_with_five_romance_books(): void
    {
        $this->assertSame(35, Buku::count());

        $romance = Kategori::where('namaKategori', 'Romance')->first();
        $this->assertNotNull($romance);

        $romanceBooks = Buku::where('idKategori', $romance->idKategori)->get();
        $this->assertCount(5, $romanceBooks);

        $titles = $romanceBooks->pluck('judul')->toArray();
        $this->assertContains('Dilan: Dia adalah Dilanku Tahun 1990', $titles);
        $this->assertContains('Antologi Rasa', $titles);
        $this->assertContains('Critical Eleven', $titles);
        $this->assertContains('Tenggelamnya Kapal Van der Wijck', $titles);
        $this->assertContains('Ayat-Ayat Cinta', $titles);

        foreach ($romanceBooks as $buku) {
            $this->assertNotEmpty($buku->cover_url);
            $this->assertGreaterThan(0, $buku->eksemplar()->count());
        }
    }

    public function test_admin_can_search_buku_by_keyword_sejarah(): void
    {
        $response = $this->actingAs($this->admin)->get(route('buku.index', ['search' => 'sejarah']));

        $response->assertOk();
        $response->assertSee('Sejarah Indonesia');
        $response->assertDontSee('Ihya Ulumuddin');
    }

    public function test_petugas_can_search_buku_by_keyword_web(): void
    {
        $response = $this->actingAs($this->petugas)->get(route('buku.index', ['search' => 'web']));

        $response->assertOk();
        $response->assertSee('Pemrograman Web dengan Laravel');
        $response->assertDontSee('Ihya Ulumuddin');
    }

    public function test_search_buku_by_category_name_romance(): void
    {
        $response = $this->actingAs($this->admin)->get(route('buku.index', ['search' => 'romance']));

        $response->assertOk();
        $response->assertSee('Dilan: Dia adalah Dilanku Tahun 1990');
        $response->assertSee('Antologi Rasa');
        $response->assertDontSee('Clean Code');
    }

    public function test_search_buku_by_author_pidi_baiq(): void
    {
        $response = $this->actingAs($this->petugas)->get(route('buku.index', ['search' => 'Pidi Baiq']));

        $response->assertOk();
        $response->assertSee('Dilan: Dia adalah Dilanku Tahun 1990');
        $response->assertDontSee('Laskar Pelangi');
    }

    public function test_search_buku_by_isbn(): void
    {
        $response = $this->actingAs($this->admin)->get(route('buku.index', ['search' => '978-602-7870-41-3']));

        $response->assertOk();
        $response->assertSee('Dilan: Dia adalah Dilanku Tahun 1990');
        $response->assertDontSee('Antologi Rasa');
    }

    public function test_search_buku_by_rak(): void
    {
        $response = $this->actingAs($this->petugas)->get(route('buku.index', ['search' => 'Rak R-01']));

        $response->assertOk();
        $response->assertSee('Dilan: Dia adalah Dilanku Tahun 1990');
        $response->assertDontSee('Bumi Manusia');
    }

    public function test_filter_buku_by_kategori_romance(): void
    {
        $romance = Kategori::where('namaKategori', 'Romance')->first();

        $response = $this->actingAs($this->admin)->get(route('buku.index', ['kategori' => $romance->idKategori]));

        $response->assertOk();
        $response->assertSee('Dilan: Dia adalah Dilanku Tahun 1990');
        $response->assertSee('Antologi Rasa');
        $response->assertSee('Critical Eleven');
        $response->assertSee('Tenggelamnya Kapal Van der Wijck');
        $response->assertSee('Ayat-Ayat Cinta');
        $response->assertDontSee('Pemrograman Web dengan Laravel');
    }
}
