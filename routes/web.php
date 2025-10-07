<?php

use App\Livewire\Vendor\VendorRegistration;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('home.index');
    })->name('home');

    Route::get('/profile', function () {
        return view('livewire.profile.index');
    })->name('profile');

    Route::get('/checkin', function () {
        return view('checkin.index');
    })->name('checkin');
});


// Vendor Routes
Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/orders', function () {
        return view('vendor.orders');
    })->name('orders');

    Route::get('/products', function () {
        return view('vendor.products');
    })->name('products');
});

Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::get('/become-vendor', VendorRegistration::class)->name('vendor.register');
});
