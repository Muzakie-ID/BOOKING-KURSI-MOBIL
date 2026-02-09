<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fleet;
use Illuminate\Http\Request;

class FleetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fleets = Fleet::all();
        return view('admin.fleets.index', compact('fleets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.fleets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:small,standard,large',
            'capacity' => 'required|integer|min:1',
            // row_layout JSON validation is tricky, we'll handle it via logic or assume standard input
        ]);

        $rows = [];
        if ($request->filled('rows')) {
            foreach($request->rows as $index => $seatCount) {
                $startSeat = 1;
                // Calculate start seat based on previous rows
                if($index > 0) {
                    $prevSeats = 0;
                    for($i=0; $i<$index; $i++) $prevSeats += $request->rows[$i];
                    $startSeat = $prevSeats + 1;
                }
                
                $seats = range($startSeat, $startSeat + $seatCount - 1);
                $rows[] = [
                    'label' => 'Baris ' . ($index + 1),
                    'seats' => $seats
                ];
            }
        }

        Fleet::create([
            'name' => $request->name,
            'type' => $request->type,
            'capacity' => $request->capacity,
            'row_layout' => $rows // Laravel casts this automatically
        ]);

        return redirect()->route('admin.fleets.index')->with('success', 'Armada berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fleet $fleet)
    {
        return view('admin.fleets.edit', compact('fleet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fleet $fleet)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:small,standard,large',
            'capacity' => 'required|integer|min:1',
        ]);

        // Re-generate layout if rows provided (Simplified logic)
        $data = [
            'name' => $request->name,
            'type' => $request->type,
            'capacity' => $request->capacity,
        ];

        // Only update layout if specifically requested/changed logic needed
        // For now let's keep it simple: if rows sent, update layout.
        if ($request->filled('rows')) {
            $rows = [];
            $currentSeat = 1;
            foreach($request->rows as $index => $seatCount) {
                $seats = range($currentSeat, $currentSeat + $seatCount - 1);
                $rows[] = [
                    'label' => 'Baris ' . ($index + 1),
                    'seats' => $seats
                ];
                $currentSeat += $seatCount;
            }
            $data['row_layout'] = $rows;
        }

        $fleet->update($data);

        return redirect()->route('admin.fleets.index')->with('success', 'Armada berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fleet $fleet)
    {
        $fleet->delete();
        return redirect()->route('admin.fleets.index')->with('success', 'Armada dihapus.');
    }
}
