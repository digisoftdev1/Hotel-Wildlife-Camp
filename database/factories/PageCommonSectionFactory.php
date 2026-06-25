<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\PageCommonSection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageCommonSectionFactory extends Factory
{
    protected $model = PageCommonSection::class;

    public function definition(): array
    {
        return [
            'page_id' => Page::factory(),
            'section_type' => $this->faker->slug,
            'section_title' => $this->faker->sentence,
            'heading' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => 'published',
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}
