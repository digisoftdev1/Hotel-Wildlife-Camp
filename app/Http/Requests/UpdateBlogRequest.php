<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'blog_title'     => 'required|string|max:255',
            'blog_title_np'  => 'nullable|string|max:255',
            'content'        => 'required|string',
            'content_np'     => 'nullable|string',
            'excerpt'        => 'required|string|max:100',
            'excerpt_np'     => 'nullable|string|max:100',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status'         => 'required|in:draft,published',
            'is_featured'    => 'nullable|boolean',
            'category_id'    => 'required|exists:blog_categories,id',
            'keywords'       => 'nullable|array',
            'keywords.*'     => 'string|max:50',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'blog_title.required' => 'Title is required',
            'blog_title.max' => 'Title must not exceed 255 characters',
            'content.required' => 'Content is required',
            'excerpt.required' => 'Excerpt is required',
            'category_id.required' => 'Please select a category',
            'category_id.exists' => 'The selected category does not exist',

            'featured_image.image' => 'Feature photo must be an image file',
            'featured_image.mimes' => 'Feature photo must be a file of type: jpeg, png, jpg, gif, webp',
            'featured_image.max' => 'Feature photo size must not exceed 2MB',
            'keywords.*.string' => 'Each tag must be a string',
            'keywords.*.max' => 'Each tag must not exceed 50 characters',

        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {

        if ($this->has('is_featured')) {
            $this->merge([
                'is_featured' => true,
            ]);
        } else {
            $this->merge([
                'is_featured' => false,
            ]);
        }
    }
}