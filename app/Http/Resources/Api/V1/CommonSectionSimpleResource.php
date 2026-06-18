<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\V1\DynamicContent\ActivityResource;
use App\Http\Resources\Api\V1\DynamicContent\BlogResource;
use App\Http\Resources\Api\V1\DynamicContent\ContactResource;
use App\Http\Resources\Api\V1\DynamicContent\GalleryCategoryResource;
use App\Http\Resources\Api\V1\DynamicContent\PackageResource;
use App\Http\Resources\Api\V1\DynamicContent\RoomResource;
use App\Http\Resources\Api\V1\DynamicContent\ServiceResource;
use App\Http\Resources\Api\V1\DynamicContent\TestimonialResource;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\ExperienceActivity;
use App\Models\GalleryCategory;
use App\Models\Package;
use App\Models\Room;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommonSectionSimpleResource extends JsonResource
{
    public function toArray(Request $request): array
{
    $displayFields = $this->display_fields ?? [];

    $data = [
        'section_id'    => $this->id,
        'order'         => $this->order,
        'section_type'  => $this->section_type,
        'section_title' => $this->section_title,
        'heading'       => $this->heading,
        'description'   => $this->description,
        'cta_buttons'   => CommonSectionCtaButtonResource::collection($this->whenLoaded('ctaButtons')),
        'images'        => CommonSectionImageResource::collection($this->whenLoaded('images')),
        'content'       => $this->resolveContent($this->section_type,$displayFields),
    ];

    if ($this->relationLoaded('about') && $this->about) {
        $data['about'] = [
            'established_year' => $this->about->established_year,
            'established_description' => $this->about->established_description,
            'location' => $this->about->location,
            'location_description' => $this->about->location_description,
        ];
    }

    return $data;
}

    protected function resolveContent(?string $sectionType, array $displayFields): array
    {
        if (!$sectionType || empty($displayFields)) {
            return [];
        }

        $fields = array_values(array_unique($displayFields));

        return match ($sectionType) {
            'accommodation'       => $this->resolveRooms($fields),
            'services'            => $this->resolveServices($fields),
            'testimonials'        => $this->resolveTestimonials($fields),
            'featured_blogs'      => $this->resolveBlogs($fields),
            'featured_activities' => $this->resolveActivities($fields),
            'featured_packages'   => $this->resolvePackages($fields),
            'gallery'             => $this->resolveGallery($fields),
            'contact'             => $this->resolveContact($fields),
            default               => [],
        };
    }

    protected function resolveRooms(array $fields): array
    {
        $query = Room::query();
        if (in_array('amenities', $fields))       $query->with('amenities');
        if (in_array('beds', $fields))            $query->with('beds');
        if (in_array('special_features', $fields)) $query->with('specialFeatures');
        if (in_array('gallery', $fields))          $query->with('gallery');

        return $query->get()->map(fn($item) => (new RoomResource($item, $fields))->toArray(request()))->toArray();
    }

    protected function resolveServices(array $fields): array
    {
        return Service::all()->map(fn($item) => (new ServiceResource($item, $fields))->toArray(request()))->toArray();
    }

    protected function resolveTestimonials(array $fields): array
    {
        return Testimonial::all()->map(fn($item) => (new TestimonialResource($item, $fields))->toArray(request()))->toArray();
    }

    protected function resolveBlogs(array $fields): array
    {
        $query = Blog::query();
        if (in_array('category', $fields)) $query->with('category');

        return $query->get()->map(fn($item) => (new BlogResource($item, $fields))->toArray(request()))->toArray();
    }

    protected function resolveActivities(array $fields): array
    {
        $query = ExperienceActivity::query();
        if (in_array('category', $fields)) $query->with('category');

        return $query->get()->map(fn($item) => (new ActivityResource($item, $fields))->toArray(request()))->toArray();
    }

    protected function resolvePackages(array $fields): array
    {
        $query = Package::query();
        if (in_array('category', $fields)) $query->with('category');
        if (in_array('gallery', $fields))  $query->with('gallery');

        return $query->get()->map(fn($item) => (new PackageResource($item, $fields))->toArray(request()))->toArray();
    }

    protected function resolveGallery(array $fields): array
    {
        $query = GalleryCategory::query();
        if (in_array('images', $fields)) $query->with('images');

        return $query->get()->map(fn($item) => (new GalleryCategoryResource($item, $fields))->toArray(request()))->toArray();
    }

    protected function resolveContact(array $fields): array
    {
        $contact = Contact::first();
        if (!$contact) return [];

        return [(new ContactResource($contact, $fields))->toArray(request())];
    }
}