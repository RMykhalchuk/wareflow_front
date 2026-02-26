<?php

namespace Database\Factories;

use App\Models\Company\CompanyType;
use App\Models\Entities\Address\AddressDetails;
use App\Models\Entities\Company\Company;
use App\Models\Entities\Company\PhysicalCompany;
use App\Models\Entities\System\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entities\Company\Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'email' => $this->faker->safeEmail(),
            'company_type' => 'App\Models\Entities\Company\PhysicalCompany',
            'company_id' => PhysicalCompany::factory(),
            'company_type_id' => (CompanyType::where('key', 'physical')->first('id'))->id,
            'ipn' => $this->faker->numerify('##########'),
            'address_id' => AddressDetails::factory(),
            'bank' => $this->faker->text(50),
            'iban' => $this->faker->text(29),
            'mfo' => $this->faker->numerify('#####'),
            'about' => $this->faker->text(),
            'currency' => $this->faker->text(5),
            'workspace_id' => Workspace::factory()
        ];
    }
}
