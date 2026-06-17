<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogDetailResource extends JsonResource
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
            'blog_title' => $this->blog_title,
            'slug' => $this->slug,
            'content' => $this->content,
            'featured_image' => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
            'blog_category' => $this->category ? $this->category->category_name : null,
            'excerpt' => $this->excerpt,
            'keywords' => $this->keywords,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'read_time' => $this->read_time,
        ];
    }
}
