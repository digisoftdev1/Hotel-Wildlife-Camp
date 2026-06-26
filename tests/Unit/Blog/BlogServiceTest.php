<?php

use App\Models\Content;
use App\Models\ContentType;
use App\Services\BlogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('lists blogs and applies content type filter', function () {
    $typeA = ContentType::create(['name' => 'Type A']);
    $typeB = ContentType::create(['name' => 'Type B']);

    Content::create([
        'title' => 'Blog A',
        'excerpt' => 'Excerpt A',
        'content' => '<p>Content A</p>',
        'status' => 'draft',
        'is_featured' => false,
        'content_type_id' => $typeA->id,
    ]);

    Content::create([
        'title' => 'Blog B',
        'excerpt' => 'Excerpt B',
        'content' => '<p>Content B</p>',
        'status' => 'draft',
        'is_featured' => false,
        'content_type_id' => $typeB->id,
    ]);

    $service = app(BlogService::class);

    expect($service->listBlogs()->count())->toBe(2);
    expect($service->listBlogs($typeA->id)->count())->toBe(1)
        ->and($service->listBlogs($typeA->id)->first()->title)->toBe('Blog A');
});

it('returns only root content types', function () {
    $root = ContentType::create(['name' => 'Root']);
    ContentType::create(['name' => 'Child', 'parent_id' => $root->id]);

    $service = app(BlogService::class);
    $roots = $service->listRootContentTypes();

    expect($roots->count())->toBe(1)
        ->and($roots->first()->id)->toBe($root->id);
});

it('creates content with featured image and read time', function () {
    Storage::fake('public');

    $type = ContentType::create(['name' => 'News']);

    $service = app(BlogService::class);
    $content = $service->createContent([
        'title' => 'New Content',
        'excerpt' => 'Short excerpt',
        'content' => '<p>' . str_repeat('word ', 260) . '</p>',
        'status' => 'draft',
        'is_featured' => true,
        'content_type_id' => $type->id,
    ], UploadedFile::fake()->image('feature.jpg'));

    expect($content->id)->not->toBeNull()
        ->and($content->featured_image)->not->toBeNull()
        ->and($content->read_time)->toBeGreaterThan(1);

    Storage::disk('public')->assertExists($content->featured_image);
});

it('updates content and replaces featured image', function () {
    Storage::fake('public');

    $type = ContentType::create(['name' => 'Article']);

    $content = Content::create([
        'title' => 'Stable Title',
        'excerpt' => 'Old excerpt',
        'content' => '<p>Old content</p>',
        'status' => 'draft',
        'is_featured' => false,
        'content_type_id' => $type->id,
    ]);

    $oldPath = "contents/{$content->id}/featured_image.jpg";
    Storage::disk('public')->put($oldPath, 'old-image');
    $content->update(['featured_image' => $oldPath]);

    $service = app(BlogService::class);
    $updated = $service->updateContent($content, [
        'title' => 'Stable Title',
        'excerpt' => 'Updated excerpt',
        'content' => '<p>Updated content</p>',
        'status' => 'draft',
        'is_featured' => false,
        'content_type_id' => $type->id,
    ], UploadedFile::fake()->image('featured_image.png'));

    expect($updated->excerpt)->toBe('Updated excerpt')
        ->and($updated->featured_image)->toBe("contents/{$content->id}/featured_image.png");

    Storage::disk('public')->assertMissing($oldPath);
    Storage::disk('public')->assertExists($updated->featured_image);
});

it('deletes content and its storage directory', function () {
    Storage::fake('public');

    $type = ContentType::create(['name' => 'Delete Type']);

    $content = Content::create([
        'title' => 'Delete Me',
        'excerpt' => 'To delete',
        'content' => '<p>Delete body</p>',
        'status' => 'draft',
        'is_featured' => false,
        'content_type_id' => $type->id,
    ]);

    $path = "contents/{$content->id}/featured_image.jpg";
    Storage::disk('public')->put($path, 'image');
    $content->update(['featured_image' => $path]);

    $service = app(BlogService::class);
    $service->deleteContent($content);

    expect(Content::find($content->id))->toBeNull();
    Storage::disk('public')->assertMissing($path);
});

it('resolves content by slug from service', function () {
    $type = ContentType::create(['name' => 'Public Type']);

    $content = Content::create([
        'title' => 'Public Slug Post',
        'excerpt' => 'Public excerpt',
        'content' => '<p>Public body</p>',
        'status' => 'published',
        'is_featured' => false,
        'content_type_id' => $type->id,
    ]);

    $service = app(BlogService::class);
    $resolved = $service->resolveContentBySlug($content->slug);

    expect($resolved)->not->toBeNull()
        ->and($resolved['redirect'])->toBeFalse()
        ->and($resolved['content']->id)->toBe($content->id);
});

it('returns children for a parent content type', function () {
    $parent = ContentType::create(['name' => 'Parent']);
    $childA = ContentType::create(['name' => 'Child A', 'parent_id' => $parent->id]);
    $childB = ContentType::create(['name' => 'Child B', 'parent_id' => $parent->id]);

    $service = app(BlogService::class);
    $children = $service->getChildTypes($parent);

    expect($children->count())->toBe(2)
        ->and($children->pluck('id')->all())->toEqualCanonicalizing([$childA->id, $childB->id]);
});
