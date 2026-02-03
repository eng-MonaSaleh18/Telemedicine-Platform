<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this['id'],
            'patient_id' => $this['patient_id'],
            'doctor_id' => $this['doctor_id'],
            'start_time'=> $this['start_time'] ,
            'end_time' => $this['end_time'],
            'status' => $this['status'],
            'price' => $this['price'],
            'notes' => $this['notes'],
        ];
    }
}
