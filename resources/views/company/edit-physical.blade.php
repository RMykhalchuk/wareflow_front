@extends('layouts.admin')

@section('title', __('localization.company_edit_physical_title'))

@section('page-style')
    
@endsection

@section('content')
    <x-layout.container id="data_tab_1">
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/companies',
                            'name' => __('localization.company_edit_physical_breadcrumb_companies'),
                        ],
                        [
                            'url' => '/companies/' . $company->id,
                            'name' => __('localization.company_edit_physical_breadcrumb_view_company', [
                                'surname' => $company->company->surname,
                                'first_name' => $company->company->first_name,
                            ]),
                        ],
                        [
                            'name' => __('localization.company_edit_physical_breadcrumb_header', [
                                'surname' => $company->company->surname,
                                'first_name' => $company->company->first_name,
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
                    {{ __('localization.company_edit_physical_button_cancel') }}
                </button>

                <button
                    id="edit"
                    type="button"
                    data-id="{{ $company->id }}"
                    class="btn btn-primary t"
                >
                    {{ __('localization.company_edit_physical_button_save') }}
                </button>
            </div>
        </x-slot>

        <x-slot:slot>
            <x-page-title
                :title="__('localization.company_edit_physical_breadcrumb_header', [
                    'surname' => $company->company->surname,
                    'first_name' => $company->company->first_name,
                ])"
            />

            <div class="card mt-2">
                <div class="card-header">
                    <h4 class="card-title">
                        {{ __('localization.company_edit_physical_data_info_1_card_title') }}
                    </h4>
                </div>
                <div class="card-body my-25">
                    <!-- header section -->

                    <x-form.avatar-uploader
                        id="company-1"
                        name="company-1"
                        imageSrc="{{ $company->img_type ? '/files/uploads/company/image/' . $company->id . '.' . $company->img_type : asset('assets/icons/entity/company/default-empty-company.svg') }}"
                        :disabled="null"
                    />

                    <!--/ header section -->
                    <div class="mt-2 pt-50">
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="accountFirstName">
                                    {{ __('localization.company_edit_physical_data_info_1_first_name') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $company->company->first_name }}"
                                    id="first_name"
                                    name="name"
                                    required
                                    placeholder="{{ __('localization.company_edit_physical_data_info_1_first_name_placeholder') }}"
                                    data-msg="Please enter first name"
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="accountLastName">
                                    {{ __('localization.company_edit_physical_data_info_1_last_name') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $company->company->surname }}"
                                    id="last_name"
                                    name="surname"
                                    placeholder="{{ __('localization.company_edit_physical_data_info_1_last_name_placeholder') }}"
                                    required
                                    data-msg="Please enter last name"
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="accountPatronymic">
                                    {{ __('localization.company_edit_physical_data_info_1_patronymic') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $company->company->patronymic }}"
                                    id="patronymic"
                                    name="patronymic"
                                    required
                                    placeholder="{{ __('localization.company_edit_physical_data_info_1_patronymic_placeholder') }}"
                                    data-msg="Please enter patronymic"
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="fp-default">
                                    {{ __('localization.company_edit_physical_data_info_1_ipn') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    required
                                    id="ipn"
                                    value="{{ $company->ipn }}"
                                    name="ipn"
                                    oninput="limitInputToNumbers(this,10)"
                                    placeholder="{{ __('localization.company_edit_physical_data_info_1_ipn_placeholder') }}"
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="email">
                                    {{ __('localization.company_edit_physical_data_info_1_email') }}
                                </label>
                                <input
                                    type="email"
                                    class="form-control"
                                    value="{{ $company->email }}"
                                    id="email"
                                    name="email"
                                    required
                                    placeholder="{{ __('localization.company_edit_physical_data_info_1_email_placeholder') }}"
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="category">
                                    {{ __('localization.company_edit_physical_data_info_1_category') }}
                                </label>
                                <select
                                    class="hide-search form-select"
                                    id="category"
                                    data-id="{{ $company->category_id }}"
                                    data-dictionary="company_category"
                                    data-placeholder="{{ __('localization.company_edit_physical_data_info_1_category_placeholder') }}"
                                ></select>
                            </div>
                            <div id="private-data-message"></div>
                        </div>
                    </div>
                    <!--/ form -->
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">
                        {{ __('localization.company_edit_physical_data_info_2_title') }}
                    </h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <div class="mb-1">
                                <label class="form-label" for="country">
                                    {{ __('localization.company_edit_physical_data_info_2_country') }}
                                </label>
                                <select
                                    class="select2 form-select"
                                    data-id="{{ $company->address?->country?->id }}"
                                    data-dictionary="country"
                                    id="country"
                                    data-placeholder="{{ __('localization.company_edit_physical_data_info_2_country_placeholder') }}"
                                >
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <div class="mb-1">
                                <label class="form-label" for="city">
                                    {{ __('localization.company_edit_physical_data_info_2_settlement') }}
                                </label>
                                <select
                                    class="select2 form-select"
                                    id="city"
                                    data-id="{{ $company->address?->settlement_id }}"
                                    data-dictionary="settlement"
                                    data-placeholder="{{ __('localization.company_edit_physical_data_info_2_settlement_placeholder') }}"
                                >
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <div class="mb-1">
                                <label class="form-label" for="street">
                                    {{ __('localization.company_edit_physical_data_info_2_street') }}
                                </label>
                                <select
                                    class="select2 form-select"
                                    id="street"
                                    data-id="{{ $company->address?->street_id }}"
                                    data-dictionary="street"
                                    data-placeholder="{{ __('localization.company_edit_physical_data_info_2_street_placeholder') }}"
                                >
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="building_number">
                                {{ __('localization.company_edit_physical_data_info_2_building_number') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $company->address?->building_number }}"
                                id="building_number"
                                name="building_number"
                                required
                                autocomplete="off"
                                placeholder="{{ __('localization.company_edit_physical_data_info_2_building_number_placeholder') }}"
                            />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="flat">
                                {{ __('localization.company_edit_physical_data_info_2_apartment_number') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $company->address?->apartment_number }}"
                                id="flat"
                                name="flat"
                                required
                                autocomplete="off"
                                placeholder="{{ __('localization.company_edit_physical_data_info_2_apartment_number_placeholder') }}"
                            />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="gln">
                                {{ __('localization.company_edit_physical_data_info_2_gln') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="gln"
                                name="gln"
                                value="{{ $company->address?->gln }}"
                                oninput="limitInputToNumbers(this,13)"
                                required
                                autocomplete="off"
                                placeholder="{{ __('localization.company_edit_physical_data_info_2_gln_placeholder') }}"
                            />
                        </div>

                        <div id="address-message"></div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">
                        {{ __('localization.company_edit_physical_data_info_3_title') }}
                    </h4>
                </div>

                <div class="card-body my-25">
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="bank">
                                {{ __('localization.company_edit_physical_data_info_3_bank_label') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $company->bank }}"
                                id="bank"
                                placeholder="{{ __('localization.company_edit_physical_data_info_3_bank_label_placeholder') }}"
                                required
                            />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="iban">
                                {{ __('localization.company_edit_physical_data_info_3_iban_label') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $company->iban }}"
                                id="iban"
                                oninput="maskNumbersPlusLatin(this,29)"
                                placeholder="{{ __('localization.company_edit_physical_data_info_3_iban_label_placeholder') }}"
                                required
                            />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <label class="form-label" for="mfo">
                                {{ __('localization.company_edit_physical_data_info_3_mfo_label') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $company->mfo }}"
                                id="mfo"
                                required
                                oninput="limitInputToNumbers(this,6)"
                                placeholder="{{ __('localization.company_edit_physical_data_info_3_mfo_label_placeholder') }}"
                            />
                        </div>

                        <div class="col-12 col-sm-6 mb-1">
                            <div class="mb-1">
                                <label class="form-label" for="select2-hide-search">
                                    {{ __('localization.company_edit_physical_data_info_3_currency_label') }}
                                </label>
                                <select
                                    class="select2 form-select"
                                    data-id="{{ $company->currency }}"
                                    data-dictionary="currencies"
                                    id="currency"
                                    data-placeholder="{{ __('localization.company_edit_physical_data_info_3_currency_placeholder') }}"
                                >
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div id="requisite-message"></div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">
                        {{ __('localization.company_edit_physical_data_info_4_title') }}
                    </h4>
                </div>
                <div class="card-body my-25">
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-1">
                            <textarea
                                class="form-control"
                                id="about"
                                rows="5"
                                placeholder="{{ __('localization.company_edit_physical_data_info_4_about_label') }}"
                            >
{{ $company->about }}</textarea
                            >
                        </div>

                        <div id="about-message"></div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-layout.container>

    <!-- Модалка скасування  редагування -->
    <x-cancel-modal
        id="cancelEditPage"
        route="/companies/{{ $company->id }}"
        title="{{ __('localization.company_edit_physical_cancel_edit_page_title') }}"
        content="{!! __('localization.company_edit_physical_cancel_edit_page_message') !!}"
        cancel-text="{{ __('localization.company_edit_physical_cancel_edit_page_cancel_btn') }}"
        confirm-text="{{ __('localization.company_edit_physical_cancel_edit_page_confirm_btn') }}"
    />
@endsection

@section('page-script')
    <script src="{{ asset('js/jquery.maskedinput.min.js') }}"></script>
    <script type="module" src="{{ asset('assets/js/entity/company/company.js') }}"></script>
@endsection
