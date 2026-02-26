@extends('layouts.admin')
@section('title', __('localization.contract_create_title'))

@section('page-style')
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>

    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}"
    />
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
        type="text/css"
        href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}"
    />
@endsection

@section('before-style')
    
@endsection

@section('content')
    <div class="mb-2 mx-2 px-0">
        <!-- Навігація з кнопками та діями головними -->
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-slash">
                        <li class="breadcrumb-item">
                            <a href="/contracts" class="text-secondary">
                                {{ __('localization.contract_create_breadcrumb_contracts') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item fw-bolder active" aria-current="page">
                            {{ __('localization.contract_create_breadcrumb_create_new') }}
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="contract-actions d-flex align-items-center gap-2">
                <div>
                    <div class="btn-group d-flex gap-1">
                        <button
                            class="btn btn-flat-secondary rounded"
                            disabled
                            id="btn-reject"
                            href="#"
                        >
                            {{ __('localization.contract_create_button_cancel') }}
                        </button>
                        <button class="btn btn-primary rounded" disabled id="btn-save" href="#">
                            {{ __('localization.contract_create_button_save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Контент -->
        <div class="row mx-0 mt-2">
            <div class="col-12 col-md-12 col-lg-5 pe-0 ps-0">
                <div class="card p-2 mb-1">
                    <h4 class="card-title fw-bolder pt-0">
                        {{ __('localization.contract_create_contract_info_title', ['contractId' => $contractId]) }}
                    </h4>
                    <div class="row mb-1">
                        <div class="col-7">
                            <label class="form-label" for="typeContract">
                                {{ __('localization.contract_create_contract_info_label_type') }}
                            </label>
                            <select
                                class="select2 form-select"
                                name="typeContract"
                                id="typeContract"
                                data-placeholder="{{ __('localization.contract_create_contract_info_placeholder_type') }}"
                            >
                                <option value=""></option>
                                <option value="0">
                                    {{ __('localization.contract_create_contract_info_option_type_0') }}
                                </option>
                                <option value="1">
                                    {{ __('localization.contract_create_contract_info_option_type_1') }}
                                </option>
                                <option value="2">
                                    {{ __('localization.contract_create_contract_info_option_type_2') }}
                                </option>
                            </select>
                        </div>
                        <div class="col-5 ps-0">
                            <label class="form-label" for="side">
                                {{ __('localization.contract_create_contract_info_label_side') }}
                            </label>
                            <select
                                class="select2 form-select"
                                name="side"
                                id="side"
                                data-placeholder="{{ __('localization.contract_create_contract_info_placeholder_side') }}"
                            >
                                <option value=""></option>
                                <option value="0">
                                    {{ __('localization.contract_create_contract_info_option_side_0') }}
                                </option>
                                <option value="1">
                                    {{ __('localization.contract_create_contract_info_option_side_1') }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="yourCompany">
                            {{ __('localization.contract_create_contract_info_label_company') }}
                        </label>
                        <select
                            class="select2 form-select"
                            name="yourCompany"
                            id="yourCompany"
                            data-placeholder="{{ __('localization.contract_create_contract_info_placeholder_company') }}"
                        >
                            <option value=""></option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="contractor">
                            {{ __('localization.contract_create_contract_info_label_contractor') }}
                        </label>
                        <select
                            class="select2 form-select"
                            name="contractor"
                            id="contractor"
                            data-placeholder="{{ __('localization.contract_create_contract_info_placeholder_contractor') }}"
                        >
                            <option value=""></option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-1 position-relative">
                        <label class="form-label" for="validityPeriod">
                            {{ __('localization.contract_create_contract_info_label_validity') }}
                        </label>
                        <input
                            type="text"
                            id="validityPeriod"
                            class="form-control flatpickr-basic flatpickr-input"
                            required
                            placeholder="{{ __('localization.contract_create_contract_info_placeholder_validity') }}"
                            name="group"
                            readonly="readonly"
                        />
                        <span
                            class="cursor-pointer text-secondary position-absolute top-50"
                            style="right: 27px; pointer-events: none"
                        >
                            <i data-feather="calendar"></i>
                        </span>
                    </div>
                    <div class="form-check form-switch mb-1">
                        <input type="checkbox" class="form-check-input" id="contractSigned" />
                        <label class="form-check-label" for="contractSigned">
                            {{ __('localization.contract_create_contract_info_label_signed') }}
                        </label>
                    </div>
                    <div class="mb-1 position-relative">
                        <label class="form-label" for="dateSigningContract">
                            {{ __('localization.contract_create_contract_info_label_date_signed') }}
                        </label>
                        <input
                            type="text"
                            id="dateSigningContract"
                            class="form-control flatpickr-basic flatpickr-input"
                            required
                            placeholder="{{ __('localization.contract_create_contract_info_placeholder_date_signed') }}"
                            name="group"
                            readonly="readonly"
                        />
                        <span
                            class="cursor-pointer text-secondary position-absolute top-50"
                            style="right: 27px; pointer-events: none"
                        >
                            <i data-feather="calendar"></i>
                        </span>
                    </div>
                    <div>
                        <label class="form-label" for="fileInput">
                            {{ __('localization.contract_create_contract_info_label_file') }}
                        </label>
                        <input type="file" class="form-control" id="fileInput" />
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 pe-0 ps-lg-1 ps-0 flex-grow-1">
                <div class="col-xl-12 col-lg-12 h-100">
                    <div class="card h-100">
                        <div class="ps-2 pt-2">
                            <h4 class="fw-bolder">
                                {{ __('localization.contract_create_regulations_info_title') }}
                            </h4>
                        </div>
                        <div class="contract-view-tables h-100">
                            <!-- початкові умови -->
                            <div id="initialConditions" class="d-none h-100">
                                <hr />
                                <div class="h-100 d-flex flex-column justify-content-center px-2">
                                    <h4 class="text-center fw-bolder">
                                        {{ __('localization.contract_create_regulations_info_initial_conditions_title') }}
                                    </h4>
                                    <p class="mb-0 text-center">
                                        {{ __('localization.contract_create_regulations_info_initial_conditions_description') }}
                                    </p>
                                </div>
                            </div>

                            <!-- відсутні регламенти -->
                            <div id="missingRegulations" class="d-none h-100">
                                <hr />
                                <div
                                    class="h-100 d-flex flex-column align-items-center justify-content-center px-2"
                                >
                                    <h4 class="fw-bolder">
                                        {{ __('localization.contract_create_regulations_info_missing_title') }}
                                    </h4>
                                    <p class="text-center">
                                        {{ __('localization.contract_create_regulations_info_missing_description_text_1') }}
                                        <span id="missingRegulationsTitleType">
                                            {{ __('localization.contract_create_regulations_info_missing_description_text_template_type') }}
                                        </span>
                                        {{ __('localization.contract_create_regulations_info_missing_description_text_2') }}
                                        <br />
                                        {{ __('localization.contract_create_regulations_info_missing_description_text_3') }}
                                        “
                                        <span id="missingRegulationsTitleSide">
                                            {{ __('localization.contract_create_regulations_info_missing_description_text_template_side') }}
                                        </span>
                                        ”
                                    </p>
                                    <button
                                        id="create-regulation-missing"
                                        type="button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#createNewRegulationModal"
                                        class="btn btn-outline-primary"
                                    >
                                        <i data-feather="plus" class="me-25"></i>
                                        <span>
                                            {{ __('localization.contract_create_regulations_info_btn_create') }}
                                        </span>
                                    </button>
                                </div>
                            </div>

                            <!-- регламенти -->
                            <div id="retail-list-regulations" class="d-none h-100">
                                <div class="p-2 pb-0 mb-2">
                                    <h2 class="f-15 fw-bolder mb-1">
                                        <span id="retail-list-regulations-type-text">
                                            {{ __('localization.contract_create_regulations_info_retail_list_title_type_text') }}
                                        </span>
                                        {{ __('localization.contract_create_regulations_info_retail_list_title') }}
                                        (
                                        <span id="retail-list-regulations-side">
                                            {{ __('localization.contract_create_regulations_info_retail_list_title_side_text') }}
                                        </span>
                                        )
                                    </h2>
                                    <div
                                        class="input-group input-group-merge mb-2"
                                        style="max-width: 350px"
                                    >
                                        <span class="input-group-text">
                                            <i data-feather="search"></i>
                                        </span>
                                        <input
                                            type="text"
                                            class="form-control ps-2"
                                            placeholder="{{ __('localization.contract_create_regulations_info_search_placeholder') }}"
                                            id="search-retail-regulation"
                                        />
                                    </div>
                                </div>

                                <hr class="mb-0" />
                                <ul class="container-for-market-list list-s-none"></ul>
                            </div>

                            <!-- один регламент-->
                            <div id="one-retail-regulation" class="d-none h-100">
                                <div
                                    class="p-2 pb-0 d-flex justify-content-between align-items-center"
                                    style="height: 60px"
                                >
                                    <h2 class="f-15 fw-bolder">
                                        <a
                                            href="#"
                                            class="text-black"
                                            id="link-to-back-retail-list"
                                        >
                                            <i
                                                data-feather="arrow-left"
                                                class="me-25 cursor-pointer"
                                            ></i>
                                        </a>
                                        <span id="one-regulation-name">
                                            {{ __('localization.contract_create_regulations_info_one_regulation_back') }}
                                        </span>

                                        <span>
                                            (
                                            <span id="one-regulation-side-regulation">
                                                {{ __('localization.contract_create_regulations_info_one_regulation_template_name') }}
                                            </span>
                                            {{ __('localization.contract_create_regulations_info_one_regulation_side_name') }})
                                        </span>
                                    </h2>
                                    <button
                                        class="d-none btn btn-outline-primary btn-sm"
                                        id="btn-cancel-changes"
                                    >
                                        {{ __('localization.contract_create_regulations_info_one_regulation_cancel_changes') }}
                                    </button>
                                </div>
                                <hr class="mb-0" />
                                <div class="accordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header px-1" id="">
                                            <button
                                                class="accordion-button fw-bolder f-15"
                                                style="color: #4b465c"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#accordionOne"
                                                aria-expanded="true"
                                                aria-controls="accordionOne"
                                            >
                                                {{ __('localization.contract_create_regulations_info_accordion_section_1') }}
                                            </button>
                                        </h2>
                                        <div
                                            id="accordionOne"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="headingOne"
                                            data-bs-parent="#accordionExample"
                                        >
                                            <div class="accordion-body px-2 ps-3">
                                                <div class="row">
                                                    <div class="col-12 col-sm-6 mb-1">
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            id="nameRetail"
                                                            required
                                                            placeholder="{{ __('localization.contract_create_regulations_info_accordion_section_1_name_placeholder') }}"
                                                        />
                                                    </div>
                                                    <div class="col-12 col-sm-6 mb-1">
                                                        <div class="mb-1">
                                                            <select
                                                                class="select2 form-select"
                                                                id="parentRegulation"
                                                                data-placeholder="{{ __('localization.contract_create_regulations_info_accordion_section_1_parent_placeholder') }}"
                                                            >
                                                                <option value=""></option>
                                                                <option value="parent">
                                                                    {{ __('localization.contract_create_regulations_info_accordion_section_1_parent_option') }}
                                                                </option>
                                                                @foreach ($regulations as $regulation)
                                                                    <option
                                                                        value="{{ $regulation->id }}"
                                                                    >
                                                                        {{ $regulation->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header px-1" id="">
                                            <button
                                                class="accordion-button fw-bolder f-15"
                                                style="color: #4b465c"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#accordionTwo"
                                                aria-expanded="true"
                                                aria-controls="accordionTwo"
                                            >
                                                {{ __('localization.contract_create_regulations_info_accordion_section_2') }}
                                            </button>
                                        </h2>
                                        <div
                                            id="accordionTwo"
                                            class="accordion-collapse collapse"
                                            aria-labelledby="headingTwo"
                                            data-bs-parent="#accordionExample"
                                        >
                                            <div class="accordion-body px-2">
                                                <div
                                                    class="d-flex align-items-center justify-content-between ps-2 py-1"
                                                >
                                                    <p class="f-15 m-0" style="color: #a5a3ae">
                                                        {{ __('localization.contract_create_regulations_info_accordion_section_type_pallet') }}
                                                    </p>
                                                    <div style="width: 260px">
                                                        <select
                                                            class="select2 form-select"
                                                            id="typePallet"
                                                            data-placeholder="{{ __('localization.contract_create_regulations_info_accordion_section_type_pallet_placeholder') }}"
                                                        >
                                                            <option value=""></option>

                                                            @php
                                                                $typePallets = [
                                                                    'evropaleta_120h80sm' => 'contract_regulations_pallet_type_1',
                                                                    'amerikans\'ka_paleta_120h100sm' => 'contract_regulations_pallet_type_2',
                                                                    'napivpaleta_60h80sm' => 'contract_regulations_pallet_type_3',
                                                                    'fins\'ka_paleta' => 'contract_regulations_pallet_type_4',
                                                                ];
                                                            @endphp

                                                            @foreach ($typePallets as $key => $value)
                                                                <option value="{{ $key }}">
                                                                    {{ isset($typePallets[$key]) ? __('localization.' . $typePallets[$key]) : '-' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div
                                                    class="d-flex align-items-center justify-content-between ps-2 py-1"
                                                >
                                                    <p class="f-15 m-0" style="color: #a5a3ae">
                                                        {{ __('localization.contract_create_regulations_info_accordion_section_height_pallet') }}
                                                    </p>
                                                    <div class="input-group" style="width: 260px">
                                                        <input
                                                            type="number"
                                                            class="form-control"
                                                            id="heightPallet"
                                                        />
                                                        <span class="input-group-text">
                                                            {{ __('localization.contract_create_regulations_info_accordion_section_height_pallet_measure_unit') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div
                                                    class="d-flex align-items-center justify-content-between ps-2 py-1"
                                                >
                                                    <p class="f-15 m-0" style="color: #a5a3ae">
                                                        {{ __('localization.contract_create_regulations_info_accordion_section_remaining_term') }}
                                                    </p>
                                                    <div class="input-group" style="width: 260px">
                                                        <input
                                                            type="number"
                                                            class="form-control"
                                                            id="remainingTerm"
                                                        />
                                                        <span class="input-group-text">
                                                            {{ __('localization.contract_create_regulations_info_accordion_section_remaining_term_measure_unit') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div
                                                    class="d-flex align-items-center justify-content-between ps-2 py-1"
                                                >
                                                    <p class="f-15 m-0" style="color: #a5a3ae">
                                                        {{ __('localization.contract_create_regulations_info_accordion_section_pallet_latter') }}
                                                    </p>
                                                    <div class="form-check form-switch">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input"
                                                            id="palletLatter"
                                                        />
                                                    </div>
                                                </div>
                                                <div
                                                    class="d-flex align-items-center justify-content-between ps-2 py-1"
                                                >
                                                    <p class="f-15 m-0" style="color: #a5a3ae">
                                                        {{ __('localization.contract_create_regulations_info_accordion_section_allow_prefabricated_pallets') }}
                                                    </p>
                                                    <div class="form-check form-switch">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input"
                                                            id="allowPrefabricatedPallets"
                                                        />
                                                    </div>
                                                </div>
                                                <div
                                                    class="d-flex align-items-center justify-content-between ps-2 py-1"
                                                >
                                                    <p class="f-15 m-0" style="color: #a5a3ae">
                                                        {{ __('localization.contract_create_regulations_info_accordion_section_allow_sendwich_pallet') }}
                                                    </p>
                                                    <div class="form-check form-switch">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input"
                                                            id="allowSendwichPallet"
                                                        />
                                                    </div>
                                                </div>
                                                <div
                                                    class="d-flex align-items-center justify-content-between ps-2 py-1"
                                                >
                                                    <p class="f-15 m-0" style="color: #a5a3ae">
                                                        {{ __('localization.contract_create_regulations_info_accordion_section_labeling') }}
                                                    </p>
                                                    <div class="form-check form-switch">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input"
                                                            id="labeling"
                                                        />
                                                    </div>
                                                </div>
                                                <div
                                                    class="d-flex align-items-center justify-content-between ps-2 py-1"
                                                >
                                                    <p class="f-15 m-0" style="color: #a5a3ae">
                                                        {{ __('localization.contract_create_regulations_info_accordion_section_allow_condacting') }}
                                                    </p>
                                                    <div class="form-check form-switch">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input"
                                                            id="allowCondacting"
                                                        />
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
        </div>
    </div>

    <!-- Amendments to the regulations-->
    <div
        class="modal fade"
        id="amendedChangesModal"
        tabindex="-1"
        aria-labelledby="amendedChangesModal"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-md" style="max-width: 580px">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="p-4 pt-2">
                    <h2 class="mb-0 mt-0 text-center fw-bolder">
                        {{ __('localization.contract_create_amended_changes_modal_title') }}
                    </h2>
                    <div class="p-2">
                        <p class="mb-1 text-start">
                            {{ __('localization.contract_create_amended_changes_modal_message') }}
                        </p>
                    </div>
                    <form class="d-flex justify-content-end" method="" action="#">
                        <a
                            class="btn btn-outline-primary mr-2 text-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#createNewRegulationModal"
                            id="btn-create-new-regulation-in-modal"
                        >
                            {{ __('localization.contract_create_amended_changes_modal_button_create') }}
                        </a>
                        <button type="button" class="btn btn-primary" id="update-regulation">
                            {{ __('localization.contract_create_amended_changes_modal_button_update') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Create new regulation-->
    <div
        class="modal fade"
        id="createNewRegulationModal"
        tabindex="-1"
        aria-labelledby="createNewRegulationModal"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-md" style="max-width: 580px">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="p-4 pt-2">
                    <h2 class="mb-1 mt-0 text-center fw-bolder">
                        {{ __('localization.contract_create_new_create_regulation_modal_title') }}
                    </h2>
                    <p class="mb-1 text-center">
                        {{ __('localization.contract_create_new_create_regulation_modal_description') }}
                    </p>
                    <form class="pt-2" method="" action="#">
                        <div class="mb-1">
                            <label class="form-label" for="nameRetailInModal">
                                {{ __('localization.contract_create_new_create_regulation_modal_label_name') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="nameRetailInModal"
                                required
                                placeholder="{{ __('localization.contract_create_new_create_regulation_modal_placeholder_name') }}"
                            />
                        </div>
                        <div class="mb-2">
                            <label class="form-label" for="parentRegulationInModal">
                                {{ __('localization.contract_create_new_create_regulation_modal_label_parent') }}
                            </label>
                            <select
                                class="select2 form-select"
                                name="parentRegulationInModal"
                                id="parentRegulationInModal"
                                data-placeholder="{{ __('localization.contract_create_new_create_regulation_modal_placeholder_parent') }}"
                            >
                                <option value=""></option>
                                <option selected value="parent">
                                    {{ __('localization.contract_create_new_create_regulation_modal__parent_option') }}
                                </option>
                                @foreach ($regulations as $regulation)
                                    <option value="{{ $regulation->id }}">
                                        {{ $regulation->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" id="create-regulation">
                                {{ __('localization.contract_create_new_create_regulation_modal_button_create') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/pickers/form-pickers.js') }}"></script>

    <script
        type="module"
        src="{{ asset('assets/js/entity/contract/contract-create.js') }}"
    ></script>
@endsection
