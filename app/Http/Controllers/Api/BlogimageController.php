<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogimageController extends Controller
{
    public function uploadEditorImage(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $path = $image->store('blog-content-images', 'public');
                $filename = basename($path);

                return response()->json([
                    'result' => [
                        [
                            'url' => Storage::disk('public')->url($path),
                            'name' => $filename,
                            'size' => $image->getSize()
                        ]
                    ]
                ]);
            }

            return response()->json([
                'errorMessage' => 'No file uploaded'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'errorMessage' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteImage(Request $request)
    {
        try {
            $request->validate([
                'image_path' => 'required|string'
            ]);

            $url = $request->image_path;
            $imagePath = Str::after($url, '/storage/');

            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);

                return response()->json(['success' => true]);
            }

            return response()->json(['error' => 'Image not found'], 404);

        } catch (\Exception $e) {
            return response()->json([
                'errorMessage' => $e->getMessage()
            ], 500);
        }
    }
}