<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    /**
     * Jumlah maksimal buku yang boleh dipinjam bersamaan oleh satu member.
     */
    public const BATAS_MAKSIMAL_BUKU = 7;

    /**
     * Lama masa pinjam reguler (dalam hari) sebelum buku jatuh tempo.
     */
    public const MASA_PINJAM_HARI = 30;

    /**
     * Lama masa pinjam (dalam bulan) sebelum buku jatuh tempo.
     */
    public const MASA_PINJAM_BULAN = 1;

    /**
     * Batas toleransi waktu pengambilan buku yang dibooking (dalam jam).
     */
    public const BATAS_AMBIL_BOOKING_JAM = 48;

    protected $table = 'peminjaman';

    protected $primaryKey = 'idPeminjaman';

    protected $guarded = [];

    protected $casts = [
        'tanggalPinjam' => 'date',
        'batasKembali' => 'date',
        'batasAmbil' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'idUserMember', 'id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'idUserPetugas', 'id');
    }

    public function details()
    {
        return $this->hasMany(DetailPeminjaman::class, 'idPeminjaman', 'idPeminjaman');
    }

    public function pengembalians()
    {
        return $this->hasMany(Pengembalian::class, 'idPeminjaman', 'idPeminjaman');
    }

    public function isBooking(): bool
    {
        return $this->status === 'Booking';
    }

    public function isSiapDiambil(): bool
    {
        return $this->status === 'Siap Diambil';
    }

    public function isDipinjam(): bool
    {
        return $this->status === 'Dipinjam';
    }

    public function isKadaluarsa(): bool
    {
        if (! in_array($this->status, ['Booking', 'Siap Diambil'])) {
            return false;
        }

        return $this->batasAmbil && Carbon::now()->greaterThan($this->batasAmbil);
    }

    /**
     * Kode transaksi peminjaman, contoh: PJ-20261006-00042.
     */
    protected function kodeTransaksi(): Attribute
    {
        return Attribute::get(fn (): string => sprintf(
            'PJ-%s-%05d',
            ($this->tanggalPinjam ? Carbon::parse($this->tanggalPinjam) : ($this->created_at ?? now()))->format('Ymd'),
            $this->idPeminjaman
        ));
    }

    /**
     * Kode booking peminjaman, contoh: BK-20261007-00042.
     */
    protected function kodeBookingDisplay(): Attribute
    {
        return Attribute::get(fn (): string => $this->kode_booking ?: sprintf(
            'BK-%s-%05d',
            ($this->created_at ?? now())->format('Ymd'),
            $this->idPeminjaman
        ));
    }
}
