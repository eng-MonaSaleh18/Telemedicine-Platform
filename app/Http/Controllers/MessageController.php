<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $messageService ;
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService ;
    }



    /**
     * Display a listing of the resource.
     */
    public function getDoctorMessages($chatId)
    {
        $messages = $this->messageService->getDoctorMessages($chatId) ;
        return response()->json(MessageResource::collection($messages));
    }
    public function getPatientMessages($chatId)
    {
        $messages = $this->messageService->getPatientMessages($chatId) ;
        return response()->json(MessageResource::collection($messages));
    }

    
    public function sendMessage(MessageRequest $request , $chatId)
    {
        $messages = $this->messageService->sendMessage($request->validated() , $chatId) ;
        return response()->json(new MessageResource($messages));
    }

    
}
