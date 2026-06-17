<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogCategory;


class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::all();
        return response()->json($categories);
    }
    public function store(Request $request)
    {

        $validated = $request->validate([
            'category_name' => 'required|string|max:255|unique:blog_categories,category_name'
        ]);

        $category = BlogCategory::create(
            ['category_name' => $validated['category_name']]
        );

        return response()->json([
            'id' => $category->id,
            'category_name' => $category->category_name
        ]);
    }
}