<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\BukuController;
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
use App\Http\Controllers\QrController;
use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// Tamu (Belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.update');
});

// Sudah login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard & Katalog Umum
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
    Route::get('/katalog/{id}', [KatalogController::class, 'show'])->name('katalog.show');

    // Fitur Khusus Member
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/denda-saya', [DendaController::class, 'memberDenda'])->name('denda.saya');
    Route::get('/denda/{id}/bayar-qr', [PembayaranController::class, 'bayarQr'])->name('bayar.qr');
    Route::post('/pembayaran/{id}/proses-qr', [PembayaranController::class, 'prosesBayarQr'])->name('bayar.proses_qr');

    // Rute cetak kartu/QR member yang sedang login langsung
    Route::get('/kartu-saya', function () {
        return redirect()->route('member.cetak-qr', auth()->id());
    })->name('member.kartu-saya');

    // Fitur Manajemen Perpustakaan (Petugas / Admin)
    Route::resource('buku', BukuController::class);
    Route::resource('kategori', KategoriController::class)->except(['create', 'show']);
    Route::resource('petugas', PetugasController::class)->except(['show']);
    Route::resource('member', MemberController::class)->except(['show', 'destroy']);
    Route::patch('/member/{id}/toggle-status', [MemberController::class, 'toggleStatus'])->name('member.toggle-status');

    // Transaksi Peminjaman & Pengembalian
    Route::resource('peminjaman', PeminjamanController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('pengembalian', PengembalianController::class)->only(['index', 'create', 'store', 'show']);

    // Barcode, Kondisi Buku & Denda
    Route::get('/scan-barcode', [BarcodeController::class, 'scan'])->name('barcode.scan');
    Route::get('/kondisi-buku', [KondisiBukuController::class, 'index'])->name('kondisi.index');
    Route::get('/kondisi-buku/{id}/edit', [KondisiBukuController::class, 'edit'])->name('kondisi.edit');
    Route::put('/kondisi-buku/{id}', [KondisiBukuController::class, 'update'])->name('kondisi.update');

    Route::get('/denda', [DendaController::class, 'index'])->name('denda.index');
    Route::get('/denda/hitung/{idPengembalian}', [DendaController::class, 'hitung'])->name('denda.hitung');
    Route::post('/denda', [DendaController::class, 'store'])->name('denda.store');
    Route::get('/denda/{id}', [DendaController::class, 'show'])->name('denda.show');

    // Pembayaran & Laporan
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran/{id}/verifikasi', [PembayaranController::class, 'verifikasi'])->name('pembayaran.verifikasi');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Fitur QR Code (Cetak & API Scanner)
    Route::get('/member/{id}/cetak-qr', [QrController::class, 'cetakMember'])->name('member.cetak-qr');
    Route::get('/buku/{id}/cetak-qr', [QrController::class, 'cetakBuku'])->name('buku.cetak-qr');
    Route::post('/api/scan/member', [QrController::class, 'apiScanMember'])->name('api.scan.member');
    Route::post('/api/scan/buku', [QrController::class, 'apiScanBuku'])->name('api.scan.buku');
    Route::post('/api/scan/pengembalian/member', [QrController::class, 'apiScanPengembalianMember'])->name('api.scan.pengembalian.member');
});
