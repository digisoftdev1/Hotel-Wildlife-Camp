<?php

use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function makeGalleryAuthUser(): User
{
    return User::factory()->create([
        'username' => fake()->unique()->userName(),
    ]);
}

it('redirects guests from gallery index', function () {
    $response = $this->get(route('gallery-categories.index'));

    $response->assertRedirect(route('login'));
});

it('creates a gallery category with a featured image', function () {
    Storage::fake('public');

    $user = makeGalleryAuthUser();

    $response = $this->actingAs($user)->post(route('gallery-categories.store'), [
        'name' => 'Nature Gallery',
        'name_np' => 'नेचर ग्यालरी',
        'description' => 'Outdoor scenes',
        'description_np' => 'बाहिरी दृश्य',
        'image' => UploadedFile::fake()->image('nature.jpg'),
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Category created successfully',
        ]);

    $category = GalleryCategory::query()->where('name', 'Nature Gallery')->first();

    expect($category)->not->toBeNull()
        ->and($category->slug)->toBe('nature-gallery')
        ->and($category->name_np)->toBe('नेचर ग्यालरी')
        ->and($category->description_np)->toBe('बाहिरी दृश्य')
        ->and($category->image)->not->toBeNull();

    Storage::disk('public')->assertExists($category->image);
});

it('updates a gallery category and replaces the featured image', function () {
    Storage::fake('public');

    $user = makeGalleryAuthUser();

    $category = GalleryCategory::create([
        'name' => 'Travel Gallery',
        'slug' => 'travel-gallery',
        'description' => 'Old description',
        'image' => 'gallery/1/featured_image/old.jpg',
    ]);

    Storage::disk('public')->put($category->image, 'old-image');

    $response = $this->actingAs($user)->put(route('gallery-categories.update', $category), [
        'name' => 'Travel Gallery',
        'name_np' => 'यात्रा ग्यालरी',
        'description' => 'Updated description',
        'description_np' => 'अद्यावधिक विवरण',
        'image' => UploadedFile::fake()->image('new-featured.png'),
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Category updated successfully',
        ]);

    $category->refresh();

    expect($category->description)->toBe('Updated description')
        ->and($category->name_np)->toBe('यात्रा ग्यालरी')
        ->and($category->description_np)->toBe('अद्यावधिक विवरण')
        ->and($category->slug)->toBe('travel-gallery')
        ->and($category->image)->not->toBe('gallery/1/featured_image/old.jpg');

    Storage::disk('public')->assertMissing('gallery/1/featured_image/old.jpg');
    Storage::disk('public')->assertExists($category->image);
});

it('deletes a gallery category and its stored files', function () {
    Storage::fake('public');

    $user = makeGalleryAuthUser();

    $category = GalleryCategory::create([
        'name' => 'Delete Gallery',
        'slug' => 'delete-gallery',
        'description' => 'Temporary',
        'image' => null,
    ]);

    $category->update([
        'image' => "gallery/{$category->id}/featured_image/featured.jpg",
    ]);

    $galleryImage = GalleryImage::create([
        'gallery_category_id' => $category->id,
        'name' => 'Image One',
        'description' => 'Temporary image',
        'image' => "gallery/{$category->id}/images/image-one.jpg",
    ]);

    Storage::disk('public')->put($category->image, 'featured-image');
    Storage::disk('public')->put($galleryImage->image, 'gallery-image');

    $response = $this->actingAs($user)->delete(route('gallery-categories.destroy', $category));

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Category and its images deleted successfully',
        ]);

    expect(GalleryCategory::find($category->id))->toBeNull();
    expect(GalleryImage::find($galleryImage->id))->toBeNull();

    Storage::disk('public')->assertMissing($category->image);
    Storage::disk('public')->assertMissing($galleryImage->image);
});

it('creates and deletes a gallery image with file cleanup', function () {
    Storage::fake('public');

    $user = makeGalleryAuthUser();

    $category = GalleryCategory::create([
        'name' => 'Portfolio',
        'slug' => 'portfolio',
        'description' => null,
        'image' => null,
    ]);

    $createResponse = $this->actingAs($user)->post(route('gallery-images.store'), [
        'gallery_category_id' => $category->id,
        'name' => 'Hero Shot',
        'name_np' => 'नायक फोटो',
        'description' => 'Main photo',
        'description_np' => 'मुख्य फोटो',
        'image' => UploadedFile::fake()->image('hero.jpg'),
    ]);

    $createResponse->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Image added successfully',
        ]);

    $galleryImage = GalleryImage::query()->where('name', 'Hero Shot')->first();

    expect($galleryImage)->not->toBeNull()
        ->and($galleryImage->name_np)->toBe('नायक फोटो')
        ->and($galleryImage->description_np)->toBe('मुख्य फोटो');
    Storage::disk('public')->assertExists($galleryImage->image);

    $deleteResponse = $this->actingAs($user)->delete(route('gallery-images.destroy', $galleryImage));

    $deleteResponse->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Image deleted successfully',
        ]);

    expect(GalleryImage::find($galleryImage->id))->toBeNull();
    Storage::disk('public')->assertMissing($galleryImage->image);
});