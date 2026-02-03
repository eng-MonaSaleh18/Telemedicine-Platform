<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
        ];
    }
}