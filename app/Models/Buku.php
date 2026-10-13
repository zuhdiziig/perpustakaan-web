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
                        'lokasi' => $i <= 2 ? 'Rak' : 'Gudang',
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

    public function eksemplarDiRak()
    {
        return $this->hasMany(BukuEksemplar::class, 'idBuku', 'idBuku')->where('status', 'Tersedia')->where('lokasi', 'Rak');
    }

    public function eksemplarDiGudang()
    {
        return $this->hasMany(BukuEksemplar::class, 'idBuku', 'idBuku')->where('status', 'Tersedia')->where('lokasi', 'Gudang');
    }

    public function perluRestock(): bool
    {
        return $this->eksemplarDiRak()->count() === 0 && $this->eksemplarDiGudang()->count() > 0;
    }

    public function restockKeRak(int $jumlah = 1): int
    {
        $copies = $this->eksemplarDiGudang()->take($jumlah)->get();
        foreach ($copies as $copy) {
            $copy->update(['lokasi' => 'Rak']);
        }

        return $copies->count();
    }

    public function syncStok(): int
    {
        $count = $this->eksemplar()->where('status', 'Tersedia')->count();
        $this->updateQuietly(['stok' => $count]);

        return $count;
    }

    /**
     * Tambah sejumlah eksemplar fisik baru untuk judul buku ini.
     *
     * @return array<int, BukuEksemplar>
     */
    public function tambahEksemplar(int $jumlah = 1, string $kondisi = 'Baik', ?string $lokasi = null): array
    {
        $created = [];
        $maxNomor = (int) ($this->eksemplar()->max('nomor_eksemplar') ?? 0);
        $currentInRak = $this->eksemplarDiRak()->count();

        for ($i = 1; $i <= $jumlah; $i++) {
            $lokasiFinal = $lokasi ?: (($currentInRak + $i <= 2) ? 'Rak' : 'Gudang');

            $created[] = $this->eksemplar()->create([
                'nomor_eksemplar' => $maxNomor + $i,
                'qr_token' => 'bk_'.bin2hex(random_bytes(16)),
                'kondisi' => $kondisi,
                'status' => 'Tersedia',
                'lokasi' => $lokasiFinal,
            ]);
        }
        $this->syncStok();

        return $created;
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
