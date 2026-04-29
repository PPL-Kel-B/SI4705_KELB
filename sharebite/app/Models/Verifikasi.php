<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_bisnis_id',
        'admin_id',
        'dokumen',
        'status',
        'catatan',
    ];

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function unitBisnis()
    {
        return $this->belongsTo(UnitBisnisProfile::class, 'unit_bisnis_id');
    }

    public function admin()
    {
        return $this->belongsTo(AdminProfile::class, 'admin_id');
    }
}