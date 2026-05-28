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
        
        $pesanans = \App\Models\Pesanan::where('user_id', $user->id)
            ->where('status', '!=', 'dibatalkan')
            ->with('menuAktif.masterMakanan')
            ->get();

        $contribution_items = $pesanans->sum('jumlah_porsi');
        
        $eco_impact_kg = $pesanans->sum(function ($pesanan) {
            $beratPerItem = $pesanan->menuAktif->masterMakanan->berat ?? 0;
            return $beratPerItem * $pesanan->jumlah_porsi;
        });

        $komunitasData = KomunitasProfile::firstOrNew(['user_id' => $user->id]);

        return [
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->no_hp ?? 'Belum diisi',
                'address' => $user->alamat ?? 'Belum diisi',
                'member_since' => optional($user->created_at)->format('M Y') ?? 'Jan 2023',
                'role' => $user->role,
                'foto_profil' => $user->foto_profil,

                // For Komunitas
                'nama_komunitas' => $komunitasData->nama_komunitas ?? $user->name,
                'jumlah_komunitas' => $komunitasData->jumlah_komunitas, // using the accessor
                'jumlah_anggota' => $komunitasData->jumlah_anggota ?? 'Belum diisi',
                'penanggung_jawab' => $komunitasData->penanggung_jawab ?? 'Belum diisi',
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
        if ($request->has('source')) {
            $source = $request->query('source');
            if ($source === 'pengaturan') {
                session(['profile_edit_back_url' => route('user.pengaturan')]);
            } else {
                session(['profile_edit_back_url' => route('user.profile')]);
            }
        } elseif (!session()->has('profile_edit_back_url')) {
            session(['profile_edit_back_url' => route('user.profile')]);
        }

        $user = $request->user();
        $komunitasData = null;
        if ($user->role === 'komunitas') {
            $komunitasData = \App\Models\KomunitasProfile::where('user_id', $user->id)->first();
        }

        return view('user.profile_edit', [
            'user' => $user,
            'komunitasData' => $komunitasData,
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        
        // Fill base user info
        $user->fill([
            'name' => $validated['name'] ?? $user->name,
            'email' => $validated['email'] ?? $user->email,
            'no_hp' => $validated['no_hp'] ?? $user->no_hp,
        ]);

        if (array_key_exists('alamat', $validated)) {
            $user->alamat = $validated['alamat'];
        }

        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('profile-photos', 'public');
            $user->foto_profil = $path;
        }

        if (isset($validated['new_password'])) {
            $user->password = \Illuminate\Support\Facades\Hash::make($validated['new_password']);
            $user->password_updated_at = now();
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $changed = false;

        if ($user->isDirty() || $request->hasFile('foto_profil') || isset($validated['new_password'])) {
            $changed = true;
            $user->save();
        }

        if ($user->role === 'komunitas') {
            $komunitasData = \App\Models\KomunitasProfile::firstOrNew(['user_id' => $user->id]);
            if ($request->has('penanggung_jawab')) {
                $komunitasData->penanggung_jawab = $request->input('penanggung_jawab');
            }
            if ($request->has('jumlah_anggota')) {
                $komunitasData->jumlah_anggota = $request->input('jumlah_anggota');
            }
            // Sync nama_komunitas with user's name
            $komunitasData->nama_komunitas = $user->name;
            
            if ($komunitasData->isDirty()) {
                $changed = true;
                $komunitasData->save();
            }
        }

        $backUrl = session('profile_edit_back_url', route('user.profile'));

        if ($changed) {
            return Redirect::to($backUrl)->with('success', 'Profil berhasil diperbarui!');
        }

        return Redirect::to($backUrl);
    }
}
