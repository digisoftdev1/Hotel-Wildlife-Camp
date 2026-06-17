<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\ActivityPackageCategory;
use App\Models\Currency;
use App\Http\Requests\StorePackageRequest;
use App\Http\Requests\UpdatePackageRequest;
use App\Services\PackageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    protected $service;

    public function __construct(PackageService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $packages = Package::with(['category', 'currency'])->orderBy('created_at', 'desc')->get();
        return view('packages.index', compact('packages'));
    }

    public function create()
    {
        $categories = ActivityPackageCategory::orderBy('name')->get();
        $currencies = Currency::orderBy('name')->get();
        return view('packages.create', compact('categories', 'currencies'));
    }

    public function store(StorePackageRequest $request)
    {
        $validated = $request->validated();
        
        DB::beginTransaction();
        try {
            $this->service->create($validated, $request);
            DB::commit();
            return redirect()->route('packages.index')->with('success', 'Package created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create: ' . $e->getMessage());
        }
    }

    public function edit(Package $package)
    {
        $categories = ActivityPackageCategory::orderBy('name')->get();
        $currencies = Currency::orderBy('name')->get();
        return view('packages.create', [
            'package' => $package,
            'categories' => $categories,
            'currencies' => $currencies
        ]);
    }

    public function update(UpdatePackageRequest $request, Package $package)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $this->service->update($package, $validated, $request);
            DB::commit();
            return redirect()->route('packages.index')->with('success', 'Package updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update: ' . $e->getMessage());
        }
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('packages.index')->with('success', 'Package deleted successfully.');
    }
}
