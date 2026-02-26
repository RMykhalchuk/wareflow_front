@extends('layouts.admin')
@section('title', __('localization.warehouse.sector.title'))

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
                            'url' => '/warehouses/' . $zone->warehouse->id,
                            'name' => __('localization.warehouse.view.breadcrumb.current'),
                            'name2' => $zone->warehouse->name ?? __('localization.warehouse.view.no_name'),
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
            <x-card.nested
                :headerAttributes="['class' => 'd-flex justify-content-start', 'id' => 'header-block']"
            >
                <x-slot:header>
                    <div class="d-flex align-items-center flex-grow-1 justify-content-between">
                        <div class="d-flex align-items-center">
                            <x-section-title>
                                {{ __('localization.warehouse.sector.title') }}
                            </x-section-title>
                            <div>
                                <a
                                    href="{{ route('warehouses.zones.index', ['warehouse' => $zone->warehouse->id]) }}"
                                    class="btn btn-outline-secondary rounded-pill"
                                >
                                    {{ __('localization.warehouse.sector.breadcrumb.zone') }}:
                                    {{ $zone->name }}
                                </a>
                            </div>
                        </div>

                        @if ($zone->sectors->count() > 0)
                            <x-modal.modal-trigger-button
                                id="print_button"
                                target="print-modal"
                                class="btn btn-outline-dark mr-1"
                                icon="printer"
                                iconStyle="mr-1"
                                :text="__('localization.warehouse.sector.print_label')"
                            />
                        @endif
                    </div>
                </x-slot>

                <x-slot:body>
                    @if ($zone->sectors->count() > 0)
                        <div class="table-responsive pb-2 pt-1">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-normalize">
                                            {{ __('localization.warehouse.sector.code') }}
                                        </th>
                                        <th class="text-normalize">
                                            {{ __('localization.warehouse.sector.name') }}
                                        </th>
                                        <th class="text-normalize">
                                            {{ __('localization.warehouse.sector.temperature_regime') }}:
                                        </th>
                                        <th class="text-normalize">
                                            {{ __('localization.warehouse.sector.humidity') }}:
                                        </th>
                                        <th class="text-normalize">
                                            {{ __('localization.warehouse.sector.floor_cell') }}
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($zone->sectors as $sector)
                                        <tr class="fs-5" data-id="1" data-parent="">
                                            <td class="bg-white">{{ $sector->code }}</td>
                                            <td class="fw-bold bg-white">
                                                <a
                                                    class="d-flex gap-50 align-items-center"
                                                    href="{{ route('sectors.rows.index', ['sector' => $sector->id]) }}"
                                                >
                                                    <p class="fw-semibold mb-0">
                                                        {{ $sector->name }}
                                                    </p>
                                                    <div
                                                        class="form-check form-check-{{ $sector->color }}"
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
                                                {{ $sector->has_temp ? $sector->temp_from . ' - ' . $sector->temp_to . ' °C' : __('localization.warehouse.sector.no') }}
                                            </td>
                                            <td class="bg-white">
                                                {{ $sector->has_humidity ? $sector->humidity_from . ' - ' . $sector->humidity_to . ' %' : __('localization.warehouse.sector.no') }}
                                            </td>
                                            <th class="bg-white">
                                                {{ $sector->cells->first()?->code ?? '-' }}
                                            </th>
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
                                                    <div
                                                        class="dropdown-menu dropdown-menu-end"
                                                        style="z-index: 10000"
                                                    >
                                                        <a
                                                            class="dropdown-item w-100 text-start"
                                                            href="{{ route('leftovers.index', ['cell_id' => $sector->cells->first()?->id]) }}"
                                                        >
                                                            {{ __('localization.warehouse.sector.view_content') }}
                                                        </a>

                                                        <button
                                                            class="dropdown-item w-100 text-start"
                                                            id="print_sector"
                                                            data-id="{{ $sector->cells->first()?->id }}"
                                                        >
                                                            {{ __('localization.warehouse.sector.print_action') }}
                                                        </button>

                                                        <x-modal.modal-trigger-button
                                                            id="edit_sector_button"
                                                            target="edit_sector"
                                                            class="dropdown-item"
                                                            :text="__('localization.warehouse.sector.modal.edit.header')"
                                                            style="width: 100%"
                                                            data-id="{{ $sector->id }}"
                                                            :data-name="$sector->name"
                                                            :data-color="$sector->color"
                                                            :data-has-temp="$sector->has_temp"
                                                            :data-temp-from="$sector->temp_from"
                                                            :data-temp-to="$sector->temp_to"
                                                            :data-has-humidity="$sector->has_humidity"
                                                            :data-humidity-from="$sector->humidity_from"
                                                            :data-humidity-to="$sector->humidity_to"
                                                            :data-has-leftovers="$sector->hasLeftovers()"
                                                        />

                                                        <button
                                                            class="dropdown-item w-100 text-start {{ $sector->hasLeftovers() ? 'disabled opacity-50' : '' }}"
                                                            id="delete_sector"
                                                            data-id="{{ $sector->id }}"
                                                            {{ $sector->hasLeftovers() ? 'disabled' : '' }}
                                                        >
                                                            <div class="btn-flat-danger">
                                                                {{ __('localization.warehouse.sector.modal.edit.delete_button') }}
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
                            id="add_sector_button"
                            target="add_sector"
                            class="btn btn-outline-secondary w-100"
                            :text="__('localization.warehouse.sector.add_sector')"
                            icon="plus"
                        />
                    </div>
                </x-slot>
            </x-card.nested>
        </x-slot>
    </x-layout.container>

    {{-- Модалки додавання та редагування --}}
    <x-modal.base id="add_sector" size="modal-lg" style="max-width: 680px !important">
        <x-slot name="header">
            <x-ui.section-card-title level="2" class="modal-title">
                {{ __('localization.warehouse.sector.modal.add.header') }}
            </x-ui.section-card-title>
        </x-slot>

        <x-slot name="body">
            <div class="row">
                {{-- Колір --}}
                <div class="col-12 col-sm-12 mb-1">
                    <div class="d-flex justify-content-between">
                        <label class="form-label fs-6">
                            {{ __('localization.warehouse.sector.modal.add.color_label') }}
                        </label>
                        <div class="d-flex">
                            @foreach (['info', 'violet', 'lime', 'orange', 'mint', 'pink', 'indigo', 'coral'] as $i => $color)
                                <div class="form-check form-check-{{ $color }}">
                                    <input
                                        type="radio"
                                        id="add_customRadio{{ $i + 1 }}"
                                        name="add_customColorRadio"
                                        value="{{ $color }}"
                                        class="form-check-input custom-color-input form-check-input-custom"
                                        {{ $i === 0 ? 'checked' : '' }}
                                    />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Назва --}}
                <div class="col-12 col-sm-12 mb-1">
                    <label class="form-label">
                        {{ __('localization.warehouse.sector.modal.add.name_label') }}
                    </label>
                    <input
                        maxlength="30"
                        placeholder=""
                        type="text"
                        class="form-control"
                        id="add_sector_name"
                    />
                </div>

                {{-- Температура --}}
                <div class="d-flex flex-row align-items-center">
                    <div class="form-check form-switch form-check-warning">
                        <input
                            type="checkbox"
                            class="form-check-input checkbox"
                            id="add_checkbox-1"
                            value="add_checkbox-1-block"
                        />
                        <label class="form-check-label" for="add_checkbox-1">
                            <span class="switch-icon-left"></span>
                            <span class="switch-icon-right"></span>
                        </label>
                    </div>
                    <label class="form-check-label" for="add_checkbox-1">
                        {{ __('localization.warehouse.sector.modal.add.temperature_label') }}
                    </label>
                </div>
                <div class="col-12 mb-1 mt-3" id="add_checkbox-1-block" style="display: none">
                    <div id="slider-1" class="slider-warning mt-md-1 mt-3 mb-4"></div>
                </div>

                {{-- Вологість --}}
                <div class="d-flex flex-row align-items-center mt-2">
                    <div class="form-check form-switch form-check-warning">
                        <input
                            type="checkbox"
                            class="form-check-input checkbox"
                            id="add_checkbox-2"
                            value="add_checkbox-2-block"
                        />
                        <label class="form-check-label" for="add_checkbox-2">
                            <span class="switch-icon-left"></span>
                            <span class="switch-icon-right"></span>
                        </label>

                        <label class="form-check-label" for="add_checkbox-2">
                            {{ __('localization.warehouse.sector.modal.add.humidity_label') }}
                        </label>
                    </div>
                </div>
                <div class="col-12 mb-1 mt-3" id="add_checkbox-2-block" style="display: none">
                    <div id="slider-2" class="slider-warning mt-md-1 mt-3 mb-4"></div>
                </div>
            </div>
            <div id="sector-message" class="mt-1"></div>
        </x-slot>

        <x-slot name="footer">
            <button
                type="button"
                class="btn btn-link"
                data-bs-target="#add_sector"
                data-bs-toggle="modal"
                data-dismiss="modal"
            >
                {{ __('localization.warehouse.sector.cancel') }}
            </button>
            <button type="button" class="btn btn-primary" id="add_sector_submit">
                {{ __('localization.warehouse.sector.save') }}
            </button>
        </x-slot>
    </x-modal.base>

    <x-modal.base id="edit_sector" size="modal-lg" style="max-width: 680px !important">
        <x-slot name="header">
            <x-ui.section-card-title level="2" class="modal-title">
                {{ __('localization.warehouse.sector.modal.edit.header') }}
            </x-ui.section-card-title>
        </x-slot>

        <x-slot name="body">
            <div class="row">
                {{-- Колір --}}
                <div class="col-12 col-sm-12 mb-1">
                    <div class="d-flex justify-content-between">
                        <label class="form-label fs-6">
                            {{ __('localization.warehouse.sector.modal.edit.color_label') }}
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

                {{-- Назва --}}
                <div class="col-12 col-sm-12 mb-1">
                    <label class="form-label">
                        {{ __('localization.warehouse.sector.modal.edit.name_label') }}
                    </label>
                    <input
                        maxlength="30"
                        placeholder=""
                        type="text"
                        class="form-control"
                        id="edit_sector_name"
                    />
                </div>

                {{-- Температура --}}
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
                        {{ __('localization.warehouse.sector.modal.edit.temperature_label') }}
                    </label>
                </div>
                <div class="col-12 mb-1 mt-3" id="edit_checkbox-1-block" style="display: none">
                    <div id="edit_slider-1" class="slider-warning mt-md-1 mt-3 mb-4"></div>
                </div>

                {{-- Вологість --}}
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
                            {{ __('localization.warehouse.sector.modal.edit.humidity_label') }}
                        </label>
                    </div>
                </div>
                <div class="col-12 mb-1 mt-3" id="edit_checkbox-2-block" style="display: none">
                    <div id="edit_slider-2" class="slider-warning mt-md-1 mt-3 mb-4"></div>
                </div>
            </div>
            <div id="sector-message-edit" class="mt-1"></div>
        </x-slot>

        <x-slot name="footer">
            <div class="d-flex justify-content-between w-100">
                <button type="button" class="btn btn-flat-danger" id="edit_delete">
                    {{ __('localization.warehouse.sector.modal.edit.delete_button') }}
                </button>
                <div>
                    <button
                        type="button"
                        class="btn btn-link"
                        data-bs-target="#edit_sector"
                        data-bs-toggle="modal"
                        data-dismiss="modal"
                    >
                        {{ __('localization.warehouse.sector.cancel') }}
                    </button>
                    <button type="button" class="btn btn-primary" id="save_edit_sector">
                        {{ __('localization.warehouse.sector.save') }}
                    </button>
                </div>
            </div>
        </x-slot>
    </x-modal.base>

    {{-- Модалка друку --}}
    @if ($zone->sectors->count() > 0)
        <x-modal.base id="print-modal" size="modal-lg" style="max-width: 680px !important">
            <x-slot name="header">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <x-ui.section-card-title level="3" class="modal-title fw-bolder pb-1">
                        {{ __('localization.warehouse.sector.modal.print.header') }}
                    </x-ui.section-card-title>
                </div>
            </x-slot>

            <x-slot name="body">
                <div class="row mx-0 js-modal-form">
                    <x-form.select
                        id="sectors_from_id"
                        name="sectors_from_id"
                        :label="__('localization.warehouse.sector.modal.print.from_label')"
                        :placeholder="__('localization.warehouse.sector.modal.print.from_placeholder')"
                    >
                        @foreach ($zone->sectors as $sector)
                            <option value="{{ $sector->id }}">
                                {{ $sector->name }}
                            </option>
                        @endforeach
                    </x-form.select>

                    <x-form.select
                        id="sectors_to_id"
                        name="sectors_to_id"
                        :label="__('localization.warehouse.sector.modal.print.to_label')"
                        :placeholder="__('localization.warehouse.sector.modal.print.to_placeholder')"
                    >
                        @foreach ($zone->sectors as $sector)
                            <option value="{{ $sector->id }}">
                                {{ $sector->name }}
                            </option>
                        @endforeach
                    </x-form.select>

                    <x-form.select
                        id="print_id"
                        name="print_id"
                        :label="__('localization.warehouse.sector.modal.print.printer_label')"
                        :placeholder="__('localization.warehouse.sector.modal.print.printer_placeholder')"
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
                    {{ __('localization.warehouse.sector.modal.print.cancel_button') }}
                </button>
                <x-ui.action-button
                    id="print"
                    class="btn btn-primary"
                    :text="__('localization.warehouse.sector.modal.print.print_button')"
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
                {{ __('localization.warehouse.sector.modal.print.loader_text') }}
            </span>
        </div>
    @endif

    <x-cancel-modal
        id="cancel_button_trigger"
        route="/warehouses/{{  $zone->warehouse->id }}"
        title="{{ __('localization.warehouse.zone.cancel_modal') }}"
        content="{!! __('localization.warehouse.zone.cancel_modal_content') !!}"
        cancel-text="{{ __('localization.warehouse.zone.cancel_modal_cancel') }}"
        confirm-text="{{ __('localization.warehouse.zone.cancel_modal_confirm') }}"
    />
@endsection

@section('page-script')
    <script>
        const zoneId = '{{ $zone->id }}';
    </script>

    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/wNumb.min.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/nouislider.min.js') }}"></script>
    <script src="{{ asset('/js/scripts/extensions/ext-component-sliders.js') }}"></script>
    <script src="{{ asset('assets/js/utils/unused/initSliderModal.js') }}"></script>

    <script
        type="module"
        src="{{ asset('assets/js/entity/warehouse/sector/sector.js') }}"
    ></script>
@endsection
