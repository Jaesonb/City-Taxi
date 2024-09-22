<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver; // Using Driver Model
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class DriverController extends Controller
{
    public function index()
    {
        try {
            $drivers = Driver::all();
            return view('drivers.index', compact('drivers'));
        } catch (\Exception $e) {
            Log::error('Error fetching drivers: ' . $e->getMessage());
            return redirect()->route('drivers')->with('error', 'Error fetching drivers.');
        }
    }

    public function create()
    {
        return view('drivers.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:drivers',
                'password' => 'required|string|min:8|confirmed',
                'phone_number' => 'required|string|max:20',
                'vehicle_number'=> 'required|string|max:20',
                'status' => 'required|string|max:255|(available,busy)',
                'color' => 'required|string|max:255|',
                'model' => 'required|string|max:255|',
                'brand' => 'required|string|max:255|',
                'latitude' => 'required|string|max:255|map:location',
                'longitude' => 'required|string|max:255|map:location'
                
            ]);

            Driver::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'status'=> $request->status,
                'vehicle_number' => $request-> vehicle_number,
                'model'=> $request->model,
                'brand'=> $request->brand,
                'color'=> $request->color,
                'latitude'=> $request->latitude,
                'longitude'=> $request->longitude,
            ]);

            return redirect()->route('drivers')->with('success', 'Driver created successfully.');
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error creating driver: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()->with('error', 'Error creating driver: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $driver = Driver::findOrFail($id);
            return view('drivers.show', compact('driver'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('drivers')->with('error', 'Driver not found.');
        } catch (\Exception $e) {
            Log::error('Error fetching driver: ' . $e->getMessage());
            return redirect()->route('drivers')->with('error', 'Error fetching driver details.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $driver = Driver::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:drivers,email,' . $driver->id,
                'password' => 'nullable|string|min:8|confirmed',
                'phone_number' => 'required|string|max:20',
                'vehicle_number'=> 'required|string|max:20',
                'status' => 'required|string|max:255|(available,busy)',
                'color' => 'required|string|max:255|',
                'model' => 'required|string|max:255|',
                'brand' => 'required|string|max:255|',
                'latitude' => 'required|string|max:255|map:location',
                'longitude' => 'required|string|max:255|map:location'
            ]);

            $driver->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => $request->password ? Hash::make($request->password) : $driver->password,
                'status'=> $request->status,
                'vehicle_number' => $request-> vehicle_number,
                'model'=> $request->model,
                'brand'=> $request->brand,
                'color'=> $request->color,
                'latitude'=> $request->latitude,
                'longitude'=> $request->longitude,
            ]);

            return redirect()->route('drivers')->with('success', 'Driver updated successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('drivers')->with('error', 'Driver not found.');
        } catch (\Exception $e) {
            Log::error('Error updating driver: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating driver.');
        }
    }

    public function destroy($id)
    {
        try {
            $driver = Driver($id);
            $driver->delete();

            return redirect()->route('drivers')->with('success', 'Driver deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('drivers')->with('error', 'Driver not found.');
        } catch (\Exception $e) {
            Log::error('Error deleting driver: ' . $e->getMessage());
            return redirect()->route('drivers')->with('error', 'Error deleting driver.');
        }
    }
    
    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            $drivers = Driver::where('name', 'like', '%' . $query . '%')
                ->orWhere('email', 'like', '%' . $query . '%')
                ->get();

            return view('drivers.index', compact('drivers'));
        } catch (\Exception $e) {
            Log::error('Error searching drivers: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error searching drivers.');
        }
    }

    public function trips($id)
    {
        try {
            $driver = Driver::findOrFail($id);
            $trips = $driver->trips;

            return view('drivers.trips', compact('driver', 'trips'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('drivers')->with('error', 'Driver not found.');
        } catch (\Exception $e) {
            Log::error('Error fetching driver trips: ' . $e->getMessage());
            return redirect()->route('drivers')->with('error', 'Error fetching trips.');
        }
    }   
}
