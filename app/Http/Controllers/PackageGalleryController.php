<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageGallery;
use App\Services\PackageGalleryService;
use Illuminate\Http\Request;

class PackageGalleryController extends Controller
{
    protected $service;

    public function __construct(PackageGalleryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $packages = Package::with('gallery')->orderBy('created_at', 'desc')->get();
        return view('packages.gallery.index', compact('packages'));
    }

    public function store(Request $request, Package $package)
    {
        $request->validate([
            'new_photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'existing_photos' => 'nullable|array',
        ]);

        try {
            $this->service->updateGallery(
                $package, 
                $request->file('new_photos', []), 
                $request->input('existing_photos', [])
            );

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Gallery updated successfully.']);
            }

            return back()->with('success', 'Gallery updated successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Failed to update gallery: ' . $e->getMessage()], 422);
            }
            return back()->with('error', 'Failed to update gallery: ' . $e->getMessage());
        }
    }
}
