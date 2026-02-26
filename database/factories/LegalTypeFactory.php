<?php

namespace Database\Factories;

use App\Models\Dictionaries\LegalType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dictionaries\LegalType>
 */
class LegalTypeFactory extends Factory
{
    protected $model = LegalType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['Тип 1', 'Тип 2', 'Тип 3'])
        ];
    }
}
