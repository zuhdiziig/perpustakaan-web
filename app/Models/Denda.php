<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;

    protected $table = 'denda';

    protected $primaryKey = 'idDenda';

    protected $guarded = [];

    public function pengembalian()
    {
        return $this->belongsTo(Pengembalian::class, 'idPengembalian', 'idPengembalian');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'idDenda', 'idDenda');
    }
}
