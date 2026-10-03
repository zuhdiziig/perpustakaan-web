<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\KondisiBukuController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\LaporanController;

// Tamu (Belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    // Rute Registrasi Member
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Sudah login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', function () {
    return view('dashboard');
    })->name('dashboard');

    Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
    Route::get('/katalog/{id}', [KatalogController::class, 'show'])->name('katalog.show');

    // Fitur Verifikasi Pembayaran (Petugas/Admin)
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran/{id}/verifikasi', [PembayaranController::class, 'verifikasi'])->name('pembayaran.verifikasi');

    // Fitur Kelola Buku (CRUD)
    Route::resource('buku', BukuController::class);

    // Fitur Kelola Kategori (Admin)
    Route::resource('kategori', KategoriController::class)->except(['create', 'show']);

    // Fitur Kelola Data Petugas (Admin)
    Route::resource('petugas', PetugasController::class)->except(['show']);

    // Fitur Kelola Data Member (Admin)
    Route::resource('member', MemberController::class)->except(['show', 'destroy']);
    Route::patch('/member/{id}/toggle-status', [MemberController::class, 'toggleStatus'])->name('member.toggle-status');

    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

    // Fitur Transaksi Pengembalian (Petugas/Admin)
    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('/pengembalian/create', [PengembalianController::class, 'create'])->name('pengembalian.create');
    Route::post('/pengembalian', [PengembalianController::class, 'store'])->name('pengembalian.store');
    Route::get('/pengembalian/{id}', [PengembalianController::class, 'show'])->name('pengembalian.show');

    // Fitur Scan Barcode Buku (Petugas/Admin)
    Route::get('/scan-barcode', [BarcodeController::class, 'scan'])->name('barcode.scan');

    // Fitur Cek & Input Kondisi Buku (Petugas/Admin)
    Route::get('/kondisi-buku', [KondisiBukuController::class, 'index'])->name('kondisi.index');
    Route::get('/kondisi-buku/{id}/edit', [KondisiBukuController::class, 'edit'])->name('kondisi.edit');
    Route::put('/kondisi-buku/{id}', [KondisiBukuController::class, 'update'])->name('kondisi.update');

    // Fitur 12 Kelola Denda (Petugas/Admin)
    Route::get('/denda', [DendaController::class, 'index'])->name('denda.index');
    Route::get('/denda/hitung/{idPengembalian}', [DendaController::class, 'hitung'])->name('denda.hitung');
    Route::post('/denda', [DendaController::class, 'store'])->name('denda.store');
    Route::get('/denda/{id}', [DendaController::class, 'show'])->name('denda.show');

    // Fitur 13 Bayar Denda via QR (Member)
    Route::get('/denda/{id}/bayar-qr', [PembayaranController::class, 'bayarQr'])->name('bayar.qr');
    Route::post('/pembayaran/{id}/proses-qr', [PembayaranController::class, 'prosesBayarQr'])->name('bayar.proses_qr');

    // Fitur 15 Melihat Riwayat (Member)
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

    // Fitur 16 Melihat Denda (Member)
    Route::get('/denda-saya', [DendaController::class, 'memberDenda'])->name('denda.saya'); 

    // Fitur 17 Melihat Laporan (Admin)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});