<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

// Rute untuk halaman utama (welcome page), bisa diakses tanpa login
Route::get('/', function () {
    return view('welcome');
});

// Rute untuk dashboard, hanya bisa diakses setelah login dan verifikasi email
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Semua rute di dalam grup ini hanya bisa diakses setelah login
Route::middleware('auth')->group(function () {
    // Rute untuk Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Rute yang diperbaiki untuk Bookings ---

    // Gunakan Route::resource untuk membuat semua rute CRUD
    // termasuk GET /bookings (index) dan POST /bookings (store)
    Route::resource('bookings', BookingController::class);

});

// Rute otentikasi bawaan Laravel (login, register, dll)
require __DIR__.'/auth.php';