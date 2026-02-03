<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicalHistoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'chronic_diseases' => $this->chronic_diseases,
            'allergies' => $this->allergies,
            'current_medications' => $this->current_medications,
            'created_at' => $this->created_at,
        ];;
    }
}