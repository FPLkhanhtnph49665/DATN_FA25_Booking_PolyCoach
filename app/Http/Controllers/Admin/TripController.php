<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\Route;
use App\Models\Bus;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * Hiển thị danh sách chuyến xe
     */
    public function index(Request $request)
    {
        $query = Trip::with(['route', 'bus'])->orderByDesc('ngay_khoi_hanh');

        // Tìm kiếm theo tuyến
        if ($request->filled('route_id')) {
            $query->where('route_id', $request->route_id);
        }

        // Tìm kiếm theo xe
        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }

        // Tìm kiếm theo ngày khởi hành
        if ($request->filled('ngay_khoi_hanh')) {
            $query->whereDate('ngay_khoi_hanh', $request->ngay_khoi_hanh);
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $trips = $query->paginate(10);
        $routes = Route::orderBy('diem_di')->get();
        $buses = Bus::orderBy('bien_so')->get();

        return view('admin.trips.index', compact('trips', 'routes', 'buses'));
    }

    /**
     * Form tạo mới chuyến xe
     */
    public function create()
    {
        $routes = Route::where('trang_thai', 1)->orderBy('diem_di')->get();
        $buses = Bus::where('trang_thai', 1)->orderBy('bien_so')->get();

        return view('admin.trips.create', compact('routes', 'buses'));
    }

    /**
     * Lưu chuyến xe mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'route_id' => 'required|exists:routes,id',
            'bus_id' => 'required|exists:buses,id',
            'ngay_khoi_hanh' => 'required|date',
            'gio_khoi_hanh' => 'required',
            'gia_ve' => 'required|numeric|min:0',
            'trang_thai' => 'required|in:0,1',
            'ngay_den' => 'nullable|date|after_or_equal:ngay_khoi_hanh',
            'gio_den' => 'nullable',
        ]);

        Trip::create($request->all());

        return redirect()->route('admin.trips.index')->with('success', 'Thêm chuyến xe thành công!');
    }

    /**
     * Hiển thị chi tiết chuyến xe
     */
    public function show(Trip $trip)
    {
        $trip->load(['route', 'bus']);
        return view('admin.trips.show', compact('trip'));
    }
    public function search(Request $request)
    {
        $keyword = $request->input('keyword'); // ví dụ tìm theo từ khóa
        $trips = Trip::where('name', 'like', "%{$keyword}%")->get(); // hoặc theo cột phù hợp

        return view('admin.trips.index', compact('trips'));
    }


    /**
     * Form chỉnh sửa chuyến xe
     */
    public function edit(Trip $trip)
    {
        $routes = Route::where('trang_thai', 1)->orderBy('diem_di')->get();
        $buses = Bus::where('trang_thai', 1)->orderBy('bien_so')->get();

        return view('admin.trips.edit', compact('trip', 'routes', 'buses'));
    }

    /**
     * Cập nhật chuyến xe
     */
    public function update(Request $request, Trip $trip)
    {
        $request->validate([
            'route_id' => 'required|exists:routes,id',
            'bus_id' => 'required|exists:buses,id',
            'ngay_khoi_hanh' => 'required|date',
            'gio_khoi_hanh' => 'required',
            'gia_ve' => 'required|numeric|min:0',
            'trang_thai' => 'required|in:0,1',
            'ngay_den' => 'nullable|date|after_or_equal:ngay_khoi_hanh',
            'gio_den' => 'nullable',
        ]);

        $trip->update($request->all());

        return redirect()->route('admin.trips.index')->with('success', 'Cập nhật chuyến xe thành công!');
    }

    /**
     * Xóa mềm chuyến xe
     */
    public function destroy(Trip $trip)
    {
        $trip->delete();

        return redirect()->route('admin.trips.index')->with('success', 'Đã chuyển chuyến xe vào thùng rác!');
    }

    /**
     * Hiển thị danh sách chuyến xe đã xóa mềm
     */
    public function trash() {}
}
