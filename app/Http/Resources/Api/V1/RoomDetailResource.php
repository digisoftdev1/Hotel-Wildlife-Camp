<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'room_name' => $this->room_name,
            'slug' => $this->slug,
            'headline' => $this->headline,
            'occupancy' => $this->occupancy,
            'price' => (float) $this->price,
            'currency' => $this->currency ? [
                'code' => $this->currency->code,
                'sign' => $this->currency->sign,
            ] : null,
            'room_size' => $this->room_size,
            'excerpt' => $this->excerpt,
            'description' => $this->description,
            'featured_image' => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
            'status' => $this->status,
            'amenities' => $this->whenLoaded('amenities', function() {
                return $this->amenities->map(function ($amenity) {
                    return [
                        'id' => $amenity->id,
                        'name' => $amenity->name,
                        'icon' => $amenity->icon,
                    ];
                });
            }),
            'special_features' => $this->whenLoaded('specialFeatures', function() {
                return $this->specialFeatures->map(function ($feature) {
                    return [
                        'id' => $feature->id,
                        'name' => $feature->name,
                        'icon' => $feature->icon,
                    ];
                });
            }),
            'beds' => $this->whenLoaded('beds', function() {
                return $this->beds->map(function ($bed) {
                    return [
                        'id' => $bed->id,
                        'bed_type' => $bed->bed_type,
                        'count' => $bed->count,
                    ];
                });
            }),
            'gallery' => $this->whenLoaded('gallery', function() {
                if ($this->gallery && $this->gallery->hasPhotos()) {
                    return array_map(function ($photo) {
                        return asset('storage/' . $photo);
                    }, $this->gallery->photos);
                }
                return [];
            }),
        ];
    }
}
