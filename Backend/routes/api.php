<?php

use App\Http\Controllers\API\DriverApiController;
use App\Http\Controllers\API\PassengerApiController;
use App\Http\Controllers\API\PaymentApiController;
use App\Http\Controllers\API\TripApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Unauthenticated Routes
Route::post('/passengers/register', [PassengerApiController::class, 'register']);
Route::post('/passengers/login', [PassengerApiController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/passengers/logout', [PassengerApiController::class, 'logout']);
    Route::get('/passengers', [PassengerApiController::class, 'show']);
    Route::put('/passengers', [PassengerApiController::class, 'update']);
    Route::delete('/passengers', [PassengerApiController::class, 'destroy']);
});

// Unauthenticated Routes
Route::post('/drivers/register', [DriverApiController::class, 'register']);
Route::post('/drivers/login', [DriverApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/drivers/logout', [DriverApiController::class, 'logout']);
    Route::get('/drivers', [DriverApiController::class, 'show']);
    Route::put('/drivers', [DriverApiController::class, 'update']);
    Route::delete('/drivers', [DriverApiController::class, 'destroy']);
    Route::get('/drivers/nearby', [DriverApiController::class, 'getNearbyDrivers']); //Route for Fetching Nearby Drivers

});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/payments', [PaymentApiController::class, 'index']);
    Route::post('/payments', [PaymentApiController::class, 'store']);
    Route::get('/payments/payment', [PaymentApiController::class, 'show']);
    Route::put('/payments', [PaymentApiController::class, 'update']);
    Route::delete('/payments', [PaymentApiController::class, 'destroy']);
    Route::get('/payments/passenger', [PaymentApiController::class, 'paymentsByPassenger']);
    Route::get('/payments/driver', [PaymentApiController::class, 'paymentsByDriver']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/trips', [TripApiController::class, 'index']);
    Route::post('/trips', [TripApiController::class, 'store']);
    Route::get('/trips/trip', [TripApiController::class, 'show']);
    Route::put('/trips', [TripApiController::class, 'update']);
    Route::get('/trips/search', [TripApiController::class, 'index']);
    Route::delete('/trips', [TripApiController::class, 'destroy']);
});
