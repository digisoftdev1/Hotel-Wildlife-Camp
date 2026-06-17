<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phones' => ['required', 'array', 'min:1'],
            'phones.*' => ['required', 'string', 'max:20'],
            'emails' => ['required', 'array', 'min:1'],
            'emails.*' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'map_url' => ['nullable', 'url', 'max:2000'],
            'business_hours' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published'],
        ];
    }
}
