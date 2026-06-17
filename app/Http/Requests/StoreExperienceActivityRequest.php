<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExperienceActivityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'excerpt' => 'required|string|max:500',
            'duration' => 'required|string|max:50',
            'difficulty_level' => 'required|string|max:50',
            'overview' => 'required|string',
            'category_id' => 'nullable|exists:activity_package_categories,id',
            'highlights' => 'nullable|array',
            'highlights.*' => 'nullable|string',
            'featured_image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable|boolean',
            'best_time' => 'nullable|string|max:100',
        ];
    }
}
