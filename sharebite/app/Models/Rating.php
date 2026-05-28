<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'unit_bisnis_id',
        'pesanan_id',
        'skor_rating',
        'catatan_pengalaman',
        'foto_bukti_berbagi',
        'nilai',
        'komentar',
    ];

    protected function casts(): array
    {
        return [
            'nilai' => 'integer',
        ];
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    // User yang memberi rating (individu atau komunitas)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unitBisnis()
    {
        return $this->belongsTo(UnitBisnisProfile::class, 'unit_bisnis_id');
    }
}