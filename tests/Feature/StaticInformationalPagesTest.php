<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaticInformationalPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_all_informational_pages(): void
    {
        $responsePanduan = $this->get(route('panduan-peminjaman'));
        $responsePanduan->assertStatus(200);
        $responsePanduan->assertSee('Panduan Peminjaman');
        $responsePanduan->assertSee('Cara Meminjam Buku Fisik');

        $responsePrivasi = $this->get(route('kebijakan-privasi'));
        $responsePrivasi->assertStatus(200);
        $responsePrivasi->assertSee('Kebijakan Privasi');
        $responsePrivasi->assertSee('Perlindungan Identitas');

        $responseSyarat = $this->get(route('syarat-layanan'));
        $responseSyarat->assertStatus(200);
        $responseSyarat->assertSee('Syarat Layanan');
        $responseSyarat->assertSee('Tata Tertib');
    }

    public function test_authenticated_member_sees_member_sidebar_on_informational_pages(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($member)->get(route('panduan-peminjaman'));
        $response->assertStatus(200);
        $response->assertSee('sidebar');

        $responsePrivasi = $this->actingAs($member)->get(route('kebijakan-privasi'));
        $responsePrivasi->assertStatus(200);
        $responsePrivasi->assertSee('sidebar');

        $responseSyarat = $this->actingAs($member)->get(route('syarat-layanan'));
        $responseSyarat->assertStatus(200);
        $responseSyarat->assertSee('sidebar');
    }

    public function test_authenticated_petugas_and_admin_can_access_informational_pages(): void
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $responsePetugas = $this->actingAs($petugas)->get(route('panduan-peminjaman'));
        $responsePetugas->assertStatus(200);

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $responseAdmin = $this->actingAs($admin)->get(route('syarat-layanan'));
        $responseAdmin->assertStatus(200);
    }

    public function test_footer_contains_links_to_informational_pages(): void
    {
        $responseWelcome = $this->get(route('home'));
        $responseWelcome->assertSee(route('panduan-peminjaman'));
        $responseWelcome->assertSee(route('kebijakan-privasi'));
        $responseWelcome->assertSee(route('syarat-layanan'));

        $responseTentang = $this->get(route('tentang'));
        $responseTentang->assertSee(route('panduan-peminjaman'));
        $responseTentang->assertSee(route('kebijakan-privasi'));
        $responseTentang->assertSee(route('syarat-layanan'));

        $responseKatalog = $this->get(route('katalog.index'));
        $responseKatalog->assertSee(route('panduan-peminjaman'));
        $responseKatalog->assertSee(route('kebijakan-privasi'));
        $responseKatalog->assertSee(route('syarat-layanan'));
    }
}
