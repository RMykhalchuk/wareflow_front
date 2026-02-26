@extends('layouts.admin')
@section('title', __('localization.transport_planning_create_title'))

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

        var addButton = $(
            '<li class="btn-tabs-watch-all"> <button id="add-new-goods-invoices-item" disabled class="btn btn-primary position-absolute " style="top: 13px; right: 15px">{{ __('localization.transport_planning_create_tabs_1_add_btn') }}</button> </li>'
        );
        addButton.appendTo('#tabs ');

        var addTransportRequestButton = $(
            '<li class="btn-tabs-watch-all"> <button id="add-new-transport-request-item" disabled class="btn btn-primary position-absolute d-none" style="top: 13px; right: 15px">{{ __('localization.transport_planning_create_tabs_2_add_btn') }}</button> </li>'
        );
        addTransportRequestButton.appendTo('#tabs ');
    </script>

    <script
        type="module"
        src="{{ asset('assets/js/grid/transport-planning/commodity-invoice-table.js') }}"
    ></script>
    <script
        type="module"
        src="{{ asset('assets/js/grid/transport-planning/request-for-transport-table.js') }}"
    ></script>
    <script src="{{ asset('assets/js/utils/loader-for-tabs.js') }}"></script>
@endsection

@section('content')
    <div id="jqxLoader"></div>

    <div class="container-fluid px-2">
        <!-- контейнер з навігацією і кнопками -->
        <div
            class="d-flex justify-content-between pb-2 flex-column flex-sm-column flex-md-row flex-lg-row flex-xxl-row"
        >
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/transport-planning',
                            'name' => __('localization.transport_planning_create_transport_planning'),
                        ],
                        [
                            'name' => __('localization.transport_planning_create_creation'),
                        ],
                    ],
                ]
            )

            <div class="d-flex justify-content-end gap-25">
                <a
                    href="/transport-planning/create"
                    type="button"
                    id="cancel-transport-planning"
                    class="btn border-0 disabled"
                    tabindex="4"
                >
                    <span class="align-middle d-sm-inline-block text-secondary">
                        {{ __('localization.transport_planning_create_breadcrumb_cancel') }}
                    </span>
                </a>
                <button
                    type="button"
                    class="btn btn-primary"
                    id="create-transport-planning"
                    tabindex="4"
                >
                    <span class="align-middle d-sm-inline-block">
                        {{ __('localization.transport_planning_create_breadcrumb_save') }}
                    </span>
                </button>
            </div>
        </div>

        <!-- контейнер з селектами та полями для створення ТP-->
        <div class="card p-2">
            <h4 class="mb-2">{{ __('localization.transport_planning_create_main_info') }}</h4>
            <!-- набір чекбоксів -->
            <div
                class="d-flex gap-1 gap-sm-1 gap-lg-0 flex-column flex-sm-column flex-md-column flex-lg-row mb-1"
            >
                <div class="form-check form-check-inline">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="3pl-auto"
                        value="unchecked"
                    />
                    <label class="form-check-label" for="3pl-auto">
                        {{ __('localization.transport_planning_create_3pl_auto') }}
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="auto-search"
                        checked
                        value="checked"
                    />
                    <label class="form-check-label" for="auto-search">
                        {{ __('localization.transport_planning_create_auto_search') }}
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="init-auto"
                        value="unchecked"
                    />
                    <label class="form-check-label" for="init-auto">
                        {{ __('localization.transport_planning_create_init_auto') }}
                    </label>
                </div>
            </div>
            <!-- набір селектів та інпутів в двох групах -->
            <div class="row">
                <div class="col-12 col-sm-12 col-md-6 mb-1">
                    <label class="form-label" for="select-company-provider">
                        {{ __('localization.transport_planning_create_company_provider') }}
                    </label>
                    <select
                        class="select2 form-select"
                        id="select-company-provider"
                        data-placeholder="{{ __('localization.transport_planning_create_select_company') }}"
                        data-dictionary="company"
                    >
                        <option value=""></option>
                    </select>
                </div>

                <div class="col-12 col-sm-12 col-md-6 mb-1">
                    <label class="form-label" for="select-company-transporter">
                        {{ __('localization.transport_planning_create_company_transporter') }}
                    </label>
                    <select
                        class="select2 form-select"
                        id="select-company-transporter"
                        data-placeholder="{{ __('localization.transport_planning_create_select_company_transporter') }}"
                        data-dictionary="company"
                    >
                        <option value=""></option>
                    </select>
                </div>

                <div class="col-12 col-sm-12 col-md-6 mb-1">
                    <label class="form-label" for="select-transport">
                        {{ __('localization.transport_planning_create_transport') }}
                    </label>
                    <select
                        class="select2 form-select"
                        id="select-transport"
                        data-placeholder="{{ __('localization.transport_planning_create_select_transport') }}"
                        data-dictionary="transport"
                    >
                        <option value=""></option>
                    </select>
                </div>

                <div class="col-12 col-sm-12 col-md-6 mb-1">
                    <label class="form-label" for="select-equipment">
                        {{ __('localization.transport_planning_create_equipment') }}
                    </label>
                    <select
                        class="select2 form-select"
                        id="select-equipment"
                        data-placeholder="{{ __('localization.transport_planning_create_select_equipment') }}"
                        data-dictionary="additional_equipment"
                    >
                        <option value=""></option>
                    </select>
                </div>

                <div class="col-12 col-sm-12 col-md-6 mb-1">
                    <label class="form-label" for="select-payer">
                        {{ __('localization.transport_planning_create_payer') }}
                    </label>
                    <select
                        class="select2 form-select"
                        id="select-payer"
                        data-placeholder="{{ __('localization.transport_planning_create_select_payer') }}"
                        data-dictionary="company"
                    >
                        <option value=""></option>
                    </select>
                </div>

                <div
                    class="col-12 col-sm-12 col-md-6 input-with-switch mb-1 d-flex align-items-end"
                >
                    <div class="w-100 mr-1">
                        <label class="form-label" for="price">
                            {{ __('localization.transport_planning_create_price') }}
                        </label>
                        <input
                            type="number"
                            class="form-control"
                            id="price"
                            placeholder="{{ __('localization.transport_planning_create_price_placeholder') }}"
                        />
                    </div>
                    <div class="form-check form-switch flex-shrink-1" style="padding-bottom: 8px">
                        <input type="checkbox" class="form-check-input" id="pdv" />
                        <label class="form-check-label" for="pdv" style="width: 70px">
                            {{ __('localization.transport_planning_create_with_vat') }}
                        </label>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-6 mb-1">
                    <label class="form-label" for="select-driver">
                        {{ __('localization.transport_planning_create_driver') }}
                    </label>
                    <select
                        class="select2 form-select"
                        id="select-driver"
                        data-placeholder="{{ __('localization.transport_planning_create_select_driver') }}"
                        data-dictionary="driver"
                    >
                        <option value=""></option>
                    </select>
                </div>

                <div class="d-none d-sm-none d-md-block col-md-6 mb-1"></div>

                <div class="trans-planning-textarea col-12 col-sm-12 col-md-6 mb-1">
                    <textarea
                        class="form-control"
                        id="comment"
                        rows="3"
                        placeholder="{{ __('localization.transport_planning_create_comment') }}"
                    ></textarea>
                </div>
            </div>

            <div id="validate-error"></div>
        </div>

        <div class="card">
            <div class="card-header pb-0 mx-0 row">
                <h4
                    class="card-title col-8 col-sm-8 col-md-8 col-lg-9 col-xxl-9 fw-semibold pb-2 pb-md-0"
                >
                    {{ __('localization.transport_planning_create_documents_title') }}
                </h4>
                <div class="col-4 col-sm-4 col-md-4 col-lg-3 col-xxl-3 pe-0">
                    <a
                        data-bs-toggle="modal"
                        id="button_sku_tp"
                        data-bs-target="#add_sku_tp"
                        class="btn btn-outline-primary float-end d-flex align-items-center"
                        href="#"
                    >
                        <img
                            class="plus-icon"
                            src="{{ asset('assets/icons/entity/transport-planning/plus-yellow-tp.svg') }}"
                            alt="plus-yellow"
                        />
                        {{ __('localization.transport_planning_create_add_document') }}
                    </a>
                </div>
            </div>

            <div class="mt-1 p-2">
                <div class="card-body p-0" id="sortable"></div>
                <div
                    class="d-flex align-items-center justify-content-center"
                    id="js-tp-sortable-placeholder"
                    style="height: 120px"
                >
                    <h4 class="text-secondary fw-normal text-center">
                        {{ __('localization.transport_planning_create_start_adding') }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="modal-size-xl d-inline-block">
            <div
                class="modal fade text-start"
                id="add_sku_tp"
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
                            <div class="modal-body-title pb-2">
                                <h3 class="text-center fw-bolder">
                                    {{ __('localization.transport_planning_create_modal_title') }}
                                </h3>
                            </div>
                            <div class="m-2">
                                <div class="card-body p-0">
                                    <div class="d-flex justify-content-between tp-tables">
                                        <div
                                            id="tabs"
                                            style="overflow: visible; position: relative"
                                            class="invisible"
                                        >
                                            <ul class="d-flex">
                                                <li id="schedule-tab">
                                                    {{ __('localization.transport_planning_create_modal_schedule') }}
                                                </li>
                                                <li id="transport-request-tab">
                                                    {{ __('localization.transport_planning_create_modal_transport_request') }}
                                                </li>
                                            </ul>

                                            <div id="schedule">
                                                <div class="card-grid" style="position: relative">
                                                    @include('layouts.table-setting')
                                                    <div
                                                        class="table-block"
                                                        id="commodityInvoiceDataTable"
                                                    ></div>
                                                </div>
                                            </div>
                                            <div id="transport-request">
                                                <div class="card-grid" style="position: relative">
                                                    @include('layouts.table-setting', ['idOne' => 'settingTable-tr-request', 'idTwo' => 'changeFonts-tr-request', 'idThree' => 'changeCol-tr-request', 'idFour' => 'jqxlistbox-tr-request'])
                                                    <div
                                                        class="table-block"
                                                        id="transportRequestDataTable"
                                                    ></div>
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
@endsection

@section('page-script')
    <script
        type="module"
        src="{{ asset('assets/js/entity/transport-planning/create-of-TP.js') }}"
    ></script>

    <!-- Jquery UI start -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"
        integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    ></script>
    <!-- Jquery UI end -->

    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/pickers/form-pickers.js') }}"></script>

    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#commodityInvoiceDataTable'), '', 85, 100);
        tableSetting($('#transportRequestDataTable'), '-tr-request', 85, 100);
    </script>

    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#commodityInvoiceDataTable'));
        offCanvasByBorder($('#transportRequestDataTable'), '-tr-request');
    </script>

    {{-- Код який ховає селект із к-стю записів в таблиці --}}
    <script>
        $('#button_sku_tp').click(function (e) {
            $('.custom-pager-select-size').addClass('d-none');
            hidenPaginationInfo();
        });
        $('#tabs').on('click', function () {
            hidenPaginationInfo();
        });

        function hidenPaginationInfo() {
            $('.custom-pager')
                .find('button')
                .on('click', function () {
                    $('.custom-pager-select-size').addClass('d-none');
                });
        }
    </script>

    {{-- <script>$(document).ready(function() { --}}
    {{-- $(".flatpickr-range").flatpickr({ --}}
    {{-- enableTime: true, --}}
    {{-- noCalendar: true, --}}
    {{-- dateFormat: "H:i", --}}
    {{-- time_24hr: true, --}}
    {{-- defaultDate: ["17:00 -", "20:00"] --}}
    {{-- }); --}}
    {{-- }); --}}
    {{-- </script> --}}

    <script src="//cdnjs.cloudflare.com/ajax/libs/validate.js/0.13.1/validate.min.js"></script>
    <script src="{{ asset('assets/js/utils/unused/validate.js') }}"></script>
@endsection
