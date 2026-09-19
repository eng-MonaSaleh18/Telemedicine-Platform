<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            
            'Complainant' => new PatientResource($this->whenLoaded('patient')),
            'accused' => new DoctorResource($this->whenLoaded('doctor')),
            
            'complaint_type' => $this->complaint_type,
            
            
            'description' => $this->description,
            'contact_number' => $this->contact_number,
            
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}