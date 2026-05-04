<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    private function profileData(): array
    {
        $user = Auth::user();
        
        $contribution_items = \App\Models\Pesanan::where('user_id', $user->id)->sum('jumlah_porsi');
        $eco_impact_kg = $contribution_items * 2.5;

        return [
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->no_hp ?? '+62 812-3456-7890',
                'address' => $user->alamat ?? 'Jl. Kebon Sirih No. 123, Menteng, Jakarta Pusat, DKI Jakarta 10340',
                'member_since' => optional($user->created_at)->format('M Y') ?? 'Jan 2023',
            ],
            'stats' => [
                'contribution_items' => $contribution_items,
                'eco_impact_kg' => $eco_impact_kg,
            ],
            'security' => [
                'change_password' => 'Ubah Kata Sandi',
                'notifications' => 'Notifikasi',
            ],
        ];
    }

    public function index()
    {
        return view('user.profile', $this->profileData());
    }
}
