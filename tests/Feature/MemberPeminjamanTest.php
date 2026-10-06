<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberPeminjamanTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_loan_confirmation(): void
    {
        $buku = Buku::factory()->create();

        $response = $this->get(route('peminjaman.konfirmasi', $buku->idBuku));

        $response->assertRedirect(route('login'));
    }

    public function test_member_can_view_loan_confirmation_page_with_complete_data(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'name' => 'Rizky Pratama',
            'status' => 'aktif',
        ]);

        $kategori = Kategori::factory()->create(['namaKategori' => 'Fiksi']);
        $buku = Buku::factory()->create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Laut Bercerita',
            'penulis' => 'Leila S. Chudori',
            'penerbit' => 'Kepustakaan Populer Gramedia',
            'tahunTerbit' => 2017,
            'stok' => 5,
        ]);

        $response = $this->actingAs($member)->get(route('peminjaman.konfirmasi', $buku->idBuku));

        $response->assertOk();
        $response->assertViewIs('peminjaman.member_konfirmasi');
        $response->assertSee('Peminjaman Buku');
        $response->assertSee('Laut Bercerita');
        $response->assertSee('Leila S. Chudori');
        $response->assertSee('Rizky Pratama');
        $response->assertSee($member->kodeAnggota);
        $response->assertSee('Rincian Peminjaman');
        $response->assertSee('Konfirmasi Peminjaman');
    }

    public function test_member_can_submit_borrow_request_successfully(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $buku = Buku::factory()->create([
            'judul' => 'Bumi Manusia',
            'stok' => 3,
        ]);

        $this->assertEquals(3, $buku->stok);

        $response = $this->actingAs($member)->post(route('peminjaman.ajukan', $buku->idBuku));

        $response->assertRedirect(route('riwayat.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('peminjaman', [
            'idUserMember' => $member->id,
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        $this->assertDatabaseHas('detail_peminjaman', [
            'idBuku' => $buku->idBuku,
            'statusBuku' => 'Dipinjam',
            'jumlah' => 1,
        ]);

        $this->assertEquals(2, $buku->fresh()->stok);
    }

    public function test_member_cannot_borrow_book_if_quota_exceeded(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $bukuBaru = Buku::factory()->create(['stok' => 2]);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => now()->toDateString(),
            'batasKembali' => now()->addMonth()->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 7,
        ]);

        for ($i = 0; $i < 7; $i++) {
            $bukuLain = Buku::factory()->create(['stok' => 2]);
            DetailPeminjaman::create([
                'idPeminjaman' => $peminjaman->idPeminjaman,
                'idBuku' => $bukuLain->idBuku,
                'jumlah' => 1,
                'statusBuku' => 'Dipinjam',
            ]);
        }

        $response = $this->actingAs($member)->post(route('peminjaman.ajukan', $bukuBaru->idBuku));

        $response->assertSessionHas('error');
        $this->assertEquals(2, $bukuBaru->fresh()->stok);
    }

    public function test_member_cannot_borrow_out_of_stock_book(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $buku = Buku::factory()->habis()->create(['judul' => 'Buku Langka']);

        $response = $this->actingAs($member)->post(route('peminjaman.ajukan', $buku->idBuku));

        $response->assertSessionHas('error');
    }
}
