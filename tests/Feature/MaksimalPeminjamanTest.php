<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaksimalPeminjamanTest extends TestCase
{
    use RefreshDatabase;

    private User $petugas;

    private User $member;

    private Kategori $kategori;

    protected function setUp(): void
    {
        parent::setUp();

        $this->petugas = User::create([
            'name' => 'Petugas Sirkulasi',
            'email' => 'petugas@perpus.test',
            'password' => bcrypt('password123'),
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $this->member = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@perpus.test',
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
     * Helper untuk membuat buku beserta eksemplarnya.
     */
    private function createBuku(string $judul = 'Buku Uji'): Buku
    {
        return Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => $judul,
            'penulis' => 'Penulis Uji',
            'penerbit' => 'Penerbit Uji',
            'tahunTerbit' => 2026,
            'harga' => 100000,
            'stok' => 1,
            'kondisi' => 'Baik',
        ]);
    }

    /**
     * Helper untuk meminjamkan N buku ke member dengan status Dipinjam.
     */
    private function pinjamkanBukuKeMember(int $jumlah): array
    {
        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'idUserPetugas' => $this->petugas->id,
            'tanggalPinjam' => Carbon::now()->toDateString(),
            'batasKembali' => Carbon::now()->addDays(30)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => $jumlah,
        ]);

        $details = [];
        for ($i = 1; $i <= $jumlah; $i++) {
            $buku = $this->createBuku("Buku Terpinjam {$i}");
            $eksemplar = $buku->eksemplar()->first();
            $eksemplar->update(['status' => 'Dipinjam']);
            $buku->syncStok();

            $detail = DetailPeminjaman::create([
                'idPeminjaman' => $peminjaman->idPeminjaman,
                'idBuku' => $buku->idBuku,
                'idEksemplar' => $eksemplar->idEksemplar,
                'jumlah' => 1,
                'statusBuku' => 'Dipinjam',
            ]);
            $details[] = $detail;
        }

        return [$peminjaman, $details];
    }

    public function test_helper_method_user_menghitung_jumlah_dan_kuota_dengan_tepat(): void
    {
        $this->assertEquals(0, $this->member->jumlahBukuSedangDipinjam());
        $this->assertEquals(7, $this->member->sisaKuotaPinjam());
        $this->assertFalse($this->member->sudahMencapaiBatasMaksimalPinjam());
        $this->assertTrue($this->member->bolehMeminjam(1));
        $this->assertTrue($this->member->bolehMeminjam(7));
        $this->assertFalse($this->member->bolehMeminjam(8));

        // Pinjam 5 buku
        $this->pinjamkanBukuKeMember(5);
        $this->member->refresh();

        $this->assertEquals(5, $this->member->jumlahBukuSedangDipinjam());
        $this->assertEquals(2, $this->member->sisaKuotaPinjam());
        $this->assertFalse($this->member->sudahMencapaiBatasMaksimalPinjam());
        $this->assertTrue($this->member->bolehMeminjam(2));
        $this->assertFalse($this->member->bolehMeminjam(3));

        // Pinjam 2 buku lagi sehingga mencapai total 7 buku
        $this->pinjamkanBukuKeMember(2);
        $this->member->refresh();

        $this->assertEquals(7, $this->member->jumlahBukuSedangDipinjam());
        $this->assertEquals(0, $this->member->sisaKuotaPinjam());
        $this->assertTrue($this->member->sudahMencapaiBatasMaksimalPinjam());
        $this->assertFalse($this->member->bolehMeminjam(1));
    }

    public function test_petugas_tidak_dapat_membuat_peminjaman_meja_jika_member_sudah_meminjam_7_buku(): void
    {
        $this->pinjamkanBukuKeMember(7);
        $this->member->refresh();

        $bukuBaru = $this->createBuku('Buku Baru ke-8');
        $eksemplarBaru = $bukuBaru->eksemplar()->first();

        $this->actingAs($this->petugas);

        $response = $this->postJson(route('peminjaman.store'), [
            'idUserMember' => $this->member->id,
            'barcodes' => [$eksemplarBaru->qr_token],
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
        $this->assertStringContainsString('batas maksimal 7 buku', $response->json('message'));

        // Pastikan eksemplar tetap 'Tersedia' dan peminjaman baru tidak terbuat
        $eksemplarBaru->refresh();
        $this->assertEquals('Tersedia', $eksemplarBaru->status);
    }

    public function test_member_tidak_bisa_mengajukan_booking_jika_sudah_meminjam_7_buku(): void
    {
        $this->pinjamkanBukuKeMember(7);
        $this->member->refresh();

        $bukuBaru = $this->createBuku('Buku Baru untuk Booking');

        $this->actingAs($this->member);

        $response = $this->post(route('peminjaman.ajukan', $bukuBaru->idBuku), [
            'opsi_pengambilan' => 'siapkan_petugas',
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('batas maksimal 7 buku', session('error'));

        // Pastikan tiket booking tidak dibuat
        $this->assertDatabaseMissing('peminjaman', [
            'idUserMember' => $this->member->id,
            'status' => 'Booking',
        ]);
    }

    public function test_petugas_tidak_dapat_serah_terima_booking_jika_member_sudah_meminjam_7_buku(): void
    {
        // 1. Member membuat booking saat kuotanya masih ada (misal sedang pinjam 0)
        $bukuBooking = $this->createBuku('Buku Booking');
        $eksemplarBooking = $bukuBooking->eksemplar()->first();
        $eksemplarBooking->update(['status' => 'Dibooking']);
        $bukuBooking->syncStok();

        $peminjamanBooking = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'tanggalPinjam' => Carbon::now()->toDateString(),
            'batasKembali' => Carbon::now()->addDays(30)->toDateString(),
            'status' => 'Siap Diambil',
            'kode_booking' => 'BK-TEST001',
            'opsi_pengambilan' => 'siapkan_petugas',
            'totalBuku' => 1,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $peminjamanBooking->idPeminjaman,
            'idBuku' => $bukuBooking->idBuku,
            'idEksemplar' => $eksemplarBooking->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Siap Diambil',
        ]);

        // 2. Kemudian member meminjam 7 buku lain secara langsung di meja perpustakaan
        $this->pinjamkanBukuKeMember(7);
        $this->member->refresh();

        $this->actingAs($this->petugas);

        // 3. Petugas mencoba memproses serah terima buku booking tersebut
        $response = $this->postJson(route('petugas.booking.serah-terima', $peminjamanBooking->idPeminjaman));

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonFragment([
                'message' => 'Penyerahan ditolak: Anggota Budi Santoso saat ini telah meminjam 7 buku. Total peminjaman setelah serah terima akan menjadi 8 buku (Batas maksimal 7 buku). Member wajib mengembalikan buku terlebih dahulu.',
            ]);

        // Status booking tidak boleh berubah menjadi 'Dipinjam'
        $peminjamanBooking->refresh();
        $this->assertEquals('Siap Diambil', $peminjamanBooking->status);
    }

    public function test_member_dapat_meminjam_kembali_setelah_mengembalikan_buku(): void
    {
        // Member meminjam 7 buku
        [$peminjamanLama, $details] = $this->pinjamkanBukuKeMember(7);
        $this->member->refresh();
        $this->assertEquals(7, $this->member->jumlahBukuSedangDipinjam());

        // Kembalikan 1 buku
        $detailPertama = $details[0];
        $detailPertama->update([
            'statusBuku' => 'Kembali',
        ]);
        $eksemplarPertama = $detailPertama->eksemplar;
        $eksemplarPertama->update(['status' => 'Tersedia']);
        $eksemplarPertama->buku->syncStok();

        $this->member->refresh();
        $this->assertEquals(6, $this->member->jumlahBukuSedangDipinjam());
        $this->assertEquals(1, $this->member->sisaKuotaPinjam());
        $this->assertTrue($this->member->bolehMeminjam(1));

        // Sekarang petugas dapat meminjamkan 1 buku baru
        $bukuBaru = $this->createBuku('Buku Baru ke-7 Pengganti');
        $eksemplarBaru = $bukuBaru->eksemplar()->first();

        $this->actingAs($this->petugas);

        $response = $this->postJson(route('peminjaman.store'), [
            'idUserMember' => $this->member->id,
            'barcodes' => [$eksemplarBaru->qr_token],
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->member->refresh();
        $this->assertEquals(7, $this->member->jumlahBukuSedangDipinjam());
        $this->assertTrue($this->member->sudahMencapaiBatasMaksimalPinjam());
    }

    public function test_api_identifikasi_dan_scan_member_memberikan_informasi_kuota_penuh(): void
    {
        $this->pinjamkanBukuKeMember(7);
        $this->member->refresh();

        $this->actingAs($this->petugas);

        // Uji API Scan Member
        $resScan = $this->postJson(route('api.scan.member'), [
            'token' => $this->member->qr_token,
        ]);

        $resScan->assertOk()
            ->assertJson([
                'success' => true,
                'member' => [
                    'sedangDipinjam' => 7,
                    'sisaKuota' => 0,
                    'kuotaPenuh' => true,
                ],
            ]);

        // Uji API Bebas Identifikasi
        $resIdentifikasi = $this->postJson(route('api.scan.identifikasi'), [
            'code' => $this->member->kode_anggota,
        ]);

        $resIdentifikasi->assertOk()
            ->assertJson([
                'success' => true,
                'type' => 'member',
                'data' => [
                    'sedangDipinjam' => 7,
                    'sisaKuota' => 0,
                    'kuotaPenuh' => true,
                ],
            ]);
    }
}
