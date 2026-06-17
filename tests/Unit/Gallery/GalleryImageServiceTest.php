<?php

use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Services\GalleryImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('stores gallery images inside the category folder', function () {
    Storage::fake('public');

    $category = GalleryCategory::create([
        'name' => 'Portfolio',
        'slug' => 'portfolio',
        'description' => 'Portfolio category',
        'image' => null,
    ]);

    $service = app(GalleryImageService::class);
    $image = $service->createImage([
        'gallery_category_id' => $category->id,
        'name' => 'Hero Shot',
        'name_np' => 'नायक फोटो',
        'description' => 'Main photo',
        'description_np' => 'मुख्य फोटो',
    ], UploadedFile::fake()->image('hero.jpg'));

    expect($image->image)->toStartWith("gallery/{$category->id}/images/")
        ->and($image->name_np)->toBe('नायक फोटो')
        ->and($image->description_np)->toBe('मुख्य फोटो');
    Storage::disk('public')->assertExists($image->image);
});

it('replaces the old image file when updating a gallery image', function () {
    Storage::fake('public');

    $category = GalleryCategory::create([
        'name' => 'Events',
        'slug' => 'events',
        'description' => null,
        'image' => null,
    ]);

    $image = GalleryImage::create([
        'gallery_category_id' => $category->id,
        'name' => 'Old Name',
        'description' => 'Old description',
        'image' => "gallery/{$category->id}/images/old.jpg",
    ]);

    Storage::disk('public')->put($image->image, 'old-image');

    $service = app(GalleryImageService::class);
    $updated = $service->updateImage($image, [
        'name' => 'New Name',
        'name_np' => 'नयाँ नाम',
        'description' => 'New description',
        'description_np' => 'नयाँ विवरण',
    ], UploadedFile::fake()->image('new.png'));

    expect($updated->name)->toBe('New Name')
        ->and($updated->name_np)->toBe('नयाँ नाम')
        ->and($updated->description_np)->toBe('नयाँ विवरण')
        ->and($updated->image)->toStartWith("gallery/{$category->id}/images/");

    Storage::disk('public')->assertMissing("gallery/{$category->id}/images/old.jpg");
    Storage::disk('public')->assertExists($updated->image);
});

it('deletes the stored file when deleting a gallery image', function () {
    Storage::fake('public');

    $category = GalleryCategory::create([
        'name' => 'Products',
        'slug' => 'products',
        'description' => null,
        'image' => null,
    ]);

    $image = GalleryImage::create([
        'gallery_category_id' => $category->id,
        'name' => 'Product Image',
        'description' => null,
        'image' => "gallery/{$category->id}/images/product.jpg",
    ]);

    Storage::disk('public')->put($image->image, 'product-image');

    $service = app(GalleryImageService::class);
    $service->deleteImage($image);

    expect(GalleryImage::find($image->id))->toBeNull();
    Storage::disk('public')->assertMissing($image->image);
});