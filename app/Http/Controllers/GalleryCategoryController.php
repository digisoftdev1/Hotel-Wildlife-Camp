<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGalleryCategoryRequest;
use App\Http\Requests\UpdateGalleryCategoryRequest;
use Illuminate\Http\Request;
use App\Models\GalleryCategory;
use App\Services\GalleryCategoryService;


class GalleryCategoryController extends Controller
{
    public function __construct(protected GalleryCategoryService $galleryCategoryService)
    {
    }

    public function index(Request $request)
    {
            $categories = $this->galleryCategoryService->listCategories();

            return view('gallerycategory.galleryindex', [
                'categories' => $categories,
            ]);
    }

    public function store(StoreGalleryCategoryRequest $request)
    {
        $this->galleryCategoryService->createCategory($request->validated(), $request->file('image'));

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
        ]);
    }

    public function show($id)
    {
        $category = $this->galleryCategoryService->findCategoryWithImages((int) $id);

        return view('gallerycategory.show', [
            'category' => $category,
        ]);
    }

    public function update(UpdateGalleryCategoryRequest $request, GalleryCategory $category)
    {
        $this->galleryCategoryService->updateCategory($category, $request->validated(), $request->file('image'));

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
        ]);
    }

    public function destroy(GalleryCategory $category)
    {
        $this->galleryCategoryService->deleteCategory($category);

        return response()->json([
            'success' => true,
            'message' => 'Category and its images deleted successfully',
        ]);
    }
}