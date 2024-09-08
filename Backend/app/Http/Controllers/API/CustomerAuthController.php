<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:customers',
                'password' => 'required|string|min:8|confirmed',
                'phone_number' => 'required|string|max:20',
            ]);

            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'user_type' => 'customer',
            ]);

            $token = $customer->createToken('customer-token')->plainTextToken;

            return response()->json([
                'customer' => $customer,
                'token' => $token,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred during registration',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Login an existing customer with exception handling
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $customer = Customer::where('email', $request->email)->first();

            if (! $customer || ! Hash::check($request->password, $customer->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $customer->createToken('customer-token')->plainTextToken;

            return response()->json([
                'customer' => $customer,
                'token' => $token,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Invalid credentials',
                'messages' => $e->errors(),
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred during login',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Logout the customer (delete token) with exception handling
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Logged out successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred during logout',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
