@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
            <p class="text-gray-500 text-sm">Selamat datang, {{ Auth::user()->name }}</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="text-sm text-red-600 font-medium hover:text-red-700">Logout</button>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
            <h3 class="text-xs font-semibold text-blue-400 uppercase tracking-wide">Hari Ini</h3>
            <p class="text-2xl font-bold text-blue-900">{{ $todayPassengers }} <span class="text-sm font-normal text-blue-600">Penumpang</span></p>
            <p class="text-xs text-blue-400 mt-1">{{ $todayBookings }} Transaksi</p>
        </div>
        <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100">
            <h3 class="text-xs font-semibold text-indigo-400 uppercase tracking-wide">Besok</h3>
            <p class="text-2xl font-bold text-indigo-900">{{ $tomorrowBookings }} <span class="text-sm font-normal text-indigo-600">Penumpang</span></p>
        </div>
        <div class="bg-orange-50 p-4 rounded-xl border border-orange-100">
            <h3 class="text-xs font-semibold text-orange-400 uppercase tracking-wide">Pending (Pool)</h3>
            <p class="text-2xl font-bold text-orange-900">{{ $pendingBookings }} <span class="text-sm font-normal text-orange-600">Org</span></p>
        </div>
        <div class="bg-green-50 p-4 rounded-xl border border-green-100">
            <h3 class="text-xs font-semibold text-green-400 uppercase tracking-wide">Total Armada</h3>
            <p class="text-2xl font-bold text-green-900">{{ $totalFleets }} <span class="text-sm font-normal text-green-600">Unit</span></p>
        </div>
    </div>

    <!-- Quick Actions (Placeholder for now) -->
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('admin.schedules.index') }}" class="p-4 bg-white border border-gray-200 rounded-xl text-left hover:border-blue-500 hover:shadow-md transition group">
                <span class="block text-gray-900 font-semibold group-hover:text-blue-600">📅 Atur Jadwal & Armada</span>
                <span class="text-xs text-gray-500">Assign mobil untuk penumpang</span>
            </a>
            <a href="{{ route('admin.fleets.index') }}" class="p-4 bg-white border border-gray-200 rounded-xl text-left hover:border-blue-500 hover:shadow-md transition group">
                <span class="block text-gray-900 font-semibold group-hover:text-blue-600">🚘 Kelola Armada</span>
                <span class="text-xs text-gray-500">Tambah/Edit data mobil</span>
            </a>
            <a href="{{ route('admin.routes.index') }}" class="p-4 bg-white border border-gray-200 rounded-xl text-left hover:border-blue-500 hover:shadow-md transition group">
                <span class="block text-gray-900 font-semibold group-hover:text-blue-600">📍 Rute & Lokasi</span>
                <span class="text-xs text-gray-500">Setting titik jemput/turun</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="p-4 bg-white border border-gray-200 rounded-xl text-left hover:border-blue-500 hover:shadow-md transition group">
                <span class="block text-gray-900 font-semibold group-hover:text-blue-600">⚙️ Pengaturan</span>
                <span class="text-xs text-gray-500">No. WhatsApp Admin, dll</span>
            </a>
        </div>
    </div>

</div>
@endsection