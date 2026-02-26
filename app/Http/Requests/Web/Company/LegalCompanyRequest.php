<?php

namespace App\Http\Requests\Web\Company;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * LegalCompanyRequest.
 */
final class LegalCompanyRequest extends FormRequest
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
        $company = $this->route('company');

        $isUstDocMissing = empty($company->company->install_doctype);
        $isRegistrationDocMissing = empty($company->company->reg_doctype);

        $isLegalType = $this->input('registration_doc') !== null;

        $shouldRequireUstDoc = $isUstDocMissing && $isLegalType;
        $shouldRequireRegistrationDoc = $isRegistrationDocMissing && $isLegalType;

        return [
            'email' => 'nullable|email',
            'edrpou' => 'nullable|string|max:15',
            'company_name' => 'required|string|max:35',
            'legal_entity' => 'nullable|string|max:20',
            'ipn' => 'nullable|digits:10',
            'country' => 'nullable',
            'city' => 'nullable',
            'street' => 'nullable',
            'building_number' => 'nullable',
            'gln' => 'nullable|digits:13|size:13',
            'u_country' => 'nullable',
            'u_city' => 'nullable',
            'u_street' => 'nullable',
            'u_building_number' => 'nullable',
            'u_gln' => 'nullable|digits:13|size:13',
            'bank' => 'nullable|string',
            'iban' => 'nullable|string|size:29',
            'mfo' => 'nullable|digits:6|size:6',
            'currency' => 'nullable|string|max:5',
            'about' => 'nullable|string|max:500',
            'ust_doc' => [
                Rule::requiredIf($shouldRequireUstDoc),
                Rule::when($this->hasFile('ust_doc') || $shouldRequireUstDoc, [
                    'file'
                ]),
            ],

            'registration_doc' => [
                Rule::requiredIf($shouldRequireRegistrationDoc),
                Rule::when($this->hasFile('registration_doc') || $shouldRequireRegistrationDoc, [
                    'file'
                ]),
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'email'             => __('localization.company_view_card_info_email'),
            'edrpou'            => __('localization.company_view_card_info_edrpou'),
            'company_name'      => __('localization.company_create_data_tab_2_company_name'),
            'legal_entity'      => __('localization.company_create_data_tab_2_legal_entity_type'),
            'ipn'               => __('localization.company_create_data_tab_2_ipn'),

            'country'           => __('localization.company_create_data_tab_2_country'),
            'city'              => __('localization.company_create_data_tab_2_city'),
            'street'            => __('localization.company_create_data_tab_2_street'),
            'building_number'   => __('localization.company_create_data_tab_2_building_number'),
            'gln'               => __('localization.company_create_data_tab_2_gln'),

            'u_country'         => __('localization.company_create_data_tab_2_country'),
            'u_city'            => __('localization.company_create_data_tab_2_city'),
            'u_street'          => __('localization.company_create_data_tab_2_street'),
            'u_building_number' => __('localization.company_create_data_tab_2_building_number'),
            'u_gln'             => __('localization.company_create_data_tab_2_gln'),

            'bank'              => __('localization.company_create_data_tab_2_bank'),
            'iban'              => __('localization.company_create_data_tab_2_iban'),
            'mfo'               => __('localization.company_create_data_tab_2_mfo'),
            'currency'          => __('localization.company_create_data_tab_2_currency'),
            'about'             => __('localization.company_create_data_tab_2_about'),

            'ust_doc'           => __('localization.company_create_data_tab_2_ust_doc'),
            'registration_doc'  => __('localization.company_edit_legal_data_info_2_registration_certificate_label'),
        ];
    }
}
