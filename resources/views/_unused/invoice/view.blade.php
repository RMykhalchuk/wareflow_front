@extends('layouts.admin')

@section('title', __('localization.invoice_view_title'))

@section('page-style')
    
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
    <script type="module" src="{{ asset('assets/js/entity/invoice/invoice-view.js') }}"></script>
    <script
        type="module"
        src="{{ asset('assets/js/grid/invoice/selected-payment-obligations-table.js') }}"
    ></script>
@endsection

@section('content')
    <div class="container-fluid px-2">
        <div class="d-flex justify-content-between js-breadcrumb-wrapper">
            <div class="pb-2">
                @include(
                    'panels.breadcrumb',
                    [
                        'options' => [
                            ['url' => '/document', 'name' => __('localization.invoice_view_breadcrumb_documents')],
                            ['url' => '/invoices/', 'name' => __('localization.invoice_view_breadcrumb_invoices')],
                            ['name' => __('localization.invoice_view_breadcrumb_view_invoice')],
                        ],
                    ]
                )
            </div>
            <div class="d-flex gap-2 align-items-center">
                <button
                    class="btn p-25 h-50"
                    id="jsPrintBtn"
                    title="{{ __('localization.invoice_view_print_tooltip') }}"
                >
                    <i data-feather="printer" style="cursor: pointer; transform: scale(1.2)"></i>
                </button>
                <button
                    class="btn p-25 h-50"
                    id="jsCopyBtn"
                    title="{{ __('localization.invoice_view_copy_tooltip') }}"
                >
                    <i data-feather="copy" style="cursor: pointer; transform: scale(1.2)"></i>
                </button>
                <div class="p-25">
                    <div class="btn-group">
                        <i
                            data-feather="more-vertical"
                            id="tn-details-header-dropdown"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            style="cursor: pointer; transform: scale(1.2)"
                        ></i>
                        <div class="dropdown-menu" aria-labelledby="tn-details-header-dropdown">
                            <a class="dropdown-item" href="#">
                                {{ __('localization.invoice_view_dropdown_action_1') }}
                            </a>
                            <a class="dropdown-item" href="#">
                                {{ __('localization.invoice_view_dropdown_action_2') }}
                            </a>
                            <a class="dropdown-item" href="#">
                                {{ __('localization.invoice_view_dropdown_action_3') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="jsCopyEventScope">
            <!-- контент -->
            <div class="row">
                <div class="col-12 col-xl-9" id="block-content-view">
                    <div class="card p-2 mb-1 pe-0">
                        <div class="px-0 d-flex gap-1">
                            <h4 class="card-title fw-bolder pt-0">
                                {{ __('localization.invoice_view_invoice_number') }}
                            </h4>

                            <!-- контейнер з статусами рахунку -->
                            <div>
                                <div
                                    class="d-none alert alert-secondary p-0"
                                    style="padding: 2px 10px !important"
                                    id="status-invoice-create"
                                >
                                    {{ __('localization.invoice_view_status_created') }}
                                </div>
                                <div
                                    class="d-none alert alert-primary p-0"
                                    style="padding: 2px 10px !important"
                                    id="status-invoice-sent-for-payment"
                                >
                                    {{ __('localization.invoice_view_status_sent_for_payment') }}
                                </div>
                                <div
                                    class="d-none alert alert-primary p-0"
                                    style="padding: 2px 10px !important"
                                    id="status-invoice-waiting-for-your-payment"
                                >
                                    {{ __('localization.invoice_view_status_waiting_for_your_payment') }}
                                </div>
                                <div
                                    class="d-none alert alert-success p-0"
                                    style="padding: 2px 10px !important"
                                    id="status-invoice-paid-by-contractor"
                                >
                                    {{ __('localization.invoice_view_status_paid_by_contractor') }}
                                </div>
                                <div
                                    class="d-none alert alert-success p-0"
                                    style="padding: 2px 10px !important"
                                    id="status-invoice-paid-by-you"
                                >
                                    {{ __('localization.invoice_view_status_paid_by_you') }}
                                </div>
                                <div
                                    class="d-none alert alert-danger p-0"
                                    style="padding: 2px 10px !important"
                                    id="status-invoice-rejected-by-you"
                                >
                                    {{ __('localization.invoice_view_status_rejected_by_you') }}
                                </div>
                                <div
                                    class="d-none alert alert-danger p-0"
                                    style="padding: 2px 10px !important"
                                    id="status-invoice-rejected-by-contractor"
                                >
                                    {{ __('localization.invoice_view_status_rejected_by_contractor') }}
                                </div>
                            </div>
                        </div>

                        <div class="card-body px-0 py-0 row">
                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="d-flex">
                                    <p class="f-15 fw-4" style="width: 180px">
                                        {{ __('localization.invoice_view_invoice_type') }}
                                    </p>
                                    <p class="f-15 fw-5" style="width: 180px">
                                        {{ __('localization.invoice_view_invoice_type_outgoing') }}
                                    </p>
                                </div>
                                <div class="d-flex">
                                    <p class="f-15 fw-4" style="width: 180px">
                                        {{ __('localization.invoice_view_supplier_company') }}
                                    </p>
                                    <a
                                        class="f-15 fw-5 text-decoration-underline"
                                        href="#"
                                        style="color: #6f6b7d; width: 180px"
                                    >
                                        {{ __('localization.invoice_view_supplier_company_name') }}
                                    </a>
                                </div>
                                <div class="d-flex">
                                    <p class="f-15 fw-4" style="width: 180px">
                                        {{ __('localization.invoice_view_supplier_person') }}
                                    </p>
                                    <a
                                        class="f-15 fw-5 text-decoration-underline"
                                        href="#"
                                        style="color: #6f6b7d; width: 180px"
                                    >
                                        {{ __('localization.invoice_view_supplier_person_name') }}
                                    </a>
                                </div>
                                <div class="d-flex">
                                    <p class="f-15 fw-4" style="width: 180px">
                                        {{ __('localization.invoice_view_recipient_company') }}
                                    </p>
                                    <a
                                        class="f-15 fw-5 text-decoration-underline"
                                        href="#"
                                        style="color: #6f6b7d; width: 180px"
                                    >
                                        {{ __('localization.invoice_view_recipient_company_name') }}
                                    </a>
                                </div>
                                <div class="d-flex">
                                    <p class="f-15 fw-4" style="width: 180px">
                                        {{ __('localization.invoice_view_recipient_person') }}
                                    </p>
                                    <a
                                        class="f-15 fw-5 text-decoration-underline"
                                        href="#"
                                        style="color: #6f6b7d; width: 180px"
                                    >
                                        {{ __('localization.invoice_view_recipient_person_name') }}
                                    </a>
                                </div>
                                <div class="d-flex">
                                    <p class="f-15 fw-4" style="width: 180px">
                                        {{ __('localization.invoice_view_based_on_contract') }}
                                    </p>
                                    <a
                                        class="f-15 fw-5 text-decoration-underline"
                                        href="#"
                                        style="color: #6f6b7d; width: 180px"
                                    >
                                        {{ __('localization.invoice_view_contract_number') }}
                                    </a>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="d-flex">
                                    <p class="f-15 fw-4" style="width: 180px">
                                        {{ __('localization.invoice_view_invoice_date') }}
                                    </p>
                                    <p class="f-15 fw-5" style="width: 180px">
                                        {{ __('localization.invoice_view_invoice_date_value') }}
                                    </p>
                                </div>
                                <div class="d-flex">
                                    <p class="f-15 fw-4" style="width: 180px">
                                        {{ __('localization.invoice_view_amount_without_vat') }}
                                    </p>
                                    <p class="f-15 fw-5" style="width: 180px">
                                        {{ __('localization.invoice_view_amount_without_vat_value') }}
                                    </p>
                                </div>
                                <div class="d-flex">
                                    <p class="f-15 fw-4" style="width: 180px">
                                        {{ __('localization.invoice_view_amount_with_vat') }}
                                    </p>
                                    <p class="f-15 fw-5" style="width: 180px">
                                        {{ __('localization.invoice_view_amount_with_vat_value') }}
                                    </p>
                                </div>
                                <div class="d-flex">
                                    <p class="f-15 fw-4" style="width: 180px">
                                        {{ __('localization.invoice_view_payment_terms') }}
                                    </p>
                                    <p class="f-15 fw-5" style="width: 180px">
                                        {{ __('localization.invoice_view_payment_terms_value') }}
                                    </p>
                                </div>
                                <div class="d-flex">
                                    <p class="f-15 fw-4" style="width: 180px">
                                        {{ __('localization.invoice_view_payment_date') }}
                                    </p>
                                    <p class="f-15 fw-5" style="width: 180px">
                                        {{ __('localization.invoice_view_payment_date_value') }}
                                    </p>
                                </div>
                                <div class="d-flex">
                                    <p class="f-15 fw-4" style="width: 180px">
                                        {!! __('localization.invoice_view_payment_receipt') !!}
                                    </p>
                                    <a
                                        class="f-15 fw-5 text-secondary"
                                        style="width: 180px"
                                        id="link-receiptForPayment"
                                    >
                                        {{ __('localization.invoice_view_payment_receipt_value') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-3" id="block-actionsDocuments">
                    <div class="card p-2 mb-1 mx-auto" style="max-width: 328px">
                        <h4 class="card-title fw-bolder pt-0">
                            {{ __('localization.invoice_view_actions_with_documents') }}
                        </h4>
                        <a class="d-none btn btn-primary mb-1" id="btn-send-to-payment" href="#">
                            {{ __('localization.invoice_view_send_to_payment') }}
                        </a>
                        <a
                            class="d-none btn btn-primary mb-1"
                            id="btn-payment"
                            href="#"
                            data-bs-toggle="modal"
                            data-bs-target="#receiptForPayment"
                        >
                            {{ __('localization.invoice_view_pay') }}
                        </a>
                        <a
                            class="d-none btn btn-primary mb-1"
                            id="btn-return-from-payment"
                            href="#"
                        >
                            {{ __('localization.invoice_view_return_from_payment') }}
                        </a>
                        <a class="d-none btn btn-outline-danger" id="btn-reject" href="#">
                            {{ __('localization.invoice_view_reject') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- таблиця  -->
            <div class="card">
                <div class="card-header border-bottom row mx-0 gap-1">
                    <h4 class="card-title col-auto fw-bolder">
                        {{ __('localization.invoice_view_payment_obligations') }}
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
                            {{ __('localization.invoice_view_no_payment_obligations') }}
                        </div>
                        <div class="text-center mt-2">
                            <a class="btn btn-primary" href="#">
                                {{ __('localization.invoice_view_add_payment_obligation') }}
                            </a>
                        </div>
                    </div>
                @endif
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
                    {{ __('localization.invoice_view_modal_payment_obligation_title') }}
                    <span id="title-number-PO">432423</span>
                </h2>
                <p class="mb-2 text-center">
                    {{ __('localization.invoice_view_modal_payment_obligation_edit_cost_text') }}
                </p>
                <div class="row">
                    <div class="col-sm-12 col-md-5 px-1 mt-0 pe-2 border-end">
                        <div style="padding-left: 6px">
                            <h4 class="fw-15">
                                {{ __('localization.invoice_view_modal_payment_obligation_general_info_title') }}
                            </h4>
                        </div>
                        <div class="card-data-reverse-darker">
                            <div style="padding: 6px 0 6px 6px">
                                <p style="margin: 5px 0">
                                    {{ __('localization.invoice_view_modal_payment_obligation_label_invoice') }}
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
                                    {{ __('localization.invoice_view_modal_payment_obligation_label_contract') }}
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
                                    {{ __('localization.invoice_view_modal_payment_obligation_label_service') }}
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
                                    {{ __('localization.invoice_view_modal_payment_obligation_label_recipient') }}
                                </p>
                                <a
                                    class="m-0 fw-medium-c text-secondary text-decoration-underline"
                                    id="recipient-name-info"
                                    href="#"
                                >
                                    ТОВ КОндитирська
                                </a>
                            </div>
                            <div style="padding: 6px 0 6px 6px">
                                <p style="margin: 5px 0">
                                    {{ __('localization.invoice_view_modal_payment_obligation_label_performer') }}
                                </p>
                                <a
                                    class="m-0 fw-medium-c text-secondary text-decoration-underline"
                                    id="performer-name-info"
                                    href="#"
                                >
                                    ТОВ КОндитирська
                                </a>
                            </div>
                            <div style="padding: 6px 0 6px 6px">
                                <p style="margin: 5px 0">
                                    {{ __('localization.invoice_view_modal_payment_obligation_label_service_date') }}
                                </p>
                                <p class="m-0 fw-medium-c" id="date-invoice-info">01.05.2023</p>
                            </div>
                            <div style="padding: 6px 0 6px 6px">
                                <p style="margin: 5px 0">
                                    {{ __('localization.invoice_view_modal_payment_obligation_label_amount') }}
                                </p>
                                <p class="m-0 fw-medium-c" id="date-invoice">
                                    <span id="cost-invoice-info">34000.00</span>
                                    {{ __('localization.invoice_view_modal_payment_obligation_label_amount_unit') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-7 ps-2">
                        <!-- block додати коригування -->
                        <div id="block-start-cost" class="d-none h-100">
                            <h4 class="fw-15">
                                {{ __('localization.invoice_view_modal_payment_obligation_correction_title') }}
                            </h4>
                            <div
                                class="p-4 d-flex flex-column align-items-center h-100 justify-content-center"
                            >
                                <p>
                                    {{ __('localization.invoice_view_modal_payment_obligation_add_correction_text') }}
                                </p>
                                <button
                                    class="btn btn-outline-primary btn-sm"
                                    id="btn-add-correction"
                                >
                                    {{ __('localization.invoice_view_modal_payment_obligation_btn_add_correction') }}
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
                                {{ __('localization.invoice_view_modal_payment_obligation_add_correction_text_btn') }}
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
                                        {{ __('localization.invoice_view_modal_payment_obligation_radio_increase') }}
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
                                        {{ __('localization.invoice_view_modal_payment_obligation_radio_reduce') }}
                                    </label>
                                </div>
                            </div>
                            <div class="mb-1" id="block-reason">
                                <label class="form-label" for="reason">
                                    {{ __('localization.invoice_view_modal_payment_obligation_label_correction_reason') }}
                                </label>
                                <select
                                    class="select2 form-select"
                                    id="reason"
                                    data-placeholder="{{ __('localization.invoice_view_modal_payment_obligation_placeholder_select_reason') }}"
                                >
                                    <option value=""></option>
                                    <option value="1">1</option>
                                </select>
                            </div>
                            <div class="mb-1 d-none" id="block-type-of-problem">
                                <label class="form-label" for="type-of-problem">
                                    {{ __('localization.invoice_view_modal_payment_obligation_label_problem_type') }}
                                </label>
                                <select
                                    class="select2 form-select"
                                    id="type-of-problem"
                                    data-placeholder="{{ __('localization.invoice_view_modal_payment_obligation_placeholder_select_problem_type') }}"
                                >
                                    <option value=""></option>
                                    <option value="1">1</option>
                                </select>
                            </div>

                            <div class="row mb-1">
                                <div class="col-6">
                                    <label class="form-label" for="status">
                                        {{ __('localization.invoice_view_modal_payment_obligation_label_status') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="status"
                                        data-placeholder="{{ __('localization.invoice_view_modal_payment_obligation_placeholder_select_status') }}"
                                    >
                                        <option value=""></option>
                                        <option value="1">
                                            {{ __('localization.invoice_view_modal_payment_obligation_select_status_1') }}
                                        </option>
                                        <option value="2">
                                            {{ __('localization.invoice_view_modal_payment_obligation_select_status_2') }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="location">
                                        {{ __('localization.invoice_view_modal_payment_obligation_label_location') }}
                                    </label>
                                    <select
                                        class="select2 form-select"
                                        id="location"
                                        data-placeholder="{{ __('localization.invoice_view_modal_payment_obligation_placeholder_select_location') }}"
                                    >
                                        <option value=""></option>
                                        <option value="1">1</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-1" id="block-amountOfAdditionalCost">
                                <label class="form-label">
                                    {{ __('localization.invoice_view_modal_payment_obligation_label_additional_cost') }}
                                </label>
                                <div class="input-group">
                                    <input
                                        type="number"
                                        class="form-control"
                                        placeholder="+1000.00"
                                        id="amountOfAdditionalCost"
                                    />
                                    <span class="input-group-text">
                                        {{ __('localization.invoice_view_modal_payment_obligation_label_additional_cost_unit') }}
                                    </span>
                                </div>
                            </div>
                            <div class="mb-1 d-none" id="block-amountPenalty">
                                <label class="form-label">
                                    {{ __('localization.invoice_view_modal_payment_obligation_label_penalty_amount') }}
                                </label>
                                <div class="input-group">
                                    <input
                                        type="number"
                                        class="form-control"
                                        placeholder="-1000.00"
                                        id="amountPenalty"
                                    />
                                    <span class="input-group-text">
                                        {{ __('localization.invoice_view_modal_payment_obligation_label_penalty_amount_unit') }}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-1">
                                <label class="form-label" for="text-for-comment">
                                    {{ __('localization.invoice_view_modal_payment_obligation_label_comment') }}
                                </label>
                                <textarea
                                    class="form-control"
                                    id="comment"
                                    rows="3"
                                    placeholder="{{ __('localization.invoice_view_modal_payment_obligation_placeholder_comment') }}"
                                ></textarea>
                            </div>
                            <button
                                class="w-100 btn btn-outline-primary"
                                id="add-to-correction-list"
                            >
                                {{ __('localization.invoice_view_modal_payment_obligation_btn_add_correction') }}
                            </button>
                        </div>

                        <!-- block вибраних коригувань -->
                        <div id="block-selected-correction" class="h-100 d-none">
                            <div class="mb-1 d-flex justify-content-between">
                                <h4 class="fw-15 mb-0">
                                    {{ __('localization.invoice_view_modal_payment_obligation_correction_title') }}
                                </h4>
                                <a href="#" id="link-to-add-correction">
                                    {{ __('localization.invoice_view_modal_payment_obligation_btn_add_correction') }}
                                </a>
                            </div>
                            <div
                                style="max-height: 400px; overflow-y: auto"
                                id="list-selected-correction"
                            ></div>
                            <div class="d-flex justify-content-between mt-1">
                                <p class="fw-medium-c fs-5 m-0 lh-1">
                                    {{ __('localization.invoice_view_modal_payment_obligation_label_amount_with_correction') }}
                                </p>
                                <p class="fw-medium-c fs-5 m-0 lh-1">
                                    <span id="item-amount">22000</span>
                                    {{ __('localization.invoice_view_modal_payment_obligation_label_amount_with_correction_unit') }}
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
                            {{ __('localization.invoice_view_modal_payment_obligation_btn_cancel') }}
                        </button>
                        <button type="button" class="btn btn-primary" id="btn-save-PO" disabled>
                            {{ __('localization.invoice_view_modal_payment_obligation_btn_save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- модал квитанція про оплату -->
    <div
        class="modal fade"
        id="receiptForPayment"
        tabindex="-1"
        aria-labelledby="receiptForPayment"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 681px">
            <div class="modal-content">
                <div class="p-4">
                    <h2 class="mb-0 mt-0 text-center">
                        {{ __('localization.invoice_view_modal_receipt_for_payment_title') }}
                    </h2>
                    <div class="p-2 pb-1">
                        <p class="mb-0 text-center">
                            {{ __('localization.invoice_view_modal_receipt_for_payment_add_receipt') }}
                        </p>
                    </div>
                    <form class="d-flex flex-column" method="" action="#">
                        <div class="my-2">
                            <input
                                type="file"
                                class="form-control"
                                id="fileInput-receiptForPayment"
                            />
                        </div>
                        <div class="d-flex justify-content-end">
                            <a
                                class="btn btn-flat-secondary float-start mr-2"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            >
                                {{ __('localization.invoice_view_modal_receipt_for_payment_cancel') }}
                            </a>
                            <button type="button" class="btn btn-primary" id="btn-paid">
                                {{ __('localization.invoice_view_modal_receipt_for_payment_paid') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#selected-payment-obligations-table'));
    </script>

    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#selected-payment-obligations-table'));
    </script>

    <script type="module">
        import { setupPrintButton } from '{{ asset('assets/js/utils/print-btn.js') }}';

        setupPrintButton('jsPrintBtn', 'jsCopyEventScope');
    </script>

    <script type="module">
        import { setupCopyButton } from '{{ asset('assets/js/utils/copy-btn.js') }}';

        setupCopyButton('jsCopyBtn', 'jsCopyEventScope');
    </script>
@endsection
