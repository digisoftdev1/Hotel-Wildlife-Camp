<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
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
            'room_name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:rooms,slug'],
            'headline' => ['nullable', 'string', 'max:100'],
            'occupancy' => ['required', 'integer', 'min:1'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'room_size' => ['nullable', 'integer', 'min:0'],
            'excerpt' => ['required', 'string'],
            'description' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:draft,published,archived'],
            
            // Related data
            
            'amenities' => ['nullable', 'array'],
            'amenities.*.name' => ['required', 'string'],
            'amenities.*.icon' => ['required', 'string'],
            
            'beds' => ['nullable', 'array'],
            'beds.*.type' => ['required', 'string'],
            'beds.*.quantity' => ['required', 'integer', 'min:1'],
            
            'special_features' => ['nullable', 'array'],
            'special_features.*' => ['required', 'string'],
        ];
    }
}
