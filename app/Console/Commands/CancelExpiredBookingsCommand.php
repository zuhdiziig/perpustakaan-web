<?php

namespace App\Console\Commands;

use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CancelExpiredBookingsCommand extends Command
{
    /**
     * Nama dan signature console command.
     *
     * @var string
     */
    protected $signature = 'booking:cancel-expired';

    /**
     * Deskripsi console command.
     *
     * @var string
     */
    protected $description = 'Batalkan otomatis antrean booking yang telah melewati batas ambil (48 jam) dan kembalikan stok eksemplar.';

    /**
     * Eksekusi perintah console.
     */
    public function handle(): int
    {
        $now = Carbon::now();
        $expiredBookings = Peminjaman::with(['details.eksemplar', 'details.buku'])
            ->whereIn('status', ['Booking', 'Siap Diambil'])
            ->whereNotNull('batasAmbil')
            ->where('batasAmbil', '<', $now)
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->info('Tidak ada antrean booking yang kadaluarsa.');

            return self::SUCCESS;
        }

        $count = 0;
        DB::transaction(function () use ($expiredBookings, &$count) {
            foreach ($expiredBookings as $peminjaman) {
                foreach ($peminjaman->details as $detail) {
                    if ($detail->eksemplar && $detail->eksemplar->status === 'Dibooking') {
                        $detail->eksemplar->update(['status' => 'Tersedia']);
                    }
                    if ($detail->buku) {
                        $detail->buku->syncStok();
                    }
                    $detail->update(['statusBuku' => 'Dibatalkan']);
                }

                $peminjaman->update([
                    'status' => 'Dibatalkan',
                    'catatan_petugas' => 'Dibatalkan otomatis oleh sistem karena melewati batas toleransi pengambilan (48 jam).',
                ]);

                $count++;
            }
        });

        $this->info("Berhasil membatalkan {$count} transaksi booking kadaluarsa.");

        return self::SUCCESS;
    }
}
