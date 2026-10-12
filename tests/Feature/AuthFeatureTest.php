<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
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
        $response->assertSee('Daftar Akun Anggota');
        $response->assertSee('Daftar Sekarang');
    }

    public function test_forgot_password_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
        $response->assertSee('BOOKNEST');
        $response->assertSee('Lupa Password');
        $response->assertSee('Kirim Tautan Reset');
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

        $response->assertRedirect('/dashboard');
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
        $response->assertSessionHasErrors('email');
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

        $response->assertRedirect('/register-success');
        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.com',
            'name' => 'Budi Santoso',
            'role' => 'member',
        ]);
    }

    public function test_reset_request_without_token_does_not_change_password(): void
    {
        config(['mail.default' => 'log']);
        $user = User::factory()->create([
            'email' => 'member@example.com',
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->post('/forgot-password', [
            'email' => 'member@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/forgot-password');
        $response->assertSessionHas('status');

        $tokenlessResponse = $this->from('/reset-password/invalid-token?email=member@example.com')
            ->post('/reset-password', [
                'email' => 'member@example.com',
                'password' => 'anotherpassword123',
                'password_confirmation' => 'anotherpassword123',
            ]);

        $tokenlessResponse->assertSessionHasErrors('token');

        $user->refresh();
        $this->assertTrue(Hash::check('oldpassword', $user->password));
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
    }

    public function test_invalid_reset_token_does_not_change_password(): void
    {
        $user = User::factory()->create([
            'email' => 'member@example.com',
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->from('/reset-password/invalid-token?email=member@example.com')->post('/reset-password', [
            'token' => 'invalid-token',
            'email' => 'member@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/reset-password/invalid-token?email=member@example.com');
        $response->assertSessionHasErrors('email');
        $this->assertTrue(Hash::check('oldpassword', $user->fresh()->password));
    }

    public function test_expired_reset_token_does_not_change_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('oldpassword')]);
        $token = Password::broker()->createToken($user);
        $this->travel(61)->minutes();

        $response = $this->from('/reset-password/'.$token.'?email='.$user->email)->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/reset-password/'.$token.'?email='.$user->email);
        $response->assertSessionHasErrors('email');
        $this->assertTrue(Hash::check('oldpassword', $user->fresh()->password));
    }

    public function test_valid_reset_token_updates_hashed_password_and_allows_only_new_password_login(): void
    {
        $user = User::factory()->create([
            'email' => 'member@example.com',
            'password' => Hash::make('oldpassword'),
        ]);
        $token = Password::broker()->createToken($user);

        $this->get(route('password.reset', ['token' => $token, 'email' => $user->email]))
            ->assertSee('Atur Password Baru')
            ->assertSee('Perbarui Kata Sandi')
            ->assertSee('name="token" value="'.$token.'"', false);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success');
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
        $this->assertFalse(Hash::check('oldpassword', $user->password));
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);

        $oldPasswordResponse = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'oldpassword',
        ]);

        $oldPasswordResponse->assertRedirect('/login');
        $this->assertGuest();

        $newPasswordResponse = $this->post('/login', [
            'email' => $user->email,
            'password' => 'newpassword123',
        ]);

        $newPasswordResponse->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_reset_request_response_does_not_disclose_unknown_email(): void
    {
        config(['mail.default' => 'smtp']);
        Notification::fake();
        $user = User::factory()->create(['email' => 'known@example.com']);

        $knownResponse = $this->from('/forgot-password')->post('/forgot-password', ['email' => $user->email]);
        $knownStatus = $knownResponse->getSession()->get('status');
        $unknownResponse = $this->from('/forgot-password')->post('/forgot-password', ['email' => 'unknown@example.com']);

        $knownResponse->assertRedirect('/forgot-password');
        $unknownResponse->assertRedirect('/forgot-password');
        $this->assertSame($knownStatus, $unknownResponse->getSession()->get('status'));
        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_request_is_disabled_while_mailer_writes_to_logs(): void
    {
        config(['mail.default' => 'log']);
        $user = User::factory()->create(['email' => 'member@example.com']);

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $response->assertRedirect('/forgot-password');
        $response->assertSessionHas('status', 'Reset password sementara dinonaktifkan karena pengiriman email belum dikonfigurasi.');
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
    }

    public function test_password_reset_requests_are_rate_limited(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.42']);

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $this->from('/forgot-password')->post('/forgot-password', ['email' => 'unknown@example.com'])
                ->assertRedirect('/forgot-password');
        }

        $this->from('/forgot-password')->post('/forgot-password', ['email' => 'unknown@example.com'])
            ->assertTooManyRequests();
    }

    public function test_user_is_redirected_to_selected_book_page_after_login_with_redirect_parameter(): void
    {
        $user = User::factory()->create([
            'email' => 'member.katalog@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'member',
        ]);

        // 1. Mengakses halaman login dengan query redirect
        $loginPage = $this->get('/login?redirect=/katalog/42');
        $loginPage->assertOk();
        $loginPage->assertSee('name="redirect" value="/katalog/42"', false);

        // 2. Submit form login dengan input redirect
        $response = $this->post('/login', [
            'email' => 'member.katalog@example.com',
            'password' => 'secret123',
            'redirect' => '/katalog/42',
        ]);

        $response->assertRedirect('/katalog/42');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_is_redirected_via_session_intended_if_form_does_not_pass_redirect_explicitly(): void
    {
        $user = User::factory()->create([
            'email' => 'member.session@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'member',
        ]);

        // Kunjungi login dengan query redirect (disimpan ke session intended)
        $this->get('/login?redirect=/katalog/99');

        // Submit form login tanpa input hidden redirect
        $response = $this->post('/login', [
            'email' => 'member.session@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/katalog/99');
        $this->assertAuthenticatedAs($user);
    }

    public function test_open_redirect_is_prevented_and_falls_back_to_dashboard(): void
    {
        User::factory()->create([
            'email' => 'member.safe@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'member',
        ]);

        $response = $this->post('/login', [
            'email' => 'member.safe@example.com',
            'password' => 'secret123',
            'redirect' => 'https://malicious-external-site.com/steal-data',
        ]);

        $response->assertRedirect('/dashboard');
    }

    public function test_katalog_index_contains_modal_guest_login_button(): void
    {
        $response = $this->get('/katalog');
        $response->assertOk();
        $response->assertSee('id="modalGuestLoginBtn"', false);
    }

    public function test_guest_clicks_masuk_akun_and_is_redirected_to_book_detail_page(): void
    {
        $buku = Buku::factory()->create([
            'judul' => 'Sejarah Indonesia Modern',
            'penulis' => 'M. C. Ricklefs',
            'stok' => 3,
        ]);

        $user = User::factory()->create([
            'email' => 'akbar1@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'member',
        ]);

        // 1. Tamu membuka katalog
        $katalogRes = $this->get('/katalog');
        $katalogRes->assertOk();
        $katalogRes->assertSee('Sejarah Indonesia Modern');
        $katalogRes->assertSee('id="modalGuestLoginBtn"', false);

        // 2. Tamu klik Masuk Akun dari modal buku yang dipilih
        $loginUrl = route('login', ['redirect' => '/katalog/'.$buku->idBuku]);
        $loginRes = $this->get($loginUrl);
        $loginRes->assertOk();
        $loginRes->assertSee('value="/katalog/'.$buku->idBuku.'"', false);

        // 3. Tamu menyelesaikan proses login
        $postRes = $this->post('/login', [
            'email' => 'akbar1@gmail.com',
            'password' => 'password123',
            'redirect' => '/katalog/'.$buku->idBuku,
        ]);

        // 4. Langsung dialihkan ke halaman buku yang dipilih
        $postRes->assertRedirect('/katalog/'.$buku->idBuku);
        $this->assertAuthenticatedAs($user);

        // 5. Halaman buku terbuka dengan sukses untuk member yang sudah login
        $bookPage = $this->get('/katalog/'.$buku->idBuku);
        $bookPage->assertOk();
        $bookPage->assertSee('Sejarah Indonesia Modern');
        $bookPage->assertSee('M. C. Ricklefs');
    }
}
