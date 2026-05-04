<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistIndividuRequest;
use App\Models\User;
use App\Models\IndividuProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class RegistIndividuController extends Controller
{
    public function create()
    {
        return view('auth.register_individu');
    }

    public function store(RegistIndividuRequest $request)
    {
        // Data sudah otomatis tervalidasi oleh Form Request
        $validated = $request->validated();

        $user = User::create([
            'name'     => $validated['nama_lengkap'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'no_hp'    => $validated['no_hp'],
            'role'     => 'individu',
        ]);

        IndividuProfile::create([
            'user_id'                  => $user->id,
            'total_berat_diselamatkan' => 0,
            'total_makanan_dibeli'     => 0,
        ]);

        return redirect()->route('individu.create')
                         ->with('success', 'Pendaftaran sebagai Individu/Relawan berhasil!');
    }
}