<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetugasDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_can_view_petugas_dashboard(): void
    {
        $petugas = User::factory()->create([
            'name' => 'Dina Amelia',
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($petugas)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.petugas');
        $response->assertSee('Dasbor Petugas');
        $response->assertSee('Selamat bertugas, Dina Amelia.');
        $response->assertSee('Panel Petugas');
        $response->assertSee('Scan Barcode');
        $response->assertSee('Catat Pengembalian');
        $response->assertSee('Pengingat petugas');
    }

    public function test_petugas_dashboard_displays_correct_circulation_statistics(): void
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $member = User::factory()->create([
            'name' => 'Rizky Pratama',
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $buku1 = Buku::factory()->create(['judul' => 'Laut Bercerita']);
        $buku2 = Buku::factory()->create(['judul' => 'Filosofi Teras']);

        // 1. Peminjaman hari ini (aktif, belum terlambat)
        $pinjamHariIni = Peminjaman::create([
            'idUserMember' => $member->id,
            'idUserPetugas' => $petugas->id,
            'tanggalPinjam' => today(),
            'batasKembali' => today()->addDays(14),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);
        DetailPeminjaman::create([
            'idPeminjaman' => $pinjamHariIni->idPeminjaman,
            'idBuku' => $buku1->idBuku,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        // 2. Peminjaman lama yang sudah terlambat (aktif)
        $pinjamTerlambat = Peminjaman::create([
            'idUserMember' => $member->id,
            'idUserPetugas' => $petugas->id,
            'tanggalPinjam' => today()->subDays(20),
            'batasKembali' => today()->subDays(3),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);
        DetailPeminjaman::create([
            'idPeminjaman' => $pinjamTerlambat->idPeminjaman,
            'idBuku' => $buku2->idBuku,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        // 3. Pengembalian hari ini
        $pinjamKembali = Peminjaman::create([
            'idUserMember' => $member->id,
            'idUserPetugas' => $petugas->id,
            'tanggalPinjam' => today()->subDays(5),
            'batasKembali' => today()->addDays(5),
            'status' => 'Selesai',
            'totalBuku' => 1,
        ]);
        Pengembalian::create([
            'idPeminjaman' => $pinjamKembali->idPeminjaman,
            'idUserPetugas' => $petugas->id,
            'tanggalKembali' => today(),
            'kondisiBuku' => 'Baik',
        ]);

        $response = $this->actingAs($petugas)->get('/dashboard');

        $response->assertStatus(200);
        // Peminjaman hari ini: 1
        $response->assertSee('Peminjaman hari ini');
        // Pengembalian hari ini: 1
        $response->assertSee('Pengembalian hari ini');
        // Peminjaman aktif: 2
        $response->assertSee('Peminjaman aktif');
        // Terlambat: 1
        $response->assertSee('Terlambat');
        // Baris tabel transaksi
        $response->assertSee('Laut Bercerita');
        $response->assertSee('Filosofi Teras');
        $response->assertSee('Dipinjam');
        $response->assertSee('Terlambat');
    }

    public function test_petugas_dashboard_search_filters_transactions(): void
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $member1 = User::factory()->create(['name' => 'Nadia Putri', 'role' => 'member']);
        $member2 = User::factory()->create(['name' => 'Siti Aisyah', 'role' => 'member']);

        $buku1 = Buku::factory()->create(['judul' => 'Bumi Manusia']);
        $buku2 = Buku::factory()->create(['judul' => 'Pulang']);

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

        // Search by book title 'Bumi'
        $response = $this->actingAs($petugas)->get('/dashboard?q=Bumi');
        $response->assertStatus(200);
        $response->assertSee('Bumi Manusia');
        $response->assertViewHas('transaksi', fn ($transaksi) => $transaksi->contains('judul', 'Bumi Manusia') &&
            ! $transaksi->contains('judul', 'Pulang')
        );

        // Search by member name 'Aisyah'
        $response2 = $this->actingAs($petugas)->get('/dashboard?q=Aisyah');
        $response2->assertStatus(200);
        $response2->assertSee('Pulang');
        $response2->assertViewHas('transaksi', fn ($transaksi) => $transaksi->contains('judul', 'Pulang') &&
            ! $transaksi->contains('judul', 'Bumi Manusia')
        );
    }
}
