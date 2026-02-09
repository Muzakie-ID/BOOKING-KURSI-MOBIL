@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Armada</h1>
            <p class="text-gray-500 text-sm">Daftar mobil yang tersedia untuk operasional.</p>
        </div>
        <a href="{{ route('admin.fleets.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            + Tambah Armada
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg border border-green-200 text-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-500">
            <thead class="bg-gray-50 text-gray-900 font-semibold border-b">
                <tr>
                    <th class="px-6 py-4">Nama Mobil</th>
                    <th class="px-6 py-4">Tipe</th>
                    <th class="px-6 py-4 text-center">Kapasitas</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($fleets as $fleet)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $fleet->name }}</td>
                    <td class="px-6 py-4 capitalize">
                        <span class="px-2 py-1 rounded text-xs font-medium 
                            @if($fleet->type == 'small') bg-blue-100 text-blue-700 
                            @elseif($fleet->type == 'standard') bg-green-100 text-green-700 
                            @else bg-purple-100 text-purple-700 @endif">
                            {{ $fleet->type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">{{ $fleet->capacity }} Kursi</td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.fleets.edit', $fleet) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                        <form action="{{ route('admin.fleets.destroy', $fleet) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus armada ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                        Belum ada data armada.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection