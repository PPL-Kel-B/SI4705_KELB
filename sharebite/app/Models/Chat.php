<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'pesan',
        'waktu',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'waktu' => 'datetime',
            'is_read' => 'boolean',
        ];
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // -------------------------------------------------------
    // Scope: ambil percakapan antara dua user
    // -------------------------------------------------------

    public function scopePercakapan($query, int $userA, int $userB)
    {
        return $query->where(function ($q) use ($userA, $userB) {
            $q->where('sender_id', $userA)->where('receiver_id', $userB);
        })->orWhere(function ($q) use ($userA, $userB) {
            $q->where('sender_id', $userB)->where('receiver_id', $userA);
        })->orderBy('waktu');
    }
}