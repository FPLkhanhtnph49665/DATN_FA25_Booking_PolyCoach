<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Kiểm tra user có tồn tại không
        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Vui lòng đăng nhập để tiếp tục.',
            ]);
        }

        // Kiểm tra role có phải admin không
        if ($user->role !== 'admin') {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Bạn không có quyền truy cập vào trang quản trị.',
            ]);
        }

        // Kiểm tra tài khoản có bị khóa không
        if ($user->status == 0) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Tài khoản của bạn đã bị khóa.',
            ]);
        }

        return $next($request);
    }
}