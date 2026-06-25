<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceActivityListResource extends JsonResource
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
            'excerpt' => $this->excerpt,
            'duration' => $this->duration,
            'difficulty_level' => $this->difficulty_level,
            'best_time' => $this->best_time,
            'category' => $this->category ? $this->category->name : null,
            'featured_image' => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
        ];
    }
}
