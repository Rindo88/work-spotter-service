<?php

use App\Livewire\Vendor\VendorRegistration;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VendorController;

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');


    Route::get('/profile', function () {
        return view('livewire.profile.index');
    })->name('profile');

    Route::get('/checkin', function () {
        return view('checkin.index');
    })->name('checkin');

    Route::get('/map', function () {
        return view('map.index');
    })->name('user.map');

    Route::get('/chat', App\Livewire\Chat\Index::class)->name('chat.index');
    Route::get('/chat/vendor/{vendorId}', App\Livewire\Chat\Room::class)->name('chat.room');
    Route::get('/notifications', App\Livewire\Notification\NotificationsIndex::class)->name('notifications.index');
    Route::get('/vendor/{vendor}', [VendorController::class, 'show'])->name('vendor.show');
    Route::post('/vendor/{vendor}/review', [VendorController::class, 'storeReview'])
        ->name('vendor.review.store');

    Route::get('/category/{category}', [App\Http\Controllers\CategoryController::class, 'show'])->name('category.show');
    Route::get('/search', [App\Http\Controllers\SearchController::class, 'results'])->name('search.results');
Route::get('/quick-access', [App\Http\Controllers\QuickAccessController::class, 'index'])->name('quick-access.index');
});


// Vendor Routes
Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {

    Route::get('/products', function () {
        return view('vendor.products');
    })->name('products');
});

Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::get('/become-vendor', VendorRegistration::class)->name('vendor.register');
});
