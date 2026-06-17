<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageDetailResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'duration' => $this->duration,
            'grade' => $this->grade,
            'best_for' => $this->best_for,
            'includes' => $this->includes,
            'excerpt' => $this->excerpt,
            'overview' => $this->overview,
            'featured_image' => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
            'price' => (float) $this->price,
            'currency' => $this->currency ? [
                'code' => $this->currency->code,
                'sign' => $this->currency->sign,
            ] : null,
            'itinerary' => $this->itinerary,
            'category' => $this->category ? $this->category->name : null,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'gallery' => $this->whenLoaded('gallery', function() {
                if ($this->gallery && is_array($this->gallery->photos)) {
                    return array_map(function ($photo) {
                        return asset('storage/' . $photo);
                    }, $this->gallery->photos);
                }
                return [];
            }),
            'faqs' => $this->whenLoaded('faqs', function() {
                if ($this->faqs && is_array($this->faqs->faqs)) {
                    return $this->faqs->faqs;
                }
                return [];
            }),
        ];
    }
}
