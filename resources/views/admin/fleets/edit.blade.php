@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('admin.fleets.index') }}" class="text-gray-500 hover:text-gray-700 text-sm mb-2 inline-block">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Armada: {{ $fleet->name }}</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6" x-data="fleetEditForm()">
        <form action="{{ route('admin.fleets.update', $fleet) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mobil</label>
                    <input type="text" name="name" value="{{ $fleet->name }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe / Kategori</label>
                    <select name="type" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="small" {{ $fleet->type == 'small' ? 'selected' : '' }}>Small (4-5 Seats)</option>
                        <option value="standard" {{ $fleet->type == 'standard' ? 'selected' : '' }}>Standard (7 Seats)</option>
                        <option value="large" {{ $fleet->type == 'large' ? 'selected' : '' }}>Large (Mini Bus/Elf)</option>
                    </select>
                </div>
            </div>

            <!-- Seat Configuration -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">Konfigurasi Kursi</label>
                    <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">Total: <span x-text="totalCapacity"></span> Kursi</span>
                </div>
                
                <input type="hidden" name="capacity" :value="totalCapacity">
                
                <div class="space-y-3 bg-gray-50 p-4 rounded-lg border border-dashed border-gray-300">
                    <template x-for="(row, index) in rows" :key="index">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-semibold text-gray-500 w-16" x-text="'Baris ' + (index + 1)"></span>
                            <input type="number" 
                                   :name="'rows[' + index + ']'" 
                                   x-model="row.seats"
                                   min="1" 
                                   max="5"
                                   class="w-20 px-3 py-1 text-center border rounded focus:ring-blue-500 text-sm" 
                                   placeholder="Qty">
                            <span class="text-sm text-gray-600">Kursi</span>
                            
                            <button type="button" @click="removeRow(index)" class="text-red-400 hover:text-red-600 ml-auto" x-show="rows.length > 1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </template>
                </div>
                
                <button type="button" @click="addRow()" class="mt-3 text-sm text-blue-600 font-medium hover:text-blue-800 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Baris
                </button>
            </div>

            <div class="pt-6 border-t mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.fleets.index') }}" class="px-5 py-2 text-gray-600 font-medium hover:bg-gray-100 rounded-lg">Batal</a>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function fleetEditForm() {
    return {
        rows: [
            @if(is_array($fleet->row_layout) || is_object($fleet->row_layout))
                @foreach($fleet->row_layout as $row)
                { seats: {{ count($row['seats'] ?? []) }} },
                @endforeach
            @else
                // Fallback default
                { seats: 1 }, { seats: 3 }, { seats: 3 }
            @endif
        ],
        addRow() {
            this.rows.push({ seats: 3 });
        },
        removeRow(index) {
            this.rows.splice(index, 1);
        },
        get totalCapacity() {
            return this.rows.reduce((sum, row) => sum + parseInt(row.seats || 0), 0);
        }
    }
}
</script>
@endsection