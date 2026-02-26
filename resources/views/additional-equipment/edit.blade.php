@extends('layouts.admin')
@section('title', __('localization.additional_equipment_edit_title'))

@section('content')
    <x-layout.container id="data_tab_1">
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/transport-equipments',
                            'name' => __(
                                'localization.additional_equipment_edit_breadcrumb_additional_equipment',
                            ),
                        ],
                        [
                            'url' => '/transport-equipments/' . $transportEquipment->id,
                            'name' => __(
                                'localization.additional_equipment_edit_breadcrumb_view_transport_equipment',
                                [
                                    'brandName' => $transportEquipment->brand->name,
                                ],
                            ),
                        ],
                        [
                            'name' => __(
                                'localization.additional_equipment_edit_breadcrumb_edit_additional_equipment',
                            ),
                        ],
                    ],
                ]
            )

            <div class="d-flex gap-1 align-self-end">
                <a
                    class="btn btn-flat-secondary"
                    href="{{ '/transport-equipments' }}"
                    data-bs-toggle="modal"
                    data-bs-target="#cancelEditPage"
                >
                    {{ __('localization.additional_equipment_edit_cancel_button') }}
                </a>
                <button
                    type="button"
                    class="btn btn-primary"
                    id="edit"
                    data-id="{{ $transportEquipment->id }}"
                >
                    {{ __('localization.additional_equipment_edit_save_button') }}
                </button>
            </div>
        </x-slot>

        <x-slot:slot>
            <x-page-title
                :title="__('localization.additional_equipment_create_breadcrumb_registration')"
            />

            <div class="card mt-2">
                <div class="card-header">
                    <h4 class="card-title fw-bolder">
                        {{ __('localization.additional_equipment_edit_card_title') }}
                    </h4>
                </div>
                <div class="card-body my-25">
                    <x-form.avatar-uploader
                        id="image"
                        name="image"
                        imageSrc="{{
                                    $transportEquipment->img_type
                                        ? '/files/uploads/transport-equipment/' . $transportEquipment->id . '.' . $transportEquipment->img_type
                                        : asset('assets/icons/entity/additional-equipment/default-truck-empty.svg')
                                }}"
                        :disabled="null"
                    />

                    <div class="mt-0.5 pt-50">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="select2-hide-search">
                                    {{ __('localization.additional_equipment_edit_brand_label') }}
                                </label>
                                <select
                                    class="hide-search form-select"
                                    data-id="{{ $transportEquipment->brand_id }}"
                                    data-dictionary="additional_equipment_brand"
                                    id="mark-equipment"
                                    data-placeholder="{{ __('localization.additional_equipment_edit_brand_placeholder') }}"
                                ></select>
                            </div>

                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="select2-hide-search">
                                    {{ __('localization.additional_equipment_edit_model_label') }}
                                </label>
                                <select
                                    class="hide-search form-select"
                                    id="model"
                                    id="select2-hide-search"
                                    data-placeholder="{{ __('localization.additional_equipment_edit_model_placeholder') }}"
                                >
                                    <option value=""></option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label">
                                    {{ __('localization.additional_equipment_edit_license_plate_label') }}
                                </label>
                                {{-- <input type="text" class="form-control" id="license_plate" required --}}
                                {{-- oninput="validateUkrainianDNZ(this,8)" --}}
                                {{-- style="text-transform: uppercase" placeholder="АА0000АА" --}}
                                {{-- value="{{$transportEquipment->license_plate}}"> --}}
                                <input
                                    type="text"
                                    class="form-control"
                                    id="license_plate"
                                    required
                                    style="text-transform: uppercase"
                                    placeholder=""
                                    value="{{ $transportEquipment->license_plate }}"
                                />
                            </div>
                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="select2-hide-search">
                                    {{ __('localization.additional_equipment_edit_type_label') }}
                                </label>
                                <select
                                    class="hide-search form-select"
                                    data-id="{{ $transportEquipment->type_id }}"
                                    data-dictionary="additional_equipment_type"
                                    id="type-equipment"
                                    data-placeholder="{{ __('localization.additional_equipment_edit_type_placeholder') }}"
                                >
                                    <option value=""></option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="select2-hide-search">
                                    {{ __('localization.additional_equipment_edit_company_label') }}
                                </label>
                                <select
                                    class="select2 form-select"
                                    id="company"
                                    data-id="{{ $transportEquipment->company_id }}"
                                    data-dictionary="company"
                                    data-placeholder="{{ __('localization.additional_equipment_edit_company_placeholder') }}"
                                >
                                    <option value=""></option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="select2-hide-search">
                                    {{ __('localization.additional_equipment_edit_default_transport_label') }}
                                </label>
                                <select
                                    class="select2 form-select hide-search"
                                    id="transport"
                                    data-placeholder="{{ __('localization.additional_equipment_edit_default_transport_placeholder') }}"
                                >
                                    <option value=""></option>
                                    @foreach ($transports as $transport)
                                        <option
                                            value="{{ $transport->id }}"
                                            {{ $transportEquipment->transport_id === $transport->id ? 'selected' : '' }}
                                        >
                                            {{ $transport->brand->name . ' ' . $transport->model->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="select2-hide-search">
                                    {{ __('localization.additional_equipment_edit_registration_country_label') }}
                                </label>
                                <select
                                    class="select2 form-select"
                                    data-id="{{ $transportEquipment->country_id }}"
                                    data-dictionary="country"
                                    id="country"
                                    data-placeholder="{{ __('localization.additional_equipment_edit_registration_country_placeholder') }}"
                                >
                                    <option value=""></option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="select2-hide-search">
                                    {{ __('localization.additional_equipment_edit_manufacture_year_label') }}
                                </label>
                                <select
                                    class="select2 form-select hide-search"
                                    id="manufacture_year"
                                    data-placeholder="{{ __('localization.additional_equipment_edit_manufacture_year_placeholder') }}"
                                >
                                    <option value=""></option>
                                    @for ($i=0;$i<=43;$i++)
                                        <option
                                            value="{{ 1980 + $i }}"
                                            {{ $transportEquipment->manufacture_year === 1980 + $i ? 'selected' : '' }}
                                        >
                                            {{ 1980 + $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div id="main-data-message"></div>
                        </div>
                    </div>
                    <!--/ form -->
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title fw-bolder">
                        {{ __('localization.additional_equipment_edit_characteristics_card_title') }}
                    </h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <x-form.select
                            id="download_method"
                            name="download_method"
                            label="localization.additional_equipment_edit_download_method_label"
                            placeholder="localization.additional_equipment_edit_download_method_placeholder"
                            multiple
                            data-dictionary="transport_download"
                            class="col-12 col-md-6 col-lg-4 mb-1"
                            :data-id="$transportEquipment->download_methods ?? []"
                        />

                        <div class="col-12 col-md-6 col-lg-4 mb-1">
                            <label class="form-label" for="select2-hide-search">
                                {{ __('localization.additional_equipment_edit_adr_label') }}
                            </label>
                            <select
                                class="select2 form-select hide-search"
                                id="adr"
                                data-id="{{ $transportEquipment->adr_id }}"
                                data-dictionary="adr"
                                data-placeholder="{{ __('localization.additional_equipment_edit_adr_placeholder') }}"
                            >
                                <option value=""></option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 mb-1">
                            <label class="form-label">
                                {{ __('localization.additional_equipment_edit_carrying_capacity_label') }}
                            </label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="carrying_capacity"
                                    placeholder="{{ __('localization.additional_equipment_edit_carrying_capacity_placeholder') }}"
                                    value="{{ $transportEquipment->carrying_capacity }}"
                                    oninput="maskFractionalNumbers(this,4)"
                                />
                                <span class="input-group-text">
                                    {{ __('localization.additional_equipment_edit_carrying_capacity_unit') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 mb-1">
                            <label class="form-label">
                                {{ __('localization.additional_equipment_edit_length_label') }}
                            </label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    value="{{ $transportEquipment->length }}"
                                    class="form-control"
                                    id="length"
                                    oninput="maskFractionalNumbers(this,3)"
                                />
                                <span class="input-group-text">
                                    {{ __('localization.additional_equipment_edit_length_unit') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 mb-1">
                            <label class="form-label">
                                {{ __('localization.additional_equipment_edit_width_label') }}
                            </label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    value="{{ $transportEquipment->width }}"
                                    class="form-control"
                                    id="width"
                                    oninput="maskFractionalNumbers(this,3)"
                                />
                                <span class="input-group-text">
                                    {{ __('localization.additional_equipment_edit_width_unit') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 mb-1">
                            <label class="form-label">
                                {{ __('localization.additional_equipment_edit_height_label') }}
                            </label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    value="{{ $transportEquipment->height }}"
                                    class="form-control"
                                    id="height"
                                    oninput="maskFractionalNumbers(this,3)"
                                />
                                <span class="input-group-text">
                                    {{ __('localization.additional_equipment_edit_height_unit') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 mb-1">
                            <label class="form-label">
                                {{ __('localization.additional_equipment_edit_volume_label') }}
                            </label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    value="{{ $transportEquipment->volume }}"
                                    class="form-control"
                                    id="volume"
                                    oninput="maskFractionalNumbers(this,4)"
                                />
                                <span class="input-group-text">
                                    {{ __('localization.additional_equipment_edit_volume_unit') }}
                                    <sup>3</sup>
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 mb-1">
                            <label class="form-label">
                                {{ __('localization.additional_equipment_edit_capacity_eu_label') }}
                            </label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    value="{{ $transportEquipment->capacity_eu }}"
                                    class="form-control"
                                    oninput="limitInputToNumbers(this,3)"
                                    id="capacity_eu"
                                />
                                <span class="input-group-text">
                                    {{ __('localization.additional_equipment_edit_capacity_eu_unit') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 mb-3 mb-md-1 mb-lg-3">
                            <label class="form-label">
                                {{ __('localization.additional_equipment_edit_capacity_am_label') }}
                            </label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    value="{{ $transportEquipment->capacity_am }}"
                                    class="form-control"
                                    oninput="limitInputToNumbers(this,3)"
                                    id="capacity_am"
                                />
                                <span class="input-group-text">
                                    {{ __('localization.additional_equipment_edit_capacity_am_unit') }}
                                </span>
                            </div>
                        </div>

                        <div
                            class="col-12 col-md-6 col-lg-4 mb-2 mb-md-1 d-flex align-items-center mb-lg-2"
                        >
                            <div class="form-check form-switch">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    id="hydroboard"
                                    {{ $transportEquipment->hydroboard ? 'checked' : '' }}
                                />
                                <label class="form-check-label" for="hydroboard">
                                    {{ __('localization.additional_equipment_edit_hydroboard_label') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="capacity-data-message"></div>
                </div>
            </div>
        </x-slot>
    </x-layout.container>

    <x-cancel-modal
        id="cancelEditPage"
        route="/transport-equipments/{{ $transportEquipment->id }}"
        title="{{ __('localization.additional_equipment_edit_cancel_modal_title') }}"
        content="{!! __('localization.additional_equipment_edit_cancel_modal_message') !!}"
        cancel-text="{{ __('localization.additional_equipment_edit_cancel_modal_cancel_button') }}"
        confirm-text="{{ __('localization.additional_equipment_edit_cancel_modal_confirm_button') }}"
    />
@endsection

@section('page-script')
    <script
        type="module"
        src="{{ asset('assets/js/entity/additional-equipment/additional-equipment.js') }}"
    ></script>
@endsection
