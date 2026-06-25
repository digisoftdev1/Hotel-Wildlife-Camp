<?php

namespace App\Http\Resources\Api\V1\DynamicContent;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
                'featured_image' => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
                'name'           => $this->name ?? null,
                'duration'       => $this->duration ?? null,
                'grade'          => $this->grade ?? null,
                'best_for'       => $this->best_for ?? null,
                'price'          => $this->price ?? null,
                'currency'       => $this->currency ?? null,
                'excerpt'        => $this->excerpt ?? null,
                'category'       => $this->category?->name ?? null,
                'gallery'        => $this->gallery && is_array($this->gallery->photos) 
                    ? array_map(fn($p) => asset('storage/' . $p), $this->gallery->photos) 
                    : [],
                default          => null,
            };
        }

        return $data;
    }
}
