@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('admin.routes.index') }}" class="text-gray-500 hover:text-gray-700 text-sm mb-2 inline-block">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Rute Baru</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6" x-data="routeForm()">
        <form action="{{ route('admin.routes.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kota Asal</label>
                    <input type="text" name="origin" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required placeholder="e.g. Semarang">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kota Tujuan</label>
                    <input type="text" name="destination" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required placeholder="e.g. Purworejo">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Min. Estimasi Harga</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                        <input type="number" name="price_min" class="w-full pl-10 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required value="75000">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max. Estimasi Harga</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                        <input type="number" name="price_max" class="w-full pl-10 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required value="85000">
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Titik Antar (Drop Off Points)</label>
                <div class="space-y-3 bg-gray-50 p-4 rounded-lg border border-dashed border-gray-300">
                    <template x-for="(point, index) in points" :key="index">
                        <div class="flex items-center gap-2">
                            <input type="text" 
                                   :name="'drop_off_points[' + index + ']'" 
                                   x-model="point.name"
                                   class="flex-1 px-4 py-2 border rounded-lg focus:ring-blue-500 text-sm" 
                                   placeholder="Nama Lokasi / Kecamatan" required>
                            
                            <button type="button" @click="removePoint(index)" class="text-red-400 hover:text-red-600 p-2" x-show="points.length > 1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </template>
                </div>
                <button type="button" @click="addPoint()" class="mt-3 text-sm text-blue-600 font-medium hover:text-blue-800 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Lokasi
                </button>
            </div>

            <div class="pt-6 border-t mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.routes.index') }}" class="px-5 py-2 text-gray-600 font-medium hover:bg-gray-100 rounded-lg">Batal</a>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-sm transition">
                    Simpan Rute
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function routeForm() {
    return {
        points: [
            { name: '' }
        ],
        addPoint() {
            this.points.push({ name: '' });
        },
        removePoint(index) {
            this.points.splice(index, 1);
        }
    }
}
</script>
@endsection