<?php

namespace App\Http\Controllers;

use App\Models\UnitBisnisProfile;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitBisnisController extends Controller
{
    private function getUnitBisnisData()
    {
        $user = Auth::user();

        $unitBisnis = UnitBisnisProfile::where('user_id', $user->id)->first();

        if (!$unitBisnis) {
            $unitBisnis = new UnitBisnisProfile([
                'user_id' => $user->id,
                'nama_usaha' => $user->name,
                'jenis_usaha' => null,
                'lokasi_lat' => '-6.9271',
                'lokasi_lng' => '107.6411',
                'radius_penjemputan' => 15,
                'jam_buka' => '08:00',
                'jam_tutup' => '21:00',
                'foto_bisnis' => 'images/placeholder-bisnis.jpg',
                'verified' => true,
                'tahun_bergabung' => now()->year,
            ]);
        }

        $unitBisnis->alamat = $user->alamat ?? '';

        // Ambil semua pesanan selesai milik unit bisnis ini berdasarkan unit_bisnis_id
        $pesanans = $unitBisnis->id
            ? Pesanan::where('unit_bisnis_id', $unitBisnis->id)
                ->where('status', 'selesai')
                ->with('menuAktif.masterMakanan')
                ->get()
            : collect();

        $totalPorsi = $pesanans->sum('jumlah_porsi');

        $totalKg = $pesanans->sum(function ($pesanan) {
            $berat = $pesanan->menuAktif->masterMakanan->berat ?? 0;
            return $berat * $pesanan->jumlah_porsi;
        });

        return [
            'unitBisnis' => $unitBisnis,
            'user' => $user,
            'stats' => [
                'total_porsi' => $totalPorsi,
                'total_kg' => $totalKg,
                'jumlah_pesanan' => $pesanans->count(),
                'total_kontribusi' => $pesanans->count(),
            ],
            'isNew' => !UnitBisnisProfile::where('user_id', $user->id)->exists(),
        ];
    }

    public function dashboard()
    {
        $data = $this->getUnitBisnisData();
        return view('unit_bisnis.dashboard', $data);
    }

    public function showProfile()
    {
        $data = $this->getUnitBisnisData();
        return view('unit_bisnis.profil', $data);
    }

    public function editProfile()
    {
        $data = $this->getUnitBisnisData();
        $data['edit_mode'] = true;
        return view('unit_bisnis.profil', $data);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama_bisnis' => 'nullable|string|max:255',
            'email_bisnis' => 'nullable|email|max:255',
            'no_telepon' => ['nullable', 'string', 'regex:/^[0-9+\-() ]+$/'],
            'jenis_usaha' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string|max:1000',
            'alamat' => 'nullable|string|max:500',
            'lokasi_lat' => 'nullable|numeric',
            'lokasi_lng' => 'nullable|numeric',
            'foto_bisnis' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'header_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'delete_photo' => 'nullable|in:0,1',
            'delete_header' => 'nullable|in:0,1',
        ], [
            'email_bisnis.email' => 'Email tidak valid',
            'no_telepon.regex' => 'Nomor telepon harus berupa angka (format: 08xx atau +62xxx)',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter',
            'foto_bisnis.image' => 'File foto profil harus berupa gambar.',
            'foto_bisnis.mimes' => 'Format foto profil tidak didukung. Gunakan format: JPG atau PNG.',
            'foto_bisnis.max' => 'Ukuran foto profil maksimal 10MB.',
            'header_image.image' => 'File header harus berupa gambar.',
            'header_image.mimes' => 'Format gambar header tidak didukung. Gunakan format: JPG atau PNG.',
            'header_image.max' => 'Ukuran gambar header maksimal 10MB.',
        ]);

        $unitBisnis = UnitBisnisProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama_usaha' => $user->name,
                'jenis_usaha' => null,
                'foto_bisnis' => 'images/placeholder-bisnis.jpg',
                'verified' => true,
                'tahun_bergabung' => now()->year,
            ]
        );

        if (!empty($validated['nama_bisnis'])) {
            $user->name = $validated['nama_bisnis'];
        }
        if (!empty($validated['email_bisnis'])) {
            $user->email = $validated['email_bisnis'];
        }
        if (!empty($validated['alamat'])) {
            $user->alamat = $validated['alamat'];
        }
        if (!empty($validated['no_telepon'])) {
            $user->no_hp = $validated['no_telepon'];
        }
        if (!empty($validated['lokasi_lat'])) {
            $user->latitude = $validated['lokasi_lat'];
        }
        if (!empty($validated['lokasi_lng'])) {
            $user->longitude = $validated['lokasi_lng'];
        }

        $user->save();

        $uploadPath = public_path('images/bisnis');

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        if ($request->input('delete_photo') == '1') {
            $this->deleteOldPhoto($unitBisnis);
            $validated['foto_bisnis'] = 'images/placeholder-bisnis.jpg';
        }

        if ($request->hasFile('foto_bisnis')) {
            $this->deleteOldPhoto($unitBisnis);

            $file = $request->file('foto_bisnis');
            $filename = 'bisnis_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            $file->move($uploadPath, $filename);

            $validated['foto_bisnis'] = 'images/bisnis/' . $filename;
        }

        if ($request->input('delete_header') == '1') {
            $this->deleteOldHeaderImage($unitBisnis);
            $validated['header_image'] = null;
        }

        if ($request->hasFile('header_image')) {
            $this->deleteOldHeaderImage($unitBisnis);

            $file = $request->file('header_image');
            $filename = 'header_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            $file->move($uploadPath, $filename);

            $validated['header_image'] = 'images/bisnis/' . $filename;
        }

        $profileData = array_filter($validated, fn($v) => $v !== null);

        if (!empty($validated['nama_bisnis'])) {
            $profileData['nama_usaha'] = $validated['nama_bisnis'];
        }

        // header_image bisa null saat dihapus — tetap masukkan
        if (array_key_exists('header_image', $validated)) {
            $profileData['header_image'] = $validated['header_image'];
        }

        unset($profileData['nama_bisnis']);
        unset($profileData['email_bisnis']);
        unset($profileData['no_telepon']);
        unset($profileData['alamat']);
        unset($profileData['delete_photo']);
        unset($profileData['delete_header']);

        $unitBisnis->update($profileData);

        return redirect()->route('unit.profil')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    public function uploadFotoProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'foto_bisnis' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $unitBisnis = UnitBisnisProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama_usaha' => $user->name,
                'jenis_usaha' => null,
                'foto_bisnis' => 'images/placeholder-bisnis.jpg',
                'verified' => true,
                'tahun_bergabung' => now()->year,
            ]
        );

        $uploadPath = public_path('images/bisnis');

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $this->deleteOldPhoto($unitBisnis);

        $file = $request->file('foto_bisnis');
        $filename = 'bisnis_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

        $file->move($uploadPath, $filename);

        $unitBisnis->update([
            'foto_bisnis' => 'images/bisnis/' . $filename,
        ]);

        return redirect()->route('unit.profil')
            ->with('success', 'Foto profil berhasil diperbarui!');
    }

    public function hapusFotoProfile()
    {
        $user = Auth::user();

        $unitBisnis = UnitBisnisProfile::where('user_id', $user->id)->first();

        if (!$unitBisnis) {
            return redirect()->route('unit.profil')
                ->with('error', 'Profil unit bisnis tidak ditemukan.');
        }

        $this->deleteOldPhoto($unitBisnis);

        $unitBisnis->update([
            'foto_bisnis' => 'images/placeholder-bisnis.jpg',
        ]);

        return redirect()->route('unit.profil')
            ->with('success', 'Foto profil berhasil dihapus!');
    }

    private function deleteOldPhoto($unitBisnis)
    {
        if (
            $unitBisnis->foto_bisnis &&
            $unitBisnis->foto_bisnis !== 'images/placeholder-bisnis.jpg' &&
            str_contains($unitBisnis->foto_bisnis, 'images/bisnis/')
        ) {
            $filePath = public_path($unitBisnis->foto_bisnis);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    private function deleteOldHeaderImage($unitBisnis)
    {
        if (
            $unitBisnis->header_image &&
            str_contains($unitBisnis->header_image, 'images/bisnis/')
        ) {
            $filePath = public_path($unitBisnis->header_image);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    public function showSettings()
    {
        $data = $this->getUnitBisnisData();
        return view('unit_bisnis.pengaturan', $data);
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
            'radius_penjemputan' => 'required|integer|min:1|max:50',
            'notifikasi_aktif' => 'nullable',
            'notifikasi_pesanan' => 'nullable',
            'notifikasi_penjemputan' => 'nullable',
        ], [
            'jam_buka.required' => 'Jam buka harus diisi',
            'jam_buka.date_format' => 'Format jam buka tidak valid',
            'jam_tutup.required' => 'Jam tutup harus diisi',
            'jam_tutup.date_format' => 'Format jam tutup tidak valid',
            'jam_tutup.after' => 'Jam tutup harus setelah jam buka',
            'radius_penjemputan.required' => 'Radius penjemputan harus diisi',
            'radius_penjemputan.min' => 'Radius minimum 1 km',
            'radius_penjemputan.max' => 'Radius maksimal 50 km',
        ]);

        $unitBisnis = UnitBisnisProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama_usaha' => $user->name,
                'jenis_usaha' => null,
                'foto_bisnis' => 'images/placeholder-bisnis.jpg',
                'verified' => true,
                'tahun_bergabung' => now()->year,
            ]
        );

        $unitBisnis->update([
            'jam_buka' => $validated['jam_buka'],
            'jam_tutup' => $validated['jam_tutup'],
            'radius_penjemputan' => $validated['radius_penjemputan'],
            'notifikasi_aktif' => (bool) ($validated['notifikasi_aktif'] ?? false),
            'notifikasi_pesanan' => (bool) ($validated['notifikasi_pesanan'] ?? false),
            'notifikasi_penjemputan' => (bool) ($validated['notifikasi_penjemputan'] ?? false),
        ]);

        return redirect()->route('unit.pengaturan')
            ->with('success', 'Pengaturan berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Kata sandi saat ini harus diisi',
            'password.required' => 'Kata sandi baru harus diisi',
            'password.min' => 'Kata sandi minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok',
        ]);

        $user = Auth::user();

        if (!Auth::attempt(['email' => $user->email, 'password' => $request->current_password])) {
            return back()->with('error', 'Kata sandi saat ini tidak sesuai');
        }

        $user->password = bcrypt($validated['password']);
        $user->password_updated_at = now()->toDateTimeString();
        $user->save();

        return redirect()->route('unit.profil')->with('success', 'Kata sandi berhasil diubah!');
    }
}