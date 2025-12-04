<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // 1. Chưa đăng nhập
        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Vui lòng đăng nhập để tiếp tục.',
            ]);
        }

        // 2. Không phải checker
        if ($user->role !== 'checker') {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Bạn không có quyền truy cập vào hệ thống kiểm soát vé.',
            ]);
        }

        // 3. Tài khoản bị khóa
        if ($user->status == 0) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Tài khoản của bạn đã bị khóa.',
            ]);
        }

        return $next($request);
    }
}
