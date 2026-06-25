<?php

use App\Models\Page;
use App\Models\PageCommonSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->page = Page::factory()->create(['slug' => 'home']);
    Storage::fake('public');
});

test('user can create a common section with buttons and images', function () {
    $targetPage = Page::factory()->create(['slug' => 'about']);
    
    $response = $this->actingAs($this->user)
        ->post(route('pages.section.store', [$this->page->slug, 'new']), [
            'dynamic_section_type' => 'my-dynamic-section',
            'section_title' => 'My Title',
            'heading' => 'My Heading',
            'description' => 'My Description',
            'status' => 'published',
            'cta_buttons' => [
                ['button_name' => 'Click Me', 'page_id' => $targetPage->id]
            ],
            'section_images' => [
                [
                    'image' => UploadedFile::fake()->image('image1.jpg'),
                    'alt_text' => 'First Image'
                ]
            ]
        ]);

    $response->assertRedirect();
    
    $this->assertDatabaseHas('page_common_sections', [
        'page_id' => $this->page->id,
        'section_type' => 'my-dynamic-section',
        'section_title' => 'My Title'
    ]);

    $section = PageCommonSection::where('section_type', 'my-dynamic-section')->first();
    
    expect($section->ctaButtons)->toHaveCount(1)
        ->and($section->ctaButtons->first()->button_name)->toBe('Click Me');
        
    expect($section->images)->toHaveCount(1)
        ->and($section->images->first()->alt_text)->toBe('First Image');
        
    Storage::disk('public')->assertExists($section->images->first()->image_path);
});

test('user can update a section and delete images/buttons', function () {
    $section = PageCommonSection::factory()->create([
        'page_id' => $this->page->id,
        'section_type' => 'about'
    ]);
    
    $existingImage = \App\Models\CommonSectionImage::factory()->create([
        'common_section_id' => $section->id,
        'image_path' => 'sections/old.jpg'
    ]);
    
    $existingButton = \App\Models\CommonSectionCtaButton::factory()->create([
        'common_section_id' => $section->id,
        'button_name' => 'Old Button'
    ]);

    $response = $this->actingAs($this->user)
        ->put(route('pages.section.update', [$this->page->slug, 'about', $section->id]), [
            'section_title' => 'Updated Title',
            'status' => 'published',
            'cta_buttons' => [], // Remove all buttons
            'section_images' => [
                [
                    'existing_path' => $existingImage->image_path,
                    'delete' => '1',
                    'alt_text' => 'Should be deleted'
                ],
                [
                    'image' => UploadedFile::fake()->image('new.jpg'),
                    'alt_text' => 'New Image'
                ]
            ]
        ]);

    $response->assertRedirect();
    
    $this->assertDatabaseMissing('common_section_cta_buttons', ['id' => $existingButton->id]);
    $this->assertDatabaseMissing('common_section_images', ['id' => $existingImage->id]);
    Storage::disk('public')->assertMissing('sections/old.jpg');
    
    expect($section->fresh()->images)->toHaveCount(1)
        ->and($section->fresh()->images->first()->alt_text)->toBe('New Image');
});
