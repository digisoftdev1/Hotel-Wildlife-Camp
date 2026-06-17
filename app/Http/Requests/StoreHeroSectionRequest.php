<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHeroSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'        => 'required|in:draft,published',
            'section_title' => 'nullable|string|max:255',
            'heading'       => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'media_type'    => 'required|in:images,video',

            // Video rules (only one, max 50MB)
            'hero_video'    => 'nullable|file|mimetypes:video/mp4,video/webm,video/ogg,video/quicktime|max:51200',

            // Image slides (multiple, max 2MB each)
            'slider_images'             => 'nullable|array',
            'slider_images.*.image'     => 'nullable|image|max:2048',
            'slider_images.*.existing_path' => 'nullable|string',
            'slider_images.*.delete'    => 'nullable|string',
            'slider_images.*.section_title' => 'nullable|string|max:255',
            'slider_images.*.heading'   => 'nullable|string|max:255',
            'slider_images.*.description' => 'nullable|string',

            // CTA buttons (max 2)
            'cta_buttons'              => 'nullable|array|max:2',
            'cta_buttons.*.button_name' => 'required_with:cta_buttons.*.page_id|string|max:255',
            'cta_buttons.*.page_id'    => 'required_with:cta_buttons.*.button_name|exists:pages,id',
        ];
    }

    public function messages(): array
    {
        return [
            'hero_video.max'       => 'Video must not exceed 50 MB.',
            'hero_video.mimetypes' => 'Only MP4, WebM, OGG, or MOV video files are allowed.',
            'cta_buttons.max'      => 'You may add a maximum of 2 CTA buttons.',
        ];
    }
}
