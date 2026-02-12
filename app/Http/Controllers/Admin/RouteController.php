<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\DropOffPoint;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routes = Route::all();
        return view('admin.routes.index', compact('routes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.routes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'price_min' => 'required|integer|min:0',
            'price_max' => 'required|integer|gte:price_min',
        ]);

        Route::create([
            'origin' => $request->origin,
            'destination' => $request->destination,
            'price_estimate_min' => $request->price_min,
            'price_estimate_max' => $request->price_max,
        ]);

        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Route $route)
    {
        return view('admin.routes.edit', compact('route'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Route $route)
    {
        $request->validate([
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'price_min' => 'required|integer|min:0',
            'price_max' => 'required|integer|gte:price_min',
        ]);

        $route->update([
            'origin' => $request->origin,
            'destination' => $request->destination,
            'price_estimate_min' => $request->price_min,
            'price_estimate_max' => $request->price_max,
        ]);

        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route)
    {
        try {
            $route->delete();
            return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus rute. Mungkin ada data booking terkait.');
        }
    }
}
