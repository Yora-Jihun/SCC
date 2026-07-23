<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;

class FriendshipFormed extends Notification
{
    public function __construct(public User $otherUser) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => "You and {$this->otherUser->name} are now friends!",
            'user_id' => $this->otherUser->id,
        ];
    }
}