@extends('layouts.admin')
@section('title', __('localization.transport_view_title'))

@section('content')
    <x-layout.container>
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/transports',
                            'name' => __('localization.transport_view_transport'),
                        ],
                        [
                            'name' =>
                                __('localization.transport_view_title_card') .
                                ' ' .
                                $transport->brand->name .
                                ' ' .
                                $transport->model->name .
                                '(' .
                                $transport->license_plate .
                                ')',
                        ],
                    ],
                ]
            )

            <div class="d-flex gap-1 align-self-end">
                <button class="btn p-25 h-50">
                    <a
                        class="text-secondary"
                        href="{{ route('transports.edit', ['transport' => $transport->id]) }}"
                        data-bs-toggle="tooltip"
                        title="{{ __('localization.transport_view_edit_icon_tooltip') }}"
                    >
                        <i data-feather="edit" style="cursor: pointer; transform: scale(1.2)"></i>
                    </a>
                </button>

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
                            <button
                                data-bs-toggle="modal"
                                id="cancel_button"
                                data-bs-target="#delete_transport"
                                type="submit"
                                class="btn btn-flat-danger"
                            >
                                {{ __('localization.transport_view_delete') }}
                            </button>
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
                                src="{{ $transport->img_type ? '/files/uploads/transport/' . $transport->id . '.' . $transport->img_type : asset('assets/icons/entity/transport/default-truck-empty.svg') }}"
                                id="account-upload-img"
                                class="uploadedAvatar rounded"
                                alt="{{ __('localization.transport_view_image_alt') }}"
                                height="80"
                                width="80"
                            />
                        </div>
                        <div class="d-flex flex-column justify-content-md-around gap-1">
                            <div class="d-flex align-items-center gap-1">
                                <h3 class="mb-0 fw-bolder text-start">
                                    {{ $transport->brand->name }} {{ $transport->model->name }}
                                </h3>
                            </div>
                            <div class="d-flex justify-content-start">
                                <div class="fw-bold">
                                    {{ $transport->license_plate ?? __('localization.transport_view_no_license_plate_placeholder') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-3 my-2" />

                    <div class="col-12 p-0 mt-1 row mx-0">
                        <h5 class="fw-bolder mb-0">
                            {{ __('localization.transport_view_characteristics_title') }}
                        </h5>
                        <div class="col-12 p-0 my-2 card-data-reverse">
                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.transport_view_registration_country_label') }}
                                </div>
                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    @switch($transport->registration_country_id ?? null)
                                        @case('1')
                                            {{ __('localization.transport_view_registration_country_value_UA') }}

                                            @break
                                        @case('2')
                                            {{ __('localization.transport_view_registration_country_value_USA') }}

                                            @break
                                        @case('3')
                                            {{ __('localization.transport_view_registration_country_value_ENG') }}

                                            @break
                                        @case('4')
                                            {{ __('localization.transport_view_registration_country_value_PLN') }}

                                            @break
                                        @case('5')
                                            {{ __('localization.transport_view_registration_country_value_GE') }}

                                            @break
                                        @default
                                            {{ $transport->registration_country_id }}
                                    @endswitch
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.transport_view_type_label') }}
                                </div>
                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $transport->type->name }}
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.transport_view_category_label') }}
                                </div>
                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    @switch($transport->category->key ?? null)
                                        @case('lorry')
                                            {{ __('localization.transport_view_category_lorry') }}

                                            @break
                                        @case('lorry_with_trailer')
                                            {{ __('localization.transport_view_category_lorry_with_trailer') }}

                                            @break
                                        @case('truck_tractor')
                                            {{ __('localization.transport_view_category_truck_tractor') }}

                                            @break
                                        @case('van')
                                            {{ __('localization.transport_view_category_van') }}

                                            @break
                                        @default
                                            {{ __('localization.transport_view_no_category') }}
                                    @endswitch
                                </div>
                            </div>

                            @if ($transport->equipment_id)
                                <div class="row mx-0 py-1">
                                    <div class="col-6 col-md-3 f-15">
                                        {{ __('localization.transport_view_equipment_label') }}
                                    </div>
                                    <div class="col-auto col-md-9 f-15 fw-bold">
                                        <a
                                            class="text-reset text-decoration-underline"
                                            href="{{ route('transport-equipments.show', ['transport_equipment' => $transport->equipment_id]) }}"
                                        >
                                            {{ $transport->equipment->brand->name . ' ' . $transport->equipment->model->name . ' (' . $transport->equipment->license_plate . ')' }}
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.transport_view_manufacture_year_label') }}
                                </div>
                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $transport->manufacture_year }}
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.transport_view_company_label') }}
                                </div>
                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    <a
                                        class="text-reset text-decoration-underline"
                                        href="{{ route('companies.show', ['company' => $transport->company_id]) }}"
                                    >
                                        {{ $transport->company->type->key == 'legal' ? $transport->company->company->name : $transport->company->company->surname . ' ' . $transport->company->company->first_name }}
                                    </a>
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.transport_view_driver_label') }}
                                </div>
                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    <a
                                        class="text-reset text-decoration-underline"
                                        href="{{ route('user.show', ['user' => $transport->driver_id]) }}"
                                    >
                                        {{ $transport->driver->surname . ' ' . $transport->driver->name . ' ' . $transport->driver->patronymic ?? __('localization.transport_view_no_driver') }}
                                    </a>
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.transport_view_empty_spending_label') }}
                                </div>
                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $transport->spending_empty }}
                                    {{ __('localization.transport_view_spending_unit') }}
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.transport_view_full_spending_label') }}
                                </div>
                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $transport->spending_full }}
                                    {{ __('localization.transport_view_spending_unit') }}
                                </div>
                            </div>

                            <div class="row mx-0 py-1">
                                <div class="col-6 col-md-3 f-15">
                                    {{ __('localization.transport_view_weight_label') }}
                                </div>
                                <div class="col-auto col-md-9 f-15 fw-bold">
                                    {{ $transport->weight }}
                                    {{ __('localization.transport_view_weight_unit') }}
                                </div>
                            </div>
                        </div>

                        @if ($transport->category_id == 2)
                            <h5 class="fw-bolder mb-0">
                                {{ __('localization.transport_view_body_characteristics_title') }}
                            </h5>
                            <div class="col-12 p-0 my-2 card-data-reverse">
                                <div class="row mx-0 py-1">
                                    <div class="col-6 col-md-3 f-15">
                                        {{ __('localization.transport_view_loading_method_label') }}
                                    </div>
                                    <div class="col-auto col-md-9 f-15 fw-bold">
                                        @if ($transport->download_methods)
                                            @foreach (json_decode($transport->download_methods) as $key => $method)
                                                {{ count(json_decode($transport->download_methods)) - 1 == $key ? $transport->getDownloadMethodById($method)->name : $transport->getDownloadMethodById($method)->name . ', ' }}
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                @if ($transport->adr)
                                    <div class="row mx-0 py-1">
                                        <div class="col-6 col-md-3 f-15">
                                            {{ __('localization.transport_view_adr_label') }}
                                        </div>
                                        <div class="col-auto col-md-9 f-15 fw-bold">
                                            {{ $transport->adr->name }}
                                            {{ __('localization.transport_view_adr_class') }}
                                        </div>
                                    </div>
                                @endif

                                <div class="row mx-0 py-1">
                                    <div class="col-6 col-md-3 f-15">
                                        {{ __('localization.transport_view_capacity_label') }}
                                    </div>
                                    <div class="col-auto col-md-9 f-15 fw-bold">
                                        {{ $transport->carrying_capacity }}
                                        {{ __('localization.transport_view_capacity_unit') }}
                                    </div>
                                </div>

                                @if ($transport->length)
                                    <div class="row mx-0 py-1">
                                        <div class="col-6 col-md-3 f-15">
                                            {{ __('localization.transport_view_length_label') }}
                                        </div>
                                        <div class="col-auto col-md-9 f-15 fw-bold">
                                            {{ $transport->length }}
                                            {{ __('localization.transport_view_length_unit') }}
                                        </div>
                                    </div>

                                    <div class="row mx-0 py-1">
                                        <div class="col-6 col-md-3 f-15">
                                            {{ __('localization.transport_view_width_label') }}
                                        </div>
                                        <div class="col-auto col-md-9 f-15 fw-bold">
                                            {{ $transport->width }}
                                            {{ __('localization.transport_view_width_unit') }}
                                        </div>
                                    </div>

                                    <div class="row mx-0 py-1">
                                        <div class="col-6 col-md-3 f-15">
                                            {{ __('localization.transport_view_height_label') }}
                                        </div>
                                        <div class="col-auto col-md-9 f-15 fw-bold">
                                            {{ $transport->height }}
                                            {{ __('localization.transport_view_height_unit') }}
                                        </div>
                                    </div>

                                    <div class="row mx-0 py-1">
                                        <div class="col-6 col-md-3 f-15">
                                            {{ __('localization.transport_view_volume_label') }}
                                        </div>
                                        <div class="col-auto col-md-9 f-15 fw-bold">
                                            {{ $transport->volume }}
                                            {!! __('localization.transport_view_volume_unit') !!}
                                        </div>
                                    </div>
                                @else
                                    <div class="d-none"></div>
                                @endif

                                <div class="row mx-0 py-1">
                                    <div class="col-6 col-md-3 f-15">
                                        {{ __('localization.transport_view_capacity') }}
                                    </div>

                                    <div
                                        class="col-auto col-md-9 f-15 fw-bold d-flex flex-column flex-md-row"
                                    >
                                        <div class="pe-1">
                                            {{ $transport->capacity_eu }}
                                            {{ __('localization.transport_view_eu_pallets_label') }}
                                        </div>
                                        <div>
                                            {{ $transport->capacity_am }}
                                            {{ __('localization.transport_view_am_pallets_label') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mx-0 py-1">
                                    <div class="col-6 col-md-3 f-15">
                                        {{ __('localization.transport_view_hydroboard_label') }}
                                    </div>
                                    <div class="col-auto col-md-9 f-15 fw-bold">
                                        @if ($transport->hydroboard)
                                            {{ __('localization.transport_view_present') }}
                                        @else
                                            {{ __('localization.transport_view_absent') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </x-slot>
    </x-layout.container>

    <x-modal.delete-modal
        modalId="delete_transport"
        :action="route('transports.destroy', ['transport' => $transport->id])"
        title="localization.transport_view_delete_transport_title"
        description="localization.transport_view_confirm_delete"
        cancelText="localization.transport_view_cancel"
        confirmText="localization.transport_view_delete_submit"
    />
@endsection

@section('page-script')
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>
@endsection
