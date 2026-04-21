<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins';
    protected $primaryKey = 'AdminID';

    protected $fillable = [
        'Nama',
        'Nomor_hp',
        'Email',
        'Password',
    ];

    protected $hidden = ['Password'];

    // Relasi: Admin memverifikasi banyak UnitBisnis
    public function unitBisnis()
    {
        return $this->hasMany(UnitBisnis::class, 'AdminID', 'AdminID');
    }
}
