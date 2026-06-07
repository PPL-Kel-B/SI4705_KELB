<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Chat;

class ChatMasukNotification extends Notification
{
    use Queueable;

    protected $chat;

    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $senderName = $this->chat->sender->name;
        $snippet = mb_strimwidth($this->chat->pesan, 0, 50, '...');

        // Determine action URL based on user role
        $actionUrl = '#';
        if ($notifiable->role === 'admin') {
            $actionUrl = route('admin.chat.show', $this->chat->sender_id);
        } elseif ($notifiable->role === 'unit_bisnis') {
            $actionUrl = route('unit.chat');
        } elseif ($notifiable->role === 'individu' || $notifiable->role === 'komunitas') {
            $actionUrl = route('user.chat');
        }

        return [
            'title' => 'Pesan Chat Baru!',
            'message' => "Pesan dari {$senderName}: \"{$snippet}\"",
            'action_url' => $actionUrl,
            'icon' => 'chat',
            'type' => 'chat',
            'chat_id' => $this->chat->id,
            'sender_id' => $this->chat->sender_id,
        ];
    }
}
