<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageFaq;
use Illuminate\Http\Request;

class PackageFaqController extends Controller
{
    public function index()
    {
        $packages = Package::with('faqs')->orderBy('created_at', 'desc')->get();
        return view('packages.faq.index', compact('packages'));
    }

    public function store(Request $request, Package $package)
    {
        $request->validate([
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'required_with:faqs|string',
            'faqs.*.answer' => 'required_with:faqs|string',
        ]);

        try {
            $faq = $package->faqs ?? new PackageFaq(['package_id' => $package->id]);
            $faq->faqs = $request->input('faqs', []);
            $faq->save();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'FAQs updated successfully.']);
            }

            return back()->with('success', 'FAQs updated successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Failed to update FAQs: ' . $e->getMessage()], 422);
            }
            return back()->with('error', 'Failed to update FAQs: ' . $e->getMessage());
        }
    }
}
