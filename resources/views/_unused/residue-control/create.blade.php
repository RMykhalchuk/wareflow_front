@extends('layouts.admin')
@section('title', __('localization.residue_control_create_title'))

@section('page-style')
    
@endsection

@section('before-style')
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

@section('content')
    <div class="container-fluid px-3">
        <div class="row" style="column-gap: 144px">
            <div
                class="col-12 col-sm-12 col-md-3 col-lg-3 col-xxl-3 px-0"
                style="min-width: 208px; max-width: fit-content"
            >
                @include('layouts.setting')
            </div>
            <div class="col-12 col-sm-12 col-md-9 col-lg-9 col-xxl-9 px-0" style="max-width: 798px">
                <div class="tab-content card pb-0">
                    <div
                        role="tabpanel"
                        class="tab-pane mb-0 active"
                        id="vertical-pill-4"
                        aria-labelledby="stacked-pill-4"
                        aria-expanded="true"
                    >
                        {{-- All Rule --}}
                        <div id="all-rule">
                            <div
                                class="p-2 pb-50 d-flex justify-content-between align-items-center"
                            >
                                <div>
                                    <h4 class="fw-bolder mb-0">
                                        {{ __('localization.residue_control_create_all_rule_title') }}
                                    </h4>
                                    <p class="fs-5 mt-50">
                                        {{ __('localization.residue_control_create_all_rule_description') }}
                                    </p>
                                </div>
                                <button class="btn btn-outline-primary" id="createNewRules">
                                    {{ __('localization.residue_control_create_all_rule_create_button') }}
                                </button>
                            </div>

                            <div class="p-2 pt-0 d-flex justify-content-between align-items-center">
                                <div class="col-6">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text" id="basic-addon-search2">
                                            <i data-feather="search"></i>
                                        </span>
                                        <input
                                            type="text"
                                            class="form-control ps-1"
                                            placeholder="{{ __('localization.residue_control_create_all_rule_search_placeholder') }}"
                                            aria-label="Search..."
                                            aria-describedby="basic-addon-search2"
                                            id="searchTypeRules"
                                        />
                                    </div>
                                </div>

                                <button
                                    data-bs-toggle="dropdown"
                                    id="filter-rule-button"
                                    href="javascript:void(0);"
                                    class="btn btn-flat-secondary dropdown-toggle"
                                >
                                    {{ __('localization.residue_control_create_all_rule_filter_button') }}
                                </button>

                                <div
                                    id="filterDropdown"
                                    class="dropdown-menu dropdown-menu-end mt-2"
                                    aria-labelledby="filter-rule-button"
                                >
                                    <div
                                        class="modal-dialog modal-dialog-centered"
                                        style="max-width: 460px !important"
                                    >
                                        <div class="modal-content">
                                            <div class="card popup-card p-2">
                                                <h4 class="fw-bolder">
                                                    {{ __('localization.residue_control_create_all_rule_filter_title') }}
                                                </h4>
                                                <div class="card-body row mx-0 p-0">
                                                    <p class="my-2 p-0">
                                                        {{ __('localization.residue_control_create_all_rule_filter_criteria') }}
                                                    </p>

                                                    <div
                                                        class="p-0 mb-2 d-flex justify-content-between"
                                                        id="radioFilterBlock"
                                                    >
                                                        <div class="form-check form-check-primary">
                                                            <input
                                                                type="radio"
                                                                id="radioTypeAllFilter"
                                                                name="radioTypeAllFilter"
                                                                class="form-check-input"
                                                                checked
                                                            />
                                                            <label
                                                                class="form-check-label"
                                                                for="radioTypeAllFilter"
                                                            >
                                                                {{ __('localization.residue_control_create_all_rule_filter_all') }}
                                                            </label>
                                                        </div>

                                                        <div class="form-check form-check-primary">
                                                            <input
                                                                type="radio"
                                                                id="radioTypeClientsFilter"
                                                                name="radioTypeClientsFilter"
                                                                class="form-check-input"
                                                            />
                                                            <label
                                                                class="form-check-label"
                                                                for="radioTypeClientsFilter"
                                                            >
                                                                {{ __('localization.residue_control_create_all_rule_filter_by_client_type') }}
                                                            </label>
                                                        </div>

                                                        <div class="form-check form-check-primary">
                                                            <input
                                                                type="radio"
                                                                id="radioTypeSpecificClientsFilter"
                                                                name="radioTypeSpecificClientsFilter"
                                                                class="form-check-input"
                                                            />
                                                            <label
                                                                class="form-check-label"
                                                                for="radioTypeSpecificClientsFilter"
                                                            >
                                                                {{ __('localization.residue_control_create_all_rule_filter_by_specific_client') }}
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="d-flex flex-column col-12 mb-1 p-0"
                                                        id="filter-rule-type-block"
                                                    >
                                                        <label
                                                            class="form-label mb-0 col-5"
                                                            for="select2-hide-search"
                                                        >
                                                            {{ __('localization.residue_control_create_all_rule_filter_type') }}
                                                        </label>
                                                        <div
                                                            class="row mx-0 flex-grow-1 justify-content-end mt-50"
                                                        >
                                                            <div class="col-12 px-0">
                                                                <select
                                                                    class="select2 hide-search form-select"
                                                                    id="filter-rule-type"
                                                                    data-placeholder="{{ __('localization.residue_control_create_all_rule_filter_select_type') }}"
                                                                >
                                                                    <option value=""></option>
                                                                    <option value="1">
                                                                        {{ __('localization.residue_control_create_all_rule_filter_all_option') }}
                                                                    </option>
                                                                    <option value="2">
                                                                        {{ __('localization.residue_control_create_all_rule_filter_shops') }}
                                                                    </option>
                                                                    <option value="3">
                                                                        {{ __('localization.residue_control_create_all_rule_filter_distributors') }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="d-flex flex-column col-12 mb-1 p-0"
                                                        id="filter-rule-clients-block"
                                                    >
                                                        <label
                                                            class="form-label mb-0 col-5"
                                                            for="select2-hide-search"
                                                        >
                                                            {{ __('localization.residue_control_create_all_rule_filter_client') }}
                                                        </label>
                                                        <div
                                                            class="row mx-0 flex-grow-1 justify-content-end mt-50"
                                                        >
                                                            <div class="col-12 px-0">
                                                                <select
                                                                    class="select2 hide-search form-select"
                                                                    id="filter-rule-clients"
                                                                    data-placeholder="{{ __('localization.residue_control_create_all_rule_filter_select_client') }}"
                                                                >
                                                                    <option value=""></option>
                                                                    <option value="1">
                                                                        {{ __('localization.residue_control_create_all_rule_filter_select_client_all_option') }}
                                                                    </option>
                                                                    <option value="2">
                                                                        {{ __('localization.residue_control_create_all_rule_filter_select_client_all_option_company_name_1') }}
                                                                    </option>
                                                                    <option value="3">
                                                                        {{ __('localization.residue_control_create_all_rule_filter_select_client_all_option_company_name_2') }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="d-flex flex-column col-12 mb-1 p-0"
                                                        id="filter-rule-goods-block"
                                                    >
                                                        <label
                                                            class="form-label mb-0 col-5"
                                                            for="select2-hide-search"
                                                        >
                                                            {{ __('localization.residue_control_create_all_rule_filter_goods') }}
                                                        </label>
                                                        <div
                                                            class="row mx-0 flex-grow-1 justify-content-end mt-50"
                                                        >
                                                            <div class="col-12 px-0">
                                                                <select
                                                                    class="select2 hide-search form-select"
                                                                    id="filter-rule-goods"
                                                                    data-placeholder="{{ __('localization.residue_control_create_all_rule_filter_select_goods') }}"
                                                                >
                                                                    <option value=""></option>
                                                                    <option value="1">
                                                                        {{ __('localization.residue_control_create_all_rule_filter_select_goods_all_option') }}
                                                                    </option>
                                                                    <option value="2">
                                                                        {{ __('localization.residue_control_create_all_rule_filter_select_goods_option_2') }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="d-flex flex-column col-12 mb-1 p-0"
                                                        id="filter-rule-status-block"
                                                    >
                                                        <label
                                                            class="form-label mb-0 col-5"
                                                            for="select2-hide-search"
                                                        >
                                                            {{ __('localization.residue_control_create_all_rule_filter_status') }}
                                                        </label>
                                                        <div
                                                            class="row mx-0 flex-grow-1 justify-content-end mt-50"
                                                        >
                                                            <div class="col-12 px-0">
                                                                <select
                                                                    class="select2 hide-search form-select"
                                                                    id="filter-rule-status"
                                                                    data-placeholder="{{ __('localization.residue_control_create_all_rule_filter_select_status') }}"
                                                                >
                                                                    <option value=""></option>
                                                                    <option value="1">
                                                                        {{ __('localization.residue_control_create_all_rule_filter_active_all') }}
                                                                    </option>
                                                                    <option value="2">
                                                                        {{ __('localization.residue_control_create_all_rule_filter_active_only') }}
                                                                    </option>
                                                                    <option value="3">
                                                                        {{ __('localization.residue_control_create_all_rule_filter_inactive_only') }}
                                                                    </option>
                                                                    <option value="4">
                                                                        {{ __('localization.residue_control_create_all_rule_filter_active_inactive') }}
                                                                    </option>
                                                                    <option value="5">
                                                                        {{ __('localization.residue_control_create_all_rule_filter_archived_only') }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 p-0 mt-1">
                                                        <div class="d-flex float-end gap-1">
                                                            <button
                                                                id="cancelFilterRules"
                                                                type="button"
                                                                class="btn btn-flat-primary"
                                                            >
                                                                {{ __('localization.residue_control_create_all_rule_filter_reset') }}
                                                            </button>
                                                            <button
                                                                id="submitFilterRules"
                                                                class="btn btn-primary"
                                                            >
                                                                {{ __('localization.residue_control_create_all_rule_filter_apply') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="selected-type">
                                <div class="pt-0">
                                    <div class="card-body px-0 py-0">
                                        <div style="max-height: 445px; overflow-y: auto">
                                            <div>
                                                <ul id="list-rule" class="list-group"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Create Rule --}}
                        <div class="d-none" id="create-rule">
                            <div
                                class="tab-title d-flex justify-content-between type-card-margin mt-2 mb-1"
                            >
                                <div class="d-flex align-items-center gap-1">
                                    <button class="btn back-to-all-rule">
                                        <i data-feather="arrow-left"></i>
                                    </button>

                                    <div class="d-flex flex-column">
                                        <h4 class="mb-0 fw-bolder">
                                            {{ __('localization.residue_control_create_create_rule_title') }}
                                        </h4>
                                        <p class="mb-0">
                                            {{ __('localization.residue_control_create_create_rule_description') }}
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <button
                                        type="button"
                                        id="createNewRule"
                                        class="btn btn-primary disabled"
                                    >
                                        {{ __('localization.residue_control_create_create_rule_save_button') }}
                                    </button>
                                </div>
                            </div>

                            <hr class="mb-0" />
                            <div>
                                <div class="row mx-0 px-2">
                                    <div class="p-0 mt-2">
                                        <h5 class="fw-bold p-0">
                                            {{ __('localization.residue_control_create_create_rule_target_title') }}
                                        </h5>
                                        <div class="ps-3 radioTypeRuleBlock">
                                            <div class="form-check form-check-primary mt-1">
                                                <input
                                                    type="radio"
                                                    id="radioTypeClients"
                                                    name="radioTypeClients"
                                                    class="form-check-input"
                                                    checked
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="radioTypeClients"
                                                >
                                                    {{ __('localization.residue_control_create_create_rule_target_client_type') }}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-primary mt-1">
                                                <input
                                                    type="radio"
                                                    id="radioSpecificClients"
                                                    name="radioSpecificClients"
                                                    class="form-check-input"
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="radioSpecificClients"
                                                >
                                                    {{ __('localization.residue_control_create_create_rule_target_specific_client') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-0 mt-2">
                                        <h5 class="fw-bold p-0">
                                            {{ __('localization.residue_control_create_create_rule_conditions_title') }}
                                        </h5>

                                        <div
                                            class="d-flex justify-content-between align-items-center col-12 mb-1 ps-3"
                                        >
                                            <label
                                                class="form-label mb-0 col-5"
                                                for="select2-hide-search"
                                            >
                                                {{ __('localization.residue_control_create_create_rule_condition_client_type') }}
                                            </label>
                                            <div class="row mx-0 flex-grow-1 justify-content-end">
                                                <div class="col-12 px-0">
                                                    <select
                                                        class="select2 hide-search form-select"
                                                        id="rule-type-client"
                                                        data-placeholder="{{ __('localization.residue_control_create_create_rule_condition_select_condition') }}"
                                                    >
                                                        <option value=""></option>
                                                        <option value="1">
                                                            {{ __('localization.residue_control_create_create_rule_condition_if_client_type_matches') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="d-flex justify-content-between align-items-center col-12 mb-1 ps-3"
                                        >
                                            <label
                                                class="form-label mb-0 col-5"
                                                for="select2-hide-search"
                                            >
                                                {{ __('localization.residue_control_create_create_rule_condition_client') }}
                                            </label>
                                            <div class="row mx-0 flex-grow-1 justify-content-end">
                                                <div class="col-12 px-0">
                                                    <select
                                                        class="select2 hide-search form-select"
                                                        id="type-client"
                                                        data-placeholder="{{ __('localization.residue_control_create_create_rule_condition_select_type') }}"
                                                    >
                                                        <option value=""></option>
                                                        <option value="1">
                                                            {{ __('localization.residue_control_create_create_rule_condition_distributor') }}
                                                        </option>
                                                        <option value="2">
                                                            {{ __('localization.residue_control_create_create_rule_condition_shop') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="d-flex justify-content-between align-items-center col-12 mb-1 ps-3"
                                        >
                                            <label
                                                class="form-label mb-0 col-5"
                                                for="select2-hide-search"
                                            >
                                                {{ __('localization.residue_control_create_create_rule_condition_goods') }}
                                            </label>
                                            <div class="row mx-0 flex-grow-1 justify-content-end">
                                                <div class="col-12 px-0">
                                                    <select
                                                        class="select2 hide-search form-select"
                                                        id="goods"
                                                        data-placeholder="{{ __('localization.residue_control_create_create_rule_condition_select_goods') }}"
                                                    >
                                                        <option value=""></option>
                                                        <option value="1">
                                                            {{ __('localization.residue_control_create_create_rule_condition_cookie_maria') }}
                                                        </option>
                                                        <option value="2">
                                                            {{ __('localization.residue_control_create_create_rule_condition_cookie_haha') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="d-flex justify-content-between align-items-center col-12 mb-1 ps-3"
                                        >
                                            <label
                                                class="form-label mb-0 col-5"
                                                for="select2-hide-search"
                                            >
                                                {{ __('localization.residue_control_create_create_rule_condition_series') }}
                                            </label>
                                            <div class="row mx-0 flex-grow-1 justify-content-end">
                                                <div class="col-12 px-0">
                                                    <select
                                                        class="select2 hide-search form-select"
                                                        id="rule-series"
                                                        data-placeholder="{{ __('localization.residue_control_create_create_rule_condition_select_condition_series') }}"
                                                    >
                                                        <option value=""></option>
                                                        <option value="1">
                                                            {{ __('localization.residue_control_create_create_rule_condition_if_series_matches') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="d-flex justify-content-between align-items-center col-12 mb-1 ps-3"
                                        >
                                            <label
                                                class="form-label mb-0 col-5"
                                                for="select2-hide-search"
                                            >
                                                {{ __('localization.residue_control_create_create_rule_condition_batch_sampling_type') }}
                                            </label>
                                            <div class="row mx-0 flex-grow-1 justify-content-end">
                                                <div class="col-12 px-0">
                                                    <select
                                                        class="select2 hide-search form-select"
                                                        id="type-samples-party"
                                                        data-placeholder="{{ __('localization.residue_control_create_create_rule_condition_select_type_batch_sampling_type') }}"
                                                    >
                                                        <option value=""></option>
                                                        <option value="1">
                                                            {{ __('localization.residue_control_create_create_rule_condition_whole_series') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="d-flex justify-content-between align-items-center col-12 mb-1 ps-3"
                                        >
                                            <label class="form-label col-5" for="contentRule">
                                                {{ __('localization.residue_control_create_create_rule_rule_content') }}
                                            </label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="contentRule"
                                                name="contentRule"
                                                placeholder="{{ __('localization.residue_control_create_create_rule_rule_content_placeholder') }}"
                                                required
                                            />
                                        </div>

                                        <div
                                            class="d-flex justify-content-between align-items-center col-12 mb-1 ps-3"
                                        >
                                            <label class="form-label col-5">
                                                {{ __('localization.residue_control_create_create_rule_validity_period') }}
                                            </label>
                                            <input
                                                type="text"
                                                id="periodFrom"
                                                class="form-control flatpickr-basic flatpickr-input"
                                                required
                                                placeholder="{{ __('localization.residue_control_create_create_rule_period_format') }}"
                                                name="periodFrom"
                                                readonly="readonly"
                                            />
                                            <img
                                                class="align-self-center"
                                                style="padding: 0 12px"
                                                src="{{ asset('assets/icons/entity/residue-control/line-schedule-residue-control.svg') }}"
                                                alt="line"
                                            />
                                            <input
                                                type="text"
                                                id="periodTo"
                                                class="form-control flatpickr-basic flatpickr-input"
                                                required
                                                placeholder="{{ __('localization.residue_control_create_create_rule_period_format') }}"
                                                name="periodTo"
                                                readonly="readonly"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Edit View Rule --}}
                        <div class="d-none" id="edit-view-rule">
                            <div
                                class="tab-title d-flex justify-content-between type-card-margin mt-2 mb-1"
                            >
                                <div class="d-flex align-items-center gap-1">
                                    <button class="btn back-to-all-rule-1">
                                        <i data-feather="arrow-left"></i>
                                    </button>

                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center gap-1">
                                            <h4 id="title-rule" class="mb-0 fw-bolder">
                                                {{ __('localization.residue_control_create_edit_rule_title', ['rule_number' => '2393']) }}
                                            </h4>
                                            <span
                                                id="status-rule"
                                                class="px-75 py-50 gap-25 badge d-inline-flex align-items-center"
                                            >
                                                <img
                                                    src="{{ asset('assets/icons/entity/residue-control/notes.svg') }}"
                                                    alt="notes"
                                                />
                                                {{ __('localization.residue_control_create_edit_rule_status_template') }}
                                            </span>
                                        </div>

                                        <p class="mb-0">
                                            {{ __('localization.residue_control_create_edit_rule_description') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <button
                                        type="button"
                                        id="saveEdit"
                                        class="btn btn-primary disabled"
                                    >
                                        {{ __('localization.residue_control_create_edit_rule_save_changes_button') }}
                                    </button>
                                    <div class="nav-item nav-search list-unstyled">
                                        <a class="nav-link nav-link-grid">
                                            <img
                                                class="nav-img"
                                                src="{{ asset('assets/icons/entity/residue-control/dots-icon.svg') }}"
                                                alt="dots-icon"
                                            />
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <hr class="mb-0" />
                            <div>
                                <div class="row mx-0 px-2">
                                    <div class="p-0 mt-2">
                                        <h5 class="fw-bold p-0">
                                            {{ __('localization.residue_control_create_edit_rule_target_title') }}
                                        </h5>
                                        <div class="ps-3">
                                            <div class="form-check form-check-primary mt-1">
                                                <input
                                                    type="radio"
                                                    id="radioTypeClientsView"
                                                    name="radioTypeClientsView"
                                                    class="form-check-input"
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="radioTypeClientsView"
                                                >
                                                    {{ __('localization.residue_control_create_edit_rule_target_client_type') }}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-primary mt-1">
                                                <input
                                                    type="radio"
                                                    id="radioSpecificClientsView"
                                                    name="radioSpecificClientsView"
                                                    class="form-check-input"
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="radioSpecificClientsView"
                                                >
                                                    {{ __('localization.residue_control_create_edit_rule_target_specific_client') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-0 mt-2">
                                        <h5 class="fw-bold p-0">
                                            {{ __('localization.residue_control_create_edit_rule_conditions_title') }}
                                        </h5>

                                        <div
                                            class="d-flex justify-content-between align-items-center col-12 mb-1 ps-3"
                                        >
                                            <label
                                                class="form-label mb-0 col-5"
                                                for="select2-hide-search"
                                            >
                                                {{ __('localization.residue_control_create_edit_rule_condition_client_type') }}
                                            </label>
                                            <div class="row mx-0 flex-grow-1 justify-content-end">
                                                <div class="col-12 px-0">
                                                    <select
                                                        class="select2 hide-search form-select"
                                                        id="rule-type-client-view"
                                                        data-placeholder="{{ __('localization.residue_control_create_edit_rule_condition_select_condition') }}"
                                                    >
                                                        <option value=""></option>
                                                        <option value="1">
                                                            {{ __('localization.residue_control_create_edit_rule_condition_if_client_type_matches') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="d-flex justify-content-between align-items-center col-12 mb-1 ps-3"
                                        >
                                            <label
                                                class="form-label mb-0 col-5"
                                                for="select2-hide-search"
                                            >
                                                {{ __('localization.residue_control_create_edit_rule_condition_client') }}
                                            </label>
                                            <div class="row mx-0 flex-grow-1 justify-content-end">
                                                <div class="col-12 px-0">
                                                    <select
                                                        class="select2 hide-search form-select"
                                                        id="type-client-view"
                                                        data-placeholder="{{ __('localization.residue_control_create_edit_rule_condition_select_type') }}"
                                                    >
                                                        <option value=""></option>
                                                        <option value="1">
                                                            {{ __('localization.residue_control_create_edit_rule_condition_distributor') }}
                                                        </option>
                                                        <option value="2">
                                                            {{ __('localization.residue_control_create_edit_rule_condition_shop') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="d-flex justify-content-between align-items-center col-12 mb-1 ps-3"
                                        >
                                            <label
                                                class="form-label mb-0 col-5"
                                                for="select2-hide-search"
                                            >
                                                {{ __('localization.residue_control_create_edit_rule_condition_goods') }}
                                            </label>
                                            <div class="row mx-0 flex-grow-1 justify-content-end">
                                                <div class="col-12 px-0">
                                                    <select
                                                        class="select2 hide-search form-select"
                                                        id="goods-view"
                                                        data-placeholder="{{ __('localization.residue_control_create_edit_rule_condition_select_goods') }}"
                                                    >
                                                        <option value=""></option>
                                                        <option value="1">
                                                            {{ __('localization.residue_control_create_edit_rule_condition_cookie_maria') }}
                                                        </option>
                                                        <option value="2">
                                                            {{ __('localization.residue_control_create_edit_rule_condition_cookie_haha') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="d-flex justify-content-between align-items-center col-12 mb-1 ps-3"
                                        >
                                            <label
                                                class="form-label mb-0 col-5"
                                                for="select2-hide-search"
                                            >
                                                {{ __('localization.residue_control_create_edit_rule_condition_series') }}
                                            </label>
                                            <div class="row mx-0 flex-grow-1 justify-content-end">
                                                <div class="col-12 px-0">
                                                    <select
                                                        class="select2 hide-search form-select"
                                                        id="rule-series-view"
                                                        data-placeholder="{{ __('localization.residue_control_create_edit_rule_condition_select_condition_series') }}"
                                                    >
                                                        <option value=""></option>
                                                        <option value="1">
                                                            {{ __('localization.residue_control_create_edit_rule_condition_if_series_matches') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="d-flex justify-content-between align-items-center col-12 mb-1 ps-3"
                                        >
                                            <label
                                                class="form-label mb-0 col-5"
                                                for="select2-hide-search"
                                            >
                                                {{ __('localization.residue_control_create_edit_rule_condition_batch_sampling_type') }}
                                            </label>
                                            <div class="row mx-0 flex-grow-1 justify-content-end">
                                                <div class="col-12 px-0">
                                                    <select
                                                        class="select2 hide-search form-select"
                                                        id="type-samples-party-view"
                                                        data-placeholder="{{ __('localization.residue_control_create_edit_rule_condition_select_type') }}"
                                                    >
                                                        <option value=""></option>
                                                        <option value="1">
                                                            {{ __('localization.residue_control_create_edit_rule_condition_whole_series') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="d-flex justify-content-between align-items-center col-12 mb-1 ps-3"
                                        >
                                            <label class="form-label col-5" for="contentRuleView">
                                                {{ __('localization.residue_control_create_edit_rule_rule_content') }}
                                            </label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="contentRuleView"
                                                name="contentRuleView"
                                                placeholder="{{ __('localization.residue_control_create_edit_rule_rule_content_placeholder') }}"
                                                required
                                                data-msg="Please enter last name"
                                            />
                                        </div>

                                        <div
                                            class="d-flex justify-content-between align-items-center col-12 mb-1 ps-3"
                                        >
                                            <label class="form-label col-5">
                                                {{ __('localization.residue_control_create_edit_rule_validity_period') }}
                                            </label>
                                            <input
                                                type="text"
                                                id="periodFromView"
                                                class="form-control flatpickr-basic flatpickr-input"
                                                required
                                                placeholder="{{ __('localization.residue_control_create_edit_rule_period_format') }}"
                                                name="periodFrom"
                                                readonly="readonly"
                                            />
                                            <img
                                                class="align-self-center"
                                                style="padding: 0 12px"
                                                src="{{ asset('assets/icons/entity/residue-control/line-schedule-residue-control.svg') }}"
                                                alt="line"
                                            />
                                            <input
                                                type="text"
                                                id="periodToView"
                                                class="form-control flatpickr-basic flatpickr-input"
                                                required
                                                placeholder="{{ __('localization.residue_control_create_edit_rule_period_format') }}"
                                                name="periodTo"
                                                readonly="readonly"
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

        <div
            class="modal fade text-start"
            id="archive_regulation"
            tabindex="-1"
            aria-labelledby="myModalLabel6"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" style="max-width: 555px !important">
                <div class="modal-content">
                    <div class="card popup-card p-2">
                        <h4 class="fw-bolder">
                            {{ __('localization.residue_control_create_modal_archive_title') }}
                            <span class="titleRuleModal"></span>
                        </h4>
                        <div class="card-body row mx-0 p-0">
                            <p class="my-2 p-0">
                                {{ __('localization.residue_control_create_modal_archive_confirm') }}
                            </p>

                            <div class="col-12">
                                <div class="d-flex float-end">
                                    <button
                                        type="button"
                                        class="btn btn-link"
                                        data-bs-dismiss="modal"
                                    >
                                        {{ __('localization.residue_control_create_modal_archive_cancel') }}
                                    </button>
                                    <button type="submit" id="archiveRule" class="btn btn-primary">
                                        {{ __('localization.residue_control_create_modal_archive_confirm_btn') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="modal fade text-start"
            id="no_archive_regulation"
            tabindex="-1"
            aria-labelledby="myModalLabel6"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" style="max-width: 555px !important">
                <div class="modal-content">
                    <div class="card popup-card p-2">
                        <h4 class="fw-bolder">
                            {{ __('localization.residue_control_create_modal_no_archive_title') }}
                            <span class="titleRuleModal"></span>
                        </h4>
                        <div class="card-body row mx-0 p-0">
                            <p class="my-2 p-0">
                                {{ __('localization.residue_control_create_modal_no_archive_confirm') }}
                            </p>

                            <div class="col-12">
                                <div class="d-flex float-end">
                                    <button
                                        type="button"
                                        class="btn btn-link"
                                        data-bs-dismiss="modal"
                                    >
                                        {{ __('localization.residue_control_create_modal_no_archive_cancel') }}
                                    </button>
                                    <button
                                        id="unzippingRule"
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        {{ __('localization.residue_control_create_modal_no_archive_confirm_btn') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="modal fade text-start"
            id="delete_regulation"
            tabindex="-1"
            aria-labelledby="myModalLabel6"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" style="max-width: 555px !important">
                <div class="modal-content">
                    <div class="card popup-card p-2">
                        <h4 class="fw-bolder">
                            {{ __('localization.residue_control_create_modal_delete_title') }}
                            <span class="titleRuleModal"></span>
                        </h4>
                        <div class="card-body row mx-0 p-0">
                            <p class="my-2 p-0">
                                {{ __('localization.residue_control_create_modal_delete_confirm') }}
                            </p>

                            <div class="col-12">
                                <div class="d-flex float-end">
                                    <button
                                        type="button"
                                        class="btn btn-link"
                                        data-bs-dismiss="modal"
                                    >
                                        {{ __('localization.residue_control_create_modal_delete_cancel') }}
                                    </button>
                                    <button type="button" class="btn btn-primary" id="deleteRule">
                                        {{ __('localization.residue_control_create_modal_delete_confirm_btn') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        // Отримуємо дропдаун-меню за його ідентифікатор
        const dropdownMenu = document.getElementById('filterDropdown');

        // Додаємо обробник події для дропдаун-меню
        dropdownMenu.addEventListener('click', function (event) {
            // Зупиняємо подальше поширення події "click" всередині дропдаун-меню
            event.stopPropagation();
        });
    </script>

    <script
        type="module"
        src="{{ asset('assets/js/entity/residue-control/residue-control.js') }}"
    ></script>
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>

    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/pickers/form-pickers.js') }}"></script>
@endsection
