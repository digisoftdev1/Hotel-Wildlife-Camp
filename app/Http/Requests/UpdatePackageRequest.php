<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:activity_package_categories,id',
            'duration' => 'nullable|string|max:255',
            'grade' => 'nullable|string|max:255',
            'best_for' => 'nullable|string|max:255',
            'price' => 'nullable|numeric',
            'featured_image' => 'nullable|image|max:2048',
            'currency_id' => 'nullable|exists:currencies,id',
            'excerpt' => 'nullable|string',
            'overview' => 'nullable|string',
            'includes' => 'nullable|array',
            'itinerary_days' => 'nullable|array',
            'itinerary_desc' => 'nullable|array',
            'status' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
        ];
    }
}
