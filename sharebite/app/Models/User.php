<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'no_hp',
        'role',
        'alamat',
        'foto_profil',
        'latitude',
        'longitude',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'password_updated_at' => 'datetime',
        ];
    }

    // -------------------------------------------------------
    // Role helpers
    // -------------------------------------------------------

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isIndividu(): bool
    {
        return $this->role === 'individu';
    }

    public function isKomunitas(): bool
    {
        return $this->role === 'komunitas';
    }

    public function isUnitBisnis(): bool
    {
        return $this->role === 'unit_bisnis';
    }

    // -------------------------------------------------------
    // Profile relations (one-to-one, sesuai role)
    // -------------------------------------------------------

    public function individuProfile()
    {
        return $this->hasOne(IndividuProfile::class);
    }

    public function komunitasProfile()
    {
        return $this->hasOne(KomunitasProfile::class);
    }

    public function unitBisnisProfile()
    {
        return $this->hasOne(UnitBisnisProfile::class);
    }

    public function adminProfile()
    {
        return $this->hasOne(AdminProfile::class);
    }

    // -------------------------------------------------------
    // Shared relations
    // -------------------------------------------------------

    public function otps()
    {
        return $this->hasMany(Otp::class);
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }

    public function pengambilans()
    {
        return $this->hasMany(Pengambilan::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function pesanSent()
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    public function pesanReceived()
    {
        return $this->hasMany(Chat::class, 'receiver_id');
    }

    // -------------------------------------------------------
    // Helper untuk Geolocation / Radius
    // -------------------------------------------------------

    public static function getUsersWithinRadius($latitude, $longitude, $radiusKm = 5, $roles = ['individu', 'komunitas'])
    {
        if (is_null($latitude) || is_null($longitude)) {
            return collect();
        }

        $users = self::whereIn('role', $roles)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return $users->filter(function ($user) use ($latitude, $longitude, $radiusKm) {
            $distance = self::calculateDistance($latitude, $longitude, $user->latitude, $user->longitude);
            return $distance <= $radiusKm;
        });
    }

    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad((float) $lat2 - (float) $lat1);
        $dLon = deg2rad((float) $lon2 - (float) $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad((float) $lat1)) * cos(deg2rad((float) $lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }
}