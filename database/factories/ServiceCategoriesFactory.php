<?php

namespace Database\Factories;

use App\Models\Dictionaries\ServiceCategories;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entities\Company\Company>
 */
class ServiceCategoriesFactory extends Factory
{
    protected $model = ServiceCategories::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(50),
        ];
    }
}
