<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HeroSectionController extends Controller
{
    public function getByPageSlug(string $page_slug)
    {
        try {
            $heroSection = \App\Models\HeroSection::with(['sliderImages', 'ctaButtons.page'])
                ->whereHas('page', fn($q) => $q->where('slug', $page_slug))
                ->first();
            if (!$heroSection) {
                return response()->json(['message' => 'Hero section not found'], 404);
            }
            return new \App\Http\Resources\Api\V1\HeroSectionSimpleResource($heroSection);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to retrieve hero section',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}