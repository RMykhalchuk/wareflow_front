@extends('layouts.admin')

@section('title', __('localization.company_create_title'))

@section('page-style')
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.6/css/intlTelInput.css"
    />

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
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.0/cropper.min.css"
        integrity="sha512-gNSHyKCA9X3fCDdTd5UxyNaSznSyGtR9pwf5YwSp7haDRz6Gqor0nY20POCYLseXq5n/FGAEogNp7G0d56d3jg=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />

    <style>
        .label {
            cursor: pointer;
        }

        .progress {
            display: none;
            margin-bottom: 1rem;
        }

        .img-container img {
            max-width: 100%;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
    </style>
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
                            'name' => __('localization.company_create_breadcrumb_header_companies'),
                        ],
                        ['name' => __('localization.company_create_breadcrumb_header_add_new_company')],
                    ],
                ]
            )
            <div class="d-flex justify-content-between">
                <button id="save" type="button" class="btn btn-primary saveBtn-1">
                    {{ __('localization.company_create_breadcrumb_header_save_button') }}
                </button>
                <button id="save_2" type="button" class="btn btn-primary d-none saveBtn-2">
                    {{ __('localization.company_create_breadcrumb_header_save_button') }}
                </button>
            </div>
        </x-slot>

        <x-slot:slot>
            <div class="d-flex mx-2">
                <div class="card radio-card tab-active tabsActiveStatus tab1">
                    <div class="card-body" id="tab_1" data-tab="1">
                        <div class="text-center">
                            <img
                                src="{{ asset('assets/icons/entity/company/default-user-logo-company.svg') }}"
                                id="tab-icon-1"
                                class="tab-filter"
                            />
                        </div>
                        <div class="text-center">
                            <span class="f-15 fw-6">
                                {{ __('localization.company_create_tabs_active_status_individual') }}
                            </span>
                        </div>
                        <div class="text-center">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="tabs"
                                value="tab1"
                                checked
                            />
                        </div>
                    </div>
                </div>
                <div class="card radio-card tabsActiveStatus tab2" style="margin-left: 35px">
                    <div class="card-body" id="tab_2" data-tab="2">
                        <div class="text-center">
                            <img
                                src="{{ asset('assets/icons/entity/company/office-company.svg') }}"
                                id="tab-icon-2"
                            />
                        </div>
                        <div class="text-center">
                            <span class="f-15 fw-6">
                                {{ __('localization.company_create_tabs_active_status_legal_entity') }}
                            </span>
                        </div>
                        <div class="text-center">
                            <input class="form-check-input" type="radio" name="tabs" value="tab2" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="mx-2" id="data_tab_1">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            {{ __('localization.company_create_data_tab_1_main_data') }}
                        </h4>
                    </div>
                    <div class="card-body my-25">
                        <!-- header section -->

                        <x-form.avatar-uploader
                            id="company-1"
                            name="company-1"
                            :imageSrc="asset('assets/icons/entity/company/default-empty-company.svg')"
                            :disabled="null"
                        />

                        {{-- todo --}}
                        {{-- <div class="d-flex"> --}}
                        {{-- <label class="label" data-toggle="tooltip" title="Change your avatar"> --}}
                        {{-- <img src="{{asset('assets/icons/entity/company/default-empty-company.svg') }}" id="avatar" --}}
                        {{-- class="uploadedAvatar rounded me-50" alt="profile image" height="100" width="100"> --}}
                        {{-- <input type="file" class="sr-only" id="input" name="image" accept="image/*"> --}}
                        {{-- </label> --}}
                        {{-- <!-- upload and reset button --> --}}
                        {{-- <div class="d-flex align-items-end mb-1 ms-1"> --}}
                        {{-- <div> --}}
                        {{-- <label for="company-upload1-cropper" --}}
                        {{-- class="btn btn-sm btn-primary mb-75 me-75 waves-effect waves-float waves-light">Завантажити</label> --}}
                        {{-- <input type="file" id="company-upload1-cropper" name="avatar" hidden="" --}}
                        {{-- accept="image/jpeg, image/png, image/gif"> --}}

                        {{-- <button type="submit" id="company-reset" --}}
                        {{-- class="btn btn-sm btn-outline-secondary mb-75 waves-effect">Видалити --}}
                        {{-- </button> --}}
                        {{-- <p class="mb-0">Формати JPG, GIF або PNG</p> --}}
                        {{-- <p class="mb-0">Розмір не більше 800kB </p> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}

                        {{-- <!--/ upload and reset button --> --}}
                        {{-- </div> --}}

                        {{-- <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" --}}
                        {{-- aria-hidden="true"> --}}
                        {{-- <div class="modal-dialog" role="document"> --}}
                        {{-- <div class="modal-content"> --}}
                        {{-- <div class="modal-header"> --}}
                        {{-- <h5 class="modal-title" id="modalLabel">Crop the image</h5> --}}
                        {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> --}}
                        {{-- <span aria-hidden="true">&times;</span> --}}
                        {{-- </button> --}}
                        {{-- </div> --}}
                        {{-- <div class="modal-body"> --}}
                        {{-- <div class="img-container"> --}}
                        {{-- <img id="image" src="https://avatars0.githubusercontent.com/u/3456749"> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}
                        {{-- <div class="modal-footer"> --}}
                        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel --}}
                        {{-- </button> --}}
                        {{-- <button type="button" class="btn btn-primary" id="crop">Crop</button> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}

                        <!--/ header section -->
                        <div class="mt-2 pt-50">
                            <div class="row">
                                <div class="col-12 col-sm-6 mb-1">
                                    <label class="form-label" for="accountFirstName">
                                        {{ __('localization.company_create_data_tab_1_first_name') }}
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="first_name"
                                        name="name"
                                        required
                                        placeholder="{{ __('localization.company_create_data_tab_1_first_name_placeholder') }}"
                                        data-msg="Please enter first name"
                                    />
                                </div>

                                <div class="col-12 col-sm-6 mb-1">
                                    <label class="form-label" for="accountLastName">
                                        {{ __('localization.company_create_data_tab_1_last_name') }}
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="last_name"
                                        name="surname"
                                        placeholder="{{ __('localization.company_create_data_tab_1_last_name_placeholder') }}"
                                        required
                                        data-msg="Please enter last name"
                                    />
                                </div>

                                <div class="col-12 col-sm-6 mb-1">
                                    <label class="form-label" for="accountPatronymic">
                                        {{ __('localization.company_create_data_tab_1_patronymic') }}
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="patronymic"
                                        name="patronymic"
                                        required
                                        placeholder="{{ __('localization.company_create_data_tab_1_patronymic_placeholder') }}"
                                        data-msg="Please enter patronymic"
                                    />
                                </div>
                                <div class="col-12 col-sm-6 mb-1">
                                    <label class="form-label" for="fp-default">
                                        {{ __('localization.company_create_data_tab_1_ipn') }}
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        required
                                        id="ipn"
                                        name="ipn"
                                        oninput="limitInputToNumbers(this,10)"
                                        placeholder="{{ __('localization.company_create_data_tab_1_ipn_placeholder') }}"
                                    />
                                </div>
                                <div class="col-12 col-sm-6 mb-1">
                                    <label class="form-label" for="email">
                                        {{ __('localization.company_create_data_tab_1_email') }}
                                    </label>
                                    <input
                                        type="email"
                                        class="form-control"
                                        id="email"
                                        name="email"
                                        required
                                        placeholder="{{ __('localization.company_create_data_tab_1_email_placeholder') }}"
                                    />
                                </div>
                                {{-- todo         поле в бд не заповнюється category_id дані записуюються все як має бути --}}
                                <div class="col-12 col-sm-6 mb-1">
                                    <label class="form-label" for="select2-hide-search">
                                        {{ __('localization.company_create_data_tab_1_company_category') }}
                                    </label>
                                    <select
                                        class="hide-search form-select"
                                        id="category"
                                        data-dictionary="company_category"
                                        data-placeholder="{{ __('localization.company_create_data_tab_1_company_category_placeholder') }}"
                                    >
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="mt-1" id="private-data-message"></div>
                            </div>
                        </div>
                        <!--/ form -->
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title">
                            {{ __('localization.company_create_data_tab_1_address') }}
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-1">
                                <div class="mb-1">
                                    <label class="form-label" for="select2-hide-search">
                                        {{ __('localization.company_create_data_tab_1_country') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="country"
                                        id="select2-hide-search"
                                        data-dictionary="country"
                                        data-placeholder="{{ __('localization.company_create_data_tab_1_country_placeholder') }}"
                                    >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <div class="mb-1">
                                    <label class="form-label" for="select2-hide-search">
                                        {{ __('localization.company_create_data_tab_1_city') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="city"
                                        data-dictionary="settlement"
                                        data-placeholder="{{ __('localization.company_create_data_tab_1_city_placeholder') }}"
                                    >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <div class="mb-1">
                                    <label class="form-label" for="select2-hide-search">
                                        {{ __('localization.company_create_data_tab_1_street') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="street"
                                        data-dictionary="street"
                                        data-placeholder="{{ __('localization.company_create_data_tab_1_street_placeholder') }}"
                                    >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="code">
                                    {{ __('localization.company_create_data_tab_1_building_number') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="building_number"
                                    name="building_number"
                                    required
                                    autocomplete="off"
                                    placeholder="{{ __('localization.company_create_data_tab_1_building_number_placeholder') }}"
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="code">
                                    {{ __('localization.company_create_data_tab_1_flat') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="flat"
                                    name="flat"
                                    required
                                    autocomplete="off"
                                    placeholder="{{ __('localization.company_create_data_tab_1_flat_placeholder') }}"
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="code">
                                    {{ __('localization.company_create_data_tab_1_gln') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="gln"
                                    name="gln"
                                    required
                                    autocomplete="off"
                                    oninput="limitInputToNumbers(this,13)"
                                    placeholder="{{ __('localization.company_create_data_tab_1_gln_placeholder') }}"
                                />
                            </div>

                            <div id="address-message"></div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title">
                            {{ __('localization.company_create_data_tab_1_requisites') }}
                        </h4>
                    </div>

                    <div class="card-body my-25">
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="bank">
                                    {{ __('localization.company_create_data_tab_1_bank') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="bank"
                                    placeholder="{{ __('localization.company_create_data_tab_1_bank_placeholder') }}"
                                    required
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="iban">
                                    {{ __('localization.company_create_data_tab_1_iban') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="iban"
                                    placeholder="{{ __('localization.company_create_data_tab_1_iban_placeholder') }}"
                                    oninput="maskNumbersPlusLatin(this,29)"
                                    required
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="mfo">
                                    {{ __('localization.company_create_data_tab_1_mfo') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="mfo"
                                    required
                                    placeholder="{{ __('localization.company_create_data_tab_1_mfo_placeholder') }}"
                                    oninput="limitInputToNumbers(this,6)"
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <div class="mb-1">
                                    <label class="form-label" for="select2-hide-search">
                                        {{ __('localization.company_create_data_tab_1_currency') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="currency"
                                        data-dictionary="currencies"
                                        data-placeholder="{{ __('localization.company_create_data_tab_1_currency_placeholder') }}"
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
                            {{ __('localization.company_create_data_tab_1_about') }}
                        </h4>
                    </div>
                    <div class="card-body my-25">
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-1">
                                <textarea
                                    class="form-control"
                                    id="about"
                                    rows="5"
                                    placeholder="{{ __('localization.company_create_data_tab_1_about_placeholder') }}"
                                ></textarea>
                            </div>

                            <div id="about-message"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mx-2" id="data_tab_2" style="display: none">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            {{ __('localization.company_create_data_tab_2_title') }}
                        </h4>
                    </div>
                    <div class="card-body my-25">
                        <!-- header section -->

                        <x-form.avatar-uploader
                            id="company-2"
                            name="company-2"
                            :imageSrc="asset('assets/icons/entity/company/default-empty-company.svg')"
                            :disabled="null"
                        />

                        <!--/ header section -->
                        <div class="mt-2 pt-50">
                            <div class="row">
                                <div class="col-12 col-sm-6 mb-1">
                                    <label class="form-label" for="company_name">
                                        {{ __('localization.company_create_data_tab_2_company_name') }}
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="company_name"
                                        name="patronymic"
                                        required
                                        placeholder="{{ __('localization.company_create_data_tab_2_company_name_placeholder') }}"
                                        data-msg="Please enter patronymic"
                                    />
                                </div>
                                <div class="col-12 col-sm-6 mb-1">
                                    <label class="form-label" for="fp-default select2-hide-search">
                                        {{ __('localization.company_create_data_tab_2_legal_entity_type') }}
                                    </label>
                                    <select
                                        class="hide-search form-select"
                                        id="legal_entity"
                                        data-placeholder="{{ __('localization.company_create_data_tab_2_legal_entity_type_placeholder') }}"
                                    >
                                        <option value=""></option>
                                        @foreach ($legalTypes as $type)
                                            <option value="{{ $type->id }}">
                                                {{ $type->label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 mb-1">
                                    <label class="form-label" for="edrpou">
                                        {{ __('localization.company_create_data_tab_2_edrpou') }}
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="edrpou"
                                        name="edrpou"
                                        required
                                        oninput="limitInputToNumbers(this,8)"
                                        placeholder="{{ __('localization.company_create_data_tab_2_edrpou_placeholder') }}"
                                        data-msg="Please enter first name"
                                    />
                                </div>

                                <div class="col-12 col-sm-6 mb-1">
                                    <label class="form-label" for="email_2">
                                        {{ __('localization.company_create_data_tab_2_email') }}
                                    </label>
                                    <input
                                        type="email"
                                        class="form-control"
                                        id="email_2"
                                        name="email"
                                        required
                                        data-msg="Please enter last name"
                                        placeholder="{{ __('localization.company_create_data_tab_2_email_placeholder') }}"
                                    />
                                </div>
                                <div class="col-12 col-sm-6 mb-1">
                                    <label class="form-label" for="select2-hide-search">
                                        {{ __('localization.company_create_data_tab_2_company_category') }}
                                    </label>
                                    <select
                                        class="hide-search form-select"
                                        id="company_category"
                                        data-dictionary="company_category"
                                        data-placeholder="{{ __('localization.company_create_data_tab_2_company_category_placeholder') }}"
                                    >
                                        <option value=""></option>
                                    </select>
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
                            {{ __('localization.company_create_data_tab_2_pdv') }}
                        </h4>
                        <input type="checkbox" class="form-check-input" id="pdv" />
                    </div>

                    <div class="card-body my-25">
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="ipn_2">
                                    {{ __('localization.company_create_data_tab_2_ipn') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="ipn_2"
                                    placeholder="{{ __('localization.company_create_data_tab_2_ipn_placeholder') }}"
                                    disabled
                                    oninput="limitInputToNumbers(this,10)"
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label for="reg_doc" class="form-label">
                                    {{ __('localization.company_create_data_tab_2_reg_doc') }}
                                </label>
                                <div class="input-group">
                                    <input class="form-control" type="file" id="reg_doc" disabled />
                                    <button
                                        class="btn btn-outline-primary input-group-btn disabled-btn-c"
                                        disabled
                                        id="reg_doc-reset"
                                        type="button"
                                    >
                                        <i data-feather="x"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-12 offset-sm-6 col-sm-6 mb-1">
                                <label for="ust_doc" class="form-label">
                                    {{ __('localization.company_create_data_tab_2_ust_doc') }}
                                </label>
                                <div class="input-group">
                                    <input class="form-control" type="file" id="ust_doc" disabled />
                                    <button
                                        class="btn btn-outline-primary input-group-btn disabled-btn-c"
                                        disabled
                                        id="ust_doc-reset"
                                        type="button"
                                    >
                                        <i data-feather="x"></i>
                                    </button>
                                </div>
                            </div>

                            <div id="pdv-message"></div>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title">
                            {{ __('localization.company_create_data_tab_2_actual_address') }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-1">
                                <div class="mb-1">
                                    <label class="form-label" for="select2-hide-search">
                                        {{ __('localization.company_create_data_tab_2_country') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="country_2"
                                        data-dictionary="country"
                                        data-placeholder="{{ __('localization.company_create_data_tab_2_country_placeholder') }}"
                                    >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <div class="mb-1">
                                    <label class="form-label" for="select2-hide-search">
                                        {{ __('localization.company_create_data_tab_2_city') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="city_2"
                                        data-dictionary="settlement"
                                        data-placeholder="{{ __('localization.company_create_data_tab_2_city_placeholder') }}"
                                    >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <div class="mb-1">
                                    <label class="form-label" for="select2-hide-search">
                                        {{ __('localization.company_create_data_tab_2_street') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="street_2"
                                        data-dictionary="street"
                                        data-placeholder="{{ __('localization.company_create_data_tab_2_street_placeholder') }}"
                                    >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="code">
                                    {{ __('localization.company_create_data_tab_2_building_number') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="building_number_2"
                                    name="buidling_number"
                                    required
                                    autocomplete="off"
                                    placeholder="{{ __('localization.company_create_data_tab_2_building_number_placeholder') }}"
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="code">
                                    {{ __('localization.company_create_data_tab_2_flat') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="flat_2"
                                    name="flat"
                                    required
                                    autocomplete="off"
                                    placeholder="{{ __('localization.company_create_data_tab_2_flat_placeholder') }}"
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="code">
                                    {{ __('localization.company_create_data_tab_2_gln') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="gln_2"
                                    name="gln"
                                    required
                                    autocomplete="off"
                                    oninput="limitInputToNumbers(this,13)"
                                    placeholder="{{ __('localization.company_create_data_tab_2_gln_placeholder') }}"
                                />
                            </div>

                            <div id="address-message2"></div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title">
                            {{ __('localization.company_create_data_tab_2_legal_address') }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-1">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="matchingAddress"
                                    value="unchecked"
                                />
                                <label class="form-check-label" for="matchingAddress">
                                    {{ __('localization.company_create_data_tab_2_matching_address') }}
                                </label>
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <div class="mb-1">
                                    <label class="form-label" for="u_country">
                                        {{ __('localization.company_create_data_tab_2_u_country') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="u_country"
                                        data-dictionary="country"
                                        data-placeholder="{{ __('localization.company_create_data_tab_2_u_country_placeholder') }}"
                                    >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <div class="mb-1">
                                    <label class="form-label" for="u_city">
                                        {{ __('localization.company_create_data_tab_2_u_city') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="u_city"
                                        data-dictionary="settlement"
                                        data-placeholder="{{ __('localization.company_create_data_tab_2_u_city_placeholder') }}"
                                    >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <div class="mb-1">
                                    <label class="form-label" for="u_street">
                                        {{ __('localization.company_create_data_tab_2_u_street') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="u_street"
                                        data-dictionary="street"
                                        data-placeholder="{{ __('localization.company_create_data_tab_2_u_street_placeholder') }}"
                                    >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="u_building_number">
                                    {{ __('localization.company_create_data_tab_2_u_building_number') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="u_building_number"
                                    name="buidling_number"
                                    required
                                    autocomplete="off"
                                    placeholder="{{ __('localization.company_create_data_tab_2_u_building_number_placeholder') }}"
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="u_flat">
                                    {{ __('localization.company_create_data_tab_2_u_flat') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="u_flat"
                                    name="flat"
                                    required
                                    autocomplete="off"
                                    placeholder="{{ __('localization.company_create_data_tab_2_u_flat_placeholder') }}"
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="u_gln">
                                    {{ __('localization.company_create_data_tab_2_u_gln') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="u_gln"
                                    name="gln"
                                    required
                                    autocomplete="off"
                                    oninput="limitInputToNumbers(this,13)"
                                    placeholder="{{ __('localization.company_create_data_tab_2_u_gln_placeholder') }}"
                                />
                            </div>

                            <div id="u-address-message"></div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title">
                            {{ __('localization.company_create_data_tab_2_requisites') }}
                        </h4>
                    </div>

                    <div class="card-body my-25">
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="bank_2">
                                    {{ __('localization.company_create_data_tab_2_bank') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="bank_2"
                                    placeholder="{{ __('localization.company_create_data_tab_2_bank_placeholder') }}"
                                    required
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="iban_2">
                                    {{ __('localization.company_create_data_tab_2_iban') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="iban_2"
                                    placeholder="{{ __('localization.company_create_data_tab_2_iban_placeholder') }}"
                                    oninput="maskNumbersPlusLatin(this,29)"
                                    required
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="mfo_2">
                                    {{ __('localization.company_create_data_tab_2_mfo') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="mfo_2"
                                    required
                                    placeholder="{{ __('localization.company_create_data_tab_2_mfo_placeholder') }}"
                                    oninput="limitInputToNumbers(this,6)"
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <div class="mb-1">
                                    <label class="form-label" for="currency_u">
                                        {{ __('localization.company_create_data_tab_2_currency') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="currency_u"
                                        data-dictionary="currencies"
                                        data-placeholder="{{ __('localization.company_create_data_tab_2_currency_placeholder') }}"
                                    >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div id="requisite-message2"></div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title">
                            {{ __('localization.company_create_data_tab_2_about') }}
                        </h4>
                    </div>
                    <div class="card-body my-25">
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-1">
                                <textarea
                                    class="form-control"
                                    id="about_2"
                                    rows="5"
                                    placeholder="{{ __('localization.company_create_data_tab_2_about_placeholder') }}"
                                ></textarea>
                            </div>

                            <div id="about_company_message_2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-layout.container>
@endsection

@section('page-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.6/js/intlTelInput.min.js"></script>
    <script type="module" src="{{ asset('assets/js/entity/company/search-company.js') }}"></script>
    <script src="{{ asset('js/jquery.maskedinput.min.js') }}"></script>
    <script type="module" src="{{ asset('assets/js/entity/company/company.js') }}"></script>
    {{-- <script src="{{asset('assets/js/utils/unused/cropper.js')}}"></script> --}}

    <script>
        $(document).ready(function () {
            $('#link-to-create-page').on('click', function () {
                $('#create-company-page').removeClass('d-none');
                $('#search-company-page').addClass('d-none');
            });

            $('#link-to-search-page').on('click', function () {
                $('#search-company-page').removeClass('d-none');
                $('#create-company-page').addClass('d-none');
            });
        });
    </script>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script> --}}
@endsection
