<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';

    protected $primaryKey = 'idPengembalian';

    protected $guarded = [];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'idPeminjaman', 'idPeminjaman');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'idUserPetugas', 'id');
    }

    public function denda()
    {
        return $this->hasOne(Denda::class, 'idPengembalian', 'idPengembalian');
    }
}
