<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Fleet;
use App\Models\Schedule;
use App\Models\Route as TravelRoute;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScheduleManagementController extends Controller
{
    // List dates with pending bookings
    public function index()
    {
        $dates = Booking::where('status', 'pending')
                    ->select('travel_date', DB::raw('count(*) as total'))
                    ->groupBy('travel_date')
                    ->orderBy('travel_date')
                    ->get();
        
        return view('admin.schedules.index', compact('dates'));
    }

    // View specific date pool & Create Schedule
    public function show($date)
    {
        $date = Carbon::parse($date);
        
        // Passengers pending for this date
        $pendingBookings = Booking::whereDate('travel_date', $date)
                            ->where('status', 'pending')
                            ->orderBy('created_at')
                            ->with(['route', 'dropOffPoint'])
                            ->get();
        
        // Existing Schedules for this date
        $existingSchedules = Schedule::whereDate('date', $date)
                            ->with(['fleet', 'bookings.dropOffPoint', 'route'])
                            ->get();

        $fleets = Fleet::all();
        $routes = TravelRoute::all();

        return view('admin.schedules.daily_pool', compact('date', 'pendingBookings', 'existingSchedules', 'fleets', 'routes'));
    }

    // Store new Schedule & Assign selected bookings
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'fleet_id' => 'required|exists:fleets,id',
            'route_id' => 'required|exists:routes,id',
            'price' => 'required|integer',
            'departure_time' => 'required',
            'booking_ids' => 'required|array|min:1'
        ]);

        DB::transaction(function () use ($request) {
            // 1. Create Schedule
            $schedule = Schedule::create([
                'route_id' => $request->route_id,
                'fleet_id' => $request->fleet_id,
                'date' => $request->date,
                'departure_time' => $request->departure_time,
                'price' => $request->price,
                'status' => 'ready'
            ]);

            // 2. Assign Bookings to this Schedule
            $bookings = Booking::whereIn('id', $request->booking_ids)->get();
            
            foreach($bookings as $booking) {
                $booking->update([
                    'schedule_id' => $schedule->id,
                    'status' => 'assigned', // Status berubah -> User bisa pilih kursi
                    'total_price' => $request->price * $booking->quantity
                ]);
            }
        });

        return redirect()->route('admin.schedules.show', ['date' => $request->date])
                         ->with('success', 'Armada berhasil diatur! Penumpang kini bisa pilih kursi.');
    }

    // Add passengers to existing schedule
    public function addPassengers(Request $request, Schedule $schedule)
    {
        $request->validate([
            'booking_ids' => 'required|array|min:1'
        ]);

        $currentPassengers = $schedule->bookings->sum('quantity');
        $newPassengers = Booking::whereIn('id', $request->booking_ids)->sum('quantity');
        
        if (($currentPassengers + $newPassengers) > $schedule->fleet->capacity) {
            return back()->with('error', 'Gagal! Kapasitas armada tidak cukup.');
        }

        DB::transaction(function () use ($request, $schedule) {
            $bookings = Booking::whereIn('id', $request->booking_ids)->get();
            
            foreach($bookings as $booking) {
                // Update booking
                $booking->update([
                    'schedule_id' => $schedule->id,
                    'status' => 'assigned',
                    'total_price' => $schedule->price * $booking->quantity
                ]);
            }
        });

        return back()->with('success', 'Penumpang berhasil ditambahkan ke jadwal ini.');
    }
}
