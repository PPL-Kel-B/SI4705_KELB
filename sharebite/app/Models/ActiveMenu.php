<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'unit_bisnis_id',
        'is_free',
        'stock',
        'limit_per_user',
        'status',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'stock' => 'integer',
        'limit_per_user' => 'integer',
    ];

    /**
     * Get the menu associated with this active menu
     */
    public function menu()
    {
        return $this->belongsTo(Makanan::class, 'menu_id');
    }

    /**
     * Get the unit bisnis associated with this active menu
     */
    public function unitBisnis()
    {
        return $this->belongsTo(Unitbisnis::class, 'unit_bisnis_id');
    }

    /**
     * Get sold portions today
     */
    public function getSoldPortionsToday()
    {
        return Pesanan::whereDate('created_at', today())
            ->where('makanan_id', $this->menu_id)
            ->sum('jumlah_porsi');
    }

    /**
     * Update status based on stock
     */
    public function updateStatus()
    {
        if ($this->stock <= 0) {
            $this->status = 'habis';
        } elseif ($this->stock <= 3) {
            $this->status = 'segera_habis';
        } else {
            $this->status = 'tersedia';
        }
        $this->save();
    }
}
