<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'name' => 'Arif Hidayat',
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.admin');
        $response->assertSee('Dasbor Admin');
        $response->assertSee('Panel Admin');
        $response->assertSee('Manajemen perpustakaan');
        $response->assertSee('Kelola Buku');
        $response->assertSee('Kelola Kategori');
        $response->assertSee('Kelola Anggota');
        $response->assertSee('Kelola Petugas');
        $response->assertSee('Kelola Transaksi');
        $response->assertSee('Kelola Denda');
        $response->assertSee('Lihat Laporan');
        $response->assertSee('Laporan bulanan');
        $response->assertSee('Unduh Laporan');
    }

    public function test_admin_dashboard_displays_correct_library_metrics(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $petugas = User::factory()->create(['role' => 'petugas', 'status' => 'aktif']);
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);

        $kategori = Kategori::factory()->create(['namaKategori' => 'Fiksi']);
        $buku = Buku::factory()->create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Laut Bercerita',
            'stok' => 5,
        ]);

        $pinjam = Peminjaman::create([
            'idUserMember' => $member->id,
            'idUserPetugas' => $petugas->id,
            'tanggalPinjam' => today(),
            'batasKembali' => today()->addDays(7),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $pinjam->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Koleksi buku');
        $response->assertSee('Anggota aktif');
        $response->assertSee('Petugas aktif');
        $response->assertSee('Pembayaran tercatat sukses');
        $response->assertSee('Laut Bercerita');
    }

    public function test_admin_dashboard_search_filters_transactions(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $member1 = User::factory()->create(['name' => 'Rizky Pratama', 'role' => 'member']);
        $member2 = User::factory()->create(['name' => 'Siti Aisyah', 'role' => 'member']);

        $buku1 = Buku::factory()->create(['judul' => 'Laut Bercerita']);
        $buku2 = Buku::factory()->create(['judul' => 'Bumi Manusia']);

        $pinjam1 = Peminjaman::create([
            'idUserMember' => $member1->id,
            'tanggalPinjam' => today(),
            'batasKembali' => today()->addDays(7),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);
        DetailPeminjaman::create([
            'idPeminjaman' => $pinjam1->idPeminjaman,
            'idBuku' => $buku1->idBuku,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        $pinjam2 = Peminjaman::create([
            'idUserMember' => $member2->id,
            'tanggalPinjam' => today(),
            'batasKembali' => today()->addDays(7),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);
        DetailPeminjaman::create([
            'idPeminjaman' => $pinjam2->idPeminjaman,
            'idBuku' => $buku2->idBuku,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        // Cari 'Laut'
        $response = $this->actingAs($admin)->get('/dashboard?q=Laut');
        $response->assertStatus(200);
        $response->assertSee('Laut Bercerita');
        $response->assertViewHas('transaksi', fn ($transaksi) => $transaksi->contains('judul', 'Laut Bercerita') &&
            ! $transaksi->contains('judul', 'Bumi Manusia')
        );

        // Cari 'Aisyah'
        $response2 = $this->actingAs($admin)->get('/dashboard?q=Aisyah');
        $response2->assertStatus(200);
        $response2->assertSee('Bumi Manusia');
        $response2->assertViewHas('transaksi', fn ($transaksi) => $transaksi->contains('judul', 'Bumi Manusia') &&
            ! $transaksi->contains('judul', 'Laut Bercerita')
        );
    }
}
