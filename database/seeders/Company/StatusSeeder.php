<?php

namespace Database\Seeders\Company;

use App\Models\Dictionaries\CompanyStatus;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanyStatus::updateOrCreate(
            ['key' => 'to_accept'],
            ['name' => [
                'uk' => 'Компанія на розгляді',
                'en' => 'Company under review'
            ]]
        );

        CompanyStatus::updateOrCreate(
            ['key' => 'accepted'],
            ['name' => [
                'uk' => 'Прийнята компанія',
                'en' => 'Accepted company'
            ]]
        );

        CompanyStatus::updateOrCreate(
            ['key' => 'rejected'],
            ['name' => [
                'uk' => 'Відхилена компанія',
                'en' => 'Rejected company'
            ]]
        );
    }
}
