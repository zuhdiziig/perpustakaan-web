<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberManagementAndBukuAccessTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $petugas;

    private User $member;

    private Buku $buku;

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
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'noTelepon' => '081299998888',
        ]);

        $kategori = Kategori::create([
            'namaKategori' => 'Teknologi',
            'deskripsi' => 'Buku-buku IT',
        ]);

        $this->buku = Buku::factory()->create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Pemrograman Web Modern',
            'penulis' => 'Zuhdi',
            'stok' => 5,
            'kondisi' => 'Baik',
        ]);
    }

    public function test_petugas_can_view_member_index_with_qr_card_button_but_no_edit_or_toggle_button(): void
    {
        $response = $this->actingAs($this->petugas)->get(route('member.index'));

        $response->assertOk();
        $response->assertSee('Data Anggota Perpustakaan');
        $response->assertSee('Kartu QR');
        $response->assertDontSee('Integritas Data & Pencegahan Kecurangan', false);
        $response->assertDontSee('Kartu Digital');
        $response->assertDontSee('Tambah Anggota Baru');
        // Tombol aksi ubah atau toggle nonaktifkan tidak boleh terlihat oleh petugas
        $response->assertDontSee(route('member.edit', $this->member->id));
        $response->assertDontSee(route('member.toggle-status', $this->member->id));
    }

    public function test_petugas_is_forbidden_from_editing_or_toggling_member_data(): void
    {
        $this->actingAs($this->petugas);

        $this->get(route('member.edit', $this->member->id))->assertForbidden();

        $this->put(route('member.update', $this->member->id), [
            'name' => 'Nama Diretas',
            'email' => 'retas@example.com',
            'noTelepon' => '089999999999',
            'alamat' => 'Alamat Baru',
            'status' => 'aktif',
        ])->assertForbidden();

        $this->patch(route('member.toggle-status', $this->member->id))->assertForbidden();

        // Verifikasi data di database tetap utuh
        $this->assertDatabaseHas('users', [
            'id' => $this->member->id,
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
        ]);
    }

    public function test_admin_can_edit_and_toggle_member_status(): void
    {
        $this->actingAs($this->admin);

        $this->get(route('member.edit', $this->member->id))->assertOk();

        $responseUpdate = $this->put(route('member.update', $this->member->id), [
            'name' => 'Budi Santoso Update',
            'email' => 'budi.update@example.com',
            'noTelepon' => '081299998888',
            'alamat' => 'Alamat Resmi Admin',
            'status' => 'aktif',
        ]);

        $responseUpdate->assertRedirect(route('member.index'));
        $this->assertDatabaseHas('users', [
            'id' => $this->member->id,
            'name' => 'Budi Santoso Update',
            'email' => 'budi.update@example.com',
        ]);

        $responseToggle = $this->patch(route('member.toggle-status', $this->member->id));
        $responseToggle->assertRedirect(route('member.index'));
        $this->assertDatabaseHas('users', [
            'id' => $this->member->id,
            'status' => 'nonaktif',
        ]);
    }

    public function test_member_index_supports_search_and_filter(): void
    {
        User::factory()->create([
            'role' => 'member',
            'status' => 'nonaktif',
            'name' => 'Siti Khadijah',
            'email' => 'siti@example.com',
        ]);

        $this->actingAs($this->petugas);

        $resSearch = $this->get(route('member.index', ['search' => 'Budi']));
        $resSearch->assertOk();
        $resSearch->assertSee('Budi Santoso');
        $resSearch->assertDontSee('Siti Khadijah');

        $resFilterStatus = $this->get(route('member.index', ['status' => 'nonaktif']));
        $resFilterStatus->assertOk();
        $resFilterStatus->assertSee('Siti Khadijah');
        $resFilterStatus->assertDontSee('Budi Santoso');
    }

    public function test_petugas_does_not_see_katalog_buku_in_sidebar(): void
    {
        $response = $this->actingAs($this->petugas)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Koleksi Buku');
        $response->assertDontSee('Katalog Buku');
    }

    public function test_petugas_can_view_buku_index_with_stock_info_but_no_crud_mutation_buttons_or_qr(): void
    {
        $response = $this->actingAs($this->petugas)->get(route('buku.index'));

        $response->assertOk();
        $response->assertSee('Koleksi & Inventaris Buku');
        $response->assertSee('Pemrograman Web Modern');
        $response->assertDontSee('Cetak QR');
        $response->assertDontSee('<th>Barcode</th>', false);
        // Petugas mode: kolom aksi dan tombol Tambah/Ubah/Hapus tidak terlihat
        $response->assertDontSee('Tambah Buku Baru');
        $response->assertDontSee(route('buku.edit', $this->buku->idBuku));
        $response->assertDontSee('title="Hapus Data Buku"', false);
    }

    public function test_admin_sees_tambah_ubah_hapus_buttons_on_buku_index_without_cetak_qr(): void
    {
        $response = $this->actingAs($this->admin)->get(route('buku.index'));

        $response->assertOk();
        $response->assertSee('Tambah Buku Baru');
        $response->assertSee(route('buku.edit', $this->buku->idBuku));
        $response->assertSee(route('buku.destroy', $this->buku->idBuku));
        $response->assertDontSee('Cetak QR');
        $response->assertDontSee('<th>Barcode</th>', false);
    }

    public function test_members_are_ordered_alphabetically(): void
    {
        User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
            'name' => 'Zara Amalia',
            'email' => 'zara@example.com',
        ]);

        User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
            'name' => 'Ahmad Dahlan',
            'email' => 'ahmad@example.com',
        ]);

        $response = $this->actingAs($this->petugas)->get(route('member.index'));
        $response->assertOk();

        // Urutan abjad: Ahmad Dahlan (A) -> Budi Santoso (B) -> Zara Amalia (Z)
        $response->assertSeeInOrder([
            'Ahmad Dahlan',
            'Budi Santoso',
            'Zara Amalia',
        ]);
    }

    public function test_petugas_visiting_katalog_sees_lihat_stok_instead_of_pinjam_buku(): void
    {
        $responseIndex = $this->actingAs($this->petugas)->get(route('katalog.index'));
        $responseIndex->assertOk();
        $responseIndex->assertSee('Lihat Stok Fisik');
        $responseIndex->assertDontSee('onclick="openBorrowModal(this)"', false);

        $responseShow = $this->actingAs($this->petugas)->get(route('katalog.show', $this->buku->idBuku));
        $responseShow->assertOk();
        $responseShow->assertSee('Lihat Stok di Koleksi Buku');
        $responseShow->assertDontSee('id="btnPinjamBuku"', false);
    }
}
