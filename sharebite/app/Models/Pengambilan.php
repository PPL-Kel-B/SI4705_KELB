<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengambilan extends Model
{
    protected $table = 'pengambilans';
    protected $primaryKey = 'PengambilanID';

    protected $fillable = [
        'Tanggal',
        'Jumlah_porsi',
        'Kode_Unik',
        'IndividuID',
        'KomunitasID',
        'PesananID',
    ];

    protected $casts = [
        'Tanggal' => 'date',
    ];

    // Relasi ke Individu
    public function individu()
    {
        return $this->belongsTo(Individu::class, 'IndividuID', 'IndividuID');
    }

    // Relasi ke Komunitas
    public function komunitas()
    {
        return $this->belongsTo(Komunitas::class, 'KomunitasID', 'KomunitasID');
    }

    // Relasi ke Pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'PesananID', 'PesananID');
    }
}
