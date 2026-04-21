<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'ratings';
    protected $primaryKey = 'RatingID';

    protected $fillable = [
        'Nilai',
        'Komentar',
        'Bukti',
        'IndividuID',
        'KomunitasID',
        'UnitBisnisID',
    ];

    // Relasi ke Individu (memberi)
    public function individu()
    {
        return $this->belongsTo(Individu::class, 'IndividuID', 'IndividuID');
    }

    // Relasi ke Komunitas (diberi)
    public function komunitas()
    {
        return $this->belongsTo(Komunitas::class, 'KomunitasID', 'KomunitasID');
    }

    // Relasi ke UnitBisnis (diberi)
    public function unitBisnis()
    {
        return $this->belongsTo(UnitBisnis::class, 'UnitBisnisID', 'UnitBisnisID');
    }
}
