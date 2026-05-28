<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\MenuAktif;

class MakananDekatNotification extends Notification
{
    use Queueable;

    protected $menuAktif;

    public function __construct(MenuAktif $menuAktif)
    {
        $this->menuAktif = $menuAktif;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $namaMakanan = $this->menuAktif->masterMakanan->nama_makanan;
        $namaUsaha = $this->menuAktif->unitBisnis->nama_usaha;

        return [
            'title' => 'Makanan Tersedia Dekat Anda!',
            'message' => "{$namaUsaha} baru saja merilis menu surplus '{$namaMakanan}'. Segera klaim sebelum habis!",
            'action_url' => route('home'),
            'icon' => 'menu',
            'type' => 'menu',
            'menu_aktif_id' => $this->menuAktif->id,
        ];
    }
}
