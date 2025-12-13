<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WisataController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PengelolaController;
use App\Http\Controllers\WisatawanController;

// Halaman Publik
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/wisata', [WisataController::class, 'index'])->name('wisata.index');
Route::get('/wisata/{wisata}', [WisataController::class, 'show'])->name('wisata.show');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Profile
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
    
    // Wisatawan Routes
    Route::prefix('wisatawan')->name('wisatawan.')->group(function () {
        Route::get('/bookings', [WisatawanController::class, 'bookings'])->name('bookings');
        Route::get('/booking/create/{wisata}', [WisatawanController::class, 'createBooking'])->name('booking.create');
        Route::post('/booking/store/{wisata}', [WisatawanController::class, 'storeBooking'])->name('booking.store');
        Route::post('/booking/cancel/{booking}', [WisatawanController::class, 'cancelBooking'])->name('booking.cancel');
        
        // Ulasan
        Route::get('/ulasan/create/{booking}', [WisatawanController::class, 'createUlasan'])->name('ulasan.create');
        Route::post('/ulasan/store/{booking}', [WisatawanController::class, 'storeUlasan'])->name('ulasan.store');
    });
    
    // Pengelola Routes
    Route::prefix('pengelola')->name('pengelola.')->middleware('pengelola')->group(function () {
        Route::get('/dashboard', [PengelolaController::class, 'dashboard'])->name('dashboard');
        
        // Wisata CRUD - Route utama
        Route::get('/wisata', [PengelolaController::class, 'wisata'])->name('wisata.index');
        
        // Route alias untuk 'pengelola.wisata' yang redirect ke 'pengelola.wisata.index'
        Route::get('/wisata/alias', function () {
            return redirect()->route('pengelola.wisata.index');
        })->name('wisata');
        
        Route::get('/wisata/create', [PengelolaController::class, 'createWisata'])->name('wisata.create');
        Route::post('/wisata/store', [PengelolaController::class, 'storeWisata'])->name('wisata.store');
        Route::get('/wisata/{wisata}/edit', [PengelolaController::class, 'editWisata'])->name('wisata.edit');
        Route::put('/wisata/{wisata}', [PengelolaController::class, 'updateWisata'])->name('wisata.update');
        Route::delete('/wisata/{wisata}', [PengelolaController::class, 'destroyWisata'])->name('wisata.destroy');
        
        // Bookings
        Route::get('/bookings', [PengelolaController::class, 'bookings'])->name('bookings.index');
        Route::get('/bookings/{booking}', [PengelolaController::class, 'showBooking'])->name('bookings.show');
        Route::post('/booking/{booking}/status', [PengelolaController::class, 'updateBookingStatus'])->name('booking.status.update');
    });
    
    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Users Management - DIPERBARUI
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::post('/user/{user}/verify', [AdminController::class, 'verifyUser'])->name('user.verify');
        
        // ROUTE PENGHAPUSAN PENGGUNA - TAMBAHAN BARU
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        
        // ROUTE SOFT DELETE (Opsional) - TAMBAHAN BARU
        Route::get('/users/trashed', [AdminController::class, 'trashedUsers'])->name('users.trashed');
        Route::post('/users/{id}/restore', [AdminController::class, 'restoreUser'])->name('users.restore');
        Route::delete('/users/{id}/force', [AdminController::class, 'forceDeleteUser'])->name('users.force-delete');
        
        // Wisata Management
        Route::get('/wisata', [AdminController::class, 'wisata'])->name('wisata.index');
        
        // Bookings Management
        Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings.index');
        Route::post('/booking/{booking}/status', [AdminController::class, 'updateBookingStatus'])->name('booking.status.update');
    });
});