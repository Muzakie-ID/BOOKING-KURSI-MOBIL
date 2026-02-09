@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Kelola Jadwal & Booking</h1>
    </div>

    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 flex items-start gap-3">
        <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
        </div>
        <div>
            <h3 class="font-bold text-blue-900">Antrian Booking (Pending)</h3>
            <p class="text-sm text-blue-700">Berikut adalah daftar tanggal yang memiliki penumpang tapi belum mendapatkan armada.</p>
        </div>
    </div>

    @if($dates->isEmpty())
        <div class="text-center py-20 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
            <p class="text-gray-400 font-medium">Belum ada bookingan baru masuk.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($dates as $item)
            <a href="{{ route('admin.schedules.show', ['date' => $item->travel_date]) }}" class="group bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-500 transition flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                        {{ \Carbon\Carbon::parse($item->travel_date)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    </h3>
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-orange-100 text-orange-700 text-xs font-bold">
                        {{ $item->total }} Penumpang Menunggu
                    </div>
                </div>
                <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </a>
            @endforeach
        </div>
    @endif

</div>
@endsection