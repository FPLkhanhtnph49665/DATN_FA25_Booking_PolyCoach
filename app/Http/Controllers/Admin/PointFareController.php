<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PickupDropoffPoint;
use App\Models\Route;
use App\Models\PointFare;
use Illuminate\Http\Request;

class PointFareController extends Controller
{
    /**
     * Hiển thị danh sách giá vé (kèm bộ lọc)
     */
    public function index(Request $request)
    {
        // 1. Khởi tạo Query Builder
        $query = PointFare::with(['route', 'route.fromCity', 'route.toCity', 'pickupPoint', 'dropoffPoint']);

        // 2. Xử lý bộ lọc từ Request
        if ($request->filled('route_id')) {
            $query->where('route_id', $request->route_id);
        }

        if ($request->filled('pickup_point_id')) {
            $query->where('pickup_point_id', $request->pickup_point_id);
        }

        if ($request->filled('dropoff_point_id')) {
            $query->where('dropoff_point_id', $request->dropoff_point_id);
        }

        // 3. Lấy dữ liệu và phân trang (giữ lại tham số lọc trên URL)
        $pointFares = $query->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        // 4. Lấy dữ liệu cho các thẻ Select trong bộ lọc
        $routes = Route::with(['fromCity', 'toCity'])->get();

        // Giả sử bảng pickup_dropoff_points có cột 'type' ('pickup' hoặc 'dropoff')
        // Nếu không có cột type, bạn dùng ::all()
        $pickupPoints = PickupDropoffPoint::where('active', 1)->where('type', 'pickup')->orderBy('name')->get();
        $dropoffPoints = PickupDropoffPoint::where('active', 1)->where('type', 'dropoff')->orderBy('name')->get();

        // Nếu bảng không chia type, dùng chung biến này cho cả 2 dropdown:
        if ($pickupPoints->isEmpty() && $dropoffPoints->isEmpty()) {
            $pickupPoints = $dropoffPoints = PickupDropoffPoint::where('active', 1)->orderBy('name')->get();
        }

        return view('admin.point_fares.index', compact('pointFares', 'routes', 'pickupPoints', 'dropoffPoints'));
    }

    /**
     * Hiển thị form tạo giá vé mới
     */
    public function create()
    {
        // Sửa cột orderBy
        $routes = Route::where('status', 1)->orderBy('id')->get();
        $points = PickupDropoffPoint::where('active', 1)
            ->orderBy('name')
            ->get();

        return view('admin.point_fares.create', compact('routes', 'points'));
    }

    /**
     * Lưu giá vé mới
     */
    public function store(Request $request)
    {
        // Giả định tên bảng chung trong database là 'pickup_dropoff_points'
        $data = $request->validate([
            'route_id' => 'required|exists:routes,id',

            // Sửa: Trỏ cả hai trường về bảng chung 'pickup_dropoff_points'
            'pickup_point_id' => 'required|exists:pickup_dropoff_points,id',
            'dropoff_point_id' => 'required|exists:pickup_dropoff_points,id',

            'price' => 'required|numeric|min:0',
            'status' => 'required|boolean',
        ], [
            'route_id.required' => 'Vui lòng chọn tuyến xe.',
            'route_id.exists' => 'Tuyến xe không hợp lệ.', // Nên thêm

            'pickup_point_id.required' => 'Vui lòng chọn điểm đón.',
            'pickup_point_id.exists' => 'Điểm đón không hợp lệ.', // Nên thêm

            'dropoff_point_id.required' => 'Vui lòng chọn điểm trả.',
            'dropoff_point_id.exists' => 'Điểm trả không hợp lệ.', // Nên thêm

            'price.required' => 'Vui lòng nhập giá vé.',
            'price.numeric' => 'Giá vé phải là số.', // Nên thêm
            'price.min' => 'Giá vé phải lớn hơn hoặc bằng 0.', // Nên thêm
            'status.required' => 'Vui lòng chọn trạng thái.', // Nên thêm
            'status.boolean' => 'Trạng thái không hợp lệ.', // Nên thêm
        ]);

        // Thêm kiểm tra trùng lặp (Tùy chọn nhưng RẤT NÊN làm)
        // Ví dụ: Kiểm tra xem cặp Tuyến - Đón - Trả này đã tồn tại chưa
        $existingFare = PointFare::where('route_id', $data['route_id'])
            ->where('pickup_point_id', $data['pickup_point_id'])
            ->where('dropoff_point_id', $data['dropoff_point_id'])
            ->first();

        if ($existingFare) {
            return redirect()->back()->withInput()
                ->withErrors(['combination' => 'Giá vé cho cặp tuyến, điểm đón và điểm trả này đã tồn tại.']);
        }


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
        $points = PickupDropoffPoint::where('active', 1)
            ->orderBy('name')
            ->get();

        return view('admin.point_fares.edit', compact('pointFare', 'routes', 'points'));
    }

    /**
     * Cập nhật giá vé
     */
    public function update(Request $request, PointFare $pointFare)
    {
        // Giả định tên bảng chung trong database là 'pickup_dropoff_points'
        $data = $request->validate([
            'route_id' => 'required|exists:routes,id',

            // Sửa: Trỏ cả hai trường về bảng chung 'pickup_dropoff_points'
            'pickup_point_id' => 'required|exists:pickup_dropoff_points,id',
            'dropoff_point_id' => 'required|exists:pickup_dropoff_points,id',

            'price' => 'required|numeric|min:0',
            'status' => 'required|boolean',
        ], [
            // Thông báo lỗi chi tiết
            'route_id.required' => 'Vui lòng chọn tuyến xe.',
            'route_id.exists' => 'Tuyến xe không hợp lệ.',
            'pickup_point_id.required' => 'Vui lòng chọn điểm đón.',
            'pickup_point_id.exists' => 'Điểm đón không hợp lệ.',
            'dropoff_point_id.required' => 'Vui lòng chọn điểm trả.',
            'dropoff_point_id.exists' => 'Điểm trả không hợp lệ.',
            'price.required' => 'Vui lòng nhập giá vé.',
            'price.numeric' => 'Giá vé phải là số.',
            'price.min' => 'Giá vé phải lớn hơn hoặc bằng 0.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.boolean' => 'Trạng thái không hợp lệ.',
        ]);

        // Kiểm tra trùng lặp: Ngăn việc cập nhật thành một cặp (Tuyến - Đón - Trả) đã tồn tại.
        $existingFare = PointFare::where('route_id', $data['route_id'])
            ->where('pickup_point_id', $data['pickup_point_id'])
            ->where('dropoff_point_id', $data['dropoff_point_id'])
            // Quan trọng: Loại trừ bản ghi hiện tại đang được cập nhật
            ->where('id', '!=', $pointFare->id)
            ->first();

        if ($existingFare) {
            return redirect()->back()->withInput()
                ->withErrors(['combination' => 'Cặp tuyến, điểm đón và điểm trả này đã có giá vé khác tồn tại. Vui lòng kiểm tra lại.']);
        }

        // Cập nhật dữ liệu
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
