<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PassengerApiController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:passengers',
                'password' => 'required|string|min:8|confirmed',
                'phone_number' => 'required|string|max:20',
            ]);

            $passenger = Passenger::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
            ]);

            $token = $passenger->createToken('passenger-token')->plainTextToken;

            return response()->json([
                'passenger' => $passenger,
                'token' => $token,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error registering passenger: ' . $e->getMessage());
            return response()->json(['error' => 'Error registering passenger: ' . $e->getMessage()], 500);
        }
    }

    // Login an existing passenger
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $passenger = Passenger::where('email', $request->email)->first();

            if (!$passenger || !Hash::check($request->password, $passenger->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $passenger->createToken('passenger-token')->plainTextToken;

            return response()->json([
                'passenger' => $passenger,
                'token' => $token,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error logging in passenger: ' . $e->getMessage());
            return response()->json(['error' => 'Error logging in passenger: ' . $e->getMessage()], 500);
        }
    }

    // Logout the passenger (delete token)
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Logged out successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error logging out passenger: ' . $e->getMessage());
            return response()->json(['error' => 'Error logging out: ' . $e->getMessage()], 500);
        }
    }

    // Fetch passenger details
    public function show(Request $request)
    {
        try {
            $passenger = Passenger::findOrFail($request->id);
            return response()->json($passenger, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Passenger not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching passenger: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching passenger: ' . $e->getMessage()], 500);
        }
    }

    // Update passenger information
    public function update(Request $request)
    {
        try {
            $passenger = Passenger::findOrFail($request->id);

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:passengers,email,' . $passenger->id,
                'password' => 'nullable|string|min:8|confirmed',
                'phone_number' => 'nullable|string|max:20',
            ]);

            $passenger->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => $request->password ? Hash::make($request->password) : $passenger->password,
            ]);

            return response()->json(['message' => 'Passenger updated successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Passenger not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Error updating passenger: ' . $e->getMessage());
            return response()->json(['error' => 'Error updating passenger: ' . $e->getMessage()], 500);
        }
    }

    // Delete a passenger
    public function destroy(Request $request)
    {
        try {
            $passenger = Passenger::findOrFail($request->id);
            $passenger->delete();

            return response()->json(['message' => 'Passenger deleted successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Passenger not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting passenger: ' . $e->getMessage());
            return response()->json(['error' => 'Error deleting passenger: ' . $e->getMessage()], 500);
        }
    }
}
