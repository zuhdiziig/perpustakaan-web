<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Denda;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DendaMonitoringTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $petugas;

    private User $member;

    private Denda $dendaAktif;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $this->petugas = User::factory()->create([
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $this->member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
            'name' => 'Fajar Nugroho',
            'email' => 'fajar@example.com',
        ]);

        $kategori = Kategori::create([
            'namaKategori' => 'Sastra',
            'deskripsi' => 'Novel & Sastra',
        ]);

        $buku = Buku::factory()->create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Laskar Pelangi',
            'harga' => 75000,
        ]);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'idUserPetugas' => $this->petugas->id,
            'tanggalPinjam' => Carbon::now()->subDays(14)->toDateString(),
            'batasKembali' => Carbon::now()->subDays(7)->toDateString(),
            'status' => 'Selesai',
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'statusBuku' => 'Kembali',
        ]);

        $pengembalian = Pengembalian::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idUserPetugas' => $this->petugas->id,
            'tanggalKembali' => Carbon::now()->toDateString(),
            'kondisiBuku' => 'Baik',
        ]);

        $this->dendaAktif = Denda::create([
            'idPengembalian' => $pengembalian->idPengembalian,
            'jenisDenda' => 'Terlambat 1 Minggu (10%)',
            'jumlah' => 7500,
            'status' => 'Belum Dibayar',
        ]);
    }

    public function test_petugas_can_view_denda_monitoring_without_manual_calculation_table(): void
    {
        $response = $this->actingAs($this->petugas)->get(route('denda.index'));

        $response->assertOk();
        $response->assertSee('Kelola Tagihan & Kas Denda');
        $response->assertSee('Total Tunggakan Belum Bayar');
        $response->assertSee('Total Kas Denda Masuk');
        $response->assertSee('Fajar Nugroho');
        $response->assertSee('Laskar Pelangi');
        $response->assertSee('Generate QRIS');
        $response->assertDontSee('Terima Tunai');
        // Tidak ada lagi tabel penetapan denda manual
        $response->assertDontSee('Pengembalian Membutuhkan Penetapan Denda');
        $response->assertDontSee('Hitung & Konfirmasi Denda');
    }

    public function test_zero_amount_dummy_denda_is_excluded(): void
    {
        // Buat dummy denda Rp 0
        Denda::create([
            'idPengembalian' => $this->dendaAktif->idPengembalian,
            'jenisDenda' => 'Nol Denda',
            'jumlah' => 0,
            'status' => 'Belum Dibayar',
        ]);

        $response = $this->actingAs($this->petugas)->get(route('denda.index'));

        $response->assertOk();
        $response->assertDontSee('Nol Denda');
    }

    public function test_petugas_can_record_cash_payment_at_circulation_desk(): void
    {
        $this->actingAs($this->petugas);

        $response = $this->post(route('denda.bayar-tunai', $this->dendaAktif->idDenda));

        $response->assertRedirect(route('denda.index'));
        $response->assertSessionHas('success');

        // Status denda berubah menjadi Lunas
        $this->assertDatabaseHas('denda', [
            'idDenda' => $this->dendaAktif->idDenda,
            'status' => 'Lunas',
        ]);

        // Record pembayaran Tunai tercatat Sukses
        $this->assertDatabaseHas('pembayaran', [
            'idDenda' => $this->dendaAktif->idDenda,
            'nominal' => 7500,
            'metode' => 'Tunai',
            'status' => 'Sukses',
        ]);
    }

    public function test_member_is_forbidden_from_accessing_denda_monitoring_and_cash_payment(): void
    {
        $this->actingAs($this->member);

        $this->get(route('denda.index'))->assertForbidden();
        $this->post(route('denda.bayar-tunai', $this->dendaAktif->idDenda))->assertForbidden();
    }
}
