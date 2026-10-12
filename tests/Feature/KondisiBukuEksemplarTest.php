<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KondisiBukuEksemplarTest extends TestCase
{
    use RefreshDatabase;

    private User $petugas;

    private User $admin;

    private Buku $buku;

    protected function setUp(): void
    {
        parent::setUp();

        $this->petugas = User::create([
            'name' => 'Petugas Inventaris',
            'email' => 'petugas.kondisi@test.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $this->admin = User::create([
            'name' => 'Admin Inventaris',
            'email' => 'admin.kondisi@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $kategori = Kategori::create([
            'namaKategori' => 'Teknologi',
            'deskripsi' => 'Buku Teknologi',
        ]);

        $this->buku = Buku::create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Sistem Inventaris Modern',
            'penulis' => 'Penulis Handal',
            'penerbit' => 'Pustaka Utama',
            'tahunTerbit' => 2026,
            'stok' => 3,
            'harga' => 85000,
        ]);
    }

    public function test_hanya_petugas_yang_dapat_mengakses_modul_kondisi_eksemplar(): void
    {
        $response = $this->actingAs($this->petugas)->get(route('kondisi.index'));
        $response->assertStatus(200);
        $response->assertSee('Pemeriksaan Kondisi Eksemplar Fisik');
        $response->assertSee('Sistem Inventaris Modern');

        // Admin dibatasi karena modul operasional fisik khusus petugas
        $responseAdmin = $this->actingAs($this->admin)->get(route('kondisi.index'));
        $responseAdmin->assertStatus(403);
    }

    public function test_filter_kondisi_dan_lokasi_berfungsi(): void
    {
        $eksemplar = $this->buku->eksemplar()->first();
        $eksemplar->update(['kondisi' => 'Rusak', 'lokasi' => 'Gudang']);

        $response = $this->actingAs($this->petugas)->get(route('kondisi.index', [
            'kondisi' => 'Rusak',
            'lokasi' => 'Gudang',
        ]));

        $response->assertStatus(200);
        $response->assertSee("Eksemplar #{$eksemplar->nomor_eksemplar}");
    }

    public function test_update_kondisi_rusak_otomatis_karantina_ke_gudang_dan_hitung_biaya(): void
    {
        $eksemplar = $this->buku->eksemplar()->first();
        $this->assertSame('Rak', $eksemplar->lokasi);

        $response = $this->actingAs($this->petugas)->put(route('kondisi.update', $eksemplar->idEksemplar), [
            'kondisi' => 'Rusak',
            'lokasi' => 'Rak', // Sengaja input Rak, harus dioverride ke Gudang
            'catatan' => 'Cover sobek parah',
        ]);

        $response->assertRedirect(route('kondisi.index'));
        $response->assertSessionHas('kondisi', 'Rusak');
        $response->assertSessionHas('biayaKerusakan', 85000.0);

        $eksemplar->refresh();
        $this->assertSame('Rusak', $eksemplar->kondisi);
        $this->assertSame('Gudang', $eksemplar->lokasi);
    }

    public function test_update_kondisi_hilang_memperbarui_status_dan_sinkronisasi_stok(): void
    {
        $this->assertSame(3, $this->buku->fresh()->stok);

        $eksemplar = $this->buku->eksemplar()->first();

        $response = $this->actingAs($this->petugas)->put(route('kondisi.update', $eksemplar->idEksemplar), [
            'kondisi' => 'Hilang',
            'lokasi' => 'Rak',
        ]);

        $response->assertRedirect(route('kondisi.index'));

        $eksemplar->refresh();
        $this->assertSame('Hilang', $eksemplar->kondisi);
        $this->assertSame('Hilang', $eksemplar->status);

        // Stok buku berkurang dari 3 menjadi 2
        $this->assertSame(2, $this->buku->fresh()->stok);
    }
}
