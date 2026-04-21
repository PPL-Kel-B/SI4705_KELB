<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanans';
    protected $primaryKey = 'PesananID';

    protected $fillable = [
        'Jumlah_porsi',
        'Total_Harga',
        'Status',
        'Waktu_pesan',
        'Waktu_diambil',
        'MakananID',
        'UnitBisnisID',
    ];

    protected $casts = [
        'Waktu_pesan' => 'datetime',
        'Waktu_diambil' => 'date',
        'Total_Harga' => 'double',
    ];

    // Relasi ke Makanan
    public function makanan()
    {
        return $this->belongsTo(Makanan::class, 'MakananID', 'MakananID');
    }

    // Relasi ke UnitBisnis
    public function unitBisnis()
    {
        return $this->belongsTo(UnitBisnis::class, 'UnitBisnisID', 'UnitBisnisID');
    }

    // Relasi ke Bukti (mempunyai)
    public function buktis()
    {
        return $this->hasMany(Bukti::class, 'PesananID', 'PesananID');
    }

    // Relasi ke Transaksi (membayar)
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'PesananID', 'PesananID');
    }

    // Relasi ke Pengambilan (memiliki)
    public function pengambilans()
    {
        return $this->hasMany(Pengambilan::class, 'PesananID', 'PesananID');
    }
}
