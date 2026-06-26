<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->blog_title,
            'slug' => $this->slug,
            'featured_image' => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
            'blog_category' => $this->category ? $this->category->category_name : null,
        ];
    }
}
