<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Services\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $chatService ;
    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService ;
    }
    



    public function getDoctorChats()
    {
        $chats = $this->chatService->getDoctorChats();
        return response()->json(ChatResource::collection($chats));
    }



    
    public function getPatientChats()
    {
        $chats = $this->chatService->getPatientChats();
        return response()->json(ChatResource::collection($chats));
    }

    
}
