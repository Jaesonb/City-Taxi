<?php

namespace App\Http\Controllers;

use App\Mail\DriverCreated;
use App\Mail\PassengerCreated;
use App\Models\Driver;
use App\Models\Passenger;
use App\Models\Rating;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class TransportController extends Controller
{
    // Serve the driver page
    public function driver()
    {
        return view('transport.driver');
    }

    // Serve the passenger page
    public function passenger()
    {
        return view('transport.passenger');
    }

    // Serve the driver registration page
    public function driverRegister()
    {
        return view('transport.driver-register');
    }

    // Serve the passenger registration page
    public function passengerRegister()
    {
        return view('transport.passenger-register');
    }

    // Serve the passenger ride request page
    public function rideRequest(Request $request)
    {
        $passenger = Auth::guard('passenger')->user();
        $pickup_latitude = $request->input('pickup_latitude');
        $pickup_longitude = $request->input('pickup_longitude');

        // If the pickup location is provided, calculate distances from drivers
        if ($pickup_latitude && $pickup_longitude) {
            $drivers = Driver::selectRaw(
                'id, name, vehicle_number, status, latitude, longitude,
                ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance',
                [$pickup_latitude, $pickup_longitude, $pickup_latitude]
            )
            ->where('status', 'AVAILABLE')
            ->orderBy('distance', 'asc')
            ->get();
        } else {
            // If no pickup location is available, just show the available drivers without distance calculation
            $drivers = Driver::where('status', 'AVAILABLE')->get();
        }

        return view('transport.ride-request', compact('passenger', 'drivers'));
    }

    // Serve the ride acceptance page to driver
    public function driverTrip()
    {
        $driver = Auth::guard('driver')->user();
        return view('transport.driver-trip', compact('driver'));
    }

    // Serve the passenger ride review and payment page
    public function postTrip()
    {
        $passenger = Auth::guard('passenger')->user();
        // dd($passenger);
        $trips = $passenger->trips ?? collect();

        return view('transport.post-trip', compact('trips'));
    }

    public function show($id)
    {
        $trip = Trip::findOrFail($id);
        return view('transport.show-trip', compact('trip'));
    }

    // Store function for Driver registration
    public function storeDriver(Request $request)
    {
        try {
            // Validate driver registration inputs
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:drivers',
                'password' => 'required|string|min:8|confirmed',
                'phone_number' => 'required|string|max:20',
                'vehicle_number' => 'required|string|max:255',
                'status' => 'required|string|in:available,busy',
                'color' => 'required|string|max:255|',
                'model' => 'required|string|max:255|',
                'brand' => 'required|string|max:255|',
                'latitude' => 'required|string|max:255',
                'longitude' => 'required|string|max:255',
            ]);

            $plainPassword = $request->password;

            $driver = Driver::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hash the password
                'phone_number' => $request->phone_number,
                'vehicle_number' => $request->vehicle_number,
                'status' => $request->status,
                'model' => $request->model,
                'brand' => $request->brand,
                'color' => $request->color,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            Mail::to($driver->email)->send(new DriverCreated($driver, $plainPassword));

            return redirect('/')->with('success', 'Driver registered successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error registering driver: ' . $e->getMessage());
        }
    }

    public function storePassenger(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:passengers',
                'password' => 'required|string|min:8|confirmed',
                'phone_number' => 'required|string|max:20',
            ]);

            $plainPassword = $request->password;

            $passenger = Passenger::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
            ]);

            Mail::to($passenger->email)->send(new PassengerCreated($passenger, $plainPassword));

            return redirect('/')->with('success', 'Passenger registered successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error registering passenger: ' . $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'user_type' => 'required|in:driver,passenger'
        ]);

        $credentials = $request->only('email', 'password');
        $userType = $request->user_type; // Get the selected user type (driver or passenger)

        // If user selected "driver"
        if ($userType == 'driver') {
            $driver = Driver::where('email', $request->email)->first();
            if ($driver && Hash::check($request->password, $driver->password)) {
                Auth::guard('driver')->login($driver);
                return redirect()->route('transport.driver-trip'); // Redirect to the driver dashboard route
            }
        }

        // If user selected "passenger"
        if ($userType == 'passenger') {
            $passenger = Passenger::where('email', $request->email)->first();
            if ($passenger && Hash::check($request->password, $passenger->password)) {
                Auth::guard('passenger')->login($passenger);
                return redirect()->route('transport.post-trip'); // Redirect to the passenger dashboard route
            }
        }

        // If no match is found, redirect back with an error message
        return redirect()->back()->with('error', 'Login failed, please check your credentials.');
    }

    public function storeTrip(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'pickup_location' => 'required|string|max:255',
            'pickup_latitude' => 'required|numeric',
            'pickup_longitude' => 'required|numeric',
            'dropoff_location' => 'required|string|max:255',
            'dropoff_latitude' => 'required|numeric',
            'dropoff_longitude' => 'required|numeric',
            'pickup_time' => 'required|date',
            'status' => 'required|string|in:PENDING,CONFIRMED,COMPLETED,CANCELLED',
        ]);

        // Add the authenticated passenger_id to the validated data
        $validated['passenger_id'] = Auth::guard('passenger')->id();

        // Create the trip with the passenger's ID
        $trip = Trip::create($validated);

        return redirect()->route('transport.post-trip')->with('success', 'Trip created successfully.');
    }

    public function getDriversByDistance(Request $request)
    {
        $pickup_latitude = $request->input('pickup_latitude');
        $pickup_longitude = $request->input('pickup_longitude');

        if ($pickup_latitude && $pickup_longitude) {
            // Calculate distance of drivers from the pickup location
            $drivers = Driver::selectRaw(
                'id, name, vehicle_number, status, latitude, longitude,
                ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance',
                [$pickup_latitude, $pickup_longitude, $pickup_latitude]
            )
            ->where('status', 'AVAILABLE')
            ->orderBy('distance', 'asc')
            ->get();

            return response()->json(['drivers' => $drivers]);
        }

        return response()->json(['drivers' => []], 400);
    }

    public function storeRating(Request $request, $tripId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $trip = Trip::findOrFail($tripId);
        Rating::create([
            'trip_id' => $trip->id,
            'driver_id' => $trip->driver_id,
            'passenger_id' => Auth::guard('passenger')->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('transport.trip-show', $trip->id)->with('success', 'Thank you for your feedback!');
    }

    public function acceptTrip($id)
    {
        $trip = Trip::findOrFail($id);

        // Only allow acceptance if the trip is still PENDING
        if ($trip->status == 'PENDING') {
            $trip->status = 'CONFIRMED';
            $trip->save();

            return redirect()->back()->with('success', 'Trip accepted successfully.');
        }

        return redirect()->back()->with('error', 'You cannot accept this trip.');
    }

    public function declineTrip($id)
    {
        $trip = Trip::findOrFail($id);

        // Only allow decline if the trip is still PENDING
        if ($trip->status == 'PENDING') {
            $trip->status = 'CANCELLED';
            $trip->save();

            return redirect()->back()->with('success', 'Trip declined successfully.');
        }

        return redirect()->back()->with('error', 'You cannot decline this trip.');
    }

    public function destroyPassenger()
    {
        Auth::guard('passenger')->logout();
        return redirect('/')->with('status', 'Logged out successfully');
    }

    public function destroyDriver()
    {
        Auth::guard('driver')->logout();
        return redirect('/')->with('status', 'Logged out successfully');
    }
}
