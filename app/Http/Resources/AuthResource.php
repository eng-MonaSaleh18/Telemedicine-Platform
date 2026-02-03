<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'email' => $this->email,
            'role' => $this->getRoleNames()->first(), // استرجاع اسم الدور باستخدام Spatie
        ];

        if ($this->hasRole('patient') && $this->patient) {
            $data['patient'] = new PatientResource($this->patient);
        } elseif ($this->hasRole('doctor') && $this->doctor) {
            $data['doctor'] = new DoctorResource($this->doctor);
            
        }

        return $data;
    }
}
