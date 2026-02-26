@php
    use App\Enums\Contract\ContractStatus;
    use Carbon\Carbon;
@endphp

@extends('layouts.admin')
@section('title', __('localization.contract_view_title'))

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
    <script src="{{ asset('assets/js/utils/loader-for-tabs.js') }}"></script>

    <script type="text/javascript">
        // Ініціалізуємо таби
        $('#tabs').jqxTabs({
            width: '100%',
            height: '100%',
        });
    </script>
@endsection

@section('content')
    <div class="mx-2 px-0">
        <!-- Навігація з кнопками та діями головними -->
        <div class="d-flex justify-content-between align-items-center">
            <div class="">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-slash">
                        <li class="breadcrumb-item">
                            <a href="/contracts" class="text-secondary">
                                {{ __('localization.contract_view_breadcrumb_action_contracts') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item fw-bolder active" aria-current="page">
                            {{ __('localization.contract_view_breadcrumb_action_view_contract', ['side' => $side === 'вхідного' ? __('localization.contract_view_breadcrumb_action_contract_type_in') : __('localization.contract_view_breadcrumb_action_contract_type_out'), 'id' => $contract->id]) }}
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="contract-actions d-flex align-items-center gap-2">
                {{-- <div class=" mr-1" id="bnt-action-printer"><i data-feather='printer' --}}
                {{-- style="cursor: pointer; transform: scale(1.2);"></i> --}}
                {{-- </div> --}}

                @if ($contract->status === ContractStatus::CREATED)
                    <div class="mr-1" id="bnt-action-edit">
                        <a class="link-secondary" href="/contracts/{{ $contract->id }}/edit">
                            <i
                                data-feather="edit"
                                style="cursor: pointer; transform: scale(1.2)"
                            ></i>
                        </a>
                    </div>

                    <div
                        id="bnt-action-delete"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteContractModal"
                    >
                        <a class="text-secondary" href="#">
                            <i
                                data-feather="trash-2"
                                style="cursor: pointer; transform: scale(1.2)"
                            ></i>
                        </a>
                    </div>
                @endif

                <div>
                    <div class="btn-group d-flex gap-1">
                        @if ($contract->status == ContractStatus::CREATED && $userSide == 1)
                            <a class="btn btn-primary rounded" id="btn-send-for-review" href="#">
                                {{ __('localization.contract_view_breadcrumb_action_send_for_review') }}
                            </a>
                        @endif

                        @if ($contract->status == ContractStatus::PENDING_SIGN && $userSide == 1)
                            <a
                                class="btn btn-primary rounded"
                                disabled
                                id="btn-second-sign"
                                href="#"
                            >
                                {{ __('localization.contract_view_breadcrumb_action_second_sign') }}
                            </a>
                        @endif

                        @if ($contract->status == ContractStatus::PENDING_SIGN && $userSide == 0)
                            <a class="btn btn-primary rounded" id="btn-reject-sign" href="#">
                                {{ __('localization.contract_view_breadcrumb_action_reject_sign') }}
                            </a>
                        @endif

                        @if ($contract->status == ContractStatus::SIGNED_ALL)
                            <a
                                class="btn btn-outline-danger rounded"
                                href="#"
                                data-bs-toggle="modal"
                                data-bs-target="#contractCancelledModal"
                            >
                                {{ __('localization.contract_view_breadcrumb_action_terminate_contract') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Контент -->
        <div class="row mx-0 my-2">
            <div class="col-12 col-md-12 col-lg-5 pe-0 pe-md-1 ps-0">
                <div class="row" style="">
                    <div class="col-lg-12 col-md-6 px-1 mt-0">
                        <!-- Блок договір -->
                        <div class="card p-2 mb-1">
                            <div class="align-items-center px-0 d-flex gap-1 mb-1">
                                <h4 class="card-title fw-bolder mb-0 pt-0">
                                    {{ __('localization.contract_view_contract_info_contract_title', ['id' => $contract->id]) }}
                                </h4>
                                <!-- Контейнер з статусами договору -->
                                <div class="">
                                    @if ($contract->status == ContractStatus::CREATED)
                                        <div
                                            class="alert alert-secondary fw-bolder p-0"
                                            style="padding: 2px 10px !important"
                                            id="status-contract-create"
                                        >
                                            {{ __('localization.contract_view_contract_info_status_created') }}
                                        </div>
                                    @endif

                                    @if ($contract->status == ContractStatus::PENDING_SIGN && $userSide == 0)
                                        <div
                                            class="alert alert-primary fw-bolder p-0"
                                            style="padding: 2px 10px !important"
                                            id="status-contract-waiting-for-your-sign"
                                        >
                                            {{ __('localization.contract_view_contract_info_status_waiting_for_sign') }}
                                        </div>
                                    @endif

                                    @if ($contract->status == ContractStatus::PENDING_SIGN && $userSide == 1)
                                        <div
                                            class="alert alert-primary fw-bolder p-0"
                                            style="padding: 2px 10px !important"
                                            id="status-contract-waiting-for-your-sign"
                                        >
                                            {{ __('localization.contract_view_contract_info_status_waiting_for_sign_side_1') }}
                                        </div>
                                    @endif

                                    @if ($contract->status == ContractStatus::SIGNED_ALL)
                                        <div
                                            class="alert alert-success fw-bolder p-0"
                                            style="padding: 2px 10px !important"
                                            id="status-contract-signed-by-all"
                                        >
                                            {{ __('localization.contract_view_contract_info_status_signed_by_all') }}
                                        </div>
                                    @endif

                                    @if ($contract->status == ContractStatus::TERMINATED)
                                        <div
                                            class="alert alert-secondary fw-bolder p-0"
                                            style="
                                                padding: 2px 10px !important;
                                                color: #4b4b4b !important;
                                            "
                                            id="status-contract-broken"
                                        >
                                            {{ __('localization.contract_view_contract_info_status_terminated') }}
                                        </div>
                                    @endif

                                    @if ($contract->status == ContractStatus::DECLINE)
                                        <div
                                            class="alert alert-danger fw-bolder p-0"
                                            style="padding: 2px 10px !important"
                                            id="status-contract-rejected-by-you"
                                        >
                                            {{ __('localization.contract_view_contract_info_status_declined_by_you') }}
                                        </div>
                                    @endif

                                    @if ($contract->status == ContractStatus::DECLINE_CONTRACTOR)
                                        <div
                                            class="alert alert-danger fw-bolder p-0"
                                            style="padding: 2px 10px !important"
                                            id="status-contract-rejected-by-contractor"
                                        >
                                            {{ __('localization.contract_view_contract_info_status_declined_by_contractor') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body px-0 py-0 d-flex flex-column">
                                <div class="d-flex row mx-0">
                                    <p class="f-15 fw-4 col-4 ps-0">
                                        {{ __('localization.contract_view_contract_info_contract_label') }}
                                    </p>
                                    <p class="f-15 fw-5 col-8">
                                        @switch($typeName ?? null)
                                            @case('Договір на торгові послуги')
                                                {{ __('localization.contract_view_contract_info_contract_name_1') }}

                                                @break
                                            @case('Договір на складські послуги')
                                                {{ __('localization.contract_view_contract_info_contract_name_2') }}

                                                @break
                                            @case('Договір на транспортні послуги')
                                                {{ __('localization.contract_view_contract_info_contract_name_3') }}

                                                @break
                                            @default
                                                {{ $typeName }}
                                        @endswitch
                                    </p>
                                </div>
                                <div class="d-flex row mx-0">
                                    <p class="f-15 fw-4 col-4 ps-0">
                                        {{ __('localization.contract_view_contract_info_contract_type_label') }}
                                    </p>
                                    <p class="f-15 fw-5 col-8">
                                        @switch($sideName ?? null)
                                            @case('Вхідний')
                                                {{ __('localization.contract_view_contract_info_contract_type_in') }}

                                                @break
                                            @case('Вихідний')
                                                {{ __('localization.contract_view_contract_info_contract_type_out') }}

                                                @break
                                            @default
                                                {{ $sideName }}
                                        @endswitch
                                    </p>
                                </div>
                                <div class="d-flex row mx-0">
                                    <p class="f-15 fw-4 col-4 ps-0">
                                        {{ __('localization.contract_view_contract_info_from_side_label') }}
                                    </p>
                                    <p class="f-15 fw-5 col-8">
                                        @switch($roleName ?? null)
                                            @case('Замовник')
                                                {{ __('localization.contract_view_contract_info_from_side_customer') }}

                                                @break
                                            @case('Постачальник')
                                                {{ __('localization.contract_view_contract_info_from_side_supplier') }}

                                                @break
                                            @default
                                                {{ $roleName }}
                                        @endswitch
                                    </p>
                                </div>
                                <div class="d-flex row mx-0">
                                    <p class="f-15 fw-4 col-4 ps-0">
                                        {{ __('localization.contract_view_contract_info_creation_date_label') }}
                                    </p>
                                    <p class="f-15 fw-5 col-8">
                                        {{ Carbon::parse($contract->created_at)->format('d.m.Y') }}
                                    </p>
                                </div>
                                <div class="d-flex row mx-0">
                                    <p class="f-15 fw-4 col-4 ps-0">
                                        {{ __('localization.contract_view_contract_info_your_company_label') }}
                                    </p>
                                    <a
                                        class="f-15 fw-5 col-8 text-decoration-underline"
                                        href="/companies/{{ $contract->company->id }}"
                                        style="color: #6f6b7d"
                                    >
                                        {{ $contract->company->name }}
                                    </a>
                                </div>
                                <div class="d-flex row mx-0">
                                    <p class="f-15 fw-4 col-4 ps-0">
                                        {{ __('localization.contract_view_contract_info_counterparty_label') }}
                                    </p>
                                    <a
                                        class="f-15 fw-5 col-8 text-decoration-underline"
                                        href="/companies/{{ $contract->counterparty->id }}"
                                        style="color: #6f6b7d"
                                    >
                                        {{ $contract->counterparty->name }}
                                    </a>
                                </div>
                                <div class="d-flex row mx-0">
                                    <p class="f-15 fw-4 col-4 ps-0">
                                        {{ __('localization.contract_view_contract_info_term_label') }}
                                    </p>
                                    <p class="f-15 fw-5 col-8">
                                        {{ __('localization.contract_view_contract_info_term_label_to') }}
                                        {{ Carbon::parse($contract->expired_at)->format('d.m.Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-6 pe-md-0 pe-lg-1">
                        <!-- Підписання та коментарі два блоки -->
                        <div class="card p-2 mb-1 pt-0">
                            <div class="card-header px-0">
                                <h5 class="card-title fw-8 pt-0 f-15 fw-bolder">
                                    {{ __('localization.contract_view_contract_info_signing_title') }}
                                </h5>
                            </div>
                            <div class="card-body px-0 py-0">
                                <div class="d-flex">
                                    <p class="f-15 fw-4" style="width: 170px">
                                        {{ __('localization.contract_view_contract_info_comment_your_company_label') }}
                                    </p>
                                    <p class="f-15 fw-5 text-truncate">
                                        {{ $contract->signed_at ?? '-' }}
                                    </p>
                                </div>
                                <div class="d-flex">
                                    <p class="f-15 fw-4" style="width: 170px">
                                        {{ __('localization.contract_view_contract_info_your_counterparty_label') }}
                                    </p>
                                    <p class="f-15 fw-5">
                                        {{ $contract->signed_at_counterparty ?? '-' }}
                                    </p>
                                </div>

                                {{-- Todo доробити розірвання договору перегляд(додати поля в бд і т.д) --}}
                                <div class="d-flex" id="block-for-broken-info">
                                    <p class="f-15 fw-4" style="width: 170px">
                                        {{ __('localization.contract_view_contract_info_termination_label') }}
                                    </p>
                                    <div>
                                        <a
                                            class="f-15 fw-5 text-decoration-underline d-flex"
                                            href="#"
                                            style="color: #6f6b7d"
                                        >
                                            ТОВ “КОМПАНІЯ 1”
                                        </a>
                                        <p class="f-15 fw-5">
                                            {{ __('localization.contract_view_contract_info_termination_label_to') }}
                                            {{ Carbon::parse($contract->expired_at)->format('d.m.Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card p-2 mb-1">
                            <div
                                class="card-header py-1 px-0 d-flex align-items-center justify-content-between"
                            >
                                <h5 class="card-title fw-8 pt-0 f-15 fw-bolder">
                                    {{ __('localization.contract_view_contract_info_comments_title') }}
                                    <span id="comment-count">
                                        {{ count($contract->comments) }}
                                    </span>
                                </h5>
                                <button
                                    class="btn btn-outline-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addCommentModal"
                                >
                                    {{ __('localization.contract_view_contract_info_add_comment_button') }}
                                </button>
                            </div>
                            <!-- контейнер для коментів -->
                            <div class="card-body px-0 py-0" id="comments-container">
                                @foreach ($contract->comments as $comment)
                                    <div>
                                        <p class="f-15 fw-5 row mx-0">
                                            <span class="col-8 ps-0">
                                                {{ $comment->company->name }}
                                            </span>
                                            <span class="f-15 col-4 text-secondary pe-0 text-end">
                                                {{ Carbon::parse($comment->created_at)->format('d.m.Y') }}
                                                {{ __('localization.contract_view_contract_info_comments_at_label') }}
                                                {{ Carbon::parse($comment->created_at)->format('H:i:s') }}
                                            </span>
                                        </p>
                                        <p>{{ $comment->comment }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 pe-0 ps-lg-1 ps-0 flex-grow-1">
                <div class="col-xl-12 col-lg-12 h-100">
                    <div class="card h-100">
                        <div id="jqxLoader"></div>

                        <div class="ps-2 pt-2">
                            <h4 class="fw-bolder">
                                {{ __('localization.contract_view_regulations_info_regulations_title') }}
                            </h4>
                        </div>
                        <div class="contract-view-tables h-100">
                            <!-- Basic Tabs starts -->
                            <div id="tabs" class="invisible">
                                <ul class="d-flex">
                                    <li>
                                        {{ __('localization.contract_view_regulations_info_from_your_side') }}
                                    </li>
                                    <li>
                                        {{ __('localization.contract_view_regulations_info_from_counterparty_side') }}
                                    </li>
                                </ul>

                                <div>
                                    <!-- Контент в першій табі вашої сторони-->
                                    @if ($ownRegulation)
                                        <!-- Для магазинів табу вашої сторони -->
                                        <div class="d-none list-regulations-for-market">
                                            <div class="p-2 pb-0">
                                                <h2 class="f-15 fw-bolder">
                                                    <h2 class="f-15 fw-bolder">
                                                        {{ $ownRegulation->name }}
                                                        ({{ $contract->role === 1 ? __('localization.contract_view_regulations_info_tab_1_customer') : __('localization.contract_view_regulations_info_tab_1_supplier') }}
                                                        {{ __('localization.contract_view_regulations_info_tab_1_text_title') }}
                                                        )
                                                    </h2>
                                                </h2>
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
                                                            {{ __('localization.contract_view_regulations_info_tab_1_regulation_name_and_parent') }}
                                                        </button>
                                                    </h2>
                                                    <div
                                                        id="accordionOne"
                                                        class="accordion-collapse collapse show"
                                                        aria-labelledby="headingOne"
                                                        data-bs-parent="#accordionExample"
                                                    >
                                                        <div class="accordion-body px-2">
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_regulation_name') }}
                                                                </p>
                                                                <p class="f-15 m-0 fw-bold">
                                                                    {{ $ownRegulation->name }}
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_parent_regulation') }}
                                                                </p>

                                                                @if ($ownRegulation->parent)
                                                                    <a
                                                                        href="#"
                                                                        class="f-15 fw-bold"
                                                                    >
                                                                        {{ $ownRegulation->parent->name }}
                                                                    </a>
                                                                @else
                                                                    <div class="f-15 fw-bold">
                                                                        -
                                                                    </div>
                                                                @endif
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
                                                            {{ __('localization.contract_view_regulations_info_tab_1_regulation_settings') }}
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
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0 fw-bold"
                                                                    style="color: #5d596c"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_return_goods') }}
                                                                </p>
                                                                <p class="f-15 m-0"></p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_pallet_type') }}
                                                                </p>
                                                                <p class="f-15 fw-bold">
                                                                    @switch($ownRegulation['settings']['typePalet'] ?? null)
                                                                        @case('Європалета 120х80см')
                                                                            {{ __('localization.contract_regulations_pallet_type_1') }}

                                                                            @break
                                                                        @case('Американська палета 120х100см')
                                                                            {{ __('localization.contract_regulations_pallet_type_2') }}

                                                                            @break
                                                                        @case('Напівпалета 60х80см')
                                                                            {{ __('localization.contract_regulations_pallet_type_3') }}

                                                                            @break
                                                                        @case('Фінська палета')
                                                                            {{ __('localization.contract_regulations_pallet_type_4') }}

                                                                            @break
                                                                        @default
                                                                            {{ $ownRegulation['settings']['typePalet'] }}
                                                                    @endswitch
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_pallet_height') }}
                                                                </p>
                                                                <p class="f-15 fw-bold">
                                                                    {{ $ownRegulation['settings']['heightPalet'] }}
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_pallet_height_measure_unit') }}
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_remaining_term') }}
                                                                </p>
                                                                <p class="f-15 fw-bold">
                                                                    {{ $ownRegulation['settings']['overheadTerm'] }}
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_remaining_term_unit') }}
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_pallet_sheet') }}
                                                                </p>
                                                                <p class="f-15 fw-bold">
                                                                    {{ $ownRegulation['settings']['palletSheet'] ? __('localization.contract_view_regulations_info_tab_1_pallet_sheet_text_true') : __('localization.contract_view_regulations_info_tab_1_pallet_sheet_text_false') }}
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_allow_prefab_pallets') }}
                                                                </p>
                                                                <p class="f-15 fw-bold">
                                                                    {{ $ownRegulation['settings']['allowPrefabPallets'] ? __('localization.contract_view_regulations_info_tab_1_allow_prefab_pallets_text_true') : __('localization.contract_view_regulations_info_tab_1_allow_prefab_pallets_text_false') }}
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_allow_sandwich_pallet') }}
                                                                </p>
                                                                <p class="f-15 fw-bold">
                                                                    {{ $ownRegulation['settings']['allowSandwichPallet'] ? __('localization.contract_view_regulations_info_tab_1_allow_sandwich_pallet_text_true') : __('localization.contract_view_regulations_info_tab_1_allow_sandwich_pallet_text_false') }}
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_stickering') }}
                                                                </p>
                                                                <p class="f-15 fw-bold">
                                                                    {{ $ownRegulation['settings']['stickering'] ? __('localization.contract_view_regulations_info_tab_1_stickering_text_true') : __('localization.contract_view_regulations_info_tab_1_stickering_text_false') }}
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_allow_holding') }}
                                                                </p>
                                                                <p class="f-15 fw-bold">
                                                                    {{ $ownRegulation['settings']['allowHolding'] ? __('localization.contract_view_regulations_info_tab_1_allow_holding_text_true') : __('localization.contract_view_regulations_info_tab_1_allow_holding_text_false') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Торгові регламенти -->
                                        <div id="retail-list-regulations" class="d-none h-100">
                                            <div class="p-2 pb-0 mb-2">
                                                <h2 class="f-15 fw-bolder mb-1">
                                                    {{ $contract->type_id === 0 ? __('localization.contract_view_regulations_info_tab_1_retail_list_trade') : '' }}
                                                    {{ $contract->type_id === 1 ? __('localization.contract_view_regulations_info_tab_1_retail_list_warehouse') : '' }}
                                                    {{ $contract->type_id === 2 ? __('localization.contract_view_regulations_info_tab_1_retail_list_transport') : '' }}
                                                    {{ __('localization.contract_view_regulations_info_tab_1_retail_list_regulations') }}
                                                    ({{ $contract->role === 1 ? __('localization.contract_view_regulations_info_tab_1_retail_list_customer') : __('localization.contract_view_regulations_info_tab_1_retail_list_supplier') }}
                                                    {{ __('localization.contract_view_regulations_info_tab_1_retail_list_services') }}
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
                                                        placeholder="{{ __('localization.contract_view_regulations_info_tab_1_retail_list_search') }}"
                                                        id="search-retail-regulation"
                                                    />
                                                </div>
                                            </div>

                                            <hr class="mb-0" />
                                            <ul class="container-for-market-list list-s-none"></ul>
                                        </div>

                                        <!-- Відсутні регламенти -->
                                        <div id="missingRegulations" class="d-none h-100">
                                            <hr />
                                            <div
                                                style="margin-top: 200px"
                                                class="h-100 d-flex flex-column align-items-center justify-content-center px-2"
                                            >
                                                <h4 class="fw-bolder">
                                                    {{ __('localization.contract_view_regulations_info_tab_1_missing_regulations_no_available') }}
                                                </h4>
                                                <p class="text-center">
                                                    {{ __('localization.contract_view_regulations_info_tab_1_missing_regulations_create') }}
                                                    <span id="missingRegulationsTitleType">
                                                        {{ __('localization.contract_view_regulations_info_tab_1_missing_regulations_template') }}
                                                    </span>
                                                    {{ __('localization.contract_view_regulations_info_tab_1_missing_regulations_services') }}
                                                    <br />
                                                    {{ __('localization.contract_view_regulations_info_tab_1_missing_regulations_for_side') }}
                                                    <span id="missingRegulationsTitleSide">
                                                        {{ __('localization.contract_view_regulations_info_tab_1_missing_regulations_template_2') }}
                                                    </span>
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
                                                        {{ __('localization.contract_view_regulations_info_tab_1_missing_regulations_create_button') }}
                                                    </span>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Один торговий регламент-->
                                        <div id="one-retail-regulation" class="d-none">
                                            <div
                                                class="p-2 pb-0 d-flex justify-content-between align-items-center"
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
                                                        {{ __('localization.contract_view_regulations_info_tab_1_one_retail_for_stores') }}
                                                    </span>
                                                    ({{ __('localization.contract_view_regulations_info_tab_1_one_retail_service_customer') }}
                                                    )
                                                </h2>
                                                <button
                                                    class="d-none btn btn-outline-primary btn-sm"
                                                    id="btn-cancel-changes"
                                                >
                                                    {{ __('localization.contract_view_regulations_info_tab_1_one_retail_cancel_changes') }}
                                                </button>
                                            </div>
                                            <hr class="mb-0" />
                                            <div class="accordion">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header px-1">
                                                        <button
                                                            class="accordion-button fw-bolder f-15"
                                                            style="color: #4b465c"
                                                            type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#accordionOne"
                                                            aria-expanded="true"
                                                            aria-controls="accordionOne"
                                                        >
                                                            {{ __('localization.contract_view_regulations_info_tab_1_one_retail_title_1') }}
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
                                                                        placeholder=""
                                                                    />
                                                                </div>
                                                                <div class="col-12 col-sm-6 mb-1">
                                                                    <div class="mb-1">
                                                                        <select
                                                                            class="select2 form-select"
                                                                            id="parentRegulation"
                                                                            data-placeholder="{{ __('localization.contract_view_regulations_info_tab_1_one_retail_parent_regulation') }}"
                                                                        >
                                                                            <option
                                                                                value=""
                                                                            ></option>
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
                                                    <h2 class="accordion-header px-1">
                                                        <button
                                                            class="accordion-button fw-bolder f-15"
                                                            style="color: #4b465c"
                                                            type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#accordionTwo"
                                                            aria-expanded="true"
                                                            aria-controls="accordionTwo"
                                                        >
                                                            {{ __('localization.contract_view_regulations_info_tab_1_one_retail_title_2') }}
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
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_one_retail_pallet_type') }}
                                                                </p>
                                                                <div style="width: 260px">
                                                                    <select
                                                                        class="select2 form-select"
                                                                        id="typePallet"
                                                                        data-placeholder="{{ __('localization.contract_view_regulations_info_tab_1_one_retail_pallet_type_placeholder') }}"
                                                                    >
                                                                        <option value=""></option>

                                                                        @php
                                                                            $typePalletNames = [
                                                                                'Європалета 120х80см' => 'contract_regulations_pallet_type_1',
                                                                                'Американська палета 120х100см' => 'contract_regulations_pallet_type_2',
                                                                                'Напівпалета 60х80см' => 'contract_regulations_pallet_type_3',
                                                                                'Фінська палета' => 'contract_regulations_pallet_type_4',
                                                                            ];
                                                                        @endphp

                                                                        @foreach ($typePallets as $key => $value)
                                                                            <option
                                                                                value="{{ $key }}"
                                                                            >
                                                                                {{ isset($typePalletNames[$value]) ? __('localization.' . $typePalletNames[$value]) : '-' }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-2 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_one_retail_pallet_height') }}
                                                                </p>
                                                                <div
                                                                    class="input-group"
                                                                    style="width: 260px"
                                                                >
                                                                    <input
                                                                        type="number"
                                                                        class="form-control"
                                                                        id="heightPallet"
                                                                    />
                                                                    <span class="input-group-text">
                                                                        {{ __('localization.contract_view_regulations_info_tab_1_one_retail_cm') }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-2 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_one_retail_remaining_term') }}
                                                                </p>
                                                                <div
                                                                    class="input-group"
                                                                    style="width: 260px"
                                                                >
                                                                    <input
                                                                        type="number"
                                                                        class="form-control"
                                                                        id="remainingTerm"
                                                                    />
                                                                    <span class="input-group-text">
                                                                        {{ __('localization.contract_view_regulations_info_tab_1_one_retail_percent') }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-2 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_one_retail_pallet_sheet') }}
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
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_one_retail_allow_prefab_pallets') }}
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
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_one_retail_allow_sandwich_pallet') }}
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
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_one_retail_labeling') }}
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
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_1_one_retail_allow_conducting') }}
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
                                    @endif
                                </div>

                                <!-- Контент в контрагента-->
                                <div>
                                    @if ($contractorRegulation)
                                        <!-- Для магазинів В ДРУГІЙ ТАБІ-->
                                        <div>
                                            <div class="p-2 pb-0">
                                                <h2 class="f-15 fw-bolder">
                                                    {{ $contractorRegulation->name }}
                                                    ({{ $contract->role !== 1 ? __('localization.contract_view_regulations_info_tab_2_service_customer') : __('localization.contract_view_regulations_info_tab_2_service_provider') }}
                                                    )
                                                </h2>
                                            </div>
                                            <hr class="mb-0" />
                                            <div class="accordion">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header px-1">
                                                        <button
                                                            class="accordion-button fw-bolder f-15"
                                                            style="color: #4b465c"
                                                            type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#accordionOne"
                                                            aria-expanded="true"
                                                            aria-controls="accordionOne"
                                                        >
                                                            {{ __('localization.contract_view_regulations_info_tab_2_title_1') }}
                                                        </button>
                                                    </h2>
                                                    <div
                                                        id="accordionOne"
                                                        class="accordion-collapse collapse show"
                                                        aria-labelledby="headingOne"
                                                        data-bs-parent="#accordionExample"
                                                    >
                                                        <div class="accordion-body px-2">
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_2_regulation_name') }}
                                                                </p>
                                                                <p class="f-15 m-0 fw-bold">
                                                                    {{ $contractorRegulation->name }}
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_2_parent_regulation') }}
                                                                </p>

                                                                @if ($contractorRegulation->parent)
                                                                    <a
                                                                        href="#"
                                                                        class="f-15 fw-bold"
                                                                    >
                                                                        {{ $contractorRegulation->parent->name }}
                                                                    </a>
                                                                @else
                                                                    <div class="f-15 fw-bold">
                                                                        -
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header px-1">
                                                        <button
                                                            class="accordion-button fw-bolder f-15"
                                                            style="color: #4b465c"
                                                            type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#accordionTwo"
                                                            aria-expanded="true"
                                                            aria-controls="accordionTwo"
                                                        >
                                                            {{ __('localization.contract_view_regulations_info_tab_2_title_2') }}
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
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0 fw-bold"
                                                                    style="color: #5d596c"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_2_return_goods_case') }}
                                                                </p>
                                                                <p class="f-15 m-0"></p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_2_pallet_type') }}
                                                                </p>
                                                                <p class="f-15 fw-bold">
                                                                    @switch($contractorRegulation['settings']['typePalet'] ?? null)
                                                                        @case('Європалета 120х80см')
                                                                            {{ __('localization.contract_regulations_pallet_type_1') }}

                                                                            @break
                                                                        @case('Американська палета 120х100см')
                                                                            {{ __('localization.contract_regulations_pallet_type_2') }}

                                                                            @break
                                                                        @case('Напівпалета 60х80см')
                                                                            {{ __('localization.contract_regulations_pallet_type_3') }}

                                                                            @break
                                                                        @case('Фінська палета')
                                                                            {{ __('localization.contract_regulations_pallet_type_4') }}

                                                                            @break
                                                                        @default
                                                                            {{ $contractorRegulation['settings']['typePalet'] }}
                                                                    @endswitch
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_2_pallet_height') }}
                                                                </p>
                                                                <p class="f-15 fw-bold">
                                                                    {{ $contractorRegulation['settings']['heightPalet'] }}
                                                                    {{ __('localization.contract_view_regulations_info_tab_2_cm') }}
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_2_remaining_term') }}
                                                                </p>
                                                                <p class="f-15 fw-bold">
                                                                    {{ $contractorRegulation['settings']['overheadTerm'] }}
                                                                    {{ __('localization.contract_view_regulations_info_tab_2_days') }}
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_2_pallet_sheet') }}
                                                                </p>
                                                                <p class="f-15 fw-bold">
                                                                    {{ $contractorRegulation['settings']['palletSheet'] ? __('localization.contract_view_regulations_info_tab_2_yes') : __('localization.contract_view_regulations_info_tab_2_no') }}
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_2_allow_prefab_pallets') }}
                                                                </p>
                                                                <p class="f-15 fw-bold">
                                                                    {{ $contractorRegulation['settings']['allowPrefabPallets'] ? __('localization.contract_view_regulations_info_tab_2_yes') : __('localization.contract_view_regulations_info_tab_2_no') }}
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_2_allow_sandwich_pallet') }}
                                                                </p>
                                                                <p class="f-15 fw-bold">
                                                                    {{ $contractorRegulation['settings']['allowSandwichPallet'] ? __('localization.contract_view_regulations_info_tab_2_yes') : __('localization.contract_view_regulations_info_tab_2_no') }}
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_2_labeling') }}
                                                                </p>
                                                                <p class="f-15 fw-bold">
                                                                    {{ $contractorRegulation['settings']['stickering'] ? __('localization.contract_view_regulations_info_tab_2_yes') : __('localization.contract_view_regulations_info_tab_2_no') }}
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between ps-3 pe-50 py-1"
                                                            >
                                                                <p
                                                                    class="f-15 m-0"
                                                                    style="color: #a5a3ae"
                                                                >
                                                                    {{ __('localization.contract_view_regulations_info_tab_2_allow_conducting') }}
                                                                </p>
                                                                <p class="f-15 fw-bold">
                                                                    {{ $contractorRegulation['settings']['allowHolding'] ? __('localization.contract_view_regulations_info_tab_2_yes') : __('localization.contract_view_regulations_info_tab_2_no') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Інфо про відсутність регламенту в контрагента -->
                                        <div
                                            class="info-about-missing-contractorTab d-flex flex-column justify-content-center align-items-center"
                                            style="height: 500px"
                                        >
                                            <h4 class="fw-bolder">
                                                {{ __('localization.contract_view_regulations_info_tab_2_missing_regulation_title') }}
                                            </h4>
                                            <p class="text-secondary">
                                                {{ __('localization.contract_view_regulations_info_tab_2_missing_regulation_description') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal add comment-->
    <div
        class="modal fade"
        id="addCommentModal"
        tabindex="-1"
        aria-labelledby="addCommentModal"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-md" style="max-width: 580px">
            <div class="p-4 modal-content">
                <h2 class="mb-2 text-center fw-bolder">
                    {{ __('localization.contract_view_add_comment_modal_title') }}
                </h2>

                <form class="" method="" action="#">
                    <textarea
                        class="form-control"
                        style="min-height: 60px; max-height: 600px"
                        id="text-for-comment"
                        rows="3"
                        placeholder="{{ __('localization.contract_view_add_comment_modal_placeholder') }}"
                    ></textarea>
                    <div class="d-flex justify-content-end pt-2">
                        <a
                            class="btn btn-flat-secondary float-start mr-2"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        >
                            {{ __('localization.contract_view_add_comment_modal_cancel') }}
                        </a>
                        <button type="button" class="btn btn-primary" id="btnAddComment">
                            {{ __('localization.contract_view_add_comment_modal_send') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal causes of rejection-->
    <div
        class="modal fade"
        id="causesRejectionModal"
        tabindex="-1"
        aria-labelledby="causesRejectionModal"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-md" style="max-width: 580px">
            <div class="p-4 modal-content">
                <h2 class="text-center fw-bolder">
                    {{ __('localization.contract_view_causes_rejection_modal_title') }}
                </h2>
                <p class="mb-2 text-center">
                    {{ __('localization.contract_view_causes_rejection_modal_description') }}
                </p>

                <form class="" method="" action="#">
                    <textarea
                        class="form-control"
                        id="text-for-comment"
                        rows="3"
                        placeholder="{{ __('localization.contract_view_causes_rejection_modal_placeholder') }}"
                    ></textarea>
                    <div class="d-flex justify-content-end pt-2">
                        <a
                            class="btn btn-flat-secondary float-start mr-2"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        >
                            {{ __('localization.contract_view_causes_rejection_modal_cancel') }}
                        </a>
                        <button
                            type="button"
                            class="btn btn-primary"
                            id="click-causes-of-rejection"
                        >
                            {{ __('localization.contract_view_causes_rejection_modal_reject') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal contract cancelled-->
    <div
        class="modal fade"
        id="contractCancelledModal"
        tabindex="-1"
        aria-labelledby="contractCancelledModal"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-md" style="max-width: 580px">
            <div class="p-4 modal-content">
                <h2 class="text-center fw-bolder">
                    {{ __('localization.contract_view_contract_cancelled_modal_title') }}
                </h2>
                <p class="mb-2 text-center">
                    {{ __('localization.contract_view_contract_cancelled_modal_description') }}
                </p>

                <form class="" method="" action="#">
                    <textarea
                        class="form-control"
                        id="text-for-comment"
                        rows="3"
                        placeholder="{{ __('localization.contract_view_contract_cancelled_modal_placeholder') }}"
                    ></textarea>
                    <div class="d-flex justify-content-end pt-2">
                        <a
                            class="btn btn-flat-secondary float-start mr-2"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        >
                            {{ __('localization.contract_view_contract_cancelled_modal_cancel') }}
                        </a>
                        <button type="button" class="btn btn-primary" id="btn-cancel-contract">
                            {{ __('localization.contract_view_contract_cancelled_modal_cancel_contract') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal delete contract-->
    <div
        class="modal fade"
        id="deleteContractModal"
        tabindex="-1"
        aria-labelledby="deleteContractModal"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-md" style="max-width: 580px">
            <div class="p-2 modal-content">
                <h4 class="mb-2 fw-bolder">
                    {{ __('localization.contract_view_contract_delete_contract_modal_title', ['id' => $contract->id]) }}
                </h4>
                <p class="mb-2">
                    {{ __('localization.contract_view_contract_delete_contract_modal_confirm') }}
                </p>

                <form class="d-flex justify-content-end" method="" action="#">
                    <a
                        class="btn btn-flat-secondary float-start mr-2"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    >
                        {{ __('localization.contract_view_contract_delete_contract_modal_cancel') }}
                    </a>
                    <button type="button" class="btn btn-primary" id="delete-contract-btn">
                        {{ __('localization.contract_view_contract_delete_contract_modal_delete') }}
                    </button>
                </form>
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
                        {{ __('localization.contract_view_contract_amended_changes_modal_title') }}
                    </h2>
                    <div class="p-2">
                        <p class="mb-1 text-start">
                            {{ __('localization.contract_view_contract_amended_changes_modal_message') }}
                        </p>
                    </div>
                    <form class="d-flex justify-content-end" method="" action="#">
                        <a
                            class="btn btn-outline-primary mr-2 text-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#createNewRegulationModal"
                            id="btn-create-new-regulation-in-modal"
                        >
                            {{ __('localization.contract_view_contract_amended_changes_modal_create_new') }}
                        </a>
                        <button type="button" class="btn btn-primary" id="update-regulation">
                            {{ __('localization.contract_view_contract_amended_changes_modal_update') }}
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
                        {{ __('localization.contract_view_contract_create_new_regulation_modal_title') }}
                    </h2>
                    <p class="mb-1 text-center">
                        {{ __('localization.contract_view_contract_create_new_regulation_modal_message') }}
                    </p>
                    <form class="pt-2" method="" action="#">
                        <div class="mb-1">
                            <label class="form-label" for="nameRetailInModal">
                                {{ __('localization.contract_view_contract_create_new_regulation_modal_name_label') }}
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="nameRetailInModal"
                                required
                                placeholder="{{ __('localization.contract_view_contract_create_new_regulation_modal_name_placeholder') }}"
                            />
                        </div>
                        <div class="mb-2">
                            <label class="form-label" for="parentRegulationInModal">
                                {{ __('localization.contract_view_contract_create_new_regulation_modal_parent_label') }}
                            </label>
                            <select
                                class="select2 form-select"
                                name="parentRegulationInModal"
                                id="parentRegulationInModal"
                                data-placeholder="{{ __('localization.contract_view_contract_create_new_regulation_modal_parent_placeholder') }}"
                            >
                                <option value=""></option>
                                <option selected value="parent">
                                    {{ __('localization.contract_view_contract_create_new_regulation_modal_parent_default') }}
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
                                {{ __('localization.contract_view_contract_create_new_regulation_modal_create_button') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        const contractId = {!! $contract->id !!};
        const isContractor = {!! intval($userSide) !!};
        const commentCompany = {!! $userSide ? $contract->counterparty : $contract->company !!};

        let contractRoleReg = {!! $contract->role !!};
        let contractTypeReg = {!! $contract->type_id !!};

        if (contractRoleReg === 0) {
            contractRoleReg = 1;
        } else {
            contractRoleReg = 0;
        }
    </script>

    <script type="module" src="{{ asset('assets/js/entity/contract/contract-view.js') }}"></script>
@endsection
