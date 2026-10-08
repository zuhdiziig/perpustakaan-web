<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\BukuEksemplar;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Database\Seeders\BukuEksemplarSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QrCodeSystemTest extends TestCase
{
    use RefreshDatabase;

    private User $petugas;

    private User $member1;

    private User $member2;

    private Kategori $kategori;

    protected function setUp(): void
    {
        parent::setUp();

        $this->petugas = User::create([
            'name' => 'Petugas Perpustakaan',
            'email' => 'petugas@perpus.test',
            'password' => bcrypt('password123'),
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $this->member1 = User::create([
            'name' => 'Member Alpha',
            'email' => 'alpha@perpus.test',
            'password' => bcrypt('password123'),
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $this->member2 = User::create([
            'name' => 'Member Beta',
            'email' => 'beta@perpus.test',
            'password' => bcrypt('password123'),
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $this->kategori = Kategori::create([
            'namaKategori' => 'Teknologi',
            'deskripsi' => 'Kategori Buku Teknologi',
        ]);
    }

    /**
     * Requirement A: Stok 0 tidak membuat physical copy.
     */
    public function test_a_stok_0_tidak_membuat_physical_copy(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Buku Habis Terjual',
            'penulis' => 'Penulis Nol',
            'penerbit' => 'Penerbit Kosong',
            'tahunTerbit' => 2026,
            'harga' => 50000,
            'stok' => 0,
            'kondisi' => 'Baik',
        ]);

        $this->assertEquals(0, $buku->eksemplar()->count());
        $this->assertEquals(0, $buku->stok);
    }

    /**
     * Requirement B: Dua copy dari judul yang sama memiliki idEksemplar dan QR berbeda.
     */
    public function test_b_dua_copy_dari_judul_sama_memiliki_ideksemplar_dan_qr_berbeda(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Struktur Data & Algoritma',
            'penulis' => 'Robert Lafore',
            'penerbit' => 'Informatika',
            'tahunTerbit' => 2026,
            'harga' => 120000,
            'stok' => 2,
            'kondisi' => 'Baik',
        ]);

        $eksemplars = $buku->eksemplar()->orderBy('nomor_eksemplar')->get();
        $this->assertCount(2, $eksemplars);

        $copy1 = $eksemplars[0];
        $copy2 = $eksemplars[1];

        $this->assertNotEquals($copy1->idEksemplar, $copy2->idEksemplar);
        $this->assertNotEquals($copy1->qr_token, $copy2->qr_token);
        $this->assertEquals(1, $copy1->nomor_eksemplar);
        $this->assertEquals(2, $copy2->nomor_eksemplar);
        $this->assertStringStartsWith('bk_', $copy1->qr_token);
        $this->assertStringStartsWith('bk_', $copy2->qr_token);
    }

    /**
     * Requirement C: Title-level legacy QR tidak memilih physical copy arbitrary untuk circulation baru.
     */
    public function test_c_title_level_legacy_qr_tidak_memilih_physical_copy_arbitrary_untuk_circulation_baru(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Basis Data Modern',
            'penulis' => 'Elmasri Navathe',
            'penerbit' => 'Pearson',
            'tahunTerbit' => 2026,
            'harga' => 150000,
            'stok' => 3,
            'kondisi' => 'Baik',
        ]);

        $this->actingAs($this->petugas);

        // 1. Scan title-level QR code pada API scan harus ditolak (422)
        $scanResponse = $this->postJson(route('api.scan.buku'), [
            'token' => $buku->qr_token,
        ]);

        $scanResponse->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);

        // 2. Submit peminjaman dengan title-level QR code harus ditolak dan tidak boleh memilih copy sembarangan
        $pinjamResponse = $this->post(route('peminjaman.store'), [
            'idUserMember' => $this->member1->id,
            'barcodes' => [$buku->qr_token],
        ]);

        $pinjamResponse->assertSessionHasErrors('barcodes');

        // Pastikan tidak ada copy yang terpinjam (semua tetap Tersedia)
        $this->assertEquals(3, $buku->eksemplar()->where('status', 'Tersedia')->count());
        $buku->refresh();
        $this->assertEquals(3, $buku->stok);
    }

    /**
     * Requirement D: Return copy A tidak mengubah copy B.
     */
    public function test_d_return_copy_a_tidak_mengubah_copy_b(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Clean Architecture',
            'penulis' => 'Robert C. Martin',
            'penerbit' => 'Prentice Hall',
            'tahunTerbit' => 2026,
            'harga' => 200000,
            'stok' => 2,
            'kondisi' => 'Baik',
        ]);

        $copyA = $buku->eksemplar()->where('nomor_eksemplar', 1)->first();
        $copyB = $buku->eksemplar()->where('nomor_eksemplar', 2)->first();

        $this->actingAs($this->petugas);

        // Member 1 meminjam copy A
        $this->post(route('peminjaman.store'), [
            'idUserMember' => $this->member1->id,
            'barcodes' => [$copyA->qr_token],
        ]);
        $peminjamanA = Peminjaman::where('idUserMember', $this->member1->id)->first();

        // Member 2 meminjam copy B
        $this->post(route('peminjaman.store'), [
            'idUserMember' => $this->member2->id,
            'barcodes' => [$copyB->qr_token],
        ]);

        $copyA->refresh();
        $copyB->refresh();
        $this->assertEquals('Dipinjam', $copyA->status);
        $this->assertEquals('Dipinjam', $copyB->status);

        // Kembalikan copy A
        $returnRes = $this->post(route('pengembalian.store'), [
            'idPeminjaman' => $peminjamanA->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $copyA->idEksemplar,
            'kondisiBuku' => 'Baik',
        ]);

        $returnRes->assertRedirect();

        // Status copy A menjadi Tersedia
        $copyA->refresh();
        $this->assertEquals('Tersedia', $copyA->status);

        // Status copy B TETAP Dipinjam
        $copyB->refresh();
        $this->assertEquals('Dipinjam', $copyB->status);

        // Stok master sinkron menjadi 1
        $buku->refresh();
        $this->assertEquals(1, $buku->stok);
    }

    /**
     * Requirement E: Wrong member tidak dapat return copy yang sedang dipinjam member lain.
     */
    public function test_e_wrong_member_tidak_dapat_return_copy_yang_sedang_dipinjam_member_lain(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Refactoring UI',
            'penulis' => 'Adam Wathan',
            'penerbit' => 'Tailwind Press',
            'tahunTerbit' => 2026,
            'harga' => 175000,
            'stok' => 1,
            'kondisi' => 'Baik',
        ]);

        $copy = $buku->eksemplar()->first();

        $this->actingAs($this->petugas);

        // Member 1 meminjam buku
        $this->post(route('peminjaman.store'), [
            'idUserMember' => $this->member1->id,
            'barcodes' => [$copy->qr_token],
        ]);
        $peminjaman = Peminjaman::where('idUserMember', $this->member1->id)->first();

        // Coba proses return dengan menyatakan member yang mengembalikan adalah Member 2
        $res = $this->post(route('pengembalian.store'), [
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $copy->idEksemplar,
            'idUserMember' => $this->member2->id, // Wrong member!
            'kondisiBuku' => 'Baik',
        ]);

        $res->assertSessionHas('error');

        // Pastikan buku tetap berstatus Dipinjam
        $copy->refresh();
        $this->assertEquals('Dipinjam', $copy->status);
    }

    /**
     * Requirement F: Double return ditolak.
     */
    public function test_f_double_return_ditolak(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Design Patterns PHP',
            'penulis' => 'Gang of Four',
            'penerbit' => 'Addison-Wesley',
            'tahunTerbit' => 2026,
            'harga' => 220000,
            'stok' => 1,
            'kondisi' => 'Baik',
        ]);

        $copy = $buku->eksemplar()->first();

        $this->actingAs($this->petugas);

        $this->post(route('peminjaman.store'), [
            'idUserMember' => $this->member1->id,
            'barcodes' => [$copy->qr_token],
        ]);
        $peminjaman = Peminjaman::where('idUserMember', $this->member1->id)->first();

        // Return pertama berhasil
        $res1 = $this->post(route('pengembalian.store'), [
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $copy->idEksemplar,
            'kondisiBuku' => 'Baik',
        ]);
        $res1->assertRedirect();

        // Return kedua untuk eksemplar yang sama pada peminjaman yang sama harus ditolak
        $res2 = $this->post(route('pengembalian.store'), [
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $copy->idEksemplar,
            'kondisiBuku' => 'Baik',
        ]);
        $res2->assertSessionHas('error');
    }

    /**
     * Requirement G: Duplicate nomor eksemplar tidak bisa dibuat untuk buku yang sama (Unique constraint).
     */
    public function test_g_duplicate_nomor_eksemplar_tidak_bisa_dibuat_untuk_buku_yang_sama(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Pragmatic Programmer',
            'penulis' => 'Andy Hunt',
            'penerbit' => 'Addison-Wesley',
            'tahunTerbit' => 2026,
            'harga' => 210000,
            'stok' => 1,
            'kondisi' => 'Baik',
        ]);

        $this->expectException(QueryException::class);

        // Paksa pembuatan copy dengan nomor eksemplar yang sama untuk buku yang sama
        BukuEksemplar::create([
            'idBuku' => $buku->idBuku,
            'nomor_eksemplar' => 1, // Sudah ada nomor 1
            'qr_token' => 'bk_duplicate_test_123',
            'kondisi' => 'Baik',
            'status' => 'Tersedia',
        ]);
    }

    /**
     * Requirement H: BukuEksemplarSeeder idempotent jika dijalankan dua kali.
     */
    public function test_h_buku_eksemplar_seeder_idempotent_jika_dijalankan_dua_kali(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Seeder Test Book',
            'penulis' => 'Tester',
            'penerbit' => 'Test Pub',
            'tahunTerbit' => 2026,
            'harga' => 90000,
            'stok' => 3,
            'kondisi' => 'Baik',
        ]);

        $initialCount = BukuEksemplar::where('idBuku', $buku->idBuku)->count();
        $this->assertEquals(3, $initialCount);

        $seeder = new BukuEksemplarSeeder;

        // Jalankan seeder pertama kali
        $seeder->run();
        $countAfterFirstRun = BukuEksemplar::where('idBuku', $buku->idBuku)->count();
        $this->assertEquals(3, $countAfterFirstRun);

        // Jalankan seeder kedua kali
        $seeder->run();
        $countAfterSecondRun = BukuEksemplar::where('idBuku', $buku->idBuku)->count();
        $this->assertEquals(3, $countAfterSecondRun);

        // Buku dengan stok 0 tidak dibuatkan copy oleh seeder
        $bukuNol = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Seeder Zero Stock Book',
            'penulis' => 'Tester 0',
            'penerbit' => 'Test Pub',
            'tahunTerbit' => 2026,
            'harga' => 90000,
            'stok' => 0,
            'kondisi' => 'Baik',
        ]);
        $seeder->run();
        $this->assertEquals(0, BukuEksemplar::where('idBuku', $bukuNol->idBuku)->count());
    }

    /**
     * Requirement I: Invalid physical QR ditolak.
     */
    public function test_i_invalid_physical_qr_ditolak(): void
    {
        $this->actingAs($this->petugas);

        $resApi = $this->postJson(route('api.scan.buku'), [
            'token' => 'bk_completely_nonexistent_token_99999',
        ]);
        $resApi->assertStatus(404);

        $resStore = $this->post(route('peminjaman.store'), [
            'idUserMember' => $this->member1->id,
            'barcodes' => ['bk_completely_nonexistent_token_99999'],
        ]);
        $resStore->assertSessionHasErrors('barcodes');
    }

    /**
     * Requirement J & Role: Member biasa ditolak mengakses API scan sirkulasi & cetak buku.
     */
    public function test_j_member_biasa_ditolak_mengakses_scan_dan_cetak_buku(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Buku Rahasia',
            'penulis' => 'Anonim',
            'penerbit' => 'Secret',
            'tahunTerbit' => 2026,
            'harga' => 100000,
            'stok' => 1,
            'kondisi' => 'Baik',
        ]);

        $copy = $buku->eksemplar()->first();

        // Login sebagai member biasa
        $this->actingAs($this->member1);

        // 1. Scan Buku API ditolak 403
        $resScanBuku = $this->postJson(route('api.scan.buku'), [
            'token' => $copy->qr_token,
        ]);
        $resScanBuku->assertStatus(403);

        // 2. Scan Member API ditolak 403
        $resScanMember = $this->postJson(route('api.scan.member'), [
            'token' => $this->member1->qr_token,
        ]);
        $resScanMember->assertStatus(403);

        // 3. Cetak Buku QR ditolak 403
        $resCetakBuku = $this->get(route('buku.cetak-qr', $buku->idBuku));
        $resCetakBuku->assertStatus(403);

        // 4. Member hanya boleh cetak kartu dirinya sendiri
        $resCetakSelf = $this->get(route('member.cetak-qr', $this->member1->id));
        $resCetakSelf->assertOk();

        // 5. Member dilarang cetak kartu member lain (403)
        $resCetakOther = $this->get(route('member.cetak-qr', $this->member2->id));
        $resCetakOther->assertStatus(403);
    }

    /**
     * Endpoint cetak buku bersifat read-only dan tidak membuat physical copy baru.
     */
    public function test_k_cetak_buku_adalah_readonly_dan_tidak_membuat_copy_baru(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Buku Read Only Test',
            'penulis' => 'Penulis RO',
            'penerbit' => 'Penerbit RO',
            'tahunTerbit' => 2026,
            'harga' => 60000,
            'stok' => 0,
            'kondisi' => 'Baik',
        ]);

        $this->assertEquals(0, $buku->eksemplar()->count());

        $this->actingAs($this->petugas);

        $response = $this->get(route('buku.cetak-qr', $buku->idBuku));
        $response->assertOk();

        // Pastikan jumlah eksemplar tetap 0, tidak ada copy dummy yang terbuat
        $this->assertEquals(0, $buku->eksemplar()->count());
    }

    /**
     * Endpoint api.identifikasi mampu mengenali QR Code Tiket Pengembalian (ret_... atau KB-...).
     */
    public function test_l_api_identifikasi_mengenali_tiket_pengembalian(): void
    {
        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Buku Sirkulasi Test',
            'penulis' => 'Penulis Sirk',
            'penerbit' => 'Penerbit Sirk',
            'tahunTerbit' => 2026,
            'harga' => 75000,
            'stok' => 1,
            'kondisi' => 'Baik',
        ]);

        $eksemplar = $buku->eksemplar()->first();
        $eksemplar->update(['status' => 'Dipinjam']);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member1->id,
            'idUserPetugas' => $this->petugas->id,
            'tanggalPinjam' => now()->subDays(3)->toDateString(),
            'batasKembali' => now()->addDays(11)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        $detail = DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Diajukan Kembali',
            'kode_kembali' => 'KB-20261007-0099',
            'qr_kembali' => 'ret_12345abcdef67890',
            'kondisi_laporan' => 'Baik',
            'waktu_pengajuan_kembali' => now(),
        ]);

        $this->actingAs($this->petugas);

        // 1. Scan via qr_kembali
        $response1 = $this->postJson(route('api.scan.identifikasi'), ['code' => 'ret_12345abcdef67890']);
        $response1->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('type', 'pengembalian')
            ->assertJsonPath('data.kodeKembali', 'KB-20261007-0099')
            ->assertJsonPath('data.member.name', $this->member1->name)
            ->assertJsonPath('data.buku.judul', 'Buku Sirkulasi Test');

        // 2. Scan via kode_kembali
        $response2 = $this->postJson(route('api.scan.identifikasi'), ['code' => 'KB-20261007-0099']);
        $response2->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('type', 'pengembalian')
            ->assertJsonPath('data.kodeKembali', 'KB-20261007-0099');
    }

    public function test_member_can_poll_realtime_circulation_status_for_loan_and_return(): void
    {
        $this->actingAs($this->member1);

        $buku = Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => 'Pemrograman Laravel',
            'penulis' => 'Taylor Otwell',
            'penerbit' => 'Laravel LLC',
            'tahunTerbit' => 2026,
            'harga' => 150000,
            'stok' => 5,
            'kondisi' => 'Baik',
        ]);
        $eksemplar = $buku->eksemplar()->first();

        // 1. Awalnya belum ada event aktif dalam 3 menit
        $resInitial = $this->getJson(route('api.member.status-sirkulasi-terbaru'));
        $resInitial->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('peminjaman', null)
            ->assertJsonPath('pengembalian', null);

        // 2. Petugas melakukan serah terima peminjaman
        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member1->id,
            'idUserPetugas' => $this->petugas->id,
            'tanggalPinjam' => now()->toDateString(),
            'batasKembali' => now()->addDays(30)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
            'kode_booking' => 'BK-TEST-REALTIME',
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        $resLoan = $this->getJson(route('api.member.status-sirkulasi-terbaru'));
        $resLoan->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('peminjaman.id', $peminjaman->idPeminjaman)
            ->assertJsonPath('peminjaman.kode', 'BK-TEST-REALTIME')
            ->assertJsonPath('peminjaman.namaPetugas', $this->petugas->name)
            ->assertJsonPath('peminjaman.daftarBuku.0', 'Pemrograman Laravel');

        // 3. Petugas menyelesaikan pengembalian
        $pengembalian = Pengembalian::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idUserPetugas' => $this->petugas->id,
            'tanggalKembali' => now()->toDateString(),
            'kondisiBuku' => 'Baik',
        ]);

        $resReturn = $this->getJson(route('api.member.status-sirkulasi-terbaru'));
        $resReturn->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('pengembalian.id', $pengembalian->idPengembalian)
            ->assertJsonPath('pengembalian.namaPetugas', $this->petugas->name)
            ->assertJsonPath('pengembalian.kondisiBuku', 'Baik');
    }
}
