<?php

namespace App\Http\Controllers\API;

use App\Models\Payment;
use App\Models\Trip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Passenger;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentApiController extends Controller
{
    // Create a new payment
    public function store(Request $request)
    {
        try {
            $request->validate([
                'trip_id' => 'required|exists:trips,id',
                'amount' => 'required|numeric',
                'payment_method' => 'required|string|max:255',
                'payment_status' => 'required|string|in:PENDING,COMPLETED,FAILED',
            ]);

            $payment = Payment::create([
                'trip_id' => $request->trip_id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status,
            ]);

            return response()->json([
                'message' => 'Payment created successfully.',
                'payment' => $payment,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating payment: ' . $e->getMessage());
            return response()->json(['error' => 'Error creating payment: ' . $e->getMessage()], 500);
        }
    }

    // Retrieve payment details by ID
    public function show(Request $request)
    {
        try {
            $payment = Payment::findOrFail($request->id);
            return response()->json($payment, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Payment not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching payment: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching payment: ' . $e->getMessage()], 500);
        }
    }

    // Update payment details
    public function update(Request $request)
    {
        try {
            $payment = Payment::findOrFail($request->id);

            $request->validate([
                'amount' => 'nullable|numeric',
                'payment_method' => 'nullable|string|max:255',
                'payment_status' => 'nullable|string|in:PENDING,COMPLETED,FAILED',
            ]);

            $payment->update([
                'amount' => $request->amount ?? $payment->amount,
                'payment_method' => $request->payment_method ?? $payment->payment_method,
                'payment_status' => $request->payment_status ?? $payment->payment_status,
            ]);

            return response()->json([
                'message' => 'Payment updated successfully.',
                'payment' => $payment,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Payment not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Error updating payment: ' . $e->getMessage());
            return response()->json(['error' => 'Error updating payment: ' . $e->getMessage()], 500);
        }
    }

    // Delete payment by ID
    public function destroy(Request $request)
    {
        try {
            $payment = Payment::findOrFail($request->id);
            $payment->delete();

            return response()->json(['message' => 'Payment deleted successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Payment not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting payment: ' . $e->getMessage());
            return response()->json(['error' => 'Error deleting payment: ' . $e->getMessage()], 500);
        }
    }

    // List all payments (optionally for a specific trip)
    public function index(Request $request)
    {
        try {
            $tripId = $request->trip_id;

            $payments = $tripId
                ? Payment::where('trip_id', $tripId)->get()
                : Payment::all();

            return response()->json($payments, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching payments: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching payments: ' . $e->getMessage()], 500);
        }
    }

    // Retrieve all payments for a specific passenger
    public function paymentsByPassenger(Request $request)
    {
        try {
            $passenger = Passenger::findOrFail($request->passengerId);
            $passengerId = $request->passengerId;
            $payments = Payment::whereHas('trip', function ($query) use ($passengerId) {
                $query->where('passenger_id', $passengerId);
            })->get();

            return response()->json($payments, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Passenger not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching payments for passenger: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching payments: ' . $e->getMessage()], 500);
        }
    }

    // Retrieve all payments for a specific driver
    public function paymentsByDriver(Request $request)
    {
        try {
            $driver = Driver::findOrFail($request->driverId);
            $driverId = $request->passengerId;
            $payments = Payment::whereHas('trip', function ($query) use ($driverId) {
                $query->where('driver_id', $driverId);
            })->get();

            return response()->json($payments, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Driver not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching payments for driver: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching payments: ' . $e->getMessage()], 500);
        }
    }
}
