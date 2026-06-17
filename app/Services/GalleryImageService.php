<?php

namespace App\Services;

use App\Models\GalleryImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GalleryImageService
{
    public function createImage(array $validated, ?UploadedFile $imageFile = null): GalleryImage
    {
        return DB::transaction(function () use ($validated, $imageFile) {
            $imagePath = null;

            if ($imageFile) {
                $imagePath = $this->storeImage($validated['gallery_category_id'], $imageFile);
            }

            return GalleryImage::create([
                'gallery_category_id' => $validated['gallery_category_id'],
                'name' => $validated['name'],
                'image' => $imagePath,
            ]);
        });
    }

    public function updateImage(GalleryImage $galleryImage, array $validated, ?UploadedFile $imageFile = null): GalleryImage
    {
        return DB::transaction(function () use ($galleryImage, $validated, $imageFile) {
            $oldPath = null;
            $galleryImage->name = $validated['name'];

            if ($imageFile) {
                $oldPath = $galleryImage->image;
                $galleryImage->image = $this->storeImage($galleryImage->gallery_category_id, $imageFile);
            }

            $galleryImage->save();

            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            return $galleryImage->refresh();
        });
    }

    public function deleteImage(GalleryImage $galleryImage): void
    {
        DB::transaction(function () use ($galleryImage) {
            if ($galleryImage->image && Storage::disk('public')->exists($galleryImage->image)) {
                Storage::disk('public')->delete($galleryImage->image);
            }

            $galleryImage->delete();
        });
    }

    private function storeImage(int $galleryCategoryId, UploadedFile $imageFile): string
    {
        return $imageFile->store("gallery/{$galleryCategoryId}/images", 'public');
    }
}