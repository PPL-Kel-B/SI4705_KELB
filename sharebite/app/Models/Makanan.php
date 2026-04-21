<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Makanan extends Model
{
    protected $table = 'makanans';
    protected $primaryKey = 'MakananID';

    protected $fillable = [
        'Nama_Makanan',
        'Jumlah_porsi',
        'Batas_waktu_pengambilan',
        'Harga',
        'Foto',
        'Status',
        'UnitBisnisID',
    ];

    protected $casts = [
        'Batas_waktu_pengambilan' => 'date',
        'Harga' => 'double',
    ];

    // Relasi ke UnitBisnis (dijual oleh)
    public function unitBisnis()
    {
        return $this->belongsTo(UnitBisnis::class, 'UnitBisnisID', 'UnitBisnisID');
    }

    // Relasi ke Pesanan (dipesan)
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'MakananID', 'MakananID');
    }
}
