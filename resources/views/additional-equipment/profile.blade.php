@extends('layouts.admin')
@section('title', __('localization.additional_equipment_view_title'))

@section('content')
    <x-layout.container>
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/transport-equipments',
                            'name' => __('localization.additional_equipment_view_breadcrumb_equipment'),
                        ],
                        [
                            'name' => __('localization.additional_equipment_view_breadcrumb_view'),
                            'name2' => $transportEquipment->brand->name,
                            'name3' => ' ' . $transportEquipment->model->name,
                        ],
                    ],
                ]
            )

            <div class="d-flex gap-1 align-self-end">
                <div>
                    <a
                        class="text-secondary"
                        data-bs-toggle="tooltip"
                        title="{{ __('localization.additional_equipment_view_edit_button') }}"
                        href="{{ route('transport-equipments.edit', ['transport_equipment' => $transportEquipment->id]) }}"
                    >
                        <i data-feather="edit" style="cursor: pointer; transform: scale(1.2)"></i>
                    </a>
                </div>
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
                                    <div>
                                        <button
                                            class="btn text-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteEquipment"
                                        >
                                            {{ __('localization.additional_equipment_view_deactivate_button') }}
                                        </button>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot:slot>
            <div class="card">
                <div class="card-body p-1 p-md-4 row mx-0">
                    <div class="col-12 d-flex flex-row justify-content-start text-center gap-1">
                        <div class="d-flex flex-column gap-1">
                            <img
                                src="{{ $transportEquipment->img_type ? '/files/uploads/transport-equipment/' . $transportEquipment->id . '.' . $transportEquipment->img_type : asset('assets/icons/entity/additional-equipment/default-truck-empty.svg') }}"
                                id="account-upload-img"
                                class="uploadedAvatar rounded"
                                alt="{{ __('localization.additional_equipment_view_profile_image_alt') }}"
                                height="80"
                                width="80"
                            />
                        </div>
                        <div
                            class="d-flex flex-column justify-content-between justify-content-md-around"
                        >
                            <div class="d-flex align-items-center gap-1">
                                <h3 class="mb-0 fw-bolder text-start">
                                    {{ $transportEquipment->brand->name }}
                                    {{ $transportEquipment->model->name }}
                                </h3>
                            </div>
                            <div class="d-flex justify-content-start">
                                <div class="fw-bold">
                                    {{ $transportEquipment->license_plate ?? __('localization.additional_equipment_view_no_license_plate') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-3 my-2" />

                    <div class="col-12 p-0 mt-1 row mx-0">
                        <h5 class="fw-bolder mb-0">
                            {{ __('localization.additional_equipment_view_basic_data_title') }}
                        </h5>
                        <div class="col-12 p-0 my-3 card-data-reverse">
                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.additional_equipment_view_registration_country_label') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    @switch($transportEquipment->country->key ?? null)
                                        @case('ukraine')
                                            {{ __('localization.additional_equipment_view_registration_country_ukraine') }}

                                            @break
                                        @case('usa')
                                            {{ __('localization.additional_equipment_view_registration_country_usa') }}

                                            @break
                                        @case('england')
                                            {{ __('localization.additional_equipment_view_registration_country_england') }}

                                            @break
                                        @case('poland')
                                            {{ __('localization.additional_equipment_view_registration_country_poland') }}

                                            @break
                                        @case('germany')
                                            {{ __('localization.additional_equipment_view_registration_country_germany') }}

                                            @break
                                        @default
                                            {{ $transportEquipment->country->name }}
                                    @endswitch
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.additional_equipment_view_company_label') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    <a
                                        class="text-reset text-decoration-underline"
                                        href="{{ route('companies.show', ['company' => $transportEquipment->company_id]) }}"
                                    >
                                        {{ $transportEquipment->company->type->key == 'legal' ? $transportEquipment->company->company->name : $transportEquipment->company->company->surname . ' ' . $transportEquipment->company->company->first_name }}
                                    </a>
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.additional_equipment_view_vehicle_label') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    <a
                                        class="text-reset text-decoration-underline"
                                        href="{{ route('transports.show', ['transport' => $transportEquipment->transport->id]) }}"
                                    >
                                        {{ $transportEquipment->transport->brand->name . ' ' . $transportEquipment->transport->model->name }}
                                    </a>
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.additional_equipment_view_manufacture_year_label') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $transportEquipment->manufacture_year }}
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.additional_equipment_view_type_label') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $transportEquipment->type->name }}
                                </div>
                            </div>
                        </div>

                        <h5 class="fw-bolder mb-0">
                            {{ __('localization.additional_equipment_view_characteristics_title') }}
                        </h5>

                        <div class="col-12 p-0 my-3 card-data-reverse">
                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.additional_equipment_view_download_method_label') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    @if ($transportEquipment->download_methods)
                                        @foreach (json_decode($transportEquipment->download_methods) as $key => $method)
                                            {{
                                                count(json_decode($transportEquipment->download_methods)) - 1 == $key
                                                    ? $transportEquipment->getDownloadMethodById($method)->name
                                                    : $transportEquipment->getDownloadMethodById($method)->name . ', '
                                            }}
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.additional_equipment_view_adr_label') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $transportEquipment->adr->name }}
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.additional_equipment_view_carrying_capacity_label') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $transportEquipment->carrying_capacity }}
                                    {{ __('localization.additional_equipment_view_ton') }}
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.additional_equipment_view_length_label') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $transportEquipment->length }}
                                    {{ __('localization.additional_equipment_view_meter') }}
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.additional_equipment_view_width_label') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $transportEquipment->width }}
                                    {{ __('localization.additional_equipment_view_meter') }}
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.additional_equipment_view_height_label') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $transportEquipment->height }}
                                    {{ __('localization.additional_equipment_view_meter') }}
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.additional_equipment_view_volume_label') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $transportEquipment->volume }}
                                    {{ __('localization.additional_equipment_view_meter') }}
                                    <sup>3</sup>
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.additional_equipment_view_capacity_label') }}
                                </div>

                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $transportEquipment->capacity_eu }}
                                    {{ __('localization.additional_equipment_view_capacity_eu_label') }}
                                    , {{ $transportEquipment->capacity_am }}
                                    {{ __('localization.additional_equipment_view_capacity_am_label') }}
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.additional_equipment_view_hydroboard_label') }}
                                </div>
                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    @if ($transportEquipment->hydroboard)
                                        {{ __('localization.additional_equipment_view_hydroboard_present') }}
                                    @else
                                        {{ __('localization.additional_equipment_view_hydroboard_absent') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-layout.container>

    <!-- Модалка видалення обладнання -->
    <x-modal.delete-modal
        modalId="deleteEquipment"
        :action="route('transport-equipments.destroy', ['transport_equipment' => $transportEquipment->id])"
        title="localization.additional_equipment_view_delete_title"
        description="localization.additional_equipment_view_delete_confirmation"
        cancelText="localization.additional_equipment_view_delete_cancel_button"
        confirmText="localization.additional_equipment_view_delete_button"
    />
@endsection

@section('page-script')
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>
@endsection
