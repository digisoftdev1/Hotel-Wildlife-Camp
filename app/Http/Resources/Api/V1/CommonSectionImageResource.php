<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;


class CommonSectionImageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
                        'image_path' => $this->image_path? Storage::disk('public')->url($this->image_path) : null,

            'order' => $this->order,
        ];
    }
}
