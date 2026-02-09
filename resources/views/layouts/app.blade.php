<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ config('app.name', 'Travel Booking') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }
        [x-cloak] { display: none !important; }
        
        /* iOS Input Style Override */
        input, select, textarea {
            font-size: 16px !important; /* Prevents zoom on focus */
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex flex-col">

    <header class="bg-white sticky top-0 z-50 border-b border-gray-100 shadow-sm" x-data="{ open: false }">
        <div class="max-w-md mx-auto px-4 h-16 flex items-center justify-between relative">
            <a href="{{ route('home') }}" class="flex items-center gap-2 decoration-0">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                    T
                </div>
                <h1 class="font-bold text-lg text-gray-900 tracking-tight">Travel<span class="text-blue-600">App</span></h1>
            </a>
            
            <button @click="open = !open" class="text-gray-500 hover:text-blue-600 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" @click.away="open = false" x-transition 
                 class="absolute top-16 right-4 w-56 bg-white border border-gray-100 rounded-xl shadow-xl py-2 z-50">
                
                <a href="{{ route('booking.checkin') }}" class="block px-4 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50">
                    🔍 Cek Pesanan / Pilih Kursi
                </a>
                <div class="h-px bg-gray-100 my-1"></div>
                
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Dashboard Admin</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Login Admin</a>
                @endauth
            
            </div>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-100 py-6 mt-8">
        <div class="max-w-md mx-auto px-4 text-center text-gray-400 text-sm">
            &copy; {{ date('Y') }} TravelApp. All rights reserved.
        </div>
    </footer>

</body>
</html>