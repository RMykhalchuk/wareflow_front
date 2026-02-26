@extends('layouts.admin')
@section('title', __('localization.transport_create_title'))

@section('content')
    <x-layout.container id="data_tab_1">
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/transports',
                            'name' => __('localization.transport_create_transport'),
                        ],
                        [
                            'name' => __('localization.transport_create_register_vehicle'),
                        ],
                    ],
                ]
            )

            <div class="d-flex gap-1 align-self-end">
                <a class="btn btn-flat-secondary" href="{{ route('transports.index') }}">
                    {{ __('localization.transport_create_cancel') }}
                </a>
                <button id="save" type="button" class="btn btn-primary">
                    {{ __('localization.transport_create_save') }}
                </button>
            </div>
        </x-slot>

        <x-slot:slot>
            <x-page-title :title="__('localization.transport_create_register_vehicle')" />

            <x-card.nested>
                <x-slot:header>
                    <x-section-title>
                        {{ __('localization.transport_create_characteristics_title') }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    <x-form.avatar-uploader
                        id="image"
                        name="image"
                        :imageSrc="asset('assets/icons/entity/transport/default-truck-empty.svg')"
                        :disabled="null"
                    />

                    <x-form.select
                        id="mark"
                        name="mark"
                        label="localization.transport_create_auto_brand"
                        placeholder="localization.transport_create_auto_brand_placeholder"
                        data-dictionary="transport_brand"
                    />

                    <x-form.select
                        id="model"
                        name="model"
                        label="localization.transport_create_auto_model"
                        placeholder="localization.transport_create_auto_model_placeholder"
                        disabled
                    />

                    <x-form.input-text
                        id="license_plate"
                        name="license_plate"
                        label="localization.transport_create_license_plate"
                        placeholder="localization.transport_create_license_plate_placeholder"
                        data-msg="localization.transport_create_license_plate_msg"
                    />

                    <x-form.select
                        id="country"
                        name="country"
                        label="localization.transport_create_registration_country"
                        placeholder="localization.transport_create_registration_country_placeholder"
                        data-dictionary="country"
                    />

                    <x-form.select
                        id="category"
                        name="category"
                        label="localization.transport_create_category"
                        placeholder="localization.transport_create_category_placeholder"
                        data-dictionary="transport_kind"
                    />

                    <x-form.select
                        id="type"
                        name="type"
                        label="localization.transport_create_type"
                        placeholder="localization.transport_create_type_placeholder"
                        data-dictionary="transport_type"
                    />

                    <x-form.select
                        id="additional_equipment"
                        name="additional_equipment"
                        label="localization.transport_create_default_equipment"
                        placeholder="localization.transport_create_default_equipment_placeholder"
                    >
                        @foreach ($additionalEquipments as $equipment)
                            <option value="{{ $equipment->id }}">
                                {{ $equipment->brand->name . ' ' . $equipment->model->name }}
                            </option>
                        @endforeach
                    </x-form.select>

                    <x-form.select
                        id="manufacture_year"
                        name="manufacture_year"
                        label="localization.transport_create_manufacture_year"
                        placeholder="localization.transport_create_manufacture_year_placeholder"
                    >
                        @for ($i=0;$i<=43;$i++)
                            <option value="{{ 1980 + $i }}">{{ 1980 + $i }}</option>
                        @endfor
                    </x-form.select>

                    <x-ui.section-divider />

                    <x-form.select
                        id="company"
                        name="company"
                        label="localization.transport_create_company"
                        placeholder="localization.transport_create_company_placeholder"
                        data-dictionary="company"
                    />

                    <x-form.select
                        id="driver"
                        name="driver"
                        label="localization.transport_create_default_driver"
                        placeholder="localization.transport_create_default_driver_placeholder"
                        data-dictionary="driver"
                    />

                    <x-form.input-text-with-unit
                        id="spending_empty"
                        name="spending_empty"
                        label="localization.transport_create_empty_consumption"
                        placeholder="localization.transport_create_empty_consumption_placeholder"
                        unit="localization.transport_create_consumption_unit"
                        oninput="maskFractionalNumbers(this, 3)"
                    />

                    <x-form.input-text-with-unit
                        id="spending_full"
                        name="spending_full"
                        label="localization.transport_create_full_consumption"
                        placeholder="localization.transport_create_full_consumption_placeholder"
                        unit="localization.transport_create_consumption_unit"
                        oninput="maskFractionalNumbers(this, 3)"
                    />

                    <x-form.input-text-with-unit
                        id="weight"
                        name="weight"
                        label="localization.transport_create_weight"
                        placeholder="localization.transport_create_weight_placeholder"
                        unit="localization.transport_create_weight_unit"
                        oninput="maskFractionalNumbers(this, 6)"
                    />

                    <div id="main-data-message"></div>
                </x-slot>
            </x-card.nested>

            <x-card.nested id="additional-data" style="display: none">
                <x-slot:header>
                    <x-section-title>
                        {{ __('localization.transport_create_characteristics_body_title') }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    <x-form.input-group-wrapper wrapperClass="col-12 px-0 mb-0">
                        <x-form.select
                            id="download_methods"
                            name="download_methods"
                            label="localization.transport_create_loading_method"
                            placeholder="localization.transport_create_loading_method_placeholder"
                            data-dictionary="transport_download"
                            multiple
                            class="col-4 mb-1"
                        />

                        <x-form.select
                            id="adr"
                            name="adr"
                            label="localization.transport_create_adr"
                            placeholder="localization.transport_create_adr_placeholder"
                            data-dictionary="adr"
                            class="col-4 mb-1"
                        />

                        <x-form.input-text-with-unit
                            id="carrying_capacity"
                            name="carrying_capacity"
                            label="localization.transport_create_carrying_capacity"
                            placeholder="localization.transport_create_carrying_capacity_placeholder"
                            unit="localization.transport_create_carrying_capacity_unit"
                            oninput="maskFractionalNumbers(this,4)"
                            class="col-4 mb-1"
                        />

                        <x-form.input-text-with-unit
                            id="length"
                            name="length"
                            label="localization.transport_create_length"
                            placeholder="localization.transport_create_length_placeholder"
                            unit="localization.transport_create_length_unit"
                            oninput="maskFractionalNumbers(this,3)"
                            class="col-4 mb-1"
                        />

                        <x-form.input-text-with-unit
                            id="width"
                            name="width"
                            label="localization.transport_create_width"
                            placeholder="localization.transport_create_width_placeholder"
                            unit="localization.transport_create_width_unit"
                            oninput="maskFractionalNumbers(this,3)"
                            class="col-4 mb-1"
                        />

                        <x-form.input-text-with-unit
                            id="height"
                            name="height"
                            label="localization.transport_create_height"
                            placeholder="localization.transport_create_height_placeholder"
                            unit="localization.transport_create_height_unit"
                            oninput="maskFractionalNumbers(this,3)"
                            class="col-4 mb-1"
                        />

                        <x-form.input-text-with-unit
                            id="volume"
                            name="volume"
                            label="localization.transport_create_volume"
                            placeholder="localization.transport_create_volume_placeholder"
                            isUnitHtml="true"
                            unit="localization.transport_create_volume_unit"
                            oninput="maskFractionalNumbers(this,4)"
                            class="col-4 mb-1"
                        />

                        <x-form.input-text-with-unit
                            id="capacity_eu"
                            name="capacity_eu"
                            label="localization.transport_create_capacity_eu"
                            placeholder="localization.transport_create_capacity_eu_placeholder"
                            unit="localization.transport_create_capacity_eu_unit"
                            oninput="limitInputToNumbers(this,3)"
                            class="col-4 mb-1"
                        />

                        <x-form.input-text-with-unit
                            id="capacity_am"
                            name="capacity_am"
                            label="localization.transport_create_capacity_am"
                            placeholder="localization.transport_create_capacity_am_placeholder"
                            unit="localization.transport_create_capacity_am_unit"
                            oninput="limitInputToNumbers(this,3)"
                            class="col-4 mb-1"
                        />

                        <x-form.switch
                            id="hydroboard"
                            name="hydroboard"
                            label="localization.transport_create_hydroboard"
                            class="col-4 mb-1"
                            :checked="false"
                        />

                        <div id="capacity-data-message"></div>
                    </x-form.input-group-wrapper>
                </x-slot>
            </x-card.nested>
        </x-slot>
    </x-layout.container>
@endsection

@section('page-script')
    <script type="module" src="{{ asset('assets/js/entity/transport/transport.js') }}"></script>
@endsection
