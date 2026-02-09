@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('admin.routes.index') }}" class="text-gray-500 hover:text-gray-700 text-sm mb-2 inline-block">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Rute: {{ $route->origin }} - {{ $route->destination }}</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6" x-data="routeEditForm()">
        <form action="{{ route('admin.routes.update', $route) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kota Asal</label>
                    <input type="text" name="origin" value="{{ $route->origin }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kota Tujuan</label>
                    <input type="text" name="destination" value="{{ $route->destination }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Min. Estimasi Harga</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                        <input type="number" name="price_min" value="{{ $route->price_estimate_min }}" class="w-full pl-10 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max. Estimasi Harga</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                        <input type="number" name="price_max" value="{{ $route->price_estimate_max }}" class="w-full pl-10 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Titik Antar (Drop Off Points)</label>
                <div class="space-y-3 bg-gray-50 p-4 rounded-lg border border-dashed border-gray-300">
                    
                    <!-- Existing Points -->
                    <template x-for="(point, index) in existingPoints" :key="'existing-' + index">
                        <div class="flex items-center gap-2">
                            <input type="text" 
                                   x-model="point.name"
                                   :name="'points[' + point.id + ']'"
                                   class="flex-1 px-4 py-2 border rounded-lg focus:ring-blue-500 text-sm bg-white" 
                                   placeholder="Nama Lokasi" required>
                            
                            <button type="button" @click="removeExistingPoint(index)" class="text-red-400 hover:text-red-600 p-2" title="Hapus Lokasi">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </template>

                    <!-- New Points -->
                    <template x-for="(point, index) in newPoints" :key="'new-' + index">
                        <div class="flex items-center gap-2">
                            <input type="text" 
                                   name="new_points[]" 
                                   x-model="point.name"
                                   x-bind:value="point.name"
                                   class="flex-1 px-4 py-2 border rounded-lg focus:ring-blue-500 text-sm" 
                                   placeholder="Lokasi Baru (Wajib Diisi)" required>
                            
                            <button type="button" @click="removeNewPoint(index)" class="text-red-400 hover:text-red-600 p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </template>
                    
                </div>
                <button type="button" @click="addNewPoint()" class="mt-3 text-sm text-blue-600 font-medium hover:text-blue-800 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Lokasi
                </button>
            </div>

            <div class="pt-6 border-t mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.routes.index') }}" class="px-5 py-2 text-gray-600 font-medium hover:bg-gray-100 rounded-lg">Batal</a>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function routeEditForm() {
    return {
        existingPoints: @json($route->dropOffPoints),
        newPoints: [],
        
        addNewPoint() {
            this.newPoints.push({ name: '' });
        },
        removeNewPoint(index) {
            this.newPoints.splice(index, 1);
        },
        removeExistingPoint(index) {
            // In a real app, you might want to mark for deletion instead of removing from DOM immediately
            // But our Controller handles "missing IDs" as deletions, so removing from DOM is correct.
            if(confirm('Lokasi akan dihapus setelah disimpan. Lanjutkan?')) {
                this.existingPoints.splice(index, 1);
            }
        }
    }
}
</script>
@endsection