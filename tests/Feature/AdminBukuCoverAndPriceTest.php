<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBukuCoverAndPriceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Kategori $kategori;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin.test@perpus.test',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $this->kategori = Kategori::create([
            'namaKategori' => 'Teknologi',
            'deskripsi' => 'Buku-buku seputar sains dan teknologi',
        ]);
    }

    public function test_tombol_tambah_petugas_tidak_memiliki_dobel_tanda_plus(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('petugas.index'));

        $response->assertOk();
        $response->assertSee('Tambah Petugas');
        $response->assertDontSee('+ + Tambah Petugas');
    }

    public function test_halaman_tambah_dan_ubah_buku_tidak_menampilkan_rp_di_samping_label_harga(): void
    {
        $this->actingAs($this->admin);

        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Clean Architecture',
            'penulis' => 'Robert C. Martin',
            'penerbit' => 'Prentice Hall',
            'tahunTerbit' => 2017,
            'harga' => 150000,
            'stok' => 5,
            'kondisi' => 'Baik',
        ]);

        // Cek halaman create
        $createResponse = $this->get(route('buku.create'));
        $createResponse->assertOk();
        $createResponse->assertSee('Harga <span class="required">*</span>', false);
        $createResponse->assertDontSee('Harga (Rp)');

        // Cek halaman edit
        $editResponse = $this->get(route('buku.edit', $buku->idBuku));
        $editResponse->assertOk();
        $editResponse->assertSee('Harga <span class="required">*</span>', false);
        $editResponse->assertDontSee('Harga (Rp)');
        $editResponse->assertSee('Pilih Gambar Cover');
    }

    public function test_admin_dapat_mengunggah_cover_pada_menu_ubah_buku(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Refactoring',
            'penulis' => 'Martin Fowler',
            'penerbit' => 'Addison-Wesley',
            'tahunTerbit' => 2018,
            'harga' => 180000,
            'stok' => 3,
            'kondisi' => 'Baik',
        ]);

        $coverFile = UploadedFile::fake()->image('clean_code.jpg', 400, 600);

        $response = $this->put(route('buku.update', $buku->idBuku), [
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Refactoring (2nd Edition)',
            'penulis' => 'Martin Fowler',
            'penerbit' => 'Addison-Wesley',
            'tahunTerbit' => 2018,
            'harga' => 'Rp 180.000', // Input format rupiah
            'stok' => 3,
            'kondisi' => 'Baik',
            'kodeBarcode' => 'BK-REF-001',
            'cover' => $coverFile,
        ]);

        $response->assertRedirect(route('buku.index'));
        $response->assertSessionHas('success');

        $buku->refresh();
        $this->assertNotNull($buku->cover);
        Storage::disk('public')->assertExists($buku->cover);
        $this->assertEquals(180000, (int) $buku->harga);
        $this->assertStringContainsString('storage/'.$buku->cover, $buku->cover_url);
    }

    public function test_admin_dapat_menghapus_cover_saat_ubah_buku(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $initialPath = 'covers/initial_test.jpg';
        Storage::disk('public')->put($initialPath, 'fake-content');

        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Design Patterns',
            'penulis' => 'Gang of Four',
            'penerbit' => 'Addison-Wesley',
            'tahunTerbit' => 1994,
            'harga' => 210000,
            'stok' => 2,
            'kondisi' => 'Baik',
            'cover' => $initialPath,
        ]);

        $response = $this->put(route('buku.update', $buku->idBuku), [
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Design Patterns',
            'penulis' => 'Gang of Four',
            'penerbit' => 'Addison-Wesley',
            'tahunTerbit' => 1994,
            'harga' => 210000,
            'stok' => 2,
            'kondisi' => 'Baik',
            'kodeBarcode' => 'BK-DP-001',
            'hapus_cover' => 1,
        ]);

        $response->assertRedirect(route('buku.index'));

        $buku->refresh();
        $this->assertNull($buku->cover);
        Storage::disk('public')->assertMissing($initialPath);
    }

    public function test_admin_dapat_tambah_buku_dengan_format_rupiah_dan_cover(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $coverFile = UploadedFile::fake()->image('buku_baru.webp', 300, 450);

        $response = $this->post(route('buku.store'), [
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Pemrograman Modern dengan Go',
            'penulis' => 'Alan Donovan',
            'penerbit' => 'Tech Press',
            'tahunTerbit' => 2026,
            'harga' => 'Rp 165.000', // Format Rupiah
            'stok' => 4,
            'kondisi' => 'Baik',
            'kodeBarcode' => 'BK-GO-001',
            'cover' => $coverFile,
        ]);

        $response->assertRedirect(route('buku.index'));
        $response->assertSessionHas('success');

        $buku = Buku::where('judul', 'Pemrograman Modern dengan Go')->first();
        $this->assertNotNull($buku);
        $this->assertEquals(165000, (int) $buku->harga);
        $this->assertNotNull($buku->cover);
        Storage::disk('public')->assertExists($buku->cover);
    }
}
