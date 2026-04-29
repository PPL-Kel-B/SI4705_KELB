<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiDonasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id',
        'foto',
        'tanggal',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'datetime',
        ];
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}