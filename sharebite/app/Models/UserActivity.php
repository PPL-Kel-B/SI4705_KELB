<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipe',
        'judul',
        'deskripsi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to log a user activity.
     */
    public static function log($userId, $tipe, $judul, $deskripsi)
    {
        return self::create([
            'user_id' => $userId,
            'tipe' => $tipe,
            'judul' => $judul,
            'deskripsi' => $deskripsi,
        ]);
    }
}
