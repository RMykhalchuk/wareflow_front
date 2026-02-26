<?php

namespace App\Http\Requests\Api\Goods;

use App\Http\Requests\RequestJson;
use GoodsData;
use Illuminate\Foundation\Http\FormRequest;

/**
 * GoodsRequest.
 */

class GoodsRequest extends FormRequest
{
    use RequestJson;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */


    public function rules(): array
    {
        $rules = [
            /**
             * Product name
             * @var string
             * @example "Milk 1L"
             */
            'name' => ['required', 'string', 'max:50'],

            /**
             * Brand name
             * @var string
             * @example "550e8400-e29b-41d4-a716-446655440000"
             */
            'brand' => ['required', 'uuid', 'max:50'],

            /**
             * Manufacturer UUID
             * @var string
             * @example "550e8400-e29b-41d4-a716-446655440000"
             */
            'manufacturer' => ['required', 'uuid', 'max:50'],

            /**
             * Provider UUID
             * @var string
             * @example "550e8400-e29b-41d4-a716-446655440000"
             */
            'provider' => ['required', 'string', 'max:50'],

            /**
             * Expiration date terms in days
             * @var array<int>
             * @example [365, 180, 90]
             */
            'expiration_date' => ['required', 'array'],
            'expiration_date.*' => ['integer'],

            /**
             * Batch accounting flag
             * @var bool
             * @example true
             */
            'is_batch_accounting' => ['required', 'boolean'],

            /**
             * Is weighted product
             * @var bool
             * @example false
             */
            'is_weight' => ['required', 'boolean'],

            /**
             * Net weight in kg
             * @var float
             * @example 0.5
             */
            'weight_netto' => ['required', 'numeric', 'min:0'],

            /**
             * Gross weight in kg (must be >= weight_netto)
             * @var float
             * @example 0.6
             */
            'weight_brutto' => ['required', 'numeric', 'min:0', 'gte:weight_netto'],

            /**
             * Height in cm
             * @var float
             * @example 10.0
             */
            'height' => ['required', 'numeric', 'min:0'],

            /**
             * Width in cm
             * @var float
             * @example 5.0
             */
            'width' => ['required', 'numeric', 'min:0'],

            /**
             * Length in cm
             * @var float
             * @example 20.0
             */
            'length' => ['required', 'numeric', 'min:0'],

            /**
             * Temperature from (°C)
             * @var float|null
             * @example 2.0
             */
            'temp_from' => ['nullable', 'numeric'],

            /**
             * Temperature to (°C, must be >= temp_from)
             * @var float|null
             * @example 6.0
             */
            'temp_to' => ['nullable', 'numeric', 'gte:temp_from'],

            /**
             * Humidity from (%)
             * @var float|null
             * @example 40.0
             */
            'humidity_from' => ['nullable', 'numeric'],

            /**
             * Humidity to (%, must be >= humidity_from)
             * @var float|null
             * @example 60.0
             */
            'humidity_to' => ['nullable', 'numeric', 'gte:humidity_from'],

            /**
             * Dustiness from
             * @var float|null
             * @example 0.0
             */
            'dustiness_from' => ['nullable', 'numeric'],

            /**
             * Dustiness to (must be >= dustiness_from)
             * @var float|null
             * @example 5.0
             */
            'dustiness_to' => ['nullable', 'numeric', 'gte:dustiness_from'],

            /**
             * Measurement unit ID from _d_measurement_units table
             * @var int
             * @example 1
             */
            'measurement_unit_id' => ['required', 'integer', 'exists:_d_measurement_units,id'],

            /**
             * ADR classification ID from _d_adrs table
             * @var int|null
             * @example 2
             */
            'adr_id' => ['nullable', 'integer', 'exists:_d_adrs,id'],

            /**
             * Manufacturer country ID from _d_countries table
             * @var int
             * @example 1
             */
            'manufacturer_country_id' => ['required', 'integer', 'exists:_d_countries,id'],

            /**
             * Category UUID from categories table
             * @var string|null
             * @example "770e8400-e29b-41d4-a716-446655440000"
             */
            'category_id' => ['nullable', 'uuid', 'exists:categories,id'],

            /**
             * Array of package definitions
             * @var array<array{id?: int, parent_id?: int, type_id:int, name: string, barcode: string, main_units_number: float, package_count?: int, weight_netto: float, weight_brutto: float, height: float, width: float, length: float}>
            */
            'packages' => ['required', 'array'],

            /**
             * Package ID for update (optional)
             * @var int|null
             */
            'packages.*.id' => ['required','integer'],

            /**
             * Package ID for update (optional)
             * @var int|null
             */
            'packages.*.uuid' => ['required','string'],
            /**
             * Package parent id.
             * @var int|null
             * @example 1
             */
            'packages.*.parent_id' => ['nullable', 'integer'],

            /**
             * Package parent level: 1 = smallest unit, 2 = next level, etc.
             * @var int|null
             * @example 1
             */
            'packages.*.type_id' => ['nullable', 'integer','exists:_d_package_types,id'],

            /**
             * Package name
             * @var string
             * @example "Bottle 1L"
             */
            'packages.*.name' => ['required', 'string', 'max:50'],

            /**
             * Package barcode
             * @var string
             * @example "0123456789012"
             */
            'packages.*.barcode' => ['required', 'string', 'max:30'],

            /**
             * Number of main units in this package (up to 3 decimal places)
             * @var float
             * @example 1.000
             */
            'packages.*.main_units_number' => ['required', 'numeric', 'min:0.001', 'decimal:0,3'],

            /**
             * Number of packages in upper-level pack
             * @var int|null
             * @example 6
             */
            'packages.*.package_count' => ['nullable', 'integer', 'min:1'],

            /**
             * Package net weight in kg
             * @var float
             * @example 0.5
             */
            'packages.*.weight_netto' => ['required', 'numeric', 'min:0'],

            /**
             * Package gross weight in kg
             * @var float
             * @example 0.6
             */
            'packages.*.weight_brutto' => ['required', 'numeric', 'min:0'],

            /**
             * Package height in cm
             * @var float
             * @example 10.0
             */
            'packages.*.height' => ['required', 'numeric', 'min:0'],

            /**
             * Package width in cm
             * @var float
             * @example 5.0
             */
            'packages.*.width' => ['required', 'numeric', 'min:0'],

            /**
             * Package length in cm
             * @var float
             * @example 20.0
             */
            'packages.*.length' => ['required', 'numeric', 'min:0'],

            /**
             * Array of product barcodes
             * @var array<string>
             * @example ["0123456789012", "9876543210987"]
             */
            'barcodes' => ['required', 'array'],

            /**
             * Barcode value
             * @var string
             * @example "0123456789012"
             */
            'barcodes.*' => ['required', 'string', 'max:30'],
        ];

        return $rules;
    }

