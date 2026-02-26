<?php

namespace Database\Factories;

use App\Models\Entities\Address\Country;
use App\Models\Entities\Address\Settlement;
use App\Models\Entities\Address\Street;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entities\Address\AddressDetails>
 */
class AddressDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'country_id' => Country::factory(),
            'settlement_id' => Settlement::factory(),
            'street_id' => Street::factory(),
            'building_number' => $this->faker->randomDigit(),
            'apartment_number' => $this->faker->numerify('#####'),
            'gln' => $this->faker->numerify('####'),
            'legal_address' => substr(fake()->address, 0, 40)
        ];
    }
}
