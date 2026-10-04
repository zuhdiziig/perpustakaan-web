<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

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
}
