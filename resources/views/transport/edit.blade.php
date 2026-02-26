@extends('layouts.admin')
@section('title', __('localization.transport_edit_title'))

@section('content')
    <x-layout.container id="data_tab_1" data-id="{{ $transport->id }}">
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/transports',
                            'name' => __('localization.transport_edit_breadcrumb_transport'),
                        ],
                        [
                            'url' => '/transports/' . $transport->id,
                            'name' => __('localization.transport_edit_breadcrumb_view_transport', [
                                'transport_brand' => $transport->brand->name,
                                'transport_model' => $transport->model->name,
                                'license_plate' => $transport->license_plate,
                            ]),
                        ],
                        [
                            'name' => __('localization.transport_edit_breadcrumb_editing_vehicle'),
                        ],
                    ],
                ]
            )

            <div class="d-flex gap-1 align-self-end">
                <button
                    data-bs-toggle="modal"
                    id="cancel_button"
                    data-bs-target="#cancel_edit_transport"
                    type="submit"
                    class="btn btn-flat-secondary"
                >
                    {{ __('localization.transport_edit_cancel_button') }}
                </button>
                <button id="update" type="button" class="btn btn-primary">
                    {{ __('localization.transport_edit_save_button') }}
                </button>
            </div>
        </x-slot>

        <x-slot:slot>
            <x-page-title :title="__('localization.transport_edit_breadcrumb_editing_vehicle')" />

            <x-card.nested>
                <x-slot:header>
                    <x-section-title>
                        {{ __('localization.transport_edit_characteristics_title') }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    <x-form.avatar-uploader
                        id="image"
                        name="image"
                        :imageSrc="$transport && $transport->img_type ? asset('files/uploads/transport/' . $transport->id . '.' . $transport->img_type) : asset('assets/icons/entity/transport/default-truck-empty.svg')"
                        :disabled="null"
                    />

                    <x-form.select
                        id="mark"
                        name="mark"
                        label="localization.transport_edit_mark"
                        placeholder="localization.transport_edit_mark_placeholder"
                        data-dictionary="transport_brand"
                        data-id="{{ $transport->brand_id }}"
                    />

                    <x-form.select
                        id="model"
                        name="model"
                        label="localization.transport_edit_model"
                        placeholder="localization.transport_edit_model_placeholder"
                    />

                    <x-form.input-text
                        id="license_plate"
                        name="license_plate"
                        label="localization.transport_edit_license_plate"
                        placeholder="localization.transport_edit_license_plate_placeholder"
                        value="{{ $transport->license_plate }}"
                        style="text-transform: uppercase"
                        :required
                    />

                    <x-form.select
                        id="country"
                        name="country"
                        label="localization.transport_edit_registration_country"
                        placeholder="localization.transport_edit_registration_country_placeholder"
                        data-dictionary="country"
                        data-id="{{ $transport->registration_country_id }}"
                    />

                    <x-form.select
                        id="type"
                        name="type"
                        label="localization.transport_edit_type"
                        placeholder="localization.transport_edit_type_placeholder"
                        data-dictionary="transport_type"
                        data-id="{{ $transport->type_id }}"
                    />

                    <x-form.select
                        id="category"
                        name="category"
                        label="localization.transport_edit_category"
                        placeholder="localization.transport_edit_category_placeholder"
                        data-dictionary="transport_kind"
                        data-id="{{ $transport->category_id }}"
                    />

                    <x-form.select
                        id="additional_equipment"
                        name="additional_equipment"
                        label="localization.transport_edit_additional_equipment"
                        placeholder="localization.transport_edit_additional_equipment_placeholder"
                    >
                        @foreach ($additionalEquipments as $equipment)
                            <option
                                value="{{ $equipment->id }}"
                                {{ $transport->equipment_id === $equipment->id ? 'selected' : '' }}
                            >
                                {{ $equipment->brand->name . ' ' . $equipment->model->name }}
                            </option>
                        @endforeach
                    </x-form.select>

                    <x-form.select
                        id="manufacture_year"
                        name="manufacture_year"
                        label="localization.transport_edit_manufacture_year"
                        placeholder="localization.transport_edit_manufacture_year_placeholder"
                    >
                        @for ($i=0;$i<=43;$i++)
                            <option
                                value="{{ 1980 + $i }}"
                                {{ $transport->manufacture_year === 1980 + $i ? 'selected' : '' }}
                            >
                                {{ 1980 + $i }}
                            </option>
                        @endfor
                    </x-form.select>

                    <x-ui.section-divider />

                    <x-form.select
                        id="company"
                        name="company"
                        label="localization.transport_edit_company"
                        placeholder="localization.transport_edit_company_placeholder"
                        data-dictionary="company"
                        data-id="{{ $transport->company_id }}"
                    />

                    <x-form.select
                        id="driver"
                        name="driver"
                        label="localization.transport_edit_default_driver"
                        placeholder="localization.transport_edit_default_driver_placeholder"
                        data-dictionary="driver"
                        data-id="{{ $transport->driver_id }}"
                    />

                    <x-form.input-text-with-unit
                        id="spending_empty"
                        name="spending_empty"
                        label="localization.transport_edit_empty_consumption"
                        placeholder="localization.transport_edit_empty_consumption_placeholder"
                        value="{{ $transport->spending_empty }}"
                        unit="localization.transport_edit_consumption_unit"
                        oninput="maskFractionalNumbers(this, 3)"
                    />

                    <x-form.input-text-with-unit
                        id="spending_full"
                        name="spending_full"
                        label="localization.transport_edit_full_consumption"
                        placeholder="localization.transport_edit_full_consumption_placeholder"
                        value="{{ $transport->spending_full }}"
                        unit="localization.transport_edit_consumption_unit"
                        oninput="maskFractionalNumbers(this, 3)"
                    />

                    <x-form.input-text-with-unit
                        id="weight"
                        name="weight"
                        label="localization.transport_edit_weight"
                        placeholder="localization.transport_edit_weight_placeholder"
                        value="{{ $transport->weight }}"
                        unit="localization.transport_edit_weight_unit"
                        oninput="maskFractionalNumbers(this, 6)"
                    />

                    <div id="main-data-message"></div>
                </x-slot>
            </x-card.nested>

            <x-card.nested
                id="additional-data"
                style="display: none"
                style="{{ $transport->category_id !== 2 ? 'display: none' : '' }}"
            >
                <x-slot:header>
                    <x-section-title>
                        {{ __('localization.transport_edit_characteristics_body_title') }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    <x-form.input-group-wrapper wrapperClass="col-12 px-0 mb-0">
                        <x-form.select
                            id="download_methods"
                            name="download_methods[]"
                            label="localization.transport_edit_loading_method"
                            placeholder="localization.transport_edit_loading_method_placeholder"
                            data-dictionary="transport_download"
                            data-id="@json($transport->download_methods)"
                            multiple
                            class="col-4 mb-1"
                        />

                        <x-form.select
                            id="adr"
                            name="adr"
                            label="localization.transport_edit_adr"
                            placeholder="localization.transport_edit_adr_placeholder"
                            data-dictionary="adr"
                            data-id="{{ $transport->adr_id }}"
                            class="col-4 mb-1"
                        />

                        <x-form.input-text-with-unit
                            id="carrying_capacity"
                            name="carrying_capacity"
                            label="localization.transport_edit_carrying_capacity"
                            placeholder="localization.transport_edit_carrying_capacity_placeholder"
                            value="{{ $transport->carrying_capacity }}"
                            unit="localization.transport_edit_carrying_capacity_unit"
                            class="col-4 mb-1"
                        />

                        <x-form.input-text-with-unit
                            id="length"
                            name="length"
                            label="localization.transport_edit_length"
                            placeholder="localization.transport_edit_length_placeholder"
                            value="{{ $transport->length }}"
                            unit="localization.transport_edit_length_unit"
                            class="col-4 mb-1"
                        />

                        <x-form.input-text-with-unit
                            id="width"
                            name="width"
                            label="localization.transport_edit_width"
                            placeholder="localization.transport_edit_width_placeholder"
                            value="{{ $transport->width }}"
                            unit="localization.transport_edit_width_unit"
                            class="col-4 mb-1"
                        />

                        <x-form.input-text-with-unit
                            id="height"
                            name="height"
                            label="localization.transport_edit_height"
                            placeholder="localization.transport_edit_height_placeholder"
                            value="{{ $transport->height }}"
                            unit="localization.transport_edit_height_unit"
                            class="col-4 mb-1"
                        />

                        <x-form.input-text-with-unit
                            id="volume"
                            name="volume"
                            label="localization.transport_edit_volume"
                            placeholder="localization.transport_edit_volume_placeholder"
                            value="{{ $transport->volume }}"
                            unit="localization.transport_edit_volume_unit"
                            class="col-4 mb-1"
                        />

                        <x-form.input-text-with-unit
                            id="capacity_eu"
                            name="capacity_eu"
                            label="localization.transport_edit_capacity_eu"
                            placeholder="localization.transport_edit_capacity_eu_placeholder"
                            value="{{ $transport->capacity_eu }}"
                            unit="localization.transport_edit_capacity_eu_unit"
                            class="col-4 mb-1"
                        />

                        <x-form.input-text-with-unit
                            id="capacity_am"
                            name="capacity_am"
                            label="localization.transport_edit_capacity_am"
                            placeholder="localization.transport_edit_capacity_am_placeholder"
                            value="{{ $transport->capacity_am }}"
                            unit="localization.transport_edit_capacity_am_unit"
                            class="col-4 mb-1"
                        />

                        <x-form.switch
                            id="hydroboard"
                            name="hydroboard"
                            label="localization.transport_edit_hydroboard"
                            checked="{{ $transport->hydroboard }}"
                            class="col-4 mb-1"
                        />

                        <div id="capacity-data-message"></div>
                    </x-form.input-group-wrapper>
                </x-slot>
            </x-card.nested>
        </x-slot>
    </x-layout.container>

    <x-cancel-modal
        id="cancel_edit_transport"
        route="/transports/{{ $transport->id }}"
        title="{{ __('localization.transport_edit_cancel_popup_title') }}"
        content="{!! __('localization.transport_edit_cancel_popup_message') !!}"
        cancel-text="{{ __('localization.transport_edit_cancel_popup_cancel_button') }}"
        confirm-text="{{ __('localization.transport_edit_cancel_popup_confirm_button') }}"
    />
@endsection

@section('page-script')
    <script type="module" src="{{ asset('assets/js/entity/transport/transport.js') }}"></script>
@endsection
