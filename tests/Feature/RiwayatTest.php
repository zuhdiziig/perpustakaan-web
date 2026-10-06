<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiwayatTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_riwayat_page(): void
    {
        $response = $this->get(route('riwayat.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_member_can_view_riwayat_page(): void
    {
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);

        $buku = Buku::factory()->create(['judul' => 'Filosofi Teras']);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => now()->toDateString(),
            'batasKembali' => now()->addDays(14)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $buku->eksemplar->first()->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        $response = $this->actingAs($member)->get(route('riwayat.index'));

        $response->assertOk();
        $response->assertViewIs('riwayat.index');
        $response->assertSee('Riwayat Peminjaman Buku');
        $response->assertSee('Filosofi Teras');
        $response->assertSee('#TRX-'.str_pad($peminjaman->idPeminjaman, 5, '0', STR_PAD_LEFT));
    }

    public function test_member_can_filter_riwayat_by_status(): void
    {
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);

        $bukuDipinjam = Buku::factory()->create(['judul' => 'Buku Sedang Dipinjam']);
        $bukuSelesai = Buku::factory()->create(['judul' => 'Buku Sudah Selesai']);

        // Peminjaman 1: Dipinjam
        $p1 = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => now()->toDateString(),
            'batasKembali' => now()->addDays(14)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);
        DetailPeminjaman::create([
            'idPeminjaman' => $p1->idPeminjaman,
            'idBuku' => $bukuDipinjam->idBuku,
            'idEksemplar' => $bukuDipinjam->eksemplar->first()->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        // Peminjaman 2: Selesai
        $p2 = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => now()->subMonth()->toDateString(),
            'batasKembali' => now()->subWeeks(2)->toDateString(),
            'status' => 'Selesai',
            'totalBuku' => 1,
        ]);
        DetailPeminjaman::create([
            'idPeminjaman' => $p2->idPeminjaman,
            'idBuku' => $bukuSelesai->idBuku,
            'idEksemplar' => $bukuSelesai->eksemplar->first()->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Dikembalikan',
        ]);

        // Filter 'Dipinjam'
        $responseDipinjam = $this->actingAs($member)->get(route('riwayat.index', ['status' => 'Dipinjam']));
        $responseDipinjam->assertOk();
        $responseDipinjam->assertSee('Buku Sedang Dipinjam');
        $responseDipinjam->assertDontSee('Buku Sudah Selesai');

        // Filter 'Selesai'
        $responseSelesai = $this->actingAs($member)->get(route('riwayat.index', ['status' => 'Selesai']));
        $responseSelesai->assertOk();
        $responseSelesai->assertSee('Buku Sudah Selesai');
        $responseSelesai->assertDontSee('Buku Sedang Dipinjam');
    }
}
