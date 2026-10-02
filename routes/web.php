<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PetugasController;

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
    
    // Placeholder dashboard sementara
    Route::get('/dashboard', function () {
        return "Selamat datang di Dashboard, " . auth()->user()->name . " (" . auth()->user()->role . ")";
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
});