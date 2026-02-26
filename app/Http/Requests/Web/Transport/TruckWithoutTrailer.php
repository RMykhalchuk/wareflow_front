<?php

namespace App\Http\Requests\Web\Transport;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

/**
 * TruckWithoutTrailer.
 */
final class TruckWithoutTrailer extends FormRequest
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
        return [
            'image' => ['nullable',
                File::types(['png', 'jpeg', 'jpg', 'gif'])
                    ->max(800)
            ],
            'mark' => 'required',
            'model' => 'required',
            'category' => 'required',
            'type' => 'required',
            //'license_plate'=> ['required_if:registration_country,1', 'regex:/[АВСЕНІКМОРТХABCEHIKMOPTX]{2}\d{4}[АВСЕНІКМОРТХABCEHIKMOPTX]{2}/i'],
            'license_plate' => 'required',
            'registration_country' => 'required',
            'manufacture_year' => 'required|digits:4',
            'company' => 'required',
            'driver' => 'required',
            'spending_empty' => 'required|numeric',
            'spending_full' => 'required|numeric'
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'image'                => __('localization.transport_view_image_alt'),

            'mark'                 => __('localization.transport_edit_mark'),
            'model'                => __('localization.transport_create_auto_model'),
            'category'             => __('localization.transport_create_category'),
            'type'                 => __('localization.transport_create_type'),

            'license_plate'        => __('localization.transport_create_license_plate'),
            'registration_country' => __('localization.transport_create_registration_country'),

            'manufacture_year'     => __('localization.transport_edit_manufacture_year'),
            'company'              => __('localization.transport_view_company_label'),
            'driver'               => __('localization.transport_create_default_driver'),

            'spending_empty'       => __('localization.transport_view_empty_spending_label'),
            'spending_full'        => __('localization.transport_view_full_spending_label'),
        ];
    }
}
