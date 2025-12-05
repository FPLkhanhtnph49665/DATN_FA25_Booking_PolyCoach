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
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:1024', // 1MB
        ], [
            'full_name.required' => 'Vui lòng nhập họ và tên.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Chỉ chấp nhận JPEG, JPG, PNG.',
            'image.max' => 'Dung lượng ảnh tối đa 1MB.',
        ]);

        // Xử lý upload avatar
        if ($request->hasFile('image')) {
            // Xoá ảnh cũ nếu có
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            $path = $request->file('image')->store('avatars', 'public');
            $data['image'] = $path;
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
                'tickets', // Eager load tất cả các vé con
                'trip.route.fromCity',
                'trip.route.toCity',
                'trip.bus'
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

        // Lọc theo Trạng thái (Trạng thái của Booking)
        if ($request->filled('status')) {
            $query->where('status', $status);
        }

        // 3. THỰC HIỆN PHÂN TRANG
        $bookings = $query->paginate(10)->withQueryString();

        // 4. TRẢ VỀ VIEW
        // Đổi biến $tickets thành $bookings để khớp với view đã sửa đổi
        return view('client.account.tickets', [
            'user' => $user,
            'bookings' => $bookings, // Đã đổi tên biến
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
