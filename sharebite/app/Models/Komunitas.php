<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komunitas extends Model
{
    protected $table = 'komunitas';
    protected $primaryKey = 'KomunitasID';

    protected $fillable = [
        'Nama',
        'Nama_penanggung_jawab',
        'Jumlah_anggota',
        'Nomor_hp',
        'Email',
        'Password',
    ];

    protected $hidden = ['Password'];

    // Relasi ke Transaksi
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'KomunitasID', 'KomunitasID');
    }

    // Relasi ke Pengambilan
    public function pengambilans()
    {
        return $this->hasMany(Pengambilan::class, 'KomunitasID', 'KomunitasID');
    }

    // Relasi ke Rating
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'KomunitasID', 'KomunitasID');
    }
}
