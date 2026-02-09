@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 py-6" x-data="seatSelector()">
    
    <!-- Info Bar -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-xl">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    Booking Anda untuk <strong>{{ $booking->quantity }} orang</strong>. <br>
                    Silakan pilih <strong>{{ $booking->quantity }} kursi</strong> pada denah di bawah ini.
                </p>
            </div>
        </div>
    </div>

    <!-- Car Info & Counter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6 flex justify-between items-center">
        <div>
            <h2 class="font-bold text-gray-900 text-sm">Armada</h2>
            <p class="text-xs text-gray-500 font-medium capitalize">{{ $fleet->name }}</p>
            
            <form id="refreshForm" action="{{ route('booking.verify') }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="code" value="{{ $booking->code }}">
                <input type="hidden" name="phone" value="{{ $booking->user_phone }}">
            </form>
            <button type="button" onclick="document.getElementById('refreshForm').submit()" class="text-[10px] text-blue-600 underline mt-1 bg-transparent border-0 p-0 cursor-pointer hover:text-blue-800">
                Refresh Status Kursi ⟳
            </button>
        </div>
        <div class="text-right">
            <span class="block text-[10px] text-gray-400 uppercase tracking-wider">Sisa Pilih</span>
            <span id="seatsRemaining" class="font-bold text-2xl text-blue-600">{{ $booking->quantity }}</span>
        </div>
    </div>

    <!-- Seat Map Visualization -->
    <form action="{{ route('booking.store_seats', $booking->code) }}" method="POST">
        @csrf
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-6 relative">
            
            <div class="space-y-6 mt-6">
                @if($fleet->row_layout)
                    @foreach($fleet->row_layout as $row)
                        <div class="flex justify-center gap-3">
                             <!-- Seat Loop -->
                             @foreach($row['seats'] as $seatNum)
                                @php
                                    $isOccupied = in_array($seatNum, $occupiedSeats);
                                @endphp
                                <label class="relative group">
                                    <input type="checkbox" name="seats[]" value="{{ $seatNum }}" 
                                           class="peer sr-only" 
                                           {{ $isOccupied ? 'disabled' : '' }}
                                           @change="validateMax({{ $booking->quantity }}, $el)">
                                    
                                    <!-- Visual Kursi -->
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center font-bold text-sm transition-all border-2
                                        {{ $isOccupied 
                                            ? 'bg-gray-200 border-gray-200 text-gray-400 cursor-not-allowed' 
                                            : 'bg-white border-gray-200 text-gray-600 hover:border-blue-400 cursor-pointer peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-blue-500/40' 
                                        }}">
                                        {{ $seatNum }}
                                        
                                        <!-- Occupied Cross -->
                                        @if($isOccupied)
                                            <svg class="absolute w-6 h-6 text-gray-400 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        @endif
                                    </div>
                                </label>
                             @endforeach

                             <!-- Driver Seat Integration (Row 1 Only) -->
                             @if($loop->first)
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center font-bold text-[10px] bg-gray-900 text-white cursor-not-allowed border-2 border-gray-900" title="Posisi Sopir">
                                    SOPIR
                                </div>
                             @endif
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Legend -->
            <div class="flex items-center justify-center gap-4 mt-8 pt-4 border-t border-gray-50">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-white border-2 border-gray-200 rounded"></div>
                    <span class="text-xs text-gray-400 font-medium">Kosong</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-gray-200 rounded"></div>
                    <span class="text-xs text-gray-400 font-medium">Terisi</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-blue-600 rounded"></div>
                    <span class="text-xs text-gray-400 font-medium">Pilihanmu</span>
                </div>
            </div>
        </div>

        <button type="submit" id="submitBtn" disabled class="w-full bg-gray-900 hover:bg-gray-800 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-4 rounded-xl shadow-lg transition-all">
            Konfirmasi Kursi
        </button>
    </form>
</div>

<script>
    function seatSelector() {
        return {
            // Functionality handled via vanilla JS below for direct DOM manipulation
        }
    }

    // Vanilla JS Logic for Seat Limit
    const checkboxes = document.querySelectorAll('input[name="seats[]"]');
    const submitBtn = document.getElementById('submitBtn');
    const maxSeats = {{ $booking->quantity }};

    checkboxes.forEach(chk => {
        chk.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('input[name="seats[]"]:checked').length;
            
            // Limit selection
            if (checkedCount > maxSeats) {
                this.checked = false;
                alert('Maksimal memilih ' + maxSeats + ' kursi sesuai pesanan Anda.');
            }

            // Enable/Disable Submit & Update UI
            const currentChecked = document.querySelectorAll('input[name="seats[]"]:checked').length;
            const remaining = maxSeats - currentChecked;
            
            document.getElementById('seatsRemaining').innerText = remaining;
            
            if(currentChecked === maxSeats) {
                submitBtn.disabled = false;
                submitBtn.innerText = "Simpan (" + currentChecked + " Kursi Terpilih)";
                submitBtn.classList.remove('bg-gray-300', 'cursor-not-allowed', 'text-gray-500');
                submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700', 'text-white', 'shadow-lg');
            } else {
                submitBtn.disabled = true;
                submitBtn.innerText = "Pilih " + remaining + " Kursi Lagi";
                submitBtn.classList.add('bg-gray-300', 'cursor-not-allowed', 'text-gray-500');
                submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'text-white', 'shadow-lg');
            }
        });
    });
    
    // Initial State Check
    submitBtn.innerText = "Pilih " + maxSeats + " Kursi Lagi";
    submitBtn.classList.add('bg-gray-300', 'cursor-not-allowed', 'text-gray-500');
</script>
@endsection