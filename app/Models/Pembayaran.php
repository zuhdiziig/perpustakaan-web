<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'idPembayaran';
    protected $guarded = [];

    public function denda()
    {
        return $this->belongsTo(Denda::class, 'idDenda', 'idDenda');
    }
}