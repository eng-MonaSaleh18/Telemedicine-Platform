<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this['id'],
            'user_id' => $this['user_id'],
            'user' => new UserResource($this->whenLoaded('user')),
            'first_name'  => $this['first_name'],
            'last_name' => $this['last_name'],
            'age' => $this['age'],
            'address' => $this['address'],
            'phone' => $this['phone'],
            'is_active' => $this['is_active'],
            'credentials' => DoctorCredentialResource::collection($this->whenLoaded('doctorCredentials')),
            'specializations' => SpecializationResource::collection($this->whenLoaded('specializations')),
        ];
    }
}
