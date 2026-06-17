<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'duration' => $this->duration,
            'grade' => $this->grade,
            'best_for' => $this->best_for,
            'price' => (float) $this->price,
            'currency' => $this->currency ? [
                'code' => $this->currency->code,
                'sign' => $this->currency->sign,
            ] : null,
            'category' => $this->category ? $this->category->name : null,
            'featured_image' => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
        ];
    }
}
