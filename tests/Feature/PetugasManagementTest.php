<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetugasManagementTest extends TestCase
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
            'name' => 'Administrator Utama',
            'email' => 'admin@booknest.test',
        ]);

        $this->petugas = User::factory()->create([
            'role' => 'petugas',
            'status' => 'aktif',
            'name' => 'Petugas Sirkulasi',
            'email' => 'petugas@booknest.test',
        ]);

        $this->member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
            'name' => 'Member Biasa',
            'email' => 'member@booknest.test',
        ]);
    }

    public function test_admin_can_delete_petugas_account_successfully(): void
    {
        $petugasToDelete = User::factory()->create([
            'role' => 'petugas',
            'status' => 'aktif',
            'name' => 'Petugas Cadangan',
            'email' => 'cadangan@booknest.test',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('petugas.destroy', $petugasToDelete->id));

        $response->assertRedirect(route('petugas.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users', [
            'id' => $petugasToDelete->id,
        ]);
    }

    public function test_admin_cannot_delete_own_account_via_petugas_destroy(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('petugas.destroy', $this->admin->id));

        $response->assertRedirect(route('petugas.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
        ]);
    }

    public function test_petugas_is_forbidden_from_deleting_petugas_account(): void
    {
        $otherPetugas = User::factory()->create([
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($this->petugas)->delete(route('petugas.destroy', $otherPetugas->id));

        $response->assertForbidden();
        $this->assertDatabaseHas('users', [
            'id' => $otherPetugas->id,
        ]);
    }

    public function test_member_is_forbidden_from_deleting_petugas_account(): void
    {
        $response = $this->actingAs($this->member)->delete(route('petugas.destroy', $this->petugas->id));

        $response->assertForbidden();
        $this->assertDatabaseHas('users', [
            'id' => $this->petugas->id,
        ]);
    }
}
