<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_sees_login_and_register_buttons_on_landing_page(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Masuk');
        $response->assertSee('Daftar Anggota');
        $response->assertDontSee('class="btn-nav-dashboard"', false);
    }

    public function test_authenticated_member_sees_role_badge_and_dashboard_shortcut(): void
    {
        $member = User::factory()->create([
            'name' => 'Budi Santoso',
            'role' => 'member',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($member)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Buka Dasbor');
        $response->assertSee('role-badge--member', false);
        $response->assertSee('Booking Saya');
        $response->assertSee('Halo, <strong>Budi Santoso</strong>', false);
        $response->assertDontSee('class="btn-nav-login"', false);
    }

    public function test_authenticated_petugas_sees_circulation_action(): void
    {
        $petugas = User::factory()->create([
            'name' => 'Siti Petugas',
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($petugas)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Buka Dasbor');
        $response->assertSee('role-badge--petugas', false);
        $response->assertSee('Meja Sirkulasi (Scan QR)');
        $response->assertDontSee('class="btn-nav-login"', false);
    }

    public function test_authenticated_admin_sees_admin_panel_action(): void
    {
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($admin)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Buka Dasbor');
        $response->assertSee('role-badge--admin', false);
        $response->assertSee('Panel Dasbor Admin');
        $response->assertDontSee('class="btn-nav-login"', false);
    }
}
