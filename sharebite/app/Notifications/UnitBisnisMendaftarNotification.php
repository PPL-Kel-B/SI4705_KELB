<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;

class UnitBisnisMendaftarNotification extends Notification
{
    use Queueable;

    public $unitBisnisUser;

    public function __construct(User $unitBisnisUser)
    {
        $this->unitBisnisUser = $unitBisnisUser;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $namaUsaha = $this->unitBisnisUser->unitBisnisProfile->nama_usaha ?? $this->unitBisnisUser->name;

        return (new MailMessage)
            ->subject('Pendaftaran Unit Bisnis Baru Butuh Verifikasi')
            ->greeting('Halo Admin,')
            ->line("Unit Bisnis '{$namaUsaha}' baru saja mendaftar di platform ShareBite dan membutuhkan verifikasi berkas NIB.")
            ->action('Verifikasi NIB', route('admin.manajemen_pengguna', ['tab' => 'verifikasi_nib']))
            ->line('Silakan periksa detail pendaftaran pada dashboard Admin.')
            ->salutation('Salam, Tim ShareBite');
    }

    public function toArray($notifiable): array
    {
        $namaUsaha = $this->unitBisnisUser->unitBisnisProfile->nama_usaha ?? $this->unitBisnisUser->name;

        return [
            'title' => 'Mitra Baru Mendaftar!',
            'message' => "Unit Bisnis '{$namaUsaha}' telah mendaftar dan membutuhkan verifikasi berkas NIB.",
            'action_url' => route('admin.manajemen_pengguna', ['tab' => 'verifikasi_nib']),
            'icon' => 'register',
            'type' => 'register',
            'user_id' => $this->unitBisnisUser->id,
        ];
    }
}
