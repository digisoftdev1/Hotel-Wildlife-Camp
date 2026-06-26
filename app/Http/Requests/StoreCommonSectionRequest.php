<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommonSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'section_identifier' => ['nullable', 'string', 'max:255'],
            'section_title' => ['nullable', 'string', 'max:255'],
            'heading' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published'],
            'section_type' => ['nullable', 'string', 'max:100'],

            // CTA Buttons
            'cta_buttons' => ['nullable', 'array', 'max:2'],
            'cta_buttons.*.button_name' => ['required', 'string', 'max:50'],
            'cta_buttons.*.page_id' => ['required', 'exists:pages,id'],

            // Multiple Section Images
            'section_images' => ['nullable', 'array', 'max:3'],
            'section_images.*.image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'section_images.*.alt_text' => ['nullable', 'string', 'max:255'],
            'section_images.*.existing_path' => ['nullable', 'string'],
            'section_images.*.delete' => ['nullable', 'boolean'],

            // Section Items and Display Fields
            'item_ids' => ['nullable', 'array'],
            'item_ids.*' => ['nullable'],
            'display_fields' => ['nullable', 'array'],
            'display_fields.*' => ['nullable', 'string', 'max:100'],

            //about section fields
             'established_year' => 'nullable|integer|min:1800|max:' . date('Y'),
        'established_description' => 'nullable|string|max:1000',
        'location' => 'nullable|string|max:255',
        'location_description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Reject the request when all three heading fields are blank.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $hasHeading = !empty(trim($this->section_title ?? ''))
                || !empty(trim($this->heading ?? ''))
                || !empty(trim($this->sub_heading ?? ''));

            if (!$hasHeading) {
                $validator->errors()->add(
                    'section_title',
                    'Please fill in at least one of: Section Title, Heading, or Sub Heading.'
                );
            }
        });
    }
}