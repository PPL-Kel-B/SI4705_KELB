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
}