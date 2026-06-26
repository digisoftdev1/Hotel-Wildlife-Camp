<?php

namespace App\Services;

use App\Models\HeroCtaButton;
use App\Models\HeroSection;
use App\Models\HeroSliderImage;
use App\Models\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class HeroSectionService
{
    public function storeHeroSection(Page $page, array $data, array $uploadedFiles): HeroSection
    {
        return DB::transaction(function () use ($page, $data, $uploadedFiles) {

            $videoPath = null;
            if (($data['media_type'] ?? 'images') === 'video' && isset($uploadedFiles['hero_video'])) {
                $videoPath = $uploadedFiles['hero_video']->store('homepage/videos', 'public');
            }

            $hero = HeroSection::create([
                'page_id'       => $page->id,
                'section_title' => $data['section_title'] ?? null,
                'heading'       => $data['heading'] ?? null,
                'description'   => $data['description'] ?? null,
                'media_type'    => $data['media_type'] ?? 'images',
                'video_path'    => $videoPath,
                'status'        => $data['status'],
                'created_by'    => auth()->id(),
                'updated_by'    => auth()->id(),
            ]);

            // Store slider images (only when media_type = images)
            if (($data['media_type'] ?? 'images') === 'images') {
                foreach ($data['slider_images'] ?? [] as $index => $slide) {
                    if (!empty($slide['delete'])) continue;

                    $file = $uploadedFiles[$index]['image'] ?? null;
                    if (!$file) continue;

                    $path = $file->store('homepage', 'public');

                    HeroSliderImage::create([
                        'hero_section_id' => $hero->id,
                        'image_path'      => $path,
                        'section_title'   => $slide['section_title'] ?? null,
                        'heading'         => $slide['heading'] ?? null,
                        'description'     => $slide['description'] ?? null,
                        'order'           => $index,
                    ]);
                }
            }

            // Store CTA buttons
            $this->syncCtaButtons($hero, $data['cta_buttons'] ?? []);

            return $hero;
        });
    }

    public function updateHeroSection(HeroSection $hero, array $data, array $uploadedFiles): HeroSection
    {
        return DB::transaction(function () use ($hero, $data, $uploadedFiles) {

            $videoPath = $hero->video_path;

            if (($data['media_type'] ?? 'images') === 'video') {
                if (isset($uploadedFiles['hero_video'])) {
                    // Delete old video
                    if ($hero->video_path) {
                        Storage::disk('public')->delete($hero->video_path);
                    }
                    $videoPath = $uploadedFiles['hero_video']->store('homepage/videos', 'public');
                }
                
                // Switched to video — delete old images if any
                foreach ($hero->sliderImages()->get() as $sliderImage) {
                    Storage::disk('public')->delete($sliderImage->image_path);
                    $sliderImage->delete();
                }
            } else {
                // Switched to images — delete old video if any
                if ($hero->video_path) {
                    Storage::disk('public')->delete($hero->video_path);
                    $videoPath = null;
                }
            }

            $hero->update([
                'section_title' => $data['section_title'] ?? null,
                'heading'       => $data['heading'] ?? null,
                'description'   => $data['description'] ?? null,
                'media_type'    => $data['media_type'] ?? 'images',
                'video_path'    => $videoPath,
                'status'        => $data['status'],
                'updated_by'    => auth()->id(),
            ]);

            // Update slider images (only when media_type = images)
            if (($data['media_type'] ?? 'images') === 'images') {
                foreach ($data['slider_images'] ?? [] as $index => $slide) {
                    if (!empty($slide['delete'])) {
                        if (!empty($slide['existing_path'])) {
                            $this->deleteImageByPath($hero, $slide['existing_path']);
                        }
                        continue;
                    }

                    $file = $uploadedFiles[$index]['image'] ?? null;
                    if ($file) {
                        $path = $file->store('homepage', 'public');

                        HeroSliderImage::create([
                            'hero_section_id' => $hero->id,
                            'image_path'      => $path,
                            'section_title'   => $slide['section_title'] ?? null,
                            'heading'         => $slide['heading'] ?? null,
                            'description'     => $slide['description'] ?? null,
                            'order'           => $index,
                        ]);
                    } elseif (!empty($slide['existing_path'])) {
                        $sliderImage = $hero->sliderImages()
                            ->where('image_path', $slide['existing_path'])
                            ->first();

                        if ($sliderImage) {
                            $sliderImage->update([
                                'section_title' => $slide['section_title'] ?? null,
                                'heading'       => $slide['heading'] ?? null,
                                'description'   => $slide['description'] ?? null,
                                'order'         => $index,
                            ]);
                        }
                    }
                }
            }

            // Sync CTA buttons
            $this->syncCtaButtons($hero, $data['cta_buttons'] ?? []);

            return $hero;
        });
    }

    protected function syncCtaButtons(HeroSection $hero, array $buttons): void
    {
        $hero->ctaButtons()->delete();

        foreach (array_slice($buttons, 0, 2) as $index => $btn) {
            if (empty($btn['button_name']) || empty($btn['page_id'])) continue;

            HeroCtaButton::create([
                'hero_section_id' => $hero->id,
                'button_name'     => $btn['button_name'],
                'page_id'         => $btn['page_id'],
                'order'           => $index,
            ]);
        }
    }

    protected function deleteImageByPath(HeroSection $hero, string $path): void
    {
        $sliderImage = $hero->sliderImages()->where('image_path', $path)->first();
        if ($sliderImage) {
            Storage::disk('public')->delete($sliderImage->image_path);
            $sliderImage->delete();
        }
    }
}
