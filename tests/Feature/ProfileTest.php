<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('profile.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_member_can_view_profile_page_with_complete_information(): void
    {
        $member = User::factory()->create([
            'name' => 'Rizky Pratama',
            'email' => 'rizky.pratama@email.com',
            'nik' => '3273011505980004',
            'noTelepon' => '081234567890',
            'alamat' => 'Jl. Melati No. 18, Bandung',
            'tanggal_lahir' => '1998-05-15',
            'status' => 'aktif',
            'role' => 'member',
        ]);

        $response = $this->actingAs($member)->get(route('profile.edit'));

        $response->assertOk();
        $response->assertViewIs('profile.edit');
        $response->assertSee('Profil Saya');
        $response->assertSee('Kelola data pribadi, keanggotaan, dan keamanan akunmu.');
        $response->assertSee('Rizky Pratama');
        $response->assertSee($member->kode_anggota);
        $response->assertSee('Anggota aktif');
        $response->assertSee('Data pribadi');
        $response->assertSee('Keamanan akun');
        $response->assertSee('Preferensi notifikasi');
        $response->assertSee('Privasi data anggota');
        $response->assertSee('Ubah Foto');
        $response->assertSee('Simpan Perubahan');
        $response->assertSee('Ubah Password');
    }

    public function test_member_can_update_personal_data(): void
    {
        $member = User::factory()->create([
            'name' => 'Nama Lama',
            'email' => 'lama@email.com',
            'noTelepon' => '08111111111',
            'alamat' => 'Alamat Lama',
            'nik' => null,
        ]);

        $response = $this->actingAs($member)->put(route('profile.update'), [
            'name' => 'Rizky Pratama Baru',
            'email' => 'rizky.baru@email.com',
            'noTelepon' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 45',
            'nik' => '3273011505980004',
            'tanggal_lahir' => '1998-05-15',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $member->refresh();
        $this->assertSame('Rizky Pratama Baru', $member->name);
        $this->assertSame('rizky.baru@email.com', $member->email);
        $this->assertSame('081234567890', $member->noTelepon);
        $this->assertSame('Jl. Merdeka No. 45', $member->alamat);
        $this->assertSame('3273011505980004', $member->nik);
        $this->assertSame('1998-05-15', $member->tanggal_lahir?->format('Y-m-d'));
    }

    public function test_member_cannot_arbitrarily_change_verified_nik(): void
    {
        $member = User::factory()->create([
            'nik' => '3273011505980004',
            'role' => 'member',
        ]);

        $response = $this->actingAs($member)->put(route('profile.update'), [
            'name' => $member->name,
            'email' => $member->email,
            'nik' => '9999999999999999',
        ]);

        $response->assertSessionHasErrors('nik');

        $member->refresh();
        $this->assertSame('3273011505980004', $member->nik);
    }

    public function test_member_can_change_password_with_valid_current_password(): void
    {
        $member = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($member)->put(route('profile.password'), [
            'current_password' => 'password123',
            'password' => 'passwordBaru2026',
            'password_confirmation' => 'passwordBaru2026',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $member->refresh();
        $this->assertTrue(Hash::check('passwordBaru2026', $member->password));
    }

    public function test_member_cannot_change_password_with_wrong_current_password(): void
    {
        $member = User::factory()->create([
            'password' => Hash::make('passwordAsli123'),
        ]);

        $response = $this->actingAs($member)->put(route('profile.password'), [
            'current_password' => 'passwordSalah',
            'password' => 'passwordBaru2026',
            'password_confirmation' => 'passwordBaru2026',
        ]);

        $response->assertSessionHasErrors('current_password');

        $member->refresh();
        $this->assertTrue(Hash::check('passwordAsli123', $member->password));
    }

    public function test_member_can_upload_and_delete_profile_photo(): void
    {
        Storage::fake('public');

        $member = User::factory()->create(['foto' => null]);
        $file = UploadedFile::fake()->image('avatar.jpg', 300, 300);

        // 1. Upload foto
        $uploadResponse = $this->actingAs($member)->post(route('profile.foto'), [
            'foto' => $file,
        ]);

        $uploadResponse->assertRedirect(route('profile.edit'));
        $member->refresh();

        $this->assertNotNull($member->foto);
        Storage::disk('public')->assertExists($member->foto);

        // 2. Hapus foto
        $deleteResponse = $this->actingAs($member)->delete(route('profile.foto.destroy'));
        $deleteResponse->assertRedirect(route('profile.edit'));

        $member->refresh();
        $this->assertNull($member->foto);
    }

    public function test_member_can_update_notification_preferences(): void
    {
        $member = User::factory()->create([
            'notif_jatuh_tempo' => true,
            'notif_koleksi_baru' => true,
        ]);

        $response = $this->actingAs($member)->putJson(route('profile.notifikasi'), [
            'notif_jatuh_tempo' => 0,
            'notif_koleksi_baru' => 1,
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $member->refresh();
        $this->assertFalse($member->notif_jatuh_tempo);
        $this->assertTrue($member->notif_koleksi_baru);
    }
}
