<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PickupPoint;
use App\Models\DropoffPoint;
use App\Models\Route;
use App\Models\PointFare;
use Illuminate\Http\Request;

class PointFareController extends Controller
{
    /**
     * Hiển thị danh sách giá vé
     */
    public function index()
    {
        $pointFares = PointFare::with(['route', 'route.fromCity', 'route.toCity', 'pickupPoint', 'dropoffPoint'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.point_fares.index', compact('pointFares'));
    }

    /**
     * Hiển thị form tạo giá vé mới
     */
    public function create()
    {
        // Sửa cột orderBy
       $routes = Route::where('status', 1)->orderBy('id')->get();
        $pickupPoints = PickupPoint::orderBy('name')->get();
        $dropoffPoints = DropoffPoint::orderBy('name')->get();

        return view('admin.point_fares.create', compact('routes', 'pickupPoints', 'dropoffPoints'));
    }

    /**
     * Lưu giá vé mới
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'pickup_point_id' => 'required|exists:pickup_points,id',
            'dropoff_point_id' => 'required|exists:dropoff_points,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|boolean',
        ], [
            'route_id.required' => 'Vui lòng chọn tuyến xe.',
            'pickup_point_id.required' => 'Vui lòng chọn điểm đón.',
            'dropoff_point_id.required' => 'Vui lòng chọn điểm trả.',
            'price.required' => 'Vui lòng nhập giá vé.',
        ]);

        PointFare::create($data);

        return redirect()->route('admin.point_fares.index')
            ->with('success', 'Tạo mới giá vé thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa giá vé
     */
    public function edit(PointFare $pointFare)
    {
        $routes = Route::where('status', 1)->orderBy('id')->get();
        $pickupPoints = PickupPoint::orderBy('name')->get();
        $dropoffPoints = DropoffPoint::orderBy('name')->get();

        return view('admin.point_fares.edit', compact('pointFare', 'routes', 'pickupPoints', 'dropoffPoints'));
    }

    /**
     * Cập nhật giá vé
     */
    public function update(Request $request, PointFare $pointFare)
    {
        $data = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'pickup_point_id' => 'required|exists:pickup_points,id',
            'dropoff_point_id' => 'required|exists:dropoff_points,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|boolean',
        ], [
            'route_id.required' => 'Vui lòng chọn tuyến xe.',
            'pickup_point_id.required' => 'Vui lòng chọn điểm đón.',
            'dropoff_point_id.required' => 'Vui lòng chọn điểm trả.',
            'price.required' => 'Vui lòng nhập giá vé.',
        ]);

        $pointFare->update($data);

        return redirect()->route('admin.point_fares.index')
            ->with('success', 'Cập nhật giá vé thành công!');
    }

    /**
     * Xóa giá vé
     */
    public function destroy(PointFare $pointFare)
    {
        $pointFare->delete();

        return redirect()->route('admin.point_fares.index')
            ->with('success', 'Xóa giá vé thành công!');
    }
}
