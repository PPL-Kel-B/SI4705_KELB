<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMakanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_bisnis_id',
        'nama_makanan',
        'kategori',
        'deskripsi',
        'harga',
        'berat',
        'foto',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'berat' => 'decimal:2',
        ];
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function unitBisnis()
    {
        return $this->belongsTo(UnitBisnisProfile::class, 'unit_bisnis_id');
    }

    // Semua menu aktif yang menggunakan master makanan ini
    public function menuAktifs()
    {
        return $this->hasMany(MenuAktif::class, 'master_makanan_id');
    }

    // Menu aktif yang sedang berjalan saat ini
    public function menuAktifSekarang()
    {
        return $this->hasOne(MenuAktif::class, 'master_makanan_id')
            ->where('status', 'aktif')
            ->latestOfMany();
    }
}