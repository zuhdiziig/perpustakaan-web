<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\User;
use App\Notifications\BookingReadyNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingApprovalNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_approval_stores_one_notification_for_the_loan_owner(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas', 'status' => 'aktif']);
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);
        $peminjaman = $this->createBooking($member, 'Bumi Manusia');

        $response = $this->actingAs($petugas)
            ->post(route('petugas.booking.siapkan', $peminjaman->idPeminjaman));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSame('Siap Diambil', $peminjaman->fresh()->status);
        $this->assertDatabaseCount('notifications', 1);
        $this->assertDatabaseHas('notifications', [
            'type' => BookingReadyNotification::class,
            'notifiable_type' => User::class,
            'notifiable_id' => $member->id,
            'read_at' => null,
        ]);

        $notification = $member->notifications()->firstOrFail();
        $this->assertSame('Peminjaman disetujui', $notification->data['title']);
        $this->assertSame('Siap Diambil', $notification->data['status']);
        $this->assertSame(['Bumi Manusia'], $notification->data['books']);
    }

    public function test_member_polling_only_returns_approval_notifications_for_the_authenticated_member(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas', 'status' => 'aktif']);
        $owner = User::factory()->create(['role' => 'member', 'status' => 'aktif']);
        $otherMember = User::factory()->create(['role' => 'member', 'status' => 'aktif']);
        $peminjaman = $this->createBooking($owner, 'Laskar Pelangi');

        $this->actingAs($petugas)->post(route('petugas.booking.siapkan', $peminjaman->idPeminjaman));

        $ownerResponse = $this->actingAs($owner)->getJson(route('api.member.status-sirkulasi-terbaru'));
        $ownerResponse->assertOk()
            ->assertJsonPath('notifications.0.title', 'Peminjaman disetujui')
            ->assertJsonPath('notifications.0.status', 'Siap Diambil')
            ->assertJsonPath('notifications.0.books.0', 'Laskar Pelangi');

        $otherResponse = $this->actingAs($otherMember)->getJson(route('api.member.status-sirkulasi-terbaru'));
        $otherResponse->assertOk()->assertJsonPath('notifications', []);

        $notificationId = $owner->notifications()->firstOrFail()->id;
        $this->actingAs($otherMember)
            ->postJson(route('api.member.notifikasi-peminjaman.dibaca', $notificationId))
            ->assertNotFound();

        $this->assertNull($owner->notifications()->firstOrFail()->read_at);

        $this->actingAs($owner)
            ->postJson(route('api.member.notifikasi-peminjaman.dibaca', $notificationId))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertNotNull($owner->notifications()->firstOrFail()->read_at);
        $this->getJson(route('api.member.status-sirkulasi-terbaru'))
            ->assertOk()
            ->assertJsonPath('notifications', []);
    }

    public function test_failed_or_repeated_approval_does_not_create_duplicate_notifications(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas', 'status' => 'aktif']);
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);
        $peminjaman = $this->createBooking($member, 'Cantik Itu Luka');

        $this->actingAs($petugas)
            ->post(route('petugas.booking.siapkan', $peminjaman->idPeminjaman))
            ->assertSessionHas('success');

        $this->post(route('petugas.booking.siapkan', $peminjaman->idPeminjaman))
            ->assertSessionHas('error');

        $this->assertSame('Siap Diambil', $peminjaman->fresh()->status);
        $this->assertDatabaseCount('notifications', 1);
    }

    public function test_approval_request_for_non_booking_status_does_not_notify_member(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas', 'status' => 'aktif']);
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);
        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => today(),
            'batasKembali' => today()->addDays(30),
            'status' => 'Siap Diambil',
            'totalBuku' => 0,
        ]);

        $this->actingAs($petugas)
            ->post(route('petugas.booking.siapkan', $peminjaman->idPeminjaman))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('notifications', 0);
        $this->assertSame('Siap Diambil', $peminjaman->fresh()->status);
    }

    public function test_admin_can_approve_a_booking_and_notify_its_owner(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $member = User::factory()->create(['role' => 'member', 'status' => 'aktif']);
        $peminjaman = $this->createBooking($member, 'Negeri 5 Menara');

        $this->actingAs($admin)
            ->post(route('petugas.booking.siapkan', $peminjaman->idPeminjaman))
            ->assertSessionHas('success');

        $this->assertSame('Siap Diambil', $peminjaman->fresh()->status);
        $this->assertDatabaseHas('notifications', [
            'type' => BookingReadyNotification::class,
            'notifiable_id' => $member->id,
        ]);
    }

    private function createBooking(User $member, string $bookTitle): Peminjaman
    {
        $buku = Buku::factory()->create(['judul' => $bookTitle, 'stok' => 1]);
        $eksemplar = $buku->eksemplar()->firstOrFail();
        $eksemplar->update(['status' => 'Dibooking']);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => today(),
            'batasKembali' => today()->addDays(30),
            'status' => 'Booking',
            'totalBuku' => 1,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $eksemplar->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Booking',
        ]);

        return $peminjaman;
    }
}
