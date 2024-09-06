<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PassengerController extends Controller
{
    public function index()
    {
        try {
            $passengers = Customer::where('user_type', 'passenger')->get();
            return view('passengers.index', compact('passengers'));
        } catch (\Exception $e) {
            Log::error('Error fetching passengers: ' . $e->getMessage());
            return redirect()->route('passengers')->with('error', 'Error fetching passengers.');
        }
    }

    public function create()
    {
        return view('passengers.create');
    }

    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:customers',
                'password' => 'required|string|min:8|confirmed',
                'phone_number' => 'nullable|string|max:20',
            ]);

            // Create a new passenger (Customer model)
            Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'user_type' => 'passenger',
            ]);

            // Redirect with success message
            return redirect()->route('passengers')->with('success', 'Passenger created successfully.');
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error creating passenger: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()->with('error', 'Error creating passenger.' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $passenger = Customer::where('user_type', 'passenger')->findOrFail($id);
            return view('passengers.show', compact('passenger'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('passengers')->with('error', 'Passenger not found.');
        } catch (\Exception $e) {
            Log::error('Error fetching passenger: ' . $e->getMessage());
            return redirect()->route('passengers')->with('error', 'Error fetching passenger details.');
        }
    }

    public function edit($id)
    {
        try {
            $passenger = Customer::where('user_type', 'passenger')->findOrFail($id);
            return view('passengers.edit', compact('passenger'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('passengers')->with('error', 'Passenger not found.');
        } catch (\Exception $e) {
            Log::error('Error fetching passenger for edit: ' . $e->getMessage());
            return redirect()->route('passengers')->with('error', 'Error fetching passenger for edit.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $passenger = Customer::where('user_type', 'passenger')->findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:customers,email,' . $passenger->id,
                'password' => 'nullable|string|min:8|confirmed',
                'phone_number' => 'nullable|string|max:20',
            ]);

            $passenger->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => $request->password ? Hash::make($request->password) : $passenger->password,
            ]);

            return redirect()->route('passengers')->with('success', 'Passenger updated successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('passengers')->with('error', 'Passenger not found.');
        } catch (\Exception $e) {
            Log::error('Error updating passenger: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating passenger.');
        }
    }

    public function destroy($id)
    {
        try {
            $passenger = Customer::where('user_type', 'passenger')->findOrFail($id);
            $passenger->delete();

            return redirect()->route('passengers')->with('success', 'Passenger deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('passengers')->with('error', 'Passenger not found.');
        } catch (\Exception $e) {
            Log::error('Error deleting passenger: ' . $e->getMessage());
            return redirect()->route('passengers')->with('error', 'Error deleting passenger.');
        }
    }

    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            $passengers = Customer::where('user_type', 'passenger')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                        ->orWhere('email', 'like', '%' . $query . '%');
                })
                ->get();

            return view('passengers.index', compact('passengers'));
        } catch (\Exception $e) {
            Log::error('Error searching passengers: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error searching passengers.');
        }
    }

    public function bookings($id)
    {
        try {
            $passenger = Customer::where('user_type', 'passenger')->findOrFail($id);
            $bookings = $passenger->bookings; // Assuming the `Customer` model has a `bookings()` relationship

            return view('passengers.bookings', compact('passenger', 'bookings'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('passengers')->with('error', 'Passenger not found.');
        } catch (\Exception $e) {
            Log::error('Error fetching passenger bookings: ' . $e->getMessage());
            return redirect()->route('passengers')->with('error', 'Error fetching bookings.');
        }
    }
}
