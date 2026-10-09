<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Denda;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\Pembayaran;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebDendaTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_denda_page(): void
    {
        $response = $this->get(route('denda.saya'));

        $response->assertRedirect(route('login'));
    }

    public function test_member_can_view_denda_page_with_unpaid_fine(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
            'name' => 'Rizky Pratama',
        ]);

        $kategori = Kategori::create([
            'namaKategori' => 'Umum',
            'deskripsi' => 'Pengembangan diri',
        ]);

        $buku = Buku::create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Filosofi Teras',
            'penulis' => 'Henry Manampiring',
            'penerbit' => 'Kompas',
            'tahunTerbit' => 2019,
            'harga' => 98000,
            'stok' => 5,
            'kondisi' => 'Baik',
        ]);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => '2026-09-14',
            'batasKembali' => '2026-09-28',
            'status' => 'Kembali',
            'totalBuku' => 1,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $buku->eksemplar->first()->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Kembali',
        ]);

        $pengembalian = Pengembalian::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idUserPetugas' => $member->id,
            'tanggalKembali' => '2026-10-03',
            'kondisiBuku' => 'Baik',
        ]);

        $denda = Denda::create([
            'idPengembalian' => $pengembalian->idPengembalian,
            'jenisDenda' => 'Keterlambatan',
            'jumlah' => 5000,
            'status' => 'Belum Dibayar',
        ]);

        $response = $this->actingAs($member)->get(route('denda.saya'));

        $response->assertOk();
        $response->assertViewIs('denda.member_index');
        $response->assertSee('Denda Peminjaman');
        $response->assertSee('Rincian keterlambatan dan pembayaran, transparan dalam satu tempat.');
        $response->assertSee('Total belum dibayar');
        $response->assertSee('Rp5.000');
        $response->assertSee('Keterlambatan');
        $response->assertSee('5 hari');
        $response->assertSee('Tarif per hari');
        $response->assertSee('Rp1.000');
        $response->assertSee('Filosofi Teras');
        $response->assertSee('Belum Lunas');
        $response->assertSee('Rincian keterlambatan');
        $response->assertSee('Total pembayaran');
        $response->assertSee('Bayar Denda');
    }

    public function test_member_can_view_denda_page_when_no_fines_exist(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
            'name' => 'Member Bersih',
        ]);

        $response = $this->actingAs($member)->get(route('denda.saya'));

        $response->assertOk();
        $response->assertViewIs('denda.member_index');
        $response->assertSee('Denda Peminjaman');
        $response->assertSee('Rp0');
        $response->assertSee('Tidak ada tunggakan');
        $response->assertSee('0 hari');
        $response->assertSee('Rp1.000');
    }

    public function test_member_can_view_qris_pending_page(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'aktif',
            'name' => 'Rizky Pratama',
        ]);

        $kategori = Kategori::create([
            'namaKategori' => 'Umum',
            'deskripsi' => 'Pengembangan diri',
        ]);

        $buku = Buku::create([
            'idKategori' => $kategori->idKategori,
            'judul' => 'Filosofi Teras',
            'penulis' => 'Henry Manampiring',
            'penerbit' => 'Kompas',
            'tahunTerbit' => 2019,
            'harga' => 98000,
            'stok' => 5,
            'kondisi' => 'Baik',
        ]);

        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => '2026-09-14',
            'batasKembali' => '2026-09-28',
            'status' => 'Kembali',
            'totalBuku' => 1,
        ]);

        DetailPeminjaman::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idBuku' => $buku->idBuku,
            'idEksemplar' => $buku->eksemplar->first()->idEksemplar,
            'jumlah' => 1,
            'statusBuku' => 'Kembali',
        ]);

        $pengembalian = Pengembalian::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idUserPetugas' => $member->id,
            'tanggalKembali' => '2026-10-03',
            'kondisiBuku' => 'Baik',
        ]);

        $denda = Denda::create([
            'idPengembalian' => $pengembalian->idPengembalian,
            'jenisDenda' => 'Keterlambatan',
            'jumlah' => 5000,
            'status' => 'Belum Dibayar',
        ]);

        $response = $this->actingAs($member)->get(route('bayar.qr', $denda->idDenda));

        $response->assertOk();
        $response->assertViewIs('pembayaran.bayar_qr');
        $response->assertSee('Pembayaran QRIS');
        $response->assertSee('Menunggu Pembayaran');
        $response->assertSee('Cek Status Pembayaran');
        $response->assertSee('Rincian pembayaran');
        $response->assertSee('Cara membayar');
        $response->assertSee('Kembali ke Denda');
        $response->assertDontSee('simulasi_status');
    }

    public function test_client_simulated_success_does_not_change_payment_or_fine_status(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $denda = $this->createDendaForMember($member);
        $pembayaran = $this->createPembayaran($denda);

        $response = $this->actingAs($member)
            ->from(route('bayar.qr', $denda->idDenda))
            ->post(route('bayar.proses_qr', $pembayaran->idPembayaran), [
                'simulasi_status' => 'berhasil',
            ]);

        $response->assertRedirect(route('bayar.qr', $denda->idDenda));
        $response->assertSessionHas('error', 'Pembayaran belum dapat diverifikasi karena integrasi payment gateway belum tersedia. Status pembayaran tidak diubah.');
        $this->assertSame('Pending', $pembayaran->fresh()->status);
        $this->assertSame('Belum Dibayar', $denda->fresh()->status);
    }

    public function test_missing_status_parameter_does_not_confirm_payment(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $denda = $this->createDendaForMember($member);
        $pembayaran = $this->createPembayaran($denda);

        $response = $this->actingAs($member)
            ->from(route('bayar.qr', $denda->idDenda))
            ->post(route('bayar.proses_qr', $pembayaran->idPembayaran));

        $response->assertRedirect(route('bayar.qr', $denda->idDenda));
        $this->assertSame('Pending', $pembayaran->fresh()->status);
        $this->assertSame('Belum Dibayar', $denda->fresh()->status);
    }

    public function test_member_cannot_access_another_members_fine_or_payment_endpoints(): void
    {
        $owner = User::factory()->create(['role' => 'member']);
        $otherMember = User::factory()->create(['role' => 'member']);
        $denda = $this->createDendaForMember($owner);
        $pembayaran = $this->createPembayaran($denda);

        $this->actingAs($otherMember)->get(route('bayar.qr', $denda->idDenda))->assertNotFound();
        $this->post(route('bayar.proses_qr', $pembayaran->idPembayaran), ['simulasi_status' => 'berhasil'])->assertNotFound();
        $this->get(route('bayar.sukses', $pembayaran->idPembayaran))->assertNotFound();
        $this->get(route('pembayaran.nota', $pembayaran->idPembayaran))->assertNotFound();

        $this->assertSame('Pending', $pembayaran->fresh()->status);
        $this->assertSame('Belum Dibayar', $denda->fresh()->status);
        $this->assertDatabaseCount('pembayaran', 1);
    }

    public function test_pending_payment_cannot_render_success_or_nota(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $denda = $this->createDendaForMember($member);
        $pembayaran = $this->createPembayaran($denda);

        $this->actingAs($member)->get(route('bayar.sukses', $pembayaran->idPembayaran))->assertNotFound();
        $this->get(route('pembayaran.nota', $pembayaran->idPembayaran))->assertNotFound();
    }

    public function test_processing_a_confirmed_payment_is_idempotent(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $denda = $this->createDendaForMember($member, 'Lunas');
        $pembayaran = $this->createPembayaran($denda, 'Sukses');
        $tanggalBayar = (string) $pembayaran->tanggalBayar;

        $response = $this->actingAs($member)
            ->post(route('bayar.proses_qr', $pembayaran->idPembayaran), ['simulasi_status' => 'berhasil']);

        $response->assertRedirect(route('bayar.sukses', $pembayaran->idPembayaran));
        $this->assertSame('Sukses', $pembayaran->fresh()->status);
        $this->assertSame('Lunas', $denda->fresh()->status);
        $this->assertSame($tanggalBayar, (string) $pembayaran->fresh()->tanggalBayar);
    }

    public function test_opening_paid_fine_does_not_create_another_pending_payment(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $denda = $this->createDendaForMember($member, 'Lunas');
        $pembayaran = $this->createPembayaran($denda, 'Sukses');

        $response = $this->actingAs($member)->get(route('bayar.qr', $denda->idDenda));

        $response->assertRedirect(route('bayar.sukses', $pembayaran->idPembayaran));
        $this->assertDatabaseCount('pembayaran', 1);
    }

    public function test_amount_mismatch_cannot_be_processed_or_rendered_as_paid(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $denda = $this->createDendaForMember($member);
        $pembayaran = $this->createPembayaran($denda, 'Pending', '4000.00');

        $response = $this->actingAs($member)
            ->from(route('bayar.qr', $denda->idDenda))
            ->post(route('bayar.proses_qr', $pembayaran->idPembayaran));

        $response->assertRedirect(route('bayar.qr', $denda->idDenda));
        $response->assertSessionHas('error', 'Status atau nominal transaksi tidak valid. Pembayaran tidak diubah.');
        $this->actingAs($member)->get(route('bayar.sukses', $pembayaran->idPembayaran))->assertNotFound();
        $this->assertSame('Pending', $pembayaran->fresh()->status);
        $this->assertSame('Belum Dibayar', $denda->fresh()->status);
    }

    public function test_staff_verification_does_not_use_mock_gateway_success(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $staff = User::factory()->create(['role' => 'petugas']);
        $denda = $this->createDendaForMember($member);
        $pembayaran = $this->createPembayaran($denda);

        $response = $this->actingAs($staff)
            ->from(route('pembayaran.index'))
            ->post(route('pembayaran.verifikasi', $pembayaran->idPembayaran));

        $response->assertRedirect(route('pembayaran.index'));
        $response->assertSessionHas('error', 'Pembayaran belum dapat diverifikasi karena integrasi payment gateway belum tersedia. Status pembayaran tidak diubah.');
        $this->assertSame('Pending', $pembayaran->fresh()->status);
        $this->assertSame('Belum Dibayar', $denda->fresh()->status);
    }

    public function test_member_can_view_success_and_receipt_for_confirmed_payment(): void
    {
        $member = User::factory()->create(['role' => 'member', 'name' => 'Rizky Pratama']);
        $denda = $this->createDendaForMember($member, 'Lunas');
        $pembayaran = $this->createPembayaran($denda, 'Sukses');

        $successResponse = $this->actingAs($member)->get(route('bayar.sukses', $pembayaran->idPembayaran));
        $successResponse->assertOk();
        $successResponse->assertSee('Status Pembayaran');
        $successResponse->assertSee('TERCATAT SUKSES');
        $successResponse->assertSee('belum diverifikasi oleh gateway');
        $successResponse->assertDontSee('berhasil diterima melalui QRIS');

        $notaResponse = $this->get(route('pembayaran.nota', $pembayaran->idPembayaran));
        $notaResponse->assertOk();
        $notaResponse->assertSee('Nota Pembayaran');
        $notaResponse->assertSee('TERCATAT SUKSES');
        $notaResponse->assertSee('penerimaan dana belum diverifikasi oleh gateway');
    }

    private function createDendaForMember(User $member, string $status = 'Belum Dibayar', string $jumlah = '5000.00'): Denda
    {
        $peminjaman = Peminjaman::create([
            'idUserMember' => $member->id,
            'tanggalPinjam' => '2026-09-14',
            'batasKembali' => '2026-09-28',
            'status' => 'Kembali',
            'totalBuku' => 1,
        ]);

        $pengembalian = Pengembalian::create([
            'idPeminjaman' => $peminjaman->idPeminjaman,
            'idUserPetugas' => $member->id,
            'tanggalKembali' => '2026-10-03',
            'kondisiBuku' => 'Baik',
        ]);

        return Denda::create([
            'idPengembalian' => $pengembalian->idPengembalian,
            'jenisDenda' => 'Keterlambatan',
            'jumlah' => $jumlah,
            'status' => $status,
        ]);
    }

    private function createPembayaran(Denda $denda, string $status = 'Pending', ?string $nominal = null): Pembayaran
    {
        return $denda->pembayaran()->create([
            'tanggalBayar' => now(),
            'metode' => 'QRIS',
            'nominal' => $nominal ?? (string) $denda->jumlah,
            'status' => $status,
        ]);
    }
}
