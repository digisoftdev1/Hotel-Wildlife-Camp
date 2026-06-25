<?php

namespace App\Services;

use App\Models\GalleryCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryCategoryService
{
    public function listCategories(): Collection
    {
        return GalleryCategory::withCount('images')->get();
    }

    public function findCategoryWithImages(int $id): GalleryCategory
    {
        return GalleryCategory::with('images')->findOrFail($id);
    }

    public function createCategory(array $validated, ?UploadedFile $imageFile = null): GalleryCategory
    {
        return DB::transaction(function () use ($validated, $imageFile) {
            $slug = $this->uniqueSlug($validated['name']);

            $category = GalleryCategory::create([
                'name' => $validated['name'],
                'slug' => $slug,
                'image' => null,
            ]);

            if ($imageFile) {
                $category->update([
                    'image' => $this->storeCategoryImage($category->id, $imageFile),
                ]);
            }

            return $category->refresh();
        });
    }

    public function updateCategory(GalleryCategory $category, array $validated, ?UploadedFile $imageFile = null): GalleryCategory
    {
        return DB::transaction(function () use ($category, $validated, $imageFile) {
            $oldPath = null;
            $newSlug = Str::slug($validated['name']);

            if ($newSlug !== $category->slug) {
                $category->slug = $this->uniqueSlug($validated['name'], $category->id);
            }

            $category->name = $validated['name'];

            if ($imageFile) {
                $oldPath = $category->image;
                $category->image = $this->storeCategoryImage($category->id, $imageFile);
            }

            $category->save();

            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            return $category->refresh();
        });
    }

    public function deleteCategory(GalleryCategory $category): void
    {
        DB::transaction(function () use ($category) {
            Storage::disk('public')->deleteDirectory("gallery/{$category->id}");
            $category->delete();
        });
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (GalleryCategory::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    private function storeCategoryImage(int $categoryId, UploadedFile $imageFile): string
    {
        return $imageFile->store("gallery/{$categoryId}/featured_image", 'public');
    }
}