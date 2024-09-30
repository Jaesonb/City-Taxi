<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;


// Root route should serve the index.blade.php view
Route::get('/', function () {
    return view('transport.index'); // This will serve the index.blade.php from resources/views/transport/
})->name('transport.index');

Route::get('/admin', function () {
    return redirect('/login');
});

Route::post('/login-submit', [TransportController::class, 'login'])->name('login.submit');

// Public routes for Transport system (no authentication required)
Route::get('/driver', [TransportController::class, 'driver'])->name('transport.driver');
Route::get('/passenger', [TransportController::class, 'passenger'])->name('transport.passenger');
Route::get('/driver-register', [TransportController::class, 'driverRegister'])->name('transport.driver-register');
Route::get('/passenger-register', [TransportController::class, 'passengerRegister'])->name('transport.passenger-register');


Route::post('/passenger/register', [TransportController::class, 'storePassenger'])->name('passenger.register');
Route::post('/driver/register', [TransportController::class, 'storeDriver'])->name('driver.register');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Public routes for Transport system (authentication required)

Route::get('/ride-request', [TransportController::class, 'rideRequest'])->name('transport.ride-request');
Route::get('/post-trip', [TransportController::class, 'postTrip'])->name('transport.post-trip');
Route::get('/trip-show/{id}', [TransportController::class, 'show'])->name('transport.trip-show');
Route::get('/driver-trip', [TransportController::class, 'driverTrip'])->name('transport.driver-trip');
Route::post('/trip/store', [TransportController::class, 'storeTrip'])->name('trip.store');
Route::get('/drivers-by-distance', [TransportController::class, 'getDriversByDistance'])->name('drivers.byDistance');
Route::post('/trip/{tripId}/rate', [TransportController::class, 'storeRating'])->name('trip.rate');


// Public routes for Admin panel system (authentication required)
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
    Route::get('/passengers/{id}/trips', [PassengerController::class, 'showTrips'])->name('passengers.trips');

    Route::get('/drivers', [DriverController::class, 'index'])->name('drivers');
    Route::get('/drivers/create', [DriverController::class, 'create'])->name('drivers.create');
    Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');
    Route::get('/drivers/{id}', [DriverController::class, 'show'])->name('drivers.show');
    Route::get('/drivers/{id}/edit', [DriverController::class, 'edit'])->name('drivers.edit');
    Route::put('/drivers/{id}', [DriverController::class, 'update'])->name('drivers.update');
    Route::delete('/drivers/{id}', [DriverController::class, 'destroy'])->name('drivers.destroy');
    Route::get('/drivers/{id}/trips', [DriverController::class, 'showTrips'])->name('drivers.trips');
    Route::get('/driver/accept-trip/{id}', [TransportController::class, 'acceptTrip'])->name('drivers.accept_trip');
    Route::get('/driver/decline-trip/{id}', [TransportController::class, 'declineTrip'])->name('drivers.decline_trip');

    Route::get('/paymentts', [PaymentController::class, 'index'])->name('payments');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{id}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{id}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{id}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    Route::get('/trips', [TripController::class, 'index'])->name('trips');
    Route::get('/trips/create', [TripController::class, 'create'])->name('trips.create');
    Route::post('/trips', [TripController::class, 'store'])->name('trips.store');
    Route::get('/trips/{id}', [TripController::class, 'show'])->name('trips.show');
    Route::get('/trips/{id}/edit', [TripController::class, 'edit'])->name('trips.edit');
    Route::put('/trips/{id}', [TripController::class, 'update'])->name('trips.update');
    Route::delete('/trips/{id}', [TripController::class, 'destroy'])->name('trips.destroy');

    // Additional routes for trip-specific actions
    Route::post('/trips/{id}/start', [TripController::class, 'startTrip'])->name('trips.start');
    Route::post('/trips/{id}/complete', [TripController::class, 'completeTrip'])->name('trips.complete');
    Route::post('/trips/{id}/cancel', [TripController::class, 'cancel'])->name('trips.cancel');
    Route::get('/trips/{id}/calculate-fare', [TripController::class, 'calculateFare'])->name('trips.calculateFare');
    Route::get('/trips/getFare/{trip}', [TripController::class, 'getFare'])->name('trips.getFare');
});

require __DIR__ . '/auth.php';
