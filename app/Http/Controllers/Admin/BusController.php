<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    /**
     * Display a listing of buses.
     */
    public function index()
    {
        $buses = Bus::latest()->paginate(25);
        return view('admin.buses.index', compact('buses'));
    }

    /**
     * Show the form for creating a new bus.
     */
    public function create()
    {
        return view('admin.buses.create');
    }

    /**
     * Store a newly created bus in storage.
     */
    public function store(Request $request)
{
    $data = $request->validate([
        'plate_number' => 'required|string|max:20|unique:buses,plate_number',
        'seat_count'   => 'required|integer|min:4|max:100',
        'type'         => 'required|in:Seat,Sleeper,Limousine',
        'status'       => 'nullable|in:0,1',
    ]);

    // Chuẩn hóa type về lowercase để khớp với Bus::getTypeLabelAttribute (seat/sleeper/limousine)
    $data['type'] = strtolower($data['type']); // "Seat" -> "seat"

    // Nếu không gửi status thì mặc định là active (1)
    $data['status'] = isset($data['status']) ? (int) $data['status'] : 1;

    // Tạo xe
    $bus = Bus::create($data);

    // 🔥 Sinh ghế mặc định cho xe này (A01..Bxx tuỳ seat_count & type)
    $bus->generateDefaultSeats();

    return redirect()
        ->route('admin.buses.index')
        ->with('success', 'Tạo xe và sơ đồ ghế thành công!');
}


    /**
     * Display the specified bus.
     */
    public function show(Bus $bus)
    {
        return view('admin.buses.show', compact('bus'));
    }

    /**
     * Show the form for editing the specified bus.
     */
    public function edit(Bus $bus)
    {
        return view('admin.buses.edit', compact('bus'));
    }

    /**
     * Update the specified bus in storage.
     */
    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            'plate_number' => 'required|string|max:20|unique:buses,plate_number,' . $bus->id,
            'seat_count'   => 'required|integer|min:4|max:100',
            'type'     => 'required|in:Seat,Sleeper,Limousine',
            'status'       => 'nullable|in:0,1',
        ]);

        $bus->update($request->only(['plate_number', 'seat_count', 'type', 'status']));

        return redirect()->route('admin.buses.index')
            ->with('success', 'Bus updated successfully!');
    }

    /**
     * Remove the specified bus from storage.
     */
    public function destroy(Bus $bus)
    {
        // Check if bus is assigned to any trips
        if ($bus->trips()->count() > 0) {
            return redirect()->route('admin.buses.index')
                ->withErrors('Cannot delete this bus because it is assigned to trips!');
        }

        // Hard delete
        $bus->forceDelete();

        return redirect()->route('admin.buses.index')
            ->with('success', 'Bus deleted successfully!');
    }
}
