<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitBisnis extends Model
{
    protected $table = 'unit_bisnis';
    protected $primaryKey = 'UnitBisnisID';

    protected $fillable = [
        'Nama_Usaha',
        'Jenis_Usaha',
        'Alamat',
        'Nomor_hp',
        'Email',
        'Password',
        'AdminID',
        'NIB_File',
        'Latitude',
        'Longitude'
    ];

    protected $hidden = ['Password'];

    // Relasi ke Admin (diverifikasi oleh)
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'AdminID', 'AdminID');
    }

    // Relasi ke Makanan (menjual)
    public function makanans()
    {
        return $this->hasMany(Makanan::class, 'UnitBisnisID', 'UnitBisnisID');
    }

    // Relasi ke Pesanan (mendapatkan)
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'UnitBisnisID', 'UnitBisnisID');
    }

    // Relasi ke Rating (diberi)
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'UnitBisnisID', 'UnitBisnisID');
    }
}
