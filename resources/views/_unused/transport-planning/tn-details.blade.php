@extends('layouts.admin')
@section('title', __('localization.transport_planning_view_tn_details_title'))

@section('page-style')
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
    <link
        rel="stylesheet"
        href="{{ asset('assets/libs/jqwidget/jqwidgets/styles/jqx.base.css') }}"
        type="text/css"
    />
    <link
        rel="stylesheet"
        href="{{ asset('assets/libs/jqwidget/jqwidgets/styles/jqx.light-wms.css') }}"
        type="text/css"
    />
@endsection

@section('table-js')
    @include('layouts.table-scripts')

    <script type="text/javascript">
        // Ініціалізуємо таби
        $('#tabs').jqxTabs({
            width: '100%',
            height: '100%',
        });
    </script>

    @if ($planning->documents[0]->documentType->key == 'tovarna_nakladna')
        <script
            type="module"
            src="{{ asset('assets/js/grid/transport-planning/goods-invoices-table.js') }}"
        ></script>
    @else
        <script
            type="module"
            src="{{ asset('assets/js/grid/transport-planning/request-for-transport-table-details.js') }}"
        ></script>
    @endif
    <script src="{{ asset('assets/js/utils/loader-for-tabs.js') }}"></script>
@endsection

@section('content')
    <div id="jqxLoader"></div>

    <div class="container-fluid px-2" id="jsCopyEventScope">
        <div class="tn-details-header pb-2 d-flex justify-content-between js-breadcrumb-wrapper">
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/transport-planning',
                            'name' => __(
                                'localization.transport_planning_view_tn_details_breadcrumb_transport_planning',
                            ),
                        ],
                        [
                            'name' => __(
                                'localization.transport_planning_view_tn_details_breadcrumb_tp_number',
                                ['id' => $planning->id],
                            ),
                        ],
                    ],
                ]
            )

            <div class="tn-details-actions d-flex align-items-center gap-50">
                <button
                    class="btn p-25 h-75"
                    id="jsPrintBtn"
                    title="{{ __('localization.transport_planning_view_planning_list_item_print_tooltip') }}"
                >
                    <i data-feather="printer" style="cursor: pointer; transform: scale(1.2)"></i>
                </button>

                <div
                    class="btn-group js-button-dropdown-menu"
                    id="delete-transport-planning-{{ $planning->id }}"
                    data-transport-id="{{ $planning->id }}"
                >
                    <div data-bs-toggle="dropdown" aria-expanded="false" class="py-25 px-50">
                        <i data-feather="more-vertical" style="cursor: pointer"></i>
                    </div>

                    <div class="dropdown-menu" aria-labelledby="js-button-dropdown-menu">
                        <a
                            data-bs-toggle="modal"
                            id="change-status-modal-finish-{{ $planning->id }}"
                            data-bs-target="#change-status-modal"
                            class="dropdown-item"
                            href="#"
                        >
                            {{ __('localization.transport_planning_view_tn_details_actions_finish_trip') }}
                        </a>
                        <a
                            data-bs-toggle="modal"
                            id="change-status-modal-cancel-{{ $planning->id }}"
                            data-bs-target="#change-status-modal"
                            class="dropdown-item"
                            href="#"
                        >
                            {{ __('localization.transport_planning_view_tn_details_actions_cancel_trip') }}
                        </a>
                        <a class="dropdown-item" href="#">
                            <p
                                class="text-danger mb-0"
                                data-bs-toggle="modal"
                                data-bs-target="#tp-delete-trip"
                            >
                                {{ __('localization.transport_planning_view_tn_details_actions_delete_trip') }}
                            </p>
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
                                        {{ __('localization.transport_planning_view_tn_details_actions_delete_trip_modal_confirmation') }}
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
                                                {{ __('localization.transport_planning_view_tn_details_actions_modal_cancel') }}
                                            </button>
                                        </div>
                                        <div class="w-100">
                                            <button
                                                id="delete-transport-planning"
                                                type="button"
                                                class="btn btn-primary w-100"
                                                data-bs-dismiss="modal"
                                            >
                                                {{ __('localization.transport_planning_view_tn_details_actions_modal_confirm') }}
                                            </button>
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
            class="m-0 d-flex flex-column flex-sm-column flex-md-column flex-lg-row flex-xxl-row flex-xl-row gap-1 justify-content-between pe-0 pe-md-0 pe-lg-1"
        >
            <div class="card col-12 col-sm-12 col-md-12 col-lg-9 col-xxl-9 p-2">
                <div class="pb-1">
                    <h4 class="fw-bolder">
                        {{ __('localization.transport_planning_view_tn_details_data_info_number', ['id' => $planning->id]) }}
                    </h4>
                </div>
                <div>
                    <div class="d-flex flex-row row">
                        <div
                            class="d-flex flex-column col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6"
                        >
                            <div>
                                <h5 class="pb-1 fw-bolder">
                                    {{ __('localization.transport_planning_view_tn_details_data_info_planning_data') }}
                                </h5>
                            </div>
                            <div class="d-flex">
                                <div class="col-6 d-flex flex-column">
                                    <div class="pb-1">
                                        <span>
                                            {{ __('localization.transport_planning_view_tn_details_data_info_provider_company') }}
                                        </span>
                                    </div>
                                    <div class="pb-1">
                                        <span>
                                            {{ __('localization.transport_planning_view_tn_details_data_info_trip_price') }}
                                        </span>
                                    </div>
                                    <div class="pb-1">
                                        <span>
                                            {{ __('localization.transport_planning_view_tn_details_data_info_payer') }}
                                        </span>
                                    </div>
                                    <div class="pb-1">
                                        <span>
                                            {{ __('localization.transport_planning_view_tn_details_data_info_updated') }}
                                        </span>
                                    </div>
                                    <div class="pb-1">
                                        <span>
                                            {{ __('localization.transport_planning_view_tn_details_data_info_created') }}
                                        </span>
                                    </div>
                                    <div class="pb-1">
                                        <span>
                                            {{ __('localization.transport_planning_view_tn_details_data_info_comment') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-6 d-flex flex-column">
                                    <div class="pb-1 text-truncate">
                                        <span class="transport-planning-bold-text">
                                            {{ $planning->provider->name }}
                                        </span>
                                    </div>
                                    <div class="pb-1 text-truncate">
                                        <span class="transport-planning-bold-text">
                                            {{ $planning->price }}
                                        </span>
                                    </div>

                                    <div class="pb-1 text-truncate">
                                        <span class="transport-planning-bold-text">
                                            {{ $planning->payer->name }}
                                        </span>
                                    </div>
                                    <div class="pb-1 text-truncate">
                                        <span class="transport-planning-bold-text">
                                            {{ \Carbon\Carbon::parse($planning->updated_at)->format('d.m.Y') }}
                                        </span>
                                    </div>
                                    <div class="pb-1 text-truncate">
                                        <span class="transport-planning-bold-text">
                                            {{ \Carbon\Carbon::parse($planning->created_ad)->format('d.m.Y') }}
                                        </span>
                                    </div>
                                    <div class="pb-1 text-truncate">
                                        <span class="transport-planning-bold-text">
                                            {{ $planning->comment ?? ' - ' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="d-flex flex-column col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6"
                        >
                            <div>
                                <h5 class="pb-1 fw-bolder">
                                    {{ __('localization.transport_planning_view_tn_details_data_info_carrier_data') }}
                                </h5>
                            </div>
                            <div class="d-flex">
                                <div class="col-6 d-flex flex-column">
                                    <div class="pb-1">
                                        <span>
                                            {{ __('localization.transport_planning_view_tn_details_data_info_carrier_company') }}
                                        </span>
                                    </div>
                                    <div class="pb-1">
                                        <span>
                                            {{ __('localization.transport_planning_view_tn_details_data_info_driver') }}
                                        </span>
                                    </div>
                                    <div class="pb-1">
                                        <span>
                                            {{ __('localization.transport_planning_view_tn_details_data_info_transport') }}
                                        </span>
                                    </div>
                                    <div class="pb-1">
                                        <span>
                                            {{ __('localization.transport_planning_view_tn_details_data_info_additional_equipment') }}
                                        </span>
                                    </div>
                                    <br />
                                    <div class="pb-1">
                                        <span>
                                            {{ __('localization.transport_planning_view_tn_details_data_info_capacity') }}
                                        </span>
                                    </div>
                                    <div class="pb-1">
                                        <span>
                                            {{ __('localization.transport_planning_view_tn_details_data_info_reserved') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-6 d-flex flex-column text-truncate">
                                    <div class="pb-1 text-truncate">
                                        <a href="/companies/{{ $planning->carrier->id }}">
                                            <span class="transport-planning-yellow-text">
                                                {{ $planning->carrier->name }}
                                            </span>
                                        </a>
                                    </div>
                                    <div class="pb-1 text-truncate">
                                        <a href="/user/show/{{ $planning->driver->id }}">
                                            <span class="transport-planning-yellow-text">
                                                {{ $planning->driver->full_name }}
                                            </span>
                                        </a>
                                    </div>
                                    <div class="pb-1 text-truncate">
                                        <a href="/transport/{{ $planning->transport->id }}">
                                            <span class="transport-planning-yellow-text">
                                                {{ $planning->transport->name }}
                                            </span>
                                        </a>
                                    </div>
                                    <div class="pb-1 text-truncate">
                                        <a
                                            href="/transport-equipments/{{ $planning->additional_equipment->id }}"
                                        >
                                            <span class="transport-planning-yellow-text">
                                                {{ $planning->additional_equipment->name }}
                                            </span>
                                        </a>
                                    </div>
                                    <br />
                                    <div class="pb-1 text-truncate">
                                        <span class="transport-planning-bold-text">
                                            {{ $planning->additional_equipment->capacity_eu }}
                                            {{ __('localization.transport_planning_view_tn_details_data_info_capacity_unit') }}
                                        </span>
                                    </div>
                                    <div class="pb-1 text-truncate">
                                        <span class="transport-planning-yellow-text">
                                            {{ $planning->allPallets }}
                                            {{ __('localization.transport_planning_view_tn_details_data_info_reserved_unit') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card col-12 col-sm-12 col-md-6 col-lg-3 col-xxl-3" style="height: 404px">
                <section id="transport-planning-status">
                    <div class="row mx-0">
                        <div class="col-sm-12">
                            <div id="transport-planning-status-wrapper">
                                <div>
                                    <div
                                        class="transport-planning-status-container d-flex flex-column p-1 gap-1"
                                        style="
                                            background-color: #ffffff;
                                            border-top-left-radius: 6px;
                                            border-top-right-radius: 6px;
                                            height: 366px;
                                            overflow: auto;
                                            border-bottom: 1px solid #d3d3d3;
                                        "
                                    >
                                        @foreach ($planning->statuses as $index => $status)
                                            <div
                                                id="status-block-{{ $status->pivot->id }}"
                                                data-status-info="{{ json_encode($status) }}"
                                                class="d-flex gap-1 transport-planning-status"
                                            >
                                                <div
                                                    class="{{ $index !== count($planning->statuses) - 1 ? 'opacity-50' : '' }}"
                                                >
                                                    <img
                                                        src="{{ asset('assets/icons/entity/transport-planning/timeline.svg') }}"
                                                    />
                                                </div>
                                                <div class="w-100">
                                                    <div
                                                        class="d-flex justify-content-between align-items-center"
                                                    >
                                                        <div
                                                            class="d-flex align-items-center {{ $index !== count($planning->statuses) - 1 ? 'opacity-50' : '' }}"
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
                                                                        style="cursor: pointer"
                                                                    ></i>
                                                                </div>
                                                                <div
                                                                    class="dropdown-menu"
                                                                    aria-labelledby="transport-planning-status-dropdown"
                                                                >
                                                                    <a
                                                                        id="open-edit-status-modal-button-{{ $status->pivot->id }}"
                                                                        data-status-id="{{ $status->pivot->id }}"
                                                                        class="dropdown-item"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#edit-status-modal"
                                                                        href="#"
                                                                    >
                                                                        {{ __('localization.transport_planning_view_tn_details_status_info_edit_current_status') }}
                                                                    </a>
                                                                    <a
                                                                        id="open-add-failure-model-button-{{ $status->pivot->id }}"
                                                                        data-status-id="{{ $status->pivot->id }}"
                                                                        class="dropdown-item"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#problem-modal"
                                                                        href="#"
                                                                    >
                                                                        {{ __('localization.transport_planning_view_tn_details_status_info_record_problem') }}
                                                                    </a>
                                                                    <a
                                                                        id="delete-status-{{ $status->pivot->id }}"
                                                                        data-status-id="{{ $status->pivot->id }}"
                                                                        class="dropdown-item"
                                                                        href="#"
                                                                    >
                                                                        {{ __('localization.transport_planning_view_tn_details_status_info_delete_current_status') }}
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="{{ $index !== count($planning->statuses) - 1 ? 'opacity-50' : '' }}"
                                                        style="
                                                            padding-top: 5px;
                                                            padding-bottom: 5px;
                                                        "
                                                    >
                                                        <span style="font-size: 13px">
                                                            {{ $status->address }}
                                                        </span>
                                                    </div>
                                                    <div
                                                        class="{{ $index !== count($planning->statuses) - 1 ? 'opacity-50' : '' }}"
                                                    >
                                                        <span class="pe-1" style="font-size: 13px">
                                                            {{ \Carbon\Carbon::parse($status->pivot->date)->format('d.m.Y') }}
                                                        </span>
                                                        <span style="font-size: 13px">
                                                            {{ \Carbon\Carbon::parse($status->pivot->date)->format('H:i') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div
                                        class="d-flex"
                                        style="
                                            background-color: #ffffff;
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
                                            {{ __('localization.transport_planning_view_tn_details_status_info_change_status') }}
                                        </button>
                                    </div>

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
                                                <div
                                                    class="modal-header d-flex justify-content-center"
                                                >
                                                    <h3 class="modal-title" id="myModalLabel18">
                                                        {{ __('localization.transport_planning_view_tn_details_change_status_modal_title') }}
                                                    </h3>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-1">
                                                        <label
                                                            class="form-label"
                                                            for="change-status-select"
                                                        >
                                                            {{ __('localization.transport_planning_view_tn_details_change_status_modal_status_label') }}
                                                        </label>
                                                        <select
                                                            class="form-select select2"
                                                            data-placeholder="{{ __('localization.transport_planning_view_tn_details_change_status_modal_status_placeholder') }}"
                                                            id="change-status-select"
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
                                                        <label
                                                            class="form-label"
                                                            for="change-status-select-location"
                                                        >
                                                            {{ __('localization.transport_planning_view_tn_details_change_status_modal_address_label') }}
                                                        </label>
                                                        <select
                                                            class="form-select select2"
                                                            id="change-status-select-location"
                                                            data-placeholder="{{ __('localization.transport_planning_view_tn_details_change_status_modal_address_placeholder') }}"
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
                                                        <label
                                                            class="form-label"
                                                            for="fp-date-time"
                                                        >
                                                            {{ __('localization.transport_planning_view_tn_details_change_status_modal_datetime_label') }}
                                                        </label>
                                                        <div class="" style="position: relative">
                                                            <input
                                                                type="text"
                                                                id="fp-date-time"
                                                                class="form-control flatpickr-date-time validateFromMon"
                                                                placeholder="{{ __('localization.transport_planning_view_tn_details_change_status_modal_datetime_placeholder') }}"
                                                                style="position: relative"
                                                            />
                                                            <img
                                                                src="{{ asset('assets/icons/entity/transport-planning/calendar-tp.svg') }}"
                                                                style="
                                                                    position: absolute;
                                                                    top: 10px;
                                                                    right: 10px;
                                                                "
                                                            />
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                        <textarea
                                                            class="form-control"
                                                            id="change-status-comment"
                                                            rows="3"
                                                            placeholder="{{ __('localization.transport_planning_view_tn_details_change_status_modal_comment_placeholder') }}"
                                                            style="
                                                                max-height: 150px;
                                                                min-height: 100px;
                                                            "
                                                        ></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer" style="border-top: none">
                                                    <button
                                                        type="button"
                                                        class="btn btn-flat-secondary"
                                                        data-bs-dismiss="modal"
                                                    >
                                                        {{ __('localization.transport_planning_view_tn_details_change_status_modal_cancel_button') }}
                                                    </button>
                                                    <button
                                                        id="create-status-button"
                                                        type="button"
                                                        class="btn btn-primary"
                                                        data-transport-planning-id="{{ $planning->id }}"
                                                        data-bs-dismiss="modal"
                                                    >
                                                        {{ __('localization.transport_planning_view_tn_details_change_status_modal_update_button') }}
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
                                                <div
                                                    class="modal-header d-flex justify-content-center"
                                                >
                                                    <h3
                                                        class="modal-title fw-bolder"
                                                        id="myModalLabel18"
                                                    >
                                                        {{ __('localization.transport_planning_view_tn_details_edit_status_modal_title') }}
                                                    </h3>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-1">
                                                        <label
                                                            class="form-label"
                                                            for="edit-status-select"
                                                        >
                                                            {{ __('localization.transport_planning_view_tn_details_edit_status_modal_status_label') }}
                                                        </label>
                                                        <select
                                                            class="form-select select2"
                                                            id="edit-status-select"
                                                            data-placeholder="{{ __('localization.transport_planning_view_tn_details_edit_status_modal_status_placeholder') }}"
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
                                                        <label
                                                            class="form-label"
                                                            for="edit-status-select-location"
                                                        >
                                                            {{ __('localization.transport_planning_view_tn_details_edit_status_modal_address_label') }}
                                                        </label>
                                                        <select
                                                            class="form-select select2"
                                                            id="edit-status-select-location"
                                                            data-placeholder="{{ __('localization.transport_planning_view_tn_details_edit_status_modal_address_placeholder') }}"
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
                                                        <label
                                                            class="form-label"
                                                            for="fp-date-time-edit"
                                                        >
                                                            {{ __('localization.transport_planning_view_tn_details_edit_status_modal_datetime_label') }}
                                                        </label>
                                                        <div class="" style="position: relative">
                                                            <input
                                                                type="text"
                                                                id="fp-date-time-edit"
                                                                class="form-control flatpickr-date-time validateFromMon"
                                                                placeholder="{{ __('localization.transport_planning_view_tn_details_edit_status_modal_datetime_placeholder') }}"
                                                                style="position: relative"
                                                            />
                                                            <img
                                                                src="{{ asset('assets/icons/entity/transport-planning/calendar-tp.svg') }}"
                                                                style="
                                                                    position: absolute;
                                                                    top: 10px;
                                                                    right: 10px;
                                                                "
                                                            />
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                        <textarea
                                                            class="form-control"
                                                            id="edit-status-comment"
                                                            rows="3"
                                                            placeholder="{{ __('localization.transport_planning_view_tn_details_edit_status_modal_comment_placeholder') }}"
                                                            style="
                                                                max-height: 150px;
                                                                min-height: 100px;
                                                            "
                                                        ></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer" style="border-top: none">
                                                    <button
                                                        type="button"
                                                        class="btn btn-flat-secondary"
                                                        data-bs-dismiss="modal"
                                                    >
                                                        {{ __('localization.transport_planning_view_tn_details_edit_status_modal_cancel_button') }}
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="btn btn-primary"
                                                        id="edit-status-button"
                                                        data-bs-dismiss="modal"
                                                    >
                                                        {{ __('localization.transport_planning_view_tn_details_edit_status_modal_update_button') }}
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
                                                <div
                                                    class="modal-header d-flex justify-content-center"
                                                >
                                                    <h3 class="modal-title" id="myModalLabel18">
                                                        {{ __('localization.transport_planning_view_tn_details_problem_modal_title') }}
                                                    </h3>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="basicSelect">
                                                            {{ __('localization.transport_planning_view_tn_details_problem_modal_failure_type_label') }}
                                                        </label>
                                                        <select
                                                            class="form-select select2"
                                                            id="problemSelect"
                                                            data-placeholder="{{ __('localization.transport_planning_view_tn_details_problem_modal_failure_type_placeholder') }}"
                                                            required
                                                        >
                                                            <option value=""></option>
                                                            @foreach ($allFailureTypes as $type)
                                                                <option
                                                                    selected
                                                                    value="{{ $type->id }}"
                                                                >
                                                                    {{ $type->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-1">
                                                        <label class="form-label" for="basicInput">
                                                            {{ __('localization.transport_planning_view_tn_details_problem_modal_failure_reason_label') }}
                                                        </label>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            id="problem-reason"
                                                            placeholder="{{ __('localization.transport_planning_view_tn_details_problem_modal_failure_reason_placeholder') }}"
                                                            required
                                                        />
                                                    </div>
                                                    <div class="mb-1">
                                                        <label class="form-label" for="basicInput">
                                                            {{ __('localization.transport_planning_view_tn_details_problem_modal_failure_author_label') }}
                                                        </label>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            id="problem-author"
                                                            placeholder="{{ __('localization.transport_planning_view_tn_details_problem_modal_failure_author_placeholder') }}"
                                                            required
                                                        />
                                                    </div>
                                                    <div class="mb-1">
                                                        <label class="form-label" for="basicInput">
                                                            {{ __('localization.transport_planning_view_tn_details_problem_modal_failure_price_label') }}
                                                        </label>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            id="problem-price"
                                                            placeholder="{{ __('localization.transport_planning_view_tn_details_problem_modal_failure_price_placeholder') }}"
                                                            required
                                                        />
                                                    </div>
                                                    <div class="">
                                                        <textarea
                                                            class="form-control"
                                                            id="problem-comment"
                                                            rows="3"
                                                            placeholder="{{ __('localization.transport_planning_view_tn_details_problem_modal_comment_placeholder') }}"
                                                            style="
                                                                max-height: 150px;
                                                                min-height: 100px;
                                                            "
                                                        ></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer" style="border-top: none">
                                                    <button
                                                        type="button"
                                                        class="btn btn-flat-secondary"
                                                        data-bs-dismiss="modal"
                                                    >
                                                        {{ __('localization.transport_planning_view_tn_details_problem_modal_cancel_button') }}
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="btn btn-primary"
                                                        id="add-reason-submit"
                                                        data-bs-dismiss="modal"
                                                    >
                                                        {{ __('localization.transport_planning_view_tn_details_problem_modal_submit_button') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- Table with tabs -->
        <div class="col-xl-12 col-lg-12 mb-2">
            <div class="card-body">
                <div class="d-flex justify-content-between tp-tables">
                    <div id="tabs" style="overflow: visible; position: relative" class="invisible">
                        <ul class="d-flex">
                            @if ($planning->documents[0]->documentType->key == 'tovarna_nakladna')
                                <li id="schedule-tab">
                                    {{ __('localization.transport_planning_view_tn_details_tabs_goods_invoices') }}
                                </li>
                            @else
                                <li id="code-tab">
                                    {{ __('localization.transport_planning_view_tn_details_tabs_transport_request') }}
                                </li>
                            @endif
                        </ul>
                        @if ($planning->documents[0]->documentType->key == 'tovarna_nakladna')
                            <div id="schedule">
                                <div class="card-grid" style="position: relative">
                                    @include('layouts.table-setting')

                                    <div class="table-block" id="goods-invoices-table"></div>
                                </div>
                            </div>
                        @else
                            <div id="code">
                                <div class="card-grid" style="position: relative">
                                    @include('layouts.table-setting', ['idOne' => 'settingTable-tr-request', 'idTwo' => 'changeFonts-tr-request', 'idThree' => 'changeCol-tr-request', 'idFour' => 'jqxlistbox-tr-request'])

                                    <div class="table-block" id="transport-request-table"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        const planning_id = {!! $planning->id !!};
    </script>

    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/pickers/form-pickers.js') }}"></script>

    <script src="{{ asset('assets/js/entity/transport-planning/transport-planning-list.js') }}"></script>

    @if ($planning->documents[0]->documentType->key == 'tovarna_nakladna')
        <script type="module">
            import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';
            import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

            tableSetting($('#goods-invoices-table'), '', 85, 100);
            offCanvasByBorder($('#goods-invoices-table'));
        </script>
    @else
        <script type="module">
            import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';
            import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

            tableSetting($('#transport-request-table'), '-tr-request', 85, 100);
            offCanvasByBorder($('#transport-request-table'), '-tr-request');
        </script>
    @endif

    <script type="module">
        import { setupPrintButton } from '{{ asset('assets/js/utils/print-btn.js') }}';

        setupPrintButton('jsPrintBtn', 'jsCopyEventScope');
    </script>
@endsection
