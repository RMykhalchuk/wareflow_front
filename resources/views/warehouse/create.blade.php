@extends('layouts.admin')
@section('title', __('localization.warehouse.create.title'))

@section('page-style')
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}"
    />

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
    <x-layout.container>
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/warehouses',
                            'name' => __('localization.warehouse.create.breadcrumb.warehouses'),
                        ],
                        [
                            'name' => __('localization.warehouse.create.breadcrumb.add'),
                        ],
                    ],
                ]
            )

            <x-ui.header-actions>
                <x-modal.modal-trigger-button
                    id="cancel_button"
                    target="cancel_edit_user"
                    class="btn btn-flat-secondary"
                    icon="x"
                    iconStyle="mr-0"
                />
            </x-ui.header-actions>
        </x-slot>

        <x-slot:slot>
            <x-page-title :title="__('localization.warehouse.create.breadcrumb.add')" />

            <x-card.nested id="block_1">
                <x-slot:header>
                    <x-section-title>
                        {{ __('localization.warehouse.create.block_1.main_data_title') }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    <x-form.input-text
                        id="name"
                        name="name"
                        label="localization.warehouse.create.block_1.name_label"
                        placeholder="localization.warehouse.create.block_1.name_placeholder"
                    />

                    <x-form.select
                        id="location"
                        name="location"
                        label="localization.warehouse.create.block_1.location_label"
                        placeholder="localization.warehouse.create.block_1.location_placeholder"
                        data-dictionary="location"
                    ></x-form.select>

                    <x-form.select
                        id="type"
                        name="type"
                        label="localization.warehouse.create.block_1.type_label"
                        placeholder="localization.warehouse.create.block_1.type_placeholder"
                        data-dictionary="warehouse_type"
                    ></x-form.select>

                    <x-form.select
                        id="erp-warehouse"
                        name="erp-warehouse"
                        label="localization.warehouse.create.block_1.erp_warehouse_label"
                        placeholder="localization.warehouse.create.block_1.erp_warehouse_placeholder"
                        data-dictionary="warehouses_erp"
                        multiple
                    ></x-form.select>

                    <div class="mt-1" id="main-data-message"></div>
                </x-slot>
            </x-card.nested>

            <x-card.nested
                :bodyAttributes="['id' => 'working-data-body', 'style' => 'display: none;']"
            >
                <x-slot:header>
                    <div class="d-flex align-items-center">
                        <x-section-title>
                            {{ __('localization.warehouse.create.block_3.work_schedule_title') }}
                        </x-section-title>

                        <div class="form-check form-switch mt-0">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="working-data"
                                name="working-data"
                            />
                        </div>
                    </div>
                </x-slot>

                <x-slot:body>
                    <div class="ps-0 col-12 col-lg-8">
                        <div class="card">
                            <div class="card-body" style="padding-top: 2.2rem">
                                <div class="row mx-0 gap-1 justify-content-between">
                                    <div class="col-auto" style="margin-top: 3.5rem">
                                        <div class="d-flex flex-column" style="gap: 2.3rem">
                                            <span>
                                                {{ __('localization.warehouse.create.block_3.monday_label') }}
                                            </span>
                                            <span>
                                                {{ __('localization.warehouse.create.block_3.tuesday_label') }}
                                            </span>
                                            <span>
                                                {{ __('localization.warehouse.create.block_3.wednesday_label') }}
                                            </span>
                                            <span>
                                                {{ __('localization.warehouse.create.block_3.thursday_label') }}
                                            </span>
                                            <span>
                                                {{ __('localization.warehouse.create.block_3.friday_label') }}
                                            </span>
                                            <span>
                                                {{ __('localization.warehouse.create.block_3.saturday_label') }}
                                            </span>
                                            <span>
                                                {{ __('localization.warehouse.create.block_3.sunday_label') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-9 col-sm-10 col-md-10 col-lg-4 flex-grow-1">
                                        <h5 class="mb-2">
                                            {{ __('localization.warehouse.create.block_3.work_hours_title') }}
                                        </h5>
                                        <div class="d-flex flex-column gap-1">
                                            <div class="d-flex two-input-for-schedule">
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Monday-1"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                        value="09:00"
                                                    />
                                                </div>
                                                <img
                                                    class="align-self-center"
                                                    style="padding: 0 12px"
                                                    src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                                    alt="line-schedule"
                                                />
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Monday-2"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                        value="18:00"
                                                    />
                                                </div>
                                            </div>

                                            <div class="d-flex two-input-for-schedule">
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Tuesday-1"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                        value="09:00"
                                                    />
                                                </div>
                                                <img
                                                    class="align-self-center"
                                                    style="padding: 0 12px"
                                                    src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                                    alt="line-schedule"
                                                />
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Tuesday-2"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                        value="18:00"
                                                    />
                                                </div>
                                            </div>

                                            <div class="d-flex two-input-for-schedule">
                                                <div>
                                                    <input
                                                        type="text"
                                                        class="form-control flatpickr-time text-start"
                                                        id="Wednesday-1"
                                                        placeholder="00:00"
                                                        value="09:00"
                                                    />
                                                </div>
                                                <img
                                                    class="align-self-center"
                                                    style="padding: 0 12px"
                                                    src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                                    alt="line-schedule"
                                                />
                                                <div>
                                                    <input
                                                        type="text"
                                                        class="form-control flatpickr-time text-start"
                                                        id="Wednesday-2"
                                                        placeholder="00:00"
                                                        value="18:00"
                                                    />
                                                </div>
                                            </div>

                                            <div class="d-flex two-input-for-schedule">
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Thursday-1"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                        value="09:00"
                                                    />
                                                </div>
                                                <img
                                                    class="align-self-center"
                                                    style="padding: 0 12px"
                                                    src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                                    alt="line-schedule"
                                                />
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Thursday-2"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                        value="18:00"
                                                    />
                                                </div>
                                            </div>

                                            <div class="d-flex two-input-for-schedule">
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Friday-1"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                        value="09:00"
                                                    />
                                                </div>
                                                <img
                                                    class="align-self-center"
                                                    style="padding: 0 12px"
                                                    src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                                    alt="line-schedule"
                                                />
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Friday-2"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                        value="18:00"
                                                    />
                                                </div>
                                            </div>

                                            <div class="d-flex two-input-for-schedule">
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Saturday-1"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                    />
                                                </div>
                                                <img
                                                    class="align-self-center"
                                                    style="padding: 0 12px"
                                                    src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                                    alt="line-schedule"
                                                />
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Saturday-2"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                    />
                                                </div>
                                            </div>

                                            <div class="d-flex two-input-for-schedule">
                                                <div class="d-flex">
                                                    <input
                                                        type="text"
                                                        id="Sunday-1"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                    />
                                                </div>
                                                <img
                                                    class="align-self-center"
                                                    style="padding: 0 12px"
                                                    src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                                    alt="line-schedule"
                                                />
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Sunday-2"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="col-auto col-md-1 wrapper d-block d-lg-none"
                                        style="margin-top: 2.4rem"
                                    >
                                        <div class="d-flex flex-column" style="gap: 2.3rem">
                                            <span>
                                                {{ __('localization.warehouse.create.block_3.monday_label') }}
                                            </span>
                                            <span>
                                                {{ __('localization.warehouse.create.block_3.tuesday_label') }}
                                            </span>
                                            <span>
                                                {{ __('localization.warehouse.create.block_3.wednesday_label') }}
                                            </span>
                                            <span>
                                                {{ __('localization.warehouse.create.block_3.thursday_label') }}
                                            </span>
                                            <span>
                                                {{ __('localization.warehouse.create.block_3.friday_label') }}
                                            </span>
                                            <span>
                                                {{ __('localization.warehouse.create.block_3.saturday_label') }}
                                            </span>
                                            <span>
                                                {{ __('localization.warehouse.create.block_3.sunday_label') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-7 col-md-8 col-lg-4 flex-grow-1">
                                        <h5 class="mb-2">
                                            {{ __('localization.warehouse.create.block_3.lunch_title') }}
                                        </h5>
                                        <div class="d-flex flex-column gap-1">
                                            <div class="d-flex two-input-for-schedule">
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Monday-3"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                        value="13:00"
                                                    />
                                                </div>
                                                <img
                                                    class="align-self-center"
                                                    style="padding: 0 12px"
                                                    src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                                    alt="line-schedule"
                                                />
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Monday-4"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                        value="14:00"
                                                    />
                                                </div>
                                            </div>

                                            <div class="d-flex two-input-for-schedule">
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Tuesday-3"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                        value="13:00"
                                                    />
                                                </div>
                                                <img
                                                    class="align-self-center"
                                                    style="padding: 0 12px"
                                                    src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                                    alt="line-schedule"
                                                />
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Tuesday-4"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                        value="14:00"
                                                    />
                                                </div>
                                            </div>

                                            <div class="d-flex two-input-for-schedule">
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Wednesday-3"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                        value="13:00"
                                                    />
                                                </div>
                                                <img
                                                    class="align-self-center"
                                                    style="padding: 0 12px"
                                                    src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                                    alt="line-schedule"
                                                />
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Wednesday-4"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                        value="14:00"
                                                    />
                                                </div>
                                            </div>

                                            <div class="d-flex two-input-for-schedule">
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Thursday-3"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                        value="13:00"
                                                    />
                                                </div>
                                                <img
                                                    class="align-self-center"
                                                    style="padding: 0 12px"
                                                    src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                                    alt="line-schedule"
                                                />
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Thursday-4"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                        value="14:00"
                                                    />
                                                </div>
                                            </div>

                                            <div class="d-flex two-input-for-schedule">
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Friday-3"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                        value="13:00"
                                                    />
                                                </div>
                                                <img
                                                    class="align-self-center"
                                                    style="padding: 0 12px"
                                                    src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                                    alt="line-schedule"
                                                />
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Friday-4"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                        value="14:00"
                                                    />
                                                </div>
                                            </div>

                                            <div class="d-flex two-input-for-schedule">
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Saturday-3"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                    />
                                                </div>
                                                <img
                                                    class="align-self-center"
                                                    style="padding: 0 12px"
                                                    src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                                    alt="line-schedule"
                                                />
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Saturday-4"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                    />
                                                </div>
                                            </div>

                                            <div class="d-flex two-input-for-schedule">
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Sunday-3"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                    />
                                                </div>
                                                <img
                                                    class="align-self-center"
                                                    style="padding: 0 12px"
                                                    src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                                    alt="line-schedule"
                                                />
                                                <div>
                                                    <input
                                                        type="text"
                                                        id="Sunday-4"
                                                        class="form-control flatpickr-time text-start"
                                                        placeholder="00:00"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-auto order-2">
                                        <h5 class="" style="margin-bottom: 2rem">
                                            {{ __('localization.warehouse.create.block_3.weekend_title') }}
                                        </h5>
                                        <div class="d-flex flex-column mt-1" style="gap: 2.35rem">
                                            <div class="d-flex">
                                                <input
                                                    class="form-check-input mt-0"
                                                    type="checkbox"
                                                    id="Monday-check"
                                                />
                                            </div>

                                            <div class="d-flex">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    id="Tuesday-check"
                                                />
                                            </div>

                                            <div class="d-flex">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    id="Wednesday-check"
                                                />
                                            </div>

                                            <div class="d-flex">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    id="Thursday-check"
                                                />
                                            </div>

                                            <div class="d-flex">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    id="Friday-check"
                                                />
                                            </div>

                                            <div class="d-flex">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    id="Saturday-check"
                                                    checked
                                                />
                                            </div>

                                            <div class="d-flex">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    id="Sunday-check"
                                                    checked
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="p-0 px-1 row mx-0 gap-1">
                                        <hr class="" style="border-top: 2px solid" />
                                        @if (count($patterns))
                                            <div class="mt-1 p-0">
                                                <div class="row mx-0 mb-1">
                                                    <label
                                                        class="d-flex align-items-center ps-0 col-5"
                                                        for="select_pattern"
                                                    >
                                                        {{ __('localization.warehouse.create.block_3.use_patterns_label') }}
                                                    </label>
                                                    <div class="col-7 pe-0">
                                                        <select
                                                            class="select2 hide-search form-select"
                                                            id="select_pattern"
                                                            data-placeholder="{{ __('localization.warehouse.create.block_3.select_pattern_placeholder') }}"
                                                        >
                                                            <option value=""></option>
                                                            @foreach ($patterns as $pattern)
                                                                <option
                                                                    class="graphic-pattern"
                                                                    data-pattern="{{ $pattern->schedule }}"
                                                                    value="{{ $pattern->schedule }}"
                                                                >
                                                                    {{ $pattern->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div style="display: none"></div>
                                        @endif

                                        <div class="col-12 d-flex align-items-center p-0">
                                            <div class="form-check form-check-inline">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    id="schedule_pattern"
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="schedule_pattern"
                                                >
                                                    {{ __('localization.warehouse.create.block_3.save_pattern_checkbox_label') }}
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-12 px-0">
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="pattern"
                                                name="pattern"
                                                placeholder="{{ __('localization.warehouse.create.block_3.pattern_name_placeholder') }}"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-1" id="working-data-message"></div>
                    </div>

                    <div class="col-12 px-0 col-lg-4 bg-light-secondary">
                        <div class="card bg-transparent h-100" id="condition-list">
                            <div class="card-header row mx-0 px-0" id="card-header-conditions">
                                <h4 class="col-auto mb-0 fw-bolder">
                                    {{ __('localization.warehouse.create.block_3.special_conditions_title') }}
                                </h4>
                                <p class="text-center d-none">
                                    {!! __('localization.warehouse.create.block_3.special_conditions_added_text') !!}
                                </p>
                                <div class="col-2">
                                    <button
                                        class="btn btn-outline-secondary float-end"
                                        data-bs-toggle="modal"
                                        data-bs-target="#animation"
                                        id="add-special-condition"
                                    >
                                        {{ __('localization.warehouse.create.block_3.add_special_condition_button') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot>
            </x-card.nested>

            <div class="d-flex justify-content-end">
                <x-ui.action-button
                    id="create"
                    class="btn btn-primary mb-2"
                    :text="__('localization.warehouse.create.save_button')"
                />
            </div>
        </x-slot>
    </x-layout.container>

    <!-- Modal add-->
    <div
        class="modal fade text-start"
        id="animation"
        tabindex="-1"
        aria-labelledby="myModalLabel6"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="card popup-card">
                    <div class="popup-header">
                        {{ __('localization.warehouse.create.add_condition_modal.title') }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="condition_name">
                                    {{ __('localization.warehouse.create.add_condition_modal.condition_name_label') }}
                                </label>
                                <select
                                    class="select2 hide-search form-select"
                                    id="condition_name"
                                    data-placeholder="{{ __('localization.warehouse.create.add_condition_modal.condition_name_placeholder') }}"
                                >
                                    <option id="condition_none" value=""></option>
                                    @php
                                        $exceptionNames = [
                                            'day_off' => 'conditions.day_off',
                                            'hospital' => 'conditions.hospital',
                                            'short_day' => 'conditions.short_day',
                                            'holiday' => 'conditions.holiday',
                                        ];
                                    @endphp

                                    @foreach ($exceptions as $exception)
                                        @if ($exception->key !== 'hospital')
                                            <option
                                                data-id="{{ $exception->id }}"
                                                data-name="{{ isset($exceptionNames[$exception->key]) ? __('localization.warehouse.' . $exceptionNames[$exception->key]) : '-' }}"
                                                value="{{ $exception->key }}"
                                            >
                                                {{ isset($exceptionNames[$exception->key]) ? __('localization.warehouse.' . $exceptionNames[$exception->key]) : '-' }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="select_period"
                                    value="one_day"
                                />
                                <label class="form-check-label" for="one_day">
                                    {{ __('localization.warehouse.create.add_condition_modal.select_period_one_day_label') }}
                                </label>
                            </div>
                            <div class="form-check" style="margin-top: 5px">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="select_period"
                                    value="period"
                                    checked
                                />
                                <label class="form-check-label" for="period">
                                    {{ __('localization.warehouse.create.add_condition_modal.select_period_period_label') }}
                                </label>
                            </div>
                        </div>
                        <div style="display: none" id="one_day" class="col-12 mt-1">
                            <input
                                type="text"
                                class="form-control one_day flatpickr-basic flatpickr-input"
                                name="one_day"
                                required
                                placeholder="{{ __('localization.warehouse.create.add_condition_modal.one_day_placeholder') }}"
                                readonly="readonly"
                            />
                        </div>
                        <div id="period" style="display: flex" class="col-12 mt-1">
                            <div style="width: 45%; padding-right: 0">
                                <input
                                    type="text"
                                    id="date-1"
                                    class="form-control date-1 flatpickr-basic flatpickr-input"
                                    required
                                    placeholder="{{ __('localization.warehouse.create.add_condition_modal.date_1_placeholder') }}"
                                    readonly="readonly"
                                />
                            </div>
                            <img
                                class="align-self-center"
                                style="
                                    width: 45px;
                                    height: 2px;
                                    padding-left: 12px;
                                    padding-right: 12px;
                                "
                                src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                alt="line-schedule"
                            />
                            <div style="width: 45%; padding-left: 0">
                                <input
                                    type="text"
                                    id="date-2"
                                    class="form-control date-2 flatpickr-basic flatpickr-input"
                                    required
                                    placeholder="{{ __('localization.warehouse.create.add_condition_modal.date_2_placeholder') }}"
                                    readonly="readonly"
                                />
                            </div>
                        </div>
                        <div class="d-none" id="work-schedule">
                            <p class="f-15 fw-bold mt-1 mb-1">
                                {{ __('localization.warehouse.create.add_condition_modal.work_schedule_label') }}
                            </p>
                            <div class="col-12 d-flex two-input-for-schedule-inmodal">
                                <div style="width: 45%; padding-right: 0">
                                    <input
                                        type="text"
                                        id="work_from"
                                        class="form-control flatpickr-time text-start"
                                        placeholder="{{ __('localization.warehouse.create.add_condition_modal.time_placeholder') }}"
                                    />
                                </div>
                                <img
                                    class="align-self-center"
                                    style="
                                        width: 45px;
                                        height: 2px;
                                        padding-left: 12px;
                                        padding-right: 12px;
                                    "
                                    src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                    alt="line-schedule"
                                />
                                <div style="width: 45%; padding-left: 0">
                                    <input
                                        type="text"
                                        id="work_to"
                                        class="form-control flatpickr-time text-start"
                                        placeholder="{{ __('localization.warehouse.create.add_condition_modal.time_placeholder') }}"
                                    />
                                </div>
                            </div>
                            <p class="f-15 fw-bold mt-1 mb-1">
                                {{ __('localization.warehouse.create.add_condition_modal.break_label') }}
                            </p>
                            <div class="col-12 d-flex two-input-for-schedule-inmodal">
                                <div style="width: 45%; padding-right: 0">
                                    <input
                                        type="text"
                                        id="break_from"
                                        class="form-control flatpickr-time text-start"
                                        placeholder="{{ __('localization.warehouse.create.add_condition_modal.time_placeholder') }}"
                                    />
                                </div>
                                <img
                                    class="align-self-center"
                                    style="
                                        width: 45px;
                                        height: 2px;
                                        padding-left: 12px;
                                        padding-right: 12px;
                                    "
                                    src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                    alt="line-schedule"
                                />
                                <div style="width: 45%; padding-left: 0">
                                    <input
                                        type="text"
                                        id="break_to"
                                        class="form-control flatpickr-time text-start"
                                        placeholder="{{ __('localization.warehouse.create.add_condition_modal.time_placeholder') }}"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-1">
                            <div class="d-flex float-end">
                                <button class="btn btn-link cancel-btn" data-dismiss="modal">
                                    {{ __('localization.warehouse.create.add_condition_modal.cancel_button') }}
                                </button>
                                <button
                                    class="btn btn-primary"
                                    disabled="disabled"
                                    id="condition_submit"
                                >
                                    {{ __('localization.warehouse.create.add_condition_modal.save_button') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal edit-->
    <div
        class="modal fade text-start"
        id="edit-modal"
        tabindex="-1"
        aria-labelledby="myModalLabel6"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="card popup-card">
                    <div class="popup-header">
                        {{ __('localization.warehouse.create.edit_condition_modal.title') }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label class="form-label" for="edit_condition_name">
                                    {{ __('localization.warehouse.create.edit_condition_modal.condition_name_label') }}
                                </label>
                                <select
                                    class="select2 hide-search form-select"
                                    id="edit_condition_name"
                                    data-placeholder="{{ __('localization.warehouse.create.edit_condition_modal.condition_name_placeholder') }}"
                                >
                                    @php
                                        $exceptionNames = [
                                            'day_off' => 'conditions.day_off',
                                            'hospital' => 'conditions.hospital',
                                            'short_day' => 'conditions.short_day',
                                            'holiday' => 'conditions.holiday',
                                        ];
                                    @endphp

                                    @foreach ($exceptions as $exception)
                                        @if ($exception->key !== 'hospital')
                                            <option
                                                data-id="{{ $exception->id }}"
                                                data-name="{{ isset($exceptionNames[$exception->key]) ? __('localization.warehouse.' . $exceptionNames[$exception->key]) : '-' }}"
                                                value="{{ $exception->key }}"
                                            >
                                                {{ isset($exceptionNames[$exception->key]) ? __('localization.warehouse.' . $exceptionNames[$exception->key]) : '-' }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="edit_select_period"
                                    value="one_day"
                                />
                                <label class="form-check-label" for="edit_one_day">
                                    {{ __('localization.warehouse.create.edit_condition_modal.select_period_one_day_label') }}
                                </label>
                            </div>
                            <div class="form-check" style="margin-top: 5px">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="edit_select_period"
                                    value="period"
                                    checked
                                />
                                <label class="form-check-label" for="period">
                                    {{ __('localization.warehouse.create.edit_condition_modal.select_period_period_label') }}
                                </label>
                            </div>
                        </div>
                        <div style="display: none" id="edit_one_day" class="col-12 mt-1">
                            <input
                                type="text"
                                id="edit_one_day"
                                class="form-control one-day edit_one_day flatpickr-basic flatpickr-input"
                                name="edit_one_day"
                                required
                                placeholder="{{ __('localization.warehouse.create.edit_condition_modal.one_day_placeholder') }}"
                                readonly="readonly"
                            />
                        </div>
                        <div id="edit_period" style="display: flex" class="col-12 mt-1">
                            <div style="width: 45%; padding-right: 0">
                                <input
                                    type="text"
                                    id="edit_date-1"
                                    class="form-control date-1 flatpickr-basic flatpickr-input"
                                    required
                                    placeholder="{{ __('localization.warehouse.create.edit_condition_modal.date_1_placeholder') }}"
                                    readonly="readonly"
                                />
                            </div>
                            <img
                                class="align-self-center"
                                style="
                                    width: 45px;
                                    height: 2px;
                                    padding-left: 12px;
                                    padding-right: 12px;
                                "
                                src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                alt="line-schedule"
                            />
                            <div style="width: 45%; padding-left: 0">
                                <input
                                    type="text"
                                    id="edit_date-2"
                                    class="form-control date-2 flatpickr-basic flatpickr-input"
                                    required
                                    placeholder="{{ __('localization.warehouse.create.edit_condition_modal.date_2_placeholder') }}"
                                    readonly="readonly"
                                />
                            </div>
                        </div>
                        <div id="work-schedule-edit">
                            <p class="f-15 fw-bold mt-1 mb-1">
                                {{ __('localization.warehouse.create.edit_condition_modal.work_schedule_label') }}
                            </p>
                            <div class="col-12 d-flex two-input-for-schedule-inmodal">
                                <div style="width: 45%; padding-right: 0">
                                    <input
                                        type="text"
                                        id="edit_work_from"
                                        class="form-control flatpickr-time text-start"
                                        placeholder="{{ __('localization.warehouse.create.edit_condition_modal.time_placeholder') }}"
                                    />
                                </div>
                                <img
                                    class="align-self-center"
                                    style="
                                        width: 45px;
                                        height: 2px;
                                        padding-left: 12px;
                                        padding-right: 12px;
                                    "
                                    src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                    alt="line-schedule"
                                />
                                <div style="width: 45%; padding-left: 0">
                                    <input
                                        type="text"
                                        id="edit_work_to"
                                        class="form-control flatpickr-time text-start"
                                        placeholder="{{ __('localization.warehouse.create.edit_condition_modal.time_placeholder') }}"
                                    />
                                </div>
                            </div>
                            <p class="f-15 fw-bold mt-1 mb-1">
                                {{ __('localization.warehouse.create.edit_condition_modal.break_label') }}
                            </p>
                            <div class="col-12 d-flex two-input-for-schedule-inmodal">
                                <div style="width: 45%; padding-right: 0">
                                    <input
                                        type="text"
                                        id="edit_break_from"
                                        class="form-control flatpickr-time text-start"
                                        placeholder="{{ __('localization.warehouse.create.edit_condition_modal.time_placeholder') }}"
                                    />
                                </div>
                                <img
                                    class="align-self-center"
                                    style="
                                        width: 45px;
                                        height: 2px;
                                        padding-left: 12px;
                                        padding-right: 12px;
                                    "
                                    src="{{ asset('assets/icons/entity/warehouse/line-schedule-warehouse.svg') }}"
                                    alt="line-schedule"
                                />
                                <div style="width: 45%; padding-left: 0">
                                    <input
                                        type="text"
                                        id="edit_break_to"
                                        class="form-control flatpickr-time text-start"
                                        placeholder="{{ __('localization.warehouse.create.edit_condition_modal.time_placeholder') }}"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-1">
                            <div class="d-flex float-end">
                                <button class="btn btn-link cancel-btn" data-dismiss="modal">
                                    {{ __('localization.warehouse.create.edit_condition_modal.cancel_button') }}
                                </button>
                                <button class="btn btn-primary" id="edit_condition_submit">
                                    {{ __('localization.warehouse.create.edit_condition_modal.save_button') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-cancel-modal
        id="cancel_edit_user"
        route="/warehouses"
        title="{{ __('localization.warehouse.create.cancel_modal.title') }}"
        content="{!! __('localization.warehouse.create.cancel_modal.message') !!}"
        cancel-text="{{ __('localization.user_cancel_edit_modal_cancel_button') }}"
        confirm-text="{{ __('localization.user_cancel_edit_modal_confirm_button') }}"
    />
@endsection

@section('page-script')
    <script>
        const conditions = [];
    </script>
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>

    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/l10n/uk.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('#country, #settlement, #street, #building_number').change(function () {
                var country = $('#country option:selected').text();
                var settlement = $('#settlement option:selected').text();
                var street = $('#street option:selected').text();
                var buildingNumber = $('#building_number').val();

                if (country && settlement && street && buildingNumber) {
                    // Об'єднуємо значення через пробіл і записуємо в #map-input
                    var combinedValue =
                        country + ' ' + settlement + ' ' + street + ' ' + buildingNumber;
                    $('#map-input').val(combinedValue);
                    // setTimeout(function () {
                    //     $("#map-input").blur();
                    // }, 3000);
                }
            });
        });
    </script>

    <script
        type="module"
        src="{{ asset('assets/js/entity/warehouse/warehouse-condition.js') }}"
    ></script>
    <script
        type="module"
        src="{{ asset('assets/js/entity/warehouse/warehouse-create.js') }}"
    ></script>

    <script>
        $(document).ready(function () {
            $('#add-special-condition').on('click', function () {
                $('#work-schedule').addClass('d-none');
            });
        });

        $(document).ready(function () {
            $('#condition_name').on('change', function () {
                var selectedOption = $(this).find('option:selected').val();
                if (['day_off', 'hospital', 'holiday'].includes(selectedOption)) {
                    $('#work-schedule').addClass('d-none');
                } else {
                    $('#work-schedule').removeClass('d-none');
                }
            });
        });

        $(document).ready(function () {
            $('#edit_condition_name').on('change', function () {
                var selectedOption = $(this).find('option:selected').val();
                if (['day_off', 'hospital', 'holiday'].includes(selectedOption)) {
                    $('#work-schedule-edit').addClass('d-none');
                } else {
                    $('#work-schedule-edit').removeClass('d-none');
                }
            });
        });
    </script>

    <script type="module">
        import { toggleBlock } from '{{ asset('assets/js/utils/toggleBlock.js') }}';

        toggleBlock({
            checkboxId: 'working-data',
            targetId: 'working-data-body',
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lang = '{{ app()->getLocale() }}'; // Laravel локаль

            flatpickr('.flatpickr-basic', {
                dateFormat: 'Y-m-d',
                locale: lang,
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lang = '{{ app()->getLocale() }}'; // Laravel локаль

            flatpickr('.flatpickr-time', {
                enableTime: true, // Увімкнути вибір часу
                noCalendar: true, // Приховати календар (тільки час)
                dateFormat: 'H:i', // Формат значення у value
                locale: lang, // Локаль з Laravel
                // altInput: true, // Альтернативний інпут для красивого вигляду
                altFormat: 'H:i', // Формат відображення
            });
        });
    </script>
@endsection
