<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'alamat',
        'status',
        'noTelepon',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi jika user bertindak sebagai Member yang meminjam buku
    public function peminjamanMember()
    {
        return $this->hasMany(Peminjaman::class, 'idUserMember', 'id');
    }

    // Relasi jika user bertindak sebagai Petugas yang memproses pengembalian
    public function pengembalianPetugas()
    {
        return $this->hasMany(Pengembalian::class, 'idUserPetugas', 'id');
    }
}