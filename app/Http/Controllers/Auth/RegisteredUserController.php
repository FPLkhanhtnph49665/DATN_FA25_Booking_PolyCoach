<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ],
        [
            'first_name.required' => 'Vui lòng nhập tên.',
            'last_name.required' => 'Vui lòng nhập họ.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Định dạng email không hợp lệ.',
            'email.unique' => 'Email đã được sử dụng.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        // ----- Generate user_code
        $prefix = 'DATN_FA25_PoLyCoach_';

        $lastUser = User::where('user_code', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        $number = 1;

        if ($lastUser && $lastUser->user_code) {
            $lastNumber = (int) substr($lastUser->user_code, strlen($prefix));
            if ($lastNumber > 0) {
                $number = $lastNumber + 1;
            }
        }

        $userCode = $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
        // ----- End generate user_code

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'full_name' => trim($request->first_name . ' ' . $request->last_name),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_code' => $userCode,
        ]);

        event(new Registered($user));
        // Auth::login($user);

        // ✅ Điều hướng sang login + thông báo
        return redirect()
            ->route('login')
            ->with('success', 'Đăng ký thành công! Vui lòng đăng nhập để tiếp tục.');
    }

}
