<?php

use App\Http\Controllers\Api\SmartDetectorController;
use Illuminate\Support\Facades\Route;


Route::prefix('vendor/iot')->group(function () {
    Route::post('/checkin', [SmartDetectorController::class, 'checkin']);
    Route::post('/checkout', [SmartDetectorController::class, 'checkout']);
    Route::get('/device/{deviceId}/status', [SmartDetectorController::class, 'deviceStatus']);
});
