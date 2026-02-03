<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorCredentialResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this['id'],
            'doctor_id' => $this['doctor_id'],
            'file_path' => $this['file_path'],
            'file_name' => $this['file_name'],
            'description' => $this['description'],
            
        ];
    }
}
