<?php

namespace App\Http\Controllers;
use App\Models\ExperienceActivity;
use App\Models\ActivityPackageCategory;
use Illuminate\Http\Request;
use App\Http\Requests\StoreExperienceActivityRequest;
use App\Http\Requests\UpdateExperienceActivityRequest;
use Illuminate\Support\Facades\DB;
use App\Services\ExperienceActivityService;
use Illuminate\Support\Facades\Storage;


class ExperienceActivityController extends Controller
{
    protected $service;

    public function __construct(ExperienceActivityService $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        $activities = ExperienceActivity::orderBy('created_at', 'desc')->get();
        return view('experience&Activities.index', compact('activities'));
    }

    public function create()
    {
        $featuredCount = ExperienceActivity::where('is_featured', 1)->count();
        $categories = ActivityPackageCategory::orderBy('name')->get();
        return view('experience&Activities.create', ['activity' => null, 'featuredCount' => $featuredCount, 'categories' => $categories]);
    }

    public function store(StoreExperienceActivityRequest $request)
    {
        $validated = $request->validated();
        $isFeatured = $request->has('is_featured') ? 1 : 0;
        $featuredCount = ExperienceActivity::where('is_featured', 1)->count();
        if ($isFeatured && !$this->service->canFeature($featuredCount, false)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Maximum 6 experiences/activities can be featured at a time.');
        }

        DB::beginTransaction();
        try {
            $this->service->create($validated, $request);
            DB::commit();
            return redirect()->route('experience-activities.index')->with('success', 'Activity/Experience created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create: ' . $e->getMessage());
        }
    }

    public function edit(ExperienceActivity $experienceActivity)
    {
        $featuredCount = ExperienceActivity::where('is_featured', 1)->count();
        $categories = ActivityPackageCategory::orderBy('name')->get();
        return view('experience&Activities.create', ['activity' => $experienceActivity, 'featuredCount' => $featuredCount, 'categories' => $categories]);
    }

    public function update(UpdateExperienceActivityRequest $request, ExperienceActivity $experienceActivity)
    {
        $validated = $request->validated();
        $isFeatured = $request->has('is_featured') ? 1 : 0;
        $featuredCount = ExperienceActivity::where('is_featured', 1)->count();
        if ($isFeatured && !$experienceActivity->is_featured && !$this->service->canFeature($featuredCount, false)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Maximum 6 experiences/activities can be featured at a time.');
        }

        DB::beginTransaction();
        try {
            $this->service->update($experienceActivity, $validated, $request);
            DB::commit();
            return redirect()->route('experience-activities.index')->with('success', 'Activity/Experience updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update: ' . $e->getMessage());
        }
    }

    public function destroy(ExperienceActivity $experienceActivity)
    {
        if ($experienceActivity->featured_image) {
            Storage::disk('public')->delete($experienceActivity->featured_image);
        }

        if ($experienceActivity->activitygallery) {
            $photos = $experienceActivity->activitygallery->photos ?? [];
            foreach ($photos as $photo) {
                $diskPath = ltrim(str_replace('storage/', '', $photo), '/');
                if (Storage::disk('public')->exists($diskPath)) {
                    Storage::disk('public')->delete($diskPath);
                }
            }
            $experienceActivity->activitygallery->delete();
        }

        $experienceActivity->delete();

        return redirect()->route('experience-activities.index')->with('success', 'Deleted successfully.');
    }
}