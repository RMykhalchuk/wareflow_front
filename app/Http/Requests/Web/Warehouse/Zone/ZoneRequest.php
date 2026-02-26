<?php

namespace App\Http\Requests\Web\Warehouse\Zone;

use App\Http\Requests\RequestJson;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * ZoneRequest.
 */
class ZoneRequest extends FormRequest
{
    use RequestJson;

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
            'color' => ['nullable','string'],
            'name' => ['required', 'string', 'max:20'],
            'has_temp' => ['required', 'boolean'],
            'temp_from' => ['nullable', 'numeric', 'lt:temp_to'],
            'temp_to' => ['nullable', 'numeric', 'gt:temp_from'],
            'has_humidity' => ['required', 'boolean'],
            'humidity_from' => ['nullable', 'numeric', 'lt:humidity_to'],
            'zone_type' => ['required', 'numeric'],
            'zone_subtype' => ['nullable', 'numeric'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'color'          => __('localization.warehouse.zone.color'),
            'name'           => __('localization.warehouse.zone.name'),

            'has_temp'       => __('localization.warehouse.zone.temperature_regime'),
            'temp_from'      => __('localization.warehouse.zone.temperature_regime'),
            'temp_to'        => __('localization.warehouse.zone.temperature_regime'),

            'has_humidity'   => __('localization.warehouse.zone.humidity'),
            'humidity_from'  => __('localization.warehouse.zone.humidity'),
            'humidity_to'    => __('localization.warehouse.zone.humidity'),
            'zone_type'  => __('localization.warehouse.zone_types_label'),
            'zone_subtype'    => __('localization.warehouse.zone_subtypes_label'),
        ];
    }
}
