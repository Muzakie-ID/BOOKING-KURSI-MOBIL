@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 py-6" x-data="bookingForm()">
    
    <!-- Intro / Hero -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-1">Mau ke mana hari ini?</h2>
        <p class="text-gray-500 text-sm">Pesan tiket travel mudah, bayar belakangan.</p>
    </div>

    <!-- The Form Card -->
    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 overflow-hidden border border-gray-50">
        <form action="{{ route('booking.store') }}" method="POST" class="p-5 space-y-5">
            @csrf

            <!-- 1. Pilih Rute -->
            <div class="space-y-1">
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Rute Perjalanan</label>
                <div class="relative">
                    <select name="route_id" x-model="selectedRouteId" @change="updateDropOffs" class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3 pl-4 pr-10 text-gray-800 font-medium focus:border-blue-500 focus:bg-white focus:ring-0 transition-all cursor-pointer appearance-none">
                        <option value="" disabled>Pilih Kota Asal & Tujuan</option>
                        <template x-for="route in routes" :key="route.id">
                            <option :value="route.id" x-text="route.origin + ' ➝ ' + route.destination"></option>
                        </template>
                    </select>
                    <!-- Custom Arrow Icon -->
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </div>
                </div>
            </div>

            <!-- 2. Tanggal & Penumpang Row -->
            <div class="grid grid-cols-2 gap-4">
                <!-- Tanggal -->
                <div class="space-y-1">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Tanggal</label>
                    <input type="date" name="date" required 
                           min="{{ date('Y-m-d') }}"
                           class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 text-gray-800 font-medium focus:border-blue-500 focus:bg-white focus:ring-0 transition-all text-sm">
                </div>
                
                <!-- Jumlah Penumpang (Stepper) -->
                <div class="space-y-1">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Penumpang</label>
                    <div class="flex items-center bg-gray-50 rounded-xl border border-gray-200">
                        <button type="button" @click="if(quantity > 1) quantity--" class="w-10 h-11 flex items-center justify-center text-gray-500 hover:text-blue-600 active:bg-gray-200 rounded-l-xl transition">-</button>
                        <input type="number" name="quantity" x-model="quantity" readonly class="w-full h-11 text-center bg-transparent border-none p-0 text-gray-900 font-bold focus:ring-0">
                        <button type="button" @click="if(quantity < 10) quantity++" class="w-10 h-11 flex items-center justify-center text-gray-500 hover:text-blue-600 active:bg-gray-200 rounded-r-xl transition">+</button>
                    </div>
                </div>
            </div>

            <!-- Price Info -->
            <div x-show="selectedRouteId" x-transition.opacity class="bg-blue-50 border border-blue-100 rounded-xl p-3 flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 01.67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 11-.671-1.34l.041-.022zM12 9a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                </svg>
                <div class="text-sm text-blue-900">
                    <span class="block font-semibold">Estimasi Harga</span>
                    <span x-text="priceEstimate"></span>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- 3. Lokasi -->
            <div class="space-y-4">
                <!-- Drop Off (Lokasi Turun) -->
                <div class="space-y-1">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Lokasi Turun (Tujuan)</label>
                    <div class="relative">
                        <select name="drop_off_point_id" :disabled="!selectedRouteId" class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3 pl-4 pr-10 text-gray-800 font-medium focus:border-blue-500 focus:bg-white focus:ring-0 transition-all cursor-pointer disabled:opacity-50 appearance-none">
                            <option value="" disabled selected>Pilih Tujuan Turun</option>
                            <template x-for="point in currentDropOffs" :key="point.id">
                                <option :value="point.id" x-text="point.name"></option>
                            </template>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                           <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                </div>

                <!-- Pickup (Jemput Manual) -->
                <div class="space-y-1">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Lokasi Jemput (Lengkap)</label>
                    <textarea name="pickup_location" rows="2" placeholder="Contoh: Depan Indomaret Point, Jl. Pemuda..." class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 text-gray-800 font-medium focus:border-blue-500 focus:bg-white focus:ring-0 transition-all text-sm"></textarea>
                </div>
            </div>
            
            <hr class="border-gray-100">

            <!-- 4. Data Diri -->
            <div class="space-y-4">
                <h3 class="font-bold text-gray-900 text-sm">Data Pemesan</h3>
                <div class="grid grid-cols-1 gap-4">
                    <input type="text" name="user_name" placeholder="Nama Lengkap" class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 text-gray-800 font-medium focus:border-blue-500 focus:bg-white focus:ring-0 transition-all text-sm">
                    <input type="tel" name="user_phone" placeholder="Nomor WhatsApp" class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 text-gray-800 font-medium focus:border-blue-500 focus:bg-white focus:ring-0 transition-all text-sm">
                </div>
            </div>

            <!-- 5. Pembayaran -->
            <div class="space-y-2">
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Pilih Pembayaran</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer relative">
                        <input type="radio" name="payment_method" value="Cash" class="peer sr-only" checked>
                        <div class="p-3 rounded-xl border-2 border-gray-100 bg-white hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition flex items-center justify-center gap-2">
                            <span class="font-semibold text-sm">Bayar Tunai</span>
                        </div>
                    </label>
                    <label class="cursor-pointer relative">
                        <input type="radio" name="payment_method" value="QRIS" class="peer sr-only">
                        <div class="p-3 rounded-xl border-2 border-gray-100 bg-white hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition flex items-center justify-center gap-2">
                            <span class="font-semibold text-sm">QRIS</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/30 active:scale-95 transition-all flex items-center justify-center gap-2">
                    <span>Lanjut ke WhatsApp</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                       <path d="M12.0007 20.4045C16.6433 20.4045 20.4053 16.6425 20.4053 11.9999C20.4053 7.35728 16.6433 3.59521 12.0007 3.59521C7.35805 3.59521 3.59598 7.35728 3.59598 11.9999C3.59598 13.626 4.05777 15.1432 4.86809 16.4446L3.62947 20.0898L7.42085 18.913C8.75624 19.8821 10.3347 20.4045 12.0007 20.4045Z" fill="currentColor" fill-opacity="0.3"></path>
                       <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0007 22C17.5235 22 22.0007 17.5228 22.0007 12C22.0007 6.47715 17.5235 2 12.0007 2C6.47784 2 2.00069 6.47715 2.00069 12C2.00069 14.0768 2.63462 16.0028 3.73177 17.6186L2.30606 21.8152C2.2384 22.0143 2.39527 22.1812 2.6006 22.1287L6.85257 21.0426C8.40698 21.9678 10.1565 22.5 12.0007 22.5V22ZM12.0007 20.4045C10.3347 20.4045 8.75624 19.8821 7.42085 18.913L3.62947 20.0898L4.86809 16.4446C4.05777 15.1432 3.59598 13.626 3.59598 11.9999C3.59598 7.35728 7.35805 3.59521 12.0007 3.59521C16.6433 3.59521 20.4053 7.35728 20.4053 11.9999C20.4053 16.6425 16.6433 20.4045 12.0007 20.4045Z" fill="currentColor"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function bookingForm() {
        return {
            selectedRouteId: '',
            quantity: 1,
            // Inject Data from Backend
            routes: @json($routes),
            currentDropOffs: [],
            priceEstimate: '',

            updateDropOffs() {
                // Determine drop offs based on selected Route
                const route = this.routes.find(r => r.id == this.selectedRouteId);
                if (route) {
                    this.currentDropOffs = route.drop_off_points;
                    
                    // Format price nicely
                    const min = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumSignificantDigits: 3 }).format(route.price_estimate_min);
                    const max = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumSignificantDigits: 3 }).format(route.price_estimate_max);
                    
                    this.priceEstimate = `${min} - ${max}`;
                } else {
                    this.currentDropOffs = [];
                    this.priceEstimate = '';
                }
            }
        }
    }
</script>
@endsection