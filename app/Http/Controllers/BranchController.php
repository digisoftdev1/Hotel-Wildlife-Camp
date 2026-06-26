<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBranchRequest;
use App\Models\Branch;
use App\Models\Province;
use App\Services\BranchService;

class BranchController extends Controller
{
    public function __construct(protected BranchService $branchService)
    {
    }

    public function index()
    {
        $filters = request()->only(['branch_category_id', 'province_id', 'district_id']);
        $branches = $this->branchService->paginateBranches($filters);
        
        $data = array_merge(
            ['branches' => $branches],
            $this->branchService->formData()
        );

        return view('branches.index', $data);
    }

    public function create()
    {
        return view('branches.create', $this->branchService->formData());
    }

    public function store(StoreBranchRequest $request)
    {
        $this->branchService->createBranch($request->validated());

        return redirect()
            ->route('branches.index')
            ->with('success', 'Branch created successfully.');
    }

    public function edit(Branch $branch)
    {
        return view('branches.edit', array_merge(
            $this->branchService->formData(),
            ['branch' => $branch]
        ));
    }

    public function update(StoreBranchRequest $request, Branch $branch)
    {
        $this->branchService->updateBranch($branch, $request->validated());

        return redirect()
            ->route('branches.index')
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        try {
            $this->branchService->deleteBranch($branch);

            return redirect()
                ->route('branches.index')
                ->with('success', 'Branch deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('branches.index')
                ->with('error', 'Unable to delete branch right now. Please try again.');
        }
    }

    public function districts(Province $province)
    {
        return response()->json([
            'data' => $this->branchService->districtsForProvince($province),
        ]);
    }

    public function mapStats()
    {
        $level = request('level', 'province');
        $parentId = request('parent_id');

        return response()->json([
            'data' => $this->branchService->getMapStats($level, $parentId),
        ]);
    }
}
