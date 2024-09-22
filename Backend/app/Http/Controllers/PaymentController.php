<?php

namespace App\Http\Controllers;

use App\Models\Payment; // Using Payment model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentController extends Controller
{
    public function index()
    {
        try {
            $payments = Payment::all();
            return view('payments.index', compact('payments'));
        } catch (\Exception $e) {
            Log::error('Error fetching payments: ' . $e->getMessage());
            return redirect()->route('payments')->with('error', 'Error fetching payments.');
        }
    }

    public function create()
    {
        return view('payments.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => 'required|string|max:255',
                'amount'=> 'required|string|max:255',
                'payment_method'=> 'required|string|max:255',
                'payment_status'=> 'required|string|max:255',
            ]);

            Passenger::create([
                'booking_id'=> $request->booking_id,
                'amount'=> $request->amount,
                'payment_method'=> $request->payment_method,
                'payment_status'=> $request->payment_status,
            ]);

            return redirect()->route('payments')->with('success', 'Payment created successfully.');
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error creating payment: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()->with('error', 'Error creating payment: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $passenger = Payment::findOrFail($id);
            return view('payments.show', compact('payment'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('payments')->with('error', 'Payment not found.');
        } catch (\Exception $e) {
            Log::error('Error fetching payment: ' . $e->getMessage());
            return redirect()->route('payments')->with('error', 'Error fetching payment details.');
        }
    }

    public function edit($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            return view('payments.edit', compact('payment'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('payments')->with('error', 'Payment not found.');
        } catch (\Exception $e) {
            Log::error('Error fetching payment for edit: ' . $e->getMessage());
            return redirect()->route('payments')->with('error', 'Error fetching payment for edit.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $payment = Payment::findOrFail($id);

            $request->validate([
                'booking_id' => 'required|string|max:255'.$payment->id,
                'amount'=> 'required|string|max:255',
                'payment_method'=> 'required|string|max:255|(cash,card,online)',
                'payment_status'=> 'required|string|max:255|(pending,paid,failed)',
            ]);

            $payment->update([
                'booking_id'=> $request->booking_id,
                'amount'=> $request->amount,
                'payment_method'=> $request->payment_method,
                'payment_status'=> $request->payment_status,
            ]);

            return redirect()->route('payments')->with('success', 'Payment updated successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('payments')->with('error', 'Payment not found.');
        } catch (\Exception $e) {
            Log::error('Error updating payment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating payment.');
        }
    }

    public function destroy($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            $payment->delete();

            return redirect()->route('payments')->with('success', 'Payment deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('payments')->with('error', 'Payment not found.');
        } catch (\Exception $e) {
            Log::error('Error deleting payment: ' . $e->getMessage());
            return redirect()->route('payments')->with('error', 'Error deleting payment.');
        }
    }

    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            $payments = Payment::where('booking_id', 'like', '%' . $query . '%')
                ->orWhere('amount', 'like', '%' . $query . '%')
                ->get();

            return view('payments.index', compact('payments'));
        } catch (\Exception $e) {
            Log::error('Error searching payments: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error searching payments.');
        }
    }

    public function trips($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            $trips = $payment->trips;

            return view('payments.trips', compact('payment', 'trips'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('payments')->with('error', 'Payment not found.');
        } catch (\Exception $e) {
            Log::error('Error fetching payment trips: ' . $e->getMessage());
            return redirect()->route('payments')->with('error', 'Error fetching trips.');
        }
    }

}
