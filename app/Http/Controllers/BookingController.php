<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Route;
use App\Models\Booking;
use App\Models\DropOffPoint;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $routes = Route::with('dropOffPoints')->get();
        return view('booking.form', compact('routes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'route_id' => 'required|exists:routes,id',
            'date' => 'required|date|after_or_equal:today',
            'quantity' => 'required|integer|min:1|max:10',
            'user_name' => 'required|string|max:255',
            'user_phone' => 'required|string|max:20',
            'pickup_location' => 'required|string',
            'drop_off_point_id' => 'required|exists:drop_off_points,id',
            'payment_method' => 'required|string'
        ]);

        $booking = DB::transaction(function () use ($request) {
            // Check for existing schedule to auto-assign (Logic for later/Phase 2)
            // For now, just create pending booking
            
            return Booking::create([
                'route_id' => $request->route_id,
                'schedule_id' => null, // Admin will assign later
                'travel_date' => $request->date,
                'user_name' => $request->user_name,
                'user_phone' => $request->user_phone,
                'pickup_location' => $request->pickup_location,
                'drop_off_point_id' => $request->drop_off_point_id,
                'quantity' => $request->quantity,
                'total_price' => null, // Admin sets final price
                'payment_method' => $request->payment_method,
                'status' => 'pending',
            ]);
        });

        // Generate WhatsApp Message
        $route = Route::find($request->route_id);
        $dropOff = DropOffPoint::find($request->drop_off_point_id);
        
        $message = "Halo Admin, saya ingin memesan travel:\n\n";
        $message .= "🆔 Kode          : *" . $booking->code . "*\n";
        $message .= "👤 Nama          : " . $request->user_name . "\n";
        $message .= "📅 Tanggal       : " . $request->date . "\n"; // Note: forgot to add date to booking table migration? Let me check.
        $message .= "🚌 Rute          : " . $route->origin . " -> " . $route->destination . "\n";
        $message .= "👥 Jml Penumpang : " . $request->quantity . " Orang\n";
        $message .= "🏠 Jemput        : " . $request->pickup_location . "\n";
        $message .= "📍 Turun         : " . $dropOff->name . "\n";
        $message .= "💳 Pembayaran    : " . $request->payment_method . "\n\n";
        $message .= "Mohon konfirmasi ketersediaan armada. Terima kasih.";

        $adminWa = Setting::where('key', 'admin_whatsapp')->value('value') ?? '6281234567890';
        $waUrl = "https://wa.me/{$adminWa}?text=" . urlencode($message);

        return redirect()->away($waUrl);
    }
}
