@extends('layouts.admin')
@section('title', __('localization.sku_edit_title'))

@section('page-style')
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

@section('content')
    <x-layout.container>
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        [
                            'url' => '/sku',
                            'name' => __('localization.sku_edit_breadcrumb_products'),
                        ],
                        [
                            'url' => '/sku/' . $sku->id,
                            'name' => __('localization.sku_edit_breadcrumb_view_product', [
                                'name' => $sku->name,
                            ]),
                        ],
                        [
                            'name' => __('localization.sku_edit_breadcrumb_edit_product', [
                                'name' => $sku->name,
                            ]),
                        ],
                    ],
                ]
            )

            <x-ui.header-actions>
                <x-modal.modal-trigger-button
                    id="cancel_button"
                    target="cancel_button_modal"
                    class="btn btn-flat-secondary"
                    icon="x"
                    iconStyle="mr-0"
                />
            </x-ui.header-actions>
        </x-slot>

        <x-slot:slot>
            <x-page-title
                :title="__('localization.sku_edit_breadcrumb_edit_product', ['name' => $sku->name])"
            />

            <x-card.nested>
                <x-slot:header>
                    <x-section-title>
                        {{ __('localization.sku_edit_main_data_title') }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    <x-form.avatar-uploader
                        id="logo"
                        name="logo"
                        :imageSrc="$sku && $sku->img_type ? asset('files/uploads/goods/' . $sku->id . '.' . $sku->img_type) : asset('assets/icons/entity/goods/avatar-default.svg')"
                        :disabled="null"
                    />

                    <x-form.input-text
                        id="name"
                        name="name"
                        label="localization.sku_edit_name_label"
                        placeholder="localization.sku_edit_name_placeholder"
                        value="{{ $sku->name }}"
                    />

                    @php
                        $barcodeValues = collect($sku->barcodes)
                            ->pluck('barcode')
                            ->map(fn ($v) => (string) $v)
                            ->toArray();
                    @endphp

                    <x-form.tag-input
                        id="barcodes"
                        name="barcodes"
                        label="{{ __('localization.sku_create_barcode') }}"
                        placeholder="{{ __('localization.sku_create_enter_barcode') }}"
                        type="number"
                        oninput="limitInputToNumbers(this,30)"
                        :tags="json_encode($barcodeValues)"
                    />

                    <x-form.select
                        id="category_id"
                        name="category_id"
                        label="localization.sku_edit_category_label"
                        placeholder="localization.sku_edit_category_placeholder"
                        data-dictionary="category"
                        data-id="{{ $sku->category_id }}"
                    />

                    <x-form.select
                        id="brand"
                        name="brand"
                        label="{{ __('localization.sku_create_brand') }}"
                        placeholder="{{ __('localization.sku_create_enter_brand') }}"
                        {{-- value="{{ $sku->brand }}" --}}
                        data-id="{{ $sku->brand }}"
                        data-dictionary="company"
                    />

                    <x-form.select
                        id="provider"
                        name="provider"
                        label="localization.sku_create_provider"
                        placeholder="localization.sku_create_provider_place"
                        {{-- value="{{ $sku->provider }}" --}}
                        data-id="{{ $sku->provider }}"
                        data-dictionary="company"
                    />

                    <div class="col-6 px-0 mb-1"></div>

                    <x-form.select
                        id="manufacturer"
                        name="manufacturer"
                        label="localization.sku_create_manufacturer"
                        placeholder="localization.sku_create_select_manufacturer"
                        {{-- value="{{ $sku->manufacturer }}" --}}
                        data-id="{{ $sku->manufacturer}}"
                        data-dictionary="company"
                    />

                    <x-form.select
                        id="manufacturer_country_id"
                        name="manufacturer_country_id"
                        label="localization.sku_edit_producer_country_label"
                        placeholder="localization.sku_edit_producer_country_placeholder"
                        data-dictionary="country"
                        data-id="{{ $sku->manufacturer_country_id }}"
                    />

                    @php
                        $expirationValues = collect($sku->expiration_date ?? [])
                            ->map(function ($v) {
                                preg_match('/\d+/', $v, $matches);
                                return $matches[0] ?? null;
                            })
                            ->filter() // прибирає null
                            ->values()
                            ->toArray();
                    @endphp

                    <x-form.tag-input
                        id="expiration_date"
                        label="{{ __('localization.sku_create_duration') }}"
                        placeholder="{{ __('localization.sku_create_enter_duration') }}"
                        name="expiration_date"
                        unit="day"
                        type="number"
                        :tags="json_encode($expirationValues)"
                    />

                    <div class="col-6 px-0 mb-1"></div>

                    <x-form.switch
                        id="is_batch_accounting"
                        name="is_batch_accounting"
                        label="localization.sku_create_party_accounting"
                        :checked="(bool) $sku->is_batch_accounting"
                    />

                    <div class="col-6 px-0 mb-1"></div>

                    <x-form.switch
                        id="is_weight"
                        name="is_weight"
                        label="localization.sku_create_weight_goods"
                        :checked="(bool) $sku->is_weight"
                    />

                    <div id="basic-data-message" class="mt-1"></div>
                </x-slot>
            </x-card.nested>

            <x-card.nested>
                <x-slot:header>
                    <x-section-title>
                        {{ __('localization.sku_edit_parameters_title') }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    <x-form.select
                        id="measurement_unit_id"
                        name="measurement_unit_id"
                        label="localization.sku_edit_measurement_unit_label"
                        placeholder="localization.sku_edit_measurement_unit_placeholder"
                        data-dictionary="measurement_unit"
                        data-id="{{ $sku->measurement_unit_id }}"
                        data-unit-text="{{ $sku->measurement_unit?->key ? __('localization.measurement_unit_' . $sku->measurement_unit->key) : '' }}"
                    />

                    <x-form.input-group-wrapper wrapperClass="col-12 col-md-6 px-0 mb-1">
                        <x-form.input-text-with-unit
                            id="height"
                            name="height"
                            label="localization.sku_edit_height_label"
                            placeholder="localization.sku_edit_height_placeholder"
                            unit="localization.sku_edit_dimension_unit"
                            oninput="maskFractionalNumbers(this,4)"
                            class="col-12 col-md-4 mb-1 mb-md-0"
                            value="{{ $sku->height }}"
                        />

                        <x-form.input-text-with-unit
                            id="width"
                            name="width"
                            label="localization.sku_edit_width_label"
                            placeholder="localization.sku_edit_width_placeholder"
                            unit="localization.sku_edit_dimension_unit"
                            oninput="maskFractionalNumbers(this,4)"
                            value="{{ $sku->width }}"
                            class="col-12 col-md-4 mb-1 mb-md-0"
                        />

                        <x-form.input-text-with-unit
                            id="length"
                            name="length"
                            label="localization.sku_edit_depth_label"
                            placeholder="localization.sku_edit_depth_placeholder"
                            unit="localization.sku_edit_dimension_unit"
                            oninput="maskFractionalNumbers(this,4)"
                            value="{{ $sku->length }}"
                            class="col-12 col-md-4 mb-1 mb-md-0"
                        />
                    </x-form.input-group-wrapper>

                    <x-form.input-text-with-unit
                        id="weight_netto"
                        name="weight_netto"
                        label="localization.sku_edit_net_weight_label"
                        placeholder="localization.sku_edit_net_weight_placeholder"
                        unit="localization.sku_edit_weight_unit"
                        oninput="maskFractionalNumbers(this,5)"
                        value="{{ $sku->weight_netto }}"
                    />

                    <x-form.input-text-with-unit
                        id="weight_brutto"
                        name="weight_brutto"
                        label="localization.sku_edit_gross_weight_label"
                        placeholder="localization.sku_edit_gross_weight_placeholder"
                        unit="localization.sku_edit_weight_unit"
                        oninput="maskFractionalNumbers(this,5)"
                        value="{{ $sku->weight_brutto }}"
                    />

                    <div id="parameters-message" class="mt-1"></div>
                </x-slot>
            </x-card.nested>

            <x-card.nested>
                <x-slot:header>
                    <x-section-title>
                        {{ __('localization.sku_edit_tabs_packaging') }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    <div class="table-responsive" id="table-container"></div>

                    <div class="px-1 mt-1" id="packing-control-block">
                        <x-modal.modal-trigger-button
                            id="add_paking_button"
                            target="add_paking"
                            class="btn btn-outline-secondary w-100 d-none"
                            :text="__('localization.sku_create_add_paking_add_button')"
                            icon="plus"
                        />

                        <div
                            id="unit_sku_error"
                            class="rounded d-flex font-small-3 fw-normal justify-content-center text-center bg-secondary-subtle w-100 text-secondary p-1"
                        >
                            {{ __('localization.sku_create_unit_sku_error') }}
                        </div>
                    </div>

                    <div id="pak-message" class="mt-1"></div>
                </x-slot>
            </x-card.nested>

            <x-card.nested
                {{-- :headerAttributes="['class' => 'bg-primary text-white', 'id' => 'header-block']" --}}
                :bodyAttributes="['id' => 'barcode-body', 'style' => 'display: none;']"
            >
                <x-slot:header>
                    <div class="d-flex align-items-center">
                        <x-section-title>
                            {{ __('localization.sku_edit_tabs_barcode') }}
                        </x-section-title>

                        @php
                            $hasStorageConditions =
                                $sku->temp_from ||
                                $sku->temp_to ||
                                $sku->humidity_from ||
                                $sku->humidity_to ||
                                $sku->dustiness_from ||
                                $sku->dustiness_to;
                        @endphp

                        <div class="form-check form-switch mt-0">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="storage-conditions"
                                name="storage-conditions"
                                {{ $hasStorageConditions ? 'checked' : '' }}
                            />
                        </div>
                    </div>
                </x-slot>

                <x-slot:body>
                    @php
                        $hasTemp = ! is_null($sku->temp_from) || ! is_null($sku->temp_to);
                        $hasHumidity = ! is_null($sku->humidity_from) || ! is_null($sku->humidity_to);
                        $hasDustiness = ! is_null($sku->dustiness_from) || ! is_null($sku->dustiness_to);
                    @endphp

                    <x-form.select
                        id="adr_id"
                        name="adr_id"
                        label="localization.sku_edit_adr_label"
                        placeholder="localization.sku_edit_adr_placeholder"
                        data-dictionary="adr"
                        data-id="{{ $sku->adr_id }}"
                    />

                    <div class="col-6 px-0 mb-1"></div>

                    <x-form.switch
                        id="temp_regime"
                        name="temp_regime"
                        label="localization.sku_edit_temperature_regime_label"
                        :checked="$hasTemp"
                    />

                    <div class="col-6 px-0 mb-1"></div>

                    <x-form.input-group-wrapper wrapperClass="col-6 px-0 mb-0">
                        <x-form.input-text-with-unit
                            id="temp_from"
                            name="temp_from"
                            placeholder="localization.sku_edit_temperature_from_placeholder"
                            unit="&#8451;"
                            value="{{ $sku->temp_from }}"
                            isUnitHtml="true"
                            oninput="maskDecimalNumber(this,2,1)"
                        />

                        <x-form.input-text-with-unit
                            id="temp_to"
                            name="temp_to"
                            placeholder="localization.sku_edit_temperature_to_placeholder"
                            unit="&#8451;"
                            value="{{ $sku->temp_to }}"
                            isUnitHtml="true"
                            oninput="maskDecimalNumber(this,2,1)"
                        />
                    </x-form.input-group-wrapper>

                    <div class="col-6 px-0 mb-1"></div>

                    <x-form.switch
                        id="humidity"
                        name="humidity"
                        label="{{ __('localization.sku_create_humidity') }}"
                        :checked="$hasHumidity"
                    />

                    <div class="col-6 px-0 mb-0"></div>

                    <x-form.input-group-wrapper wrapperClass="col-6 px-0 mb-0">
                        <x-form.input-text-with-unit
                            id="humidity_from"
                            name="humidity_from"
                            placeholder="localization.sku_create_temperature_from"
                            unit="%"
                            value="{{ $sku->humidity_from }}"
                            isUnitHtml="true"
                            oninput="maskDecimalNumber(this,2,1)"
                        />

                        <x-form.input-text-with-unit
                            id="humidity_to"
                            name="humidity_to"
                            placeholder="localization.sku_create_temperature_to"
                            unit="%"
                            value="{{ $sku->humidity_to }}"
                            isUnitHtml="true"
                            oninput="maskDecimalNumber(this,2,1)"
                        />
                    </x-form.input-group-wrapper>

                    <div class="col-6 px-0 mb-1"></div>

                    <x-form.switch
                        id="dustiness"
                        name="dustiness"
                        label="{{ __('localization.sku_create_dustiness_air') }}"
                        :checked="$hasDustiness"
                    />

                    <div class="col-6 px-0 mb-0"></div>

                    <x-form.input-group-wrapper wrapperClass="col-6 px-0 mb-0">
                        <x-form.input-text-with-unit
                            id="dustiness_from"
                            name="dustiness_from"
                            placeholder="{{ __('localization.sku_create_temperature_from') }}"
                            unit="{{ __('localization.sku_create_dustiness_air_unit') }}"
                            value="{{ $sku->dustiness_from }}"
                            isUnitHtml="true"
                            oninput="maskDecimalNumber(this,2,1)"
                        />

                        <x-form.input-text-with-unit
                            id="dustiness_to"
                            name="dustiness_to"
                            placeholder="{{ __('localization.sku_create_temperature_to') }}"
                            unit="{{ __('localization.sku_create_dustiness_air_unit') }}"
                            value="{{ $sku->dustiness_to }}"
                            isUnitHtml="true"
                            oninput="maskDecimalNumber(this,2,1)"
                        />
                    </x-form.input-group-wrapper>

                    <div id="storage-conditions-message" class="mt-1"></div>
                </x-slot>
            </x-card.nested>

            <div class="d-flex justify-content-end">
                <x-ui.action-button
                    id="edit"
                    data-id="{{ $sku->id }}"
                    class="btn btn-primary mb-2"
                    :text="__('localization.sku_edit_save_button')"
                />
            </div>
        </x-slot>
    </x-layout.container>

    <!-- Add Modal Paking -->
    <x-modal.base id="add_paking" size="modal-lg" style="max-width: 680px !important">
        <x-slot name="header">
            <x-ui.section-card-title level="2" class="modal-title">
                {{ __('localization.sku_create_add_paking_header') }}
            </x-ui.section-card-title>
        </x-slot>

        <x-slot name="body">
            <form class="row mx-0 js-modal-form">
                <x-form.select
                    id="parent_id"
                    name="parent_id"
                    label="localization.sku_create_add_paking_parent"
                    placeholder="localization.sku_create_add_paking_select_parent"
                    class="col-12 mb-1 d-none"
                >
                    @foreach ($sku->packages as $number => $package)
                        <option data-uuid="true" value="{{ $number + 1 }}">
                            {{ $package['name'] }}
                        </option>
                    @endforeach
                </x-form.select>

                <x-form.select
                    id="type_id"
                    name="type_id"
                    label="localization.sku_create_add_paking_type"
                    placeholder="localization.sku_create_add_paking_select_type"
                    data-dictionary="package_type"
                    data-full-object="true"
                    class="col-12 mb-1"
                />

                <x-form.input-text
                    id="add_name"
                    name="add_name"
                    label="localization.sku_create_add_paking_name"
                    placeholder="localization.sku_create_add_paking_enter_name"
                    class="col-12 mb-1"
                />

                <x-form.input-group-wrapper wrapperClass="col-12 px-0 mb-1">
                    <x-form.input-text
                        id="barcode"
                        name="barcode"
                        label="localization.sku_create_add_barcode_code"
                        placeholder="localization.sku_create_add_barcode_enter_code"
                        class="col-8"
                        oninput="limitInputToNumbers(this,30)"
                    />

                    <div class="col-4 ps-0 d-flex align-items-end">
                        <button disabled class="btn btn-outline-dark w-100">
                            {{ __('localization.sku.packing.barcode_btn') }}
                        </button>
                    </div>
                </x-form.input-group-wrapper>

                <x-ui.section-divider />

                <x-form.input-text-with-unit
                    id="package_count"
                    name="package_count"
                    label="localization.sku_create_add_package_count"
                    placeholder="localization.sku_create_add_paking_enter_units"
                    unit=""
                    oninput="maskFractionalNumbers(this,4)"
                />

                <x-form.input-text-with-unit
                    id="main_units_number"
                    name="main_units_number"
                    label="localization.sku_create_add_paking_units"
                    placeholder="localization.sku_create_add_paking_enter_units"
                    unit=""
                    oninput="maskDecimalNumber(this, 3, 3)"
                />

                <x-ui.section-divider />

                <x-form.input-text-with-unit
                    id="add_weight_netto"
                    name="add_weight_netto"
                    label="localization.sku_create_add_paking_net_weight"
                    placeholder="000"
                    unit="localization.sku_create_add_paking_net_weight_unit"
                    oninput="maskFractionalNumbers(this,7)"
                />

                <x-form.input-text-with-unit
                    id="add_weight_brutto"
                    name="add_weight_brutto"
                    label="localization.sku_create_add_paking_gross_weight"
                    placeholder="000"
                    unit="localization.sku_create_add_paking_gross_weight_unit"
                    oninput="maskFractionalNumbers(this,7)"
                />

                <x-form.input-group-wrapper wrapperClass="col-12 px-0 mb-1">
                    <x-form.input-text-with-unit
                        id="add_height"
                        name="add_height"
                        label="localization.sku_create_add_paking_height"
                        placeholder="000.0"
                        unit="localization.sku_create_add_paking_dimension_unit"
                        oninput="maskFractionalNumbers(this,4)"
                        class="col-4 mb-1 mb-md-0"
                    />

                    <x-form.input-text-with-unit
                        id="add_width"
                        name="add_width"
                        label="localization.sku_create_add_paking_width"
                        placeholder="000.0"
                        unit="localization.sku_create_add_paking_dimension_unit"
                        oninput="maskFractionalNumbers(this,4)"
                        class="col-4 mb-1 mb-md-0"
                    />

                    <x-form.input-text-with-unit
                        id="add_length"
                        name="add_length"
                        label="localization.sku_create_add_paking_length"
                        placeholder="000.0"
                        unit="localization.sku_create_add_paking_dimension_unit"
                        oninput="maskFractionalNumbers(this,4)"
                        class="col-4 mb-1 mb-md-0"
                    />
                </x-form.input-group-wrapper>
            </form>
        </x-slot>

        <x-slot name="footer">
            <button
                type="button"
                class="btn btn-link"
                data-bs-target="#add_paking"
                data-bs-toggle="modal"
                data-dismiss="modal"
            >
                {{ __('localization.sku_create_add_paking_cancel') }}
            </button>
            <button type="button" class="btn btn-primary" id="package_submit">
                {{ __('localization.sku_create_add_paking_save') }}
            </button>
        </x-slot>
    </x-modal.base>

    <!-- Edit Modal Paking -->
    <x-modal.base id="edit_paking" size="modal-lg" style="max-width: 680px !important">
        <x-slot name="header">
            <x-ui.section-card-title level="2" class="modal-title">
                {{ __('localization.sku_create_edit_paking_header') }}
            </x-ui.section-card-title>
        </x-slot>

        <x-slot name="body">
            <form class="row mx-0 js-modal-form2">
                <x-form.select
                    id="edit_parent_id"
                    name="edit_parent_id"
                    label="localization.sku_create_add_paking_parent"
                    placeholder="localization.sku_create_add_paking_select_parent"
                    class="col-12 mb-1 d-none"
                >
                    @foreach ($sku->packages as $number => $package)
                        <option data-uuid="true" value="{{ $number + 1 }}">
                            {{ $package['name'] }}
                        </option>
                    @endforeach
                </x-form.select>

                <x-form.select
                    id="edit_type_id"
                    name="edit_type_id"
                    label="localization.sku_create_add_paking_type"
                    placeholder="localization.sku_create_add_paking_select_type"
                    data-dictionary="package_type"
                    data-full-object="true"
                    class="col-12 mb-1"
                />

                <x-form.input-text
                    id="edit_name"
                    name="edit_name"
                    label="localization.sku_create_add_paking_name"
                    placeholder="localization.sku_create_add_paking_enter_name"
                    class="col-12 mb-1"
                />

                <x-form.input-group-wrapper wrapperClass="col-12 px-0 mb-1">
                    <x-form.input-text
                        id="edit_barcode"
                        name="edit_barcode"
                        label="localization.sku_create_add_barcode_code"
                        placeholder="localization.sku_create_add_barcode_enter_code"
                        class="col-8"
                        oninput="limitInputToNumbers(this,30)"
                    />

                    <div class="col-4 ps-0 d-flex align-items-end">
                        <button disabled class="btn btn-outline-dark w-100">
                            {{ __('localization.sku.packing.barcode_btn') }}
                        </button>
                    </div>
                </x-form.input-group-wrapper>

                <x-ui.section-divider />

                <x-form.input-text-with-unit
                    id="edit_package_count"
                    name="edit_package_count"
                    label="localization.sku_create_add_package_count"
                    placeholder="localization.sku_create_add_paking_enter_units"
                    unit=""
                    oninput="maskFractionalNumbers(this,4)"
                />

                <x-form.input-text-with-unit
                    id="edit_main_units_number"
                    name="edit_main_units_number"
                    label="localization.sku_create_add_paking_units"
                    placeholder="localization.sku_create_add_paking_enter_units"
                    unit=""
                    oninput="maskDecimalNumber(this, 3, 3)"
                />

                <x-ui.section-divider />

                <x-form.input-text-with-unit
                    id="edit_weight_netto"
                    name="edit_weight_netto"
                    label="localization.sku_create_add_paking_net_weight"
                    placeholder="000"
                    unit="localization.sku_create_add_paking_net_weight_unit"
                    oninput="maskFractionalNumbers(this,7)"
                />

                <x-form.input-text-with-unit
                    id="edit_weight_brutto"
                    name="edit_weight_brutto"
                    label="localization.sku_create_add_paking_gross_weight"
                    placeholder="000"
                    unit="localization.sku_create_add_paking_gross_weight_unit"
                    oninput="maskFractionalNumbers(this,7)"
                />

                <x-form.input-group-wrapper wrapperClass="col-12 px-0 mb-1">
                    <x-form.input-text-with-unit
                        id="edit_height"
                        name="edit_height"
                        label="localization.sku_create_add_paking_height"
                        placeholder="000.0"
                        unit="localization.sku_create_add_paking_dimension_unit"
                        oninput="maskFractionalNumbers(this,4)"
                        class="col-4 mb-1 mb-md-0"
                    />

                    <x-form.input-text-with-unit
                        id="edit_width"
                        name="edit_width"
                        label="localization.sku_create_add_paking_width"
                        placeholder="000.0"
                        unit="localization.sku_create_add_paking_dimension_unit"
                        oninput="maskFractionalNumbers(this,4)"
                        class="col-4 mb-1 mb-md-0"
                    />

                    <x-form.input-text-with-unit
                        id="edit_length"
                        name="edit_length"
                        label="localization.sku_create_add_paking_length"
                        placeholder="000.0"
                        unit="localization.sku_create_add_paking_dimension_unit"
                        oninput="maskFractionalNumbers(this,4)"
                        class="col-4 mb-1 mb-md-0"
                    />
                </x-form.input-group-wrapper>
            </form>
        </x-slot>

        <x-slot name="footer">
            <button
                type="button"
                class="btn btn-flat-danger"
                id="edit_condition_delete"
                style="margin-right: auto"
            >
                {{ __('localization.sku_create_edit_paking_delete') }}
            </button>

            <button
                class="btn btn-link"
                type="button"
                data-bs-target="#edit_paking"
                data-bs-toggle="modal"
                data-dismiss="modal"
            >
                {{ __('localization.sku_create_edit_paking_cancel') }}
            </button>
            <button type="button" class="btn btn-primary" id="edit_condition_submit">
                {{ __('localization.sku_create_edit_paking_save') }}
            </button>
        </x-slot>
    </x-modal.base>

    <x-cancel-modal
        id="cancel_button_modal"
        route="/sku"
        title="{{ __('localization.sku.cancel.edit.modal.title') }}"
        content="{!! __('localization.sku.cancel.edit.modal.content') !!}"
        cancel-text="{{ __('localization.sku.cancel.cancel_button') }}"
        confirm-text="{{ __('localization.sku.cancel.confirm_button') }}"
    />
@endsection

@section('page-script')
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/pickers/form-pickers.js') }}"></script>

    <script>
        let selectedTypeData = null;
        let selectedEditTypeData = null;
    </script>

    <script type="module">
        import { renderTable } from '{{ asset('assets/js/entity/sku/packaging-tree-table.js') }}';

        let tableData = @json($sku->packages);
        console.log(tableData);
        // 1️⃣ Спочатку створюємо idMap для parent_id
        const idMap = {};
        tableData.forEach((item, index) => {
            idMap[item.id] = (index + 1).toString();
        });

        // 2️⃣ Формуємо початкові дані без назв типів
        tableData = tableData.map((item, index) => ({
            id: (index + 1).toString(),
            parent_id: item.parent_id ? idMap[item.parent_id] || null : null,
            type_id: item.type_id,
            type_name: item.type.name, // тимчасово пусто
            name: item.name,
            main_units_number: item.main_units_number,
            package_count: item.package_count,
            weight_netto: item.weight_netto,
            weight_brutto: item.weight_brutto,
            height: item.height,
            width: item.width,
            length: item.length,
            barcode: item.barcodeString,
            canEdit: item.canEdit,
            uuid: item.id,
        }));

        // 3️⃣ Рендеримо таблицю одразу
        renderTable(tableData, true);

        window.tableData = tableData;
    </script>

    <script type="module" src="{{ asset('assets/js/entity/sku/sku-create.js') }}"></script>

    <script type="module" src="{{ asset('assets/js/utils/input-tag.js') }}"></script>

    <script type="module">
        import { initSelectWithValidationToggle } from '{{ asset('assets/js/utils/selectToggle.js') }}';

        initSelectWithValidationToggle(
            'measurement_unit_id',
            'unit_sku_error',
            'add_paking_button'
        );
    </script>

    <script type="module">
        import { bindSelectToUnitText } from '{{ asset('assets/js/utils/bindSelectToUnitText.js') }}';

        bindSelectToUnitText('measurement_unit_id', 'main_units_number');
        bindSelectToUnitText('measurement_unit_id', 'edit_main_units_number');
    </script>

    <script type="module">
        import { toggleBlock } from '{{ asset('assets/js/utils/toggleBlock.js') }}';

        toggleBlock({
            checkboxId: 'storage-conditions',
            targetId: 'barcode-body',
        });
    </script>

    <script type="module">
        import { setupConditionalHideOnEmptySelect } from '{{ asset('assets/js/utils/setupConditionalHideOnEmptySelect.js') }}';

        setupConditionalHideOnEmptySelect('parent_id', 'package_count_wrapper');
        setupConditionalHideOnEmptySelect('edit_parent_id', 'edit_package_count_wrapper');
    </script>
@endsection
