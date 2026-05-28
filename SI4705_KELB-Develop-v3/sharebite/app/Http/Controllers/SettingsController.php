<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $agent = new Agent();

        // Mengambil data sesi aktif dari database
        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) use ($agent) {
                $agent->setUserAgent($session->user_agent);
                
                $browser = $agent->browser();
                $platform = $agent->platform();
                
                return (object) [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'is_current_device' => $session->id === request()->session()->getId(),
                    'last_active' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    'device_name' => ($platform ?: 'Unknown OS') . ' • ' . ($browser ?: 'Unknown Browser'),
                    'location' => 'Bandung, Indonesia', // Hardcoded sesuai permintaan
                    'icon' => $agent->isMobile() ? 'mobile' : 'desktop'
                ];
            });

        return view('user.pengaturan', compact('user', 'sessions'));
    }

    public function policy($type)
    {
        $policies = [
            'keamanan-pangan' => ['title' => 'Standar Keamanan Pangan', 'icon' => '🛡️', 'description' => 'Panduan menjaga kualitas makanan'],
            'etika-berbagi' => ['title' => 'Etika Berbagi', 'icon' => '🤝', 'description' => 'Cara berinteraksi yang baik'],
            'ketentuan-layanan' => ['title' => 'Ketentuan Layanan', 'icon' => '⚖️', 'description' => 'Aspek hukum & tanggung jawab']
        ];

        if (!isset($policies[$type])) abort(404);
        $policy = (object) $policies[$type];
        return view('user.kebijakan_detail', compact('policy'));
    }

    public function logoutSession($id)
    {
        DB::table('sessions')->where('id', $id)->where('user_id', Auth::id())->delete();
        return redirect()->route('user.pengaturan')->with('success', 'Perangkat berhasil dikeluarkan.');
    }
}