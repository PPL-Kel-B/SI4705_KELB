<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifikasis()
    {
        return $this->hasMany(Verifikasi::class, 'admin_id');
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class, 'admin_id');
    }
}