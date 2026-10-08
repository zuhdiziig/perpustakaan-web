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
        $response->assertSee('Metode Pengambilan Buku');
        $response->assertSee('Ambil Mandiri Langsung di Rak Buku');
        $response->assertSee('Booking Buku');
        $response->assertSee('Dapatkan QR Code');
    }

    public function test_member_can_submit_booking_request_successfully(): void
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

        $response = $this->actingAs($member)->post(route('peminjaman.ajukan', $buku->idBuku), [
            'opsi_pengambilan' => 'ambil_mandiri',
        ]);

        $peminjaman = Peminjaman::where('idUserMember', $member->id)->first();
        $this->assertNotNull($peminjaman);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->assertEquals('Booking', $peminjaman->status);
        $this->assertEquals('ambil_mandiri', $peminjaman->opsi_pengambilan);
        $this->assertNotNull($peminjaman->kode_booking);
        $this->assertNotNull($peminjaman->qr_token);
        $this->assertNotNull($peminjaman->batasAmbil);

        $this->assertDatabaseHas('detail_peminjaman', [
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'statusBuku' => 'Booking',
            'jumlah' => 1,
        ]);

        $this->assertEquals(2, $buku->fresh()->stok);
    }

    public function test_member_can_view_booking_ticket_with_qr_code(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $buku = Buku::factory()->create([
            'judul' => 'Cantik Itu Luka',
            'stok' => 2,
        ]);

        $this->actingAs($member)->post(route('peminjaman.ajukan', $buku->idBuku), [
            'opsi_pengambilan' => 'ambil_mandiri',
        ]);

        $peminjaman = Peminjaman::where('idUserMember', $member->id)->first();

        $response = $this->actingAs($member)->get(route('peminjaman.booking.tiket', $peminjaman->idPeminjaman));

        $response->assertOk();
        $response->assertViewIs('peminjaman.booking_tiket');
        $response->assertSee('Tiket Booking Peminjaman');
        $response->assertSee('Cantik Itu Luka');
        $response->assertSee($peminjaman->kode_booking);
        $response->assertSee('Ambil Mandiri');
    }

    public function test_other_member_cannot_view_someone_elses_booking_ticket(): void
    {
        $member1 = User::factory()->create(['role' => 'member']);
        $member2 = User::factory()->create(['role' => 'member']);

        $buku = Buku::factory()->create(['stok' => 2]);

        $this->actingAs($member1)->post(route('peminjaman.ajukan', $buku->idBuku));
        $peminjaman = Peminjaman::where('idUserMember', $member1->id)->first();

        $response = $this->actingAs($member2)->get(route('peminjaman.booking.tiket', $peminjaman->idPeminjaman));
        $response->assertForbidden();
    }

    public function test_member_can_cancel_active_booking(): void
    {
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);
        $buku = Buku::factory()->create(['stok' => 2]);

        $this->actingAs($member)->post(route('peminjaman.ajukan', $buku->idBuku));
        $peminjaman = Peminjaman::where('idUserMember', $member->id)->first();
        $this->assertEquals(1, $buku->fresh()->stok);

        $response = $this->actingAs($member)->post(route('peminjaman.booking.batal', $peminjaman->idPeminjaman));

        $response->assertRedirect(route('riwayat.index'));
        $response->assertSessionHas('success');

        $this->assertEquals('Dibatalkan', $peminjaman->fresh()->status);
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

    public function test_member_can_poll_booking_status_realtime(): void
    {
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);
        $petugas = User::factory()->create(['role' => 'petugas', 'name' => 'Petugas Sirkulasi']);
        $buku = Buku::factory()->create(['stok' => 2]);

        $this->actingAs($member)->post(route('peminjaman.ajukan', $buku->idBuku));
        $peminjaman = Peminjaman::where('idUserMember', $member->id)->first();

        // 1. Cek saat status masih Booking
        $resPending = $this->actingAs($member)->getJson(route('peminjaman.booking.status-tiket', $peminjaman->idPeminjaman));
        $resPending->assertOk()
            ->assertJson([
                'success' => true,
                'completed' => false,
                'status' => 'Booking',
            ]);

        // 2. Petugas mengonfirmasi penyerahan buku
        $peminjaman->update([
            'status' => 'Dipinjam',
            'idUserPetugas' => $petugas->id,
            'batasKembali' => now()->addDays(30)->toDateString(),
        ]);

        // 3. Polling ulang mendeteksi completed dan data serah terima
        $resCompleted = $this->actingAs($member)->getJson(route('peminjaman.booking.status-tiket', $peminjaman->idPeminjaman));
        $resCompleted->assertOk()
            ->assertJson([
                'success' => true,
                'completed' => true,
                'status' => 'Dipinjam',
                'kodeBooking' => $peminjaman->kode_booking,
                'namaPetugas' => 'Petugas Sirkulasi',
            ]);
    }
}
