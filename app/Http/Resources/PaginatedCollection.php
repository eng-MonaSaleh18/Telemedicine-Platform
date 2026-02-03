<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */


    protected $pagination;
    public function __construct($resource)
    {
        $this->pagination =[
            'total' => $resource->total(), // all models count
            'count' => $resource->count(), // paginated result count
            'per_page' => $resource->perPage(),
            'current_page' => $resource->currentPage(),
            'total_pages' => $resource->lastPage(),
            'next_page_url'=>$resource->nextPageUrl(),
        ];

        $resource = $resource->getCollection();

        parent::__construct($resource);
    }

    
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,

            // pagination data
            'pagination' => $this->pagination
        ];
    }
}
