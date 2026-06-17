<?php

namespace App\Http\Resources\Api\V1\DynamicContent;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    protected array $fields;

    public function __construct($resource, array $fields = [])
    {
        parent::__construct($resource);
        $this->fields = $fields;
    }

    public function toArray(Request $request): array
    {
        $data = [];

        if (isset($this->slug)) {
            $data['slug'] = $this->slug;
        }

        foreach ($this->fields as $field) {
            if ($field === 'slug') continue;
            $data[$field] = match ($field) {
                'featured_image'   => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
                'room_name'        => $this->room_name ?? null,
                'headline'         => $this->headline ?? null,
                'occupancy'        => $this->occupancy ?? null,
                'room_size'        => $this->room_size ?? null,
                'price'            => $this->price ?? null,
                'currency'         => $this->currency ?? null,
                'excerpt'          => $this->excerpt ?? null,
                'description'      => $this->description ?? null,
                'amenities'        => $this->amenities?->pluck('name')->toArray() ?? [],
                'beds'             => $this->beds?->map(fn($b) => ['type' => $b->type, 'count' => $b->count])->toArray() ?? [],
                'special_features' => $this->specialFeatures?->pluck('name')->toArray() ?? [],
                'gallery'          => $this->gallery && is_array($this->gallery->photos) 
                    ? array_map(fn($p) => asset('storage/' . $p), $this->gallery->photos) 
                    : [],
                default            => null,
            };
        }

        return $data;
    }
}
