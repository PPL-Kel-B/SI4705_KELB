<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuAktif extends Model
{
    use HasFactory;

    protected $fillable = [
        'master_makanan_id',
        'unit_bisnis_id',
        'is_gratis',
        'harga_jual',
        'stok_porsi',
        'batas_pengambilan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_gratis' => 'boolean',
            'harga_jual' => 'decimal:2',
            'stok_porsi' => 'integer',
            'batas_pengambilan' => 'datetime',
        ];
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function masterMakanan()
    {
        return $this->belongsTo(MasterMakanan::class, 'master_makanan_id');
    }

    public function unitBisnis()
    {
        return $this->belongsTo(UnitBisnisProfile::class, 'unit_bisnis_id');
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'menu_aktif_id');
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function isKadaluarsa(): bool
    {
        return $this->batas_pengambilan->isPast();
    }

    public function isStokHabis(): bool
    {
        return $this->stok_porsi <= 0;
    }

    public function isTersedia(): bool
    {
        return $this->status === 'aktif'
            && !$this->isStokHabis()
            && !$this->isKadaluarsa();
    }

    // Harga efektif yang dibayar user (0 jika gratis)
    public function hargaEfektif(): float
    {
        return $this->is_gratis ? 0 : (float) $this->harga_jual;
    }
}