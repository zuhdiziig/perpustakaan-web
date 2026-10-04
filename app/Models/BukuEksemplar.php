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
}
