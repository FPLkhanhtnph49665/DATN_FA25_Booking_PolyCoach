<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PickupPoint;
use App\Models\DropoffPoint;
use App\Models\City;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // Dùng cho transaction
use App\Models\PointFare;

class PointFareController extends Controller
{
    //lấy danh sách điểm đón trả

    public function index()
    {
        $pointFares = PointFare::with(['pickupPoint', 'dropoffPoint'])
                                ->paginate(15);
        
        return view('admin.point_fares.index', compact('pointFares'));
    }

    /**
     * Hiển thị form tạo mới. Bắt buộc phải có tham số 'type' (pickup/dropoff).
     */
    public function create(Request $request)
    {
        $type = $request->string('type')->trim();
        if (!in_array($type, ['pickup', 'dropoff'])) {
             return redirect()->route('admin.dashboard')->with('error', 'Vui lòng chọn loại điểm (Đón hoặc Trả).');
        }

        $cities = City::orderBy('name')->get();
        $routes = Route::orderBy('id')->get(); 

        // Truyền type xuống view để view biết đang tạo điểm loại nào
        return view('admin.pickup-dropoff-points.create', compact('cities', 'routes', 'type'));
    }


    /**
     * Lưu điểm mới vào CSDL (table pickup_points HOẶC dropoff_points).
     */
    public function store(Request $request)
    {
        $type = $request->string('type')->trim();
        if (!in_array($type, ['pickup', 'dropoff'])) {
             return back()->with('error', 'Loại điểm không xác định.')->withInput();
        }

        $data = $request->validate([
            'city_id' => 'nullable|exists:cities,id',
            'route_id' => 'required|exists:routes,id',
            // Dùng tên cột thực tế của bạn
            'ten_diem_tra' => 'required|string|max:255', 
            'dia_chi' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0', // Thêm validation cho cột order
            'active' => 'boolean',
        ], [
            'ten_diem_tra.required' => 'Tên điểm không được để trống.',
        ]);

        $model = $this->resolveModel($type);
        $model->create($data);

        return redirect()->route('admin.pickup-dropoff-points.index', ['type' => $type])
            ->with('success', 'Đã tạo điểm ' . ($type === 'pickup' ? 'đón' : 'trả') . ' mới!');
    }

    // Show không thay đổi nhiều, cần dùng Route Model Binding nhưng phức tạp hơn, ta dùng cách thủ công.
    public function show($type, $id)
    {
        $model = $this->resolveModel($type);
        $point = $model->newQuery()->with(['city', 'route'])->findOrFail($id);

        return view('admin.pickup-dropoff-points.show', compact('point', 'type'));
    }

    /**
     * Hiển thị form chỉnh sửa.
     */
    public function edit(Request $request, $type, $id)
    {
        $model = $this->resolveModel($type);
        $point = $model->newQuery()->findOrFail($id);
        
        $cities = City::orderBy('name')->get();
        $routes = Route::orderBy('id')->get();

        return view('admin.pickup-dropoff-points.edit', [
            'point'  => $point,
            'cities' => $cities,
            'routes' => $routes,
            'type'   => $type, // Truyền type xuống view
        ]);
    }


    /**
     * Cập nhật điểm.
     */
    public function update(Request $request, $type, $id)
    {
        $model = $this->resolveModel($type);
        $point = $model->newQuery()->findOrFail($id);

        $data = $request->validate([
            'city_id' => 'nullable|exists:cities,id',
            'route_id' => 'required|exists:routes,id',
            'ten_diem_tra' => 'required|string|max:255', 
            'dia_chi' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'active' => 'boolean',
        ], [
            'ten_diem_tra.required' => 'Tên điểm không được để trống.',
        ]);

        $point->update($data);

        return redirect()->route('admin.pickup-dropoff-points.index', ['type' => $type])
            ->with('success', 'Đã cập nhật điểm ' . ($type === 'pickup' ? 'đón' : 'trả') . '!');
    }

    /**
     * Xóa điểm.
     */
    public function destroy($type, $id)
    {
        $model = $this->resolveModel($type);
        $point = $model->newQuery()->findOrFail($id);
        
        // Dùng Soft Deletes (nếu bạn dùng SoftDeletes trong model)
        $point->delete();

        return redirect()->route('admin.pickup-dropoff-points.index', ['type' => $type])
            ->with('success', 'Đã xóa điểm ' . ($type === 'pickup' ? 'đón' : 'trả') . '!');
    }
}