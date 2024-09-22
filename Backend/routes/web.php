<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/passengers', [PassengerController::class, 'index'])->name('passengers');
    Route::get('/passengers/create', [PassengerController::class, 'create'])->name('passengers.create');
    Route::post('/passengers', [PassengerController::class, 'store'])->name('passengers.store');
    Route::get('/passengers/{id}', [PassengerController::class, 'show'])->name('passengers.show');
    Route::get('/passengers/{id}/edit', [PassengerController::class, 'edit'])->name('passengers.edit');
    Route::put('/passengers/{id}', [PassengerController::class, 'update'])->name('passengers.update');
    Route::delete('/passengers/{id}', [PassengerController::class, 'destroy'])->name('passengers.destroy');

    Route::get('/drivers', [DriverController::class, 'index'])->name('drivers');
    Route::get('/drivers/create', [DriverController::class, 'create'])->name('drivers.create');
    Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');
    Route::get('/drivers/{id}', [DriverController::class, 'show'])->name('drivers.show');
    Route::get('/drivers/{id}/edit', [DriverController::class, 'edit'])->name('drivers.edit');
    Route::put('/drivers/{id}', [DriverController::class, 'update'])->name('drivers.update');
    Route::delete('/drivers/{id}', [DriverController::class, 'destroy'])->name('drivers.destroy');

    Route::get('/paymentts', [PaymentController::class, 'index'])->name('payments');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{id}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{id}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{id}', [PaymentController::class, 'destroy'])->name('payments.destroy');
});

require __DIR__.'/auth.php';
