<?php

namespace Tests\Feature;

use App\Models\Barcode;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberPeminjamanTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_loan_page(): void
    {
        $buku = Buku::factory()->create();

        $response = $this->get(route('peminjaman.ajukan', $buku->idBuku));

        $response->assertRedirect(route('login'));
    }

    public function test_inactive_member_cannot_access_loan_page(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'nonaktif',
        ]);
        $buku = Buku::factory()->create();

        $response = $this->actingAs($member)->get(route('peminjaman.ajukan', $buku->idBuku));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHasErrors(['peminjaman']);
    }

    public function test_member_without_book_id_is_redirected_to_katalog_index(): void
    {
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);

        $response = $this->actingAs($member)->get(route('peminjaman.ajukan'));

        $response->assertRedirect(route('katalog.index'));
        $response->assertSessionHas('info');
    }

    public function test_member_can_view_loan_page_with_complete_details_and_terms(): void
    {
        $member = User::factory()->create([
            'name' => 'Rizky Pratama',
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $kategori = Kategori::factory()->create(['namaKategori' => 'Fiksi']);
        $buku = Buku::factory()->create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Laut Bercerita',
            'penulis' => 'Leila S. Chudori',
            'rak' => 'Rak F-12',
            'stok' => 5,
        ]);
        Barcode::create(['idBuku' => $buku->idBuku, 'kodeBarcode' => 'BK-00417']);

        $response = $this->actingAs($member)->get(route('peminjaman.ajukan', $buku->idBuku));

        $response->assertOk();
        $response->assertViewIs('peminjaman.ajukan');

        // Header & Stepper
        $response->assertSee('Ajukan Peminjaman');
        $response->assertSee('1. Rincian buku');
        $response->assertSee('2. Konfirmasi');
        $response->assertSee('3. Berhasil');

        // Book metadata
        $response->assertSee('Laut Bercerita');
        $response->assertSee('Leila S. Chudori');
        $response->assertSee('Rak F-12');
        $response->assertSee('Tersedia');

        // Loan details
        $response->assertSee('Rincian peminjaman');
        $response->assertSee('Rizky Pratama');
        $response->assertSee($member->kode_anggota);
        $response->assertSee('Laut Bercerita · BK-00417');
        $response->assertSee('14 hari / 1 buku');

        // Terms and progressive fine rules
        $response->assertSee('Ketentuan peminjaman');
        $response->assertSee('Saya memahami ketentuan peminjaman.');
        $response->assertSee('10% per minggu dari harga buku (maksimal 100%)');
        $response->assertSee('denda 100% seharga buku jika buku rusak atau hilang');

        // Confirmation modal
        $response->assertSee('Konfirmasi Peminjaman');
        $response->assertSee('Lanjutkan Konfirmasi');
    }

    public function test_cannot_borrow_book_with_zero_stock(): void
    {
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);
        $buku = Buku::factory()->habis()->create();

        $response = $this->actingAs($member)->get(route('peminjaman.ajukan', $buku->idBuku));

        $response->assertRedirect(route('katalog.show', $buku->idBuku));
        $response->assertSessionHasErrors(['stok']);
    }

    public function test_cannot_borrow_when_member_reaches_maximum_active_quota(): void
    {
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);
        $bukuBaru = Buku::factory()->create();

        // Buat 7 peminjaman aktif yang belum kembali
        for ($i = 0; $i < Peminjaman::BATAS_MAKSIMAL_BUKU; $i++) {
            $bukuLain = Buku::factory()->create();
            $peminjaman = Peminjaman::create([
                'idUserMember' => $member->id,
                'tanggalPinjam' => now()->toDateString(),
                'batasKembali' => now()->addDays(14)->toDateString(),
                'status' => 'Dipinjam',
                'totalBuku' => 1,
            ]);
            DetailPeminjaman::create([
                'idPeminjaman' => $peminjaman->idPeminjaman,
                'idBuku' => $bukuLain->idBuku,
                'idEksemplar' => $bukuLain->eksemplar->first()->idEksemplar,
                'jumlah' => 1,
                'statusBuku' => 'Dipinjam',
            ]);
        }

        $response = $this->actingAs($member)->get(route('peminjaman.ajukan', $bukuBaru->idBuku));

        $response->assertRedirect(route('katalog.show', $bukuBaru->idBuku));
        $response->assertSessionHasErrors(['kuota']);
    }

    public function test_submitting_loan_requires_terms_acceptance(): void
    {
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);
        $buku = Buku::factory()->create();

        $response = $this->actingAs($member)->post(route('peminjaman.ajukan.proses', $buku->idBuku), [
            'setuju_ketentuan' => '0',
        ]);

        $response->assertSessionHasErrors(['setuju_ketentuan']);
        $this->assertDatabaseCount('peminjaman', 0);
    }

    public function test_member_can_successfully_submit_loan_and_redirect_to_success(): void
    {
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);
        $buku = Buku::factory()->create(['stok' => 3]);
        $stokAwal = $buku->stok;

        $response = $this->actingAs($member)->post(route('peminjaman.ajukan.proses', $buku->idBuku), [
            'setuju_ketentuan' => '1',
        ]);

        $this->assertDatabaseHas('peminjaman', [
            'idUserMember' => $member->id,
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);

        $peminjaman = Peminjaman::where('idUserMember', $member->id)->first();
        $response->assertRedirect(route('peminjaman.sukses', $peminjaman->idPeminjaman));

        $this->assertDatabaseHas('detail_peminjaman', [
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'statusBuku' => 'Dipinjam',
        ]);

        // Stok eksemplar terupdate
        $buku->refresh();
        $this->assertEquals($stokAwal - 1, $buku->stok);
    }

    public function test_success_page_displays_transaction_and_pickup_instructions(): void
    {
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);
        $buku = Buku::factory()->create(['judul' => 'Laut Bercerita']);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => now()->toDateString(),
            'batasKembali' => now()->addDays(14)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);
        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $buku->eksemplar->first()->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        $response = $this->actingAs($member)->get(route('peminjaman.sukses', $peminjaman->idPeminjaman));

        $response->assertOk();
        $response->assertViewIs('peminjaman.sukses');
        $response->assertSee('Peminjaman Berhasil');
        $response->assertSee('Bacaan baru siap menemanimu');
        $response->assertSee('Peminjaman tercatat');
        $response->assertSee('Barcode Peminjaman');
        $response->assertSee('Simpan Barcode');
        $response->assertSee('Ke Dasbor');
        $response->assertSee($peminjaman->kode_transaksi);
        $response->assertSee('Laut Bercerita');
        $response->assertSee('Pengambilan: meja layanan, lantai 1.');
    }

    public function test_other_member_cannot_view_someone_elses_loan_success_page(): void
    {
        $member1 = User::factory()->create(['role' => 'member']);
        $member2 = User::factory()->create(['role' => 'member']);
        $buku = Buku::factory()->create();

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member1->id,
            'tanggalPinjam' => now()->toDateString(),
            'batasKembali' => now()->addDays(14)->toDateString(),
            'status' => 'Dipinjam',
            'totalBuku' => 1,
        ]);
        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $buku->eksemplar->first()->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Dipinjam',
        ]);

        $response = $this->actingAs($member2)->get(route('peminjaman.sukses', $peminjaman->idPeminjaman));

        $response->assertForbidden();
    }
}
