<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'room_name' => $this->room_name,
            'slug' => $this->slug,
            'price' => (float) $this->price,
            'currency' => $this->currency ? [
                'code' => $this->currency->code,
                'sign' => $this->currency->sign,
            ] : null,
            'featured_image' => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
        ];
    }
}
