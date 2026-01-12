<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\File;

class AuthenticatedSessionController extends Controller
{
    /**
     * Hiển thị trang login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Xác thực thông tin đăng nhập
        $request->authenticate();
        // Bảo vệ session
        $request->session()->regenerate();

        // Lấy thông tin user vừa đăng nhập
        $user = Auth::user();

        // Kiểm tra tài khoản bị khóa
        if ($user->status === 0) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Tài khoản của bạn đã bị khóa.',
            ]);
        }

        // Phân quyền admin
        if ($user->role === 'admin' || ($user->is_admin ?? false)) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'checker') {
            // Phân quyền nhân viên
            return redirect()->route('checker.dashboard');
        }

        // User bình thường → giữ URL trước khi login nếu có
        return redirect()->intended(route('client.home'));
    }

    public function show()
    {
        $user = Auth::user();

        return view('client.account.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            // Sửa lại: Thêm dấu phẩy trước $user->id và đưa regex vào đúng vị trí
            'phone' => 'nullable|string|size:10|regex:/^[0-9]+$/|unique:users,phone,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ], [
            'first_name.required' => 'Vui lòng nhập tên.',
            'last_name.required' => 'Vui lòng nhập họ.',
            'phone.size' => 'Số điện thoại phải đúng 10 ký tự.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'phone.regex' => 'Số điện thoại chỉ được chứa các chữ số.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email đã tồn tại.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Chỉ chấp nhận JPEG, JPG, PNG.',
            'image.max' => 'Dung lượng ảnh tối đa 1MB.',
        ]);

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ
            if ($user->image) {
                $oldImagePath = public_path($user->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            // Lưu ảnh mới
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/user_images'), $filename);

            $data['image'] = 'uploads/user_images/' . $filename;
        } else {
            // Nếu không upload ảnh mới, giữ nguyên ảnh cũ (tránh bị null khi update)
            unset($data['image']);
        }

        $user->update($data);

        return back()->with('success', 'Cập nhật thông tin tài khoản thành công.');
    }

    public function ticketHistory(Request $request)
    {
        $user = Auth::user();

        // Lấy các tham số filter
        $code = trim($request->input('code'));      // mã Booking (id)
        $date = $request->input('date');            // ngày đi (Y-m-d)
        $routeQ = trim($request->input('route'));   // tuyến đường (tên TP)
        $status = $request->input('status');        // trạng thái Booking

        // 1. CHUYỂN TRUY VẤN CHÍNH SANG BOOKING
        $query = $user->bookings()
            ->with([
                'trip.route.fromCity',
                'trip.route.toCity',
                'trip.bus',
                // Load lồng nhau: Vé -> Giá chặng -> Điểm đón/trả cụ thể
                'tickets.pointFare.pickupPoint',
                'tickets.pointFare.dropoffPoint'
            ])
            ->orderByDesc('created_at');

        // 2. ÁP DỤNG CÁC FILTER

        // Lọc theo Mã Booking (tương đương Mã vé trước đây, nhưng giờ là Booking ID)
        if ($request->filled('code')) {
            // Lọc trực tiếp theo ID của Booking
            $query->where('id', $code);
        }

        // Lọc theo Ngày đi (Tìm chuyến đi thuộc Booking)
        if ($request->filled('date')) {
            $query->whereHas('trip', function ($q) use ($date) {
                $q->whereDate('departure_date', $date);
            });
        }

        // Lọc theo Tuyến đường
        if ($request->filled('route')) {
            // Truy vấn qua mối quan hệ trip -> route -> city
            $query->whereHas('trip.route', function ($q) use ($routeQ) {
                // Lọc theo tên thành phố đi hoặc đến (giả định route có tên)
                $q->where(function ($subQ) use ($routeQ) {
                    $subQ->whereHas('fromCity', function ($c) use ($routeQ) {
                        $c->where('name', 'like', "%{$routeQ}%");
                    })
                        ->orWhereHas('toCity', function ($c) use ($routeQ) {
                            $c->where('name', 'like', "%{$routeQ}%");
                        });
                });
            });
        }

        // Lọc theo Trạng thái chuyến đi (Nằm ở bảng trips)
        if ($request->filled('status')) {
            $query->whereHas('trip', function ($q) use ($status) {
                $q->where('trip_status', $status);
            });
        }

        // 3. THỰC HIỆN PHÂN TRANG
        $bookings = $query->paginate(10)->withQueryString();

        // 4. TRẢ VỀ VIEW
        return view('client.account.tickets', [
            'user' => $user,
            'bookings' => $bookings,
            'code' => $code,
            'date' => $date,
            'routeQ' => $routeQ,
            'status' => $status
        ]);
    }

    /**
     * Đăng xuất user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        // Hủy session cũ
        $request->session()->invalidate();

        // Tạo token CSRF mới
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
