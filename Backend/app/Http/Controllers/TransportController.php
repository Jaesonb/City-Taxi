<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransportController extends Controller
{
    // Serve the login page
    public function login()
    {
        return view('transport.index');
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


    // Serve the passenger registration page
    public function rideRequest()
    {
        return view('transport.ride-request');
    }
}
