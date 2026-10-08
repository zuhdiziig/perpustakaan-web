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
        $response->assertSee('Pengembalian');
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

    public function test_petugas_dashboard_displays_booking_stat_card(): void
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $member = User::factory()->create([
            'name' => 'Budi Santoso',
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $buku = Buku::factory()->create(['judul' => 'Atomic Habits', 'rak' => 'Rak A-12', 'stok' => 1]);
        $eksemplar = $buku->eksemplar()->first();
        $eksemplar->update(['status' => 'Dibooking']);

        $booking = Peminjaman::create([
            'idUserMember' => $member->id,
            'kode_booking' => 'BK-20261007-9999',
            'qr_token' => 'book_test_token_123',
            'opsi_pengambilan' => 'siapkan_petugas',
            'tanggalPinjam' => today(),
            'batasKembali' => today()->addDays(30),
            'status' => 'Booking',
            'totalBuku' => 1,
            'batasAmbil' => now()->addHours(48),
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $booking->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Booking',
        ]);

        $response = $this->actingAs($petugas)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Booking menunggu');
        $response->assertViewHas('statistik', fn (array $statistik): bool => ($statistik['bookingMenunggu'] ?? 0) === 1);
        $response->assertDontSee('Pemberitahuan Sirkulasi:');
        $response->assertDontSee('Antrean Booking & Reservasi Mandiri');
    }

    public function test_petugas_can_mark_booking_as_ready_for_pickup(): void
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $buku = Buku::factory()->create(['judul' => 'Laskar Pelangi', 'stok' => 1]);
        $eksemplar = $buku->eksemplar()->first();
        $eksemplar->update(['status' => 'Dibooking']);

        $booking = Peminjaman::create([
            'idUserMember' => $member->id,
            'kode_booking' => 'BK-20261007-1234',
            'opsi_pengambilan' => 'siapkan_petugas',
            'tanggalPinjam' => today(),
            'batasKembali' => today()->addDays(30),
            'status' => 'Booking',
            'totalBuku' => 1,
            'batasAmbil' => now()->addHours(48),
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $booking->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Booking',
        ]);

        $response = $this->actingAs($petugas)->post(route('petugas.booking.siapkan', $booking->idPeminjaman));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $booking->refresh();
        $this->assertSame('Siap Diambil', $booking->status);
    }

    public function test_petugas_can_handover_booking_to_member(): void
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $buku = Buku::factory()->create(['judul' => 'Sapiens', 'stok' => 1]);
        $eksemplar = $buku->eksemplar()->first();
        $eksemplar->update(['status' => 'Dibooking']);

        $booking = Peminjaman::create([
            'idUserMember' => $member->id,
            'kode_booking' => 'BK-20261007-5678',
            'opsi_pengambilan' => 'ambil_mandiri',
            'tanggalPinjam' => today(),
            'batasKembali' => today()->addDays(30),
            'status' => 'Booking',
            'totalBuku' => 1,
            'batasAmbil' => now()->addHours(48),
        ]);

        $detail = DetailPeminjaman::create([
            'idPeminjaman' => $booking->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Booking',
        ]);

        $response = $this->actingAs($petugas)->post(route('petugas.booking.serah-terima', $booking->idPeminjaman));

        $response->assertRedirect(route('peminjaman.show', $booking->idPeminjaman));
        $response->assertSessionHas('success');

        $booking->refresh();
        $detail->refresh();
        $eksemplar->refresh();

        $this->assertSame('Dipinjam', $booking->status);
        $this->assertSame($petugas->id, $booking->idUserPetugas);
        $this->assertNotNull($booking->tanggalPinjam);
        $this->assertNotNull($booking->batasKembali);
        $this->assertSame('Dipinjam', $detail->statusBuku);
        $this->assertSame('Dipinjam', $eksemplar->status);
    }

    public function test_member_cannot_access_petugas_booking_actions(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $booking = Peminjaman::create([
            'idUserMember' => $member->id,
            'kode_booking' => 'BK-20261007-0099',
            'opsi_pengambilan' => 'siapkan_petugas',
            'tanggalPinjam' => today(),
            'batasKembali' => today()->addDays(30),
            'status' => 'Booking',
            'totalBuku' => 1,
        ]);

        $responseSiapkan = $this->actingAs($member)->post(route('petugas.booking.siapkan', $booking->idPeminjaman));
        $responseSiapkan->assertForbidden();

        $responseSerahTerima = $this->actingAs($member)->post(route('petugas.booking.serah-terima', $booking->idPeminjaman));
        $responseSerahTerima->assertForbidden();
    }
}
