<?php

use App\Models\Page;
use App\Models\PageCommonSection;
use App\Models\CommonSectionCtaButton;
use App\Models\CommonSectionImage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('page common section has many cta buttons', function () {
    $section = PageCommonSection::factory()->create();
    $button = CommonSectionCtaButton::factory()->create(['common_section_id' => $section->id]);

    expect($section->ctaButtons)->toHaveCount(1)
        ->and($section->ctaButtons->first()->id)->toBe($button->id);
});

test('page common section has many images', function () {
    $section = PageCommonSection::factory()->create();
    $image = CommonSectionImage::factory()->create(['common_section_id' => $section->id]);

    expect($section->images)->toHaveCount(1)
        ->and($section->images->first()->id)->toBe($image->id);
});

test('cta button belongs to a section and a page', function () {
    $page = Page::factory()->create();
    $section = PageCommonSection::factory()->create();
    $button = CommonSectionCtaButton::factory()->create([
        'common_section_id' => $section->id,
        'page_id' => $page->id
    ]);

    expect($button->commonSection->id)->toBe($section->id)
        ->and($button->page->id)->toBe($page->id);
});

test('section image belongs to a section', function () {
    $section = PageCommonSection::factory()->create();
    $image = CommonSectionImage::factory()->create(['common_section_id' => $section->id]);

    expect($image->commonSection->id)->toBe($section->id);
});
