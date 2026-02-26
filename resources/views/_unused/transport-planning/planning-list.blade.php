@extends('layouts.admin')
@section('title', __('localization.transport_planning_view_planning_list_title'))
@section('page-style')
    
@endsection

@section('before-style')
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
    <div class="container-fluid px-2">
        @include(
            'panels.breadcrumb',
            [
                'options' => [
                    [
                        'url' => '/transport-planning',
                        'name' => __(
                            'localization.transport_planning_view_planning_list_breadcrumbs_transport_planning',
                        ),
                    ],
                    [
                        'name' => __(
                            'localization.transport_planning_view_planning_list_breadcrumbs_shipment',
                            ['date' => $date],
                        ),
                    ],
                ],
            ]
        )

        @foreach ($transportPlannings as $planningIndex => $planning)
            <div class="card p-2 px-50" id="jsCopyEventScope">
                <div
                    class="transport-planning-item-header d-flex flex-wrap gap-1 justify-content-center justify-content-sm-between px-1 pb-2"
                >
                    <div
                        class="transport-planning-item-number-actions-wrapper d-flex align-items-center"
                    >
                        <div class="transport-planning-item-number d-flex align-items-center pe-2">
                            <h4 class="item-number mb-0 fw-bolder">
                                {{ __('localization.transport_planning_view_planning_list_item_number', ['id' => $planning->id]) }}
                            </h4>
                        </div>
                        <div
                            class="transport-planning-item-actions d-flex align-items-center gap-50"
                        >
                            <button
                                class="btn p-25 h-50"
                                id="jsPrintBtn-{{ $planning->id }}-planning-item"
                                title="{{ __('localization.transport_planning_view_planning_list_item_print_tooltip') }}"
                            >
                                <i
                                    data-feather="printer"
                                    style="cursor: pointer; transform: scale(1.2)"
                                ></i>
                            </button>

                            {{-- <div class="py-25 px-50"> --}}
                            {{-- <i data-feather='edit' style="cursor: pointer; transform: scale(1.2);"></i> --}}
                            {{-- </div> --}}

                            <div
                                class="btn-group js-button-dropdown-menu"
                                id="delete-transport-planning-{{ $planning->id }}"
                                data-transport-id="{{ $planning->id }}"
                            >
                                <div
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    class="py-25 px-50"
                                >
                                    <i data-feather="more-vertical" style="cursor: pointer"></i>
                                </div>
                                <div
                                    class="dropdown-menu"
                                    aria-labelledby="js-button-dropdown-menu"
                                >
                                    <a
                                        data-bs-toggle="modal"
                                        id="change-status-modal-finish-{{ $planning->id }}"
                                        data-bs-target="#change-status-modal"
                                        class="dropdown-item"
                                        href="#"
                                    >
                                        {{ __('localization.transport_planning_view_planning_list_item_complete_trip') }}
                                    </a>
                                    <a
                                        data-bs-toggle="modal"
                                        id="change-status-modal-cancel-{{ $planning->id }}"
                                        data-bs-target="#change-status-modal"
                                        class="dropdown-item"
                                        href="#"
                                    >
                                        {{ __('localization.transport_planning_view_planning_list_item_cancel_trip') }}
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <p
                                            class="text-danger mb-0"
                                            data-bs-toggle="modal"
                                            data-bs-target="#tp-delete-trip"
                                        >
                                            {{ __('localization.transport_planning_view_planning_list_item_delete_trip') }}
                                        </p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="transport-planning-item-button">
                        <a href="/transport-planning/{{ $planning->id }}">
                            <button type="button" class="btn btn-outline-primary waves-effect">
                                {{ __('localization.transport_planning_view_planning_list_item_view_car') }}
                            </button>
                        </a>
                    </div>

                    <!-- Delete trip modal -->
                    <div
                        class="modal fade"
                        id="tp-delete-trip"
                        tabindex="-1"
                        aria-labelledby="exampleModalCenterTitle"
                        aria-hidden="true"
                    >
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body p-4">
                                    <p
                                        class="text-center fw-semibold"
                                        style="font-size: 26px; line-height: 40px"
                                    >
                                        {{ __('localization.transport_planning_view_planning_list_item_confirm_delete') }}
                                        <br />
                                        <span id="delete-planning-id-block"></span>
                                        ?
                                    </p>
                                    <div
                                        class="d-flex gap-1 w-100 justify-content-center pt-2"
                                        style="border-top: none"
                                    >
                                        <div class="w-100">
                                            <button
                                                type="button"
                                                class="btn btn-flat-secondary w-100"
                                                data-bs-dismiss="modal"
                                            >
                                                {{ __('localization.transport_planning_view_planning_list_item_cancel') }}
                                            </button>
                                        </div>
                                        <div class="w-100">
                                            <button
                                                id="delete-transport-planning"
                                                type="button"
                                                class="btn btn-primary w-100"
                                                data-bs-dismiss="modal"
                                            >
                                                {{ __('localization.transport_planning_view_planning_list_item_confirm') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mx-0" style="row-gap: 1rem">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xxl-3">
                        <section id="transport-planning-status">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="transport-planning-status-wrapper">
                                        <div class="">
                                            <div
                                                class="transport-planning-status-container d-flex flex-column p-1 gap-1"
                                                style="
                                                    background-color: rgba(168, 170, 174, 0.08);
                                                    border-top-left-radius: 6px;
                                                    border-top-right-radius: 6px;
                                                    height: 282px;
                                                    overflow: auto;
                                                    border-bottom: 1px solid #d3d3d3;
                                                "
                                            >
                                                @foreach ($planning->statuses as $index => $status)
                                                    @if ($index !== count($planning->statuses) - 1)
                                                        <div
                                                            id="status-block-{{ $status->pivot->id }}"
                                                            data-status-info="{{ json_encode($status) }}"
                                                            class="d-flex gap-1 transport-planning-status"
                                                        >
                                                            <div class="opacity-50">
                                                                <img
                                                                    src="{{ asset('assets/icons/entity/transport-planning/timeline.svg') }}"
                                                                />
                                                            </div>
                                                            <div class="w-100">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center"
                                                                >
                                                                    <div
                                                                        class="d-flex align-items-center opacity-50"
                                                                    >
                                                                        <span
                                                                            class="fw-semibold"
                                                                            style="
                                                                                padding-right: 10px;
                                                                                font-size: 15px;
                                                                            "
                                                                        >
                                                                            @switch($status->key ?? null)
                                                                                @case('approval_price')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_approval_price') }}

                                                                                    @break
                                                                                @case('approved')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_approved') }}

                                                                                    @break
                                                                                @case('before_downloading')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_before_downloading') }}

                                                                                    @break
                                                                                @case('loaded')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_loaded') }}

                                                                                    @break
                                                                                @case('in_the_way')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_in_the_way') }}

                                                                                    @break
                                                                                @case('in_the_distribution_center')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_in_the_distribution_center') }}

                                                                                    @break
                                                                                @case('delivery_in_progress')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_delivery_in_progress') }}

                                                                                    @break
                                                                                @case('delivered')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_delivered') }}

                                                                                    @break
                                                                                @case('delivered_with_a_delay')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_delivered_with_a_delay') }}

                                                                                    @break
                                                                                @case('delivered_damaged')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_delivered_damaged') }}

                                                                                    @break
                                                                                @case('paid')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_paid') }}

                                                                                    @break
                                                                                @case('end_the_trip')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_end_the_trip') }}

                                                                                    @break
                                                                                @case('cancel_the_trip')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_cancel_the_trip') }}

                                                                                    @break
                                                                                @default
                                                                                    {{ $status->name }}
                                                                            @endswitch
                                                                        </span>
                                                                        @if (! $status->failure_id && $status->pivot->comment)
                                                                            <i
                                                                                data-feather="info"
                                                                                data-bs-toggle="tooltip"
                                                                                title="{{ $status->pivot->comment }}"
                                                                            ></i>
                                                                        @endif

                                                                        @if ($status->failure_id)
                                                                            <img
                                                                                src="{{ asset('assets/icons/entity/transport-planning/problem-icon.svg') }}"
                                                                                data-bs-toggle="tooltip"
                                                                                title="{{ $status->failure_comment }}"
                                                                                style="
                                                                                    padding-left: 5px;
                                                                                    cursor: pointer;
                                                                                "
                                                                            />
                                                                        @endif
                                                                    </div>
                                                                    <div style="height: 25px">
                                                                        <div
                                                                            class="btn-group js-button-dropdown-menu d-none js-button-dropdown-menu-hover align-items-center"
                                                                        >
                                                                            <div
                                                                                data-bs-toggle="dropdown"
                                                                                aria-expanded="false"
                                                                                class="d-flex align-items-center py-50 px-50"
                                                                            >
                                                                                <i
                                                                                    data-feather="more-vertical"
                                                                                    style="
                                                                                        cursor: pointer;
                                                                                    "
                                                                                ></i>
                                                                            </div>

                                                                            <div
                                                                                class="dropdown-menu"
                                                                                aria-labelledby="js-button-dropdown-menu"
                                                                            >
                                                                                <a
                                                                                    id="open-edit-status-modal-button-{{ $status->pivot->id }}"
                                                                                    data-status-id="{{ $status->pivot->id }}"
                                                                                    class="dropdown-item"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#edit-status-modal"
                                                                                    href="#"
                                                                                >
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_edit_status') }}
                                                                                </a>
                                                                                <a
                                                                                    id="open-add-failure-model-button-{{ $status->pivot->id }}"
                                                                                    class="dropdown-item"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#problem-modal"
                                                                                    href="#"
                                                                                >
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_record_problem') }}
                                                                                </a>
                                                                                <a
                                                                                    id="delete-status-{{ $status->pivot->id }}"
                                                                                    data-status-id="{{ $status->pivot->id }}"
                                                                                    class="dropdown-item"
                                                                                    href="#"
                                                                                >
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_delete_status') }}
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="opacity-50"
                                                                    style="
                                                                        padding-top: 5px;
                                                                        padding-bottom: 5px;
                                                                    "
                                                                >
                                                                    <span style="font-size: 13px">
                                                                        {{ $status->address }}
                                                                    </span>
                                                                </div>
                                                                <div class="opacity-50">
                                                                    <span
                                                                        class="pe-1"
                                                                        style="font-size: 13px"
                                                                    >
                                                                        {{ \Carbon\Carbon::parse($status->pivot->date)->format('d.m.Y') }}
                                                                    </span>
                                                                    <span style="font-size: 13px">
                                                                        {{ \Carbon\Carbon::parse($status->pivot->date)->format('H:i') }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div
                                                            id="status-block-{{ $status->pivot->id }}"
                                                            data-status-info="{{ json_encode($status) }}"
                                                            class="d-flex gap-1 transport-planning-status"
                                                        >
                                                            <div class="">
                                                                <img
                                                                    src="{{ asset('assets/icons/entity/transport-planning/timeline.svg') }}"
                                                                />
                                                            </div>
                                                            <div class="w-100">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center"
                                                                >
                                                                    <div
                                                                        class="d-flex align-items-center"
                                                                    >
                                                                        <span
                                                                            class="fw-semibold"
                                                                            style="
                                                                                padding-right: 10px;
                                                                                font-size: 15px;
                                                                            "
                                                                        >
                                                                            @switch($status->key ?? null)
                                                                                @case('approval_price')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_approval_price') }}

                                                                                    @break
                                                                                @case('approved')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_approved') }}

                                                                                    @break
                                                                                @case('before_downloading')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_before_downloading') }}

                                                                                    @break
                                                                                @case('loaded')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_loaded') }}

                                                                                    @break
                                                                                @case('in_the_way')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_in_the_way') }}

                                                                                    @break
                                                                                @case('in_the_distribution_center')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_in_the_distribution_center') }}

                                                                                    @break
                                                                                @case('delivery_in_progress')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_delivery_in_progress') }}

                                                                                    @break
                                                                                @case('delivered')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_delivered') }}

                                                                                    @break
                                                                                @case('delivered_with_a_delay')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_delivered_with_a_delay') }}

                                                                                    @break
                                                                                @case('delivered_damaged')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_delivered_damaged') }}

                                                                                    @break
                                                                                @case('paid')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_paid') }}

                                                                                    @break
                                                                                @case('end_the_trip')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_end_the_trip') }}

                                                                                    @break
                                                                                @case('cancel_the_trip')
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_status_cancel_the_trip') }}

                                                                                    @break
                                                                                @default
                                                                                    {{ $status->name }}
                                                                            @endswitch
                                                                        </span>
                                                                        @if (! $status->failure_id && $status->pivot->comment)
                                                                            <i
                                                                                data-feather="info"
                                                                                data-bs-toggle="tooltip"
                                                                                title="{{ $status->pivot->comment }}"
                                                                            ></i>
                                                                        @endif

                                                                        @if ($status->failure_id)
                                                                            <img
                                                                                src="{{ asset('assets/icons/entity/transport-planning/problem-icon.svg') }}"
                                                                                data-bs-toggle="tooltip"
                                                                                title="{{ $status->failure_comment }}"
                                                                                style="
                                                                                    padding-left: 5px;
                                                                                    cursor: pointer;
                                                                                "
                                                                            />
                                                                        @endif
                                                                    </div>
                                                                    <div
                                                                        class="d-flex align-items-center"
                                                                        style="height: 25px"
                                                                    >
                                                                        <div
                                                                            class="btn-group js-button-dropdown-menu d-none js-button-dropdown-menu-hover align-items-center"
                                                                        >
                                                                            <div
                                                                                data-bs-toggle="dropdown"
                                                                                aria-expanded="false"
                                                                                class="d-flex align-items-center py-50 px-50"
                                                                            >
                                                                                <i
                                                                                    data-feather="more-vertical"
                                                                                    style="
                                                                                        cursor: pointer;
                                                                                    "
                                                                                ></i>
                                                                            </div>
                                                                            <div
                                                                                class="dropdown-menu"
                                                                                aria-labelledby="js-button-dropdown-menu"
                                                                            >
                                                                                <a
                                                                                    id="open-edit-status-modal-button-{{ $status->pivot->id }}"
                                                                                    data-status-id="{{ $status->pivot->id }}"
                                                                                    class="dropdown-item"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#edit-status-modal"
                                                                                    href="#"
                                                                                >
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_edit_status') }}
                                                                                </a>
                                                                                <a
                                                                                    id="open-add-failure-model-button-{{ $status->pivot->id }}"
                                                                                    data-status-id="{{ $status->pivot->id }}"
                                                                                    class="dropdown-item"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#problem-modal"
                                                                                    href="#"
                                                                                >
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_record_problem') }}
                                                                                </a>
                                                                                <a
                                                                                    id="delete-status-{{ $status->pivot->id }}"
                                                                                    data-status-id="{{ $status->pivot->id }}"
                                                                                    class="dropdown-item"
                                                                                    href="#"
                                                                                >
                                                                                    {{ __('localization.transport_planning_view_planning_list_item_delete_status') }}
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    style="
                                                                        padding-top: 5px;
                                                                        padding-bottom: 5px;
                                                                    "
                                                                >
                                                                    <span style="font-size: 13px">
                                                                        {{ $status->address }}
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    <span
                                                                        class="pe-1"
                                                                        style="font-size: 13px"
                                                                    >
                                                                        {{ \Carbon\Carbon::parse($status->pivot->date)->format('d.m.Y') }}
                                                                    </span>
                                                                    <span style="font-size: 13px">
                                                                        {{ \Carbon\Carbon::parse($status->pivot->date)->format('H:i') }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div
                                                class="d-flex"
                                                style="
                                                    background-color: rgba(168, 170, 174, 0.08);
                                                    border-bottom-left-radius: 6px;
                                                    border-bottom-right-radius: 6px;
                                                "
                                            >
                                                <button
                                                    type="button"
                                                    class="btn btn-flat-primary waves-effect w-100"
                                                    style="
                                                        border-top-left-radius: 0;
                                                        border-top-right-radius: 0;
                                                    "
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#change-status-modal"
                                                >
                                                    {{ __('localization.transport_planning_view_planning_list_item_add_status') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div
                        class="col-12 col-sm-12 col-md-12 col-lg-3 col-xxl-3 d-flex flex-column justify-content-between"
                        style="row-gap: 1rem"
                    >
                        <div>
                            <div class="planning-data-header pb-1">
                                <h5 class="fw-bolder">
                                    {{ __('localization.transport_planning_view_planning_list_item_planning_data') }}
                                </h5>
                            </div>
                            <div class="planning-data d-flex flex-column gap-1">
                                <div class="text-truncate">
                                    <span>
                                        {{ __('localization.transport_planning_view_planning_list_item_carrier') }}
                                        <a href="/companies/{{ $planning->carrier->id }}">
                                            <span class="transport-planning-yellow-text">
                                                {{ $planning->carrier->name }}
                                            </span>
                                        </a>
                                    </span>
                                </div>
                                <div class="text-truncate">
                                    <span>
                                        {{ __('localization.transport_planning_view_planning_list_item_driver') }}
                                        <a href="/user/show/{{ $planning->driver->id }}">
                                            <span class="transport-planning-yellow-text">
                                                {{ $planning->driver->full_name }}
                                            </span>
                                        </a>
                                    </span>
                                </div>
                                <div class="text-truncate">
                                    <span>
                                        {{ __('localization.transport_planning_view_planning_list_item_vehicle') }}
                                        <a href="/transport/{{ $planning->transport->id }}">
                                            <span class="transport-planning-yellow-text">
                                                {{ $planning->transport->name }}
                                            </span>
                                        </a>
                                    </span>
                                </div>
                                <div class="text-truncate">
                                    <span>
                                        {{ __('localization.transport_planning_view_planning_list_item_trailer') }}
                                        <a
                                            href="/transport-equipments/{{ $planning->additional_equipment->id }}"
                                        >
                                            <span class="transport-planning-yellow-text">
                                                {{ $planning->additional_equipment->name }}
                                            </span>
                                        </a>
                                    </span>
                                </div>
                                <div class="text-truncate">
                                    <span>
                                        {{ __('localization.transport_planning_view_planning_list_item_vehicle_capacity') }}
                                        <span class="transport-planning-bold-text">
                                            {{ $planning->additional_equipment->capacity_eu }}
                                            {{ __('localization.transport_planning_view_planning_list_item_vehicle_capacity_unit') }}
                                        </span>
                                    </span>
                                </div>
                                <div class="text-truncate">
                                    <span>
                                        {{ __('localization.transport_planning_view_planning_list_item_reserved') }}
                                        <span class="transport-planning-bold-text">
                                            {{ $planning->countPallets }}
                                            {{ __('localization.transport_planning_view_planning_list_item_reserved_unit') }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="planning-time d-flex flex-column" style="gap: 5px">
                            <div>
                                <span>
                                    {{ __('localization.transport_planning_view_planning_list_item_created_at') }}
                                    {{ \Carbon\Carbon::parse($planning->created_at)->format('d.m.Y') }}
                                    {{ __('localization.transport_planning_view_planning_list_item_at') }}
                                    <span class="transport-planning-bold-text">
                                        {{ \Carbon\Carbon::parse($planning->created_at)->format('H:i') }}
                                    </span>
                                </span>
                            </div>
                            <div>
                                <span>
                                    {{ __('localization.transport_planning_view_planning_list_item_updated_at') }}
                                    {{ \Carbon\Carbon::parse($planning->updated_at)->format('d.m.Y') }}
                                    {{ __('localization.transport_planning_view_planning_list_item_at') }}
                                    <span class="transport-planning-bold-text">
                                        {{ \Carbon\Carbon::parse($planning->updated_at)->format('H:i') }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xxl-6">
                        <div
                            class="transoprt-planning-orders-header d-flex justify-content-between pb-1"
                        >
                            <div class="d-flex gap-1 align-items-center">
                                <div>
                                    <h5 class="m-0 fw-bolder">
                                        {{ __('localization.transport_planning_view_planning_list_item_orders') }}
                                    </h5>
                                </div>
                                <div>
                                    <span
                                        class="transport-planning-yellow-text"
                                        style="cursor: pointer"
                                        data-bs-toggle="modal"
                                        data-bs-target="#transport-planning-orders-modal"
                                    >
                                        {{ __('localization.transport_planning_view_planning_list_item_all') }}
                                        ({{ count($planning->documents) }})
                                    </span>
                                </div>
                            </div>
                            <div>
                                <span>
                                    {{ __('localization.transport_planning_view_planning_list_item_total_pallets') }}
                                    <span class="transport-planning-bold-text">
                                        {{ $planning->countPallets }}
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div
                            class="d-flex flex-column gap-1"
                            style="height: 285px; overflow-y: auto"
                        >
                            @foreach ($planning->documents as $documentIndex => $document)
                                <div
                                    class="d-flex justify-content-between p-1 gap-2"
                                    style="
                                        background-color: rgba(168, 170, 174, 0.08);
                                        border-radius: 6px;
                                    "
                                >
                                    <div class="pe-0">
                                        <span class="transport-planning-bold-text">
                                            {{ $documentIndex + 1 }}
                                        </span>
                                    </div>
                                    <div class="p-0 flex-grow-1">
                                        <div style="padding-bottom: 10px">
                                            <a href="/document/{{ $document->id }}">
                                                <span class="transport-planning-yellow-text pe-1">
                                                    {{ $planning->documents[0]->documentType->key == 'tovarna_nakladna' ? 'ТН' : 'ЗНТ' }}{{ $document->id }}
                                                </span>
                                            </a>
                                            <span>
                                                {{ __('localization.transport_planning_view_planning_list_item_pallets') }}
                                            </span>
                                            <span class="transport-planning-bold-text">
                                                {{ $document->pallet }}
                                                ({{ $document->weight }}{{ __('localization.transport_planning_view_planning_list_item_pallet_unit') }})
                                            </span>
                                        </div>
                                        <div style="padding-bottom: 5px">
                                            <span>
                                                {{ __('localization.transport_planning_view_planning_list_item_supplier') }}
                                            </span>
                                            <span class="transport-planning-bold-text">
                                                {{ $document->company_provider }}
                                            </span>
                                        </div>
                                        <div style="padding-bottom: 5px">
                                            <span>
                                                {{ __('localization.transport_planning_view_planning_list_item_loading_warehouse') }}
                                            </span>
                                            <span class="transport-planning-bold-text">
                                                {{ $document->loading_warehouse }}
                                            </span>
                                        </div>
                                        <div style="padding-bottom: 5px">
                                            <span>
                                                {{ __('localization.transport_planning_view_planning_list_item_customer') }}
                                            </span>
                                            <span class="transport-planning-bold-text">
                                                {{ $document->company_customer }}
                                            </span>
                                        </div>
                                        <div>
                                            <span>
                                                {{ __('localization.transport_planning_view_planning_list_item_unloading_warehouse') }}
                                            </span>
                                            <span class="transport-planning-bold-text">
                                                {{ $document->unloading_warehouse }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ps-0 pe-1">
                                        <div
                                            class="btn-group js-button-dropdown-menu"
                                            id="transport-planning-order-dropdown"
                                        >
                                            <div
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                                class="py-25 px-50"
                                            >
                                                <i
                                                    data-feather="more-vertical"
                                                    style="cursor: pointer"
                                                ></i>
                                            </div>
                                            <div
                                                class="dropdown-menu"
                                                aria-labelledby="transport-planning-order-dropdown"
                                            >
                                                <a
                                                    class="dropdown-item"
                                                    href="/document/{{ $document->id }}"
                                                >
                                                    {{ __('localization.transport_planning_view_planning_list_item_view_tn') }}
                                                </a>
                                                <a
                                                    class="dropdown-item"
                                                    id="jsPrintBtn-{{ $planning->id }}-order-dropdown"
                                                >
                                                    {{ __('localization.transport_planning_view_planning_list_item_print_tn_document') }}
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    {{ __('localization.transport_planning_view_planning_list_item_view_ttn') }}
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    {{ __('localization.transport_planning_view_planning_list_item_print_ttn') }}
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    {{ __('localization.transport_planning_view_planning_list_item_view_pallet_lists') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Change status modal -->
        <div
            class="modal fade text-start"
            id="change-status-modal"
            tabindex="-1"
            aria-labelledby="myModalLabel18"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-2">
                    <div class="modal-header d-flex justify-content-center">
                        <h3 class="modal-title fw-bolder" id="myModalLabel18">
                            {{ __('localization.transport_planning_view_planning_list_item_change_status_modal_title') }}
                        </h3>
                    </div>
                    <div class="modal-body">
                        <div class="mb-1">
                            <label class="form-label" for="change-status-select">
                                {{ __('localization.transport_planning_view_planning_list_item_change_status_modal_status_label') }}
                            </label>
                            <select
                                class="form-select select2"
                                id="change-status-select"
                                data-placeholder="{{ __('localization.transport_planning_view_planning_list_item_change_status_modal_status_placeholder') }}"
                                required
                            >
                                <option value=""></option>

                                @foreach ($allStatuses as $status)
                                    <option value="{{ $status->id }}">
                                        @switch($status->key ?? null)
                                            @case('approval_price')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_approval_price') }}

                                                @break
                                            @case('approved')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_approved') }}

                                                @break
                                            @case('before_downloading')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_before_downloading') }}

                                                @break
                                            @case('loaded')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_loaded') }}

                                                @break
                                            @case('in_the_way')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_in_the_way') }}

                                                @break
                                            @case('in_the_distribution_center')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_in_the_distribution_center') }}

                                                @break
                                            @case('delivery_in_progress')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_delivery_in_progress') }}

                                                @break
                                            @case('delivered')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_delivered') }}

                                                @break
                                            @case('delivered_with_a_delay')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_delivered_with_a_delay') }}

                                                @break
                                            @case('delivered_damaged')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_delivered_damaged') }}

                                                @break
                                            @case('paid')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_paid') }}

                                                @break
                                            @case('end_the_trip')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_end_the_trip') }}

                                                @break
                                            @case('cancel_the_trip')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_cancel_the_trip') }}

                                                @break
                                            @default
                                                {{ $status->name }}
                                        @endswitch
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="change-status-select-location">
                                {{ __('localization.transport_planning_view_planning_list_item_change_status_modal_address_label') }}
                            </label>
                            <select
                                class="form-select select2"
                                id="change-status-select-location"
                                data-placeholder="{{ __('localization.transport_planning_view_planning_list_item_change_status_modal_address_placeholder') }}"
                                required
                            >
                                <option value=""></option>
                                @foreach ($allAddresses as $address)
                                    <option value="{{ $address->id }}">
                                        {{ $address->full_address }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 w-100 pb-1">
                            <label class="form-label" for="fp-date-time">
                                {{ __('localization.transport_planning_view_planning_list_item_change_status_modal_date_time_label') }}
                            </label>
                            <div style="position: relative">
                                <input
                                    type="text"
                                    id="fp-date-time"
                                    class="form-control flatpickr-date-time validateFromMon"
                                    placeholder="{{ __('localization.transport_planning_view_planning_list_item_change_status_modal_date_time_placeholder') }}"
                                    style="position: relative"
                                />
                                <img
                                    src="{{ asset('assets/icons/entity/transport-planning/calendar-tp.svg') }}"
                                    style="position: absolute; top: 10px; right: 10px"
                                />
                            </div>
                        </div>
                        <div>
                            <textarea
                                class="form-control"
                                id="change-status-comment"
                                rows="3"
                                placeholder="{{ __('localization.transport_planning_view_planning_list_item_change_status_modal_comment_placeholder') }}"
                                style="max-height: 150px; min-height: 100px"
                            ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: none">
                        <button
                            type="button"
                            class="btn btn-flat-secondary"
                            data-bs-dismiss="modal"
                        >
                            {{ __('localization.transport_planning_view_planning_list_item_change_status_modal_cancel_button') }}
                        </button>
                        <button
                            id="create-status-button"
                            data-transport-planning-id="{{ $planning->id }}"
                            type="button"
                            class="btn btn-primary"
                            data-bs-dismiss="modal"
                        >
                            {{ __('localization.transport_planning_view_planning_list_item_change_status_modal_update_button') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit status modal -->
        <div
            class="modal fade text-start"
            id="edit-status-modal"
            tabindex="-1"
            aria-labelledby="myModalLabel18"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-2">
                    <div class="modal-header d-flex justify-content-center">
                        <h3 class="modal-title fw-bolder" id="myModalLabel18">
                            {{ __('localization.transport_planning_view_planning_list_item_edit_status_modal_title') }}
                        </h3>
                    </div>
                    <div class="modal-body">
                        <div class="mb-1">
                            <label class="form-label" for="edit-status-select">
                                {{ __('localization.transport_planning_view_planning_list_item_edit_status_modal_status_label') }}
                            </label>
                            <select
                                class="form-select select2"
                                id="edit-status-select"
                                required
                                data-placeholder="{{ __('localization.transport_planning_view_planning_list_item_edit_status_modal_status_placeholder') }}"
                            >
                                <option value=""></option>
                                @foreach ($allStatuses as $status)
                                    <option
                                        value="{{ $status->id }}"
                                        {{ $status->id == $status->id ? 'selected' : '' }}
                                    >
                                        @switch($status->key ?? null)
                                            @case('approval_price')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_approval_price') }}

                                                @break
                                            @case('approved')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_approved') }}

                                                @break
                                            @case('before_downloading')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_before_downloading') }}

                                                @break
                                            @case('loaded')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_loaded') }}

                                                @break
                                            @case('in_the_way')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_in_the_way') }}

                                                @break
                                            @case('in_the_distribution_center')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_in_the_distribution_center') }}

                                                @break
                                            @case('delivery_in_progress')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_delivery_in_progress') }}

                                                @break
                                            @case('delivered')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_delivered') }}

                                                @break
                                            @case('delivered_with_a_delay')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_delivered_with_a_delay') }}

                                                @break
                                            @case('delivered_damaged')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_delivered_damaged') }}

                                                @break
                                            @case('paid')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_paid') }}

                                                @break
                                            @case('end_the_trip')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_end_the_trip') }}

                                                @break
                                            @case('cancel_the_trip')
                                                {{ __('localization.transport_planning_view_planning_list_item_status_cancel_the_trip') }}

                                                @break
                                            @default
                                                {{ $status->name }}
                                        @endswitch
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="edit-status-select-location">
                                {{ __('localization.transport_planning_view_planning_list_item_edit_status_modal_address_label') }}
                            </label>
                            <select
                                class="form-select select2"
                                id="edit-status-select-location"
                                required
                                data-placeholder="{{ __('localization.transport_planning_view_planning_list_item_edit_status_modal_address_placeholder') }}"
                            >
                                <option value=""></option>

                                @foreach ($allAddresses as $address)
                                    <option
                                        value="{{ $address->id }}"
                                        {{ $address->id == $address->id ? 'selected' : '' }}
                                    >
                                        {{ $address->full_address }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 w-100 pb-1">
                            <label class="form-label" for="fp-date-time-edit">
                                {{ __('localization.transport_planning_view_planning_list_item_edit_status_modal_date_time_label') }}
                            </label>
                            <div style="position: relative">
                                <input
                                    type="text"
                                    id="fp-date-time-edit"
                                    class="form-control flatpickr-date-time validateFromMon"
                                    placeholder="{{ __('localization.transport_planning_view_planning_list_item_edit_status_modal_date_time_placeholder') }}"
                                    style="position: relative"
                                />
                                <img
                                    src="{{ asset('assets/icons/entity/transport-planning/calendar-tp.svg') }}"
                                    style="position: absolute; top: 10px; right: 10px"
                                    alt="calendar"
                                />
                            </div>
                        </div>
                        <div>
                            <textarea
                                class="form-control"
                                id="edit-status-comment"
                                rows="3"
                                placeholder="{{ __('localization.transport_planning_view_planning_list_item_edit_status_modal_comment_placeholder') }}"
                                style="max-height: 150px; min-height: 100px"
                            ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: none">
                        <button
                            type="button"
                            class="btn btn-flat-secondary"
                            data-bs-dismiss="modal"
                        >
                            {{ __('localization.transport_planning_view_planning_list_item_edit_status_modal_cancel_button') }}
                        </button>
                        <button
                            id="edit-status-button"
                            type="button"
                            class="btn btn-primary"
                            data-bs-dismiss="modal"
                        >
                            {{ __('localization.transport_planning_view_planning_list_item_edit_status_modal_update_button') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Problem modal -->
        <div
            class="modal fade text-start"
            id="problem-modal"
            tabindex="-1"
            aria-labelledby="myModalLabel18"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content p-3">
                    <div class="modal-header d-flex justify-content-center">
                        <h3 class="modal-title fw-bolder" id="myModalLabel18">
                            {{ __('localization.transport_planning_view_planning_list_item_problem_modal_title') }}
                        </h3>
                    </div>
                    <div class="modal-body">
                        <div class="mb-1">
                            <label class="form-label" for="basicSelect">
                                {{ __('localization.transport_planning_view_planning_list_item_problem_modal_failure_type') }}
                            </label>
                            <select
                                class="form-select select2"
                                id="problemSelect"
                                data-placeholder="{{ __('localization.transport_planning_view_planning_list_item_problem_modal_failure_type_placeholder') }}"
                                required
                            >
                                <option value=""></option>
                                @foreach ($allFailureTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="basicInput">
                                {{ __('localization.transport_planning_view_planning_list_item_problem_modal_failure_reason') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="problem-reason"
                                placeholder="{{ __('localization.transport_planning_view_planning_list_item_problem_modal_failure_reason_placeholder') }}"
                                required
                            />
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="basicInput">
                                {{ __('localization.transport_planning_view_planning_list_item_problem_modal_fault_author') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="problem-author"
                                placeholder="{{ __('localization.transport_planning_view_planning_list_item_problem_modal_fault_author_placeholder') }}"
                                required
                            />
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="basicInput">
                                {{ __('localization.transport_planning_view_planning_list_item_problem_modal_penalty_amount') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="problem-price"
                                placeholder="{{ __('localization.transport_planning_view_planning_list_item_problem_modal_penalty_amount_placeholder') }}"
                                required
                            />
                        </div>
                        <div>
                            <textarea
                                class="form-control"
                                id="problem-comment"
                                rows="3"
                                placeholder="{{ __('localization.transport_planning_view_planning_list_item_problem_modal_comment_placeholder') }}"
                                style="max-height: 150px; min-height: 100px"
                            ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: none">
                        <button
                            type="button"
                            class="btn btn-flat-secondary"
                            data-bs-dismiss="modal"
                        >
                            {{ __('localization.transport_planning_view_planning_list_item_problem_modal_cancel_button') }}
                        </button>
                        <button
                            id="add-reason-submit"
                            type="button"
                            class="btn btn-primary"
                            data-bs-dismiss="modal"
                        >
                            {{ __('localization.transport_planning_view_planning_list_item_problem_modal_submit_button') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transport planning orders modal -->
        <div class="modal-size-xl d-inline-block">
            <div
                class="modal fade text-start"
                id="transport-planning-orders-modal"
                tabindex="-1"
                aria-labelledby="myModalLabel16"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            ></button>
                        </div>
                        <div class="modal-body">
                            <div class="pb-2">
                                <div class="modal-body-title pb-2">
                                    <h3 class="text-center fw-bolder">
                                        {{ $planning->documents[0]->documentType->key == 'tovarna_nakladna' ? __('localization.transport_planning_view_planning_list_all_tn_modal_title_tn') : __('localization.transport_planning_view_planning_list_all_tn_modal_title_znt') }}
                                    </h3>
                                </div>
                                <div class="d-flex flex-column gap-1">
                                    @foreach ($planning->documents as $documentIndex => $document)
                                        <div
                                            class="p-2"
                                            style="
                                                background-color: rgba(168, 170, 174, 0.08);
                                                border-radius: 6px;
                                            "
                                        >
                                            <div class="d-flex align-items-center pb-1">
                                                <span class="pe-1 fw-semibold">
                                                    {{ $documentIndex + 1 }}
                                                </span>
                                                <img
                                                    src="{{ asset('assets/icons/entity/transport-planning/info-square.svg') }}"
                                                    alt="info-square"
                                                />
                                                <span class="transport-planning-bold-text">
                                                    {{ $planning->documents[0]->documentType->key == 'tovarna_nakladna' ? __('localization.transport_planning_view_planning_list_all_tn_modal_doc_tn') : __('localization.transport_planning_view_planning_list_all_tn_modal_doc_znt') }}
                                                    №{{ $document->id }}
                                                </span>
                                            </div>
                                            <div class="d-flex ps-2 gap-1">
                                                <div
                                                    class="d-flex flex-column"
                                                    style="flex: 3; gap: 10px"
                                                >
                                                    <div>
                                                        <span>
                                                            {{ __('localization.transport_planning_view_planning_list_all_tn_modal_loading_warehouse_time') }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="transport-planning-bold-text">
                                                            {{ $document->loading_warehouse }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="transport-planning-bold-text">
                                                            {{ \Carbon\Carbon::parse($document->loading_date['date'])->format('d.m.Y') }}
                                                            {{ $document->loading_date['from'] }}-{{ $document->loading_date['to'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div
                                                    class="d-flex flex-column"
                                                    style="flex: 3; gap: 10px"
                                                >
                                                    <div>
                                                        <span>
                                                            {{ __('localization.transport_planning_view_planning_list_all_tn_modal_unloading_warehouse_time') }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="transport-planning-bold-text">
                                                            {{ $document->unloading_warehouse }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="transport-planning-bold-text">
                                                            {{ \Carbon\Carbon::parse($document->unloading_date['date'])->format('d.m.Y') }}
                                                            {{ $document->unloading_date['from'] }}-{{ $document->unloading_date['to'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="empty-container" style="flex: 3"></div>
                                                <div style="flex: 3">
                                                    <div
                                                        class="d-flex flex-column"
                                                        style="gap: 10px"
                                                    >
                                                        <div>
                                                            <span>
                                                                {{ __('localization.transport_planning_view_planning_list_all_tn_modal_total_weight') }}
                                                            </span>
                                                            <span
                                                                class="transport-planning-bold-text"
                                                            >
                                                                {{ $document->weight }}
                                                                {{ __('localization.transport_planning_view_planning_list_all_tn_modal_total_weight_unit') }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <span>
                                                                {{ __('localization.transport_planning_view_planning_list_all_tn_modal_actual_pallets') }}
                                                            </span>
                                                            <span
                                                                class="transport-planning-bold-text"
                                                            >
                                                                {{ $document->pallet }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/pickers/form-pickers.js') }}"></script>

    <script src="{{ asset('assets/js/entity/transport-planning/transport-planning-list.js') }}"></script>

    <script type="module">
        import { setupPrintButton } from '{{ asset('assets/js/utils/print-btn.js') }}';

        setupPrintButton("[id^='jsPrintBtn-']", 'jsCopyEventScope');
    </script>
@endsection
