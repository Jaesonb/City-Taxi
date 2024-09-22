<?php

namespace App\Http\Controllers\API;

use App\Models\Trip;
use App\Models\Passenger;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TripApiController extends Controller
{
    // Create a new trip
    public function store(Request $request)
    {
        try {
            $request->validate([
                'passenger_id' => 'required|exists:passengers,id',
                'driver_id' => 'required|exists:drivers,id',
                'pickup_location' => 'required|string|max:255',
                'pickup_latitude' => 'required|numeric',
                'pickup_longitude' => 'required|numeric',
                'dropoff_location' => 'required|string|max:255',
                'dropoff_latitude' => 'required|numeric',
                'dropoff_longitude' => 'required|numeric',
                'pickup_time' => 'required|date',
                'fare' => 'required|numeric',
                'status' => 'required|string|in:ONGOING,COMPLETED,CANCELLED',
            ]);

            $trip = Trip::create([
                'passenger_id' => $request->passenger_id,
                'driver_id' => $request->driver_id,
                'vehicle_id' => $request->vehicle_id,
                'pickup_location' => $request->pickup_location,
                'pickup_latitude' => $request->pickup_latitude,
                'pickup_longitude' => $request->pickup_longitude,
                'dropoff_location' => $request->dropoff_location,
                'dropoff_latitude' => $request->dropoff_latitude,
                'dropoff_longitude' => $request->dropoff_longitude,
                'pickup_time' => $request->pickup_time,
                'fare' => $request->fare,
                'status' => $request->status,
            ]);

            return response()->json([
                'message' => 'Trip created successfully.',
                'trip' => $trip,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating trip: ' . $e->getMessage());
            return response()->json(['error' => 'Error creating trip: ' . $e->getMessage()], 500);
        }
    }

    // Retrieve trip details by ID
    public function show(Request $request)
    {
        try {
            $trip = Trip::with(['passenger', 'driver'])->findOrFail($request->id);
            return response()->json($trip, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Trip not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching trip: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching trip: ' . $e->getMessage()], 500);
        }
    }

    // Update trip details
    public function update(Request $request)
    {
        try {
            $trip = Trip::findOrFail($request->id);

            $request->validate([
                'passenger_id' => 'nullable|exists:passengers,id',
                'driver_id' => 'nullable|exists:drivers,id',
                'pickup_location' => 'nullable|string|max:255',
                'pickup_latitude' => 'nullable|numeric',
                'pickup_longitude' => 'nullable|numeric',
                'dropoff_location' => 'nullable|string|max:255',
                'dropoff_latitude' => 'nullable|numeric',
                'dropoff_longitude' => 'nullable|numeric',
                'pickup_time' => 'nullable|date',
                'dropoff_time' => 'nullable|date',
                'fare' => 'nullable|numeric',
                'status' => 'nullable|string|in:ONGOING,COMPLETED,CANCELLED',
            ]);

            $trip->update($request->only([
                'passenger_id',
                'driver_id',
                'vehicle_id',
                'pickup_location',
                'pickup_latitude',
                'pickup_longitude',
                'dropoff_location',
                'dropoff_latitude',
                'dropoff_longitude',
                'pickup_time',
                'dropoff_time',
                'fare',
                'status',
            ]));

            return response()->json([
                'message' => 'Trip updated successfully.',
                'trip' => $trip,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Trip not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Error updating trip: ' . $e->getMessage());
            return response()->json(['error' => 'Error updating trip: ' . $e->getMessage()], 500);
        }
    }

    // Delete trip by ID
    public function destroy(Request $request)
    {
        try {
            $trip = Trip::findOrFail($request->id);
            $trip->delete();

            return response()->json(['message' => 'Trip deleted successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Trip not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting trip: ' . $e->getMessage());
            return response()->json(['error' => 'Error deleting trip: ' . $e->getMessage()], 500);
        }
    }

    // List all trips (optionally filter by passenger_id or driver_id)
    public function index(Request $request)
    {
        try {
            $passengerId = $request->query('passenger_id');
            $driverId = $request->query('driver_id');

            $trips = Trip::with(['passenger', 'driver'])
                ->when($passengerId, function ($query, $passengerId) {
                    return $query->where('passenger_id', $passengerId);
                })
                ->when($driverId, function ($query, $driverId) {
                    return $query->where('driver_id', $driverId);
                })
                ->get();

            return response()->json($trips, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching trips: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching trips: ' . $e->getMessage()], 500);
        }
    }
}
