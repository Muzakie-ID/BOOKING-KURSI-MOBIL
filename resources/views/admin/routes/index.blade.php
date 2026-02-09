@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Rute & Titik Antar</h1>
            <p class="text-gray-500 text-sm">Kelola rute perjalanan dan titik drop-off yang tersedia.</p>
        </div>
        <a href="{{ route('admin.routes.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            + Tambah Rute
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg border border-green-200 text-sm">
        {{ session('success') }}
    </div>
    @endif
    
    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg border border-red-200 text-sm">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500">
            <thead class="bg-gray-50 text-gray-900 font-semibold border-b">
                <tr>
                    <th class="px-6 py-4 whitespace-nowrap">Asal</th>
                    <th class="px-6 py-4 whitespace-nowrap">Tujuan</th>
                    <th class="px-6 py-4 whitespace-nowrap">Estimasi Harga</th>
                    <th class="px-6 py-4 text-center whitespace-nowrap">Titik Antar</th>
                    <th class="px-6 py-4 text-right whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($routes as $route)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $route->origin }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $route->destination }}</td>
                    <td class="px-6 py-4">
                        Rp {{ number_format($route->price_estimate_min, 0, ',', '.') }} - 
                        Rp {{ number_format($route->price_estimate_max, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $route->drop_off_points_count }} Lokasi
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.routes.edit', $route) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                        <form action="{{ route('admin.routes.destroy', $route) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus rute ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        Belum ada data rute.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection