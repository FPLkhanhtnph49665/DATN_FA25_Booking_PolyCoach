<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Auth\LoginRequest;

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
        // return redirect()->intended(route('dashboard', absolute: false));
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
            'phone'     => 'nullable|string|max:20',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg|max:1024', // 1MB
        ], [
            'full_name.required' => 'Vui lòng nhập họ và tên.',
            'image.image'        => 'File tải lên phải là hình ảnh.',
            'image.mimes'        => 'Chỉ chấp nhận JPEG, JPG, PNG.',
            'image.max'          => 'Dung lượng ảnh tối đa 1MB.',
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

        $code   = trim($request->input('code'));      // mã vé
        $date   = $request->input('date');            // ngày đi
        $routeQ = trim($request->input('route'));     // tuyến đường
        $status = $request->input('status');          // trạng thái

        $query = $user->tickets()
            ->with(['trip.route'])
            ->orderByDesc('created_at');

        if ($code !== '') {
            // tạm thời lọc theo id vé
            $query->where('id', $code);
        }

        if (!empty($date)) {
            $query->whereHas('trip', function ($q) use ($date) {
                $q->whereDate('ngay_khoi_hanh', $date);
            });
        }

        if ($routeQ !== '') {
            $query->whereHas('trip.route', function ($q) use ($routeQ) {
                $q->where('diem_di', 'like', "%{$routeQ}%")
                    ->orWhere('diem_den', 'like', "%{$routeQ}%");
            });
        }

        if ($status !== '') {
            $query->where('trang_thai', $status);
        }

        $tickets = $query->paginate(10)->withQueryString();

        return view('client.account.tickets', compact(
            'user',
            'tickets',
            'code',
            'date',
            'routeQ',
            'status'
        ));
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
