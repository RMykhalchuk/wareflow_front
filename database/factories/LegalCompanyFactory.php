<?php

namespace Database\Factories;

use App\Models\Entities\Address\AddressDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entities\Company\LegalCompany>
 */
class LegalCompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'edrpou' => $this->faker->numerify('##########'),
            'legal_type_id' => '',
            'legal_address_id' => AddressDetails::factory()
        ];
    }
}
