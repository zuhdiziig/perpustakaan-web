<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';
    protected $primaryKey = 'idBuku';
    protected $guarded = [];

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
}
