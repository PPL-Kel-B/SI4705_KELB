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
        return view('auth.login_custom');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;

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

        // Cek verifikasi untuk Unit Bisnis
        if ($user->role === 'unit_bisnis') {
            $profile = $user->unitBisnisProfile;
            if ($profile) {
                if ($profile->status_verifikasi === 'pending') {
                    return back()->withErrors([
                        'email' => 'Akun mu belum terverifikasi, harap cek secara berkala.',
                    ])->withInput();
                } elseif ($profile->status_verifikasi === 'ditolak') {
                    return back()->with([
                        'rejection_message' => 'Mohon maaf, pengajuan verifikasi NIB Anda ditolak.',
                        'rejection_notes' => $profile->reviewer_notes ?? 'Tidak ada catatan tambahan.'
                    ])->withInput();
                }
            }
        }

        // Login Berhasil
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Redirect sesuai role
        if ($user->role == 'unit_bisnis') {
            return redirect('/unit/dashboard');
        } elseif ($user->role == 'komunitas') {
            return redirect('/user/dashboard');
        } elseif ($user->role == 'individu') {
            return redirect('/user/dashboard');
        } elseif ($user->role == 'admin') {
            return redirect('/admin/dashboard');
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