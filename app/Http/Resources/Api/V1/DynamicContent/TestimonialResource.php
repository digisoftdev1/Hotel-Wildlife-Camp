<?php

namespace App\Http\Resources\Api\V1\DynamicContent;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
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
                'name'        => $this->name ?? null,
                'platform'    => $this->platform ?? null,
                'testimonial' => $this->testimonial ?? null,
                default       => null,
            };
        }

        return $data;
    }
}
