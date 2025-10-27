<?php

use App\Http\Controllers\FavoriteController;
use App\Livewire\Vendor\VendorRegistration;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendorController;

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // routes/web.php

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/general', [ProfileController::class, 'general'])->name('profile.general');
    Route::get('/profile/security', [ProfileController::class, 'security'])->name('profile.security');
    Route::get('/profile/help', [ProfileController::class, 'help'])->name('profile.help');
    Route::get('/profile/feedback', [ProfileController::class, 'feedback'])->name('profile.feedback');
    Route::post('/profile/switch-role', [ProfileController::class, 'switchRole'])->name('profile.switch-role');

    // Vendor Profile Routes
    Route::get('/vendor/dashboard', [VendorController::class, 'dashboard'])->name('vendor.dashboard');
    Route::get('/vendor/profile', [VendorController::class, 'profile'])->name('vendor.profile');
    Route::get('/vendor/services', [VendorController::class, 'services'])->name('vendor.services');
    Route::get('/vendor/schedule', [VendorController::class, 'schedule'])->name('vendor.schedule');
    Route::get('/vendor/location', [VendorController::class, 'location'])->name('vendor.location');
    Route::get('/vendor/rfid', [VendorController::class, 'rfid'])->name('vendor.rfid');
    Route::get('/become-vendor', VendorRegistration::class)->name('vendor.register');


    Route::get('/checkin', function () {
        return view('checkin.index');
    })->name('checkin');

    Route::get('/map', function () {
        return view('map.index');
    })->name('user.map');

    
    Route::get('/chat', App\Livewire\Chat\Index::class)->name('chat.index');
    Route::get('/chat/vendor/{vendorId}', App\Livewire\Chat\Room::class)->name('chat.room');
    Route::get('/notifications', App\Livewire\Notification\NotificationsIndex::class)->name('notifications.index');
    Route::get('/notifications/{id}', App\Livewire\Notification\NotificationDetail::class)->name('notifications.detail');
    Route::get('/vendor/{vendor}', [VendorController::class, 'show'])->name('vendor.show');
    Route::post('/vendor/{vendor}/review', [VendorController::class, 'storeReview'])
        ->name('vendor.review.store');

    Route::get('/category/{category}', [App\Http\Controllers\CategoryController::class, 'show'])->name('category.show');
    Route::get('/search', [App\Http\Controllers\SearchController::class, 'results'])->name('search.results');
    Route::get('/quick-access', [App\Http\Controllers\QuickAccessController::class, 'index'])->name('quick-access.index');
    
    // API route for load more vendors
    Route::get('/api/vendors/load-more', [HomeController::class, 'loadMoreVendors'])->name('api.vendors.load-more');




    // favorite routes
    Route::get('/profile/favorites', [FavoriteController::class, 'index'])->name('profile.favorites');
    Route::post('/vendors/{vendor}/favorite', [FavoriteController::class, 'favoriteVendor'])->name('vendors.favorite');
    Route::post('/services/{service}/favorite', [FavoriteController::class, 'favoriteService'])->name('services.favorite');
    Route::delete('/favorites/{favorite}', [FavoriteController::class, 'unfavorite'])->name('favorites.unfavorite');
    Route::delete('/vendors/{vendor}/unfavorite', [FavoriteController::class, 'unfavoriteVendor'])->name('vendors.unfavorite');
    Route::delete('/services/{service}/unfavorite', [FavoriteController::class, 'unfavoriteService'])->name('services.unfavorite');
});


// Vendor Routes
Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {});

Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    // Route::get('/become-vendor', VendorRegistration::class)->name('vendor.register');
});
