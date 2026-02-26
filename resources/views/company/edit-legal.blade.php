@extends('layouts.admin')

@section('title', __('localization.company_edit_legal_title'))

@section('page-style')
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>

    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}"
    />

    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}"
    />
@endsection

@section('content')
    <x-layout.container id="create-company-page">
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/companies',
                            'name' => __('localization.company_edit_legal_breadcrumb_header_companies_link'),
                        ],
                        [
                            'url' => '/companies/' . $company->id,
                            'name' => __(
                                'localization.company_edit_legal_breadcrumb_header_view_company_link',
                                ['company_name' => $company->company->name],
                            ),
                        ],
                        [
                            'name' => __('localization.company_edit_legal_breadcrumb_header_edit_company', [
                                'company_name' => $company->company->name,
                            ]),
                        ],
                    ],
                ]
            )
            <div class="d-flex justify-content-between">
                <button
                    class="btn btn-flat-secondary float-start mr-1"
                    data-bs-toggle="modal"
                    data-bs-target="#cancelEditPage"
                >
                    {{ __('localization.company_edit_legal_breadcrumb_header_cancel_button') }}
                </button>

                <button
                    id="edit_2"
                    type="button"
                    data-id="{{ $company->id }}"
                    class="btn btn-primary t"
                >
                    {{ __('localization.company_edit_legal_breadcrumb_header_save_button') }}
                </button>
            </div>
        </x-slot>

        <x-slot:slot>
            <x-page-title
                :title="__('localization.company_edit_legal_breadcrumb_header_edit_company', [
                    'company_name' => $company->company->name
                ])"
            />

            <div class="card mt-2">
                <div class="card-header">
                    <h4 class="card-title">
                        {{ __('localization.company_edit_legal_data_info_1_main_data') }}
                    </h4>
                </div>
                <div class="card-body my-25">
                    <!-- header section -->

                    <x-form.avatar-uploader
                        id="company-2"
                        name="company-2"
                        imageSrc="{{ $company->img_type ? '/files/uploads/company/image/' . $company->id . '.' . $company->img_type : asset('assets/icons/entity/company/default-empty-company.svg') }}"
                        :disabled="null"
                    />

                    <!--/ header section -->
                    <div class="mt-2 pt-50">
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="company_name">
                                    {{ __('localization.company_edit_legal_data_info_1_company_name_label') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="company_name"
                                    value="{{ $company->company->name }}"
                                    required
                                    placeholder="{{ __('localization.company_edit_legal_data_info_1_company_name_placeholder') }}"
                                    data-msg="Please enter patronymic"
                                />
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="legal_entity">
                                    {{ __('localization.company_edit_legal_data_info_1_legal_entity_label') }}
                                </label>
                                <select
                                    class="hide-search form-select"
                                    id="legal_entity"
                                    data-placeholder="{{ __('localization.company_edit_legal_data_info_1_legal_entity_placeholder') }}"
                                >
                                    <option value=""></option>

                                    @php
                                        $positionNames = [
                                            'type_1' => 'company_edit_legal_data_info_1_legal_entity_type_1',
                                            'type_2' => 'company_edit_legal_data_info_1_legal_entity_type_2',
                                            'type_3' => 'company_edit_legal_data_info_1_legal_entity_type_3',
                                        ];
                                    @endphp

                                    @foreach ($legalTypes as $type)
                                        <option
                                            value="{{ $type->id }}"
                                            {{ $company?->company_type_id === $type->id ? 'selected' : '' }}
                                        >
                                            {{ $type->label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="edrpou">
                                    {{ __('localization.company_edit_legal_data_info_1_edrpou_label') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="edrpou"
                                    name="edrpou"
                                    oninput="limitInputToNumbers(this,8)"
                                    value="{{ $company->company->edrpou }}"
                                    required
                                    placeholder="{{ __('localization.company_edit_legal_data_info_1_edrpou_placeholder') }}"
                                    data-msg="Please enter first name"
                                />
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="email_2">
                                    {{ __('localization.company_edit_legal_data_info_1_email_label') }}
                                </label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="email_2"
                                    name="email"
                                    value="{{ $company->email }}"
                                    required
                                    data-msg="Please enter last name"
                                    placeholder="{{ __('localization.company_edit_legal_data_info_1_email_placeholder') }}"
                                />
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="company_category">
                                    {{ __('localization.company_edit_legal_data_info_1_company_category_label') }}
                                </label>
                                <select
                                    class="hide-search form-select"
                                    id="company_category"
                                    data-id="{{ $company->category_id }}"
                                    data-dictionary="company_category"
                                    data-placeholder="{{ __('localization.company_edit_legal_data_info_1_company_category_placeholder') }}"
                                ></select>
                            </div>

                            <div id="private-data-message2"></div>
                        </div>
                    </div>
                    <!--/ form -->
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header justify-content-normal">
                    <h4 class="card-title">
                        {{ __('localization.company_edit_legal_data_info_2_pdv_payer') }}
                    </h4>
                    <input
                        type="checkbox"
                        {{ $company->ipn ? 'checked' : '' }}
                        class="form-check-input"
                        id="pdv"
                    />
                </div>

                <div class="card-body my-25">
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="ipn_2">
                                {{ __('localization.company_edit_legal_data_info_2_ipn_number_label') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $company->ipn }}"
                                id="ipn_2"
                                placeholder="{{ __('localization.company_edit_legal_data_info_2_ipn_number_placeholder') }}"
                                oninput="limitInputToNumbers(this,10)"
                                {{ $company->ipn ? '' : 'disabled' }}
                            />
                        </div>
                        <div class="col-12 col-sm-6 mb-1">
                            <label for="reg_doc" class="form-label">
                                {{ __('localization.company_edit_legal_data_info_2_registration_certificate_label') }}
                            </label>

                            @if ($company->company?->reg_docname)
                                <small class="text-muted d-block mb-1">
                                    {{ $company->company->reg_docname }}
                                </small>
                            @endif

                            <input
                                class="form-control"
                                type="file"
                                id="reg_doc"
                                {{ $company->ipn ? '' : 'disabled' }}
                            />
                        </div>

                        <div class="col-12 offset-sm-6 col-sm-6 mb-1">
                            <label for="ust_doc" class="form-label">
                                {{ __('localization.company_edit_legal_data_info_2_constitutional_documents_label') }}
                            </label>

                            @if ($company->company?->install_docname)
                                <small class="text-muted d-block mb-1">
                                    {{ $company->company->install_docname }}
                                </small>
                            @endif

                            <input
                                class="form-control"
                                type="file"
                                id="ust_doc"
                                {{ $company->ipn ? '' : 'disabled' }}
                            />
                        </div>

                        <div id="pdv-message"></div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">
                        {{ __('localization.company_edit_legal_data_info_3_actual_address') }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <div class="mb-1">
                                <label class="form-label" for="country_2">
                                    {{ __('localization.company_edit_legal_data_info_3_country_label') }}
                                </label>
                                <select
                                    class="select2 form-select"
                                    id="country_2"
                                    data-id="{{ $company->address?->country?->id ?? null }}"
                                    data-dictionary="country"
                                    data-placeholder="{{ __('localization.company_edit_legal_data_info_3_country_placeholder') }}"
                                >
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <div class="mb-1">
                                <label class="form-label" for="city_2">
                                    {{ __('localization.company_edit_legal_data_info_3_settlement_label') }}
                                </label>
                                <select
                                    class="select2 form-select"
                                    id="city_2"
                                    data-placeholder="{{ __('localization.company_edit_legal_data_info_3_settlement_placeholder') }}"
                                    data-id="{{ $company->address?->settlement_id }}"
                                    data-dictionary="settlement"
                                >
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <div class="mb-1">
                                <label class="form-label" for="street_2">
                                    {{ __('localization.company_edit_legal_data_info_3_street_label') }}
                                </label>
                                <select
                                    class="select2 form-select"
                                    id="street_2"
                                    data-placeholder="{{ __('localization.company_edit_legal_data_info_3_street_placeholder') }}"
                                    data-id="{{ $company->address?->street_id }}"
                                    data-dictionary="street"
                                >
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="u_building_number">
                                {{ __('localization.company_edit_legal_data_info_3_building_number_label') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $company->address?->building_number }}"
                                id="u_building_number"
                                name="building_number"
                                required
                                autocomplete="off"
                                placeholder="{{ __('localization.company_edit_legal_data_info_3_building_number_placeholder') }}"
                            />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="flat_2">
                                {{ __('localization.company_edit_legal_data_info_3_apartment_number_label') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $company->address?->apartment_number }}"
                                id="flat_2"
                                name="flat"
                                required
                                autocomplete="off"
                                placeholder="{{ __('localization.company_edit_legal_data_info_3_apartment_number_placeholder') }}"
                            />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="gln_2">
                                {{ __('localization.company_edit_legal_data_info_3_gln_label') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $company->address?->gln }}"
                                id="gln_2"
                                name="gln"
                                oninput="limitInputToNumbers(this,13)"
                                required
                                autocomplete="off"
                                placeholder="{{ __('localization.company_edit_legal_data_info_3_gln_placeholder') }}"
                            />
                        </div>

                        <div id="address-message2"></div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">
                        {{ __('localization.company_edit_legal_data_info_4_legal_address') }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <div class="mb-1">
                                <label class="form-label" for="u_country">
                                    {{ __('localization.company_edit_legal_data_info_4_country_label') }}
                                </label>
                                <select
                                    class="select2 form-select"
                                    id="u_country"
                                    data-id="{{ $company->company->address?->country?->id ?? null }}"
                                    data-dictionary="country"
                                    data-placeholder="{{ __('localization.company_edit_legal_data_info_4_country_placeholder') }}"
                                >
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <div class="mb-1">
                                <label class="form-label" for="u_city">
                                    {{ __('localization.company_edit_legal_data_info_4_settlement_label') }}
                                </label>
                                <select
                                    class="select2 form-select"
                                    id="u_city"
                                    data-placeholder="{{ __('localization.company_edit_legal_data_info_4_settlement_placeholder') }}"
                                    data-id="{{ $company->company->address?->settlement_id ?? null }}"
                                    data-dictionary="settlement"
                                >
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <div class="mb-1">
                                <label class="form-label" for="u_street">
                                    {{ __('localization.company_edit_legal_data_info_4_street_label') }}
                                </label>
                                <select
                                    class="select2 form-select"
                                    id="u_street"
                                    data-placeholder="{{ __('localization.company_edit_legal_data_info_4_street_placeholder') }}"
                                    data-id="{{ $company->company->address?->street_id ?? null }}"
                                    data-dictionary="street"
                                >
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="building_number_2">
                                {{ __('localization.company_edit_legal_data_info_4_building_number_label') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $company->company->address?->building_number ?? null }}"
                                id="building_number_2"
                                name="building_number"
                                required
                                autocomplete="off"
                                placeholder="{{ __('localization.company_edit_legal_data_info_4_building_number_placeholder') }}"
                            />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="u_flat">
                                {{ __('localization.company_edit_legal_data_info_4_apartment_number_label') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $company->company->address?->apartment_number ?? null }}"
                                id="u_flat"
                                name="flat"
                                required
                                autocomplete="off"
                                placeholder="{{ __('localization.company_edit_legal_data_info_4_apartment_number_placeholder') }}"
                            />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="u_gln">
                                {{ __('localization.company_edit_legal_data_info_4_gln_label') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="u_gln"
                                value="{{ $company->company->address?->gln ?? null }}"
                                oninput="limitInputToNumbers(this,13)"
                                name="gln"
                                required
                                autocomplete="off"
                                placeholder="{{ __('localization.company_edit_legal_data_info_4_gln_placeholder') }}"
                            />
                        </div>

                        <div id="u-address-message"></div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">
                        {{ __('localization.company_edit_legal_data_info_5_requisites') }}
                    </h4>
                </div>

                <div class="card-body my-25">
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="bank_2">
                                {{ __('localization.company_edit_legal_data_info_5_bank_label') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $company->bank }}"
                                id="bank_2"
                                placeholder="{{ __('localization.company_edit_legal_data_info_5_bank_placeholder') }}"
                                required
                            />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="iban_2">
                                {{ __('localization.company_edit_legal_data_info_5_iban_label') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $company->iban }}"
                                id="iban_2"
                                oninput="maskNumbersPlusLatin(this,29)"
                                placeholder="{{ __('localization.company_edit_legal_data_info_5_iban_placeholder') }}"
                                required
                            />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="mfo_2">
                                {{ __('localization.company_edit_legal_data_info_5_mfo_label') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $company->mfo }}"
                                placeholder="{{ __('localization.company_edit_legal_data_info_5_mfo_placeholder') }}"
                                id="mfo_2"
                                oninput="limitInputToNumbers(this,6)"
                                required
                            />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <div class="mb-1">
                                <label class="form-label" for="currency_u">
                                    {{ __('localization.company_edit_legal_data_info_5_currency_label') }}
                                </label>
                                <select
                                    class="select2 form-select"
                                    id="currency_u"
                                    data-id="{{ $company->currency }}"
                                    data-dictionary="currencies"
                                    data-placeholder="{{ __('localization.company_edit_legal_data_info_5_currency_placeholder') }}"
                                ></select>
                            </div>
                        </div>

                        <div id="requisite-message2"></div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">
                        {{ __('localization.company_edit_legal_data_info_6_about_title') }}
                    </h4>
                </div>
                <div class="card-body my-25">
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <textarea
                                class="form-control"
                                id="about_2"
                                rows="5"
                                placeholder="{{ __('localization.company_edit_legal_data_info_6_about_placeholder') }}"
                            >
{{ $company->about }}</textarea
                            >
                        </div>

                        <div id="about_company_message_2"></div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-layout.container>

    <!-- Модалка скасування  редагування -->
    <x-cancel-modal
        id="cancelEditPage"
        route="/companies/{{ $company->id }}"
        title="{{ __('localization.company_edit_legal_cancel_edit_page_title') }}"
        content="{!! __('localization.company_edit_legal_cancel_edit_page_message') !!}"
        cancel-text="{{ __('localization.company_edit_legal_cancel_edit_page_cancel_button') }}"
        confirm-text="{{ __('localization.company_edit_legal_cancel_edit_page_confirm_button') }}"
    />
@endsection

@section('page-script')
    <script src="{{ asset('js/jquery.maskedinput.min.js') }}"></script>
    <script type="module" src="{{ asset('assets/js/entity/company/company.js') }}"></script>

    <script>
        @if($company->ipn)
        const fileInput = document.querySelector('#reg_doc');

        const myFile = new File([''], '{!! $company->company->name.'.'.$company->company->reg_doctype !!}', {
            type: 'text/plain',
            lastModified: new Date(),
        });

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(myFile);
        fileInput.files = dataTransfer.files;

        const fileInput2 = document.querySelector('#ust_doc');

        const myFile2 = new File([''], '{!! $company->company->name.'.'.$company->company->install_doctype !!}', {
            type: 'text/plain',
            lastModified: new Date(),
        });

        const dataTransfer2 = new DataTransfer();
        dataTransfer2.items.add(myFile2);
        fileInput2.files = dataTransfer2.files;
        @endif
        let company_id = {!! $company->id !!};
    </script>
@endsection
