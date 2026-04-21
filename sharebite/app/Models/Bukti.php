<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bukti extends Model
{
    protected $table = 'buktis';
    protected $primaryKey = 'BuktiID';

    protected $fillable = [
        'Foto_bukti',
        'Waktu_upload',
        'PesananID',
    ];

    protected $casts = [
        'Waktu_upload' => 'datetime',
    ];

    // Relasi ke Pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'PesananID', 'PesananID');
    }
}
