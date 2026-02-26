@extends('layouts.empty')
@section('title', __('localization.onboarding_index_title'))

@section('page-style')
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}"
    />
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}"> -->
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}"
    />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.6/css/intlTelInput.css"
    />
@endsection

@section('before-style')
    
@endsection

@section('content')
    <div
        class="container-fluid personal-info-user py-0 d-flex align-items-center"
        style="height: 100vh"
        id="personal-info-user"
    >
        <div class="card my-0">
            <div class="row mx-0">
                <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 col-xxl-8 px-2 py-3">
                    <div class="row mx-0">
                        <div class="navbar-header col-md-12 col-12">
                            <ul class="nav navbar-nav navbar-brand">
                                <li class="nav-item d-flex">
                                    <div class="align-self-center px-0">
                                        <img
                                            width="30px"
                                            src="{{ asset('assets/icons/entity/logo/logo-consolid.svg') }}"
                                        />
                                    </div>
                                    <div class="col-9 px-0">
                                        <h3
                                            style="
                                                margin-top: 8px;
                                                margin-left: 6px;
                                                font-weight: bold;
                                            "
                                            class="brand-txt"
                                        >
                                            {{ config('app.name') }}
                                        </h3>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-12 col-12 my-2">
                            <h2 class="fw-bolder">
                                {{ __('localization.onboarding_index_personal_info_user_welcome') }}{{ config('app.name') }}!
                            </h2>
                            <div>
                                {{ __('localization.onboarding_index_personal_info_user_fill_details') }}
                            </div>
                        </div>

                        <div class="col-12 col-sm-12 mb-1">
                            <label class="form-label" for="user-name">
                                {{ __('localization.onboarding_index_personal_info_user_name') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                placeholder="{{ __('localization.onboarding_index_personal_info_user_name_placeholder') }}"
                            />
                        </div>

                        <div class="col-12 col-sm-12 mb-1">
                            <label class="form-label" for="user-last-name">
                                {{ __('localization.onboarding_index_personal_info_user_last_name') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="surname"
                                placeholder="{{ __('localization.onboarding_index_personal_info_user_last_name_placeholder') }}"
                            />
                        </div>

                        <div class="col-12 col-sm-12 mb-1">
                            <label class="form-label" for="user-patronymic">
                                {{ __('localization.onboarding_index_personal_info_user_patronymic') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="_patronymic"
                                placeholder="{{ __('localization.onboarding_index_personal_info_user_patronymic_placeholder') }}"
                            />
                        </div>

                        <div class="col-12 col-sm-12 mb-1">
                            <label class="form-label" for="_email">
                                {{ __('localization.onboarding_index_personal_info_user_email') }}
                            </label>
                            <input
                                type="email"
                                class="form-control"
                                id="_email"
                                placeholder="{{ __('localization.onboarding_index_personal_info_user_email_placeholder') }}"
                                {{ $user['login'] ?? null ? 'value=' . $user['login'] . ' disabled' : 'data-required="true"' }}
                            />
                        </div>

                        {{-- <!-- <div class="col-12 col-sm-12 mb-1"> --}}
                        {{-- <label class="form-label" for="phone-number">Телефон</label> --}}
                        {{-- <div class="input-group input-group-merge"> --}}
                        {{-- <span class="input-group-text" --}}
                        {{-- style="border-right-width: 2px">+380</span> --}}
                        {{-- <input type="text" class="form-control phone-number-mask" maxlength="11" --}}
                        {{-- placeholder="546 784 5489" style="padding-left: 10px" --}}
                        {{-- id="phone" {{ Auth::user()->phone ? 'value=' . substr(Auth::user()->phone, 4) . ' disabled' : '' }}/> --}}
                        {{-- </div> --}}
                        {{-- </div> --> --}}

                        <div class="col-12 col-sm-12 mb-1 inpSelectNumCountry">
                            <div class="mb-1 d-flex flex-column">
                                <label class="form-label" for="phone">
                                    {{ __('localization.onboarding_index_personal_info_user_phone') }}
                                </label>
                                <input
                                    class="form-control input-number-country"
                                    id="phone"
                                    name=""
                                    aria-describedby="phone"
                                    autofocus=""
                                />
                            </div>
                        </div>

                        <div
                            class="d-md-block d-lg-none col-12 col-sm-12 mb-1 d-flex justify-content-center align-items-center flex-column"
                        >
                            <h4 class="align-self-start mt-2 fw-bolder">
                                {{ __('localization.onboarding_index_personal_info_user_why_needed') }}
                            </h4>
                            <p>
                                {{ __('localization.onboarding_index_personal_info_user_why_needed_text') }}
                            </p>
                            <a
                                data-bs-toggle="modal"
                                data-bs-target="#modalForSendSupport"
                                href="#"
                                class="align-self-start fw-bolder"
                            >
                                {{ __('localization.onboarding_index_personal_info_user_contact_us') }}
                            </a>
                        </div>

                        <div class="col-12 col-sm-12">
                            <button
                                id="next-find-company"
                                class="next-find-company btn disabled btn-primary py-0 float-end"
                            >
                                {{ __('localization.onboarding_index_personal_info_user_next') }}
                                <img
                                    style="margin-left: 5px"
                                    src="{{ asset('assets/icons/entity/onboarding/arrow-right.svg') }}"
                                />
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    class="col-4 d-none d-sm-none d-md-none bg-secondary-100 d-lg-block col-sm-4 col-md-4 col-lg-4 col-xxl-4 px-3"
                >
                    <div class="d-flex justify-content-center align-items-center h-100 flex-column">
                        <img
                            class=""
                            src="{{ asset('assets/icons/entity/onboarding/onboarding-info.svg') }}"
                        />
                        <h4 class="align-self-start mt-2 fw-bolder">
                            {{ __('localization.onboarding_index_personal_info_user_why_needed') }}
                        </h4>
                        <p>
                            {{ __('localization.onboarding_index_personal_info_user_why_needed_text') }}
                        </p>
                        <a
                            data-bs-toggle="modal"
                            data-bs-target="#modalForSendSupport"
                            href="#"
                            class="align-self-start fw-bolder"
                        >
                            {{ __('localization.onboarding_index_personal_info_user_contact_us') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('onboarding.base-steps-onboarding', ['findCompanyShowOrHide' => 'd-none'])
@endsection

@section('page-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.6/js/intlTelInput.min.js"></script>

    <script type="module">
        import { inputSelectCountry } from '{{ asset('assets/js/utils/inputSelectCountry.js') }}';

        inputSelectCountry('#feedBackNumberInp');
        inputSelectCountry('#phone');
    </script>

    <script type="module">
        import { selectDictionaries } from '{{ asset('assets/js/utils/selectDictionaries.js') }}';

        selectDictionaries();
    </script>

    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>

    <!-- <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script> -->
    <!-- <script src="{{ asset('js/scripts/forms/pickers/form-pickers.js') }}"></script> -->

    <script src="{{ asset('vendors/js/forms/cleave/cleave.min.js') }}"></script>
    <script src="{{ asset('vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/form-input-mask.js') }}"></script>

    <script type="module" src="{{ asset('assets/js/entity/onboarding/onboarding.js') }}"></script>
    <script type="module" src="{{ asset('assets/js/entity/company/company.js') }}"></script>

    <script src="{{ asset('assets/js/utils/validationInputs.js') }}"></script>
@endsection
