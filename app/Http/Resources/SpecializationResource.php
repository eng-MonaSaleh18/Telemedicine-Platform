<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecializationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this['id'],
            'Specialization' => $this['Specialization'],
            'doctor' => DoctorResource::collection($this->whenLoaded('doctors')),
        ];
    }
}