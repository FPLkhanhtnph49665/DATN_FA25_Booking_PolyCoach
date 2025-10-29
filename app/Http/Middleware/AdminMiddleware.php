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

    if (!$user || $user->role !== 'admin' || $user->status === 0) {
        Auth::logout(); // nếu cần
        return redirect()->route('login')->withErrors([
            'email' => 'Bạn không có quyền truy cập hoặc tài khoản bị khóa.',
        ]);
    }

    return $next($request);
}

}
