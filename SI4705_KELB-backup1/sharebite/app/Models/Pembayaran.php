<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id',
        'status',
        'qrcode',
        'tanggal_bayar',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'datetime',
        ];
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function isBerhasil(): bool
    {
        return $this->status === 'berhasil';
    }
}