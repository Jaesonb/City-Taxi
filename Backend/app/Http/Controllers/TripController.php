<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Passenger;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TripController extends Controller
{
    // Show all trips
    public function index()
    {
        try {
            $trips = Trip::all();
            return view('trips.index', compact('trips'));
        } catch (\Exception $e) {
            Log::error('Error fetching trips: ' . $e->getMessage());
            return redirect()->route('trips')->with('error', 'Error fetching trips: ' . $e->getMessage());
        }
    }

    // Show the form to create a new trip
    public function create()
    {
        $passengers = Passenger::all();
        $drivers = Driver::all();
        $trips = Trip::all();

        return view('trips.create', compact('passengers', 'drivers'));
    }

    // Store a new trip in the database
    public function store(Request $request)
    {
        try {
            $request->validate([
                'passenger_id' => 'required|integer',
                'driver_id' => 'required|integer',
                'pickup_location' => 'required|string|max:255',
                'pickup_latitude' => 'required|numeric',
                'pickup_longitude' => 'required|numeric',
                'dropoff_location' => 'required|string|max:255',
                'dropoff_latitude' => 'required|numeric',
                'dropoff_longitude' => 'required|numeric',
                'pickup_time' => 'required|date',
            ]);

            Trip::create($request->all());

            return redirect()->route('trips')->with('success', 'Trip created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating trip: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error creating trip: ' . $e->getMessage());
        }
    }

    // Show a specific trip
    public function show($id)
    {
        try {
            $trip = Trip::findOrFail($id);
            return view('trips.show', compact('trip'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('trips')->with('error', 'Trip not found.');
        } catch (\Exception $e) {
            Log::error('Error fetching trip details: ' . $e->getMessage());
            return redirect()->route('trips')->with('error', 'Error fetching trip details: ' . $e->getMessage());
        }
    }

    // Show the form to edit a specific trip
    public function edit($id)
    {
        try {
            $trip = Trip::findOrFail($id);
            $passengers = Passenger::all();
            $drivers = Driver::all();
            return view('trips.edit', compact('trip', 'passengers', 'drivers'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('trips')->with('error', 'Trip not found.');
        } catch (\Exception $e) {
            Log::error('Error fetching trip for edit: ' . $e->getMessage());
            return redirect()->route('trips')->with('error', 'Error fetching trip for edit: ' . $e->getMessage());
        }
    }

    // Update an existing trip
    public function update(Request $request, $id)
    {
        try {
            $trip = Trip::findOrFail($id);

            $request->validate([
                'passenger_id' => 'required|integer',
                'driver_id' => 'required|integer',
                'pickup_location' => 'required|string|max:255',
                'pickup_latitude' => 'required|numeric',
                'pickup_longitude' => 'required|numeric',
                'dropoff_location' => 'required|string|max:255',
                'dropoff_latitude' => 'required|numeric',
                'dropoff_longitude' => 'required|numeric',
                'pickup_time' => 'required|date',
                'dropoff_time' => 'required|date',
                'status' => 'nullable|in:PENDING,CONFIRMED,CANCELLED,COMPLETED',
            ]);

            $trip->update($request->all());

            return redirect()->route('trips')->with('success', 'Trip updated successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('trips')->with('error', 'Trip not found.');
        } catch (\Exception $e) {
            Log::error('Error updating trip: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating trip: ' . $e->getMessage());
        }
    }

    // Delete a trip
    public function destroy($id)
    {
        try {
            $trip = Trip::findOrFail($id);
            $trip->delete();

            return redirect()->route('trips')->with('success', 'Trip deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('trips')->with('error', 'Trip not found.');
        } catch (\Exception $e) {
            Log::error('Error deleting trip: ' . $e->getMessage());
            return redirect()->route('trips.index')->with('error', 'Error deleting trip: ' . $e->getMessage());
        }
    }

    // Search for trips by fields such as passenger or driver
    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            $trips = Trip::where('pickup_location', 'like', '%' . $query . '%')
                ->orWhere('dropoff_location', 'like', '%' . $query . '%')
                ->get();

            return view('trips.index', compact('trips'));
        } catch (\Exception $e) {
            Log::error('Error searching trips: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error searching trips: ' . $e->getMessage());
        }
    }

    public function getFare(Trip $trip)
    {
        if ($trip) {
            return response()->json([
                'fare' => $trip->fare,
            ]);
        }

        return response()->json([
            'error' => 'Trip not found',
        ], 404);
    }
}
