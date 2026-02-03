<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Message; // افترض أن لديك نموذج Message

class ChatMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message; // متغير لتخزين الرسالة

    /**
     * Create a new notification instance.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['fcm']; // هنا نحدد القناة الخاصة بـ FCM
    }

    /**
     * Get the FCM representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toFcm($notifiable)
    {
        return [
            'title' => 'رسالة جديدة',
            'body' => 'لديك رسالة من ' . $this->message->sender->name . ': ' . $this->shorten($this->message->message, 50),
            'data' => [
                'message_id' => $this->message->id,
                'sender_id' => $this->message->sender_id,
            ],
        ];
    }

    /**
     * دالة مساعدة لتقصير النص
     */
    private function shorten(string $text, int $limit): string
    {
        return mb_strlen($text) > $limit 
            ? mb_substr($text, 0, $limit - 3) . '...' 
            : $text;
    }
}