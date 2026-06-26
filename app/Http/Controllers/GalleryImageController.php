<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGalleryImageRequest;
use App\Http\Requests\UpdateGalleryImageRequest;
use App\Models\GalleryImage;
use App\Services\GalleryImageService;

class GalleryImageController extends Controller
{
    public function __construct(protected GalleryImageService $galleryImageService)
    {
    }

    public function store(StoreGalleryImageRequest $request)
    {
        $galleryImage = $this->galleryImageService->createImage($request->validated(), $request->file('image'));

        return response()->json([
            'success' => true,
            'message' => 'Image added successfully',
            'data' => $galleryImage
        ]);
    }

    public function update(UpdateGalleryImageRequest $request, GalleryImage $galleryImage)
    {
        $this->galleryImageService->updateImage($galleryImage, $request->validated(), $request->file('image'));

        return response()->json([
            'success' => true,
            'message' => 'Image updated successfully',
        ]);
    }

    public function destroy(GalleryImage $galleryImage)
    {
        $this->galleryImageService->deleteImage($galleryImage);

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully',
        ]);
    }
}