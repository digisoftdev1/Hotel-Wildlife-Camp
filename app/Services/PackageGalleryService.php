<?php

namespace App\Services;

use App\Models\Package;
use App\Models\PackageGallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageGalleryService
{
    public function updateGallery(Package $package, array $newPhotos = [], array $existingPhotos = [])
    {
        $gallery = $package->gallery ?? new PackageGallery(['package_id' => $package->id]);
        $currentPhotos = $gallery->photos ?? [];

        // Identify photos to delete (present in current but not in existing)
        $photosToDelete = array_diff($currentPhotos, $existingPhotos);
        foreach ($photosToDelete as $photo) {
            Storage::disk('public')->delete($photo);
        }

        // Process new uploads
        $uploadedPhotos = [];
        $dir = 'packages/gallery/' . ($package->slug ?? Str::slug($package->name));
        
        foreach ($newPhotos as $file) {
            if ($file->isValid()) {
                $path = $file->store($dir, 'public');
                $uploadedPhotos[] = $path;
            }
        }

        // Combine existing (kept) photos and new uploads
        $gallery->photos = array_merge($existingPhotos, $uploadedPhotos);
        $gallery->save();

        return $gallery;
    }
}
