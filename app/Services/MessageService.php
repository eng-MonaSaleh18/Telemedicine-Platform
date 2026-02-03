<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\Doctor;
use App\Models\Message;
use App\Notifications\NewMessageNotification;  // إضافة import للـ Notification
use App\Models\User;  // إضافة import لـ User
use App\Notifications\ChatMessageNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessageService
{
    public function __construct() {}

    public function sendMessage(array $data, $chatId)
{
    $chat = Chat::findOrFail($chatId);
    $senderId = Auth::id();
    $receiverId = ($senderId == $chat->patient->user_id) ? $chat->doctor->user_id : $chat->patient->user_id;

    // التحقق من الصلاحيات
    if ($senderId != $chat->patient->user_id && $senderId != $chat->doctor->user_id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // حفظ الرسالة
    $message = Message::create([
        'chat_id' => $chatId,
        'sender_id' => $senderId,
        'receiver_id' => $receiverId,
        'message' => $data['message'],
    ]);

    // إرسال الإشعار للمستلم عبر FCM (بعد الحفظ الناجح)
    /* $receiver = User::find($receiverId);
    if ($receiver && $receiver->fcmTokens->isNotEmpty()) {
        Log::info('Sending FCM Notification to receiver ' . $receiverId);
        $receiver->notify(new ChatMessageNotification($message));  // استدعاء الإشعار باستخدام الكلاس الجديد
    } else {
        Log::info('No FCM tokens for receiver ' . $receiverId . ' – No notification sent');
    }
 */
    Log::info('Sending to Socket.IO: ', ['chatId' => $chatId, 'message' => $message->toArray()]);
    $response = Http::post('http://localhost:3000/emit-message', [
        'chatId' => $chatId,
        'message' => $message->toArray(),
    ]);
    Log::info('Socket.IO Response: ', ['status' => $response->status()]);

    return $message;
}
    public function getDoctorMessages($chatId)
    {
        $messages = Message::where('chat_id', $chatId)->with('sender', 'receiver')->get();
        return $messages;
    }

    public function getPatientMessages($chatId)
    {
        $messages = Message::where('chat_id', $chatId)->with('sender', 'receiver')->get();
        return $messages;
    }
}