<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommonSectionRequest;
use App\Models\Page;
use App\Models\PageCommonSection;
use App\Services\CommonSectionService;
use Illuminate\Http\Request;

class PageSectionController extends Controller
{
    protected $sectionService;

    public function __construct(CommonSectionService $sectionService)
    {
        $this->sectionService = $sectionService;
    }

    /**
     * Check if the section type is valid.
     */
    protected function validateSectionType(string $sectionType): void
    {
        // Removed strict validation to allow dynamic sections
    }

    /**
     * Get the label for a section type.
     */
    protected function getSectionLabel(?string $sectionType): string
    {
        if (!$sectionType || in_array($sectionType, ['new', 'simple'])) {
            return 'Section';
        }
        return ucfirst(str_replace('-', ' ', $sectionType));
    }

    /**
     * Show or redirect to the section management.
     */
    public function getSection(Page $page, string $sectionType)
    {
        $this->validateSectionType($sectionType);

        // If it's a specific dynamic section type, check if it already exists
        if ($sectionType !== 'new' && $sectionType !== 'simple') {
            $section = PageCommonSection::where('page_id', $page->id)
                ->where('section_type', $sectionType)
                ->first();

            if ($section) {
                return redirect()->route('pages.section.edit', [$page->slug, $section->slug]);
            }
        }

        $sectionLabel = $this->getSectionLabel($sectionType);
        $pages = Page::select('id', 'slug')->orderBy('slug')->get();
        $contentMeta = $this->getContentMeta();

        return view('pages.createcommonsection', compact('page', 'sectionType', 'sectionLabel', 'pages', 'contentMeta'));
    }

    /**
     * Store a new common section.
     */
    public function storeSection(StoreCommonSectionRequest $request, Page $page, string $sectionType)
    {

        $this->validateSectionType($sectionType);

        $section = $this->sectionService->store(
            $page,
            $request->validated(),
            ['section_images' => $request->file('section_images') ?? []],
            $sectionType === 'new' ? 'simple' : $sectionType
        );

        return redirect()->route('pages.dashboard', $page->slug)
            ->with('success', ($section->section_identifier ?: $this->getSectionLabel($section->section_type)) . ' created successfully.');
    }

    /**
     * Show edit form for a section.
     */
    public function editSection(Page $page, PageCommonSection $section)
    {
        $section->load(['ctaButtons', 'images']);
        $sectionType = $section->section_type ?: 'simple';
        $sectionLabel = $section->section_identifier ?: $this->getSectionLabel($sectionType);
        $pages = Page::select('id', 'slug')->orderBy('slug')->get();
        $contentMeta = $this->getContentMeta();

        return view(
            'pages.createcommonsection',
            compact('page', 'section', 'sectionType', 'sectionLabel', 'pages', 'contentMeta')
        );
    }

    /**
     * Update an existing common section.
     */
    public function updateSection(StoreCommonSectionRequest $request, Page $page, PageCommonSection $section)
    {
        $this->sectionService->update(
            $section,
            $request->validated(),
            ['section_images' => $request->file('section_images') ?? []]
        );

        $sectionType = $section->section_type ?: 'simple';

        return redirect()->route('pages.dashboard', $page->slug)
            ->with('success', ($section->section_identifier ?: $this->getSectionLabel($sectionType)) . ' updated successfully.');
    }

