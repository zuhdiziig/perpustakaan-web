<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\KategoriController;
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
    | LAYANAN MANDIRI MEMBER
    |--------------------------------------------------------------------------
    */

    Route::get('/riwayat', [RiwayatController::class, 'index'])
        ->name('riwayat.index');

    Route::get('/denda-saya', [DendaController::class, 'memberDenda'])
        ->name('denda.saya');

    Route::get('/denda/{id}/bayar-qr', [PembayaranController::class, 'bayarQr'])
        ->name('bayar.qr');

    Route::post('/pembayaran/{id}/proses-qr', [PembayaranController::class, 'prosesBayarQr'])
        ->name('bayar.proses_qr');

    /*
    |--------------------------------------------------------------------------
    | PROFILE MEMBER
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    /*
    |--------------------------------------------------------------------------
    | KARTU / QR MEMBER
    |--------------------------------------------------------------------------
    */

    Route::get('/kartu-saya', function () {
        return redirect()->route('member.cetak-qr', auth()->id());
    })->name('member.kartu-saya');

    /*
    |--------------------------------------------------------------------------
    | MODUL OPERASIONAL PETUGAS & ADMIN
    |--------------------------------------------------------------------------
    */

    Route::resource('buku', BukuController::class);

    Route::resource('kategori', KategoriController::class)
        ->except(['create', 'show']);

    Route::resource('petugas', PetugasController::class)
        ->except(['show']);

    Route::resource('member', MemberController::class)
        ->except(['show', 'destroy']);

    Route::patch('/member/{id}/toggle-status', [MemberController::class, 'toggleStatus'])
        ->name('member.toggle-status');

    /*
    |--------------------------------------------------------------------------
    | PEMINJAMAN
    |--------------------------------------------------------------------------
    */

    Route::resource('peminjaman', PeminjamanController::class)
        ->only(['index', 'create', 'store', 'show']);

    /*
    |--------------------------------------------------------------------------
    | PENGEMBALIAN
    |--------------------------------------------------------------------------
    */

    Route::resource('pengembalian', PengembalianController::class)
        ->only(['index', 'create', 'store', 'show']);

    /*
    |--------------------------------------------------------------------------
    | SCAN BARCODE
    |--------------------------------------------------------------------------
    */

    Route::get('/scan-barcode', [BarcodeController::class, 'scan'])
        ->name('barcode.scan');

    /*
    |--------------------------------------------------------------------------
    | KONDISI BUKU
    |--------------------------------------------------------------------------
    */

    Route::get('/kondisi-buku', [KondisiBukuController::class, 'index'])
        ->name('kondisi.index');

    Route::get('/kondisi-buku/{id}/edit', [KondisiBukuController::class, 'edit'])
        ->name('kondisi.edit');

    Route::put('/kondisi-buku/{id}', [KondisiBukuController::class, 'update'])
        ->name('kondisi.update');

    /*
    |--------------------------------------------------------------------------
    | DENDA
    |--------------------------------------------------------------------------
    */

    Route::get('/denda', [DendaController::class, 'index'])
        ->name('denda.index');

    Route::get('/denda/hitung/{idPengembalian}', [DendaController::class, 'hitung'])
        ->name('denda.hitung');

    Route::post('/denda', [DendaController::class, 'store'])
        ->name('denda.store');

    Route::get('/denda/{id}', [DendaController::class, 'show'])
        ->name('denda.show');

    /*
    |--------------------------------------------------------------------------
    | PEMBAYARAN
    |--------------------------------------------------------------------------
    */

    Route::get('/pembayaran', [PembayaranController::class, 'index'])
        ->name('pembayaran.index');

    Route::post('/pembayaran/{id}/verifikasi', [PembayaranController::class, 'verifikasi'])
        ->name('pembayaran.verifikasi');

    /*
    |--------------------------------------------------------------------------
    | LAPORAN
    |--------------------------------------------------------------------------
    */

    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');

    /*
    |--------------------------------------------------------------------------
    | QR CODE
    |--------------------------------------------------------------------------
    */

    Route::get('/member/{id}/cetak-qr', [QrController::class, 'cetakMember'])
        ->name('member.cetak-qr');

    Route::get('/buku/{id}/cetak-qr', [QrController::class, 'cetakBuku'])
        ->name('buku.cetak-qr');

    Route::post('/api/scan/member', [QrController::class, 'apiScanMember'])
        ->name('api.scan.member');

    Route::post('/api/scan/buku', [QrController::class, 'apiScanBuku'])
        ->name('api.scan.buku');

    Route::post('/api/scan/pengembalian/member', [QrController::class, 'apiScanPengembalianMember'])
        ->name('api.scan.pengembalian.member');
});
