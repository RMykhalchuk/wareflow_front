<?php

namespace App\Http\Requests\Web\Goods;

use App\Http\Requests\RequestJson;
use Dedoc\Scramble\Attributes\BodyParameter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

/**
 * GoodsRequest.
 */
final class GoodsRequest extends FormRequest
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
            'image' => ['nullable',
                File::types(['png', 'jpeg', 'jpg','gif'])
                    ->max(800)
            ],
            'name' => ['required', 'string', 'max:50'],
            'brand' => ['required', 'string', 'max:50'],
            'manufacturer' => ['required', 'string', 'max:50'],
            'provider' => ['required', 'string', 'max:50'],
            'expiration_date' => ['nullable', 'array'],
            'expiration_date.*' => ['integer'],
            'is_batch_accounting' => ['required', 'boolean'],
            'is_weight' => ['required', 'boolean'],
            'weight_netto' => ['required', 'numeric', 'min:0'],
            'weight_brutto' => ['required', 'numeric', 'min:0', 'gte:weight_netto'],
            'height' => ['required', 'numeric', 'min:0'],
            'width' => ['required', 'numeric', 'min:0'],
            'length' => ['required', 'numeric', 'min:0'],
            'temp_from' => ['nullable', 'numeric'],
            'temp_to' => ['nullable', 'numeric', 'gte:temp_from'],
            'humidity_from' => ['nullable', 'numeric'],
            'humidity_to' => ['nullable', 'numeric', 'gte:humidity_from'],
            'dustiness_from' => ['nullable', 'numeric'],
            'dustiness_to' => ['nullable', 'numeric', 'gte:dustiness_from'],
            'measurement_unit_id' => ['required', 'integer', 'exists:_d_measurement_units,id'],
            'adr_id' => ['nullable', 'integer', 'exists:_d_adrs,id'],
            'manufacturer_country_id' => ['required', 'integer', 'exists:_d_countries,id'],
            'category_id' => ['nullable', 'uuid', 'exists:categories,id'],

            'packages' => ['required', 'array'],
            'packages.*.id' => ['nullable','integer'],
            'packages.*.parent_id' => ['nullable', 'integer'],
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
