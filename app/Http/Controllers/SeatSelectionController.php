<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class SeatSelectionController extends Controller
{
    // 1. Show Form Check-in
    public function showSearchForm()
    {
        return view('booking.checkin');
    }

    // 2. Validate Booking Code & Show Seat Map
    public function checkBooking(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:bookings,code',
            'phone' => 'required|string',
        ]);

        // Cari bookingan
        $booking = Booking::where('code', $request->code)
                    ->where('user_phone', $request->phone)
                    ->first();

        if (!$booking) {
            return back()->with('error', 'Kode booking atau nomor HP tidak cocok.');
        }

        // Cek Status: Harus 'assigned' atau 'paid'
        if (!in_array($booking->status, ['assigned', 'paid', 'seated'])) {
            return back()->with('error', 'Pesanan Anda belum mendapatkan Armada dari Admin. Mohon tunggu konfirmasi WhatsApp.');
        }

        // Kalau sudah selesai pilih kursi
        if ($booking->status == 'seated') {
            return redirect()->route('booking.ticket', $booking->code);
        }

        // Load data kursi yang sudah terisi di Jadwal (Schedule) Mobil yg sama
        $occupiedSeats = BookingSeat::whereHas('booking', function($query) use ($booking) {
            $query->where('schedule_id', $booking->schedule_id);
        })->pluck('seat_number')->toArray();

        // Load Fleet Layout
        $schedule = $booking->schedule;
        $fleet = $schedule->fleet;

        return view('booking.select_seat', compact('booking', 'schedule', 'fleet', 'occupiedSeats'));
    }

    // 3. Store Selected Seats
    public function storeSeats(Request $request, $code)
    {
        $booking = Booking::where('code', $code)->firstOrFail();
        
        $request->validate([
            'seats' => ['required', 'array', 'size:' . $booking->quantity], // Wajib pilih sesuai jumlah penumpang
            'seats.*' => 'required|integer' // Kursi berupa nomor
        ]);

        // Cek lagi takutnya barusan diambil orang lain
        $isTaken = BookingSeat::whereHas('booking', function($q) use ($booking) {
                        $q->where('schedule_id', $booking->schedule_id);
                    })->whereIn('seat_number', $request->seats)->exists();

        if ($isTaken) {
            return back()->with('error', 'Salah satu kursi yang dipilih baru saja diambil orang lain.');
        }

        DB::transaction(function () use ($booking, $request) {
            foreach ($request->seats as $seatNum) {
                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'seat_number' => $seatNum
                ]);
            }
            
            $booking->update(['status' => 'seated']);
        });

        return redirect()->route('booking.ticket', $booking->code);
    }

    // 4. Show E-Ticket
    public function showTicket($code)
    {
        $booking = Booking::with(['schedule.fleet', 'seats', 'route', 'dropOffPoint'])->where('code', $code)->firstOrFail();
        $adminWa = Setting::where('key', 'admin_whatsapp')->value('value') ?? '6281234567890';
        
        return view('booking.ticket', compact('booking', 'adminWa'));
    }
}
