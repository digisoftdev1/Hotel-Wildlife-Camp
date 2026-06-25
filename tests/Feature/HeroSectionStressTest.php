<?php

/**
 * @property \App\Services\HeroSectionService $heroService
 * @property \App\Models\User $user
 * @property \App\Models\Page $page
 */

use App\Models\HeroSection;
use App\Models\Page;
use App\Models\User;
use App\Services\HeroSectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    
    $this->heroService = app(HeroSectionService::class);
    
    $this->user = User::factory()->create(['username' => 'stresstester']);
    $this->actingAs($this->user);
    
    $this->page = Page::create([
        'name' => 'Stress Test Page',
        'slug' => 'stress-test-page',
    ]);
});

test('create and update hero section with multiple images', function () {
    $data = [
        'section_title' => 'Welcome',
        'heading' => 'Welcome to our site',
        'description' => 'This is a test',
        'media_type' => 'images',
        'status' => 'draft',
        'slider_images' => [
            [
                'section_title' => 'Slide 1',
                'heading' => 'Welcome',
                'description' => 'First slide',
            ],
        ],
        'cta_buttons' => [
            [
                'button_name' => 'Learn More',
                'page_id'     => $this->page->id,
            ]
        ],
    ];

    $uploadedFiles = [
        0 => [
            'image' => UploadedFile::fake()->image('slide1.jpg', 1920, 1080),
        ],
    ];

    $hero = $this->heroService->storeHeroSection($this->page, $data, $uploadedFiles);

    expect($hero)
        ->toBeInstanceOf(HeroSection::class)
        ->media_type->toBe('images');

    // Verify images are stored
    expect(Storage::disk('public')->exists($hero->sliderImages->first()->image_path))->toBeTrue();

    // Now update with a different image
    $updateData = [
        'section_title' => 'Welcome Updated',
        'heading' => 'Updated Welcome',
        'description' => 'This is updated',
        'media_type' => 'images',
        'status' => 'published',
        'slider_images' => [
            [
                'id' => $hero->sliderImages->first()->id,
                'section_title' => 'Slide 1 Updated',
                'heading' => 'Welcome Updated',
                'description' => 'First slide updated',
                'existing_path' => $hero->sliderImages->first()->image_path,
            ],
            [
                'section_title' => 'Slide 2',
                'heading' => 'Second Slide',
                'description' => 'New second slide',
            ],
        ],
        'cta_buttons' => [
            [
                'button_name' => 'Learn More Updated',
                'page_id'     => $this->page->id,
            ]
        ],
    ];

    $uploadedFiles = [
        1 => [
            'image' => UploadedFile::fake()->image('slide2.jpg', 1920, 1080),
        ],
    ];

    $updated = $this->heroService->updateHeroSection($hero, $updateData, $uploadedFiles);

    expect($updated)
        ->section_title->toBe('Welcome Updated')
        ->sliderImages()->count()->toBeGreaterThan(1);
});

test('create hero section with video', function () {
    $data = [
        'section_title' => 'Video Hero',
        'heading' => 'Video Welcome',
        'description' => 'Video description',
        'media_type' => 'video',
        'status' => 'draft',
        'cta_buttons' => [
            [
                'button_name' => 'Learn More',
                'page_id'     => $this->page->id,
            ]
        ],
    ];

    $uploadedFiles = [
        'hero_video' => UploadedFile::fake()->create('test-video.mp4', 1000, 'video/mp4')
    ];

    $hero = $this->heroService->storeHeroSection($this->page, $data, $uploadedFiles);

    expect($hero)
        ->toBeInstanceOf(HeroSection::class)
        ->media_type->toBe('video')
        ->video_path->not->toBeNull();
    
    expect(Storage::disk('public')->exists($hero->video_path))->toBeTrue();
});



it('can handle uploading a massive amount of slider images at once', function () {
    $slideCount = 50; 
    $sliderImagesData = [];
    $uploadedFiles = [];

    for ($i = 0; $i < $slideCount; $i++) {
        $sliderImagesData[$i] = [
            'section_title' => "Stress Slide $i",
            'heading'       => "Stress Heading $i",
            'description'   => "Stress Description $i",
        ];
        
        $uploadedFiles[$i] = [
            'image' => UploadedFile::fake()->image("stress_slide_{$i}.jpg")
        ];
    }

    $data = [
        'media_type'    => 'images',
        'status'        => 'published',
        'slider_images' => $sliderImagesData,
    ];

    // Measure time optionally, but mostly we check if it succeeds without memory limit/timeout
    $hero = $this->heroService->storeHeroSection($this->page, $data, $uploadedFiles);

    expect($hero)->toBeInstanceOf(HeroSection::class);
    expect($hero->sliderImages)->toHaveCount($slideCount);

    // Verify all 50 files exist on disk
    foreach ($hero->sliderImages as $slide) {
        expect(Storage::disk('public')->exists($slide->image_path))->toBeTrue();
    }
});

it('can handle repeatedly switching between video and images back and forth without leaking files', function () {
    // Initial state: Video
    $hero = $this->heroService->storeHeroSection($this->page, [
        'media_type' => 'video',
        'status' => 'published'
    ], [
        'hero_video' => UploadedFile::fake()->create('initial-video.mp4', 100, 'video/mp4')
    ]);

    // We will do 10 cycles of switching
    for ($cycle = 1; $cycle <= 10; $cycle++) {
        
        // 1. Switch to Images (Upload 5 images)
        $imagesData = [];
        $imageFiles = [];
        for ($i = 0; $i < 5; $i++) {
            $imagesData[$i] = ['section_title' => "Cycle $cycle Slide $i"];
            $imageFiles[$i] = ['image' => UploadedFile::fake()->image("cycle_{$cycle}_slide_{$i}.jpg")];
        }

        $hero = $this->heroService->updateHeroSection($hero, [
            'media_type' => 'images',
            'status' => 'published',
            'slider_images' => $imagesData
        ], $imageFiles);

        // Verify state is images and 5 slides exist
        expect($hero->fresh()->media_type)->toBe('images');
        expect($hero->fresh()->sliderImages)->toHaveCount(5);
        expect($hero->fresh()->video_path)->toBeNull();

        // 2. Switch back to Video
        $hero = $this->heroService->updateHeroSection($hero, [
            'media_type' => 'video',
            'status' => 'published'
        ], [
            'hero_video' => UploadedFile::fake()->create("cycle_{$cycle}_video.mp4", 100, 'video/mp4')
        ]);

        // Verify state is video and 0 slides exist
        expect($hero->fresh()->media_type)->toBe('video');
        expect($hero->fresh()->sliderImages)->toHaveCount(0);
        expect($hero->fresh()->video_path)->not->toBeNull();
    }

    // At the very end, there should be EXACTLY 1 file in the storage representing the final video
    // (Because all old videos and old images should have been deleted during the 10 cycles)
    $filesInStorage = Storage::disk('public')->allFiles('homepage');
    
    // There might be a directory structure, but we only expect exactly 1 video file remaining.
    // The path should be something like homepage/videos/cycle_10_video...
    expect($filesInStorage)->toHaveCount(1);
    expect($filesInStorage[0])->toContain('homepage/videos/');
});