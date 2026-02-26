<?php

namespace App\Http\Requests\Web\Location;

use App\Http\Requests\RequestJson;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * LocationRequest.
 */
class LocationRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'company_id' => ['required', 'uuid', 'exists:companies,id'],
            'country_id' => ['required', 'integer', 'exists:_d_countries,id'],
            'settlement_id' => ['required', 'integer', 'exists:_d_settlements,id'],
            'street_info' => ['required', 'array'],
            'url' => [
                'required',
                'string',
                'max:2048',
                'url',
                function ($attribute, $value, $fail) {
                    $validDomains = [
                        'google.com/maps',
                        'maps.google.com',
                        'goo.gl/maps',
                        'maps.app.goo.gl',
                    ];

                    $isValid = false;

                    foreach ($validDomains as $domain) {
                        if (str_contains($value, $domain)) {
                            $isValid = true;
                            break;
                        }
                    }

                    if (!$isValid) {
                        $fail('Link should be from Google Maps.');
                    }
                },
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name'          => __('localization.location.create.block_1.name_label'),
            'company_id'    => __('localization.location.create.block_1.company_label'),
            'country_id'    => __('localization.location.create.block_1.country_label'),
            'settlement_id' => __('localization.location.create.block_1.settlement_label'),
            'street_info'   => __('localization.location.create.block_1.street_label'),
            'url'           => __('localization.location.create.block_2.coordinates_label'),
        ];
    }
}