    /**
     * Get metadata about dynamic content types.
     */
    private function getContentMeta(): array
    {
        return [
            'accommodation' => [
                'count' => \App\Models\Room::count(),
                'create_url' => route('rooms.create'),
                'label' => 'Rooms',
                'field_stats' => [
                    'featured_image' => \App\Models\Room::whereNotNull('featured_image')->exists(),
                    'room_name' => \App\Models\Room::whereNotNull('room_name')->exists(),
                    'headline' => \App\Models\Room::whereNotNull('headline')->exists(),
                    'occupancy' => \App\Models\Room::whereNotNull('occupancy')->exists(),
                    'room_size' => \App\Models\Room::whereNotNull('room_size')->exists(),
                    'price' => \App\Models\Room::whereNotNull('price')->exists(),
                    'currency' => \App\Models\Room::whereNotNull('currency_id')->exists(),
                    'excerpt' => \App\Models\Room::whereNotNull('excerpt')->exists(),
                    'description' => \App\Models\Room::whereNotNull('description')->exists(),
                    'amenities' => \Illuminate\Support\Facades\DB::table('room_amenity_pivots')->exists(),
                    'beds' => \App\Models\RoomBed::exists(),
                    'special_features' => \App\Models\RoomSpecialFeature::exists(),
                    'gallery' => \App\Models\RoomGallery::exists(),
                ]
            ],
            'services' => [
                'count' => \App\Models\Service::count(),
                'create_url' => route('services.index'),
                'label' => 'Services',
                'field_stats' => [
                    'icon' => \App\Models\Service::whereNotNull('icon')->exists(),
                    'service_name' => \App\Models\Service::whereNotNull('service_name')->exists(),
                    'description' => \App\Models\Service::whereNotNull('description')->exists(),
                ]
            ],
            'testimonials' => [
                'count' => \App\Models\Testimonial::count(),
                'create_url' => route('testimonials.index'),
                'label' => 'Testimonials',
                'field_stats' => [
                    'name' => \App\Models\Testimonial::whereNotNull('name')->exists(),
                    'platform' => \App\Models\Testimonial::whereNotNull('platform')->exists(),
                    'testimonial' => \App\Models\Testimonial::whereNotNull('testimonial')->exists(),
                ]
            ],
            'featured_blogs' => [
                'count' => \App\Models\Blog::count(),
                'create_url' => route('blogs.create'),
                'label' => 'Blogs',
                'field_stats' => [
                    'featured_image' => \App\Models\Blog::whereNotNull('featured_image')->exists(),
                    'blog_title' => \App\Models\Blog::whereNotNull('blog_title')->exists(),
                    'excerpt' => \App\Models\Blog::whereNotNull('excerpt')->exists(),
                    'category' => \App\Models\Blog::whereNotNull('category_id')->exists(),
                    'keywords' => \App\Models\Blog::whereNotNull('keywords')->where('keywords', '!=', 'null')->where('keywords', '!=', '[]')->exists(),
                    'read_time' => \App\Models\Blog::whereNotNull('read_time')->exists(),
                    'created_at' => \App\Models\Blog::exists(),
                ]
            ],
            'featured_activities' => [
                'count' => \App\Models\ExperienceActivity::count(),
                'create_url' => route('experience-activities.create'),
                'label' => 'Activities',
                'field_stats' => [
                    'featured_image' => \App\Models\ExperienceActivity::whereNotNull('featured_image')->exists(),
                    'name' => \App\Models\ExperienceActivity::whereNotNull('name')->exists(),
                    'excerpt' => \App\Models\ExperienceActivity::whereNotNull('excerpt')->exists(),
                    'duration' => \App\Models\ExperienceActivity::whereNotNull('duration')->exists(),
                    'difficulty_level' => \App\Models\ExperienceActivity::whereNotNull('difficulty_level')->exists(),
                    'best_time' => \App\Models\ExperienceActivity::whereNotNull('best_time')->exists(),
                    'category' => \App\Models\ExperienceActivity::whereNotNull('category_id')->exists(),
                ]
            ],
            'featured_packages' => [
                'count' => \App\Models\Package::count(),
                'create_url' => route('packages.create'),
                'label' => 'Packages',
                'field_stats' => [
                    'featured_image' => \App\Models\Package::whereNotNull('featured_image')->exists(),
                    'name' => \App\Models\Package::whereNotNull('name')->exists(),
                    'duration' => \App\Models\Package::whereNotNull('duration')->exists(),
                    'grade' => \App\Models\Package::whereNotNull('grade')->exists(),
                    'best_for' => \App\Models\Package::whereNotNull('best_for')->exists(),
                    'price' => \App\Models\Package::whereNotNull('price')->exists(),
                    'currency' => \App\Models\Package::whereNotNull('currency_id')->exists(),
                    'excerpt' => \App\Models\Package::whereNotNull('excerpt')->exists(),
                    'category' => \App\Models\Package::whereNotNull('category_id')->exists(),
                    'gallery' => \App\Models\PackageGallery::exists(),
                ]
            ],
            'gallery' => [
                'count' => \App\Models\GalleryCategory::count(),
                'create_url' => route('gallery-categories.index'),
                'label' => 'Gallery Categories',
                'field_stats' => [
                    'name' => \App\Models\GalleryCategory::whereNotNull('name')->exists(),
                    'image' => \App\Models\GalleryCategory::whereNotNull('image')->exists(),
                    'images' => \App\Models\GalleryImage::exists(),
                ]
            ],
            'contact' => [
                'count' => \App\Models\Contact::count(),
                'create_url' => route('contactpage.index'),
                'label' => 'Contact Info',
                'field_stats' => [
                    'phones' => \App\Models\Contact::whereNotNull('phones')->exists(),
                    'emails' => \App\Models\Contact::whereNotNull('emails')->exists(),
                    'address' => \App\Models\Contact::whereNotNull('address')->exists(),
                    'map_url' => \App\Models\Contact::whereNotNull('map_url')->exists(),
                    'business_hours' => \App\Models\Contact::whereNotNull('business_hours')->exists(),
                ]
            ],
        ];
    }
}