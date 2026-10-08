<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjaman';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'idPeminjaman', 'idPeminjaman');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'idBuku', 'idBuku');
    }

    public function eksemplar()
    {
        return $this->belongsTo(BukuEksemplar::class, 'idEksemplar', 'idEksemplar');
    }

    public function denda()
    {
        return $this->belongsTo(Denda::class, 'id_denda', 'idDenda');
    }
}
