<?php

namespace App\Http\Requests\Web\Transport;

use App\Http\Requests\RequestJson;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * EquipmentRequest.
 */
final class EquipmentRequest extends FormRequest
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
            'type' => 'required',
        //    'license_plate'=> ['required_if:registration_country,1',
            // 'regex:/[АВСЕНІКМОРТХABCEHIKMOPTX]{2}\d{4}[АВСЕНІКМОРТХABCEHIKMOPTX]{2}/i'],
            'license_plate' => 'required',
            'registration_country' => 'required',
            'download_methods' => 'required',
            'manufacture_year' => 'required|digits:4',
            'company' => 'required',
            'transport' => 'required',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'volume' => 'required|numeric',
            'capacity_eu' => 'required|integer',
            'capacity_am' => 'required|integer',
            'carrying_capacity' => 'required|numeric',
        ];
    }

    /**
     * @param Validator $validator
     * @return never
     */
    #[\Override]
    /**
     * @return never
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(response()->json([
            'errors' => $errors
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
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
            'type'                 => __('localization.transport_create_type'),

            'license_plate'        => __('localization.transport_create_license_plate'),
            'registration_country' => __('localization.transport_create_registration_country'),
            'download_methods'     => __('localization.transport_create_loading_method'),

            'manufacture_year'     => __('localization.transport_edit_manufacture_year'),
            'company'              => __('localization.transport_view_company_label'),
            'transport'            => __('localization.transport_index_title'),

            'length'               => __('localization.transport_length'),
            'width'                => __('localization.transport_create_length'),
            'height'               => __('localization.transport_edit_height'),
            'volume'               => __('localization.transport_view_volume_label'),

            'capacity_eu'          => __('localization.transport_create_capacity_eu'),
            'capacity_am'          => __('localization.transport_create_capacity_am'),
            'carrying_capacity'    => __('localization.transport_edit_carrying_capacity'),
        ];
    }
}
