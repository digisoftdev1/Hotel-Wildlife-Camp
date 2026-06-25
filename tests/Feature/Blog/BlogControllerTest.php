<?php

use App\Models\Content;
use App\Models\ContentType;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function makeAuthUser(): User
{
    return User::factory()->create([
        'username' => fake()->unique()->userName(),
    ]);
}

it('redirects guests from blog index', function () {
    $response = $this->get(route('blogs.index'));

    $response->assertRedirect(route('login'));
});

it('creates a blog with featured image', function () {
    Storage::fake('public');

    $user = makeAuthUser();
    $type = ContentType::create([
        'name' => 'News',
    ]);

    $payload = [
        'title' => 'Test Blog Title',
        'excerpt' => 'Short excerpt',
        'content' => '<p>Blog content body for read time.</p>',
        'status' => 'draft',
        'is_featured' => '1',
        'content_type_id' => $type->id,
        'featured_image' => UploadedFile::fake()->image('featured.jpg'),
    ];

    $response = $this->actingAs($user)->post(route('blogs.store'), $payload);

    $response->assertSessionHas('success');

    $content = Content::query()->where('title', 'Test Blog Title')->first();

    expect($content)->not->toBeNull();
    expect($content->excerpt)->toBe('Short excerpt');
    expect($content->content_type_id)->toBe($type->id);
    expect($content->read_time)->toBeGreaterThan(0);
    expect($content->featured_image)->not->toBeNull();

    Storage::disk('public')->assertExists($content->featured_image);
});

it('updates a blog and replaces featured image', function () {
    Storage::fake('public');

    $user = makeAuthUser();
    $type = ContentType::create([
        'name' => 'Article',
    ]);

    $content = Content::create([
        'title' => 'Old Title',
        'excerpt' => 'Old excerpt',
        'content' => '<p>Old content</p>',
        'status' => 'draft',
        'is_featured' => false,
        'content_type_id' => $type->id,
        'featured_image' => 'contents/1/featured_image.jpg',
        'read_time' => 1,
    ]);

    $oldPath = "contents/{$content->id}/featured_image.jpg";
    Storage::disk('public')->put($oldPath, 'old-image');
    $content->update(['featured_image' => $oldPath]);

    $response = $this->actingAs($user)->put(route('blogs.update', $content), [
        'title' => 'Old Title',
        'excerpt' => 'New excerpt',
        'content' => '<p>Updated content goes here.</p>',
        'status' => 'draft',
        'content_type_id' => $type->id,
        'featured_image' => UploadedFile::fake()->image('featured_image.png'),
    ]);

    $response->assertSessionHas('success');

    $content->refresh();

    expect($content->title)->toBe('Old Title');
    expect($content->excerpt)->toBe('New excerpt');
    expect($content->featured_image)->toBe("contents/{$content->id}/featured_image.png");

    Storage::disk('public')->assertMissing($oldPath);
    Storage::disk('public')->assertExists($content->featured_image);
});

it('returns child content types for ajax endpoint', function () {
    $user = makeAuthUser();

    $parent = ContentType::create(['name' => 'Parent Type']);
    $childA = ContentType::create(['name' => 'Child A', 'parent_id' => $parent->id]);
    $childB = ContentType::create(['name' => 'Child B', 'parent_id' => $parent->id]);

    $response = $this->actingAs($user)
        ->get(route('blogs.content-types.children', $parent));

    $response->assertOk()
        ->assertJson([
            'has_children' => true,
        ])
        ->assertJsonCount(2, 'children')
        ->assertJsonFragment(['id' => $childA->id, 'name' => $childA->name])
        ->assertJsonFragment(['id' => $childB->id, 'name' => $childB->name]);
});
