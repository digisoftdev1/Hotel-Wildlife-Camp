<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $abouts = About::all();
        return view('about.aboutindex', compact('abouts'));
    }
    
    /**
     * Show the form for creating a new resource. 
     */
    public function create()
    {
       return view('about.createabout');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'about_title' => 'required|string|max:255',
        'description' => 'required|string',

        'image_path' => 'required|string|max:255',

        'established_year' => 'nullable|integer',
        'established_description' => 'nullable|string',

        'location' => 'nullable|string|max:255',
        'location_description' => 'nullable|string',

        'team_title' => 'nullable|string|max:255',
        'team_description' => 'nullable|string',
        'team_image' => 'nullable|string|max:255',

        'facilities_title' => 'nullable|string|max:255',
        'facilities' => 'nullable|array',
    ]);

    About::create($validated);

    return redirect()->route('abouts.index')->with('success', 'About section created successfully.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, About $about)
{
    $validated = $request->validate([
        'about_title' => 'nullable|string|max:255',
        'description' => 'nullable|string',

        'image_path' => 'nullable|string|max:255',

        'established_year' => 'nullable|integer',
        'established_description' => 'nullable|string',

        'location' => 'nullable|string|max:255',
        'location_description' => 'nullable|string',

        'team_title' => 'nullable|string|max:255',
        'team_description' => 'nullable|string',
        'team_image' => 'nullable|string|max:255',

        'facilities_title' => 'nullable|string|max:255',
        'facilities' => 'nullable|array',
    ]);

    $about->update($validated);

    return redirect()->back()->with('success', 'About updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(About $about)
{
    $about->delete();

    return redirect()->back()->with('success', 'About deleted successfully.');
}
}