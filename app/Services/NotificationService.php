<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public function send($userId, $type, $data = null)
    {
        Notification::create([
            'user_id' => $userId,
            'type'    => $type,
            'data'    => $data,
        ]);
    }
}
