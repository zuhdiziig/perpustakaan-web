<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $primaryKey = 'idKategori';

    protected $guarded = [];

    public function buku()
    {
        return $this->hasMany(Buku::class, 'idKategori', 'idKategori');
    }
}
