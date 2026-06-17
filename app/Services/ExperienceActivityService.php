<?php

namespace App\Services;

use App\Models\ExperienceActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExperienceActivityService
{
    public function create(array $validated, Request $request): ExperienceActivity
    {
        $validated['slug'] = $this->generateUniqueSlug($validated['name']);
        $activity = new ExperienceActivity($validated);
        $activity->created_by = auth()->id();
        $activity->is_featured = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('experience_activities', 'public');
            $activity->featured_image = $path;
        }

        $activity->save();
        return $activity;
    }

    public function update(ExperienceActivity $activity, array $validated, Request $request): ExperienceActivity
    {
        if ($validated['name'] !== $activity->name) {
            $validated['slug'] = $this->generateUniqueSlug($validated['name'], $activity->id);
        }
        $activity->fill($validated);
        $activity->updated_by = auth()->id();
        $activity->is_featured = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('featured_image')) {
            if ($activity->featured_image) {
                Storage::disk('public')->delete($activity->featured_image);
            }
            $path = $request->file('featured_image')->store('experience_activities', 'public');
            $activity->featured_image = $path;
        }

        $activity->save();
        return $activity;
    }

    public function canFeature(int $currentFeaturedCount, bool $isCurrentlyFeatured): bool
    {
        return $isCurrentlyFeatured || $currentFeaturedCount < 6;
    }

    private function generateUniqueSlug(string $name, $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $query = ExperienceActivity::where('slug', 'LIKE', "$slug%" );
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
