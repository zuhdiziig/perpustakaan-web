<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BukuEksemplarStokTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $petugas;

    private User $member;

    private Kategori $kategori;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Perpus',
            'email' => 'admin.eksemplar@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $this->petugas = User::create([
            'name' => 'Petugas Sirkulasi',
            'email' => 'petugas.eksemplar@test.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $this->member = User::create([
            'name' => 'Member Pembaca',
            'email' => 'member.eksemplar@test.com',
            'password' => bcrypt('password'),
            'role' => 'member',
            'status' => 'aktif',
            'kode_anggota' => 'AG-2026-99991',
        ]);

        $this->kategori = Kategori::create([
            'namaKategori' => 'Teknologi Informasi',
            'deskripsi' => 'Buku-buku komputer',
        ]);
    }

    public function test_setiap_eksemplar_memiliki_id_unik_dan_nomor_berbeda(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Pemrograman Web',
            'penulis' => 'Zuhdi',
            'penerbit' => 'Informatika',
            'tahunTerbit' => 2026,
            'harga' => 120000,
            'stok' => 7,
            'kondisi' => 'Baik',
        ]);

        $copies = $buku->eksemplar()->orderBy('nomor_eksemplar')->get();

        $this->assertCount(7, $copies);

        $ids = $copies->pluck('idEksemplar')->toArray();
        $this->assertCount(7, array_unique($ids), 'Setiap eksemplar harus memiliki idEksemplar yang unik');

        $nomors = $copies->pluck('nomor_eksemplar')->toArray();
        $this->assertEquals([1, 2, 3, 4, 5, 6, 7], $nomors);

        $tokens = $copies->pluck('qr_token')->toArray();
        $this->assertCount(7, array_unique($tokens), 'Setiap eksemplar harus memiliki qr_token yang unik');
    }

    public function test_halaman_stok_petugas_menampilkan_setiap_eksemplar_sebagai_baris_terpisah(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Pemrograman Web',
            'penulis' => 'Zuhdi',
            'penerbit' => 'Informatika',
            'tahunTerbit' => 2026,
            'harga' => 120000,
            'stok' => 7,
            'kondisi' => 'Baik',
        ]);

        $response = $this->actingAs($this->petugas)->get(route('buku.index'));

        $response->assertOk();
        $response->assertSee('Pemrograman Web');

        // Pastikan setiap ID eksemplar ditampilkan sebagai identitas baris tersendiri
        foreach ($buku->eksemplar as $eks) {
            $response->assertSee('#EK-'.$eks->idEksemplar);
            $response->assertSee('Eksemplar #'.$eks->nomor_eksemplar);
        }
    }

    public function test_peminjaman_mengubah_status_eksemplar_tertentu_dan_tampil_di_halaman_stok(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Basis Data Lanjut',
            'penulis' => 'C.J. Date',
            'penerbit' => 'Erlangga',
            'tahunTerbit' => 2025,
            'harga' => 150000,
            'stok' => 3,
            'kondisi' => 'Baik',
        ]);

        $copy1 = $buku->eksemplar()->where('nomor_eksemplar', 1)->first();
        $copy2 = $buku->eksemplar()->where('nomor_eksemplar', 2)->first();
        $copy3 = $buku->eksemplar()->where('nomor_eksemplar', 3)->first();

        // Pinjamkan copy #1 kepada member
        $this->actingAs($this->petugas);
        $resPinjam = $this->post(route('peminjaman.store'), [
            'idUserMember' => $this->member->id,
            'barcodes' => [$copy1->qr_token],
        ]);
        $resPinjam->assertSessionHasNoErrors();

        $copy1->refresh();
        $copy2->refresh();
        $copy3->refresh();
        $buku->refresh();

        $this->assertEquals('Dipinjam', $copy1->status);
        $this->assertEquals('Tersedia', $copy2->status);
        $this->assertEquals('Tersedia', $copy3->status);
        $this->assertEquals(2, $buku->stok);

        // Halaman stok petugas menampilkan informasi peminjam pada baris eksemplar #1
        $resIndex = $this->actingAs($this->petugas)->get(route('buku.index'));
        $resIndex->assertOk();
        $resIndex->assertSee('#EK-'.$copy1->idEksemplar);
        $resIndex->assertSee('Dipinjam');
        $resIndex->assertSee('Member Pembaca');
    }

    public function test_pengembalian_memulihkan_status_eksemplar_dan_stok(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Algoritma Pemrograman',
            'penulis' => 'Rinaldi Munir',
            'penerbit' => 'Informatika',
            'tahunTerbit' => 2024,
            'harga' => 100000,
            'stok' => 2,
            'kondisi' => 'Baik',
        ]);

        $copy = $buku->eksemplar()->first();

        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'idUserPetugas' => $this->petugas->id,
            'tanggalPinjam' => Carbon::now()->toDateString(),
            'batasKembali' => Carbon::now()->addDays(30)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        $detail = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $copy->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        $copy->update(['status' => 'Dipinjam']);
        $buku->syncStok();
        $this->assertEquals(1, $buku->stok);

        // Petugas memproses pengembalian eksemplar
        $this->actingAs($this->petugas);
        $responseKembali = $this->post(route('pengembalian.store'), [
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $copy->idEksemplar,
            'kondisiBuku' => 'Baik',
        ]);
        $responseKembali->assertSessionHasNoErrors();

        $copy->refresh();
        $buku->refresh();
        $this->assertEquals('Tersedia', $copy->status);
        $this->assertEquals(2, $buku->stok);
    }

    public function test_admin_dapat_menambah_eksemplar_fisik_baru(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Sistem Terdistribusi',
            'penulis' => 'Tanenbaum',
            'penerbit' => 'Pearson',
            'tahunTerbit' => 2023,
            'harga' => 250000,
            'stok' => 2,
            'kondisi' => 'Baik',
        ]);

        $this->actingAs($this->admin);
        $response = $this->post(route('eksemplar.store', $buku->idBuku), [
            'jumlah' => 3,
            'kondisi' => 'Baik',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $buku->refresh();
        $this->assertCount(5, $buku->eksemplar);
        $this->assertEquals(5, $buku->stok);

        $nomors = $buku->eksemplar()->orderBy('nomor_eksemplar')->pluck('nomor_eksemplar')->toArray();
        $this->assertEquals([1, 2, 3, 4, 5], $nomors);
    }

    public function test_petugas_dapat_memperbarui_kondisi_eksemplar_individual(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Jaringan Komputer',
            'penulis' => 'Forouzan',
            'penerbit' => 'McGraw-Hill',
            'tahunTerbit' => 2022,
            'harga' => 175000,
            'stok' => 2,
            'kondisi' => 'Baik',
        ]);

        $copy = $buku->eksemplar()->first();

        $this->actingAs($this->petugas);
        $response = $this->patch(route('eksemplar.update-kondisi', $copy->idEksemplar), [
            'kondisi' => 'Rusak',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $copy->refresh();
        $this->assertEquals('Rusak', $copy->kondisi);
    }

    public function test_tidak_dapat_mengubah_status_eksemplar_yang_sedang_dipinjam(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Kecerdasan Buatan',
            'penulis' => 'Russell & Norvig',
            'penerbit' => 'Pearson',
            'tahunTerbit' => 2024,
            'harga' => 300000,
            'stok' => 1,
            'kondisi' => 'Baik',
        ]);

        $copy = $buku->eksemplar()->first();
        $copy->update(['status' => 'Dipinjam']);

        $this->actingAs($this->petugas);
        $response = $this->patch(route('eksemplar.update-kondisi', $copy->idEksemplar), [
            'kondisi' => 'Baik',
            'status' => 'Tersedia', // Mencoba ubah paksa status tanpa pengembalian
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $copy->refresh();
        $this->assertEquals('Dipinjam', $copy->status);
    }

    public function test_alokasi_otomatis_maksimal_2_eksemplar_di_rak_dan_sisanya_di_gudang(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Arsitektur Komputer',
            'penulis' => 'Stallings',
            'penerbit' => 'Pearson',
            'tahunTerbit' => 2024,
            'harga' => 180000,
            'stok' => 5,
            'kondisi' => 'Baik',
        ]);

        $copies = $buku->eksemplar()->orderBy('nomor_eksemplar')->get();
        $this->assertCount(5, $copies);

        $this->assertEquals('Rak', $copies[0]->lokasi);
        $this->assertEquals('Rak', $copies[1]->lokasi);
        $this->assertEquals('Gudang', $copies[2]->lokasi);
        $this->assertEquals('Gudang', $copies[3]->lokasi);
        $this->assertEquals('Gudang', $copies[4]->lokasi);
    }

    public function test_petugas_dapat_memindahkan_eksemplar_antara_rak_dan_gudang(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Jaringan Nirkabel',
            'penulis' => 'Kurose',
            'penerbit' => 'Pearson',
            'tahunTerbit' => 2025,
            'harga' => 190000,
            'stok' => 3,
            'kondisi' => 'Baik',
        ]);

        $copyGudang = $buku->eksemplar()->where('nomor_eksemplar', 3)->first();
        $this->assertEquals('Gudang', $copyGudang->lokasi);

        // Restock copy #3 ke Rak
        $this->actingAs($this->petugas);
        $resRestock = $this->patch(route('eksemplar.restock', $copyGudang->idEksemplar));
        $resRestock->assertRedirect();
        $resRestock->assertSessionHas('success');

        $copyGudang->refresh();
        $this->assertEquals('Rak', $copyGudang->lokasi);

        // Simpan kembali copy #3 ke Gudang
        $resSimpan = $this->patch(route('eksemplar.simpan-gudang', $copyGudang->idEksemplar));
        $resSimpan->assertRedirect();
        $resSimpan->assertSessionHas('success');

        $copyGudang->refresh();
        $this->assertEquals('Gudang', $copyGudang->lokasi);
    }

    public function test_banner_dan_fitur_restock_otomatis_bekerja_saat_rak_display_kosong(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Struktur Data & Algoritma',
            'penulis' => 'Sedgewick',
            'penerbit' => 'Addison-Wesley',
            'tahunTerbit' => 2024,
            'harga' => 220000,
            'stok' => 4,
            'kondisi' => 'Baik',
        ]);

        // 2 copy di rak, 2 di gudang
        $copyRak1 = $buku->eksemplar()->where('nomor_eksemplar', 1)->first();
        $copyRak2 = $buku->eksemplar()->where('nomor_eksemplar', 2)->first();

        // Pinjam kedua eksemplar di rak
        $copyRak1->update(['status' => 'Dipinjam']);
        $copyRak2->update(['status' => 'Dipinjam']);
        $buku->syncStok();

        $buku->refresh();
        $this->assertTrue($buku->perluRestock(), 'Buku harus membutuhkan restock karena rak kosong tapi gudang masih punya stok');

        // Buka buku.index, harus melihat banner restock
        $resIndex = $this->actingAs($this->petugas)->get(route('buku.index'));
        $resIndex->assertOk();
        $resIndex->assertSee('Perlu Restock ke Rak');

        // Lakukan restock otomatis untuk buku ini
        $resRestockBuku = $this->post(route('buku.restock-rak', $buku->idBuku));
        $resRestockBuku->assertRedirect();
        $resRestockBuku->assertSessionHas('success');

        $buku->refresh();
        $this->assertFalse($buku->perluRestock(), 'Buku tidak lagi memerlukan restock setelah eksemplar cadangan dipajang ke rak');
        $this->assertEquals(1, $buku->eksemplarDiRak()->where('status', 'Tersedia')->count());
    }

    public function test_pengembalian_memperhatikan_kapasitas_rak_maksimal(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Deep Learning',
            'penulis' => 'Goodfellow',
            'penerbit' => 'MIT Press',
            'tahunTerbit' => 2025,
            'harga' => 350000,
            'stok' => 3,
            'kondisi' => 'Baik',
        ]);

        // Copy 1 (Rak), Copy 2 (Rak), Copy 3 (Gudang)
        $copy1 = $buku->eksemplar()->where('nomor_eksemplar', 1)->first();

        // Pinjam copy 1
        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'idUserPetugas' => $this->petugas->id,
            'tanggalPinjam' => Carbon::now()->toDateString(),
            'batasKembali' => Carbon::now()->addDays(30)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $copy1->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        $copy1->update(['status' => 'Dipinjam']);

        // Sekarang di Rak tinggal 1 buku tersedia (Copy 2).
        // Ketika copy 1 dikembalikan, karena di rak baru ada 1 (< 2), maka copy 1 masuk kembali ke Rak.
        $this->actingAs($this->petugas);
        $this->post(route('pengembalian.store'), [
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $copy1->idEksemplar,
            'kondisiBuku' => 'Baik',
        ]);

        $copy1->refresh();
        $this->assertEquals('Tersedia', $copy1->status);
        $this->assertEquals('Rak', $copy1->lokasi);
    }
}
