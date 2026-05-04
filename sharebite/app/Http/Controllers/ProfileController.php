<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Models\KomunitasProfile;

class ProfileController extends Controller
{
    private function profileData(): array
    {
        $user = Auth::user();
        
        $contribution_items = \App\Models\Pesanan::where('user_id', $user->id)->sum('jumlah_porsi');
        $eco_impact_kg = $contribution_items * 2.5;

        $komunitasData = KomunitasProfile::firstOrNew(['user_id' => $user->id]);

        return [
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->no_hp ?? '+62 812-3456-7890',
                'address' => $user->alamat ?? 'Jl. Kebon Sirih No. 123, Menteng, Jakarta Pusat, DKI Jakarta 10340',
                'member_since' => optional($user->created_at)->format('M Y') ?? 'Jan 2023',

                'jumlah_komunitas' => $komunitasData->jumlah_komunitas,
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

    public function edit(Request $request)
    {
        return view('user.profile_edit', [
            'user' => $request->user(),
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('user.profile.edit');
    }
}
