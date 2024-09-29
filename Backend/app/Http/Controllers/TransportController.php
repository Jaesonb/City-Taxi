<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransportController extends Controller
{
    // Serve the login page
    public function login()
    {
        return view('login');
    }

    // Serve the driver page
    public function driver()
    {
        return view('driver');
    }

    // Serve the passenger page
    public function passenger()
    {
        return view('passenger');
    }

    // Serve the driver registration page
    public function driverRegister()
    {
        return view('driver-register');
    }

    // Serve the passenger registration page
    public function passengerRegister()
    {
        return view('passenger-register');
    }
}
