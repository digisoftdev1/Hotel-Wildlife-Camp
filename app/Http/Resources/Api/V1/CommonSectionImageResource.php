<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CommonSectionImageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'image_path' => $this->image_path,
            'order' => $this->order,
        ];
    }
}
