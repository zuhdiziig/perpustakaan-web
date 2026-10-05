<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Denda;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_member_can_view_member_dashboard(): void
    {
        $member = User::factory()->create([
            'name' => 'Rizky Pratama',
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($member)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.member');
        $response->assertSee('Dasbor Anggota');
        $response->assertSee('Selamat membaca, Rizky!');
        $response->assertSee($member->kode_anggota);
        $response->assertSee('Panel Anggota');
    }

    public function test_admin_and_petugas_see_their_dashboards(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $petugas = User::factory()->create([
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $this->actingAs($admin)
            ->get('/dashboard')
            ->assertStatus(200)
            ->assertViewIs('dashboard.admin')
            ->assertSee('Panel Admin');

        $this->actingAs($petugas)
            ->get('/dashboard')
            ->assertStatus(200)
            ->assertViewIs('dashboard.petugas')
            ->assertSee('Panel Petugas');
    }

    public function test_dashboard_displays_correct_statistics_and_transaction_statuses(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $buku1 = Buku::factory()->create(['judul' => 'Laut Bercerita']);
        $buku2 = Buku::factory()->create(['judul' => 'Filosofi Teras']);
        $buku3 = Buku::factory()->create(['judul' => 'Bumi Manusia']);

        // 1. Pinjaman aktif yang belum terlambat
        $pinjam1 = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => today()->subDays(2),
            'batasKembali' => today()->addDays(12),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);
        DetailPeminjaman::create([
            'idPeminjaman' => $pinjam1->idPeminjaman,
            'idBuku' => $buku1->idBuku,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        // 2. Pinjaman aktif yang terlambat
        $pinjam2 = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => today()->subDays(20),
            'batasKembali' => today()->subDays(5),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);
        DetailPeminjaman::create([
            'idPeminjaman' => $pinjam2->idPeminjaman,
            'idBuku' => $buku2->idBuku,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        // 3. Pinjaman selesai (dikembalikan)
        $pinjam3 = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => today()->subDays(30),
            'batasKembali' => today()->subDays(15),
            'status' => 'Selesai',
            'totalBuku' => 1,
        ]);
        DetailPeminjaman::create([
            'idPeminjaman' => $pinjam3->idPeminjaman,
            'idBuku' => $buku3->idBuku,
            'jumlah' => 1,
            'statusBuku' => 'Kembali',
        ]);

        $response = $this->actingAs($member)->get('/dashboard');

        $response->assertStatus(200);
        // Statistik sedang dipinjam: 2 buku
        $response->assertSee('2 buku');
        // Total dibaca: 1 buku
        $response->assertSee('1 buku');
        // Status di tabel
        $response->assertSee('Laut Bercerita');
        $response->assertSee('Filosofi Teras');
        $response->assertSee('Bumi Manusia');
        $response->assertSee('Dipinjam');
        $response->assertSee('Terlambat');
        $response->assertSee('Dikembalikan');
    }

    public function test_fine_alert_appears_when_unpaid_fine_exists(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $petugas = User::factory()->create([
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $buku = Buku::factory()->create(['judul' => 'Filosofi Teras']);

        $pinjam = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => today()->subDays(20),
            'batasKembali' => today()->subDays(5),
            'status' => 'Selesai',
            'totalBuku' => 1,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $pinjam->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'jumlah' => 1,
            'statusBuku' => 'Kembali',
        ]);

        $pengembalian = Pengembalian::create([
            'idPeminjaman' => $pinjam->idPeminjaman,
            'idUserPetugas' => $petugas->id,
            'tanggalKembali' => today(),
            'kondisiBuku' => 'Baik',
        ]);

        Denda::create([
            'idPengembalian' => $pengembalian->idPengembalian,
            'jenisDenda' => 'Terlambat 1 Minggu',
            'jumlah' => 5000,
            'status' => 'Belum Dibayar',
        ]);

        $response = $this->actingAs($member)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Ada denda yang perlu diselesaikan');
        $response->assertSee('Rp5.000');
        $response->assertSee('Lihat Denda');
    }

    public function test_transaction_search_filters_results(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $buku1 = Buku::factory()->create(['judul' => 'Laut Bercerita']);
        $buku2 = Buku::factory()->create(['judul' => 'Pulang']);

        $pinjam = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => today(),
            'batasKembali' => today()->addDays(7),
            'status' => 'Dipinjam',
            'totalBuku' => 2,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $pinjam->idPeminjaman,
            'idBuku' => $buku1->idBuku,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $pinjam->idPeminjaman,
            'idBuku' => $buku2->idBuku,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        // Cari 'Laut'
        $response = $this->actingAs($member)->get('/dashboard?q=Laut');

        $response->assertStatus(200);
        $response->assertSee('Laut Bercerita');
        $response->assertDontSee('Pulang');
    }
}
