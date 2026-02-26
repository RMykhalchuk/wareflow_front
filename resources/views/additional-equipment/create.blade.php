@extends('layouts.admin')
@section('title', __('localization.additional_equipment_create_title'))

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
                                'localization.additional_equipment_create_breadcrumb_additional_equipment',
                            ),
                        ],
                        [
                            'name' => __('localization.additional_equipment_create_breadcrumb_registration'),
                        ],
                    ],
                ]
            )

            <div class="d-flex gap-1 align-self-end">
                <a class="btn btn-flat-secondary" href="{{ '/transport-equipments' }}">
                    {{ __('localization.additional_equipment_create_cancel_button') }}
                </a>
                <button id="save" type="button" class="btn btn-primary">
                    {{ __('localization.additional_equipment_create_save_button') }}
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
                        {{ __('localization.additional_equipment_create_card_title') }}
                    </h4>
                </div>
                <div class="card-body my-25">
                    <!-- header section -->
                    <x-form.avatar-uploader
                        id="image"
                        name="image"
                        :imageSrc="asset('assets/icons/entity/additional-equipment/default-truck-empty.svg')"
                        :disabled="null"
                    />
                    <!--/ header section -->
                    <div class="mt-0.5 pt-50">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="select2-hide-search">
                                    {{ __('localization.additional_equipment_create_brand_label') }}
                                </label>
                                <select
                                    class="hide-search form-select"
                                    id="mark-equipment"
                                    data-dictionary="additional_equipment_brand"
                                    data-placeholder="{{ __('localization.additional_equipment_create_brand_placeholder') }}"
                                >
                                    <option value=""></option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="select2-hide-search">
                                    {{ __('localization.additional_equipment_create_model_label') }}
                                </label>
                                <select
                                    class="hide-search form-select"
                                    id="model"
                                    disabled
                                    data-placeholder="{{ __('localization.additional_equipment_create_model_placeholder') }}"
                                >
                                    <option value=""></option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label">
                                    {{ __('localization.additional_equipment_create_license_plate_label') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="license_plate"
                                    required
                                    placeholder=""
                                />
                                {{-- <input type="text" class="form-control" id="license_plate" --}}
                                {{-- required oninput="validateUkrainianDNZ(this,8)" --}}
                                {{-- placeholder="АА0000АА"> --}}
                            </div>
                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="select2-hide-search">
                                    {{ __('localization.additional_equipment_create_type_label') }}
                                </label>
                                <select
                                    class="hide-search form-select"
                                    id="type-equipment"
                                    data-dictionary="additional_equipment_type"
                                    data-placeholder="{{ __('localization.additional_equipment_create_type_placeholder') }}"
                                >
                                    <option value=""></option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="select2-hide-search">
                                    {{ __('localization.additional_equipment_create_company_label') }}
                                </label>
                                <select
                                    class="select2 form-select hide-search"
                                    id="company"
                                    data-dictionary="company"
                                    data-placeholder="{{ __('localization.additional_equipment_create_company_placeholder') }}"
                                >
                                    <option value=""></option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="select2-hide-search">
                                    {{ __('localization.additional_equipment_create_default_transport_label') }}
                                </label>
                                <select
                                    class="select2 form-select hide-search"
                                    id="transport"
                                    data-placeholder="{{ __('localization.additional_equipment_create_default_transport_placeholder') }}"
                                >
                                    <option value=""></option>
                                    @foreach ($transports as $transport)
                                        <option value="{{ $transport->id }}">
                                            {{ $transport->brand?->name . ' ' . $transport->model?->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="select2-hide-search">
                                    {{ __('localization.additional_equipment_create_registration_country_label') }}
                                </label>
                                <select
                                    class="select2 form-select"
                                    id="country"
                                    id="select2-hide-search"
                                    data-dictionary="country"
                                    data-placeholder="{{ __('localization.additional_equipment_create_registration_country_placeholder') }}"
                                >
                                    <option value=""></option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="select2-hide-search">
                                    {{ __('localization.additional_equipment_create_manufacture_year_label') }}
                                </label>
                                <select
                                    class="select2 form-select hide-search"
                                    id="manufacture_year"
                                    data-placeholder="{{ __('localization.additional_equipment_create_manufacture_year_placeholder') }}"
                                >
                                    <option value=""></option>
                                    @for ($i = 0; $i <= 43; $i++)
                                        <option value="{{ 1980 + $i }}">{{ 1980 + $i }}</option>
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
                        {{ __('localization.additional_equipment_create_characteristics_title') }}
                    </h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-4 mb-1">
                            <label class="form-label" for="select2-hide-search">
                                {{ __('localization.additional_equipment_create_loading_method_label') }}
                            </label>
                            <select
                                class="select2 form-select hide-search"
                                multiple
                                id="download_method"
                                data-dictionary="transport_download"
                                data-placeholder="{{ __('localization.additional_equipment_create_loading_method_placeholder') }}"
                            >
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 mb-1">
                            <label class="form-label" for="select2-hide-search">
                                {{ __('localization.additional_equipment_create_adr_label') }}
                            </label>
                            <select
                                class="select2 form-select hide-search"
                                id="adr"
                                data-dictionary="adr"
                                data-placeholder="{{ __('localization.additional_equipment_create_adr_placeholder') }}"
                            >
                                <option value=""></option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 mb-1">
                            <label class="form-label">
                                {{ __('localization.additional_equipment_create_carrying_capacity_label') }}
                            </label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="carrying_capacity"
                                    placeholder="{{ __('localization.additional_equipment_create_carrying_capacity_placeholder') }}"
                                    oninput="maskFractionalNumbers(this,4)"
                                />
                                <span class="input-group-text">
                                    {{ __('localization.additional_equipment_create_ton') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 mb-1">
                            <label class="form-label">
                                {{ __('localization.additional_equipment_create_length_label') }}
                            </label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="length"
                                    placeholder="{{ __('localization.additional_equipment_create_length_placeholder') }}"
                                    oninput="maskFractionalNumbers(this,3)"
                                />
                                <span class="input-group-text">
                                    {{ __('localization.additional_equipment_create_meter') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 mb-1">
                            <label class="form-label">
                                {{ __('localization.additional_equipment_create_width_label') }}
                            </label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="width"
                                    placeholder="{{ __('localization.additional_equipment_create_width_placeholder') }}"
                                    oninput="maskFractionalNumbers(this,3)"
                                />
                                <span class="input-group-text">
                                    {{ __('localization.additional_equipment_create_meter') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 mb-1">
                            <label class="form-label">
                                {{ __('localization.additional_equipment_create_height_label') }}
                            </label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="height"
                                    placeholder="{{ __('localization.additional_equipment_create_height_placeholder') }}"
                                    oninput="maskFractionalNumbers(this,3)"
                                />
                                <span class="input-group-text">
                                    {{ __('localization.additional_equipment_create_meter') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 mb-1">
                            <label class="form-label">
                                {{ __('localization.additional_equipment_create_volume_label') }}
                            </label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="volume"
                                    placeholder="{{ __('localization.additional_equipment_create_volume_placeholder') }}"
                                    oninput="maskFractionalNumbers(this,4)"
                                />
                                <span class="input-group-text">
                                    М
                                    <sup>3</sup>
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 mb-1">
                            <label class="form-label">
                                {{ __('localization.additional_equipment_create_capacity_eu_label') }}
                            </label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="capacity_eu"
                                    placeholder="{{ __('localization.additional_equipment_create_capacity_eu_placeholder') }}"
                                    oninput="limitInputToNumbers(this,3)"
                                />
                                <span class="input-group-text">
                                    {{ __('localization.additional_equipment_create_pallet') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 mb-3 mb-md-1 mb-lg-3">
                            <label class="form-label">
                                {{ __('localization.additional_equipment_create_capacity_am_label') }}
                            </label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="capacity_am"
                                    placeholder="{{ __('localization.additional_equipment_create_capacity_am_placeholder') }}"
                                    oninput="limitInputToNumbers(this,3)"
                                />
                                <span class="input-group-text">
                                    {{ __('localization.additional_equipment_create_pallet') }}
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
                                    checked
                                />
                                <label class="form-check-label" for="hydroboard">
                                    {{ __('localization.additional_equipment_create_hydroboard_label') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="capacity-data-message"></div>
                </div>
            </div>
        </x-slot>
    </x-layout.container>
@endsection

@section('page-script')
    <script
        type="module"
        src="{{ asset('assets/js/entity/additional-equipment/additional-equipment.js') }}"
    ></script>
@endsection
