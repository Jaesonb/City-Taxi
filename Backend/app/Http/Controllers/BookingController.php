<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        $booking = Booking::create([
            'passenger_id' => $request->passenger_id,
            'driver_id' => $request->driver_id,
            'vehicle_id' => $request->vehicle_id,
            'pickup_location' => $request->pickup_location,
            'dropoff_location' => $request->dropoff_location,
            'pickup_time' => $request->pickup_time,
            'status' => 'PENDING'
        ]);

        return response()->json($booking, 201);
    }

    public function index()
    {
        $bookings = Booking::all();
        return response()->json($bookings);
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = $request->status;
        $booking->save();

        return response()->json($booking);
    }

    public function show($id)
    {
        $booking = Booking::findOrFail($id);
        return response()->json($booking);
    }
}
