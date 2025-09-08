<?php

use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;
require __DIR__.'/auth.php';

Route::view('/', 'welcome')->middleware(['role:admin'])->name('home');
Route::view('/phone', 'tes-phone');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


    // routes/web.php
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::get('/become-vendor', [VendorController::class, 'showRegistrationForm'])->name('become.vendor');
    Route::post('/become-vendor', [VendorController::class, 'register'])->name('vendor.register.submit');
});

Route::middleware(['auth', 'verified', 'role:vendor'])->group(function () {
    Route::get('/become-vendor', [VendorController::class, 'showRegistrationForm'])->name('become.vendor');
    Route::post('/become-vendor', [VendorController::class, 'register'])->name('vendor.register.submit');
});


// routes/web.php
Route::middleware(['auth', 'verified', 'role:vendor'])->prefix('vendor')->group(function () {
    // Dashboard
    Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('vendor.dashboard');
    
    // Services
  
});
