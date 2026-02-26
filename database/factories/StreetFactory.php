<?php

namespace Database\Factories;

use App\Models\Entities\Address\Street;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entities\Address\Street>
 */
class StreetFactory extends Factory
{
    protected $model = Street::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->streetName()
        ];
    }
}
