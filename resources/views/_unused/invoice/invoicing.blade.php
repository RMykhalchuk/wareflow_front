@extends('layouts.admin')

@section('title', __('localization.invoice_invoicing_title'))

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
    <script
        type="module"
        src="{{ asset('assets/js/entity/invoice/payment-obligation.js') }}"
    ></script>
    <script
        type="module"
        src="{{ asset('assets/js/grid/invoice/selected-payment-obligations-table.js') }}"
    ></script>
    <script
        type="module"
        src="{{ asset('assets/js/grid/invoice/payment-obligations-table.js') }}"
    ></script>
@endsection

@section('content')
    <div class="px-2">
        <div class="d-flex align-items-center flex-column flex-lg-row justify-content-between pb-2">
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/document',
                            'name' => __('localization.invoice_invoicing_breadcrumb_documents'),
                        ],
                        [
                            'url' => '/invoices/',
                            'name' => __('localization.invoice_invoicing_breadcrumb_invoices'),
                        ],
                        ['name' => __('localization.invoice_invoicing_breadcrumb_create')],
                    ],
                ]
            )

            <div class="tn-details-actions d-flex gap-1 align-self-end">
                <button type="submit" class="btn btn-flat-primary">
                    {{ __('localization.invoice_invoicing_save_as_draft') }}
                </button>
                <button type="submit" class="btn btn-green">
                    {{ __('localization.invoice_invoicing_save') }}
                </button>
            </div>
        </div>

        <div>
            <div class="card mb-2 py-1">
                <div class="row mx-0">
                    <div class="card col-12 p-2 mb-0">
                        <div class="card-header p-0">
                            <h4 class="card-title fw-bolder">
                                {{ __('localization.invoice_invoicing_title_content') }}
                            </h4>
                        </div>
                        <div class="card-body p-0 mt-1">
                            <div class="row">
                                <div class="col-12 col-md-6 mb-1">
                                    <label class="form-label" for="supplier-of-services">
                                        {{ __('localization.invoice_invoicing_supplier') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="supplier-of-services"
                                        data-placeholder="{{ __('localization.invoice_invoicing_supplier_placeholder') }}"
                                    >
                                        <option value=""></option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6 mb-1">
                                    <label class="form-label" for="responsible-for-supply">
                                        {{ __('localization.invoice_invoicing_responsible_supply') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="responsible-for-supply"
                                        data-placeholder="{{ __('localization.invoice_invoicing_responsible_supply_placeholder') }}"
                                    >
                                        <option value=""></option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6 mb-1">
                                    <label class="form-label" for="recipient-of-services">
                                        {{ __('localization.invoice_invoicing_recipient') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="recipient-of-services"
                                        data-placeholder="{{ __('localization.invoice_invoicing_recipient_placeholder') }}"
                                    >
                                        <option value=""></option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6 mb-1">
                                    <label class="form-label" for="responsible-for-receiving">
                                        {{ __('localization.invoice_invoicing_responsible_receive') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="responsible-for-receiving"
                                        data-placeholder="{{ __('localization.invoice_invoicing_responsible_receive_placeholder') }}"
                                    >
                                        <option value=""></option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6 mb-1">
                                    <label class="form-label" for="basis-of-contract">
                                        {{ __('localization.invoice_invoicing_contract') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="basis-of-contract"
                                        data-placeholder="{{ __('localization.invoice_invoicing_contract_placeholder') }}"
                                    >
                                        <option value=""></option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6 mb-1 position-relative">
                                    <label class="form-label" for="fp-default">
                                        {{ __('localization.invoice_invoicing_invoice_date') }}
                                    </label>
                                    <input
                                        type="text"
                                        id="fp-default"
                                        class="form-control flatpickr-basic flatpickr-input"
                                        required
                                        placeholder="{{ __('localization.invoice_invoicing_invoice_date_placeholder') }}"
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

                                <div class="col-12 col-md-6 mb-1">
                                    <label class="form-label" for="disabledInput">
                                        {{ __('localization.invoice_invoicing_amount_without_vat') }}
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="readonlyInput"
                                        readonly="readonly"
                                        value="{{ __('localization.invoice_invoicing_amount_without_vat_placeholder') }}"
                                        disabled
                                    />
                                </div>

                                <div class="col-12 col-md-6 mb-1">
                                    <label class="form-label" for="disabledInput2">
                                        {{ __('localization.invoice_invoicing_amount_with_vat') }}
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="readonlyInput"
                                        readonly="readonly"
                                        value="{{ __('localization.invoice_invoicing_amount_with_vat_placeholder') }}"
                                        disabled
                                    />
                                </div>

                                <div class="col-12 col-md-6 mb-1">
                                    <label class="form-label" for="payment-term">
                                        {{ __('localization.invoice_invoicing_payment_term') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="payment-term"
                                        data-placeholder="{{ __('localization.invoice_invoicing_payment_term_placeholder') }}"
                                    >
                                        <option value=""></option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>

                                <!-- For errors -->
                                <div class="col-12">
                                    <div
                                        class="alert alert-danger alert-dismissible fade hide mt-1"
                                        role="alert"
                                    >
                                        <button
                                            type="button"
                                            class="close"
                                            data-bs-dismiss="alert"
                                            aria-label="Close"
                                        >
                                            <span aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-bottom row mx-0 gap-1">
                <h4 class="card-title col-auto fw-bolder px-0">
                    {{ __('localization.invoice_invoicing_payment_obligations') }}
                </h4>
                <div class="col-auto d-flex flex-grow-1 justify-content-end pe-0"></div>
            </div>

            @if (True)
                <div class="card-grid" style="position: relative">
                    @include('layouts.table-setting')
                    <div class="table-block" id="selected-payment-obligations-table"></div>
                </div>
            @else
                <div style="margin: 50px 0">
                    <div class="text-center">
                        {{ __('localization.invoice_invoicing_no_payment_obligations') }}
                    </div>
                    <div class="text-center mt-2">
                        <a
                            class="btn btn-primary"
                            href="#"
                            data-bs-toggle="modal"
                            data-bs-target="#modalAddPaymentObligations"
                        >
                            {{ __('localization.invoice_invoicing_add_payment_obligation') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div
        class="modal fade"
        id="modalAddPaymentObligations"
        tabindex="-1"
        aria-labelledby="modalAddPaymentObligations"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content p-3">
                <div class="pb-2">
                    <h3 class="text-center">
                        {{ __('localization.invoice_invoicing_modal_add_payment_obligations_title') }}
                    </h3>
                </div>
                <div class="card mb-0">
                    <div class="card-header border-bottom row mx-0 gap-1">
                        <h4 class="card-title col-auto fw-bolder px-0">
                            {{ __('localization.invoice_invoicing_modal_add_payment_obligations_subtitle') }}
                        </h4>
                        <div class="col-auto d-flex flex-grow-1 justify-content-end pe-0"></div>
                    </div>
                    <div class="card-grid" style="position: relative">
                        @include('layouts.table-setting')
                        <div class="table-block" id="payment-obligations-table"></div>
                    </div>
                </div>
                <div class="d-flex pt-2 gap-1 justify-content-end">
                    <button
                        type="button"
                        class="btn btn-flat-secondary waves-effect waves-float waves-light"
                        data-bs-dismiss="modal"
                    >
                        {{ __('localization.invoice_invoicing_modal_add_payment_obligations_cancel') }}
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary waves-effect waves-float waves-light"
                        data-bs-dismiss="modal"
                    >
                        {{ __('localization.invoice_invoicing_modal_add_payment_obligations_add') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- модалка платіжне зобов‘язання -->
    <div
        class="modal fade"
        id="modalPaymentObligation"
        tabindex="-1"
        aria-labelledby="modalPaymentObligation"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-ld" style="max-width: 928px">
            <div class="modal-content p-5">
                <h2 class="mb-1 mt-0 text-center">
                    {{ __('localization.invoice_invoicing_modal_payment_obligation_title') }}
                    <span id="title-number-PO"></span>
                </h2>
                <p class="mb-2 text-center">
                    {{ __('localization.invoice_invoicing_modal_payment_obligation_edit_cost') }}
                </p>
                <div class="row">
                    <div class="col-sm-12 col-md-5 px-1 mt-0 pe-2 border-end">
                        <div style="padding-left: 6px">
                            <h4 class="fw-15">
                                {{ __('localization.invoice_invoicing_modal_payment_obligation_general_info') }}
                            </h4>
                        </div>
                        <div class="card-data-reverse-darker">
                            <div style="padding: 6px 0 6px 6px">
                                <p style="margin: 5px 0">
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_po_number') }}
                                </p>
                                <a
                                    class="m-0 fw-medium-c text-secondary text-decoration-underline"
                                    href="#"
                                >
                                    №
                                    <span id="number-info-PO">432423</span>
                                </a>
                            </div>
                            <div style="padding: 6px 0 6px 6px">
                                <p style="margin: 5px 0">
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_based_on_contract') }}
                                </p>
                                <a
                                    class="m-0 fw-medium-c text-secondary text-decoration-underline"
                                    href="#"
                                >
                                    №
                                    <span id="number-contract-info">432423</span>
                                </a>
                            </div>
                            <div style="padding: 6px 0 6px 6px">
                                <p style="margin: 5px 0">
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_based_on_service') }}
                                </p>
                                <a
                                    class="m-0 fw-medium-c text-secondary text-decoration-underline"
                                    href="#"
                                >
                                    №
                                    <span id="number-service-info">432423</span>
                                </a>
                            </div>
                            <div style="padding: 6px 0 6px 6px">
                                <p style="margin: 5px 0">
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_recipient') }}
                                </p>
                                <a
                                    class="m-0 fw-medium-c text-secondary text-decoration-underline"
                                    id="recipient-name-info"
                                    href="#"
                                >
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_example_name_company') }}
                                </a>
                            </div>
                            <div style="padding: 6px 0 6px 6px">
                                <p style="margin: 5px 0">
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_performer') }}
                                </p>
                                <a
                                    class="m-0 fw-medium-c text-secondary text-decoration-underline"
                                    id="performer-name-info"
                                    href="#"
                                >
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_example_name_company') }}
                                </a>
                            </div>
                            <div style="padding: 6px 0 6px 6px">
                                <p style="margin: 5px 0">
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_service_date') }}
                                </p>
                                <p class="m-0 fw-medium-c" id="date-invoice-info">01.05.2023</p>
                            </div>
                            <div style="padding: 6px 0 6px 6px">
                                <p style="margin: 5px 0">
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_amount') }}
                                </p>
                                <p class="m-0 fw-medium-c" id="date-invoice">
                                    <span id="cost-invoice-info">34000.00</span>
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_amount_unit') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-7 ps-2">
                        <!-- block додати коригування -->
                        <div id="block-start-cost" class="d-none h-100">
                            <h4 class="fw-15">
                                {{ __('localization.invoice_invoicing_modal_payment_obligation_adjustment') }}
                            </h4>
                            <div
                                class="p-4 d-flex flex-column align-items-center h-100 justify-content-center"
                            >
                                <p>
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_no_adjustment') }}
                                </p>
                                <button
                                    class="btn btn-outline-primary btn-sm"
                                    id="btn-add-correction"
                                >
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_add_adjustment') }}
                                </button>
                            </div>
                        </div>

                        <!-- block додати коригування вартості -->
                        <div id="block-add-correction-cost" class="d-none h-100">
                            <h4 class="fw-15 mb-2">
                                <a href="#" class="text-black" id="link-to-back-start-cost">
                                    <i
                                        data-feather="arrow-left"
                                        style="transform: scale(1.3)"
                                        class="me-25 cursor-pointer"
                                    ></i>
                                </a>
                                {{ __('localization.invoice_invoicing_modal_payment_obligation_add_adjustment') }}
                            </h4>
                            <div class="d-flex gap-2 mb-1">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="checkedWay"
                                        id="increaseCost"
                                        checked
                                    />
                                    <label class="form-check-label" for="increaseCost">
                                        {{ __('localization.invoice_invoicing_modal_payment_obligation_increase') }}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="checkedWay"
                                        id="reduceCost"
                                    />
                                    <label class="form-check-label" for="reduceCost">
                                        {{ __('localization.invoice_invoicing_modal_payment_obligation_reduce') }}
                                    </label>
                                </div>
                            </div>
                            <div class="mb-1" id="block-reason">
                                <label class="form-label" for="reason">
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_reason') }}
                                </label>
                                <select
                                    class="select2 form-select"
                                    id="reason"
                                    data-placeholder="{{ __('localization.invoice_invoicing_modal_payment_obligation_select_reason') }}"
                                >
                                    <option value=""></option>
                                    <option value="1">1</option>
                                </select>
                            </div>
                            <div class="mb-1 d-none" id="block-type-of-problem">
                                <label class="form-label" for="type-of-problem">
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_problem_type') }}
                                </label>
                                <select
                                    class="select2 form-select"
                                    id="type-of-problem"
                                    data-placeholder="{{ __('localization.invoice_invoicing_modal_payment_obligation_select_problem') }}"
                                >
                                    <option value=""></option>
                                    <option value="1">1</option>
                                </select>
                            </div>

                            <div class="row mb-1">
                                <div class="col-6">
                                    <label class="form-label" for="status">
                                        {{ __('localization.invoice_invoicing_modal_payment_obligation_status') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="status"
                                        data-placeholder="{{ __('localization.invoice_invoicing_modal_payment_obligation_select_status') }}"
                                    >
                                        <option value=""></option>
                                        <option value="1">
                                            {{ __('localization.invoice_invoicing_modal_payment_obligation_select_status_1') }}
                                        </option>
                                        <option value="2">
                                            {{ __('localization.invoice_invoicing_modal_payment_obligation_select_status_2') }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="location">
                                        {{ __('localization.invoice_invoicing_modal_payment_obligation_location') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="location"
                                        data-placeholder="{{ __('localization.invoice_invoicing_modal_payment_obligation_select_location') }}"
                                    >
                                        <option value=""></option>
                                        <option value="1">1</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-1" id="block-amountOfAdditionalCost">
                                <label class="form-label">
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_additional_cost') }}
                                </label>
                                <div class="input-group">
                                    <input
                                        type="number"
                                        class="form-control"
                                        placeholder="+1000.00"
                                        id="amountOfAdditionalCost"
                                    />
                                    <span class="input-group-text">
                                        {{ __('localization.invoice_invoicing_modal_payment_obligation_additional_cost_unit') }}
                                    </span>
                                </div>
                            </div>
                            <div class="mb-1 d-none" id="block-amountPenalty">
                                <label class="form-label">
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_penalty') }}
                                </label>
                                <div class="input-group">
                                    <input
                                        type="number"
                                        class="form-control"
                                        placeholder="-1000.00"
                                        id="amountPenalty"
                                    />
                                    <span class="input-group-text">
                                        {{ __('localization.invoice_invoicing_modal_payment_obligation_penalty_unit') }}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-1">
                                <label class="form-label" for="text-for-comment">
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_comment') }}
                                </label>
                                <textarea
                                    class="form-control"
                                    id="comment"
                                    rows="3"
                                    placeholder="{{ __('localization.invoice_invoicing_modal_payment_obligation_leave_comment') }}"
                                ></textarea>
                            </div>
                            <button
                                class="w-100 btn btn-outline-primary"
                                id="add-to-correction-list"
                            >
                                {{ __('localization.invoice_invoicing_modal_payment_obligation_add_correction') }}
                            </button>
                        </div>

                        <!-- block вибраних коригувань -->
                        <div id="block-selected-correction" class="h-100 d-none">
                            <div class="mb-1 d-flex justify-content-between">
                                <h4 class="fw-15 mb-0">
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_selected_correction') }}
                                </h4>
                                <a href="#" id="link-to-add-correction">
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_add_adjustment') }}
                                </a>
                            </div>
                            <div
                                style="height: 400px; overflow: scroll"
                                id="list-selected-correction"
                            ></div>
                            <div class="d-flex justify-content-between mt-1">
                                <p class="fw-medium-c fs-5 m-0 lh-1">
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_total_amount') }}
                                </p>
                                <p class="fw-medium-c fs-5 m-0 lh-1">
                                    <span id="item-amount">22000</span>
                                    {{ __('localization.invoice_invoicing_modal_payment_obligation_total_amount_unit') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 d-flex justify-content-end" id="btns-action-modal">
                        <button
                            class="btn btn-flat-secondary float-start mr-2"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        >
                            {{ __('localization.invoice_invoicing_modal_payment_obligation_cancel') }}
                        </button>
                        <button type="button" class="btn btn-primary" id="btn-save-PO" disabled>
                            {{ __('localization.invoice_invoicing_modal_payment_obligation_save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end -->
@endsection

@section('page-script')
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/pickers/form-pickers.js') }}"></script>

    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#selected-payment-obligations-table'));
        tableSetting($('#payment-obligations-table'));
    </script>

    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#selected-payment-obligations-table'));
        offCanvasByBorder($('#payment-obligations-table'));
    </script>
@endsection
