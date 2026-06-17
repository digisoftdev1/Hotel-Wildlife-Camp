<?php

namespace App\Http\Resources\Api\V1\DynamicContent;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
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
            if ($field === 'slug')
                continue;
            $data[$field] = match ($field) {
                'featured_image' => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
                'blog_title' => $this->blog_title ?? null,
                'excerpt' => $this->excerpt ?? null,
                'category' => $this->category?->category_name ?? null,
                'keywords' => $this->keywords ?? null,
                'read_time' => $this->read_time ?? null,
                'created_at' => $this->created_at?->toDateString(),
                default => null,
            };
        }

        return $data;
    }
}
