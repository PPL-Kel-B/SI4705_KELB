<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'deskripsi',
        'status',
        'periode',
    ];

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function admin()
    {
        return $this->belongsTo(AdminProfile::class, 'admin_id');
    }
}