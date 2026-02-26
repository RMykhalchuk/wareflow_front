<?php

namespace App\Http\Requests\Web\Warehouse\Sector;

use App\Http\Requests\RequestJson;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * SectorRequest.
 */
class SectorRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:30'],
            'has_temp' => ['required', 'boolean'],
            'temp_from' => ['nullable', 'numeric', 'lt:temp_to'],
            'temp_to' => ['nullable', 'numeric', 'gt:temp_from'],
            'has_humidity' => ['required', 'boolean'],
            'humidity_from' => ['nullable', 'numeric', 'lt:humidity_to'],
            'humidity_to' => ['nullable', 'numeric', 'gt:humidity_from'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'color'          => __('localization.warehouse.sector.color'),
            'name'           => __('localization.warehouse.sector.name'),

            'has_temp'       => __('localization.warehouse.sector.temperature_regime'),
            'temp_from'      => __('localization.warehouse.sector.temperature_regime'),
            'temp_to'        => __('localization.warehouse.sector.temperature_regime'),

            'has_humidity'   => __('localization.warehouse.sector.humidity'),
            'humidity_from'  => __('localization.warehouse.sector.humidity'),
            'humidity_to'    => __('localization.warehouse.sector.humidity'),
        ];
    }
}
