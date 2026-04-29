<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividuProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_berat_diselamatkan',
        'total_makanan_dibeli',
    ];

    protected function casts(): array
    {
        return [
            'total_berat_diselamatkan' => 'decimal:2',
            'total_makanan_dibeli' => 'integer',
        ];
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}