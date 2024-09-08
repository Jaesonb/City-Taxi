<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function create(Request $request)
    {
        $trip = Trip::create([
            'passenger_id' => $request->passenger_id,
            'driver_id' => $request->driver_id,
            'pickup_location' => $request->pickup_location,
            'dropoff_location' => $request->dropoff_location,
            'pickup_time' => $request->pickup_time,
            'status' => 'PENDING'
        ]);

        return response()->json($trip, 201);
    }

    public function index()
    {
        $trips = Trip::all();
        return response()->json($trips);
    }

    public function updateStatus(Request $request, $id)
    {
        $trip = Trip::findOrFail($id);
        $trip->status = $request->status;
        $trip->save();

        return response()->json($trip);
    }

    public function show($id)
    {
        $trip = Trip::findOrFail($id);
        return response()->json($trip);
    }
}