    /**
     * @param $validator
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $packages = $this->get('packages', []);
            foreach ($packages as $i => $package) {
                if (
                    isset($package['weight_netto'], $package['weight_brutto']) &&
                    $package['weight_brutto'] < $package['weight_netto']
                ) {
                    $validator->errors()->add(
                        "packages.$i.weight_brutto",
                        'Поле packages.' . $i . '.weight_brutto має дорівнювати чи бути більше ніж weight_netto.'
                    );
                }
            }
        });
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'image'                     => __('localization.transport_view_image_alt'),
            'name'                      => __('localization.sku_create_add_paking_name'),
            'brand'                     => __('localization.sku_view_brand'),
            'manufacturer'              => __('localization.sku_create_manufacturer'),
            'provider'                  => __('localization.sku_create_provider'),
            'expiration_date'           => __('localization.sku_view_expiration_date'),
            'is_batch_accounting'       => __('localization.sku_edit_batch_label'),
            'is_weight'                 => __('localization.sku_edit_net_weight_label'),
            'weight_netto'              => __('localization.sku_edit_net_weight_label'),
            'weight_brutto'             => __('localization.sku_edit_gross_weight_label'),
            'height'                    => __('localization.sku_edit_height_label'),
            'width'                     => __('localization.sku_create_width'),
            'length'                    => __('localization.sku_create_add_paking_length'),
            'temp_from'                 => __('localization.sku_edit_temperature_regime_label'),
            'temp_to'                   => __('localization.sku_edit_temperature_regime_label'),
            'humidity_from'             => __('localization.sku_view_humidity'),
            'humidity_to'               => __('localization.sku_view_humidity'),
            'dustiness_from'            => __('localization.sku_view_dustiness'),
            'dustiness_to'              => __('localization.sku_view_dustiness'),
            'measurement_unit_id'       => __('localization.sku_create_measurement_unit'),
            'adr_id'                    => __('localization.sku_view_adr'),
            'manufacturer_country_id'   => __('localization.sku_create_manufacturer_country'),
            'category_id'               => __('localization.sku_edit_category_label'),

            'packages'                      => __('localization.sku_create_tabs_packaging'),
            'packages.*.name'               => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_create_add_paking_name'),
            'packages.*.barcode'            => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_create_barcode'),
            'packages.*.parent_id'          => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_create_add_paking_parent'),
            'packages.*.main_units_number'  => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_create_measurement_unit'),
            'packages.*.package_count'      => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_create_add_package_count'),
            'packages.*.weight_netto'       => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_view_net_weight'),
            'packages.*.weight_brutto'      => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_edit_gross_weight_label'),
            'packages.*.height'             => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_create_height'),
            'packages.*.width'              => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_create_width'),
            'packages.*.length'             => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_create_add_paking_length'),

            'barcodes'          => __('localization.sku_create_barcode'),
            'barcodes.*'        => __('localization.sku_create_barcode'),
        ];
    }
}
