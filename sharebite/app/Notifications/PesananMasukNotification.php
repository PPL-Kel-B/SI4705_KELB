<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Pesanan;

class PesananMasukNotification extends Notification
{
    use Queueable;

    protected $pesanan;

    public function __construct(Pesanan $pesanan)
    {
        $this->pesanan = $pesanan;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $namaPemesan = $this->pesanan->user->name;
        $namaMakanan = $this->pesanan->menuAktif->masterMakanan->nama_makanan;
        $jumlahPorsi = $this->pesanan->jumlah_porsi;

        return [
            'title' => 'Pesanan Baru Masuk!',
            'message' => "{$namaPemesan} memesan {$jumlahPorsi} porsi '{$namaMakanan}'. Silakan periksa detail pesanan.",
            'action_url' => route('unit.pesanan'),
            'icon' => 'order',
            'type' => 'order',
            'pesanan_id' => $this->pesanan->id,
        ];
    }
}
