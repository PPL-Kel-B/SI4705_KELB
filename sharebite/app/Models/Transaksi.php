<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksis';
    protected $primaryKey = 'TransaksiID';

    protected $fillable = [
        'QRcode',
        'Status',
        'IndividuID',
        'KomunitasID',
        'PesananID',
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
