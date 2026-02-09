@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 py-8">
    
    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden relative">
        
        <!-- Header Tiket -->
        <div class="bg-blue-600 p-6 text-white text-center relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-2xl font-bold tracking-tight">E-Ticket</h1>
                <p class="text-blue-100 text-sm mt-1">Simpan bukti tiket ini (Screenshot)</p>
            </div>
            
            <!-- Decor -->
            <div class="absolute top-0 left-0 w-full h-full opacity-10">
                <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="w-full h-full"><path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path></svg>
            </div>
        </div>

        <!-- Body Tiket -->
        <div class="p-6 space-y-6 relative">
            
            <!-- Holes decoration -->
            <div class="absolute -left-3 top-[-12px] w-6 h-6 bg-gray-50 rounded-full"></div>
            <div class="absolute -right-3 top-[-12px] w-6 h-6 bg-gray-50 rounded-full"></div>

            <!-- Kode Booking -->
            <div class="text-center">
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Kode Booking</span>
                <span class="block text-3xl font-black text-gray-900 tracking-wider font-mono select-all">{{ $booking->code }}</span>
            </div>

            <hr class="border-dashed border-gray-200">

            <!-- Detail Perjalanan -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase">Tanggal</span>
                    <span class="block font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->travel_date)->locale('id')->isoFormat('D MMM Y') }}</span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase">Jam</span>
                    <span class="block font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') }} WIB</span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase">Rute</span>
                    <span class="block font-semibold text-gray-900">{{ $booking->route->origin }} ➝ {{ $booking->route->destination }}</span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase">Armada</span>
                    <span class="block font-semibold text-gray-900 capitalize">{{ $booking->schedule->fleet->name }}</span>
                </div>
            </div>

            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                <span class="block text-xs font-bold text-blue-400 uppercase mb-1">Nomor Kursi</span>
                <div class="flex flex-wrap gap-2">
                    @foreach($booking->seats as $seat)
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 text-white font-bold rounded-lg shadow-sm shadow-blue-500/30 text-sm">
                            {{ $seat->seat_number }}
                        </span>
                    @endforeach
                </div>
            </div>

            <!-- Passenger Info -->
            <div class="space-y-1">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Nama Penumpang</span>
                    <span class="font-semibold text-gray-900">{{ $booking->user_name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Lokasi Jemput</span>
                    <span class="font-semibold text-gray-900 text-right truncate w-40">{{ $booking->pickup_location }}</span>
                </div>
                 <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Turun Di</span>
                    <span class="font-semibold text-gray-900 text-right">{{ $booking->dropOffPoint->name }}</span>
                </div>
            </div>
            
            <hr class="border-gray-100">

            <!-- Total Harga -->
            <div class="flex justify-between items-center bg-gray-900 text-white p-4 rounded-xl">
                <span class="text-xs font-medium text-gray-400 uppercase">Total Bayar</span>
                <span class="text-xl font-bold">Rp {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}</span>
            </div>

        </div>
    </div>

    <!-- Actions -->
    <div class="mt-8 space-y-3">
        <a href="https://wa.me/{{ $adminWa }}?text=Halo%20Admin,%20saya%20sudah%20pilih%20kursi%20untuk%20{{ $booking->code }}.%20Mohon%20info%20supir." target="_blank" class="block w-full text-center bg-green-500 hover:bg-green-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-green-500/30 transition-all">
            Kontak Admin (WA)
        </a>
        <a href="{{ route('home') }}" class="block w-full text-center text-gray-500 font-semibold py-3 hover:bg-gray-100 rounded-xl transition">
            Kembali ke Beranda
        </a>
    </div>

</div>
@endsection