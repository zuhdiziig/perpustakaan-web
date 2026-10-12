<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\BukuEksemplar;
use App\Models\Denda;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetugasBarcodePengembalianTest extends TestCase
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
            'name' => 'Petugas Sirkulasi',
            'email' => 'sirkulasi@perpus.test',
            'password' => bcrypt('password123'),
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $this->member = User::create([
            'name' => 'Frezia Allifia',
            'email' => 'frezia@perpus.test',
            'password' => bcrypt('password123'),
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $this->kategori = Kategori::create([
            'namaKategori' => 'Agama',
            'deskripsi' => 'Buku-buku keagamaan',
        ]);

        $this->buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Kisah Para Nabi',
            'penulis' => 'Ibnu Katsir',
            'penerbit' => 'Ummul Qura',
            'tahunTerbit' => 2026,
            'harga' => 85000,
            'stok' => 1,
            'kondisi' => 'Baik',
        ]);

        $this->eksemplar = $this->buku->eksemplar()->first();
        $this->eksemplar->update(['status' => 'Dipinjam']);
    }

    public function test_guest_is_redirected_when_accessing_pengembalian_create(): void
    {
        $response = $this->get(route('pengembalian.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_member_cannot_access_petugas_pengembalian_create(): void
    {
        $response = $this->actingAs($this->member)->get(route('pengembalian.create'));

        $response->assertForbidden();
    }

    public function test_petugas_can_view_barcode_pengembalian_page(): void
    {
        $response = $this->actingAs($this->petugas)->get(route('pengembalian.create'));

        $response->assertOk();
        $response->assertViewIs('pengembalian.create');
        $response->assertSee('Barcode Pengembalian');
        $response->assertSee('Scan barcode');
        $response->assertSee('Rincian pengembalian');
        $response->assertSee('Verifikasi Kondisi');
        $response->assertSee('Konfirmasi Pengembalian');
    }

    public function test_petugas_can_load_return_details_via_ticket_code(): void
    {
        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'idUserPetugas' => $this->petugas->id,
            'tanggalPinjam' => Carbon::now()->subDays(5)->toDateString(),
            'batasKembali' => Carbon::now()->addDays(9)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $this->buku->idBuku,
            'idEksemplar' => $this->eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Diajukan Kembali',
            'kode_kembali' => 'KB-20261007-0088',
            'qr_kembali' => 'ret_secrettoken88',
            'kondisi_laporan' => 'Baik',
            'waktu_pengajuan_kembali' => Carbon::now(),
        ]);

        $response = $this->actingAs($this->petugas)->get(route('pengembalian.create', ['code' => 'KB-20261007-0088']));

        $response->assertOk();
        $response->assertSee('KB-20261007-0088');
        $response->assertSee('Kisah Para Nabi');
        $response->assertSee('Frezia Allifia');
        $response->assertSee('Tepat Waktu (Bebas Denda Keterlambatan)');
    }

    public function test_petugas_can_confirm_return_and_finalize_loan(): void
    {
        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'idUserPetugas' => $this->petugas->id,
            'tanggalPinjam' => Carbon::now()->subDays(4)->toDateString(),
            'batasKembali' => Carbon::now()->addDays(10)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        $detail = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $this->buku->idBuku,
            'idEksemplar' => $this->eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Diajukan Kembali',
            'kode_kembali' => 'KB-20261007-0089',
            'qr_kembali' => 'ret_secrettoken89',
            'kondisi_laporan' => 'Baik',
            'waktu_pengajuan_kembali' => Carbon::now(),
        ]);

        $response = $this->actingAs($this->petugas)->post(route('pengembalian.store'), [
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $this->buku->idBuku,
            'idEksemplar' => $this->eksemplar->idEksemplar,
            'idUserMember' => $this->member->id,
            'kondisiBuku' => 'Baik',
        ]);

        $pengembalian = Pengembalian::where('idPeminjaman', $peminjaman->idPeminjaman)->first();
        $this->assertNotNull($pengembalian);
        $this->assertEquals('Baik', $pengembalian->kondisiBuku);

        $response->assertRedirect(route('pengembalian.show', $pengembalian->idPengembalian));

        $detail->refresh();
        $this->assertEquals('Kembali', $detail->statusBuku);

        $this->eksemplar->refresh();
        $this->assertEquals('Tersedia', $this->eksemplar->status);

        $peminjaman->refresh();
        $this->assertEquals('Selesai', $peminjaman->status);
    }

    public function test_petugas_can_confirm_return_with_damaged_condition_and_generates_fine(): void
    {
        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'idUserPetugas' => $this->petugas->id,
            'tanggalPinjam' => Carbon::now()->subDays(20)->toDateString(),
            'batasKembali' => Carbon::now()->subDays(5)->toDateString(), // Terlambat 5 hari
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        $detail = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $this->buku->idBuku,
            'idEksemplar' => $this->eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Diajukan Kembali',
            'kode_kembali' => 'KB-20261007-0090',
            'qr_kembali' => 'ret_secrettoken90',
            'kondisi_laporan' => 'Rusak',
            'waktu_pengajuan_kembali' => Carbon::now(),
        ]);

        $response = $this->actingAs($this->petugas)->post(route('pengembalian.store'), [
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $this->buku->idBuku,
            'idEksemplar' => $this->eksemplar->idEksemplar,
            'idUserMember' => $this->member->id,
            'kondisiBuku' => 'Rusak',
        ]);

        $pengembalian = Pengembalian::where('idPeminjaman', $peminjaman->idPeminjaman)->first();
        $this->assertNotNull($pengembalian);
        $this->assertEquals('Rusak', $pengembalian->kondisiBuku);

        // Terlambat 1 minggu (10% x 85.000 = 8.500) + Rusak (100% x 85.000 = 85.000) = 93.500
        $denda = Denda::where('idPengembalian', $pengembalian->idPengembalian)->first();
        $this->assertNotNull($denda);
        $this->assertEquals(93500, $denda->jumlah);
        $this->assertEquals('Belum Dibayar', $denda->status);
    }

    public function test_petugas_scan_member_qr_in_pengembalian_context_loads_borrowed_books(): void
    {
        $this->actingAs($this->petugas);

        $this->member->update(['qr_token' => 'usr_member_ret_test_456']);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'idUserPetugas' => $this->petugas->id,
            'tanggalPinjam' => Carbon::now()->subDays(5)->toDateString(),
            'batasKembali' => Carbon::now()->addDays(25)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $this->buku->idBuku,
            'idEksemplar' => $this->eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        // Petugas scan QR Anggota di menu pengembalian
        $response = $this->postJson(route('api.scan.identifikasi'), [
            'code' => 'usr_member_ret_test_456',
            'context' => 'pengembalian',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('type', 'pengembalian_batch')
            ->assertJsonPath('data.totalBuku', 1)
            ->assertJsonPath('data.member.name', $this->member->name)
            ->assertJsonPath('data.daftarBuku.0.judul', $this->buku->judul);
    }

    public function test_petugas_dapat_menyelesaikan_pengembalian_langsung_satu_buku_di_meja_sirkulasi(): void
    {
        $this->actingAs($this->petugas);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'idUserPetugas' => $this->petugas->id,
            'tanggalPinjam' => Carbon::now()->subDays(3)->toDateString(),
            'batasKembali' => Carbon::now()->addDays(11)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        $detail = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $this->buku->idBuku,
            'idEksemplar' => $this->eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        $response = $this->post(route('sirkulasi.pengembalian-langsung'), [
            'idUserMember' => $this->member->id,
            'detail_ids' => [$detail->id],
            'kondisi' => [
                $detail->id => 'Baik',
            ],
        ]);

        $response->assertRedirect(route('barcode.scan', ['member_id' => $this->member->id, 'tab' => 'kembali']));
        $response->assertSessionHas('success');

        $detail->refresh();
        $this->assertSame('Kembali', $detail->statusBuku);

        $this->eksemplar->refresh();
        $this->assertSame('Tersedia', $this->eksemplar->status);
        $this->assertSame('Baik', $this->eksemplar->kondisi);

        $peminjaman->refresh();
        $this->assertSame('Selesai', $peminjaman->status);

        $this->assertDatabaseHas('pengembalian', [
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idUserPetugas' => $this->petugas->id,
            'kondisiBuku' => 'Baik',
        ]);
    }

    public function test_petugas_dapat_menyelesaikan_pengembalian_langsung_banyak_buku_sekaligus(): void
    {
        $this->actingAs($this->petugas);

        $buku2 = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Fiqih Sunnah',
            'penulis' => 'Sayyid Sabiq',
            'penerbit' => 'Pena Pundi',
            'tahunTerbit' => 2026,
            'harga' => 120000,
            'stok' => 1,
            'kondisi' => 'Baik',
        ]);
        $eksemplar2 = $buku2->eksemplar()->first();
        $eksemplar2->update(['status' => 'Dipinjam']);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'idUserPetugas' => $this->petugas->id,
            'tanggalPinjam' => Carbon::now()->subDays(2)->toDateString(),
            'batasKembali' => Carbon::now()->addDays(12)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 2,
        ]);

        $detail1 = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $this->buku->idBuku,
            'idEksemplar' => $this->eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        $detail2 = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku2->idBuku,
            'idEksemplar' => $eksemplar2->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        $response = $this->post(route('sirkulasi.pengembalian-langsung'), [
            'idUserMember' => $this->member->id,
            'detail_ids' => [$detail1->id, $detail2->id],
            'kondisi' => [
                $detail1->id => 'Baik',
                $detail2->id => 'Baik',
            ],
        ]);

        $response->assertRedirect(route('barcode.scan', ['member_id' => $this->member->id, 'tab' => 'kembali']));
        $response->assertSessionHas('success');

        $detail1->refresh();
        $detail2->refresh();
        $this->assertSame('Kembali', $detail1->statusBuku);
        $this->assertSame('Kembali', $detail2->statusBuku);

        $this->eksemplar->refresh();
        $eksemplar2->refresh();
        $this->assertSame('Tersedia', $this->eksemplar->status);
        $this->assertSame('Tersedia', $eksemplar2->status);

        $peminjaman->refresh();
        $this->assertSame('Selesai', $peminjaman->status);
    }

    public function test_pengembalian_langsung_menghitung_denda_jika_buku_rusak_atau_terlambat(): void
    {
        $this->actingAs($this->petugas);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'idUserPetugas' => $this->petugas->id,
            'tanggalPinjam' => Carbon::now()->subDays(25)->toDateString(),
            'batasKembali' => Carbon::now()->subDays(10)->toDateString(), // Terlambat 10 hari (2 minggu)
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        $detail = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $this->buku->idBuku,
            'idEksemplar' => $this->eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        $response = $this->post(route('sirkulasi.pengembalian-langsung'), [
            'idUserMember' => $this->member->id,
            'detail_ids' => [$detail->id],
            'kondisi' => [
                $detail->id => 'Rusak',
            ],
        ]);

        $response->assertRedirect(route('barcode.scan', ['member_id' => $this->member->id, 'tab' => 'kembali']));
        $response->assertSessionHas('success');

        $detail->refresh();
        $this->assertSame('Kembali', $detail->statusBuku);
        $this->assertNotNull($detail->id_denda);

        // Harga buku: 85.000
        // Terlambat 10 hari = 2 minggu terlambat = 20% x 85.000 = 17.000
        // Rusak = 100% x 85.000 = 85.000
        // Total = 102.000
        $denda = Denda::find($detail->id_denda);
        $this->assertNotNull($denda);
        $this->assertEquals(102000, $denda->jumlah);
        $this->assertSame('Belum Dibayar', $denda->status);
        $this->assertStringContainsString('Terlambat 2 Minggu (20%)', $denda->jenisDenda);
        $this->assertStringContainsString('Kerusakan (100% Harga Buku)', $denda->jenisDenda);

        $this->eksemplar->refresh();
        $this->assertSame('Tersedia', $this->eksemplar->status);
        $this->assertSame('Rusak', $this->eksemplar->kondisi);
    }
}
