<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Denda;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Pembayaran;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebDendaTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_denda_page(): void
    {
        $response = $this->get(route('denda.saya'));

        $response->assertRedirect(route('login'));
    }

    public function test_member_can_view_denda_page_with_unpaid_fine(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
            'name' => 'Rizky Pratama',
        ]);

        $kategori = Kategori::create([
            'namaKategori' => 'Umum',
            'deskripsi' => 'Pengembangan diri',
        ]);

        $buku = Buku::create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Filosofi Teras',
            'penulis' => 'Henry Manampiring',
            'penerbit' => 'Kompas',
            'tahunTerbit' => 2019,
            'harga' => 98000,
            'stok' => 5,
            'kondisi' => 'Baik',
        ]);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => '2026-09-14',
            'batasKembali' => '2026-09-28',
            'status' => 'Kembali',
            'totalBuku' => 1,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $buku->eksemplar->first()->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Kembali',
        ]);

        $pengembalian = Pengembalian::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idUserPetugas' => $member->id,
            'tanggalKembali' => '2026-10-03',
            'kondisiBuku' => 'Baik',
        ]);

        $denda = Denda::create([
            'idPengembalian' => $pengembalian->idPengembalian,
            'jenisDenda' => 'Keterlambatan',
            'jumlah' => 5000,
            'status' => 'Belum Dibayar',
        ]);

        $response = $this->actingAs($member)->get(route('denda.saya'));

        $response->assertOk();
        $response->assertViewIs('denda.member_index');
        $response->assertSee('Denda Peminjaman');
        $response->assertSee('Rincian keterlambatan dan pembayaran, transparan dalam satu tempat.');
        $response->assertSee('Total belum dibayar');
        $response->assertSee('Rp5.000');
        $response->assertSee('Keterlambatan');
        $response->assertSee('5 hari');
        $response->assertSee('Tarif per hari');
        $response->assertSee('Rp1.000');
        $response->assertSee('Filosofi Teras');
        $response->assertSee('Belum Lunas');
        $response->assertSee('Rincian keterlambatan');
        $response->assertSee('Total pembayaran');
        $response->assertSee('Bayar Denda');
    }

    public function test_member_can_view_denda_page_when_no_fines_exist(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
            'name' => 'Member Bersih',
        ]);

        $response = $this->actingAs($member)->get(route('denda.saya'));

        $response->assertOk();
        $response->assertViewIs('denda.member_index');
        $response->assertSee('Denda Peminjaman');
        $response->assertSee('Rp0');
        $response->assertSee('Tidak ada tunggakan');
        $response->assertSee('0 hari');
        $response->assertSee('Rp1.000');
    }

    public function test_member_can_view_qris_pending_page(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
            'name' => 'Rizky Pratama',
        ]);

        $kategori = Kategori::create([
            'namaKategori' => 'Umum',
            'deskripsi' => 'Pengembangan diri',
        ]);

        $buku = Buku::create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Filosofi Teras',
            'penulis' => 'Henry Manampiring',
            'penerbit' => 'Kompas',
            'tahunTerbit' => 2019,
            'harga' => 98000,
            'stok' => 5,
            'kondisi' => 'Baik',
        ]);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => '2026-09-14',
            'batasKembali' => '2026-09-28',
            'status' => 'Kembali',
            'totalBuku' => 1,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $buku->eksemplar->first()->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Kembali',
        ]);

        $pengembalian = Pengembalian::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idUserPetugas' => $member->id,
            'tanggalKembali' => '2026-10-03',
            'kondisiBuku' => 'Baik',
        ]);

        $denda = Denda::create([
            'idPengembalian' => $pengembalian->idPengembalian,
            'jenisDenda' => 'Keterlambatan',
            'jumlah' => 5000,
            'status' => 'Belum Dibayar',
        ]);

        $response = $this->actingAs($member)->get(route('bayar.qr', $denda->idDenda));

        $response->assertOk();
        $response->assertViewIs('pembayaran.bayar_qr');
        $response->assertSee('Pembayaran QRIS');
        $response->assertSee('Menunggu Pembayaran');
        $response->assertSee('Cek Status Pembayaran');
        $response->assertSee('Rincian pembayaran');
        $response->assertSee('Cara membayar');
        $response->assertSee('Kembali ke Denda');
    }

    public function test_member_can_process_qris_payment_and_view_success_and_nota(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
            'name' => 'Rizky Pratama',
        ]);

        $kategori = Kategori::create([
            'namaKategori' => 'Umum',
            'deskripsi' => 'Pengembangan diri',
        ]);

        $buku = Buku::create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Filosofi Teras',
            'penulis' => 'Henry Manampiring',
            'penerbit' => 'Kompas',
            'tahunTerbit' => 2019,
            'harga' => 98000,
            'stok' => 5,
            'kondisi' => 'Baik',
        ]);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => '2026-09-14',
            'batasKembali' => '2026-09-28',
            'status' => 'Kembali',
            'totalBuku' => 1,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $buku->eksemplar->first()->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Kembali',
        ]);

        $pengembalian = Pengembalian::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idUserPetugas' => $member->id,
            'tanggalKembali' => '2026-10-03',
            'kondisiBuku' => 'Baik',
        ]);

        $denda = Denda::create([
            'idPengembalian' => $pengembalian->idPengembalian,
            'jenisDenda' => 'Keterlambatan',
            'jumlah' => 5000,
            'status' => 'Belum Dibayar',
        ]);

        // 1. Visit QRIS page to create pending payment
        $this->actingAs($member)->get(route('bayar.qr', $denda->idDenda));
        $pembayaran = Pembayaran::where('idDenda', $denda->idDenda)->first();
        $this->assertNotNull($pembayaran);

        // 2. Process QRIS simulation
        $processResponse = $this->actingAs($member)->post(route('bayar.proses_qr', $pembayaran->idPembayaran), [
            'simulasi_status' => 'berhasil',
        ]);
        $processResponse->assertRedirect(route('bayar.sukses', $pembayaran->idPembayaran));

        $pembayaran->refresh();
        $denda->refresh();
        $this->assertEquals('Sukses', $pembayaran->status);
        $this->assertEquals('Lunas', $denda->status);

        // 3. View Success Page (Screen 19)
        $suksesResponse = $this->actingAs($member)->get(route('bayar.sukses', $pembayaran->idPembayaran));
        $suksesResponse->assertOk();
        $suksesResponse->assertViewIs('pembayaran.sukses');
        $suksesResponse->assertSee('Pembayaran Berhasil');
        $suksesResponse->assertSee('Terima kasih, Rizky!');
        $suksesResponse->assertSee('LUNAS');
        $suksesResponse->assertSee('Lihat Nota');
        $suksesResponse->assertSee('Kembali ke Dasbor');

        // 4. View Official Nota Page (Screen 20)
        $notaResponse = $this->actingAs($member)->get(route('pembayaran.nota', $pembayaran->idPembayaran));
        $notaResponse->assertOk();
        $notaResponse->assertViewIs('pembayaran.nota');
        $notaResponse->assertSee('Nota Pembayaran');
        $notaResponse->assertSee('Cetak Nota');
        $notaResponse->assertSee('Kembali ke Dasbor');
        $notaResponse->assertSee('Nota Denda Peminjaman');
        $notaResponse->assertSee('TOTAL DIBAYAR');
        $notaResponse->assertSee('SISA TAGIHAN');
        $notaResponse->assertSee('Rp5.000');
    }
}
