<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    // Tampilkan halaman login
    public function index()
    {
        return view('login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $email    = $request->email;
        $password = $request->password;

        // Login Admin
        if ($email === 'admin@sharebite.com') {
            if ($password !== 'Admin@2024!') {
                return back()->withErrors([
                    'password' => 'Password salah.',
                ])->withInput();
            }

            $request->session()->regenerate();
            session([
                'user_role'  => 'admin',
                'user_email' => $email,
            ]);
            return redirect('/dashboard');
        }

        // Cek User di Database
        $user = User::where('email', $email)->first();

        // Email tidak ditemukan
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar.',
            ])->withInput();
        }

        // Password salah
        if (!Hash::check($password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password salah.',
            ])->withInput();
        }

        // Login Berhasil
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Redirect sesuai role
        if ($user->role == 'unit_bisnis') {
            return redirect('/unit/dashboard');
        } elseif ($user->role == 'komunitas') {
            return redirect('/komunitas/dashboard');
        } elseif ($user->role == 'individu') {
            return redirect('/individu/dashboard');
        }

        return redirect('/');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}