<?php

namespace App\Http\Controllers\Checker;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class TripCheckController extends Controller
{
    /**
     * Danh sách chuyến
     */
    public function index(Request $request)
    {
        // 1. Khởi tạo query và Eager Loading
        // withCount('bookings') giúp lấy số lượng khách mà không cần load hết data vé
        $query = Trip::query()
            ->with(['route.fromCity', 'route.toCity', 'bus'])
            ->withCount('bookings');

        // 2. Lọc theo Mã chuyến (code) - Khớp với input name="code"
        if ($request->filled('code')) {
            $query->where('trip_code', 'like', '%' . $request->code . '%');
        }

        // 3. Lọc theo Trạng thái (trip_status) - Khớp với select name="trip_status"
        if ($request->filled('trip_status')) {
            $query->where('trip_status', $request->trip_status);
        }

        // 4. Lọc theo Tuyến (route_id)
        if ($request->filled('route_id')) {
            $query->where('route_id', $request->route_id);
        }

        // 5. (Tùy chọn) Lọc theo ngày - Nếu bạn muốn giữ lại
        // Lưu ý: Trong Model bạn khai báo departure_date riêng, nên whereDate vào cột date
        if ($request->filled('departure_date')) {
            $query->where('departure_date', $request->departure_date);
        }

        // 6. Sắp xếp: Ưu tiên chuyến đi gần nhất hoặc mới tạo
        // Sắp xếp theo ngày đi giảm dần (mới nhất lên đầu) rồi đến giờ đi
        $query->orderByDesc('departure_date')
            ->orderByDesc('departure_time');

        // 7. Phân trang & giữ lại query string (để khi bấm trang 2 không mất bộ lọc)
        $trips = $query->paginate(20)->withQueryString();

        // 8. Lấy danh sách tuyến cho Filter
        $routes = \App\Models\Route::with(['fromCity', 'toCity'])->get();

        // Trả về view (Lưu ý đường dẫn view phải đúng với cấu trúc thư mục của bạn)
        // Dựa theo file blade trước đó có thể là: 'checker.index' hoặc 'checker.trips.trips'
        return view('checker.trips.trips', compact('trips', 'routes'));
    }
    // cập nhật trạng thái chuyến
    public function updateStatus(Request $request, $id)
    {
        $trip = Trip::findOrFail($id);

        $request->validate([
            'trip_status' => 'required|in:1,2,3,4',
        ]);

        try {
            DB::transaction(function () use ($trip, $request) {

                // Cập nhật trạng thái và thông tin kiểm tra
                $trip->trip_status = $request->trip_status;
                $trip->checked_at = now(); // Lưu thời điểm hiện tại
                $trip->checked_by = auth()->id(); // Lưu ID của User đang đăng nhập (Checker)
                $trip->save();

                // Logic cập nhật Bookings và Tickets (giữ nguyên như cũ)
                $targetStatus = match ((int) $request->trip_status) {
                    2 => 'cancelled',
                    4 => 'paid',
                    default => null,
                };

                if ($targetStatus) {
                    $trip->bookings()->update(['status' => $targetStatus]);
                    $trip->tickets()->update(['status' => $targetStatus]);
                }
            });

            return redirect()->back()->with('success', 'Cập nhật trạng thái thành công.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}