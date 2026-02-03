<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FcmChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     */
    /**
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification&\App\Notifications\ChatMessageNotification $notification
     */


    public function send($notifiable, Notification $notification)
    {
        $tokens = $notifiable->fcmTokens->pluck('token')->toArray(); // افترض عمود 'token' في جدول fcmTokens
        if (empty($tokens)) {
            return;
        }

        $messageData = $notification->toFcm($notifiable);

        $fcmNotification = FcmNotification::create($messageData['title'], $messageData['body']);

        $cloudMessage = CloudMessage::new()
            ->withNotification($fcmNotification)
            ->withData($messageData['data'] ?? []);

        try {
            Firebase::messaging()->sendMulticast($cloudMessage, $tokens); // إرسال إلى توكنز متعددة
        } catch (\Exception $e) {
            Log::error('FCM Error: ' . $e->getMessage());
        }
    }
}
