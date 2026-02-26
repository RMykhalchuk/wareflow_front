@extends('layouts.admin')
@section('title', __('localization.warehouse.zone.title'))

@section('before-style')
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset('vendors/css/extensions/nouislider.min.css') }}"
    />

    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset('css/base/plugins/extensions/ext-component-sliders.css') }}"
    />
@endsection

@section('page-style')
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}"
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
                            'url' => '/warehouses',
                            'name' => __('localization.warehouse.view.breadcrumb.warehouses'),
                        ],
                        [
                            'url' => '/warehouses/' . $warehouse->id,
                            'name' => __('localization.warehouse.view.breadcrumb.current'),
                            'name2' => $warehouse->name ?? __('localization.warehouse.view.no_name'),
                        ],
                        [
                            'name' => __('localization.warehouse.zone.components'),
                        ],
                    ],
                ]
            )

            <x-ui.header-actions>
                <x-modal.modal-trigger-button
                    id="cancel_button"
                    target="cancel_button_trigger"
                    class="btn btn-flat-secondary"
                    icon="x"
                    iconStyle="mr-0"
                />
            </x-ui.header-actions>
        </x-slot>

        <x-slot:slot>
            <x-card.nested>
                <x-slot:header>
                    <div class="d-flex align-items-center flex-grow-1 justify-content-between">
                        <x-section-title>
                            {{ __('localization.warehouse.zone.title') }}
                        </x-section-title>
                        @if ($warehouse->zones->count() > 0)
                            <x-modal.modal-trigger-button
                                id="print_button"
                                target="print-modal"
                                class="btn btn-outline-dark mr-1"
                                icon="printer"
                                iconStyle="mr-1"
                                :text="__('localization.warehouse.zone.print_label')"
                            />
                        @endif
                    </div>
                </x-slot>

                <x-slot:body>
                    @if ($warehouse->zones->count() > 0)
                        <div class="table-responsive pb-2 pt-1">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-normalize">
                                            {{ __('localization.warehouse.zone.name') }}
                                        </th>
                                        <th class="text-normalize">
                                            {{ __('localization.warehouse.zone.type') }}:
                                        </th>
                                        <th class="text-normalize">
                                            {{ __('localization.warehouse.zone.subtype') }}:
                                        </th>
                                        <th class="text-normalize">
                                            {{ __('localization.warehouse.zone.temperature_regime') }}:
                                        </th>
                                        <th class="text-normalize">
                                            {{ __('localization.warehouse.zone.humidity') }}:
                                        </th>
                                        <th class="text-normalize">
                                            {{ __('localization.warehouse.zone.floor_cell') }}
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($warehouse->zones->sortBy('name') as $zone)
                                        <tr class="fs-5" id="data_tab_1" data-id="{{ $zone->id }}">
                                            <td class="fw-bold bg-white">
                                                <a
                                                    class="d-flex gap-50 align-items-center"
                                                    href="{{ route('zones.sectors.index', ['zone' => $zone->id]) }}"
                                                >
                                                    <p class="fw-semibold mb-0">
                                                        {{ $zone->name }}
                                                    </p>
                                                    <div
                                                        class="form-check form-check-{{ $zone->color }}"
                                                    >
                                                        <input
                                                            disabled
                                                            type="radio"
                                                            id="selectedCustomRadio"
                                                            name="customColorRadio"
                                                            class="form-check-input custom-color-input form-check-input-custom w-2"
                                                        />
                                                    </div>
                                                </a>
                                            </td>
                                            <td class="bg-white">
                                                {{ $zone->zoneType->name ?? '-' }}
                                            </td>
                                            <td class="bg-white">
                                                {{ $zone->zoneSubtype->name ?? '-' }}
                                            </td>
                                            <td class="bg-white">
                                                {{ $zone->has_temp ? $zone->temp_from . ' - ' . $zone->temp_to . ' °C' : __('localization.warehouse.zone.no') }}
                                            </td>
                                            <td class="bg-white">
                                                {{ $zone->has_humidity ? $zone->humidity_from . ' - ' . $zone->humidity_to . ' %' : __('localization.warehouse.zone.no') }}
                                            </td>
                                            <td class="bg-white">
                                                {{ $zone->cells[0]->code ?? '-' }}
                                            </td>
                                            <td class="bg-white d-flex justify-content-end w-full">
                                                <div class="bg-transparent shadow-none">
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm dropdown-toggle hide-arrow p-25"
                                                        data-bs-toggle="dropdown"
                                                    >
                                                        <i
                                                            data-feather="more-vertical"
                                                            style="width: 16px; height: 16px"
                                                        ></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a
                                                            class="dropdown-item w-100 text-start"
                                                            href="{{ route('leftovers.index', ['cell_id' => $zone->cells[0]->id]) }}"
                                                        >
                                                            {{ __('localization.warehouse.zone.view_content') }}
                                                        </a>

                                                        <button
                                                            class="dropdown-item w-100 text-start"
                                                            id="print_zone"
                                                            data-id="{{ $zone->cells[0]->id }}"
                                                        >
                                                            {{ __('localization.warehouse.zone.print_action') }}
                                                        </button>
                                                        <x-modal.modal-trigger-button
                                                            id="edit_zone_button"
                                                            target="edit_zone"
                                                            class="dropdown-item"
                                                            :text="__('localization.warehouse.zone.edit')"
                                                            style="width: 100%"
                                                            data-id="{{ $zone->id }}"
                                                            :data-name="$zone->name"
                                                            :data-color="$zone->color"
                                                            :data-has-temp="$zone->has_temp"
                                                            :data-temp-from="$zone->temp_from"
                                                            :data-temp-to="$zone->temp_to"
                                                            :data-has-humidity="$zone->has_humidity"
                                                            :data-humidity-from="$zone->humidity_from"
                                                            :data-humidity-to="$zone->humidity_to"
                                                            :data-has-leftovers="$zone->hasLeftovers()"
                                                            :data-zone_type="$zone->zone_type"
                                                            :data-zone_subtype="$zone->zone_subtype"
                                                        />
                                                        <button
                                                            class="dropdown-item w-100 text-start {{ $zone->hasLeftovers() ? 'disabled opacity-50' : '' }}"
                                                            id="delete_zone"
                                                            data-id="{{ $zone->id }}"
                                                            {{ $zone->hasLeftovers() ? 'disabled' : '' }}
                                                        >
                                                            <div class="btn-flat-danger">
                                                                {{ __('localization.warehouse.zone.delete') }}
                                                            </div>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="px-1">
                        <x-modal.modal-trigger-button
                            id="add_zone_button"
                            target="add_zone"
                            class="btn btn-outline-secondary w-100"
                            :text="__('localization.warehouse.zone.add_zone')"
                            icon="plus"
                        />
                    </div>
                </x-slot>
            </x-card.nested>
        </x-slot>
    </x-layout.container>

    {{-- ADD ZONE --}}
    <x-modal.base id="add_zone" size="modal-lg" style="max-width: 680px !important">
        <x-slot name="header">
            <x-ui.section-card-title level="2" class="modal-title">
                {{ __('localization.warehouse.zone.modal.add.header') }}
            </x-ui.section-card-title>
        </x-slot>

        <x-slot name="body">
            <div class="row">
                <div class="col-12 col-sm-12 mb-1">
                    <div class="d-flex justify-content-between">
                        <label class="form-label fs-6">
                            {{ __('localization.warehouse.zone.modal.add.color_label') }}
                        </label>
                        <div class="d-flex">
                            @foreach (['info', 'violet', 'lime', 'orange', 'mint', 'pink', 'indigo', 'coral'] as $color)
                                <div class="form-check form-check-{{ $color }}">
                                    <input
                                        type="radio"
                                        name="add_customColorRadio"
                                        value="{{ $color }}"
                                        class="form-check-input custom-color-input form-check-input-custom"
                                        {{ $loop->first ? 'checked' : '' }}
                                    />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 mb-1">
                    <label class="form-label">
                        {{ __('localization.warehouse.zone.modal.add.name_label') }}
                    </label>
                    <input placeholder="A" type="text" class="form-control" id="add_zone_name" />
                </div>

                <div class="col-12 col-sm-12 mb-1">
                    <x-form.select
                        id="add_zone_type"
                        name="add_zone_type"
                        :label="__('localization.warehouse.zone_types_label')"
                        :placeholder="__('localization.warehouse.zone_types_label')"
                        data-dictionary="zone_types"
                        class="col-12 mb-1"
                    />

                    <x-form.select
                        id="add_zone_subtype"
                        name="add_zone_subtype"
                        :label="__('localization.warehouse.zone_subtypes_label')"
                        :placeholder="__('localization.warehouse.zone_subtypes_label')"
                        data-dictionary-base="zone_subtypes"
                        data-dependent="add_zone_type"
                        data-dependent-param="zone_type_id"
                        class="col-12 mb-1"
                    />
                </div>

                <div class="d-flex flex-row align-items-center">
                    <div class="form-check form-switch form-check-warning">
                        <input
                            type="checkbox"
                            class="form-check-input checkbox"
                            id="add_checkbox-1"
                            value="add_checkbox-1-block"
                        />
                    </div>
                    <label class="form-check-label" for="add_checkbox-1">
                        {{ __('localization.warehouse.zone.modal.add.temperature_label') }}
                    </label>
                </div>
                <div class="col-12 mb-1 mt-3" id="add_checkbox-1-block" style="display: none">
                    <div id="slider-1" class="slider-warning mt-md-1 mt-3 mb-4"></div>
                </div>

                <div class="d-flex flex-row align-items-center mt-2">
                    <div class="form-check form-switch form-check-warning">
                        <input
                            type="checkbox"
                            class="form-check-input checkbox"
                            id="add_checkbox-2"
                            value="add_checkbox-2-block"
                        />
                        <label class="form-check-label" for="add_checkbox-2">
                            {{ __('localization.warehouse.zone.modal.add.humidity_label') }}
                        </label>
                    </div>
                </div>
                <div class="col-12 mb-1 mt-3" id="add_checkbox-2-block" style="display: none">
                    <div id="slider-2" class="slider-warning mt-md-1 mt-3 mb-4"></div>
                </div>
            </div>
            <div id="zone-message" class="mt-1"></div>
        </x-slot>

        <x-slot name="footer">
            <button
                type="button"
                class="btn btn-link"
                data-bs-target="#add_zone"
                data-bs-toggle="modal"
                data-dismiss="modal"
            >
                {{ __('localization.warehouse.zone.modal.add.cancel_button') }}
            </button>
            <button type="button" class="btn btn-primary" id="add_zone_submit">
                {{ __('localization.warehouse.zone.modal.add.save_button') }}
            </button>
        </x-slot>
    </x-modal.base>

    {{-- EDIT ZONE --}}
    <x-modal.base id="edit_zone" size="modal-lg" style="max-width: 680px !important">
        <x-slot name="header">
            <x-ui.section-card-title level="2" class="modal-title">
                {{ __('localization.warehouse.zone.modal.edit.header') }}
            </x-ui.section-card-title>
        </x-slot>

        <x-slot name="body">
            <div class="row">
                <div class="col-12 col-sm-12 mb-1">
                    <div class="d-flex justify-content-between">
                        <label class="form-label fs-6">
                            {{ __('localization.warehouse.zone.modal.edit.color_label') }}
                        </label>
                        <div class="d-flex">
                            @foreach (['info', 'violet', 'lime', 'orange', 'mint', 'pink', 'indigo', 'coral'] as $i => $color)
                                <div class="form-check form-check-{{ $color }}">
                                    <input
                                        type="radio"
                                        id="edit_customRadio{{ $i + 1 }}"
                                        name="edit_customColorRadio"
                                        value="{{ $color }}"
                                        class="form-check-input custom-color-input form-check-input-custom"
                                        {{ $i === 0 ? 'checked' : '' }}
                                    />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 mb-1">
                    <label class="form-label">
                        {{ __('localization.warehouse.zone.modal.edit.name_label') }}
                    </label>
                    <input placeholder="A" type="text" class="form-control" id="edit_zone_name" />
                </div>

                <div class="col-12 col-sm-12 mb-1">
                    <x-form.select
                        id="edit_zone_type"
                        name="edit_zone_type"
                        :label="__('localization.warehouse.zone_types_label')"
                        :placeholder="__('localization.warehouse.zone_types_label')"
                        data-dictionary="zone_types"
                        class="col-12 mb-1"
                    />

                    <x-form.select
                        id="edit_zone_subtype"
                        name="edit_zone_subtype"
                        :label="__('localization.warehouse.zone_subtypes_label')"
                        :placeholder="__('localization.warehouse.zone_subtypes_label')"
                        data-dictionary-base="zone_subtypes"
                        data-dependent="edit_zone_type"
                        data-dependent-param="zone_type_id"
                        class="col-12 mb-1"
                    />
                </div>

                <div class="d-flex flex-row align-items-center">
                    <div class="form-check form-switch form-check-warning">
                        <input
                            type="checkbox"
                            class="form-check-input checkbox"
                            id="edit_checkbox-1"
                            value="edit_checkbox-1-block"
                        />
                        <label class="form-check-label" for="edit_checkbox-1">
                            <span class="switch-icon-left"></span>
                            <span class="switch-icon-right"></span>
                        </label>
                    </div>
                    <label class="form-check-label" for="edit_checkbox-1">
                        {{ __('localization.warehouse.zone.modal.edit.temperature_label') }}
                    </label>
                </div>
                <div class="col-12 mb-1 mt-3" id="edit_checkbox-1-block" style="display: none">
                    <div id="edit_slider-1" class="slider-warning mt-md-1 mt-3 mb-4"></div>
                </div>

                <div class="d-flex flex-row align-items-center mt-2">
                    <div class="form-check form-switch form-check-warning">
                        <input
                            type="checkbox"
                            class="form-check-input checkbox"
                            id="edit_checkbox-2"
                            value="edit_checkbox-2-block"
                        />
                        <label class="form-check-label" for="edit_checkbox-2">
                            <span class="switch-icon-left"></span>
                            <span class="switch-icon-right"></span>
                        </label>
                        <label class="form-check-label" for="edit_checkbox-2">
                            {{ __('localization.warehouse.zone.modal.edit.humidity_label') }}
                        </label>
                    </div>
                </div>
                <div class="col-12 mb-1 mt-3" id="edit_checkbox-2-block" style="display: none">
                    <div id="edit_slider-2" class="slider-warning mt-md-1 mt-3 mb-4"></div>
                </div>
            </div>
            <div id="zone-message-edit" class="mt-1"></div>
        </x-slot>

        <x-slot name="footer">
            <div class="d-flex justify-content-between w-100">
                <button type="button" class="btn btn-flat-danger" id="delete_edit_zone">
                    {{ __('localization.warehouse.zone.modal.edit.delete_button') }}
                </button>

                <div>
                    <button
                        type="button"
                        class="btn btn-link"
                        data-bs-target="#edit_zone"
                        data-bs-toggle="modal"
                        data-dismiss="modal"
                    >
                        {{ __('localization.warehouse.zone.modal.edit.cancel_button') }}
                    </button>
                    <button type="button" class="btn btn-primary" id="save_edit_zone">
                        {{ __('localization.warehouse.zone.modal.edit.save_button') }}
                    </button>
                </div>
            </div>
        </x-slot>
    </x-modal.base>

    {{-- PRINT MODAL --}}
    @if ($warehouse->zones->count() > 0)
        <x-modal.base id="print-modal" size="modal-lg" style="max-width: 680px !important">
            <x-slot name="header">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <x-ui.section-card-title level="3" class="modal-title fw-bolder pb-1">
                        {{ __('localization.warehouse.zone.modal.print.header') }}
                    </x-ui.section-card-title>
                </div>
            </x-slot>

            <x-slot name="body">
                <div class="row mx-0 js-modal-form">
                    <x-form.select
                        id="zones_from_id"
                        name="zones_from_id"
                        :label="__('localization.warehouse.zone.modal.print.from_label')"
                        :placeholder="__('localization.warehouse.zone.modal.print.from_placeholder')"
                    >
                        @foreach ($warehouse->zones->sortBy('name') as $zone)
                            <option value="{{ $zone->id }}">
                                {{ $zone->name }}
                            </option>
                        @endforeach
                    </x-form.select>

                    <x-form.select
                        id="zones_to_id"
                        name="zones_to_id"
                        :label="__('localization.warehouse.zone.modal.print.to_label')"
                        :placeholder="__('localization.warehouse.zone.modal.print.to_placeholder')"
                    >
                        @foreach ($warehouse->zones->sortBy('name') as $zone)
                            <option value="{{ $zone->id }}">
                                {{ $zone->name }}
                            </option>
                        @endforeach
                    </x-form.select>
                    <x-form.select
                        id="print_id"
                        name="print_id"
                        :label="__('localization.warehouse.zone.modal.print.printer_label')"
                        :placeholder="__('localization.warehouse.zone.modal.print.printer_placeholder')"
                        class="col-12"
                    >
                        <option value="1">1</option>
                    </x-form.select>

                    <div class="mt-1" id="print-error"></div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <button
                    type="button"
                    class="btn btn-link"
                    data-bs-target="#print-modal"
                    data-bs-toggle="modal"
                    data-dismiss="modal"
                >
                    {{ __('localization.warehouse.zone.modal.print.cancel_button') }}
                </button>
                <x-ui.action-button
                    id="print"
                    class="btn btn-primary"
                    :text="__('localization.warehouse.zone.modal.print.print_button')"
                />
            </x-slot>
        </x-modal.base>

        <!-- 🔹 Лоадер поверх -->
        <div
            id="print-loader"
            class="position-fixed top-0 start-0 d-none align-items-center justify-content-center bg-secondary-100 w-100 vh-100 bg-secondary-100"
            style="z-index: 10001"
        >
            <div class="spinner-border text-primary me-2" role="status"></div>
            <span class="fw-bold">
                {{ __('localization.warehouse.zone.modal.print.loader_text') }}
            </span>
        </div>
    @endif

    <x-cancel-modal
        id="cancel_button_trigger"
        route="/warehouses/{{ $warehouse->id }}"
        title="{{ __('localization.warehouse.zone.cancel_modal') }}"
        content="{!! __('localization.warehouse.zone.cancel_modal_content') !!}"
        cancel-text="{{ __('localization.warehouse.zone.cancel_modal_cancel') }}"
        confirm-text="{{ __('localization.warehouse.zone.cancel_modal_confirm') }}"
    />
@endsection

@section('page-script')
    <script>
        const warehouseId = '{{ $warehouse->id }}';
    </script>

    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/wNumb.min.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/nouislider.min.js') }}"></script>
    <script src="{{ asset('/js/scripts/extensions/ext-component-sliders.js') }}"></script>
    <script src="{{ asset('assets/js/utils/unused/initSliderModal.js') }}"></script>
    <script
        type="module"
        src="{{ asset('assets/js/utils/dictionary/selectDictionaryRelated.js') }}"
    ></script>

    <script type="module" src="{{ asset('assets/js/entity/warehouse/zone/zone.js') }}"></script>
@endsection
