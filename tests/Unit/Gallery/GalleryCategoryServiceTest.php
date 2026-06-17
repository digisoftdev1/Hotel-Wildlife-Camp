<?php

use App\Models\GalleryCategory;
use App\Services\GalleryCategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('generates a unique slug when creating categories with the same name', function () {
    $service = app(GalleryCategoryService::class);

    $first = $service->createCategory([
        'name' => 'Nature Gallery',
        'name_np' => 'नेचर ग्यालरी',
        'description' => 'First category',
        'description_np' => 'पहिलो वर्ग',
    ], UploadedFile::fake()->image('nature-1.jpg'));

    $second = $service->createCategory([
        'name' => 'Nature Gallery',
        'description' => 'Second category',
    ], UploadedFile::fake()->image('nature-2.jpg'));

    expect($first->slug)->toBe('nature-gallery')
        ->and($first->name_np)->toBe('नेचर ग्यालरी')
        ->and($first->description_np)->toBe('पहिलो वर्ग')
        ->and($second->slug)->toBe('nature-gallery-1');
});

it('keeps the same slug when updating with the same name and replaces the featured image', function () {
    Storage::fake('public');

    $service = app(GalleryCategoryService::class);

    $category = $service->createCategory([
        'name' => 'Travel Gallery',
        'description' => 'Travel photos',
    ], UploadedFile::fake()->image('travel-old.jpg'));

    $oldPath = $category->image;
    Storage::disk('public')->assertExists($oldPath);

    $updated = $service->updateCategory($category, [
        'name' => 'Travel Gallery',
        'name_np' => 'यात्रा ग्यालरी',
        'description' => 'Updated travel photos',
        'description_np' => 'अपडेट गरिएको यात्रा फोटो',
    ], UploadedFile::fake()->image('travel-new.png'));

    expect($updated->slug)->toBe('travel-gallery')
        ->and($updated->name_np)->toBe('यात्रा ग्यालरी')
        ->and($updated->description)->toBe('Updated travel photos')
        ->and($updated->description_np)->toBe('अपडेट गरिएको यात्रा फोटो')
        ->and($updated->image)->not->toBe($oldPath);

    Storage::disk('public')->assertMissing($oldPath);
    Storage::disk('public')->assertExists($updated->image);
});

it('deletes the category folder when removing a gallery category', function () {
    Storage::fake('public');

    $service = app(GalleryCategoryService::class);

    $category = $service->createCategory([
        'name' => 'Delete Gallery',
        'description' => 'To be removed',
    ], UploadedFile::fake()->image('delete.jpg'));

    $folderPath = "gallery/{$category->id}";
    Storage::disk('public')->put("{$folderPath}/images/example.jpg", 'image-data');

    $service->deleteCategory($category);

    expect(GalleryCategory::find($category->id))->toBeNull();
    Storage::disk('public')->assertMissing($folderPath . '/featured_image/' . basename($category->image));
    Storage::disk('public')->assertMissing("{$folderPath}/images/example.jpg");
});