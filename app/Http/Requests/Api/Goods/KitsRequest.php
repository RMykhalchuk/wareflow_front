<?php

namespace App\Http\Requests\Api\Goods;

use App\Http\Requests\RequestJson;
use Dedoc\Scramble\Attributes\BodyParameter;
use Illuminate\Foundation\Http\FormRequest;


/**
 * KitsItemsRequest.
 */
class KitsRequest extends FormRequest
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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_batch_accounting' => $this->input('is_batch_accounting') ?? false,
            'is_weight'           => $this->input('is_weight') ?? false,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    #[BodyParameter('name', type: 'string', example: 'Milk 1L', description: 'Name of the product')]
    #[BodyParameter('brand', type: 'uuid', example: 'f47ac10b-58cc-4372-a567-0e02b2c3d479', description: 'Brand')]
    #[BodyParameter('manufacturer', type: 'uuid', description: 'Manufacturer', example: 'f47ac10b-58cc-4372-a567-0e02b2c3d479')]
    #[BodyParameter('provider', type: 'uuid', description: 'Provider', example: 'f47ac10b-58cc-4372-a567-0e02b2c3d479')]
    #[BodyParameter('expiration_date', type: 'array', description: 'Expiration terms as array of integers (days)')]
    #[BodyParameter('expiration_date.*', type: 'integer', example: 365, description: 'Expiration term in days')]
    #[BodyParameter('is_batch_accounting', type: 'boolean', example: 'true', description: 'Batch accounting flag')]
    #[BodyParameter('is_weight', type: 'boolean', example: 'false', description: 'Is weighted product')]
    #[BodyParameter('weight_netto', type: 'number', example: '0.5', description: 'Net weight')]
    #[BodyParameter('weight_brutto', type: 'number', example: '0.6', description: 'Gross weight. Must be >= weight_netto')]
    #[BodyParameter('height', type: 'number', example: '10.0', description: 'Height in cm')]
    #[BodyParameter('width', type: 'number', example: '5.0', description: 'Width in cm')]
    #[BodyParameter('length', type: 'number', example: '20.0', description: 'Length in cm')]
    #[BodyParameter('temp_from', type: 'number', required: false, description: 'Temperature from (nullable)')]
    #[BodyParameter('temp_to', type: 'number', required: false, description: 'Temperature to (nullable) — must be >= temp_from')]
    #[BodyParameter('humidity_from', type: 'number', required: false)]
    #[BodyParameter('humidity_to', type: 'number', required: false, description: '>= humidity_from')]
    #[BodyParameter('dustiness_from', type: 'number', required: false)]
    #[BodyParameter('dustiness_to', type: 'number', required: false, description: '>= dustiness_from')]
    #[BodyParameter('measurement_unit_id', type: 'integer', example: '1', description: 'ID of measurement unit (_d_measurement_units)')]
    #[BodyParameter('adr_id', type: 'integer', required: false, example: '2', description: 'ADR id (nullable)')]
    #[BodyParameter('manufacturer_country_id', type: 'integer', example: '1', description: 'Country id')]
    #[BodyParameter('category_id', type: 'string', required: false, example: 'uuid', description: 'Category UUID (nullable)')]
    // packages array (detailed: packages.*.*)
    #[BodyParameter('packages', type: 'array', description: 'Array of package definitions')]
    #[BodyParameter('packages.*.id', type: 'integer', required: false, description: 'Existing package id for update (nullable)')]
    #[BodyParameter(
        'packages.*.parent_id',
        type: 'integer',
        required: false,
        example: '1',
        description: 'Package parent number/level. This is NOT DB id — it is package ordinal level (1 = smallest unit, 2 = next, etc.). Use integer sequence: 1,2,3...'
    )]
    #[BodyParameter('packages.*.name', type: 'string', example: 'Bottle 1L', description: 'Package name')]
    #[BodyParameter('packages.*.barcode', type: 'string', example: '0123456789012', description: 'Barcode of the package')]
    #[BodyParameter('packages.*.main_units_number', type: 'number', example: '1.000', description: 'Number of main units in this package (decimal up to 3)')]
    #[BodyParameter('packages.*.package_count', type: 'integer', required: false, example: '6', description: 'Number of packages in upper-level pack (nullable)')]
    #[BodyParameter('packages.*.weight_netto', type: 'number', example: '0.5', description: 'Net weight of package')]
    #[BodyParameter('packages.*.weight_brutto', type: 'number', example: '0.6', description: 'Gross weight of package')]
    #[BodyParameter('packages.*.height', type: 'number', example: '10.0')]
    #[BodyParameter('packages.*.width', type: 'number', example: '5.0')]
    #[BodyParameter('packages.*.length', type: 'number', example: '20.0')]
    // barcodes array
    #[BodyParameter('barcodes', type: 'array', description: 'Array of product barcodes (strings)')]
    #[BodyParameter('barcodes.*', type: 'string', example: '0123456789012', description: 'Barcode value')]
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:50'],
            'brand' => ['nullable', 'uuid', 'max:50'],
            'manufacturer' => ['nullable', 'uuid', 'max:50'],
            'provider' => ['nullable', 'uuid', 'max:50'],
            'expiration_date' => ['nullable', 'array'],
            'expiration_date.*' => ['integer'],
            'is_batch_accounting' => ['nullable', 'boolean'],
            'is_weight' => ['nullable', 'boolean'],
            'weight_netto' => ['required', 'numeric', 'min:0'],
            'weight_brutto' => ['required', 'numeric', 'min:0', 'gte:weight_netto'],
            'height' => ['required', 'numeric', 'min:0'],
            'width' => ['required', 'numeric', 'min:0'],
            'length' => ['required', 'numeric', 'min:0'],
            'temp_from' => ['nullable', 'integer'],
            'temp_to' => ['nullable', 'integer', 'gte:temp_from'],
            'humidity_from' => ['nullable', 'integer'],
            'humidity_to' => ['nullable', 'integer', 'gte:humidity_from'],
            'dustiness_from' => ['nullable', 'integer'],
            'dustiness_to' => ['nullable', 'integer', 'gte:dustiness_from'],
            'measurement_unit_id' => ['required', 'integer', 'exists:_d_measurement_units,id'],
            'adr_id' => ['nullable', 'integer', 'exists:_d_adrs,id'],
            'manufacturer_country_id' => ['nullable', 'integer', 'exists:_d_countries,id'],
            'category_id' => ['nullable', 'uuid', 'exists:categories,id'],

            'packages' => ['required', 'array'],
            'packages.*.id' => ['nullable', 'integer'],
            'packages.*.parent_id' => ['nullable', 'integer'],
            'packages.*.type_id' => ['required', 'integer','exists:_d_package_types,id'],
            'packages.*.name' => ['required', 'string', 'max:50'],
            'packages.*.barcode' => ['required', 'string', 'max:30'],
            'packages.*.main_units_number' => ['required', 'numeric', 'min:0.001', 'decimal:0,3'],
            'packages.*.package_count' => ['nullable', 'integer', 'min:1'],
            'packages.*.weight_netto' => ['required', 'numeric', 'min:0'],
            'packages.*.weight_brutto' => ['required', 'numeric', 'min:0'],
            'packages.*.height' => ['required', 'numeric', 'min:0'],
            'packages.*.width' => ['required', 'numeric', 'min:0'],
            'packages.*.length' => ['required', 'numeric', 'min:0'],

            'barcodes' => ['required', 'array'],
            'barcodes.*' => ['required', 'string', 'max:30'],

            'goods' => ['required', 'array'],
            'goods.*.goods_id' => ['uuid', 'exists:goods,id'],
            'goods.*.package_id' => ['uuid', 'exists:packages,id'],
            'goods.*.quantity' => ['numeric', 'min:0.1'],
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
                        trans('validation.gte.numeric', [
                            'attribute' => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_edit_gross_weight_label'),
                            'value'     => __('localization.sku_edit_net_weight_label'),
                        ])
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
            'name' => __('localization.sku_create_add_paking_name'),
            'brand' => __('localization.sku_view_brand'),
            'manufacturer' => __('localization.sku_create_manufacturer'),
            'provider' => __('localization.sku_create_provider'),

            'expiration_date' => __('localization.sku_view_expiration_date'),
            'expiration_date.*' => __('localization.sku_view_expiration_date'),

            'is_batch_accounting' => __('localization.sku_edit_batch_label'),
            'is_weight' => __('localization.sku_edit_net_weight_label'),
            'weight_netto' => __('localization.sku_edit_net_weight_label'),
            'weight_brutto' => __('localization.sku_edit_gross_weight_label'),
            'height' => __('localization.sku_edit_height_label'),
            'width' => __('localization.sku_create_width'),
            'length' => __('localization.sku_create_add_paking_length'),
            'temp_from' => __('localization.sku_edit_temperature_regime_label'),
            'temp_to' => __('localization.sku_edit_temperature_regime_label'),
            'humidity_from' => __('localization.sku_view_humidity'),
            'humidity_to' => __('localization.sku_view_humidity'),
            'dustiness_from' => __('localization.sku_view_dustiness'),
            'dustiness_to' => __('localization.sku_view_dustiness'),
            'measurement_unit_id' => __('localization.sku_create_measurement_unit'),
            'adr_id' => __('localization.sku_view_adr'),
            'manufacturer_country_id' => __('localization.sku_create_manufacturer_country'),
            'category_id' => __('localization.sku_edit_category_label'),

            'packages' => __('localization.sku_create_tabs_packaging'),
            'packages.*.id' => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_package_id'),
            'packages.*.parent_id' => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_create_add_paking_parent'),
            'packages.*.name' => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_create_add_paking_name'),
            'packages.*.barcode' => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_create_barcode'),
            'packages.*.main_units_number' => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_create_measurement_unit'),
            'packages.*.package_count' => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_create_add_package_count'),
            'packages.*.weight_netto' => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_view_net_weight'),
            'packages.*.weight_brutto' => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_edit_gross_weight_label'),
            'packages.*.height' => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_create_height'),
            'packages.*.width' => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_create_width'),
            'packages.*.length' => __('localization.sku_create_tabs_packaging') . ' ' . __('localization.sku_create_add_paking_length'),

            'barcodes' => __('localization.sku_create_barcode'),
            'barcodes.*' => __('localization.sku_create_barcode'),

            'goods' => __('localization.kits.content.title'),
            'goods.*.goods_parent_id' => __('localization.sku_create_edit_paking_parent'),
            'goods.*.package_id' => __('localization.sku_create_tabs_packaging'),
            'goods.*.quantity' => __('localization.kits.modal.add.fields.quantity_label'),
        ];
    }
}
