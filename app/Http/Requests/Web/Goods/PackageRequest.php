<?php

namespace App\Http\Requests\Web\Goods;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * PackageRequest.
 */
class PackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            '*.parent_id'         => ['nullable', 'integer'],
            '*.type_id'           => ['required', 'integer', 'exists:_d_package_types,id'],
            '*.name'              => ['required', 'string', 'max:50'],
            '*.main_units_number' => ['required', 'numeric', 'min:0.001', 'decimal:0,3'],
            '*.package_count'     => ['required', 'integer', 'min:1'],
            '*.weight_netto'      => ['required', 'numeric', 'min:0'],
            '*.weight_brutto'     => ['required', 'numeric', 'min:0', 'gte:*.weight_netto'],
            '*.height'            => ['required', 'numeric', 'min:0'],
            '*.width'             => ['required', 'numeric', 'min:0'],
            '*.length'            => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            '*.parent_id'         => __('localization.sku_package_parent'),
            '*.type_id'           => __('localization.sku_package_type'),

            '*.name'              => __('localization.sku_create_add_paking_name'),
            '*.barcode'           => __('localization.sku_create_barcode'),
            '*.main_units_number' => __('localization.sku_create_measurement_unit'),
            '*.package_count'     => __('localization.sku_create_add_package_count'),

            '*.weight_netto'      => __('localization.sku_view_net_weight'),
            '*.weight_brutto'     => __('localization.sku_edit_gross_weight_label'),
            '*.height'            => __('localization.sku_create_height'),
            '*.width'             => __('localization.sku_create_width'),
            '*.length'            => __('localization.sku_create_add_paking_length'),
        ];
    }
}
