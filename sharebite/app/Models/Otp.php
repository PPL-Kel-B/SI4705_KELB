<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'kode',
        'expired_at',
        'is_used',
    ];

    protected function casts(): array
    {
        return [
            'expired_at' => 'datetime',
            'is_used' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function isExpired(): bool
    {
        return $this->expired_at->isPast();
    }

    public function isValid(): bool
    {
        return !$this->is_used && !$this->isExpired();
    }
}