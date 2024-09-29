<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Payment;
use App\Models\Trip;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDrivers = Driver::count();
        $activeTrips = Trip::where('status', 'CONFIRMED')->count();
        $completedTrips = Trip::where('status', 'COMPLETED')->count();
        $availableDrivers = Driver::where('status', 'AVAILABLE')->count();
        $totalPayments = Payment::sum('amount');
        $revenue = Payment::where('payment_status', 'PAID')->sum('amount');

        return view('dashboard', [
            'totalDrivers' => $totalDrivers,
            'activeTrips' => $activeTrips,
            'completedTrips' => $completedTrips,
            'availableDrivers' => $availableDrivers,
            'totalPayments' => $totalPayments,
            'revenue' => $revenue,
        ]);
    }
}
