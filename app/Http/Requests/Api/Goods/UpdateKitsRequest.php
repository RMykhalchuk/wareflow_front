<?php

namespace App\Http\Requests\Api\Goods;

use App\Http\Requests\RequestJson;
use Dedoc\Scramble\Attributes\BodyParameter;
use Illuminate\Foundation\Http\FormRequest;


/**
 * KitsItemsRequest.
 */
class UpdateKitsRequest extends KitsRequest
{
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
    #[BodyParameter('packages.*.id', type: 'integer', required: false, description: 'Existing package temporarary id for update. For example: id = 1,
     parent_id of the child element should be equal 1 for sync packages')]
    #[BodyParameter('packages.*.uuid', type: 'string', required: false, description: 'Existing package uuid or "false" for new packages')]
    #[BodyParameter(
        'packages.*.parent_id',
        type: 'integer',
        required: false,
        example: '1',
        description: 'Package parent number/level. This is NOT DB id — it is package ordinal level (1 = smallest unit, 2 = next, etc.). Use integer sequence: 1,2,3...'
    )]
    #[BodyParameter('packages.*.parent_id', type: 'integer', required: false, description: 'Package parent id.')]
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
    protected function prepareForValidation(): void
    {
        if ($this->has('is_batch_accounting')) {
            $this->merge(['is_batch_accounting' => $this->input('is_batch_accounting') ?? false]);
        }
        if ($this->has('is_weight')) {
            $this->merge(['is_weight' => $this->input('is_weight') ?? false]);
        }
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['nullable', 'string', 'max:50'],
            'brand' => ['nullable', 'string', 'max:50'],
            'manufacturer' => ['nullable', 'string', 'max:50'],
            'provider' => ['nullable', 'string', 'max:50'],
            'expiration_date' => ['nullable', 'array'],
            'expiration_date.*' => ['integer'],
            'is_batch_accounting' => ['nullable', 'boolean'],
            'is_weight' => ['nullable', 'boolean'],
            'weight_netto' => ['nullable', 'numeric', 'min:0'],
            'weight_brutto' => ['nullable', 'numeric', 'min:0', 'gte:weight_netto'],
            'height' => ['nullable', 'numeric', 'min:0'],
            'width' => ['nullable', 'numeric', 'min:0'],
            'length' => ['nullable', 'numeric', 'min:0'],
            'temp_from' => ['nullable', 'integer'],
            'temp_to' => ['nullable', 'integer', 'gte:temp_from'],
            'humidity_from' => ['nullable', 'integer'],
            'humidity_to' => ['nullable', 'integer', 'gte:humidity_from'],
            'dustiness_from' => ['nullable', 'integer'],
            'dustiness_to' => ['nullable', 'integer', 'gte:dustiness_from'],
            'measurement_unit_id' => ['nullable', 'integer', 'exists:_d_measurement_units,id'],
            'adr_id' => ['nullable', 'integer', 'exists:_d_adrs,id'],
            'manufacturer_country_id' => ['nullable', 'integer', 'exists:_d_countries,id'],
            'category_id' => ['nullable', 'uuid', 'exists:categories,id'],

            'packages' => ['nullable', 'array'],
            'packages.*.uuid' => ['required'],
            'packages.*.id' => ['nullable', 'integer'],
            'packages.*.parent_id' => ['nullable', 'integer'],
            'packages.*.name' => ['nullable', 'string', 'max:50'],
            'packages.*.barcode' => ['nullable', 'string', 'max:30'],
            'packages.*.main_units_number' => ['nullable', 'numeric', 'min:0.001', 'decimal:0,3'],
            'packages.*.package_count' => ['nullable', 'integer', 'min:1'],
            'packages.*.weight_netto' => ['nullable', 'numeric', 'min:0'],
            'packages.*.weight_brutto' => ['nullable', 'numeric', 'min:0'],
            'packages.*.height' => ['nullable', 'numeric', 'min:0'],
            'packages.*.width' => ['nullable', 'numeric', 'min:0'],
            'packages.*.length' => ['nullable', 'numeric', 'min:0'],

            /**
             * Package parent level: 1 = smallest unit, 2 = next level, etc.
             * @var int|null
             * @example 1
             */
            'packages.*.type_id' => ['nullable', 'integer','exists:_d_package_types,id'],

            'barcodes' => ['nullable', 'array'],
            'barcodes.*' => ['nullable', 'string', 'max:30'],

            'goods' => ['nullable', 'array'],
            'goods.*.goods_parent_id' => ['uuid'],
            'goods.*.goods_id' => ['uuid'],
            'goods.*.package_id' => ['uuid'],
            'goods.*.quantity' => ['integer', 'min:1'],
        ];

        return $rules;
    }
}
