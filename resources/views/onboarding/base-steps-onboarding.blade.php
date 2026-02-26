<div
    class="container-fluid {{ $findCompanyShowOrHide ?? '' }} find-company py-0 d-flex align-items-center"
    style="height: 100vh"
    id="find-company"
>
    <div class="card my-0">
        <div class="row mx-0" style="height: 851px">
            <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 col-xxl-8 px-2 py-3">
                <div class="row mx-0 h-100 justify-content-between flex-column">
                    <div class="col-md-12 col-12">
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
                        <div class="col-md-12 col-12">
                            <div class="col-md-12 col-12 my-2">
                                <h2 class="fw-bolder">
                                    {{ __('localization.onboarding_index_find_company_add_company') }}
                                </h2>
                                <div>
                                    {{ __('localization.onboarding_index_find_company_instructions') }}
                                </div>
                            </div>

                            <div class="col-md-12 col-12 mb-1">
                                <div class="input-group inpSelectCountry">
                                    <input
                                        id="searchCompany"
                                        type="text"
                                        class="form-control"
                                        placeholder="{{ __('localization.onboarding_index_find_company_search_placeholder') }}"
                                        aria-describedby="button-addon2"
                                    />

                                    <button
                                        class="btn btn-outline-primary"
                                        id="searchCompanyButton"
                                        type="button"
                                    >
                                        <i style="margin-right: 10px" data-feather="search"></i>
                                        {{ __('localization.onboarding_index_find_company_search') }}
                                    </button>
                                </div>
                            </div>

                            <div id="searchCompanyResult" class="col-12 col-sm-12">
                                <div
                                    class="d-flex flex-column align-items-center justify-content-center"
                                    style="height: 414px"
                                >
                                    <div
                                        id="findCompany"
                                        class="d-flex flex-column align-items-center justify-content-center"
                                    >
                                        <img
                                            src="{{ asset('assets/icons/entity/onboarding/onboarding-search.svg') }}"
                                        />
                                        <h4 class="fw-bolder">
                                            {{ __('localization.onboarding_index_find_company_find_company') }}
                                        </h4>
                                        <p
                                            class="text-secondary text-center"
                                            style="max-width: 390px; opacity: 0.7 !important"
                                        >
                                            {{ __('localization.onboarding_index_find_company_find_company_instructions') }}
                                        </p>
                                    </div>

                                    <div
                                        id="notFoundCompany"
                                        class="d-none d-flex flex-column align-items-center justify-content-center"
                                    >
                                        <img
                                            src="{{ asset('assets/icons/entity/onboarding/onboarding-search-question.svg') }}"
                                        />
                                        <h4 class="fw-bolder">
                                            {{ __('localization.onboarding_index_find_company_not_found') }}
                                        </h4>
                                        <p
                                            class="text-secondary text-center"
                                            style="max-width: 390px; opacity: 0.7 !important"
                                        >
                                            {{ __('localization.onboarding_index_find_company_not_found_instructions') }}
                                        </p>
                                    </div>

                                    <button
                                        class="btn btn-primary float-end mt-1 d-none"
                                        id="create-new-company"
                                        type="button"
                                    >
                                        <img
                                            class="plus-icon"
                                            src="{{ asset('assets/icons/utils/plus.svg') }}"
                                        />
                                        {{ __('localization.onboarding_index_find_company_create_new') }}
                                    </button>
                                </div>
                            </div>

                            <div id="listFindCompany" class="col-12 col-sm-12 d-none">
                                <p id="countCompanyItem" class="mb-0">
                                    {{ __('localization.onboarding_index_find_company_found_results', ['count' => 'X']) }}
                                </p>
                                <div
                                    class="d-flex flex-column"
                                    style="max-height: 476px; overflow-y: auto"
                                >
                                    <div class="row mx-0" id="listItemCompany"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 d-flex justify-content-between align-items-center">
                        <button
                            class="btn btn-flat-secondary float-start {{ $isShowBtnStep1 ?? '' }}"
                            id="back-personal-info"
                            style="padding-top: 10px !important ; padding-bottom: 10px !important"
                        >
                            <img
                                style="margin-right: 0.5rem"
                                src="{{ asset('assets/icons/entity/onboarding/arrow-left.svg') }}"
                            />
                            {{ __('localization.onboarding_index_find_company_back') }}
                        </button>

                        <p id="onbording-link-proposition" class="p-0 m-0 d-none">
                            {{ __('localization.onboarding_index_find_company_not_found_company') }}
                            <a href="#" id="create-new-company-link">
                                {{ __('localization.onboarding_index_find_company_not_found_create_new') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="d-none d-sm-none d-md-none bg-secondary-100 d-lg-block col-4 col-sm-4 col-md-4 col-lg-4 col-xxl-4 px-3"
            >
                <div class="d-flex justify-content-center align-items-center h-100 flex-column">
                    <img
                        width="100%"
                        style="max-width: max-content"
                        src="{{ asset('assets/icons/entity/onboarding/onboarding-company.svg') }}"
                    />
                    <h4 class="align-self-start mt-2 fw-bolder">
                        {{ __('localization.onboarding_index_find_company_how_it_works') }}
                    </h4>
                    <p>{{ __('localization.onboarding_index_find_company_how_it_works_text') }}</p>
                    <a
                        data-bs-toggle="modal"
                        data-bs-target="#modalForSendSupport"
                        href="#"
                        class="align-self-start fw-bolder"
                    >
                        {{ __('localization.onboarding_index_find_company_contact_us') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div
    class="container-fluid d-none create-company py-0 d-flex align-items-center"
    style="height: 100vh"
    id="create-company"
>
    <div class="card my-0">
        <div class="row mx-0" style="height: 851px">
            <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 col-xxl-8 px-2 py-3">
                <div class="row mx-0 h-100 justify-content-between flex-column">
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
                                        style="margin-top: 8px; margin-left: 6px; font-weight: bold"
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
                            {{ __('localization.onboarding_index_create_company_new_company') }}
                        </h2>
                    </div>

                    <div class="col-md-12 col-12" style="overflow-y: auto; max-height: 600px">
                        <div class="col-md-12 col-12">
                            <div class="d-flex row mx-0">
                                <div class="col-6 ps-0">
                                    <div
                                        class="card onboarding-radio-card radio-card mb-0 tab-active"
                                        style="width: auto !important"
                                    >
                                        <div class="card-body" id="tab_1" data-tab="1">
                                            <div class="text-center">
                                                <img
                                                    src="{{ asset('assets/icons/entity/onboarding/tab-user.svg') }}"
                                                    id="tab-icon-1"
                                                    class="tab-filter"
                                                />
                                            </div>
                                            <div class="text-center">
                                                <span class="f-15 fw-6">
                                                    {{ __('localization.onboarding_index_tab_individual') }}
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
                                </div>
                                <div class="col-6">
                                    <div
                                        class="card onboarding-radio-card radio-card mb-0"
                                        style="width: auto !important"
                                    >
                                        <div class="card-body" id="tab_2" data-tab="2">
                                            <div class="text-center">
                                                <img
                                                    src="{{ asset('assets/icons/entity/onboarding/tab-company.svg') }}"
                                                    id="tab-icon-2"
                                                />
                                            </div>
                                            <div class="text-center">
                                                <span class="f-15 fw-6">
                                                    {{ __('localization.onboarding_index_tab_legal_entity') }}
                                                </span>
                                            </div>
                                            <div class="text-center">
                                                <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    name="tabs"
                                                    value="tab2"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="data_tab_1">
                                <div class="card mb-0">
                                    <div class="card-header px-0">
                                        <h4 class="card-title">
                                            {{ __('localization.onboarding_index_create_company_tab_1_main_data') }}
                                        </h4>
                                    </div>
                                    <div class="card-body my-25 px-0 pb-0">
                                        <!-- header section -->

                                        <x-form.avatar-uploader
                                            id="company-1"
                                            name="company-1"
                                            :imageSrc="asset('assets/icons/entity/company/default-empty-company.svg')"
                                            :disabled="null"
                                        />
                                        <!--/ header section -->
                                        <div class="mt-2 pt-50">
                                            <div class="row">
                                                <div class="col-12 col-sm-6 mb-1">
                                                    <label
                                                        class="form-label"
                                                        for="accountFirstName"
                                                    >
                                                        {{ __('localization.onboarding_index_create_company_tab_1_first_name') }}
                                                    </label>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        id="first_name"
                                                        name="name"
                                                        required
                                                        placeholder="{{ __('localization.onboarding_index_create_company_tab_1_first_name_placeholder') }}"
                                                        data-msg="{{ __('localization.onboarding_index_create_company_tab_1_first_name_validation') }}"
                                                    />
                                                </div>

                                                <div class="col-12 col-sm-6 mb-1">
                                                    <label class="form-label" for="accountLastName">
                                                        {{ __('localization.onboarding_index_create_company_tab_1_last_name') }}
                                                    </label>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        id="last_name"
                                                        name="surname"
                                                        placeholder="{{ __('localization.onboarding_index_create_company_tab_1_last_name_placeholder') }}"
                                                        required
                                                        data-msg="{{ __('localization.onboarding_index_create_company_tab_1_last_name_validation') }}"
                                                    />
                                                </div>

                                                <div class="col-12 col-sm-6 mb-1">
                                                    <label
                                                        class="form-label"
                                                        for="accountPatronymic"
                                                    >
                                                        {{ __('localization.onboarding_index_create_company_tab_1_patronymic') }}
                                                    </label>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        id="patronymic"
                                                        name="patronymic"
                                                        required
                                                        placeholder="{{ __('localization.onboarding_index_create_company_tab_1_patronymic_placeholder') }}"
                                                        data-msg="{{ __('localization.onboarding_index_create_company_tab_1_patronymic_validation') }}"
                                                    />
                                                </div>

                                                <div class="col-12 col-sm-6 mb-1">
                                                    <label class="form-label" for="fp-default">
                                                        {{ __('localization.onboarding_index_create_company_tab_1_ipn') }}
                                                    </label>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        required
                                                        id="ipn"
                                                        oninput="limitInputToNumbers(this,10)"
                                                        name="ipn"
                                                        placeholder="{{ __('localization.onboarding_index_create_company_tab_1_ipn_placeholder') }}"
                                                    />
                                                </div>

                                                <div class="col-12 col-sm-6 mb-1">
                                                    <label class="form-label" for="email">
                                                        {{ __('localization.onboarding_index_create_company_tab_1_email') }}
                                                    </label>
                                                    <input
                                                        type="email"
                                                        class="form-control"
                                                        id="email"
                                                        name="email"
                                                        required
                                                        placeholder="{{ __('localization.onboarding_index_create_company_tab_1_email_placeholder') }}"
                                                    />
                                                </div>

                                                <div class="col-12 col-sm-6 mb-1">
                                                    <label
                                                        class="form-label"
                                                        for="select2-hide-search"
                                                    >
                                                        {{ __('localization.onboarding_index_create_company_tab_1_company_category') }}
                                                    </label>
                                                    <select
                                                        class="hide-search form-select"
                                                        id="category"
                                                        data-dictionary="company_category"
                                                        data-placeholder="{{ __('localization.onboarding_index_create_company_tab_1_company_category_placeholder') }}"
                                                    >
                                                        <option value=""></option>
                                                    </select>
                                                </div>

                                                <div id="private-data-message"></div>
                                            </div>
                                        </div>
                                        <!--/ form -->
                                    </div>
                                </div>

                                <div class="card mb-0">
                                    <div class="card-header px-0">
                                        <h4 class="card-title">
                                            {{ __('localization.onboarding_index_create_company_tab_1_actual_address') }}
                                        </h4>
                                    </div>

                                    <div class="card-body px-0 pb-0">
                                        <div class="row">
                                            <div class="col-12 col-sm-6 mb-1">
                                                <div class="mb-1">
                                                    <label
                                                        class="form-label"
                                                        for="select2-hide-search"
                                                    >
                                                        {{ __('localization.onboarding_index_create_company_tab_1_country') }}
                                                    </label>
                                                    <select
                                                        class="select2 form-select"
                                                        id="country"
                                                        data-dictionary="country"
                                                        id="select2-hide-search"
                                                        data-placeholder="{{ __('localization.onboarding_index_create_company_tab_1_country_placeholder') }}"
                                                    >
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <div class="mb-1">
                                                    <label
                                                        class="form-label"
                                                        for="select2-hide-search"
                                                    >
                                                        {{ __('localization.onboarding_index_create_company_tab_1_city') }}
                                                    </label>
                                                    <select
                                                        class="select2 form-select"
                                                        id="city"
                                                        data-dictionary="settlement"
                                                        data-placeholder="{{ __('localization.onboarding_index_create_company_tab_1_city_placeholder') }}"
                                                    >
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <div class="mb-1">
                                                    <label
                                                        class="form-label"
                                                        for="select2-hide-search"
                                                    >
                                                        {{ __('localization.onboarding_index_create_company_tab_1_street') }}
                                                    </label>
                                                    <select
                                                        class="select2 form-select"
                                                        id="street"
                                                        data-dictionary="street"
                                                        data-placeholder="{{ __('localization.onboarding_index_create_company_tab_1_street_placeholder') }}"
                                                    >
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="code">
                                                    {{ __('localization.onboarding_index_create_company_tab_1_building_number') }}
                                                </label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="building_number"
                                                    name="building_number"
                                                    required
                                                    autocomplete="off"
                                                    placeholder="{{ __('localization.onboarding_index_create_company_tab_1_building_number_placeholder') }}"
                                                />
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="code">
                                                    {{ __('localization.onboarding_index_create_company_tab_1_flat') }}
                                                </label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="flat"
                                                    name="flat"
                                                    required
                                                    autocomplete="off"
                                                    placeholder="{{ __('localization.onboarding_index_create_company_tab_1_flat_placeholder') }}"
                                                />
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="code">
                                                    {{ __('localization.onboarding_index_create_company_tab_1_gln') }}
                                                </label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="gln"
                                                    name="gln"
                                                    required
                                                    oninput="limitInputToNumbers(this,13)"
                                                    autocomplete="off"
                                                    placeholder="{{ __('localization.onboarding_index_create_company_tab_1_gln_placeholder') }}"
                                                />
                                            </div>

                                            <div id="address-message"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-0">
                                    <div class="card-header px-0">
                                        <h4 class="card-title">
                                            {{ __('localization.onboarding_index_create_company_tab_1_details') }}
                                        </h4>
                                    </div>
                                    <div class="card-body my-25 px-0 pb-0">
                                        <div class="row">
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="bank">
                                                    {{ __('localization.onboarding_index_create_company_tab_1_bank') }}
                                                </label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="bank"
                                                    placeholder="{{ __('localization.onboarding_index_create_company_tab_1_bank_placeholder') }}"
                                                    required
                                                />
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="iban">
                                                    {{ __('localization.onboarding_index_create_company_tab_1_iban') }}
                                                </label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="iban"
                                                    oninput="maskNumbersPlusLatin(this,29)"
                                                    placeholder="{{ __('localization.onboarding_index_create_company_tab_1_iban_placeholder') }}"
                                                    required
                                                />
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="mfo">
                                                    {{ __('localization.onboarding_index_create_company_tab_1_mfo') }}
                                                </label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="mfo"
                                                    required
                                                    oninput="limitInputToNumbers(this,6)"
                                                    placeholder="{{ __('localization.onboarding_index_create_company_tab_1_mfo_placeholder') }}"
                                                />
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <div class="mb-1">
                                                    <label
                                                        class="form-label"
                                                        for="select2-hide-search"
                                                    >
                                                        {{ __('localization.onboarding_index_create_company_tab_1_currency') }}
                                                    </label>
                                                    <select
                                                        class="select2 form-select"
                                                        id="currency"
                                                        data-dictionary="currencies"
                                                        data-placeholder="{{ __('localization.onboarding_index_create_company_tab_1_currency_placeholder') }}"
                                                    >
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div id="requisite-message"></div>
                                        </div>
                                    </div>
                                </div>

                                <hr />

                                <div class="card mb-0">
                                    <div class="card-header px-0">
                                        <h4 class="card-title">
                                            {{ __('localization.onboarding_index_create_company_tab_1_about_company') }}
                                        </h4>
                                    </div>
                                    <div class="card-body my-25 px-0 pb-0">
                                        <div class="row">
                                            <div class="col-12 col-sm-12 mb-1">
                                                <textarea
                                                    class="form-control"
                                                    id="about"
                                                    rows="5"
                                                    placeholder="{{ __('localization.onboarding_index_create_company_tab_1_about_company_placeholder') }}"
                                                ></textarea>
                                            </div>

                                            <div id="about-message"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="data_tab_2" style="display: none">
                                <div class="card mb-0 mt-1">
                                    <div class="col-12 col-sm-6 mb-1">
                                        <label
                                            class="form-label"
                                            for="fp-default select2-hide-search"
                                        >
                                            {{ __('localization.onboarding_index_create_company_tab_2_label_legal_entity') }}
                                        </label>
                                        <select
                                            class="hide-search form-select"
                                            id="legal_entity"
                                            data-placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_legal_entity') }}"
                                        >
                                            <option value=""></option>
                                            @php
                                                $positionNames = [
                                                    'llc' => 'company_create_data_tab_2_legal_entity_llc',
                                                    'public_jsc' => 'company_create_data_tab_2_legal_entity_public_jsc',
                                                    'private_jsc' => 'company_create_data_tab_2_legal_entity_private_jsc',
                                                    'private_enterprise' => 'company_create_data_tab_2_legal_entity_private_enterprise',
                                                    'other' => 'company_create_data_tab_2_legal_entity_other',
                                                ];
                                            @endphp

                                            @foreach ($legalTypes as $type)
                                                <option value="{{ $type->id }}">
                                                    {{ isset($positionNames[$type->key]) ? __('localization.' . $positionNames[$type->key]) : '-' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="card-header px-0">
                                        <h4 class="card-title">
                                            {{ __('localization.onboarding_index_create_company_tab_2_label_main_data') }}
                                        </h4>
                                    </div>
                                    <div class="card-body my-25 px-0 pb-0">
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
                                                    <label class="form-label" for="edrpou">
                                                        {{ __('localization.onboarding_index_create_company_tab_2_label_edrpou') }}
                                                    </label>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        id="edrpou"
                                                        oninput="limitInputToNumbers(this,8)"
                                                        name="edrpou"
                                                        required
                                                        placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_edrpou') }}"
                                                        data-msg="{{ __('localization.onboarding_index_create_company_tab_2_msg_edrpou') }}"
                                                    />
                                                </div>

                                                <div class="col-12 col-sm-6 mb-1">
                                                    <label class="form-label" for="email_2">
                                                        {{ __('localization.onboarding_index_create_company_tab_2_label_email') }}
                                                    </label>
                                                    <input
                                                        type="email"
                                                        class="form-control"
                                                        id="email_2"
                                                        name="email"
                                                        required
                                                        data-msg="{{ __('localization.onboarding_index_create_company_tab_2_msg_email') }}"
                                                        placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_email') }}"
                                                    />
                                                </div>

                                                <div class="col-12 col-sm-6 mb-1">
                                                    <label class="form-label" for="company_name">
                                                        {{ __('localization.onboarding_index_create_company_tab_2_label_company_name') }}
                                                    </label>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        id="company_name"
                                                        name="patronymic"
                                                        required
                                                        placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_company_name') }}"
                                                        data-msg="{{ __('localization.onboarding_index_create_company_tab_2_msg_company_name') }}"
                                                    />
                                                </div>

                                                <div class="col-12 col-sm-6 mb-1">
                                                    <label
                                                        class="form-label"
                                                        for="select2-hide-search"
                                                    >
                                                        {{ __('localization.onboarding_index_create_company_tab_2_label_company_category') }}
                                                    </label>
                                                    <select
                                                        class="hide-search form-select"
                                                        id="company_category"
                                                        data-dictionary="company_category"
                                                        data-placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_company_category') }}"
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

                                <div class="card mb-0">
                                    <div class="card-header justify-content-normal px-0">
                                        <h4 class="card-title">
                                            {{ __('localization.onboarding_index_create_company_tab_2_label_pdv') }}
                                        </h4>
                                        <input type="checkbox" class="form-check-input" id="pdv" />
                                    </div>

                                    <div class="card-body my-25 px-0 pb-0">
                                        <div class="row">
                                            <div class="col-12 col-sm-12 mb-1">
                                                <label class="form-label" for="passwordEmail">
                                                    {{ __('localization.onboarding_index_create_company_tab_2_label_ipn') }}
                                                </label>
                                                <input
                                                    oninput="limitInputToNumbers(this,10)"
                                                    type="text"
                                                    class="form-control"
                                                    id="ipn_2"
                                                    placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_ipn') }}"
                                                    disabled
                                                />
                                            </div>

                                            <div class="col-12 col-sm-12 mb-1">
                                                <label for="reg_doc" class="form-label">
                                                    {{ __('localization.onboarding_index_create_company_tab_2_label_reg_doc') }}
                                                </label>
                                                <div class="input-group">
                                                    <input
                                                        class="form-control"
                                                        type="file"
                                                        id="reg_doc"
                                                        disabled
                                                    />
                                                    <button
                                                        class="btn btn-outline-primary disabled-btn-c"
                                                        id="reg_doc-reset"
                                                        type="button"
                                                        disabled
                                                    >
                                                        <i data-feather="x"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-12 mb-1">
                                                <label for="ust_doc" class="form-label">
                                                    {{ __('localization.onboarding_index_create_company_tab_2_label_ust_doc') }}
                                                </label>
                                                <div class="input-group">
                                                    <input
                                                        class="form-control"
                                                        type="file"
                                                        id="ust_doc"
                                                        disabled
                                                    />
                                                    <button
                                                        class="btn btn-outline-primary disabled-btn-c"
                                                        id="ust_doc-reset"
                                                        type="button"
                                                        disabled
                                                    >
                                                        <i data-feather="x"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div id="pdv-message"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-0">
                                    <div class="card-header px-0">
                                        <h4 class="card-title">
                                            {{ __('localization.onboarding_index_create_company_tab_2_label_physical_address') }}
                                        </h4>
                                    </div>
                                    <div class="card-body px-0 pb-0">
                                        <div class="row">
                                            <div class="col-12 col-sm-6 mb-1">
                                                <div class="mb-1">
                                                    <label
                                                        class="form-label"
                                                        for="select2-hide-search"
                                                    >
                                                        {{ __('localization.onboarding_index_create_company_tab_2_label_country') }}
                                                    </label>
                                                    <select
                                                        class="select2 form-select"
                                                        id="country_2"
                                                        data-dictionary="country"
                                                        data-placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_country') }}"
                                                    >
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <div class="mb-1">
                                                    <label
                                                        class="form-label"
                                                        for="select2-hide-search"
                                                    >
                                                        {{ __('localization.onboarding_index_create_company_tab_2_label_city') }}
                                                    </label>
                                                    <select
                                                        class="select2 form-select"
                                                        id="city_2"
                                                        data-dictionary="settlement"
                                                        data-placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_city') }}"
                                                    >
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <div class="mb-1">
                                                    <label
                                                        class="form-label"
                                                        for="select2-hide-search"
                                                    >
                                                        {{ __('localization.onboarding_index_create_company_tab_2_label_street') }}
                                                    </label>
                                                    <select
                                                        class="select2 form-select"
                                                        id="street_2"
                                                        data-dictionary="street"
                                                        data-placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_street') }}"
                                                    >
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="code">
                                                    {{ __('localization.onboarding_index_create_company_tab_2_label_building_number') }}
                                                </label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="building_number_2"
                                                    placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_building_number') }}"
                                                    data-inputmask="'mask': '99999999'"
                                                    name="buidling_number"
                                                    required
                                                    autocomplete="off"
                                                />
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="flat_2">
                                                    {{ __('localization.onboarding_index_create_company_tab_2_label_flat') }}
                                                </label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="flat_2"
                                                    name="flat"
                                                    required
                                                    autocomplete="off"
                                                    placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_flat') }}"
                                                />
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="gln_2">
                                                    {{ __('localization.onboarding_index_create_company_tab_2_label_gln') }}
                                                </label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="gln_2"
                                                    name="gln"
                                                    oninput="limitInputToNumbers(this,13)"
                                                    required
                                                    autocomplete="off"
                                                    placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_gln') }}"
                                                />
                                            </div>

                                            <div id="address-message2"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-0">
                                    <div class="card-header px-0">
                                        <h4 class="card-title">
                                            {{ __('localization.onboarding_index_create_company_tab_2_label_legal_address') }}
                                        </h4>
                                    </div>
                                    <div class="card-body px-0 pb-0">
                                        <div class="row">
                                            <div class="col-12 mb-1">
                                                <div class="form-check form-check-inline">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        id="matchingAddress"
                                                        value="unchecked"
                                                    />
                                                    <label
                                                        class="form-check-label"
                                                        for="matchingAddress"
                                                    >
                                                        {{ __('localization.onboarding_index_create_company_tab_2_label_matching_address') }}
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <div class="mb-1">
                                                    <label class="form-label" for="u_country">
                                                        {{ __('localization.onboarding_index_create_company_tab_2_label_country') }}
                                                    </label>
                                                    <select
                                                        class="select2 form-select"
                                                        id="u_country"
                                                        data-dictionary="country"
                                                        data-placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_country') }}"
                                                    >
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <div class="mb-1">
                                                    <label class="form-label" for="u_city">
                                                        {{ __('localization.onboarding_index_create_company_tab_2_label_city') }}
                                                    </label>
                                                    <select
                                                        class="select2 form-select"
                                                        id="u_city"
                                                        data-dictionary="settlement"
                                                        data-placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_city') }}"
                                                    >
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <div class="mb-1">
                                                    <label class="form-label" for="u_street">
                                                        {{ __('localization.onboarding_index_create_company_tab_2_label_street') }}
                                                    </label>
                                                    <select
                                                        class="select2 form-select"
                                                        id="u_street"
                                                        data-dictionary="street"
                                                        data-placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_street') }}"
                                                    >
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="u_building_number">
                                                    {{ __('localization.onboarding_index_create_company_tab_2_label_building_number') }}
                                                </label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="u_building_number"
                                                    name="buidling_number"
                                                    required
                                                    autocomplete="off"
                                                    placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_u_building_number') }}"
                                                />
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="code">
                                                    {{ __('localization.onboarding_index_create_company_tab_2_label_u_flat') }}
                                                </label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="u_flat"
                                                    name="flat"
                                                    required
                                                    autocomplete="off"
                                                    placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_u_flat') }}"
                                                />
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="u_gln">
                                                    {{ __('localization.onboarding_index_create_company_tab_2_label_u_gln') }}
                                                </label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="u_gln"
                                                    name="gln"
                                                    oninput="limitInputToNumbers(this,13)"
                                                    required
                                                    autocomplete="off"
                                                    placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_u_gln') }}"
                                                />
                                            </div>

                                            <div id="u-address-message"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-0">
                                    <div class="card-header px-0">
                                        <h4 class="card-title">
                                            {{ __('localization.onboarding_index_create_company_tab_2_label_account_data') }}
                                        </h4>
                                    </div>

                                    <div class="card-body my-25 px-0 pb-0">
                                        <div class="row">
                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="bank_2">
                                                    {{ __('localization.onboarding_index_create_company_tab_2_label_bank') }}
                                                </label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="bank_2"
                                                    placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_bank') }}"
                                                    required
                                                />
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="iban_2">
                                                    {{ __('localization.onboarding_index_create_company_tab_2_label_iban') }}
                                                </label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="iban_2"
                                                    oninput="maskNumbersPlusLatin(this,29)"
                                                    placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_iban') }}"
                                                    required
                                                />
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <label class="form-label" for="mfo_2">
                                                    {{ __('localization.onboarding_index_create_company_tab_2_label_mfo') }}
                                                </label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_mfo') }}"
                                                    oninput="limitInputToNumbers(this,6)"
                                                    id="mfo_2"
                                                    required
                                                />
                                            </div>

                                            <div class="col-12 col-sm-6 mb-1">
                                                <div class="mb-1">
                                                    <label
                                                        class="form-label"
                                                        for="select2-hide-search"
                                                    >
                                                        {{ __('localization.onboarding_index_create_company_tab_2_label_currency') }}
                                                    </label>
                                                    <select
                                                        class="select2 form-select"
                                                        id="currency_u"
                                                        data-dictionary="currencies"
                                                        data-placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_currency') }}"
                                                    >
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div id="requisite-message2"></div>
                                        </div>
                                    </div>
                                </div>

                                <hr />

                                <div class="card mb-0">
                                    <div class="card-header px-0">
                                        <h4 class="card-title">
                                            {{ __('localization.onboarding_index_create_company_tab_2_label_about_company') }}
                                        </h4>
                                    </div>
                                    <div class="card-body my-25 px-0 pb-0">
                                        <div class="row">
                                            <div class="col-12 col-sm-12 mb-1">
                                                <label class="form-label" for="about_2">
                                                    {{ __('localization.onboarding_index_create_company_tab_2_placeholder_about_company') }}
                                                </label>
                                                <textarea
                                                    class="form-control"
                                                    id="about_2"
                                                    rows="5"
                                                    placeholder="{{ __('localization.onboarding_index_create_company_tab_2_placeholder_about_company') }}"
                                                ></textarea>
                                            </div>

                                            <div id="about_company_message_2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 d-flex justify-content-between">
                        <button
                            class="btn btn-flat-secondary float-start"
                            id="back-find-company"
                            style="padding-top: 10px !important ; padding-bottom: 10px !important"
                        >
                            <img
                                style="margin-right: 0.5rem"
                                src="{{ asset('assets/icons/entity/onboarding/arrow-left.svg') }}"
                            />
                            {{ __('localization.onboarding_index_create_company_back') }}
                        </button>

                        <button id="onboarding_save" type="button" class="btn btn-primary me-1">
                            {{ __('localization.onboarding_index_create_company_create') }}
                        </button>

                        <button
                            id="onboarding_save_2"
                            type="button"
                            class="d-none btn btn-primary me-1"
                        >
                            {{ __('localization.onboarding_index_create_company_create') }}
                        </button>

                        <button id="edit_save" type="button" class="d-none btn btn-primary me-1">
                            {{ __('localization.onboarding_index_create_company_edit') }}
                        </button>

                        <button id="edit_save_2" type="button" class="d-none btn btn-primary me-1">
                            {{ __('localization.onboarding_index_create_company_edit') }}
                        </button>
                    </div>
                </div>
            </div>

            <div
                class="d-none d-sm-none d-md-none bg-secondary-100 d-lg-block col-4 col-sm-4 col-md-4 col-lg-4 col-xxl-4 px-3"
            >
                <div class="d-flex justify-content-center align-items-center h-100 flex-column">
                    <img
                        width="100%"
                        style="max-width: max-content"
                        src="{{ asset('assets/icons/entity/onboarding/onboarding-company.svg') }}"
                    />
                    <h4 class="align-self-start mt-2 fw-bolder">
                        {{ __('localization.onboarding_index_title_how_it_works') }}
                    </h4>
                    <p>
                        {{ __('localization.onboarding_index_description_how_it_works') }}
                    </p>
                    <a
                        data-bs-toggle="modal"
                        data-bs-target="#modalForSendSupport"
                        href="#"
                        class="align-self-start fw-bolder"
                    >
                        {{ __('localization.onboarding_index_link_contact_us') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div
    class="container-fluid d-none create-workspace py-0 d-flex align-items-center"
    style="height: 100vh"
    id="create-workspace"
>
    <div class="card my-0">
        <div class="row mx-0" style="height: 851px">
            <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 col-xxl-8 px-2 py-3">
                <div class="row mx-0 h-100 justify-content-between flex-column">
                    <div class="col-md-12 col-12">
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
                        <div class="col-md-12 col-12">
                            <div class="col-md-12 col-12 my-2">
                                <h2 class="fw-bolder">
                                    {{ __('localization.onboarding_index_create_workspace_create_workspace') }}
                                </h2>
                                <div>
                                    {{ __('localization.onboarding_index_create_workspace_instructions') }}
                                </div>
                            </div>

                            <div class="col-12 col-sm-12">
                                <div
                                    class="d-flex flex-column"
                                    style="max-height: 476px; overflow-y: auto"
                                >
                                    <div class="row mx-0">
                                        <a
                                            class="col-12 px-0 border onboarding-item-company mt-1"
                                            style="border-radius: 6px"
                                        >
                                            <div class="row mx-0">
                                                <div class="col-auto p-0">
                                                    <div
                                                        class="p-0"
                                                        style="
                                                            background-color: #a8aaae14;
                                                            width: 138px;
                                                        "
                                                    >
                                                        <img
                                                            width="138px"
                                                            height="138px"
                                                            id="company-img"
                                                            src="{{ asset('assets/icons/entity/onboarding/building-community.svg') }}"
                                                        />
                                                    </div>
                                                </div>

                                                <div class="col-9 py-1 flex-grow-1">
                                                    <div
                                                        class="d-flex align-items-center"
                                                        style="gap: 12px"
                                                    >
                                                        <h4
                                                            class="fw-bolder mb-0"
                                                            id="title-company"
                                                        ></h4>
                                                        <span class="badge badge-light-primary">
                                                            {{ __('localization.onboarding_index_create_workspace_no_administrator') }}
                                                        </span>
                                                    </div>

                                                    <div
                                                        class="d-flex align-items-center mt-1"
                                                        style="gap: 5px; font-size: 15px !important"
                                                        id="title-payment-details"
                                                    ></div>

                                                    <div
                                                        class="d-flex align-items-center"
                                                        style="
                                                            gap: 5px;
                                                            margin-top: 6px;
                                                            font-size: 15px !important;
                                                        "
                                                    >
                                                        <p class="mb-0 fw-normal">
                                                            {{ __('localization.onboarding_index_create_workspace_registration_country') }}
                                                        </p>
                                                        <p
                                                            class="fw-bold mb-0"
                                                            id="title-country"
                                                        ></p>
                                                    </div>

                                                    <div
                                                        class="d-flex align-items-center"
                                                        style="
                                                            gap: 5px;
                                                            margin-top: 6px;
                                                            font-size: 15px !important;
                                                        "
                                                    >
                                                        <p class="mb-0 fw-normal">
                                                            {{ __('localization.onboarding_index_create_workspace_added_to_consolid') }}{{ config('app.name') }}
                                                            :
                                                        </p>
                                                        <p class="fw-bold mb-0">
                                                            {{ now()->format('d.m.Y') }}
                                                        </p>
                                                        <p class="mb-0 fw-normal">
                                                            {{ __('localization.onboarding_index_create_workspace_by_user') }}
                                                        </p>
                                                        <p class="fw-bold mb-0" id="full-name-user">
                                                            {{-- <!-- {{ --}}
                                                            {{-- Auth::user()->surname . --}}
                                                            {{-- ' ' . --}}
                                                            {{-- mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) . --}}
                                                            {{-- '.' . --}}
                                                            {{-- mb_strtoupper(mb_substr(Auth::user()->patronymic, 0, 1)) --}}
                                                            {{-- }} --> --}}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 d-flex justify-content-between">
                        <button
                            class="btn btn-flat-secondary float-start"
                            id="back-create-new-company"
                            style="padding-top: 10px !important ; padding-bottom: 10px !important"
                        >
                            <img
                                style="margin-right: 0.5rem"
                                src="{{ asset('assets/icons/entity/onboarding/arrow-left.svg') }}"
                            />
                            {{ __('localization.onboarding_index_create_workspace_back') }}
                        </button>

                        <a
                            id="condition_submit"
                            class="btn btn-primary py-0 float-end"
                            style="padding-top: 10px !important ; padding-bottom: 10px !important"
                        >
                            {{ __('localization.onboarding_index_create_workspace_start') }}
                            <img
                                style="margin-left: 5px"
                                src="{{ asset('assets/icons/entity/onboarding/arrow-right.svg') }}"
                            />
                        </a>
                    </div>
                </div>
            </div>

            <div
                class="d-none d-sm-none d-md-none d-lg-block col-4 col-sm-4 col-md-4 col-lg-4 col-xxl-4 px-3"
            >
                <div class="d-flex justify-content-center align-items-center h-100 flex-column">
                    <img
                        width="100%"
                        style="max-width: max-content"
                        src="{{ asset('assets/icons/entity/onboarding/onboarding-company.svg') }}"
                    />
                    <h4 class="align-self-start mt-2 fw-bolder">
                        {{ __('localization.onboarding_index_create_workspace_how_it_works') }}
                    </h4>
                    <p>
                        {{ __('localization.onboarding_index_create_workspace_how_it_works_text') }}
                    </p>
                    <a
                        data-bs-toggle="modal"
                        data-bs-target="#modalForSendSupport"
                        href="#"
                        class="align-self-start fw-bolder"
                    >
                        {{ __('localization.onboarding_index_create_workspace_contact_us') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div
    class="container-fluid d-none send-join py-0 d-flex align-items-center"
    style="height: 100vh"
    id="send-join"
>
    <div class="card my-0">
        <div class="row mx-0" style="height: 851px">
            <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 col-xxl-8 px-2 py-3">
                <div class="row mx-0 h-100 justify-content-between flex-column">
                    <div class="col-md-12 col-12">
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
                        <div class="col-md-12 col-12">
                            <div class="col-md-12 col-12 my-2">
                                <h2 class="fw-bolder">
                                    {{ __('localization.onboarding_index_send_join_send_request') }}
                                </h2>
                                <div>
                                    {{ __('localization.onboarding_index_send_join_instructions') }}
                                </div>
                            </div>

                            <div class="col-12 col-sm-12">
                                <div
                                    class="d-flex flex-column"
                                    style="max-height: 476px; overflow-y: auto"
                                >
                                    <div class="row mx-0">
                                        <a
                                            class="col-12 px-0 border onboarding-item-company mt-1"
                                            style="border-radius: 6px"
                                            id="request-company-card"
                                        ></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 d-flex justify-content-between">
                        <button
                            class="btn btn-flat-secondary float-start"
                            id="back-find-company-2"
                            style="padding-top: 10px !important ; padding-bottom: 10px !important"
                        >
                            <img
                                style="margin-right: 0.5rem"
                                src="{{ asset('assets/icons/entity/onboarding/arrow-left.svg') }}"
                            />
                            {{ __('localization.onboarding_index_send_join_back') }}
                        </button>

                        <button
                            data-bs-toggle="modal"
                            data-bs-target="#animation-2"
                            class="btn btn-primary py-0 float-end"
                            id="send-request"
                            user-id="{{ Auth::id() }}"
                            style="padding-top: 10px !important ; padding-bottom: 10px !important"
                        >
                            {{ __('localization.onboarding_index_send_join_send') }}
                            <img
                                style="margin-left: 5px"
                                src="{{ asset('assets/icons/entity/onboarding/arrow-right.svg') }}"
                            />
                        </button>

                        <div
                            class="modal text-start"
                            id="animation-2"
                            tabindex="-1"
                            aria-labelledby="myModalLabel6"
                            aria-hidden="true"
                        >
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header pt-4"></div>
                                    <div class="card popup-card">
                                        <div class="popup-header">
                                            {{ __('localization.onboarding_index_send_join_request_sent') }}
                                        </div>
                                        <div class="card-body px-4 pb-4">
                                            <div class="row">
                                                <div class="col-12 col-sm-12 mb-1">
                                                    <p>
                                                        {{ __('localization.onboarding_index_send_join_wait_for_confirmation') }}
                                                    </p>
                                                </div>
                                                <div class="col-12 mt-1">
                                                    <div class="d-flex float-end">
                                                        <a
                                                            href="{{ route('workspaces.index') }}"
                                                            class="btn btn-primary"
                                                            id="condition_apply_submit"
                                                        >
                                                            {{ __('localization.onboarding_index_send_join_understood') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="d-none d-sm-none d-md-none d-lg-block bg-secondary-100 col-4 col-sm-4 col-md-4 col-lg-4 col-xxl-4 px-3"
            >
                <div class="d-flex justify-content-center align-items-center h-100 flex-column">
                    <img
                        width="100%"
                        style="max-width: max-content"
                        src="{{ asset('assets/icons/entity/onboarding/onboarding-company.svg') }}"
                    />
                    <h4 class="align-self-start mt-2 fw-bolder">
                        {{ __('localization.onboarding_index_send_join_how_it_works') }}
                    </h4>
                    <p>{{ __('localization.onboarding_index_send_join_how_it_works_text') }}</p>
                    <a
                        data-bs-toggle="modal"
                        data-bs-target="#modalForSendSupport"
                        href="#"
                        class="align-self-start fw-bolder"
                    >
                        {{ __('localization.onboarding_index_send_join_contact_us') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модалка "Зв‘язатись з адміністратором" -->
<x-modal.support-modal
    modalId="modalForSendSupport"
    titleKey="localization.onboarding_index_modal_for_send_support_title"
    subtitleKey="localization.onboarding_index_modal_for_send_support_description"
    emailLabelKey="localization.onboarding_index_modal_for_send_support_email_label"
    emailPlaceholderKey="localization.onboarding_index_modal_for_send_support_email_placeholder"
    emailLinkKey="localization.onboarding_index_modal_for_send_support_use_phone"
    phoneLabelKey="localization.onboarding_index_modal_for_send_support_phone_label"
    phonePlaceholderKey="+"
    phoneLinkKey="localization.onboarding_index_modal_for_send_support_use_email"
    contactInfoKey="localization.onboarding_index_modal_for_send_support_contact_manually"
    phoneNumberKey="localization.onboarding_index_modal_for_send_support_phone"
    phoneNumberHref="tel:+38000000"
    phoneNumberDisplay="+38 (088) 888 88 88"
    emailAddressKey="localization.onboarding_index_modal_for_send_support_email"
    emailAddressHref="mailto:abc@example.com"
    emailAddressDisplay="example@email.com"
    cancelButtonKey="localization.onboarding_index_modal_for_send_support_cancel"
    sendButtonKey="localization.onboarding_index_modal_for_send_support_submit"
/>
