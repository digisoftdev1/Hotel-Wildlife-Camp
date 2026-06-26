<?php

namespace App\Http\Controllers;

use App\Models\ActivityPackageCategory;
use Illuminate\Http\Request;

class ActivityPackageCategoryController extends Controller
{
    public function index()
    {
        $categories = ActivityPackageCategory::orderBy('name')->get();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:100|unique:activity_package_categories,name',
            'description' => 'nullable|string|max:255',
        ]);

        $category = new ActivityPackageCategory();
        $category->name = $validated['category_name'];
        $category->description = $validated['description'];
        $category->created_by = auth()->id();
        $category->save();

        return response()->json($category, 201);
    }
}