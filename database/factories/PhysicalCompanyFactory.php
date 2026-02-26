<?php

namespace Database\Factories;

use App\Models\Entities\Company\PhysicalCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entities\Company\PhysicalCompany>
 */
class PhysicalCompanyFactory extends Factory
{
    protected $model = PhysicalCompany::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'patronymic' => $this->faker->text(20)
        ];
    }
}
