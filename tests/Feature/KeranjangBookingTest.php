<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeranjangBookingTest extends TestCase
{
    use RefreshDatabase;

    private User $member;

    private User $petugas;

    private User $admin;

    private Kategori $kategori;

    protected function setUp(): void
    {
        parent::setUp();

        $this->member = User::create([
            'name' => 'Siti Rahma',
            'email' => 'siti@perpus.test',
            'password' => bcrypt('password123'),
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $this->petugas = User::create([
            'name' => 'Petugas Sirkulasi',
            'email' => 'petugas@perpus.test',
            'password' => bcrypt('password123'),
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $this->admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@perpus.test',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $this->kategori = Kategori::create([
            'namaKategori' => 'Sains',
            'deskripsi' => 'Buku Sains dan Pengetahuan',
        ]);
    }

    private function createBuku(string $judul = 'Buku Sains'): Buku
    {
        return Buku::create([
            'idKategori' => $this->kategori->idKategori,
            'judul' => $judul,
            'penulis' => 'Prof. Sains',
            'penerbit' => 'Gramedia',
            'tahunTerbit' => 2026,
            'harga' => 95000,
            'stok' => 2,
            'kondisi' => 'Baik',
        ]);
    }

    public function test_hanya_member_yang_dapat_mengakses_keranjang_booking(): void
    {
        // 1. Guest diarahkan ke login
        $resGuest = $this->get(route('keranjang.index'));
        $resGuest->assertRedirect(route('login'));

        // 2. Petugas ditolak (403)
        $this->actingAs($this->petugas);
        $resPetugas = $this->get(route('keranjang.index'));
        $resPetugas->assertForbidden();

        // 3. Admin ditolak (403)
        $this->actingAs($this->admin);
        $resAdmin = $this->get(route('keranjang.index'));
        $resAdmin->assertForbidden();

        // 4. Member aktif diizinkan (200)
        $this->actingAs($this->member);
        $resMember = $this->get(route('keranjang.index'));
        $resMember->assertOk()
            ->assertSee('Keranjang Booking Buku');
    }

    public function test_member_dapat_menambahkan_buku_ke_keranjang(): void
    {
        $buku1 = $this->createBuku('Fisika Kuantum');
        $buku2 = $this->createBuku('Biologi Molekuler');

        $this->actingAs($this->member);

        // Tambah buku 1
        $res1 = $this->post(route('keranjang.tambah', $buku1->idBuku));
        $res1->assertSessionHas('success');
        $this->assertTrue(session()->has('keranjang_booking.'.$buku1->idBuku));

        // Tambah buku 2 via JSON
        $res2 = $this->postJson(route('keranjang.tambah', $buku2->idBuku));
        $res2->assertOk()
            ->assertJson([
                'success' => true,
                'totalItem' => 2,
            ]);

        $cart = session('keranjang_booking');
        $this->assertCount(2, $cart);
        $this->assertArrayHasKey($buku1->idBuku, $cart);
        $this->assertArrayHasKey($buku2->idBuku, $cart);
    }

    public function test_member_tidak_bisa_menambahkan_buku_yang_sama_dua_kali(): void
    {
        $buku = $this->createBuku('Astronomi Modern');

        $this->actingAs($this->member);

        $this->post(route('keranjang.tambah', $buku->idBuku));

        // Tambah lagi buku yang sama
        $resDuplicate = $this->post(route('keranjang.tambah', $buku->idBuku));
        $resDuplicate->assertSessionHas('info');

        $cart = session('keranjang_booking');
        $this->assertCount(1, $cart);
    }

    public function test_member_tidak_bisa_menambahkan_buku_jika_total_melebihi_7_buku(): void
    {
        $this->actingAs($this->member);

        // Pinjamkan 5 buku langsung
        $peminjaman = Peminjaman::create([
            'idUserMember' => $this->member->id,
            'idUserPetugas' => $this->petugas->id,
            'tanggalPinjam' => now()->toDateString(),
            'batasKembali' => now()->addDays(30)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 5,
        ]);

        for ($i = 1; $i <= 5; $i++) {
            $b = $this->createBuku("Buku Dipinjam {$i}");
            DetailPeminjaman::create([
                'idPeminjaman' => $peminjaman->idPeminjaman,
                'idBuku' => $b->idBuku,
                'idEksemplar' => $b->eksemplar()->first()->idEksemplar,
                'jumlah' => 1,
                'statusBuku' => 'Dipinjam',
            ]);
        }

        // Member sudah punya 5 buku dipinjam, sisa kuota 2
        $buku1 = $this->createBuku('Buku Tambahan 1');
        $buku2 = $this->createBuku('Buku Tambahan 2');
        $buku3 = $this->createBuku('Buku Tambahan 3');

        // Tambah buku 1 (total rencana: 6) -> Berhasil
        $res1 = $this->post(route('keranjang.tambah', $buku1->idBuku));
        $res1->assertSessionHas('success');

        // Tambah buku 2 (total rencana: 7) -> Berhasil
        $res2 = $this->post(route('keranjang.tambah', $buku2->idBuku));
        $res2->assertSessionHas('success');

        // Tambah buku 3 (total rencana: 8 > 7) -> Ditolak
        $res3 = $this->post(route('keranjang.tambah', $buku3->idBuku));
        $res3->assertSessionHas('error');
        $this->assertStringContainsString('Batas maksimal peminjaman adalah 7 buku', session('error'));

        $cart = session('keranjang_booking');
        $this->assertCount(2, $cart);
    }

    public function test_member_dapat_menghapus_item_dan_mengosongkan_keranjang(): void
    {
        $buku1 = $this->createBuku('Kimia Organik');
        $buku2 = $this->createBuku('Kimia Anorganik');

        $this->actingAs($this->member);

        $this->post(route('keranjang.tambah', $buku1->idBuku));
        $this->post(route('keranjang.tambah', $buku2->idBuku));

        $this->assertCount(2, session('keranjang_booking'));

        // Hapus buku 1
        $resHapus = $this->post(route('keranjang.hapus', $buku1->idBuku));
        $resHapus->assertSessionHas('success');

        $this->assertCount(1, session('keranjang_booking'));
        $this->assertArrayNotHasKey($buku1->idBuku, session('keranjang_booking'));
        $this->assertArrayHasKey($buku2->idBuku, session('keranjang_booking'));

        // Kosongkan keranjang
        $resKosongkan = $this->post(route('keranjang.kosongkan'));
        $resKosongkan->assertRedirect(route('keranjang.index'));
        $this->assertEmpty(session('keranjang_booking', []));
    }

    public function test_member_dapat_checkout_keranjang_secara_bersamaan_menjadi_satu_transaksi_booking(): void
    {
        $buku1 = $this->createBuku('Robotika Cerdas');
        $buku2 = $this->createBuku('Kecerdasan Buatan');
        $buku3 = $this->createBuku('Jaringan Komputer');

        $this->actingAs($this->member);

        // Masukkan 3 buku ke keranjang
        $this->post(route('keranjang.tambah', $buku1->idBuku));
        $this->post(route('keranjang.tambah', $buku2->idBuku));
        $this->post(route('keranjang.tambah', $buku3->idBuku));

        $this->assertCount(3, session('keranjang_booking'));

        // Lakukan Checkout Booking Sekaligus
        $resCheckout = $this->post(route('keranjang.checkout'), [
            'opsi_pengambilan' => 'siapkan_petugas',
        ]);

        // Verifikasi Redirect ke tiket booking
        $peminjaman = Peminjaman::where('idUserMember', $this->member->id)->first();
        $this->assertNotNull($peminjaman);

        $resCheckout->assertRedirect(route('peminjaman.booking.tiket', $peminjaman->idPeminjaman));

        // Verifikasi data transaksi induk Peminjaman
        $this->assertEquals(3, $peminjaman->totalBuku);
        $this->assertEquals('Booking', $peminjaman->status);
        $this->assertEquals('siapkan_petugas', $peminjaman->opsi_pengambilan);
        $this->assertNotEmpty($peminjaman->kode_booking);
        $this->assertNotEmpty($peminjaman->qr_token);

        // Verifikasi DetailPeminjaman
        $this->assertCount(3, $peminjaman->details);
        foreach ($peminjaman->details as $detail) {
            $this->assertEquals('Booking', $detail->statusBuku);
            $this->assertEquals('Dibooking', $detail->eksemplar->status);
        }

        // Verifikasi keranjang session telah dikosongkan
        $this->assertEmpty(session('keranjang_booking', []));

        // Verifikasi halaman tiket menampilkan ketiga buku
        $resTiket = $this->get(route('peminjaman.booking.tiket', $peminjaman->idPeminjaman));
        $resTiket->assertOk()
            ->assertSee('Robotika Cerdas')
            ->assertSee('Kecerdasan Buatan')
            ->assertSee('Jaringan Komputer')
            ->assertSee('Total 3 Buku');
    }

    public function test_petugas_dapat_serah_terima_booking_multi_buku_secara_bersamaan(): void
    {
        $buku1 = $this->createBuku('Struktur Data');
        $buku2 = $this->createBuku('Basis Data Lanjut');

        $this->actingAs($this->member);
        $this->post(route('keranjang.tambah', $buku1->idBuku));
        $this->post(route('keranjang.tambah', $buku2->idBuku));

        $this->post(route('keranjang.checkout'), [
            'opsi_pengambilan' => 'siapkan_petugas',
        ]);

        $peminjaman = Peminjaman::where('idUserMember', $this->member->id)->first();

        // Petugas melakukan serah terima di meja sirkulasi
        $this->actingAs($this->petugas);

        $resSerahTerima = $this->postJson(route('petugas.booking.serah-terima', $peminjaman->idPeminjaman));
        $resSerahTerima->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $peminjaman->refresh();
        $this->assertEquals('Dipinjam', $peminjaman->status);

        foreach ($peminjaman->details as $detail) {
            $this->assertEquals('Dipinjam', $detail->statusBuku);
            $this->assertEquals('Dipinjam', $detail->eksemplar->status);
        }

        $this->member->refresh();
        $this->assertEquals(2, $this->member->jumlahBukuSedangDipinjam());
        $this->assertEquals(5, $this->member->sisaKuotaPinjam());
    }
}
