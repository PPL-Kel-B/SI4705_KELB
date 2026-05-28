<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengambilan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id',
        'user_id',
        'tanggal',
        'jumlah_porsi',
        'kode_unik',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    // User yang mengambil (individu atau komunitas)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}