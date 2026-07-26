<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // ──────────────────────────────────────────────
    //  LOGIN
    // ──────────────────────────────────────────────

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required'],
        ]);

        $login = $request->input('login');

        // Tentukan apakah input berupa email atau phone_number
        $field = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone_number';

        $credentials = [
            $field     => $login,
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended('/');
        }

        return back()
            ->with('error', 'Email atau password salah.')
            ->onlyInput('login');
    }

    // ──────────────────────────────────────────────
    //  REGISTER
    // ──────────────────────────────────────────────

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => [
                'required',
                'string',
                'max:20',
                'regex:/^(\+62|62|0)8[0-9]{8,12}$/',   // format nomor HP Indonesia
                'unique:users,phone_number',
            ],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'phone_number.regex'  => 'Format nomor HP tidak valid (contoh: 081234567890).',
            'phone_number.unique' => 'Nomor HP sudah terdaftar.',
        ]);

        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'password'     => Hash::make($request->password),
            'role'         => 'pembeli',
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Registrasi berhasil!');
    }

    // ──────────────────────────────────────────────
    //  LOGOUT
    // ──────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}