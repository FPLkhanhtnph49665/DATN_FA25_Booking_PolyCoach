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
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // ----- Generate user_code: DATN_FA25_PoLyCoach_0001, 0002, ...
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
            'last_name'  => $request->last_name,
            'full_name'  => trim($request->first_name . ' ' . $request->last_name),
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'user_code'  => $userCode,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Nếu mày có route dashboard admin riêng thì giữ như này,
        // còn nếu client thì có thể đổi thành route('client.home')
        return redirect(route('dashboard', absolute: false));
    }
}
