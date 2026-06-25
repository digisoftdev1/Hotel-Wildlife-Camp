<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class PageListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}