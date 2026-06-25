<?php

namespace App\Services;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PackageService
{
    public function create(array $validated, Request $request): Package
    {
        $validated['slug'] = $this->generateUniqueSlug($validated['name']);
        $package = new Package($validated);
        $package->created_by = auth()->id();
        $package->is_featured = $request->has('is_featured') ? 1 : 0;
        
        if ($request->hasFile('featured_image')) {
            $package->featured_image = $request->file('featured_image')->store('packages', 'public');
        }

        // Handle Itinerary
        if ($request->has('itinerary_days') && $request->has('itinerary_desc')) {
            $itinerary = [];
            foreach ($request->itinerary_days as $index => $day) {
                if (!empty($day)) {
                    $itinerary[] = [
                        'day' => $day,
                        'description' => $request->itinerary_desc[$index] ?? ''
                    ];
                }
            }
            $package->itinerary = $itinerary;
        }

        $package->save();
        return $package;
    }

    public function update(Package $package, array $validated, Request $request): Package
    {
        if ($validated['name'] !== $package->name) {
            $validated['slug'] = $this->generateUniqueSlug($validated['name'], $package->id);
        }
        
        if ($request->hasFile('featured_image')) {
            if ($package->featured_image) {
                Storage::disk('public')->delete($package->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('packages', 'public');
        }

        $package->fill($validated);
        $package->updated_by = auth()->id();
        $package->is_featured = $request->has('is_featured') ? 1 : 0;

        // Handle Itinerary
        if ($request->has('itinerary_days') && $request->has('itinerary_desc')) {
            $itinerary = [];
            foreach ($request->itinerary_days as $index => $day) {
                if (!empty($day)) {
                    $itinerary[] = [
                        'day' => $day,
                        'description' => $request->itinerary_desc[$index] ?? ''
                    ];
                }
            }
            $package->itinerary = $itinerary;
        }

        $package->save();
        return $package;
    }

    public function canFeature(int $currentFeaturedCount, bool $isCurrentlyFeatured): bool
    {
        return $isCurrentlyFeatured || $currentFeaturedCount < 6;
    }

    private function generateUniqueSlug(string $name, $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $query = Package::where('slug', 'LIKE', "$slug%");
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        $slugCount = $query->count();
        if ($slugCount > 0) {
            $slug .= '-' . ($slugCount + 1);
        }
        return $slug;
    }
}
