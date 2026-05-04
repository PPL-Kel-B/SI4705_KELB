<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitBisnisProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_usaha',
        'jenis_usaha',
        'nib_file',
        'status_verifikasi',
        'reviewer_notes',
        'total_makanan_terjual',
        'total_berat_terjual',
    ];

    protected function casts(): array
    {
        return [
            'total_makanan_terjual' => 'integer',
            'total_berat_terjual' => 'decimal:2',
        ];
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifikasis()
    {
        return $this->hasMany(Verifikasi::class, 'unit_bisnis_id');
    }

    // Verifikasi terbaru (yang aktif/sedang diproses)
    public function verifikasiTerbaru()
    {
        return $this->hasOne(Verifikasi::class, 'unit_bisnis_id')->latestOfMany();
    }

    public function masterMakanans()
    {
        return $this->hasMany(MasterMakanan::class, 'unit_bisnis_id');
    }

    public function menuAktifs()
    {
        return $this->hasMany(MenuAktif::class, 'unit_bisnis_id');
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'unit_bisnis_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'unit_bisnis_id');
    }
}