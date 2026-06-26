<?php

namespace Database\Factories;

use App\Models\CommonSectionCtaButton;
use App\Models\Page;
use App\Models\PageCommonSection;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommonSectionCtaButtonFactory extends Factory
{
    protected $model = CommonSectionCtaButton::class;

    public function definition(): array
    {
        return [
            'common_section_id' => PageCommonSection::factory(),
            'button_name' => $this->faker->word,
            'page_id' => Page::factory(),
            'order' => 0,
        ];
    }
}
