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
        // Simple init if needed
    }
}
</script>
@endsection