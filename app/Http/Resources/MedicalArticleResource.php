<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicalArticleResource extends JsonResource
{
    public function toArray($request)
    {
        return  [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}