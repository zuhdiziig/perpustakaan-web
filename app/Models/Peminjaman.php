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
    public const MASA_PINJAM_HARI = 14;

    /**
     * Lama masa pinjam (dalam bulan) sebelum buku jatuh tempo.
     */
    public const MASA_PINJAM_BULAN = 1;

    protected $table = 'peminjaman';

    protected $primaryKey = 'idPeminjaman';

    protected $guarded = [];

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
}
