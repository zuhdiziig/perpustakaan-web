<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenyesuaianSirkulasiDanUniversalSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_dan_admin_dapat_mengakses_halaman_sirkulasi_buku(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas', 'status' => 'aktif']);
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);

        $buku = Buku::factory()->create(['judul' => 'Sistem Informasi Perpustakaan']);
        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'idUserPetugas' => $petugas->id,
            'tanggalPinjam' => today(),
            'batasKembali' => today()->addDays(30),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        // Petugas
        $resPetugas = $this->actingAs($petugas)->get(route('sirkulasi.index'));
        $resPetugas->assertOk();
        $resPetugas->assertSee('Sirkulasi Buku');
        $resPetugas->assertSee('Transaksi Peminjaman');
        $resPetugas->assertSee('Sistem Informasi Perpustakaan');
        $resPetugas->assertSee('#TRX-'.str_pad($peminjaman->idPeminjaman, 5, '0', STR_PAD_LEFT));

        // Admin
        $resAdmin = $this->actingAs($admin)->get(route('sirkulasi.index'));
        $resAdmin->assertOk();
        $resAdmin->assertSee('Sirkulasi Buku');
    }

    public function test_tombol_jelajahi_katalog_di_beranda_tersembunyi_untuk_admin_dan_petugas(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $petugas = User::factory()->create(['role' => 'petugas', 'status' => 'aktif']);
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);

        // Tamu (guest) melihat Jelajahi Katalog
        $this->get('/')->assertSee('Jelajahi Katalog');

        // Member melihat Jelajahi Katalog
        $this->actingAs($member)->get('/')->assertSee('Jelajahi Katalog');

        // Admin TIDAK melihat Jelajahi Katalog
        $this->actingAs($admin)->get('/')->assertDontSee('Jelajahi Katalog');

        // Petugas TIDAK melihat Jelajahi Katalog & Meja Sirkulasi menuju scan barcode
        $resPetugas = $this->actingAs($petugas)->get('/');
        $resPetugas->assertDontSee('Jelajahi Katalog');
        $resPetugas->assertSee(route('barcode.scan'));
    }

    public function test_universal_search_mampu_mendeteksi_kode_transaksi_trx_ret_dan_denda(): void
    {
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);
        $petugas = User::factory()->create(['role' => 'petugas', 'status' => 'aktif']);
        $buku = Buku::factory()->create(['judul' => 'Filosofi Teras']);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'idUserPetugas' => $petugas->id,
            'tanggalPinjam' => today(),
            'batasKembali' => today()->addDays(30),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        $trxCode = '#TRX-'.str_pad($peminjaman->idPeminjaman, 5, '0', STR_PAD_LEFT);

        $res = $this->actingAs($member)->getJson(route('member.universal-search', [
            'q' => $trxCode,
            'tab' => 'semua',
        ]));

        $res->assertOk();
        $data = $res->json();
        $this->assertNotEmpty($data['peminjaman']);
        $this->assertEquals($peminjaman->idPeminjaman, $data['peminjaman'][0]['id']);
    }

    public function test_laporan_peminjaman_menampilkan_jam_realtime(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);
        $buku = Buku::factory()->create(['judul' => 'Bumi Manusia']);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => today()->toDateString(),
            'batasKembali' => today()->addDays(30)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        $this->assertNotNull($peminjaman->tanggal_pinjam_realtime);
        $this->assertNotNull($peminjaman->batas_kembali_realtime);
        $this->assertMatchesRegularExpression('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $peminjaman->tanggal_pinjam_realtime);
        $this->assertMatchesRegularExpression('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $peminjaman->batas_kembali_realtime);

        $res = $this->actingAs($admin)->get(route('laporan.index', [
            'jenis' => 'peminjaman',
            'tgl_mulai' => today()->subDays(5)->toDateString(),
            'tgl_selesai' => today()->addDays(5)->toDateString(),
        ]));
        $res->assertOk();
        $res->assertSee($peminjaman->tanggal_pinjam_realtime);
        $res->assertSee($peminjaman->batas_kembali_realtime);
    }
}
