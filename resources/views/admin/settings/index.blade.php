@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Pengaturan Aplikasi</h1>
        <p class="text-gray-500 text-sm">Kelola konfigurasi umum aplikasi.</p>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg border border-green-200 text-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">Kontak Admin</h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp Admin</label>
                        <p class="text-xs text-gray-500 mb-2">Gunakan format internasional (contoh: 6281xxxx).</p>
                        <input type="text" name="admin_whatsapp" value="{{ $settings['admin_whatsapp'] ?? '' }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t mt-6 flex justify-end">
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-sm transition">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection