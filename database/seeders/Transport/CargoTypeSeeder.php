<?php

namespace Database\Seeders\Transport;

use App\Models\Dictionaries\CargoType;
use Illuminate\Database\Seeder;

class CargoTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CargoType::updateOrCreate(
            ['key' => 'product'],
            ['name' => [
                'uk' => 'Продукти харчування, б/а напої без дотримання температурного режиму',
                'en' => 'Food products, non-alcoholic beverages without temperature control'
            ]]
        );

        CargoType::updateOrCreate(
            ['key' => 'product_t'],
            ['name' => [
                'uk' => 'Продукти харчування з дотриманням температурного режиму',
                'en' => 'Food products with temperature control'
            ]]
        );

        CargoType::updateOrCreate(
            ['key' => 'pobut'],
            ['name' => [
                'uk' => 'Побутові та господарські товари',
                'en' => 'Household and domestic goods'
            ]]
        );

        CargoType::updateOrCreate(
            ['key' => 'vydub_prom'],
            ['name' => [
                'uk' => 'Продукція видобувної промисловості',
                'en' => 'Mining industry products'
            ]]
        );

        CargoType::updateOrCreate(
            ['key' => 'textile'],
            ['name' => [
                'uk' => 'Текстильні товари',
                'en' => 'Textile products'
            ]]
        );

        CargoType::updateOrCreate(
            ['key' => 'bud_material'],
            ['name' => [
                'uk' => 'Будівельні матеріали, інструменти, сировина для будівництва, сантехніка',
                'en' => 'Building materials, tools, construction raw materials, plumbing'
            ]]
        );

        CargoType::updateOrCreate(
            ['key' => 'poligraph_prod'],
            ['name' => [
                'uk' => 'Поліграфічна продукція',
                'en' => 'Printing products'
            ]]
        );

        CargoType::updateOrCreate(
            ['key' => 'sport_prylad'],
            ['name' => [
                'uk' => 'Спортивне приладдя та аксесуари для відпочинку',
                'en' => 'Sports equipment and leisure accessories'
            ]]
        );

        CargoType::updateOrCreate(
            ['key' => 'naft_prod'],
            ['name' => [
                'uk' => 'Нафтопродукти ADR',
                'en' => 'ADR petroleum products'
            ]]
        );

        CargoType::updateOrCreate(
            ['key' => 'material_synt'],
            ['name' => [
                'uk' => 'Матеріали штучного походження(синтетичні, гумові, пластмасові)',
                'en' => 'Artificial materials (synthetic, rubber, plastic)'
            ]]
        );

        CargoType::updateOrCreate(
            ['key' => 'vyroby_zi_skla'],
            ['name' => [
                'uk' => 'Вироби зі скла, фарфору, кераміки та інший крихкий вантаж',
                'en' => 'Glass, porcelain, ceramic products and other fragile cargo'
            ]]
        );

        CargoType::updateOrCreate(
            ['key' => 'elektronika'],
            ['name' => [
                'uk' => 'Електротехніка, деталі до електричних приладів, аксесуари',
                'en' => 'Electronics, electric device parts, accessories'
            ]]
        );

        CargoType::updateOrCreate(
            ['key' => 'mebli'],
            ['name' => [
                'uk' => 'Меблі',
                'en' => 'Furniture'
            ]]
        );

        CargoType::updateOrCreate(
            ['key' => 'pryrodna_s'],
            ['name' => [
                'uk' => 'Природна сировина',
                'en' => 'Natural raw materials'
            ]]
        );

        CargoType::updateOrCreate(
            ['key' => 'tsinni_mat'],
            ['name' => [
                'uk' => 'Цінні матеріали',
                'en' => 'Valuable materials'
            ]]
        );

        CargoType::updateOrCreate(
            ['key' => 'other'],
            ['name' => [
                'uk' => 'Інші види вантажів, не віднесені до попередніх угруповань',
                'en' => 'Other types of cargo not included in previous groups'
            ]]
        );

        CargoType::updateOrCreate(
            ['key' => 'raw'],
            ['name' => [
                'uk' => 'Сировина',
                'en' => 'Raw materials'
            ]]
        );
    }
}
