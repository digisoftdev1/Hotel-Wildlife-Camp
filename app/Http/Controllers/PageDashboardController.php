<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageCommonSection;
use Illuminate\Http\Request;

class PageDashboardController extends Controller
{
    /**
     * Show the dashboard for a specific page.
     */
    public function index(Page $page)
    {
        $heroSection = \App\Models\HeroSection::where('page_id', $page->id)->first();
        $commonSections = PageCommonSection::where('page_id', $page->id)->get();

        $allSections = collect();

        // Always include Hero Section at the top
        $allSections->push([
            'id' => $heroSection->id ?? 0,
            'type' => 'hero',
            'label' => 'Hero Section',
            'order' => -1, // Keep it at the very top
            'status' => $heroSection->status ?? 'draft',
            'edit_url' => route('pages.herosection', $page->slug),
        ]);

        // Add Common Sections
        foreach ($commonSections as $section) {
            $allSections->push([
                'id' => $section->id,
                'type' => 'common',
                'section_type' => $section->section_type,
                'slug' => $section->slug,
                'label' => $section->section_identifier ?: ($section->section_type ? ucfirst(str_replace('-', ' ', $section->section_type)) : 'Simple Section'),
                'order' => $section->order,
                'status' => $section->status,
                'edit_url' => route('pages.section.edit', [$page->slug, $section->slug]),
            ]);
        }

        // Sort by order
        $availableSections = $allSections->sortBy('order')->values();

        return view('pages.page_dashboard', [
            'page' => $page,
            'availableSections' => $availableSections,
        ]);
    }

    /**
     * Update the order of sections.
     */
    public function updateOrder(Request $request, Page $page)
    {
        $orders = $request->input('orders');

        foreach ($orders as $item) {
            if ($item['type'] === 'hero') {
                \App\Models\HeroSection::where('id', $item['id'])->update(['order' => $item['order']]);
            } else {
                PageCommonSection::where('id', $item['id'])->update(['order' => $item['order']]);
            }
        }

        return response()->json(['success' => true]);
    }
}
