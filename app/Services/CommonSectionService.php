<?php

namespace App\Services;

use App\Models\Page;
use App\Models\PageCommonSection;
use Illuminate\Support\Facades\Storage;

class CommonSectionService
{
    /**
     * Store a new common section.
     */
    public function store(Page $page, array $data, array $files, string $sectionType): PageCommonSection
    {
        // Use the section_type from the request (dropdown) if it's a new dynamic section
        $finalSectionType = $data['section_type'] ?? (in_array($sectionType, ['new', 'simple']) ? null : $sectionType);

        $maxOrder = PageCommonSection::where('page_id', $page->id)->max('order') ?? 0;

        $section = PageCommonSection::create([
            'page_id' => $page->id,
            'section_type' => $finalSectionType,
            'order' => $maxOrder + 1,
            'section_identifier' => $data['section_identifier'] ?? null,
            'section_title' => $data['section_title'] ?? null,
            'heading' => $data['heading'] ?? null,
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'display_fields' => isset($data['display_fields']) ? array_values(array_unique($data['display_fields'])) : null,
        ]);

        $this->syncCtaButtons($section, $data['cta_buttons'] ?? []);
        $this->syncSectionImages($section, $data['section_images'] ?? [], $files['section_images'] ?? []);

        return $section;
    }

    /**
     * Update an existing common section.
     */
    public function update(PageCommonSection $section, array $data, array $files): void
    {
        $section->update([
            'section_type' => $data['section_type'] ?? $section->section_type,
            'section_identifier' => $data['section_identifier'] ?? $section->section_identifier,
            'section_title' => $data['section_title'] ?? $section->section_title,
            'heading' => $data['heading'] ?? $section->heading,
            'description' => $data['description'] ?? $section->description,
            'status' => $data['status'] ?? $section->status,
            'updated_by' => auth()->id(),
            'display_fields' => isset($data['display_fields']) ? array_values(array_unique($data['display_fields'])) : null,
        ]);

        $this->syncCtaButtons($section, $data['cta_buttons'] ?? []);
        $this->syncSectionImages($section, $data['section_images'] ?? [], $files['section_images'] ?? []);
    }

    /**
     * Sync CTA buttons for a section.
     */
    public function syncCtaButtons(PageCommonSection $section, array $buttons): void
    {
        $section->ctaButtons()->delete();

        foreach (array_slice($buttons, 0, 2) as $index => $btn) {
            if (empty($btn['button_name']) || empty($btn['page_id'])) continue;

            $section->ctaButtons()->create([
                'button_name' => $btn['button_name'],
                'page_id' => $btn['page_id'],
                'order' => $index,
            ]);
        }
    }

    /**
     * Sync images for a section.
     */
    public function syncSectionImages(PageCommonSection $section, array $imagesData, array $uploadedFiles): void
    {
        foreach ($imagesData as $index => $data) {
            // Handle deletion
            if (!empty($data['delete'])) {
                if (!empty($data['existing_path'])) {
                    $image = $section->images()->where('image_path', $data['existing_path'])->first();
                    if ($image) {
                        Storage::disk('public')->delete($image->image_path);
                        $image->delete();
                    }
                }
                continue;
            }

            $file = $uploadedFiles[$index]['image'] ?? null;

            if ($file) {
                // New file upload
                $path = $file->store('sections', 'public');

                // If updating existing image record
                if (!empty($data['existing_path'])) {
                    $image = $section->images()->where('image_path', $data['existing_path'])->first();
                    if ($image) {
                        Storage::disk('public')->delete($image->image_path);
                        $image->update([
                            'image_path' => $path,
                            'alt_text' => $data['alt_text'] ?? null,
                            'order' => $index,
                        ]);
                        continue;
                    }
                }

                // Create new image record
                $section->images()->create([
                    'image_path' => $path,
                    'alt_text' => $data['alt_text'] ?? null,
                    'order' => $index,
                ]);
            } elseif (!empty($data['existing_path'])) {
                // Update only metadata for existing image
                $image = $section->images()->where('image_path', $data['existing_path'])->first();
                if ($image) {
                    $image->update([
                        'alt_text' => $data['alt_text'] ?? null,
                        'order' => $index,
                    ]);
                }
            }
        }
    }
}

