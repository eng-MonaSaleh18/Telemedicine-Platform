<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this['id'] ,
            'doctor_id' => $this['doctor_id'] ,
            'patient_id' => $this['patient_id'] ,
            'doctor' => new DoctorResource($this->whenLoaded('doctor')),
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'messages' => MessageResource::collection($this->whenLoaded('messages')),
        ];
    }
}