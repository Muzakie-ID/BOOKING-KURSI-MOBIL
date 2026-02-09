@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 text-blue-600 rounded-full mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Cek Pesanan & Pilih Kursi</h1>
        <p class="text-gray-500 text-sm mt-2">Masukkan Kode Booking dan Nomor WA Anda untuk memilih kursi.</p>
    </div>

    @if(session('error'))
        <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm font-medium mb-6 text-center border border-red-100">
            {{ session('error') }}
            <p class="text-xs mt-1 text-red-400">Pastikan Admin sudah menghubungi Anda untuk konfirmasi Armada.</p>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-50 overflow-hidden">
        <form action="{{ route('booking.verify') }}" method="POST" class="p-6 space-y-5">
            @csrf
            
            <div class="space-y-1">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Kode Booking</label>
                <input type="text" name="code" placeholder="Cth: BK1A2B3C" required 
                       class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 text-gray-800 font-bold tracking-widest uppercase focus:border-blue-500 focus:bg-white focus:ring-0 transition-all text-center">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Nomor WhatsApp</label>
                <input type="tel" name="phone" placeholder="08xxxxxxxxxx" required 
                       class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 text-gray-800 font-medium focus:border-blue-500 focus:bg-white focus:ring-0 transition-all text-center">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/30 transition-all mt-2">
                Cari Pesanan Saya
            </button>
        </form>
    </div>
    
    <div class="text-center mt-8">
        <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-gray-600">← Kembali ke Halaman Utama</a>
    </div>
</div>
@endsection