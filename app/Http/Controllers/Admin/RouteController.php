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
        $routes = Route::withCount('dropOffPoints')->get();
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
            'drop_off_points' => 'required|array|min:1',
            'drop_off_points.*' => 'required|string|max:255',
        ]);

        $route = Route::create([
            'origin' => $request->origin,
            'destination' => $request->destination,
            'price_estimate_min' => $request->price_min,
            'price_estimate_max' => $request->price_max,
        ]);

        foreach ($request->drop_off_points as $pointName) {
            $route->dropOffPoints()->create(['name' => $pointName]);
        }

        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Route $route)
    {
        $route->load('dropOffPoints');
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
            'points' => 'array', // Existing points with IDs
            'new_points' => 'array', // New points
        ]);

        $route->update([
            'origin' => $request->origin,
            'destination' => $request->destination,
            'price_estimate_min' => $request->price_min,
            'price_estimate_max' => $request->price_max,
        ]);

        // 1. Update existing points
        if ($request->filled('points')) {
            foreach ($request->points as $id => $name) {
                $point = DropOffPoint::find($id);
                if ($point && $point->route_id == $route->id) {
                    $point->update(['name' => $name]);
                }
            }
        }

        // 2. Create new points
        if ($request->filled('new_points')) {
            foreach ($request->new_points as $name) {
                if (!empty($name)) {
                    $route->dropOffPoints()->create(['name' => $name]);
                }
            }
        } else {
            // Fallback for direct input (if array logic fails in some clients)
             if ($request->has('new_points') && is_array($request->new_points)) {
                foreach ($request->new_points as $name) {
                     if (!empty($name)) {
                        $route->dropOffPoints()->create(['name' => $name]);
                    }
                }
             }
        }

        // 3. Handle deletions (Points not present in the 'points' array are processed for deletion)
        // Collect all IDs sent in the request
        $submittedIds = $request->filled('points') ? array_keys($request->points) : [];
        
        // Find points in DB that belong to this route but are NOT in submitted IDs
        $pointsToDelete = $route->dropOffPoints()->whereNotIn('id', $submittedIds)->get();

        foreach ($pointsToDelete as $point) {
            try {
                $point->delete();
            } catch (\Exception $e) {
                // Ignore delete errors (constraint violations) silently for now, or use flash message
                // For better UX, we'd tell them "Some points couldn't be deleted because they are in use"
            }
        }

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
