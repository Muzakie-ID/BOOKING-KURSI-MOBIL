<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Schedule;

class DashboardController extends Controller
{
    public function index()
    {
        // Simple dashboard data
        $todayBookings = Booking::whereDate('travel_date', today())->count();
        $todayPassengers = Booking::whereDate('travel_date', today())->sum('quantity');
        
        $pendingBookings = Booking::where('status', 'pending')->sum('quantity');
        
        // Future stats
        $tomorrowBookings = Booking::whereDate('travel_date', today()->addDay())->sum('quantity');

        // Resources
        $totalFleets = \App\Models\Fleet::count();
        $totalRoutes = \App\Models\Route::count();

        return view('admin.dashboard.index', compact(
            'todayBookings', 
            'todayPassengers', 
            'pendingBookings', 
            'tomorrowBookings',
            'totalFleets',
            'totalRoutes'
        ));
    }
}
