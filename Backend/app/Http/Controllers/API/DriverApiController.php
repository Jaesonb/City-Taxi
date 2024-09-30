<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DriverApiController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:drivers',
                'password' => 'required|string|min:8|confirmed',
                'phone_number' => 'nullable|string|max:20',
                'vehicle_number' => 'required|string|max:255|unique:drivers',
                'model' => 'required|string|max:255',
                'brand' => 'required|string|max:255',
                'color' => 'required|string|max:255',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            $driver = Driver::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'vehicle_number' => $request->vehicle_number,
                'model' => $request->model,
                'brand' => $request->brand,
                'color' => $request->color,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => 'AVAILABLE',
            ]);

            $token = $driver->createToken('driver-token')->plainTextToken;

            return response()->json([
                'driver' => $driver,
                'token' => $token,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error registering driver: ' . $e->getMessage());
            return response()->json(['error' => 'Error registering driver: ' . $e->getMessage()], 500);
        }
    }

    // Login an existing driver
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $driver = Driver::where('email', $request->email)->first();

            if (!$driver || !Hash::check($request->password, $driver->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $driver->createToken('driver-token')->plainTextToken;

            return response()->json([
                'driver' => $driver,
                'token' => $token,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error logging in driver: ' . $e->getMessage());
            return response()->json(['error' => 'Error logging in driver: ' . $e->getMessage()], 500);
        }
    }

    // Logout the driver (delete token)
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Logged out successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error logging out driver: ' . $e->getMessage());
            return response()->json(['error' => 'Error logging out: ' . $e->getMessage()], 500);
        }
    }

    // Fetch driver details
    public function show(Request $request)
    {
        try {
            $driver = Driver::findOrFail($request->id);
            return response()->json($driver, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Driver not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching driver: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching driver: ' . $e->getMessage()], 500);
        }
    }


    // Method to Fetch Nearby Drivers
    public function getNearbyDrivers(Request $request)
    {
        try {
            // Validate that the latitude and longitude are provided
            $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            $passengerLatitude = $request->latitude;
            $passengerLongitude = $request->longitude;

            // Define a radius (in kilometers) for nearby drivers search
            $radius = 10; // You can change the radius as per your requirement

            // Fetch drivers using the haversine formula to calculate distance
            $drivers = Driver::select('name', 'email', 'phone_number', 'status', 'vehicle_number', 'model', 'brand', 'color', 'latitude', 'longitude')
                ->where('status', 'AVAILABLE') // Fetch only available drivers
                ->selectRaw("( 6371 * acos( cos( radians(?) ) *
                          cos( radians( latitude ) ) *
                          cos( radians( longitude ) - radians(?) ) +
                          sin( radians(?) ) *
                          sin( radians( latitude ) ) ) ) AS distance",
                    [$passengerLatitude, $passengerLongitude, $passengerLatitude]
                )
                ->having('distance', '<', $radius)
                ->orderBy('distance')
                ->get();

            // Return the list of drivers
            return response()->json($drivers, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching nearby drivers: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching nearby drivers: ' . $e->getMessage()], 500);
        }
    }


    // Update driver information
    public function update(Request $request)
    {
        try {
            $driver = Driver::findOrFail($request->id);

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:drivers,email,' . $driver->id,
                'phone_number' => 'nullable|string|max:20',
                'vehicle_number' => 'required|string|max:255|unique:drivers,vehicle_number,' . $driver->id,
                'model' => 'required|string|max:255',
                'brand' => 'required|string|max:255',
                'color' => 'required|string|max:255',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'status' => 'required|in:AVAILABLE,BUSY',
            ]);

            $driver->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'vehicle_number' => $request->vehicle_number,
                'model' => $request->model,
                'brand' => $request->brand,
                'color' => $request->color,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => $request->status,
            ]);

            return response()->json(['message' => 'Driver updated successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Driver not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Error updating driver: ' . $e->getMessage());
            return response()->json(['error' => 'Error updating driver: ' . $e->getMessage()], 500);
        }
    }

    // Delete a driver
    public function destroy(Request $request)
    {
        try {
            $driver = Driver::findOrFail($request->id);
            $driver->delete();

            return response()->json(['message' => 'Driver deleted successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Driver not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting driver: ' . $e->getMessage());
            return response()->json(['error' => 'Error deleting driver: ' . $e->getMessage()], 500);
        }
    }
}
