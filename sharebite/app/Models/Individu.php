<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Individu extends Model
{
    protected $table = 'individus';
    protected $primaryKey = 'IndividuID';

    protected $fillable = [
        'Nama',
        'Nomor_hp',
        'Email',
        'Password',
    ];

    protected $hidden = ['Password'];

    // Relasi ke Transaksi (melakukan)
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'IndividuID', 'IndividuID');
    }

    // Relasi ke Pengambilan (mengambil)
    public function pengambilans()
    {
        return $this->hasMany(Pengambilan::class, 'IndividuID', 'IndividuID');
    }

    // Relasi ke Rating (memberi)
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'IndividuID', 'IndividuID');
    }
}
