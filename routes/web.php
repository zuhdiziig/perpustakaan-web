<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KeranjangBookingController;
use App\Http\Controllers\KondisiBukuController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. RUTE ROOT / LANDING PAGE PUBLIK
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/tentang', function () {
    return view('tentang');
})->name('tentang');

/*
|--------------------------------------------------------------------------
| KATALOG BUKU PUBLIK (Bisa diakses Tamu & Semua Role)
|--------------------------------------------------------------------------
*/

Route::get('/katalog', [KatalogController::class, 'index'])
    ->name('katalog.index');

Route::get('/katalog/{id}', [KatalogController::class, 'show'])
    ->name('katalog.show');

/*
|--------------------------------------------------------------------------
| 2. AREA TAMU (GUEST)
|--------------------------------------------------------------------------
| Hanya bisa diakses sebelum login.
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/register-success', [AuthController::class, 'registerSuccess'])
        ->name('register.success');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])
        ->name('password.request');

    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
        ->name('password.update');
});

/*
|--------------------------------------------------------------------------
| 3. AREA SISTEM (AUTH)
|--------------------------------------------------------------------------
| Khusus pengguna yang sudah login.
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTH / LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    /*
    |--------------------------------------------------------------------------
    | DASBOR (berbeda untuk setiap role: admin, petugas, member)
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', DashboardController::class)
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | PROFIL PENGGUNA (Semua Role Terautentikasi)
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');

    Route::post('/profile/foto', [ProfileController::class, 'updateFoto'])
        ->name('profile.foto');

    Route::delete('/profile/foto', [ProfileController::class, 'destroyFoto'])
        ->name('profile.foto.destroy');

    Route::put('/profile/notifikasi', [ProfileController::class, 'updateNotifikasi'])
        ->name('profile.notifikasi');

    /*
    |--------------------------------------------------------------------------
    | KARTU / QR MEMBER
    |--------------------------------------------------------------------------
    */

    Route::get('/member/{id}/cetak-qr', [QrController::class, 'cetakMember'])
        ->name('member.cetak-qr');

    /*
    |--------------------------------------------------------------------------
    | LAYANAN MANDIRI MEMBER (Role: Member)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:member')->group(function () {
        // Keranjang Booking Buku Simultan (Khusus Member)
        Route::get('/keranjang-booking', [KeranjangBookingController::class, 'index'])
            ->name('keranjang.index');
        Route::post('/keranjang-booking/tambah/{idBuku}', [KeranjangBookingController::class, 'tambah'])
            ->name('keranjang.tambah');
        Route::post('/keranjang-booking/hapus/{idBuku}', [KeranjangBookingController::class, 'hapus'])
            ->name('keranjang.hapus');
        Route::post('/keranjang-booking/kosongkan', [KeranjangBookingController::class, 'kosongkan'])
            ->name('keranjang.kosongkan');
        Route::post('/keranjang-booking/checkout', [KeranjangBookingController::class, 'checkout'])
            ->name('keranjang.checkout');

        Route::get('/riwayat', [RiwayatController::class, 'index'])
            ->name('riwayat.index');

        Route::get('/peminjaman/konfirmasi/{id}', [PeminjamanController::class, 'konfirmasiMember'])
            ->name('peminjaman.konfirmasi');

        Route::post('/peminjaman/ajukan/{id}', [PeminjamanController::class, 'ajukanMember'])
            ->name('peminjaman.ajukan');

        Route::get('/booking/{id}/tiket', [PeminjamanController::class, 'tiketBooking'])
            ->name('peminjaman.booking.tiket');

        Route::post('/booking/{id}/batal', [PeminjamanController::class, 'batalBooking'])
            ->name('peminjaman.booking.batal');

        Route::get('/pengembalian-saya', [PengembalianController::class, 'memberIndex'])
            ->name('pengembalian.member');

        Route::post('/pengembalian-saya/{idDetail}/proses', [PengembalianController::class, 'memberStore'])
            ->name('pengembalian.member.store');

        Route::get('/pengembalian-saya/{idDetail}/tiket', [PengembalianController::class, 'memberTiket'])
            ->name('pengembalian.member.tiket');

        Route::post('/pengembalian-saya/{idDetail}/batal', [PengembalianController::class, 'memberBatal'])
            ->name('pengembalian.member.batal');

        Route::get('/pengembalian-saya/{idPengembalian}/bukti', [PengembalianController::class, 'memberBukti'])
            ->name('pengembalian.member.bukti');

        Route::get('/denda-saya', [DendaController::class, 'memberDenda'])
            ->name('denda.saya');

        Route::get('/denda/{id}/bayar-qr', [PembayaranController::class, 'bayarQr'])
            ->name('bayar.qr');

        Route::post('/pembayaran/{id}/proses-qr', [PembayaranController::class, 'prosesBayarQr'])
            ->name('bayar.proses_qr');

        Route::get('/pembayaran/{id}/sukses', [PembayaranController::class, 'sukses'])
            ->name('bayar.sukses');

        Route::get('/pembayaran/{id}/nota', [PembayaranController::class, 'nota'])
            ->name('pembayaran.nota');

        Route::get('/kartu-saya', function () {
            return redirect()->route('member.cetak-qr', auth()->id());
        })->name('member.kartu-saya');
    });

    /*
    |--------------------------------------------------------------------------
    | MODUL KHUSUS ADMIN (Role: Admin)
    |--------------------------------------------------------------------------
    | Pengawasan sistem, kebijakan, master data kategori & buku, serta laporan eksekutif.
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        Route::resource('petugas', PetugasController::class)
            ->except(['show']);

        Route::resource('kategori', KategoriController::class)
            ->except(['create', 'show']);

        Route::get('/laporan', [LaporanController::class, 'index'])
            ->name('laporan.index');

        // Mutasi Master Data Buku (Tambah, Edit, Hapus)
        Route::resource('buku', BukuController::class)
            ->except(['index', 'show']);
    });

    /*
    |--------------------------------------------------------------------------
    | MODUL OPERASIONAL STAF SIRKULASI (Role: Petugas & Admin)
    |--------------------------------------------------------------------------
    | Pelayanan peminjaman/pengembalian meja sirkulasi, scan, denda, dan data member.
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,petugas')->group(function () {
        // Akses daftar & detail buku untuk staf
        Route::get('/buku', [BukuController::class, 'index'])
            ->name('buku.index');

        Route::get('/buku/{buku}', [BukuController::class, 'show'])
            ->name('buku.show');

        Route::get('/buku/{id}/cetak-qr', [QrController::class, 'cetakBuku'])
            ->name('buku.cetak-qr');

        // Kelola Data Member
        Route::resource('member', MemberController::class)
            ->except(['show', 'destroy']);

        Route::patch('/member/{id}/toggle-status', [MemberController::class, 'toggleStatus'])
            ->name('member.toggle-status');

        // Sirkulasi Peminjaman & Pengembalian
        Route::resource('peminjaman', PeminjamanController::class)
            ->only(['index', 'create', 'store', 'show']);

        // Aksi Pengelolaan Booking Sirkulasi
        Route::post('/peminjaman/booking/{id}/siapkan', [PeminjamanController::class, 'siapkanBooking'])
            ->name('petugas.booking.siapkan');

        Route::post('/peminjaman/booking/{id}/serah-terima', [PeminjamanController::class, 'serahTerimaBooking'])
            ->name('petugas.booking.serah-terima');

        Route::resource('pengembalian', PengembalianController::class)
            ->only(['index', 'create', 'store', 'show']);

        // Barcode & Kondisi Buku
        Route::get('/scan-barcode', [BarcodeController::class, 'scan'])
            ->name('barcode.scan');

        Route::get('/kondisi-buku', [KondisiBukuController::class, 'index'])
            ->name('kondisi.index');

        Route::get('/kondisi-buku/{id}/edit', [KondisiBukuController::class, 'edit'])
            ->name('kondisi.edit');

        Route::put('/kondisi-buku/{id}', [KondisiBukuController::class, 'update'])
            ->name('kondisi.update');

        // Kelola Denda & Pembayaran
        Route::get('/denda', [DendaController::class, 'index'])
            ->name('denda.index');

        Route::get('/denda/hitung/{idPengembalian}', [DendaController::class, 'hitung'])
            ->name('denda.hitung');

        Route::post('/denda', [DendaController::class, 'store'])
            ->name('denda.store');

        Route::get('/denda/{id}', [DendaController::class, 'show'])
            ->name('denda.show');

        Route::get('/pembayaran', [PembayaranController::class, 'index'])
            ->name('pembayaran.index');

        Route::post('/pembayaran/{id}/verifikasi', [PembayaranController::class, 'verifikasi'])
            ->name('pembayaran.verifikasi');

        // API Scan Sirkulasi
        Route::post('/api/scan/member', [QrController::class, 'apiScanMember'])
            ->name('api.scan.member');

        Route::post('/api/scan/buku', [QrController::class, 'apiScanBuku'])
            ->name('api.scan.buku');

        Route::post('/api/scan/pengembalian/member', [QrController::class, 'apiScanPengembalianMember'])
            ->name('api.scan.pengembalian.member');

        Route::post('/api/scan/identifikasi', [QrController::class, 'apiIdentifikasi'])
            ->name('api.scan.identifikasi');
    });
});
