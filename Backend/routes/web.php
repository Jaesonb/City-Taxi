<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PassengerController;
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
});

require __DIR__.'/auth.php';
