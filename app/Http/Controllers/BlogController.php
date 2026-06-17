<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreBlogRequest;
use App\Models\BlogCategory;
use App\Models\Blog;
use App\Http\Requests\UpdateBlogRequest;
use Illuminate\Support\Facades\Storage;



class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Blog::with('category')->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('featured')) {
            $query->where('is_featured', $request->featured);
        }

        $blogs = $query->get();
        $categories = BlogCategory::select('id', 'category_name as name')->get();

        return view('blogs.blogindex', compact('blogs', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = BlogCategory::select('id', 'category_name')->get();
        $featuredCount = Blog::where('is_featured', 1)->count();
        return view('blogs.createblog', compact('categories', 'featuredCount'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogRequest $request)
    {
        $validated = $request->validated();
        $featuredImageFile = $request->file('featured_image');

        $isFeatured = $request->boolean('is_featured') ? 1 : 0;

        if ($isFeatured) {
            $featuredCount = Blog::where('is_featured', 1)->count();
            if ($featuredCount >= 3) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Maximum 3 blogs can be featured at a time.');
            }
        }

        $validated['is_featured'] = $isFeatured;
        $validated['read_time'] = Blog::calculateReadTime($validated['content']);
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        unset($validated['featured_image']);
        $blog = Blog::create($validated);

        if ($featuredImageFile) {
            $path = $featuredImageFile->store('blogs', 'public');
            $blog->update(['featured_image' => $path]);
        }

        return redirect()->route('blogs.index')
            ->with('success', 'Blog post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = BlogCategory::select('id', 'category_name')->get();
        $blog = Blog::findOrFail($id);
        $featuredCount = Blog::where('is_featured', 1)->count();

        return view('blogs.createblog', compact('categories', 'blog', 'featuredCount'));
    }


    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $validated = $request->validated();

        $isFeatured = $request->boolean('is_featured') ? 1 : 0;

        // Check limit if setting to featured and it wasn't already featured
        if ($isFeatured && !$blog->is_featured) {
            $featuredCount = Blog::where('is_featured', 1)->count();
            if ($featuredCount >= 3) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Maximum 3 blogs can be featured at a time.');
            }
        }

        $validated['is_featured'] = $isFeatured;

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        $validated['read_time'] = Blog::calculateReadTime($validated['content']);
        $validated['updated_by'] = auth()->id();
        $blog->update($validated);

        return redirect()->route('blogs.index')
            ->with('success', 'Blog post updated successfully!');
    }

    public function destroy(string $id)
    {
        $blog = Blog::findOrFail($id);

        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        $blog->delete();

        return redirect()->back()->with('success', 'Blog post deleted successfully!');
    }

    public function toggleFeatured(Blog $blog)
    {
        $newStatus = !$blog->is_featured;

        if ($newStatus) {
            $featuredCount = Blog::where('is_featured', 1)->count();
            if ($featuredCount >= 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maximum 3 blogs can be featured at a time.'
                ], 422);
            }
        }

        $blog->update(['is_featured' => $newStatus]);

        return response()->json([
            'success' => true,
            'is_featured' => $blog->is_featured,
            'message' => $blog->is_featured ? 'Blog featured successfully!' : 'Blog unfeatured successfully!'
        ]);
    }
}