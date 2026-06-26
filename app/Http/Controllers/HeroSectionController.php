<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHeroSectionRequest;
use App\Models\HeroSection;
use App\Models\Page;
use App\Services\HeroSectionService;
use Illuminate\Http\Request;

class HeroSectionController extends Controller
{
    protected $heroService;

    public function __construct(HeroSectionService $heroService)
    {
        $this->heroService = $heroService;
    }

    /**
     * Homepage Management Dashboard (Special Case).
     */
    public function index()
    {
        $page = Page::where('slug', 'home')->first();
        if (!$page) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('pages.dashboard', $page->slug);
    }

    /**
     * Redirect to edit or show create view for Hero Section of a specific page.
     */
    public function getHeroSection(Page $page)
    {
        $hero = HeroSection::with(['sliderImages', 'ctaButtons'])->where('page_id', $page->id)->first();

        if ($hero) {
            return redirect()->route('pages.herosection.edit', [$page->slug, $hero->id]);
        }

        $pages = Page::select('id', 'slug')->orderBy('id')->get();
        return view('pages.homepage.createherosection', compact('page', 'pages'));
    }

    /**
     * Store a new Hero Section for a specific page.
     */
    public function store(StoreHeroSectionRequest $request, Page $page)
    {
        $uploadedFiles = [];
        if ($request->file('slider_images')) {
            $uploadedFiles = $request->file('slider_images');
        }
        if ($request->hasFile('hero_video')) {
            $uploadedFiles['hero_video'] = $request->file('hero_video');
        }

        $this->heroService->storeHeroSection(
            $page,
            $request->validated(),
            $uploadedFiles
        );

        return redirect()->route('pages.herosection', $page->slug)
            ->with('success', 'Hero section created successfully.');
    }

    /**
     * Show edit form for Hero Section.
     */
    public function edit(Page $page, HeroSection $hero)
    {
        $hero->load(['sliderImages', 'ctaButtons']);
        $pages = Page::select('id', 'slug')->orderBy('slug')->get();

        return view('pages.homepage.createherosection', compact('page', 'hero', 'pages'));
    }

    /**
     * Update an existing Hero Section.
     */
    public function update(StoreHeroSectionRequest $request, Page $page, HeroSection $hero)
    {
        $uploadedFiles = [];
        if ($request->file('slider_images')) {
            $uploadedFiles = $request->file('slider_images');
        }
        if ($request->hasFile('hero_video')) {
            $uploadedFiles['hero_video'] = $request->file('hero_video');
        }

        $this->heroService->updateHeroSection(
            $hero,
            $request->validated(),
            $uploadedFiles
        );

        return redirect()->route('pages.herosection', $page->slug)
            ->with('success', 'Hero section updated successfully.');
    }
}
