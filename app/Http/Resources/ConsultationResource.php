<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConsultationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this['id'],
            'patient_id' => $this['patient_id'],
            'doctor_id' => $this['doctor_id'],
            'appointment_id'=> $this['appointment_id'] ,
            'notes'=> $this['notes'] ,
            'meet_url'=> $this['meet_url'] ,
        ];
    }
}