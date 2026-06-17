<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_category_id' => ['required', 'exists:branch_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'leader_name' => ['nullable', 'string', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'province_id' => ['required', 'exists:provinces,id'],
            'district_id' => [
                'required',
                Rule::exists('districts', 'id')->where(fn ($query) => $query->where('province_id', $this->input('province_id'))),
            ],
            'location' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'parent_id' => ['nullable', 'exists:branches,id'],
        ];
    }
}
