<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'chat_id' => $this['chat_id'],
            'sender_id' => $this['sender_id'],
            'receiver_id' => $this['receiver_id'],
            'sender' => new UserResource($this->whenLoaded('sender' )),  // بيانات المرسل الكاملة
            'receiver' => new UserResource($this->whenLoaded('receiver')),
            
            'message' => $this['message'],
            
        ];
    }
}
