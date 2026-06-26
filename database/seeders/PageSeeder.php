<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Top-level pages
        $overview = Page::updateOrCreate(
            ['slug' => 'overview'],
            ['name' => 'Overview']
        );
        
          $about = Page::updateOrCreate(
            ['slug' => 'about'],
            ['name' => 'About']
        );
        
        $gallery = Page::updateOrCreate(
            ['slug' => 'gallery'],
            ['name' => 'Gallery']
        );

        $accommodation = Page::updateOrCreate(
            ['slug' => 'accommodation'],
            ['name' => 'Accommodation']
        );

        $activities = Page::updateOrCreate(
            ['slug' => 'activities'],
            ['name' => 'Activities']
        );

        $packages = Page::updateOrCreate(
            ['slug' => 'packages'],
            ['name' => 'Packages']
        );

        $contact = Page::updateOrCreate(
            ['slug' => 'contact'],
            ['name' => 'Contact']
        );

        $blog = Page::updateOrCreate(
            ['slug' => 'blog'],
            ['name' => 'Blog']
        );

    }
}