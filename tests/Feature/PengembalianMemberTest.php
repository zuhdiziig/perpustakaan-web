<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengembalianMemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_member_pengembalian(): void
    {
        $response = $this->get(route('pengembalian.member'));

        $response->assertRedirect(route('login'));
    }

    public function test_member_can_view_pengembalian_page(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'name' => 'Budi Santoso',
            'status' => 'aktif',
        ]);

        $kategori = Kategori::factory()->create();
        $buku = Buku::factory()->create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Pemrograman Web Modern',
            'stok' => 2,
        ]);

        $eksemplar = $buku->eksemplar()->first();
        $eksemplar->update(['status' => 'Dipinjam']);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'idUserPetugas' => null,
            'tanggalPinjam' => Carbon::now()->subDays(5)->toDateString(),
            'batasKembali' => Carbon::now()->addDays(9)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        $response = $this->actingAs($member)->get(route('pengembalian.member'));

        $response->assertOk();
        $response->assertViewIs('pengembalian.member');
        $response->assertSee('Pengembalian Buku');
        $response->assertSee('Pemrograman Web Modern');
        $response->assertSee('Daftar Buku Sedang Dipinjam yang Perlu Dikembalikan');
    }

    public function test_member_can_submit_return_and_is_redirected_to_return_ticket(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $kategori = Kategori::factory()->create();
        $buku = Buku::factory()->create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Struktur Data Dasar',
            'harga' => 50000,
            'stok' => 1,
        ]);

        $eksemplar = $buku->eksemplar()->first();
        $eksemplar->update(['status' => 'Dipinjam']);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'idUserPetugas' => $petugas->id,
            'tanggalPinjam' => Carbon::now()->subDays(3)->toDateString(),
            'batasKembali' => Carbon::now()->addDays(11)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        $detail = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        $response = $this->actingAs($member)->post(route('pengembalian.member.store', $detail->id), [
            'kondisiBuku' => 'Baik',
            'konfirmasi' => 'on',
        ]);

        // Pengembalian resmi belum dibuat karena belum discan oleh petugas
        $pengembalian = Pengembalian::where('idPeminjaman', $peminjaman->idPeminjaman)->first();
        $this->assertNull($pengembalian);

        // Harus diarahkan ke halaman tiket QR Pengembalian
        $response->assertRedirect(route('pengembalian.member.tiket', $detail->id));

        $detail->refresh();
        $this->assertEquals('Diajukan Kembali', $detail->statusBuku);
        $this->assertNotNull($detail->kode_kembali);
        $this->assertStringStartsWith('KB-', $detail->kode_kembali);
        $this->assertNotNull($detail->qr_kembali);
        $this->assertStringStartsWith('ret_', $detail->qr_kembali);
        $this->assertEquals('Baik', $detail->kondisi_laporan);

        // Eksemplar dan transaksi masih Dipinjam sampai disahkan oleh petugas
        $eksemplar->refresh();
        $this->assertEquals('Dipinjam', $eksemplar->status);

        $peminjaman->refresh();
        $this->assertEquals('Dipinjam', $peminjaman->status);
    }

    public function test_member_can_view_return_ticket(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);
        $member = User::factory()->create([
            'role' => 'member',
            'name' => 'Frezia Allifia',
            'status' => 'aktif',
        ]);

        $kategori = Kategori::factory()->create();
        $buku = Buku::factory()->create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Kisah Para Nabi',
            'stok' => 1,
        ]);

        $eksemplar = $buku->eksemplar()->first();

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'idUserPetugas' => $petugas->id,
            'tanggalPinjam' => Carbon::now()->subDays(2)->toDateString(),
            'batasKembali' => Carbon::now()->addDays(12)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        $detail = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Diajukan Kembali',
            'kode_kembali' => 'KB-20261007-0001',
            'qr_kembali' => 'ret_sampletoken12345678',
            'kondisi_laporan' => 'Baik',
            'waktu_pengajuan_kembali' => Carbon::now(),
        ]);

        $response = $this->actingAs($member)->get(route('pengembalian.member.tiket', $detail->id));

        $response->assertOk();
        $response->assertViewIs('pengembalian.member_tiket');
        $response->assertSee('Tiket Pengembalian Buku Fisik');
        $response->assertSee('KB-20261007-0001');
        $response->assertSee('Kisah Para Nabi');
        $response->assertSee('Frezia Allifia');
        $response->assertSee('Menunggu Scan Petugas');
    }

    public function test_member_cannot_view_other_members_return_ticket(): void
    {
        $memberA = User::factory()->create(['role' => 'member']);
        $memberB = User::factory()->create(['role' => 'member']);
        $petugas = User::factory()->create(['role' => 'petugas']);

        $kategori = Kategori::factory()->create();
        $buku = Buku::factory()->create(['idKategori' => $kategori->idKategori]);
        $eksemplar = $buku->eksemplar()->first();

        $peminjaman = Peminjaman::create([
            'idUserMember' => $memberA->id,
            'idUserPetugas' => $petugas->id,
            'tanggalPinjam' => Carbon::now()->subDays(2)->toDateString(),
            'batasKembali' => Carbon::now()->addDays(12)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        $detail = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Diajukan Kembali',
            'kode_kembali' => 'KB-20261007-0002',
            'qr_kembali' => 'ret_othertoken12345678',
        ]);

        $response = $this->actingAs($memberB)->get(route('pengembalian.member.tiket', $detail->id));

        $response->assertForbidden();
    }

    public function test_member_can_cancel_return_request(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $kategori = Kategori::factory()->create();
        $buku = Buku::factory()->create(['idKategori' => $kategori->idKategori]);
        $eksemplar = $buku->eksemplar()->first();

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => Carbon::now()->subDays(2)->toDateString(),
            'batasKembali' => Carbon::now()->addDays(12)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        $detail = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Diajukan Kembali',
            'kode_kembali' => 'KB-20261007-0003',
            'qr_kembali' => 'ret_canceltoken12345678',
        ]);

        $response = $this->actingAs($member)->post(route('pengembalian.member.batal', $detail->id));

        $response->assertRedirect(route('pengembalian.member'));

        $detail->refresh();
        $this->assertEquals('Dipinjam', $detail->statusBuku);
        $this->assertNull($detail->kode_kembali);
        $this->assertNull($detail->qr_kembali);
    }

    public function test_member_can_view_official_return_receipt(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);
        $member = User::factory()->create(['role' => 'member']);

        $kategori = Kategori::factory()->create();
        $buku = Buku::factory()->create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Algoritma Pemrograman',
            'stok' => 1,
        ]);

        $eksemplar = $buku->eksemplar()->first();

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'idUserPetugas' => $petugas->id,
            'tanggalPinjam' => Carbon::now()->subDays(10)->toDateString(),
            'batasKembali' => Carbon::now()->addDays(4)->toDateString(),
            'status' => 'Selesai',
            'totalBuku' => 1,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Kembali',
        ]);

        $pengembalian = Pengembalian::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idUserPetugas' => $petugas->id,
            'tanggalKembali' => Carbon::now()->toDateString(),
            'kondisiBuku' => 'Baik',
        ]);

        $response = $this->actingAs($member)->get(route('pengembalian.member.bukti', $pengembalian->idPengembalian));

        $response->assertOk();
        $response->assertViewIs('pengembalian.member_bukti');
        $response->assertSee('BUKTI PENGEMBALIAN BUKU');
        $response->assertSee('Algoritma Pemrograman');
        $response->assertSee($member->name);
    }

    public function test_member_cannot_view_other_members_return_receipt(): void
    {
        $memberA = User::factory()->create(['role' => 'member']);
        $memberB = User::factory()->create(['role' => 'member']);
        $petugas = User::factory()->create(['role' => 'petugas']);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $memberA->id,
            'idUserPetugas' => $petugas->id,
            'tanggalPinjam' => Carbon::now()->subDays(5)->toDateString(),
            'batasKembali' => Carbon::now()->addDays(9)->toDateString(),
            'status' => 'Selesai',
            'totalBuku' => 1,
        ]);

        $pengembalian = Pengembalian::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idUserPetugas' => $petugas->id,
            'tanggalKembali' => Carbon::now()->toDateString(),
            'kondisiBuku' => 'Baik',
        ]);

        $response = $this->actingAs($memberB)->get(route('pengembalian.member.bukti', $pengembalian->idPengembalian));

        $response->assertForbidden();
    }

    public function test_member_can_submit_batch_return_with_individual_book_conditions(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
        ]);
        $petugas = User::factory()->create(['role' => 'petugas']);
        $kategori = Kategori::factory()->create();

        $buku1 = Buku::factory()->create(['idKategori' => $kategori->idKategori, 'judul' => 'Buku Clean Code', 'harga' => 80000]);
        $buku2 = Buku::factory()->create(['idKategori' => $kategori->idKategori, 'judul' => 'Buku Refactoring', 'harga' => 95000]);
        $buku3 = Buku::factory()->create(['idKategori' => $kategori->idKategori, 'judul' => 'Buku Design Patterns', 'harga' => 120000]);

        $eksemplar1 = $buku1->eksemplar()->first();
        $eksemplar2 = $buku2->eksemplar()->first();
        $eksemplar3 = $buku3->eksemplar()->first();

        $eksemplar1->update(['status' => 'Dipinjam']);
        $eksemplar2->update(['status' => 'Dipinjam']);
        $eksemplar3->update(['status' => 'Dipinjam']);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'idUserPetugas' => $petugas->id,
            'tanggalPinjam' => Carbon::now()->subDays(4)->toDateString(),
            'batasKembali' => Carbon::now()->addDays(10)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 3,
        ]);

        $detail1 = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku1->idBuku,
            'idEksemplar' => $eksemplar1->idEksemplar,
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

        $detail3 = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku3->idBuku,
            'idEksemplar' => $eksemplar3->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        $response = $this->actingAs($member)->post(route('pengembalian.member.batch-store'), [
            'detail_ids' => [$detail1->id, $detail2->id, $detail3->id],
            'kondisi' => [
                $detail1->id => 'Baik',
                $detail2->id => 'Rusak',
                $detail3->id => 'Hilang',
            ],
            'konfirmasi' => 1,
        ]);

        $detail1->refresh();
        $detail2->refresh();
        $detail3->refresh();

        $this->assertNotNull($detail1->kode_batch_kembali);
        $batchCode = $detail1->kode_batch_kembali;

        $response->assertRedirect(route('pengembalian.member.batch-tiket', $batchCode));

        // Verifikasi semua buku berstatus diajukan kembali
        $this->assertEquals('Diajukan Kembali', $detail1->statusBuku);
        $this->assertEquals('Diajukan Kembali', $detail2->statusBuku);
        $this->assertEquals('Diajukan Kembali', $detail3->statusBuku);

        // Verifikasi kondisi masing-masing buku tersimpan akurat
        $this->assertEquals('Baik', $detail1->kondisi_laporan);
        $this->assertEquals('Rusak', $detail2->kondisi_laporan);
        $this->assertEquals('Hilang', $detail3->kondisi_laporan);

        // Verifikasi kode batch sama untuk ketiganya
        $this->assertEquals($batchCode, $detail2->kode_batch_kembali);
        $this->assertEquals($batchCode, $detail3->kode_batch_kembali);

        // Verifikasi setiap buku tetap memiliki token kode_kembali yang unik
        $this->assertNotEquals($detail1->kode_kembali, $detail2->kode_kembali);
        $this->assertNotEquals($detail2->kode_kembali, $detail3->kode_kembali);
    }

    public function test_member_can_view_batch_return_ticket(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
        ]);
        $kategori = Kategori::factory()->create();

        $buku1 = Buku::factory()->create(['idKategori' => $kategori->idKategori, 'judul' => 'Sistem Informasi']);
        $buku2 = Buku::factory()->create(['idKategori' => $kategori->idKategori, 'judul' => 'Basis Data Lanjut']);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => Carbon::now()->subDays(2)->toDateString(),
            'batasKembali' => Carbon::now()->addDays(12)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 2,
        ]);

        $batchCode = 'KB-BATCH-TEST-12345';

        $detail1 = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku1->idBuku,
            'idEksemplar' => $buku1->eksemplar()->first()->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Diajukan Kembali',
            'kode_kembali' => 'KMB-TEST-001',
            'qr_kembali' => 'QR-RET-TEST-001',
            'kode_batch_kembali' => $batchCode,
            'kondisi_laporan' => 'Baik',
        ]);

        $detail2 = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku2->idBuku,
            'idEksemplar' => $buku2->eksemplar()->first()->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Diajukan Kembali',
            'kode_kembali' => 'KMB-TEST-002',
            'qr_kembali' => 'QR-RET-TEST-002',
            'kode_batch_kembali' => $batchCode,
            'kondisi_laporan' => 'Rusak',
        ]);

        $response = $this->actingAs($member)->get(route('pengembalian.member.batch-tiket', $batchCode));

        $response->assertOk();
        $response->assertViewIs('pengembalian.member_batch_tiket');
        $response->assertSee($batchCode);
        $response->assertSee('Sistem Informasi');
        $response->assertSee('Basis Data Lanjut');
        $response->assertSee('Tiket Pengembalian Buku Sekaligus');
    }

    public function test_member_cannot_view_other_members_batch_ticket(): void
    {
        $memberA = User::factory()->create(['role' => 'member', 'status' => 'aktif']);
        $memberB = User::factory()->create(['role' => 'member', 'status' => 'aktif']);
        $kategori = Kategori::factory()->create();

        $buku = Buku::factory()->create(['idKategori' => $kategori->idKategori]);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $memberA->id,
            'tanggalPinjam' => Carbon::now()->subDays(2)->toDateString(),
            'batasKembali' => Carbon::now()->addDays(12)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        $batchCode = 'KB-BATCH-MEMBER-A';

        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $buku->eksemplar()->first()->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Diajukan Kembali',
            'kode_kembali' => 'KMB-TEST-003',
            'qr_kembali' => 'QR-RET-TEST-003',
            'kode_batch_kembali' => $batchCode,
            'kondisi_laporan' => 'Baik',
        ]);

        $response = $this->actingAs($memberB)->get(route('pengembalian.member.batch-tiket', $batchCode));

        $response->assertForbidden();
    }

    public function test_member_can_cancel_batch_return(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
        ]);
        $kategori = Kategori::factory()->create();

        $buku1 = Buku::factory()->create(['idKategori' => $kategori->idKategori]);
        $buku2 = Buku::factory()->create(['idKategori' => $kategori->idKategori]);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => Carbon::now()->subDays(2)->toDateString(),
            'batasKembali' => Carbon::now()->addDays(12)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 2,
        ]);

        $batchCode = 'KB-BATCH-CANCEL-TEST';

        $detail1 = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku1->idBuku,
            'idEksemplar' => $buku1->eksemplar()->first()->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Diajukan Kembali',
            'kode_kembali' => 'KMB-CAN-001',
            'qr_kembali' => 'QR-RET-CAN-001',
            'kode_batch_kembali' => $batchCode,
            'kondisi_laporan' => 'Baik',
        ]);

        $detail2 = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku2->idBuku,
            'idEksemplar' => $buku2->eksemplar()->first()->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Diajukan Kembali',
            'kode_kembali' => 'KMB-CAN-002',
            'qr_kembali' => 'QR-RET-CAN-002',
            'kode_batch_kembali' => $batchCode,
            'kondisi_laporan' => 'Rusak',
        ]);

        $response = $this->actingAs($member)->post(route('pengembalian.member.batch-batal', $batchCode));

        $response->assertRedirect(route('pengembalian.member'));
        $response->assertSessionHas('success');

        $detail1->refresh();
        $detail2->refresh();

        $this->assertEquals('Dipinjam', $detail1->statusBuku);
        $this->assertEquals('Dipinjam', $detail2->statusBuku);
        $this->assertNull($detail1->kode_batch_kembali);
        $this->assertNull($detail2->kode_batch_kembali);
        $this->assertNull($detail1->kode_kembali);
        $this->assertNull($detail2->kode_kembali);
        $this->assertEquals('Baik', $detail1->kondisi_laporan);
        $this->assertEquals('Baik', $detail2->kondisi_laporan);
    }
}
