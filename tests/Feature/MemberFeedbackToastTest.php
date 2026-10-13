<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberFeedbackToastTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_receives_a_success_toast_from_server_flash_data(): void
    {
        $member = User::factory()->create(['role' => 'member']);

        $response = $this->actingAs($member)
            ->withSession(['success' => 'Peminjaman berhasil diajukan.'])
            ->get(route('dashboard'));

        $response->assertSee('member-feedback-toasts');
        $response->assertSee('Peminjaman berhasil diajukan.');
        $response->assertSee('member-realtime-overlay');
        $this->assertSame(1, substr_count($response->getContent(), 'id="member-feedback-toasts"'));
    }

    public function test_member_receives_a_persistent_error_toast(): void
    {
        $member = User::factory()->create(['role' => 'member']);

        $response = $this->actingAs($member)
            ->withSession(['error' => 'Pembayaran belum berhasil diverifikasi.'])
            ->get(route('dashboard'));

        $response->assertSee('Pembayaran belum berhasil diverifikasi.');
        $response->assertSee('error:');
    }

    public function test_flash_content_is_serialized_without_raw_html_injection(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $untrustedMessage = '<img src=x onerror=alert(1)>';

        $response = $this->actingAs($member)
            ->withSession(['success' => $untrustedMessage])
            ->get(route('dashboard'));

        $response->assertSee('member-feedback-toasts');
        $response->assertDontSee($untrustedMessage, false);
    }

    public function test_admin_and_petugas_do_not_receive_member_toast_ui(): void
    {
        foreach (['admin', 'petugas'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $response = $this->actingAs($user)
                ->withSession(['success' => 'Pesan khusus member.'])
                ->get(route('dashboard'));

            $response->assertDontSee('member-feedback-toasts');
        }
    }
}
