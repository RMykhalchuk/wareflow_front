@extends('layouts.admin')
@section('title', __('localization.company_view_title'))

@section('page-style')
    
@endsection

@section('before-style')
    
@endsection

@section('content')
    <!-- сторінка для ТОВ  -->
    <x-layout.container>
        <x-slot:header>
            @if ($company)
                @php
                    $companyName =
                        $company->type->key === 'legal'
                            ? $company->company->name
                            : $company->company->surname . ' ' . $company->company->first_name;
                @endphp

                @include(
                    'panels.breadcrumb',
                    [
                        'options' => [
                            [
                                'url' => '/companies/',
                                'name' => __('localization.company_view_breadcrumb_text_1'),
                            ],
                            [
                                'name' => __('localization.company_view_breadcrumb_text_2', [
                                    'companyName' => $companyName,
                                ]),
                            ],
                        ],
                    ]
                )
            @endif

            <div class="d-flex gap-1 align-self-end">
                <div>
                    <a
                        class="text-secondary"
                        href="{{ route('companies.edit', ['company' => $company->id]) }}"
                    >
                        <i data-feather="edit" style="cursor: pointer; transform: scale(1.2)"></i>
                    </a>
                </div>
                @if (! $company->trashed() && ! $company->hasRelatedGoods())
                    <div>
                        <div class="btn-group">
                            <i
                                data-feather="more-horizontal"
                                id="header-dropdown"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                                style="cursor: pointer; transform: scale(1.2)"
                            ></i>
                            <div class="dropdown-menu px-1" aria-labelledby="header-dropdown">
                                <a class="" href="#">
                                    <div>
                                        <button
                                            class="btn text-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteCompany"
                                        >
                                            {{ __('localization.company_view_delete_company_btn_modal') }}
                                        </button>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </x-slot>

        <!-- тут вся інфа модал компанії -->
        <x-slot:slot>
            <div class="card mt-2">
                <div class="card-body p-1 p-md-4 row mx-0">
                    <div
                        class="col-12 d-flex flex-row justify-content-start text-center gap-1 flex-wrap flex-md-nowrap"
                    >
                        <div class="d-flex flex-column gap-1">
                            <img
                                src="{{ $company->img_type ? '/files/uploads/company/image/' . $company->id . '.' . $company->img_type : asset('assets/icons/entity/company/default-empty-company.svg') }}"
                                id="account-upload-img"
                                class="uploadedAvatar rounded"
                                alt="company image"
                                height="80"
                                width="80"
                            />
                        </div>
                        <div
                            class="d-flex flex-column justify-content-between justify-content-md-around"
                        >
                            <div class="d-flex align-items-center gap-1">
                                <h3 class="mb-0 fw-bolder text-start">
                                    {{ $company->type->key == 'legal' ? $company->company->name : $company->company->surname . ' ' . $company->company->first_name }}
                                </h3>
                            </div>
                            <div class="d-flex justify-content-start">
                                <div class="fw-bold">
                                    {{ $company->type->key == 'legal' ? __('localization.company_view_card_info_main_data_company_type_name_legal') : __('localization.company_view_card_info_main_data_company_type_name_physical') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-2 my-2" />

                    <div class="col-12 p-0 mt-1 row mx-0">
                        <h5 class="fw-bolder mb-0">
                            {{ __('localization.company_view_card_info_main_data_title') }}
                        </h5>
                        <div class="col-12 p-0 my-2 card-data-reverse">
                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ $company->type->key == 'legal' ? __('localization.company_view_card_info_edrpou') : __('localization.company_view_card_info_ipn') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $company->type->key == 'legal' ? (isset($company->company->edrpou) ? $company->company->edrpou : __('localization.company_view_card_info_no_data')) : (isset($company->ipn) ? $company->ipn : __('localization.company_view_card_info_no_data')) }}
                                </div>
                            </div>
                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.company_view_card_info_email') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ isset($company->email) ? $company->email : __('localization.company_view_card_info_no_data') }}
                                </div>
                            </div>
                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.company_view_card_info_category') }}
                                </div>
                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    @if ($company->category_id === 1)
                                        {{ __('localization.company_view_card_info_category_manufacturer') }}
                                    @elseif ($company->category_id === 2)
                                        {{ __('localization.company_view_card_info_category_supplier') }}
                                    @elseif ($company->category_id === 3)
                                        {{ __('localization.company_view_card_info_category_distributor') }}
                                    @elseif ($company->category_id === 4)
                                        {{ __('localization.company_view_card_info_category_supermarket') }}
                                    @elseif ($company->category_id === 5)
                                        {{ __('localization.company_view_card_info_category_carrier') }}
                                    @elseif ($company->category_id === 6)
                                        {{ __('localization.company_view_card_info_category_3pl_operator') }}
                                    @else
                                        {{ __('localization.company_view_card_info_category_none') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($company->type->key == 'legal' && $company->ipn)
                        <div class="col-12 p-0 mt-1 row mx-0">
                            <h5 class="fw-bolder mb-0">
                                {{ __('localization.company_view_card_info_vat_data_title') }}
                            </h5>
                            <div class="col-12 p-0 my-2 card-data-reverse">
                                <div class="row mx-0 py-1">
                                    <div class="col-6 col-md-3 f-15">
                                        {{ __('localization.company_view_card_info_ipn_number') }}
                                    </div>

                                    <div class="col-auto col-md-9 f-15 fw-bold">
                                        {{ $company->ipn }}
                                    </div>
                                </div>
                                <div class="row mx-0 py-1">
                                    <div class="col-6 col-md-3 f-15">
                                        {{ __('localization.company_view_card_info_registration_certificate') }}
                                    </div>

                                    <div
                                        class="col-auto col-md-9 f-15 fw-bold d-flex align-items-center gap-1"
                                    >
                                        <img
                                            src="{{ asset('assets/icons/entity/company/file-upload.svg') }}"
                                            alt="file-upload"
                                        />
                                        <a
                                            class="link-secondary text-decoration-underline"
                                            download="{{ $registerFile->name }}"
                                            href="{{ '/files/uploads/company/docs/registration' . '/' . $company->company->id . '.' . $company->company->reg_doctype }}"
                                        >
                                            {{ $registerFile->name }}
                                        </a>
                                    </div>
                                </div>
                                <div class="row mx-0 py-1">
                                    <div class="col-6 col-md-3 f-15">
                                        {{ __('localization.company_view_card_info_install_documents') }}
                                    </div>

                                    <div
                                        class="col-auto col-md-9 f-15 fw-bold d-flex align-items-center gap-1"
                                    >
                                        <img
                                            src="{{ asset('assets/icons/entity/company/file-upload.svg') }}"
                                            alt="file-upload"
                                        />
                                        <a
                                            class="link-secondary text-decoration-underline"
                                            download="{{ $installFile->name }}"
                                            href="{{ '/files/uploads/company/docs/install' . '/' . $company->company->id . '.' . $company->company->install_doctype }}"
                                        >
                                            {{ $installFile->name }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-12 p-0 mt-1 row mx-0">
                        <h5 class="fw-bolder mb-0">
                            {{ __('localization.company_view_card_info_address_title') }}
                        </h5>
                        <div class="col-12 p-0 my-2 card-data-reverse">
                            @if (($company->address->country?->name && $company->address->settlement?->name && $company->address->street?->name && $company->address->building_number) || $company->address->gln)
                                <div class="row mx-0 py-1">
                                    <div class="col-6 col-md-3 f-15">
                                        {{ __('localization.company_view_card_info_actual_address') }}
                                    </div>
                                    <div class="col-auto col-md-9 f-15 fw-bold">
                                        @if ($company->address->country?->name && $company->address->settlement?->name && $company->address->street?->name && $company->address->building_number)
                                            {{ $company->address->country->name . ', ' . $company->address->settlement->name . ', ' . $company->address->street->name . ', ' . __('localization.company_view_card_info_actual_address_house') . $company->address->building_number . ', ' }}
                                            {{ $company->address->apartment_number ? __('localization.company_view_card_info_actual_address_apartment') . ' ' . $company->address->apartment_number : '' }}
                                        @endif

                                        <br />
                                        @if ($company->address?->gln)
                                            {{ 'GLN ' . $company->address->gln }}
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="row mx-0 py-1">
                                    <div class="col-6 col-md-3 f-15">
                                        {{ __('localization.company_view_card_info_actual_address') }}
                                    </div>
                                    <div class="col-auto col-md-9 f-15 fw-bold">
                                        {{ __('localization.company_view_card_info_no_data') }}
                                    </div>
                                </div>
                            @endif
                            @if ($company->type->key == 'legal')
                                @if (($company->address->country?->name && $company->address->settlement?->name && $company->address->street?->name && $company->address->building_number) || $company->address->gln)
                                    <div class="row mt-1 mx-0 p-0">
                                        <div class="col-6 col-md-3 f-15">
                                            {{ __('localization.company_view_card_info_legal_address') }}
                                        </div>

                                        <div class="col-auto col-md-9 f-15 fw-bold">
                                            @if ($company->address->country?->name && $company->address->settlement?->name && $company->address->street?->name && $company->address->building_number)
                                                {{ $company->address->country->name . ', ' . $company->address->settlement->name . ', ' . $company->address->street->name . ', ' . __('localization.company_view_card_info_actual_address_house') . $company->address->building_number . ', ' }}
                                                {{ $company->address->apartment_number ? __('localization.company_view_card_info_legal_address_apartment') . ' ' . $company->address->apartment_number : '' }}
                                            @endif

                                            <br />
                                            @if ($company->address?->gln)
                                                {{ 'GLN ' . $company->company->address->gln }}
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="row mt-1 mx-0 p-0">
                                        <div class="col-6 col-md-3 f-15">
                                            {{ __('localization.company_view_card_info_legal_address') }}
                                        </div>
                                        <div class="col-auto col-md-9 f-15 fw-bold">
                                            {{ __('localization.company_view_card_info_no_data') }}
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="col-12 p-0 mt-1 row mx-0">
                        <h5 class="fw-bolder mb-0">
                            {{ __('localization.company_view_card_info_requisites_title') }}
                        </h5>
                        <div class="col-12 p-0 my-2 card-data-reverse">
                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.company_view_card_info_bank') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ isset($company->bank) ? $company->bank : __('localization.company_view_card_info_no_data') }}
                                </div>
                            </div>
                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.company_view_card_info_iban') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $company->iban ?? __('localization.company_view_card_info_no_data') }}
                                </div>
                            </div>
                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.company_view_card_info_mfo') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $company->mfo ?? __('localization.company_view_card_info_no_data') }}
                                </div>
                            </div>
                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.company_view_card_info_currency') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $company->currency?->label() ?? __('localization.company_view_card_info_no_data') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 p-0 mt-1 row mx-0">
                        <h5 class="fw-bolder mb-0">
                            {{ __('localization.company_view_card_info_additional_title') }}
                        </h5>
                        <div class="col-12 p-0 my-2 card-data-reverse">
                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.company_view_card_info_about') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $company->about ?? __('localization.company_view_card_info_no_description') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-layout.container>

    <!-- модал видалення компанії -->
    <x-modal.delete-modal
        modalId="deleteCompany"
        :action="route('companies.destroy', ['company' => $company->id])"
        title="localization.company_view_delete_company_modal_title"
        description="localization.company_view_delete_company_modal_description"
        cancelText="localization.company_view_delete_company_modal_cancel_button"
        confirmText="localization.company_view_delete_company_modal_delete_button"
    />
@endsection

@section('page-script')
    
@endsection
