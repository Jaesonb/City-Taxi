<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransportController;
use Illuminate\Support\Facades\Route;


// Root route should serve the index.blade.php view
Route::get('/transport', function () {
    return view('transport.index'); // This will serve the index.blade.php from resources/views/transport/
})->name('transport.index');


// Public routes for Transport system (no authentication required)
Route::get('/login', [TransportController::class, 'login'])->name('transport.login');
Route::get('/driver', [TransportController::class, 'driver'])->name('transport.driver');
Route::get('/passenger', [TransportController::class, 'passenger'])->name('transport.passenger');
Route::get('/driver-register', [TransportController::class, 'driverRegister'])->name('transport.driver-register');
Route::get('/passenger-register', [TransportController::class, 'passengerRegister'])->name('transport.passenger-register');

// Dashboard route (requires authentication)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes that require authentication
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Passenger management routes
    Route::get('/passengers', [PassengerController::class, 'index'])->name('passengers');
    Route::get('/passengers/create', [PassengerController::class, 'create'])->name('passengers.create');
    Route::post('/passengers', [PassengerController::class, 'store'])->name('passengers.store');
    Route::get('/passengers/{id}', [PassengerController::class, 'show'])->name('passengers.show');
    Route::get('/passengers/{id}/edit', [PassengerController::class, 'edit'])->name('passengers.edit');
    Route::put('/passengers/{id}', [PassengerController::class, 'update'])->name('passengers.update');
    Route::delete('/passengers/{id}', [PassengerController::class, 'destroy'])->name('passengers.destroy');
});

require __DIR__ . '/auth.php';

