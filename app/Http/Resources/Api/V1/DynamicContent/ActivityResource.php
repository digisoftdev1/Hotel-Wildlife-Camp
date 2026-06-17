<?php

namespace App\Http\Resources\Api\V1\DynamicContent;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
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
                'name'             => $this->name ?? null,
                'excerpt'          => $this->excerpt ?? null,
                'duration'         => $this->duration ?? null,
                'difficulty_level' => $this->difficulty_level ?? null,
                'best_time'        => $this->best_time ?? null,
                'category'         => $this->category?->name ?? null,
                default            => null,
            };
        }

        return $data;
    }
}
