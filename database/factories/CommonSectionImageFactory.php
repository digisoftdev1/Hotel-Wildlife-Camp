<?php

namespace Database\Factories;

use App\Models\CommonSectionImage;
use App\Models\PageCommonSection;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommonSectionImageFactory extends Factory
{
    protected $model = CommonSectionImage::class;

    public function definition(): array
    {
        return [
            'common_section_id' => PageCommonSection::factory(),
            'image_path' => 'sections/fake.jpg',
            'alt_text' => $this->faker->sentence,
            'order' => 0,
        ];
    }
}
