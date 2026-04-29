<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_aktif_id',
        'unit_bisnis_id',
        'user_id',
        'jumlah_porsi',
        'total_harga',
        'catatan',
        'status',
        'kode_unik',
        'waktu_pesan',
        'waktu_diambil',
    ];

    protected function casts(): array
    {
        return [
            'waktu_pesan' => 'datetime',
            'waktu_diambil' => 'date',
            'total_harga' => 'decimal:2',
            'jumlah_porsi' => 'integer',
        ];
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function menuAktif()
    {
        return $this->belongsTo(MenuAktif::class, 'menu_aktif_id');
    }

    public function unitBisnis()
    {
        return $this->belongsTo(UnitBisnisProfile::class, 'unit_bisnis_id');
    }

    // User yang memesan (individu atau komunitas)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function pengambilans()
    {
        return $this->hasMany(Pengambilan::class);
    }

    public function buktiDonasis()
    {
        return $this->hasMany(BuktiDonasi::class);
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function isSudahDibayar(): bool
    {
        return in_array($this->status, ['dibayar', 'siap_diambil', 'selesai']);
    }

    public function isSelesai(): bool
    {
        return $this->status === 'selesai';
    }
}