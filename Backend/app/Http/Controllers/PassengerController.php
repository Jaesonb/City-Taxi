<?php

namespace App\Http\Controllers;

use App\Mail\PassengerCreated;
use App\Models\Passenger; // Using Passenger model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;

class PassengerController extends Controller
{
    public function index()
    {
        try {
            $passengers = Passenger::all();
            return view('passengers.index', compact('passengers'));
        } catch (\Exception $e) {
            Log::error('Error fetching passengers: ' . $e->getMessage());
            return redirect()->route('passengers')->with('error', 'Error fetching passengers: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('passengers.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:passengers',
                'password' => 'required|string|min:8|confirmed',
                'phone_number' => 'required|string|max:20',
            ]);

            $plainPassword = $request->password;

            $passenger = Passenger::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($plainPassword),
                'phone_number' => $request->phone_number,
            ]);

            // Send email with plain password
            Mail::to($passenger->email)->send(new PassengerCreated($passenger, $plainPassword));

            // Redirect with success message
            return redirect()->route('passengers')->with('success', 'Passenger created successfully.');
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error creating passenger: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()->with('error', 'Error creating passenger: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $passenger = Passenger::findOrFail($id);
            return view('passengers.show', compact('passenger'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('passengers')->with('error', 'Passenger not found.');
        } catch (\Exception $e) {
            Log::error('Error fetching passenger: ' . $e->getMessage());
            return redirect()->route('passengers')->with('error', 'Error fetching passenger details: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $passenger = Passenger::findOrFail($id);
            return view('passengers.edit', compact('passenger'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('passengers')->with('error', 'Passenger not found.');
        } catch (\Exception $e) {
            Log::error('Error fetching passenger for edit: ' . $e->getMessage());
            return redirect()->route('passengers')->with('error', 'Error fetching passenger for edit: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $passenger = Passenger::findOrFail($id);

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
                'password' => $request->password ? $request->password : $passenger->password,
            ]);

            return redirect()->route('passengers')->with('success', 'Passenger updated successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('passengers')->with('error', 'Passenger not found.');
        } catch (\Exception $e) {
            Log::error('Error updating passenger: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating passenger: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $passenger = Passenger::findOrFail($id);
            $passenger->delete();

            return redirect()->route('passengers')->with('success', 'Passenger deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('passengers')->with('error', 'Passenger not found.');
        } catch (\Exception $e) {
            Log::error('Error deleting passenger: ' . $e->getMessage());
            return redirect()->route('passengers')->with('error', 'Error deleting passenger: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            $passengers = Passenger::where('name', 'like', '%' . $query . '%')
                ->orWhere('email', 'like', '%' . $query . '%')
                ->get();

            return view('passengers.index', compact('passengers'));
        } catch (\Exception $e) {
            Log::error('Error searching passengers: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error searching passengers: ' . $e->getMessage());
        }
    }

    public function trips($id)
    {
        try {
            $passenger = Passenger::findOrFail($id);
            $trips = $passenger->trips;

            return view('passengers.trips', compact('passenger', 'trips'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('passengers')->with('error', 'Passenger not found.');
        } catch (\Exception $e) {
            Log::error('Error fetching passenger trips: ' . $e->getMessage());
            return redirect()->route('passengers')->with('error', 'Error fetching trips: ' . $e->getMessage());
        }
    }

    public function showTrips($id)
    {
        $passenger = Passenger::with('trips.payment')->findOrFail($id);

        return view('passengers.trips', compact('passenger'));
    }
}
