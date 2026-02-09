@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center -mt-10">
    <div class="w-full max-w-sm bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Admin Login</h1>
            <p class="text-sm text-gray-500">Masuk untuk mengelola travel</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Email</label>
                <input type="email" name="email" class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 text-sm focus:border-blue-500 focus:ring-0" placeholder="admin@travel.com" required>
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Password</label>
                <input type="password" name="password" class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 text-sm focus:border-blue-500 focus:ring-0" placeholder="••••••••" required>
            </div>

            @error('email')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror

            <button type="submit" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 rounded-xl transition shadow-lg mt-2">
                Masuk Dashboard
            </button>
        </form>
        
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-gray-600">← Kembali ke Halaman Utama</a>
        </div>
    </div>
</div>
@endsection