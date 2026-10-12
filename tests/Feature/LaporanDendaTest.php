<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Denda;
use App\Models\Kategori;
use App\Models\Pembayaran;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanDendaTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $member;

    private User $petugas;

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
            'name' => 'Budi Pembaca',
            'role' => 'member',
            'status' => 'aktif',
        ]);
    }

    public function test_admin_dapat_mengakses_laporan_denda_dan_keuangan_tanpa_error(): void
    {
        $kategori = Kategori::create([
            'namaKategori' => 'Teknologi',
            'deskripsi' => 'Buku Komputer',
        ]);

        $buku = Buku::create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Sistem Basis Data',
            'penulis' => 'Silberschatz',
            'penerbit' => 'McGraw-Hill',
            'tahunTerbit' => 2024,
            'harga' => 150000,
            'stok' => 5,
            'kondisi' => 'Baik',
        ]);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'idUserPetugas' => $this->petugas->id,
            'tanggalPinjam' => Carbon::now()->subDays(10)->toDateString(),
            'batasKembali' => Carbon::now()->subDays(3)->toDateString(),
            'status' => 'Selesai',
            'totalBuku' => 1,
        ]);

        $pengembalian = Pengembalian::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idUserPetugas' => $this->petugas->id,
            'tanggalKembali' => Carbon::now()->toDateString(),
            'kondisiBuku' => 'Baik',
        ]);

        $denda = Denda::create([
            'idPengembalian' => $pengembalian->idPengembalian,
            'jumlah' => 15000,
            'jenisDenda' => 'Keterlambatan 3 Hari',
            'status' => 'Lunas',
        ]);

        Pembayaran::create([
            'idDenda' => $denda->idDenda,
            'tanggalBayar' => Carbon::now(),
            'metode' => 'QRIS',
            'nominal' => 15000,
            'status' => 'Sukses',
        ]);

        // Denda kedua: belum dibayar
        $dendaBelum = Denda::create([
            'idPengembalian' => $pengembalian->idPengembalian,
            'jumlah' => 10000,
            'jenisDenda' => 'Keterlambatan 2 Hari',
            'status' => 'Belum Dibayar',
        ]);

        $response = $this->actingAs($this->admin)->get(route('laporan.index', [
            'jenis' => 'denda',
            'tgl_mulai' => Carbon::now()->startOfMonth()->toDateString(),
            'tgl_selesai' => Carbon::now()->toDateString(),
        ]));

        $response->assertOk();
        $response->assertSee('Laporan Tagihan Denda & Arus Pembayaran', false);
        $response->assertSee('#DND-'.str_pad($denda->idDenda, 5, '0', STR_PAD_LEFT));
        $response->assertSee('QRIS');
        $response->assertSee('Lunas');
        $response->assertSee('#DND-'.str_pad($dendaBelum->idDenda, 5, '0', STR_PAD_LEFT));
        $response->assertSee('Belum Dibayar');
    }
}
