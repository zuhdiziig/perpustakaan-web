<?php

namespace Tests\Feature;

use App\Models\Denda;
use App\Models\Pembayaran;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanPendapatanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_counts_only_successful_payments_in_the_current_month(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $denda = $this->createFine($admin);

        $this->createPayment($denda, 'Sukses', '1250.00', now());
        $pending = $this->createPayment($denda, 'Pending', '2000.00', now());
        $failed = $this->createPayment($denda, 'Gagal', '3000.00', now());
        $outsideMonth = $this->createPayment($denda, 'Sukses', '4500.00', now()->subMonth());

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('statistik', fn (array $statistik): bool => $statistik['dendaTerkumpulBulanIni'] === 1250.0
                && $statistik['transaksiDendaBulanIni'] === 1
            );

        $this->assertSame('Pending', $pending->fresh()->status);
        $this->assertSame('Gagal', $failed->fresh()->status);
        $this->assertSame('Sukses', $outsideMonth->fresh()->status);
    }

    public function test_report_counts_only_successful_payments_within_the_selected_payment_date_range(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $denda = $this->createFine($admin);

        $this->createPayment($denda, 'Sukses', '1250.00', now());
        $pending = $this->createPayment($denda, 'Pending', '2000.00', now());
        $failed = $this->createPayment($denda, 'Gagal', '3000.00', now());
        $outsideRange = $this->createPayment($denda, 'Sukses', '4500.00', now()->subMonth());

        $response = $this->actingAs($admin)->get(route('laporan.index', [
            'jenis' => 'denda',
            'tgl_mulai' => now()->startOfMonth()->toDateString(),
            'tgl_selesai' => now()->toDateString(),
        ]));

        $response->assertOk()
            ->assertViewHas('ringkasan', fn (array $ringkasan): bool => (float) $ringkasan['denda_lunas'] === 1250.0
            );

        $this->assertSame('Pending', $pending->fresh()->status);
        $this->assertSame('Gagal', $failed->fresh()->status);
        $this->assertSame('Sukses', $outsideRange->fresh()->status);
    }

    private function createFine(User $member): Denda
    {
        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => today(),
            'batasKembali' => today()->addDays(7),
            'status' => 'Kembali',
            'totalBuku' => 1,
        ]);

        $pengembalian = Pengembalian::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idUserPetugas' => $member->id,
            'tanggalKembali' => today(),
            'kondisiBuku' => 'Baik',
        ]);

        return Denda::create([
            'idPengembalian' => $pengembalian->idPengembalian,
            'jenisDenda' => 'Keterlambatan',
            'jumlah' => 5000,
            'status' => 'Belum Dibayar',
        ]);
    }

    private function createPayment(Denda $denda, string $status, string $nominal, mixed $tanggalBayar): Pembayaran
    {
        return Pembayaran::create([
            'idDenda' => $denda->idDenda,
            'tanggalBayar' => $tanggalBayar,
            'metode' => 'QRIS',
            'nominal' => $nominal,
            'status' => $status,
        ]);
    }
}
