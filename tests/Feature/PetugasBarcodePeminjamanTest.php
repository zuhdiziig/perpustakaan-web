<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\BukuEksemplar;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetugasBarcodePeminjamanTest extends TestCase
{
    use RefreshDatabase;

    private User $petugas;

    private User $member;

    private Kategori $kategori;

    private Buku $buku;

    private BukuEksemplar $eksemplar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->petugas = User::create([
            'name' => 'Dina Amelia',
            'email' => 'dina.petugas@perpus.test',
            'password' => bcrypt('password123'),
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $this->member = User::create([
            'name' => 'Rizky Pratama',
            'email' => 'rizky@perpus.test',
            'password' => bcrypt('password123'),
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $this->kategori = Kategori::create([
            'namaKategori' => 'Fiksi',
            'deskripsi' => 'Kategori Buku Fiksi',
        ]);

        $this->buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Laut Bercerita',
            'penulis' => 'Leila S. Chudori',
            'penerbit' => 'KPG',
            'tahunTerbit' => 2026,
            'harga' => 115000,
            'stok' => 1,
            'kondisi' => 'Baik',
        ]);

        $this->eksemplar = $this->buku->eksemplar()->first();
    }

    public function test_petugas_dapat_mengakses_halaman_barcode_peminjaman(): void
    {
        $this->actingAs($this->petugas);

        $response = $this->get(route('peminjaman.create'));

        $response->assertOk()
            ->assertSee('Barcode Peminjaman')
            ->assertSee('Scan barcode')
            ->assertSee('Atau masukkan kode manual')
            ->assertSee('Rincian peminjaman')
            ->assertSee('Validasi petugas')
            ->assertSee('Konfirmasi Peminjaman')
            ->assertSee('Konfirmasi Barcode')
            ->assertSee('Periksa data anggota dan buku. Peminjaman berlangsung '.Peminjaman::MASA_PINJAM_HARI.' hari, tanpa biaya.')
            ->assertSee('id="modalOverlay"', false)
            ->assertSee('id="btnModalBatal"', false)
            ->assertSee('id="btnModalKonfirmasi"', false);
    }

    public function test_akses_peminjaman_index_oleh_petugas_dialihkan_ke_create(): void
    {
        $this->actingAs($this->petugas);

        $response = $this->get(route('peminjaman.index'));

        $response->assertRedirect(route('peminjaman.create'));
    }

    public function test_api_identifikasi_menolak_member_biasa(): void
    {
        $this->actingAs($this->member);

        $response = $this->postJson(route('api.scan.identifikasi'), [
            'code' => $this->member->qr_token,
        ]);

        $response->assertStatus(403);
    }

    public function test_api_identifikasi_mengenali_member(): void
    {
        $this->actingAs($this->petugas);

        $response = $this->postJson(route('api.scan.identifikasi'), [
            'code' => $this->member->qr_token,
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'type' => 'member',
                'data' => [
                    'id' => $this->member->id,
                    'name' => 'Rizky Pratama',
                    'kodeAnggota' => $this->member->kode_anggota,
                ],
            ]);
    }

    public function test_api_identifikasi_mengenali_eksemplar_buku(): void
    {
        $this->actingAs($this->petugas);

        $response = $this->postJson(route('api.scan.identifikasi'), [
            'code' => $this->eksemplar->qr_token,
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'type' => 'buku',
                'data' => [
                    'idBuku' => $this->buku->idBuku,
                    'idEksemplar' => $this->eksemplar->idEksemplar,
                    'judul' => 'Laut Bercerita',
                ],
            ]);
    }

    public function test_api_identifikasi_mengenali_transaksi_peminjaman(): void
    {
        $this->actingAs($this->petugas);

        // Buat peminjaman
        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'idUserPetugas' => $this->petugas->id,
            'tanggalPinjam' => now()->toDateString(),
            'batasKembali' => now()->addDays(14)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        $response = $this->postJson(route('api.scan.identifikasi'), [
            'code' => sprintf('PJ-%s-%04d', now()->format('Ymd'), $peminjaman->idPeminjaman),
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'type' => 'transaksi',
                'data' => [
                    'idPeminjaman' => $peminjaman->idPeminjaman,
                ],
            ]);
    }

    public function test_petugas_dapat_mengonfirmasi_peminjaman_secara_atomik(): void
    {
        $this->actingAs($this->petugas);

        $response = $this->post(route('peminjaman.store'), [
            'idUserMember' => $this->member->id,
            'barcodes' => [$this->eksemplar->qr_token],
        ]);

        $response->assertRedirect();

        // Verifikasi transaksi tercatat di database
        $this->assertDatabaseHas('peminjaman', [
            'idUserMember' => $this->member->id,
            'idUserPetugas' => $this->petugas->id,
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        // Status eksemplar berubah menjadi Dipinjam
        $this->eksemplar->refresh();
        $this->assertEquals('Dipinjam', $this->eksemplar->status);

        // Stok buku tersinkronisasi menjadi 0
        $this->buku->refresh();
        $this->assertEquals(0, $this->buku->stok);
    }

    public function test_petugas_dapat_melihat_halaman_barcode_berhasil_dengan_data_aktual(): void
    {
        $this->actingAs($this->petugas);

        $response = $this->post(route('peminjaman.store'), [
            'idUserMember' => $this->member->id,
            'barcodes' => [$this->eksemplar->qr_token],
        ]);

        $peminjaman = Peminjaman::latest('idPeminjaman')->first();
        $this->assertNotNull($peminjaman);

        $response->assertRedirect(route('peminjaman.show', $peminjaman->idPeminjaman));

        $viewResponse = $this->get(route('peminjaman.show', $peminjaman->idPeminjaman));

        $viewResponse->assertOk()
            ->assertSee('BOOKNEST / Petugas')
            ->assertSee('Barcode Berhasil')
            ->assertSee('Validasi selesai. Buku telah diserahkan kepada anggota.')
            ->assertSee('Peminjaman berhasil dikonfirmasi')
            ->assertSee('Laut Bercerita telah diserahkan kepada Rizky Pratama', false)
            ->assertSee('Stok tersedia kini 0 eksemplar', false)
            ->assertSee('Dipinjam')
            ->assertSee('Dina Amelia')
            ->assertSee('Waktu konfirmasi')
            ->assertSee('Rincian peminjaman')
            ->assertSee($peminjaman->kode_transaksi)
            ->assertSee('Rizky Pratama')
            ->assertSee($this->member->kode_anggota)
            ->assertSee('Laut Bercerita')
            ->assertSee('Scan Berikutnya')
            ->assertSee('Ke Dasbor')
            ->assertSee(route('peminjaman.create'), false)
            ->assertSee(route('dashboard'), false);
    }

    public function test_member_tidak_bisa_mengakses_halaman_barcode_berhasil(): void
    {
        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'idUserPetugas' => $this->petugas->id,
            'tanggalPinjam' => now()->toDateString(),
            'batasKembali' => now()->addDays(14)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        $this->actingAs($this->member);

        $response = $this->get(route('peminjaman.show', $peminjaman->idPeminjaman));

        $response->assertStatus(403);
    }

    public function test_tamu_diarahkan_ke_login_saat_mengakses_halaman_barcode_berhasil(): void
    {
        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'idUserPetugas' => $this->petugas->id,
            'tanggalPinjam' => now()->toDateString(),
            'batasKembali' => now()->addDays(14)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        $response = $this->get(route('peminjaman.show', $peminjaman->idPeminjaman));

        $response->assertRedirect(route('login'));
    }

    public function test_api_identifikasi_mengenali_tiket_booking_peminjaman(): void
    {
        $this->actingAs($this->petugas);

        $booking = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'kode_booking' => 'BK-20261007-7777',
            'qr_token' => 'book_test_token_7777',
            'opsi_pengambilan' => 'siapkan_petugas',
            'tanggalPinjam' => now()->toDateString(),
            'batasKembali' => now()->addDays(30)->toDateString(),
            'status' => 'Booking',
            'totalBuku' => 1,
            'batasAmbil' => now()->addHours(48),
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $booking->idPeminjaman,
            'idBuku' => $this->buku->idBuku,
            'idEksemplar' => $this->eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Booking',
        ]);

        $this->eksemplar->update(['status' => 'Dibooking']);

        // 1. Scan via kode booking BK-20261007-7777
        $responseCode = $this->postJson(route('api.scan.identifikasi'), [
            'code' => 'BK-20261007-7777',
        ]);

        $responseCode->assertOk()
            ->assertJson([
                'success' => true,
                'type' => 'booking',
                'data' => [
                    'idPeminjaman' => $booking->idPeminjaman,
                    'kodeBooking' => 'BK-20261007-7777',
                    'status' => 'Booking',
                    'opsiPengambilan' => 'siapkan_petugas',
                ],
            ]);

        // 2. Scan via QR token
        $responseToken = $this->postJson(route('api.scan.identifikasi'), [
            'code' => 'book_test_token_7777',
        ]);

        $responseToken->assertOk()
            ->assertJson([
                'success' => true,
                'type' => 'booking',
                'data' => [
                    'idPeminjaman' => $booking->idPeminjaman,
                    'kodeBooking' => 'BK-20261007-7777',
                ],
            ]);
    }

    public function test_halaman_scan_barcode_mendeteksi_tiket_booking_dan_opsi_serah_terima(): void
    {
        $this->actingAs($this->petugas);

        $booking = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'kode_booking' => 'BK-20261007-8888',
            'qr_token' => 'book_test_token_8888',
            'opsi_pengambilan' => 'ambil_mandiri',
            'tanggalPinjam' => now()->toDateString(),
            'batasKembali' => now()->addDays(30)->toDateString(),
            'status' => 'Booking',
            'totalBuku' => 1,
            'batasAmbil' => now()->addHours(48),
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $booking->idPeminjaman,
            'idBuku' => $this->buku->idBuku,
            'idEksemplar' => $this->eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Booking',
        ]);

        $response = $this->get(route('barcode.scan', ['kodeBarcode' => 'BK-20261007-8888']));

        $response->assertOk()
            ->assertSee('Tiket Booking Online')
            ->assertSee('BK-20261007-8888')
            ->assertSee('Laut Bercerita')
            ->assertSee('Rizky Pratama')
            ->assertSee('Ambil Mandiri dari Rak')
            ->assertSee('Serah Terima Buku')
            ->assertSee('Tandai Siap Diambil');
    }

    public function test_serah_terima_booking_mengubah_status_menjadi_dipinjam(): void
    {
        $this->actingAs($this->petugas);

        $booking = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'kode_booking' => 'BK-20261007-9999',
            'qr_token' => 'book_test_token_9999',
            'opsi_pengambilan' => 'siapkan_petugas',
            'tanggalPinjam' => now()->toDateString(),
            'batasKembali' => now()->addDays(30)->toDateString(),
            'status' => 'Booking',
            'totalBuku' => 1,
        ]);

        $detail = DetailPeminjaman::create([
            'idPeminjaman' => $booking->idPeminjaman,
            'idBuku' => $this->buku->idBuku,
            'idEksemplar' => $this->eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Booking',
        ]);

        $this->eksemplar->update(['status' => 'Dibooking']);

        $response = $this->post(route('petugas.booking.serah-terima', $booking->idPeminjaman));

        $response->assertRedirect(route('peminjaman.show', $booking->idPeminjaman));

        $booking->refresh();
        $detail->refresh();
        $this->eksemplar->refresh();

        $this->assertSame('Dipinjam', $booking->status);
        $this->assertSame($this->petugas->id, $booking->idUserPetugas);
        $this->assertSame('Dipinjam', $detail->statusBuku);
        $this->assertSame('Dipinjam', $this->eksemplar->status);
    }
}
