<?php

namespace App\Http\Controllers;

use App\Mail\DriverCreated;
use App\Mail\PassengerCreated;
use App\Models\Driver;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class TransportController extends Controller
{
    // Serve the login page
    public function login()
    {
        return view('transport.login');
    }

    // Serve the driver page
    public function driver()
    {
        return view('transport.driver');
    }

    // Serve the passenger page
    public function passenger()
    {
        return view('transport.passenger');
    }

    // Serve the driver registration page
    public function driverRegister()
    {
        return view('transport.driver-register');
    }

    // Serve the passenger registration page
    public function passengerRegister()
    {
        return view('transport.passenger-register');
    }

     // Store function for Driver registration
     public function storeDriver(Request $request)
     {
         try {
             // Validate driver registration inputs
             $request->validate([
                 'name' => 'required|string|max:255',
                 'email' => 'required|string|email|max:255|unique:drivers',
                 'password' => 'required|string|min:8|confirmed',
                 'phone_number' => 'required|string|max:20',
                 'vehicle_number' => 'required|string|max:255',
                 'status' => 'required|string|in:available,busy',
                 'color' => 'required|string|max:255|',
                 'model' => 'required|string|max:255|',
                 'brand' => 'required|string|max:255|',
                 'latitude' => 'required|string|max:255',
                 'longitude' => 'required|string|max:255',
             ]);

            $plainPassword = $request->password;

            $driver = Driver::create([
                 'name' => $request->name,
                 'email' => $request->email,
                 'password' => Hash::make($request->password), // Hash the password
                 'phone_number' => $request->phone_number,
                 'vehicle_number' => $request->vehicle_number,
                 'status' => $request->status,
                 'model'=> $request->model,
                 'brand'=> $request->brand,
                 'color'=> $request->color,
                 'latitude'=> $request->latitude,
                 'longitude'=> $request->longitude,
             ]);

             Mail::to($driver->email)->send(new DriverCreated($driver, $plainPassword));

             return redirect('/')->with('success', 'Driver registered successfully.');
         } catch (\Exception $e) {
             return redirect()->back()->with('error', 'Error registering driver: ' . $e->getMessage());
         }
     }

     public function storePassenger(Request $request)
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
                 'password' => Hash::make($request->password),
                 'phone_number' => $request->phone_number,
             ]);

            Mail::to($passenger->email)->send(new PassengerCreated($passenger, $plainPassword));

             return redirect('/')->with('success', 'Passenger registered successfully.');
         } catch (\Exception $e) {
             return redirect()->back()->with('error', 'Error registering passenger: ' . $e->getMessage());
         }
     }
}
