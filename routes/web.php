<?php

use App\Livewire\Vendor\VendorRegistration;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VendorController;

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Profile Routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/general', [App\Http\Controllers\ProfileController::class, 'general'])->name('profile.general');
    Route::get('/profile/security', [App\Http\Controllers\ProfileController::class, 'security'])->name('profile.security');
    Route::get('/profile/favorites', [App\Http\Controllers\ProfileController::class, 'favorites'])->name('profile.favorites');
    Route::get('/profile/help', [App\Http\Controllers\ProfileController::class, 'help'])->name('profile.help');
    Route::get('/profile/feedback', [App\Http\Controllers\ProfileController::class, 'feedback'])->name('profile.feedback');

    // Vendor Dashboard Routes
    Route::get('/vendor/dashboard', [App\Http\Controllers\VendorController::class, 'dashboard'])->name('vendor.dashboard');
    Route::get('/vendor/profile', [App\Http\Controllers\VendorController::class, 'profile'])->name('vendor.profile');
    Route::get('/vendor/services', [App\Http\Controllers\VendorController::class, 'services'])->name('vendor.services');
    Route::get('/vendor/schedule', [App\Http\Controllers\VendorController::class, 'schedule'])->name('vendor.schedule');
    // routes/web.php
    Route::post('/profile/switch-role', [App\Http\Controllers\ProfileController::class, 'switchRole'])->name('profile.switch-role');


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
Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {});

Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::get('/become-vendor', VendorRegistration::class)->name('vendor.register');
});
