<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\BukuEksemplar;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CancelExpiredBookingsTest extends TestCase
{
    use RefreshDatabase;

    private User $member;

    private Buku $buku;

    private BukuEksemplar $eksemplar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $kategori = Kategori::create([
            'namaKategori' => 'Teknologi',
            'deskripsi' => 'Buku Komputer',
        ]);

        $this->buku = Buku::create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Clean Architecture',
            'penulis' => 'Robert C. Martin',
            'penerbit' => 'Prentice Hall',
            'tahunTerbit' => 2026,
            'harga' => 150000,
            'stok' => 1,
        ]);

        $this->eksemplar = $this->buku->eksemplar()->first();
    }

    public function test_expired_booking_is_automatically_cancelled_and_stock_restored(): void
    {
        // Set eksemplar to Dibooking
        $this->eksemplar->update(['status' => 'Dibooking']);
        $this->buku->syncStok();
        $this->assertEquals(0, $this->buku->stok);

        // Booking kadaluarsa (dibuat 3 hari lalu, batas ambil kemarin)
        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'tanggalPinjam' => Carbon::now()->subDays(3)->toDateString(),
            'batasKembali' => Carbon::now()->addDays(27)->toDateString(),
            'batasAmbil' => Carbon::now()->subHours(5), // Sudah lewat batas ambil
            'status' => 'Booking',
            'totalBuku' => 1,
            'kode_booking' => 'BK-EXP-001',
            'qr_token' => 'qr_exp_001',
        ]);

        $detail = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $this->buku->idBuku,
            'idEksemplar' => $this->eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Booking',
        ]);

        // Jalankan console command
        $this->artisan('booking:cancel-expired')
            ->expectsOutput('Berhasil membatalkan 1 transaksi booking kadaluarsa.')
            ->assertSuccessful();

        $peminjaman->refresh();
        $detail->refresh();
        $this->eksemplar->refresh();
        $this->buku->refresh();

        $this->assertEquals('Dibatalkan', $peminjaman->status);
        $this->assertEquals('Dibatalkan', $detail->statusBuku);
        $this->assertEquals('Tersedia', $this->eksemplar->status);
        $this->assertEquals(1, $this->buku->stok);
    }

    public function test_active_booking_is_not_cancelled(): void
    {
        $this->eksemplar->update(['status' => 'Dibooking']);
        $this->buku->syncStok();

        // Booking masih aktif (batas ambil masih 24 jam ke depan)
        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'tanggalPinjam' => Carbon::now()->toDateString(),
            'batasKembali' => Carbon::now()->addDays(30)->toDateString(),
            'batasAmbil' => Carbon::now()->addHours(24),
            'status' => 'Booking',
            'totalBuku' => 1,
            'kode_booking' => 'BK-ACT-002',
            'qr_token' => 'qr_act_002',
        ]);

        $detail = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $this->buku->idBuku,
            'idEksemplar' => $this->eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Booking',
        ]);

        $this->artisan('booking:cancel-expired')
            ->expectsOutput('Tidak ada antrean booking yang kadaluarsa.')
            ->assertSuccessful();

        $peminjaman->refresh();
        $this->assertEquals('Booking', $peminjaman->status);
        $this->assertEquals('Dibooking', $this->eksemplar->fresh()->status);
    }
}
