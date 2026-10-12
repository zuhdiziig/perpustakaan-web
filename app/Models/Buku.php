<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $primaryKey = 'idBuku';

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($buku) {
            if (empty($buku->qr_token)) {
                $buku->qr_token = 'bk_title_'.bin2hex(random_bytes(16));
            }
        });

        static::created(function ($buku) {
            $stok = (int) $buku->stok;
            if ($stok > 0 && $buku->eksemplar()->count() === 0) {
                for ($i = 1; $i <= $stok; $i++) {
                    $buku->eksemplar()->create([
                        'nomor_eksemplar' => $i,
                        'qr_token' => 'bk_'.bin2hex(random_bytes(16)),
                        'kondisi' => $buku->kondisi ?? 'Baik',
                        'status' => 'Tersedia',
                    ]);
                }
            }
        });
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'idKategori', 'idKategori');
    }

    public function barcode()
    {
        return $this->hasOne(Barcode::class, 'idBuku', 'idBuku');
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'idBuku', 'idBuku');
    }

    public function eksemplar()
    {
        return $this->hasMany(BukuEksemplar::class, 'idBuku', 'idBuku');
    }

    public function eksemplarTersedia()
    {
        return $this->hasMany(BukuEksemplar::class, 'idBuku', 'idBuku')->where('status', 'Tersedia');
    }

    public function syncStok(): int
    {
        $count = $this->eksemplar()->where('status', 'Tersedia')->count();
        $this->update(['stok' => $count]);

        return $count;
    }

    public function getCoverUrlAttribute(): ?string
    {
        if (empty($this->cover)) {
            return null;
        }

        if (Str::startsWith($this->cover, ['http://', 'https://'])) {
            return $this->cover;
        }

        return asset('storage/'.$this->cover);
    }
}
