@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8" x-data="scheduleManager()">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.schedules.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pool Harian</h1>
            <p class="text-gray-500">{{ $date->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
        </div>
    </div>
    
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- LEFT: Pool Penumpang (Booking List) -->
        <div class="lg:col-span-1 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-lg text-gray-800">Penumpang Pending</h2>
                <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded">{{ $pendingBookings->sum('quantity') }} Org</span>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden min-h-[400px]">
                @if($pendingBookings->isEmpty())
                    <div class="p-8 text-center text-gray-400 text-sm">
                        Tidak ada penumpang pending.
                    </div>
                @else
                    <ul class="divide-y divide-gray-100">
                        @foreach($pendingBookings->groupBy(fn($b) => $b->route->origin . ' ➝ ' . $b->route->destination) as $routeName => $bookings)
                        @php $routeId = $bookings->first()->route_id; @endphp
                        <li x-show="!selectedRouteFilter || selectedRouteFilter == '{{ $routeId }}'" class="px-4 py-2 bg-blue-50/50 border-b border-gray-100 text-[10px] font-extrabold text-blue-800 uppercase tracking-widest sticky top-0 flex justify-between items-center">
                            <span>{{ $routeName }}</span>
                            <span class="bg-blue-100 text-blue-800 px-1.5 rounded">{{ $bookings->sum('quantity') }}</span>
                        </li>
                        @foreach($bookings as $booking)
                        <li x-show="!selectedRouteFilter || selectedRouteFilter == '{{ $routeId }}'" class="p-3 hover:bg-gray-50 cursor-pointer transition select-none flex items-start gap-3" 
                            @click="toggleSelection({{ $booking->id }}, {{ $booking->quantity }}, '{{ $booking->user_name }}')">
                            
                            <!-- Checkbox Fake UI -->
                            <div class="mt-1 w-5 h-5 rounded border border-gray-300 flex items-center justify-center transition"
                                 :class="selectedIds.includes({{ $booking->id }}) ? 'bg-blue-600 border-blue-600' : 'bg-white'">
                                <svg x-show="selectedIds.includes({{ $booking->id }})" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>

                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $booking->user_name }}</h4>
                                    <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-1.5 py-0.5 rounded">{{ $booking->quantity }} Kursi</span>
                                </div>
                                <!-- Route info moved to header -->
                                <p class="text-xs text-gray-400 mt-1 truncate w-48">
                                    📍 {{ $booking->pickup_location }}
                                </p>
                            </div>
                        </li>
                        @endforeach
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                <p class="text-xs text-blue-800 mb-1">Total Dipilih:</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-bold text-blue-900" x-text="totalSelectedQuery">0</span>
                    <span class="text-sm font-medium text-blue-700">Penumpang</span>
                </div>
                <p class="text-[10px] text-blue-500 mt-1">Segera masukan ke dalam armada.</p>
            </div>
        </div>

        <!-- RIGHT: Schedule Creation Form -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- 1. Form Buat Jadwal Baru -->
            <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
                <h2 class="font-bold text-lg text-gray-900 mb-4 border-b border-gray-100 pb-3">Buat Jawal & Assign Armada</h2>
                
                <form action="{{ route('admin.schedules.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date->format('Y-m-d') }}">
                    
                    <!-- Hidden input for selected booking IDs -->
                    <template x-for="id in selectedIds">
                        <input type="hidden" name="booking_ids[]" :value="id">
                    </template>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        
                        <!-- Rute -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Rute</label>
                            <select name="route_id" x-model="selectedRouteFilter" required class="w-full rounded-xl border-gray-300 text-sm py-2.5">
                                <option value="">Semua Rute</option>
                                @foreach($routes as $r)
                                    <option value="{{ $r->id }}">{{ $r->origin }} -> {{ $r->destination }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Armada -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pilih Unit Mobil</label>
                            <select name="fleet_id" x-model="selectedFleetId" @change="updateFleetInfo" required class="w-full rounded-xl border-gray-300 text-sm py-2.5">
                                <option value="" disabled selected>-- Pilih Mobil --</option>
                                @foreach($fleets as $fleet)
                                    <option value="{{ $fleet->id }}" data-capacity="{{ $fleet->capacity }}">
                                        {{ $fleet->name }} (Kap: {{ $fleet->capacity }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jam Berangkat -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jam Berangkat</label>
                            <input type="time" name="departure_time" required class="w-full rounded-xl border-gray-300 text-sm py-2.5">
                        </div>

                        <!-- Harga Final -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Harga Tiket / Org</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500 text-sm">Rp</span>
                                <input type="number" name="price" required class="w-full rounded-xl border-gray-300 text-sm py-2.5 pl-9" placeholder="80000">
                            </div>
                        </div>
                    </div>

                    <!-- Validation / Status Info -->
                    <div class="bg-gray-50 rounded-xl p-4 mb-5 border border-gray-200">
                        <div class="flex items-center justify-between text-sm mb-2">
                            <span class="text-gray-500">Kapasitas Mobil:</span>
                            <span class="font-bold text-gray-900"><span x-text="currentCapacity">0</span> Kursi</span>
                        </div>
                        <div class="flex items-center justify-between text-sm mb-2">
                            <span class="text-gray-500">Penumpang Dipilih:</span>
                            <span class="font-bold" :class="isOverCapacity ? 'text-red-600' : 'text-gray-900'"><span x-text="totalSelectedQuery">0</span> Orang</span>
                        </div>
                        
                        <div x-show="isOverCapacity" class="mt-2 text-xs text-red-600 font-bold bg-red-50 p-2 rounded">
                            ⚠️ Kapasitas mobil tidak cukup! Kurangi penumpang atau pilih mobil besar.
                        </div>

                        <div x-show="selectedIds.length === 0" class="mt-2 text-xs text-orange-600 font-bold bg-orange-50 p-2 rounded">
                            ⚠️ Pilih minimal 1 penumpang dari daftar di sebelah kiri.
                        </div>
                    </div>

                    <button type="submit" 
                            :disabled="selectedIds.length === 0 || isOverCapacity" 
                            class="w-full bg-gray-900 hover:bg-gray-800 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-3 rounded-xl transition shadow-lg">
                        Simpan & Assign Penumpang
                    </button>
                    <p class="text-center text-xs text-gray-400 mt-3">Penumpang akan otomatis mendapat status "Assigned" dan bisa pilih kursi.</p>
                </form>
            </div>

            <!-- 2. List Jadwal yang Sudah Dibuat -->
            @if($existingSchedules->count() > 0)
            <h3 class="font-bold text-gray-800 text-lg pt-4">Jadwal Terbentuk</h3>
            <div class="space-y-8">
                @foreach($existingSchedules->groupBy(fn($s) => $s->route->origin . ' ➝ ' . $s->route->destination) as $routeName => $schedules)
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-2 h-8 bg-blue-500 rounded-r"></span>
                        <h4 class="font-bold text-gray-700 text-sm">{{ $routeName }}</h4>
                        <span class="text-xs text-gray-400 font-medium">({{ $schedules->count() }} Jadwal)</span>
                    </div>
                    <div class="space-y-4 pl-4 border-l-2 border-gray-100 ml-1">
                        @foreach($schedules as $sched)
                        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="font-bold text-gray-900 flex items-center gap-2">
                                {{ $sched->fleet->name }}
                                <span class="bg-green-100 text-green-700 text-[10px] uppercase font-bold px-2 py-0.5 rounded-full">{{ $sched->status }}</span>
                            </h4>
                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($sched->departure_time)->format('H:i') }} WIB • Rp {{ number_format($sched->price) }}</p>
                        </div>
                        <div class="text-right">
                             <div class="text-xs font-bold text-gray-400 uppercase mb-1">Terisi</div>
                             <span class="text-lg font-bold text-gray-900">{{ $sched->bookings->sum('quantity') }}</span><span class="text-gray-400">/{{ $sched->fleet->capacity }}</span>
                        </div>
                    </div>

                    <!-- Passenger List inside Schedule -->
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-2">Penumpang</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($sched->bookings as $sb)
                                <span class="inline-flex items-center px-2 py-1 rounded bg-white border border-gray-200 text-xs text-gray-600 shadow-sm">
                                    {{ $sb->user_name }} ({{ $sb->quantity }})
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Add to Schedule Action -->
                    <div class="mt-4 pt-4 border-t border-gray-100" x-show="selectedIds.length > 0">
                        @php
                            $filled = $sched->bookings->sum('quantity');
                            $remaining = $sched->fleet->capacity - $filled;
                        @endphp
                        
                        <div x-show="totalSelectedQuery <= {{ $remaining }}">
                            <form action="{{ route('admin.schedules.add_passengers', $sched->id) }}" method="POST">
                                @csrf
                                <template x-for="id in selectedIds">
                                    <input type="hidden" name="booking_ids[]" :value="id">
                                </template>
                                <button type="submit" class="w-full py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold rounded-lg text-sm transition flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Tambahkan Terpilih (<span x-text="totalSelectedQuery"></span>) ke Sini
                                </button>
                            </form>
                        </div>
                        <div x-show="totalSelectedQuery > {{ $remaining }}" class="text-center">
                            <span class="text-xs text-red-400 font-medium cursor-not-allowed">
                                Kapasitas tidak cukup (Sisa: {{ $remaining }}, Dipilih: <span x-text="totalSelectedQuery"></span>)
                            </span>
                        </div>
                    </div>
                </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</div>

<script>
    function scheduleManager() {
        return {
            selectedRouteFilter: '',
            selectedIds: [],
            totalSelectedQuery: 0,
            selectedFleetId: '',
            currentCapacity: 0,

            // Penumpang yang dipilih (ID, Jumlah Kursi yg dibooking)
            passengers: [
                @foreach($pendingBookings as $pb)
                { id: {{ $pb->id }}, qty: {{ $pb->quantity }} },
                @endforeach
            ],

            toggleSelection(id, qty, name) {
                if (this.selectedIds.includes(id)) {
                    this.selectedIds = this.selectedIds.filter(item => item !== id);
                    this.totalSelectedQuery -= qty;
                } else {
                    this.selectedIds.push(id);
                    this.totalSelectedQuery += qty;
                }
            },

            updateFleetInfo(e) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                this.currentCapacity = parseInt(selectedOption.getAttribute('data-capacity')) || 0;
            },

            get isOverCapacity() {
                return this.currentCapacity > 0 && this.totalSelectedQuery > this.currentCapacity;
            }
        }
    }
</script>
@endsection