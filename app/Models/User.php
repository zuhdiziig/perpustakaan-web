<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nik',
        'email',
        'password',
        'role',
        'alamat',
        'tanggal_lahir',
        'status',
        'noTelepon',
        'foto',
        'qr_token',
        'notif_jatuh_tempo',
        'notif_koleksi_baru',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'notif_jatuh_tempo' => 'boolean',
        'notif_koleksi_baru' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->qr_token)) {
                $user->qr_token = 'usr_'.bin2hex(random_bytes(16));
            }
        });
    }

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

    /**
     * Nomor keanggotaan yang ditampilkan ke member, contoh: AG-2026-00128.
     */
    protected function kodeAnggota(): Attribute
    {
        return Attribute::get(fn (): string => sprintf(
            'AG-%s-%05d',
            ($this->created_at ?? now())->format('Y'),
            $this->id
        ));
    }

    /**
     * Dua huruf inisial nama untuk avatar, contoh: Rizky Pratama -> RP.
     */
    protected function inisial(): Attribute
    {
        return Attribute::get(function (): string {
            $kata = preg_split('/\s+/', trim((string) $this->name)) ?: [];
            $inisial = collect($kata)->filter()->take(2)->map(fn (string $k): string => mb_substr($k, 0, 1))->implode('');

            return mb_strtoupper($inisial !== '' ? $inisial : 'U');
        });
    }

    /**
     * URL foto profil jika ada, atau null bila belum diunggah.
     */
    protected function fotoUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->foto ? asset('storage/'.$this->foto) : null);
    }

    /**
     * Jumlah buku yang saat ini sedang berstatus 'Dipinjam' oleh member.
     */
    public function jumlahBukuSedangDipinjam(): int
    {
        return DetailPeminjaman::whereHas('peminjaman', function ($q) {
            $q->where('idUserMember', $this->id)->where('status', 'Dipinjam');
        })->where('statusBuku', 'Dipinjam')->count();
    }

    /**
     * Jumlah seluruh buku aktif milik member (termasuk status Booking dan Siap Diambil).
     */
    public function jumlahBukuAktif(): int
    {
        return DetailPeminjaman::whereHas('peminjaman', function ($q) {
            $q->where('idUserMember', $this->id)->whereIn('status', ['Booking', 'Siap Diambil', 'Dipinjam']);
        })->whereIn('statusBuku', ['Booking', 'Siap Diambil', 'Dipinjam'])->count();
    }

    /**
     * Sisa kuota buku yang masih boleh dipinjam oleh member (maksimal 7 buku).
     */
    public function sisaKuotaPinjam(): int
    {
        return max(0, Peminjaman::BATAS_MAKSIMAL_BUKU - $this->jumlahBukuSedangDipinjam());
    }

    /**
     * Apakah member telah mencapai batas kuota maksimal 7 buku dengan status dipinjam.
     */
    public function sudahMencapaiBatasMaksimalPinjam(): bool
    {
        return $this->jumlahBukuSedangDipinjam() >= Peminjaman::BATAS_MAKSIMAL_BUKU;
    }

    /**
     * Apakah member masih diperbolehkan meminjam sejumlah buku tertentu.
     */
    public function bolehMeminjam(int $jumlah = 1): bool
    {
        return ($this->jumlahBukuSedangDipinjam() + $jumlah) <= Peminjaman::BATAS_MAKSIMAL_BUKU;
    }
}
