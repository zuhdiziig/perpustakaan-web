<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuEksemplar extends Model
{
    use HasFactory;

    protected $table = 'buku_eksemplar';

    protected $primaryKey = 'idEksemplar';

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($eksemplar) {
            if (empty($eksemplar->qr_token)) {
                $eksemplar->qr_token = 'bk_'.bin2hex(random_bytes(16));
            }

            if (empty($eksemplar->nomor_eksemplar) && ! empty($eksemplar->idBuku)) {
                $maxNomor = (int) static::where('idBuku', $eksemplar->idBuku)->max('nomor_eksemplar');
                $eksemplar->nomor_eksemplar = $maxNomor + 1;
            }
        });

        static::saved(function ($eksemplar) {
            if ($eksemplar->idBuku) {
                $buku = Buku::find($eksemplar->idBuku);
                $buku?->syncStok();
            }
        });

        static::deleted(function ($eksemplar) {
            if ($eksemplar->idBuku) {
                $buku = Buku::find($eksemplar->idBuku);
                $buku?->syncStok();
            }
        });
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'idBuku', 'idBuku');
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'idEksemplar', 'idEksemplar');
    }

    public function activeDetail()
    {
        return $this->hasOne(DetailPeminjaman::class, 'idEksemplar', 'idEksemplar')
            ->whereIn('statusBuku', ['Booking', 'Siap Diambil', 'Dipinjam', 'Diajukan Kembali'])
            ->latest('id');
    }

    public function getKodeDisplayAttribute(): string
    {
        if (! empty($this->kode_barcode)) {
            return $this->kode_barcode;
        }

        return sprintf('EKS-%05d', $this->idEksemplar);
    }

    public function isTersedia(): bool
    {
        return $this->status === 'Tersedia';
    }

    public function isSedangDipinjam(): bool
    {
        return in_array($this->status, ['Dipinjam', 'Dibooking', 'Diajukan Kembali'], true);
    }

    public function isDiRak(): bool
    {
        return ($this->lokasi ?? 'Rak') === 'Rak';
    }

    public function isDiGudang(): bool
    {
        return ($this->lokasi ?? 'Rak') === 'Gudang';
    }

    public function pindahKeRak(): bool
    {
        return $this->update(['lokasi' => 'Rak']);
    }

    public function pindahKeGudang(): bool
    {
        return $this->update(['lokasi' => 'Gudang']);
    }
}
