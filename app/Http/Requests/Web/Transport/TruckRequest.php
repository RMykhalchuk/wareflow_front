<?php

namespace App\Http\Requests\Web\Transport;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

/**
 * TruckRequest.
 */
final class TruckRequest extends FormRequest
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
                File::types(['png', 'jpeg', 'jpg','gif'])
                    ->max(800)
            ],
            'mark' => 'required',
            'model' => 'required',
            'category' => 'required',
            'type' => 'required',
           // 'license_plate'=> ['required_if:registration_country,1', 'regex:/[АВСЕНІКМОРТХABCEHIKMOPTX]{2}\d{4}[АВСЕНІКМОРТХABCEHIKMOPTX]{2}/i'],
            'license_plate' => 'required',
            'registration_country' => 'required',
            'download_methods' => 'required',
            'manufacture_year' => 'required|digits:4',
            'company' => 'required',
            'driver' => 'required',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'volume' => 'required|numeric',
            'weight' => 'required|numeric',
            'capacity_eu' => 'required|integer',
            'capacity_am' => 'required|integer',
            'spending_empty' => 'required|numeric',
            'spending_full' => 'required|numeric',
            'carrying_capacity' => 'required|numeric',
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
            'download_methods'     => __('localization.transport_create_loading_method'),

            'manufacture_year'     => __('localization.transport_edit_manufacture_year'),
            'company'              => __('localization.transport_view_company_label'),
            'driver'               => __('localization.transport_create_default_driver'),

            'length'               => __('localization.transport_length'),
            'width'                => __('localization.transport_create_length'),
            'height'               => __('localization.transport_edit_height'),
            'volume'               => __('localization.transport_view_volume_label'),
            'weight'               => __('localization.transport_edit_weight'),

            'capacity_eu'          => __('localization.transport_create_capacity_eu'),
            'capacity_am'          => __('localization.transport_create_capacity_am'),
            'spending_empty'       => __('localization.transport_view_empty_spending_label'),
            'spending_full'        => __('localization.transport_view_full_spending_label'),
            'carrying_capacity'    => __('localization.transport_edit_carrying_capacity'),
        ];
    }
}
