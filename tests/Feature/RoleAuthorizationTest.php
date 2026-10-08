<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $petugas;

    private User $member;

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
        ]);

        Kategori::create([
            'namaKategori' => 'Teknologi',
            'deskripsi' => 'Buku-buku komputer',
        ]);
    }

    public function test_member_cannot_access_admin_or_petugas_routes(): void
    {
        $this->actingAs($this->member);

        // Akses route admin ditolak 403
        $this->get(route('petugas.index'))->assertForbidden();
        $this->get(route('kategori.index'))->assertForbidden();
        $this->get(route('laporan.index'))->assertForbidden();
        $this->get(route('buku.create'))->assertForbidden();

        // Akses route staf sirkulasi ditolak 403
        $this->get(route('peminjaman.index'))->assertForbidden();
        $this->get(route('pengembalian.index'))->assertForbidden();
        $this->get(route('barcode.scan'))->assertForbidden();
        $this->get(route('denda.index'))->assertForbidden();
        $this->get(route('member.index'))->assertForbidden();
    }

    public function test_petugas_cannot_access_exclusive_admin_routes(): void
    {
        $this->actingAs($this->petugas);

        // Petugas dilarang kelola petugas, kategori, laporan, atau tambah buku
        $this->get(route('petugas.index'))->assertForbidden();
        $this->get(route('kategori.index'))->assertForbidden();
        $this->get(route('laporan.index'))->assertForbidden();
        $this->get(route('buku.create'))->assertForbidden();
    }

    public function test_petugas_can_access_circulation_and_operational_routes(): void
    {
        $this->actingAs($this->petugas);

        $dash = $this->get(route('dashboard'))->assertOk();
        $dash->assertSee('Scan Barcode');
        $dash->assertSee('Kelola Denda');
        $dash->assertDontSee('Data Petugas');
        $dash->assertDontSee('Kategori Buku');

        $this->get(route('peminjaman.create'))->assertOk();
        $this->get(route('peminjaman.index', ['riwayat' => 1]))->assertOk();
        $this->get(route('pengembalian.index'))->assertOk();
        $this->get(route('barcode.scan'))->assertOk();
        $this->get(route('denda.index'))->assertOk();
        $this->get(route('member.index'))->assertOk();
        $this->get(route('buku.index'))->assertOk();
    }

    public function test_admin_can_access_governance_and_management_routes(): void
    {
        $this->actingAs($this->admin);

        $dash = $this->get(route('dashboard'))->assertOk();
        $dash->assertSee('Data Petugas');
        $dash->assertSee('Kategori Buku');
        $dash->assertSee('Laporan');

        $this->get(route('petugas.index'))->assertOk();
        $this->get(route('kategori.index'))->assertOk();
        $this->get(route('laporan.index'))->assertOk();
        $this->get(route('buku.create'))->assertOk();
        $this->get(route('member.index'))->assertOk();
    }

    public function test_admin_cannot_access_operational_desk_routes(): void
    {
        $this->actingAs($this->admin);

        $this->get(route('peminjaman.create'))->assertRedirect(route('dashboard'));
        $this->get(route('pengembalian.create'))->assertRedirect(route('dashboard'));
        $this->get(route('barcode.scan'))->assertRedirect(route('dashboard'));
    }
}
