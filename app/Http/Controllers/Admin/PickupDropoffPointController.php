<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PickupDropoffPoint;
use App\Models\City;
use App\Models\Route;
use Illuminate\Http\Request;

class PickupDropoffPointController extends Controller
{
    public function index(Request $request)
    {
        $query = PickupDropoffPoint::with(['city', 'route']);

        if ($search = $request->string('search')->trim()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city_id') && $request->city_id !== '') {
            $query->where('city_id', (int) $request->city_id);
        }

        if ($request->filled('route_id') && $request->route_id !== '') {
            $query->where('route_id', (int) $request->route_id);
        }

        if ($request->filled('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        if ($request->filled('active') && $request->active !== '') {
            $query->where('active', (int) $request->active);
        }

        $points = $query
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        // 👇 cái view ông đang dùng cần 2 biến này
        $cities = City::orderBy('name')->get();
        $routes = Route::orderBy('id')->get(); // hoặc orderBy('name')

        return view('admin.pickup-dropoff-points.index', compact('points', 'cities', 'routes'));
    }
    public function create()
    {
        $cities = City::orderBy('name')->get();
        $routes = Route::orderBy('id')->get(); // hoặc orderBy('name') nếu có

        return view('admin.pickup-dropoff-points.create', compact('cities', 'routes'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'city_id' => 'nullable|exists:cities,id',
            'route_id' => 'required|exists:routes,id',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'time' => 'nullable|date_format:H:i',
            'type' => 'required|in:pickup,dropoff',
            'active' => 'boolean',
        ]);

        PickupDropoffPoint::create($data);

        return redirect()->route('admin.pickup-dropoff-points.index')->with('success', 'Đã tạo điểm đón/trả!');
    }

    public function show(PickupDropoffPoint $pickupDropoffPoint)
    {
        return view('admin.pickup-dropoff-points.show', compact('pickupDropoffPoint'));
    }

    public function edit(PickupDropoffPoint $pickupDropoffPoint)
    {
        $cities = City::orderBy('name')->get();
        $routes = Route::orderBy('id')->get();

        return view('admin.pickup-dropoff-points.edit', [
            'point'  => $pickupDropoffPoint,
            'cities' => $cities,
            'routes' => $routes,
        ]);
    }


    public function update(Request $request, PickupDropoffPoint $pickupDropoffPoint)
    {
        $data = $request->validate([
            'city_id' => 'nullable|exists:cities,id',
            'route_id' => 'required|exists:routes,id',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'time' => 'nullable|date_format:H:i',
            'type' => 'required|in:pickup,dropoff',
            'active' => 'boolean',
        ]);

        $pickupDropoffPoint->update($data);

        return redirect()->route('admin.pickup-dropoff-points.index')->with('success', 'Đã cập nhật điểm đón/trả!');
    }

    public function destroy(PickupDropoffPoint $pickupDropoffPoint)
    {
        $pickupDropoffPoint->delete();

        return redirect()->route('admin.pickup-dropoff-points.index')->with('success', 'Đã xóa điểm đón/trả!');
    }
}
