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
        'foto_bisnis',
        'header_image',
        'deskripsi',
        'alamat',
        'lokasi_lat',
        'lokasi_lng',
        'radius_penjemputan',
        'jam_buka',
        'jam_tutup',
        'status_verifikasi',
        'verified',
        'tahun_bergabung',
        'reviewer_notes',
        'total_makanan_terjual',
        'total_berat_terjual',
        'notifikasi_aktif',
        'notifikasi_pesanan',
        'notifikasi_penjemputan',
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

    /**
     * Cek apakah toko sedang buka berdasarkan jam_buka dan jam_tutup.
     * Mendukung jam lintas tengah malam (misal: 22:00 - 03:00).
     */
    public function isOpen(): bool
    {
        if (!$this->jam_buka || !$this->jam_tutup) {
            return true; // Default buka jika belum diset
        }

        $now   = now()->setTimezone('Asia/Jakarta')->format('H:i');
        $buka  = $this->jam_buka;
        $tutup = $this->jam_tutup;

        if ($buka <= $tutup) {
            // Jam normal, misal: 08:00 – 21:00
            return $now >= $buka && $now <= $tutup;
        } else {
            // Lintas tengah malam, misal: 22:00 – 03:00
            return $now >= $buka || $now <= $tutup;
        }
    }

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