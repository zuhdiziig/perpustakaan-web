<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('BOOKNEST');
        $response->assertSee('Selamat datang kembali');
        $response->assertSee('Ingat Saya');
        $response->assertSee('Lupa Password?');
    }

    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('BOOKNEST');
        $response->assertSee('Daftar Akun Baru');
        $response->assertSee('Daftar Sekarang');
    }

    public function test_forgot_password_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
        $response->assertSee('BOOKNEST');
        $response->assertSee('Lupa Password');
        $response->assertSee('Perbarui Kata Sandi');
    }

    public function test_user_can_login_with_remember_me(): void
    {
        $user = User::factory()->create([
            'email' => 'reader@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'member',
        ]);

        $response = $this->post('/login', [
            'email' => 'reader@example.com',
            'password' => 'secret123',
            'remember' => 'on',
        ]);

        $response->assertRedirect('/katalog');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'reader@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'reader@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('login_gagal');
        $this->assertGuest();
    }

    public function test_new_user_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'noTelepon' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 10 Jakarta',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.com',
            'name' => 'Budi Santoso',
            'role' => 'member',
        ]);
    }

    public function test_user_can_reset_password_with_valid_phone_verification(): void
    {
        $user = User::factory()->create([
            'email' => 'member@example.com',
            'noTelepon' => '081234567890',
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->post('/forgot-password', [
            'email' => 'member@example.com',
            'noTelepon' => '081234567890',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    public function test_user_cannot_reset_password_with_wrong_phone_verification(): void
    {
        User::factory()->create([
            'email' => 'member@example.com',
            'noTelepon' => '081234567890',
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->from('/forgot-password')->post('/forgot-password', [
            'email' => 'member@example.com',
            'noTelepon' => '089999999999',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/forgot-password');
        $response->assertSessionHasErrors('noTelepon');
    }
}
