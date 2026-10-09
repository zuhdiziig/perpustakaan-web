<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingReadyNotification extends Notification
{
    use Queueable;

    /** @param array<int, string> $bookTitles */
    public function __construct(
        public int $peminjamanId,
        public string $bookingCode,
        public array $bookTitles
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /** @return array{event: string, title: string, status: string, peminjaman_id: int, kode_booking: string, books: array<int, string>} */
    public function toDatabase(object $notifiable): array
    {
        return [
            'event' => 'booking_ready',
            'title' => 'Peminjaman disetujui',
            'status' => 'Siap Diambil',
            'peminjaman_id' => $this->peminjamanId,
            'kode_booking' => $this->bookingCode,
            'books' => $this->bookTitles,
        ];
    }
}
