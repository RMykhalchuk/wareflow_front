<?php

namespace App\Http\Requests\Web\Company;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;

/**
 * PhysicalCompanyRequest.
 */
final class PhysicalCompanyRequest extends FormRequest
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
            'email' => 'nullable|email',
            'firstName' => 'required|string|max:15',
            'lastName' => 'required|string|max:15',
            'patronymic' => 'nullable|string|max:20',
            'ipn' => 'nullable|digits:10|size:10',
            'country' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:50',
            'street' => 'nullable|string|max:80',
            'building_number' => 'nullable',
            'gln' => 'nullable|digits:13|size:13',
            'bank' => 'nullable|string',
            'iban' => 'nullable|string|size:29',
            'mfo' => 'nullable|digits:6|size:6',
            'currency' => 'nullable|string|max:5',
            'about' => 'nullable|string|max:500'
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'email'           => __('localization.company_view_card_info_email'),

            'firstName'       => __('localization.company_edit_physical_data_info_1_first_name'),
            'lastName'        => __('localization.company_edit_physical_data_info_1_last_name'),
            'patronymic'      => __('localization.company_edit_physical_data_info_1_patronymic'),

            'ipn'             => __('localization.company_view_card_info_ipn_number'),

            'country'         => __('localization.company_create_data_tab_1_country'),
            'city'            => __('localization.company_create_data_tab_2_city'),
            'street'          => __('localization.company_create_data_tab_2_street'),
            'building_number' => __('localization.company_create_data_tab_2_building_number'),
            'gln'             => __('localization.company_create_data_tab_2_gln'),

            'bank'            => __('localization.company_create_data_tab_2_bank'),
            'iban'            => __('localization.company_create_data_tab_2_iban'),
            'mfo'             => __('localization.company_create_data_tab_2_mfo'),
            'currency'        => __('localization.company_create_data_tab_2_currency'),
            'about'           => __('localization.company_create_data_tab_2_about'),
        ];
    }
}
