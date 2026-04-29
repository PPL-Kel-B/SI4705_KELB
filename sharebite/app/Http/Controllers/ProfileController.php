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

        return [
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? 'N/A',
                'address' => $user->address ?? 'Jl. Kebon Sirih No. 123, Menteng, Jakarta Pusat, DKI Jakarta 10340',
                'member_since' => optional($user->created_at)->format('M Y') ?? 'Jan 2023',
            ],
            'stats' => [
                'contribution_items' => 128,
                'eco_impact_kg' => 42.5,
            ],
            'security' => [
                'change_password' => 'Ubah Kata Sandi',
                'notifications' => 'Notifikasi',
            ],
        ];
    }

    public function index()
    {
        return view('profile.index', $this->profileData());
    }

    public function edit()
    {
        return view('profile.edit', $this->profileData());
    }

    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    // public function destroy(Request $request)
    // {
    //     $request->validateWithBag('userDeletion', [
    //         'password' => ['required', 'current_password'],
    //     ]);

    //     $user = $request->user();

    //     Auth::logout();

    //     $user->delete();

    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return Redirect::to('/');
    // }
}
