<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this['id'],
            'user_id'         => $this['user_id'],
            'user'            => new UserResource($this->whenLoaded('user')),
            'first_name'      => $this['first_name'],
            'last_name'       => $this['last_name'],
            'age'             => $this['age'],
            'address'         => $this['address'],
            'phone'           => $this['phone'],
            'is_active'       => $this['is_active'],
            'languages' => $this['languages'],
            'years_of_experience' => $this['years_of_experience'],
            'credentials'     => DoctorCredentialResource::collection($this->whenLoaded('doctorCredentials')),
            'specializations' => new SpecializationResource($this->whenLoaded('specialization')),
            'rating'          => [
                'average'       => round($this->ratings_avg_rating ?? 0, 2),
                'total_reviews' => $this->ratings_count ?? 0,
                'out_of'        => 5,              
                'stars'         => $this->getStarRating(), 
            ],
        ];
    }
    private function getStarRating(): int
    {
        $average = $this->ratings_avg_rating ?? 0;
        return (int) round($average); // تقريب لأقرب رقم صحيح
    }
}
