<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'prescription' => [
                'id' => $this->resource['prescription']->id,
                'medication' => $this->resource['prescription']->medication,
                'instructions' => $this->resource['prescription']->instructions,
                'signature' => $this->resource['prescription']->signature,
                'created_at' => $this->resource['prescription']->created_at,
            ],
            'medical_history' => $this->resource['medical_history']
                ? new MedicalHistoryResource($this->resource['medical_history'])
                : null,
            'medical_files' => MedicalFileResource::collection($this->resource['medical_files']),
        ];
    }
}