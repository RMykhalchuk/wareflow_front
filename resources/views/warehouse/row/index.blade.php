@extends('layouts.admin')
@section('title', __('localization.warehouse.row.title'))

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
                            'url' => '/warehouses/' . $sector->zone->warehouse->id,
                            'name' => __('localization.warehouse.view.breadcrumb.current'),
                            'name2' =>
                                $sector->zone->warehouse->name ?? __('localization.warehouse.view.no_name'),
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
                                {{ __('localization.warehouse.row.title') }}
                            </x-section-title>
                            <div class="d-flex gap-1">
                                <a
                                    href="{{ route('warehouses.zones.index', ['warehouse' => $sector->zone->warehouse->id]) }}"
                                    class="btn btn-outline-secondary rounded-pill"
                                >
                                    {{ __('localization.warehouse.row.breadcrumb.zone') }}:
                                    {{ $sector->zone->name }}
                                </a>
                                <a
                                    href="{{ route('zones.sectors.index', ['zone' => $sector->zone->id]) }}"
                                    class="btn btn-outline-secondary rounded-pill"
                                >
                                    {{ __('localization.warehouse.row.breadcrumb.sector') }}:
                                    {{ $sector->name }}
                                </a>
                            </div>
                        </div>

                        @if ($sector->rows->count() > 0)
                            <x-modal.modal-trigger-button
                                id="print_button"
                                target="print-modal"
                                class="btn btn-outline-dark mr-1"
                                icon="printer"
                                iconStyle="mr-1"
                                :text="__('localization.warehouse.row.print_label')"
                            />
                        @endif
                    </div>
                </x-slot>

                <x-slot:body>
                    @if ($sector->rows->count() > 0)
                        <div class="table-responsive pb-2 pt-1">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-normalize">
                                            {{ __('localization.warehouse.row.code') }}
                                        </th>
                                        <th class="text-normalize">
                                            {{ __('localization.warehouse.row.floors') }}
                                        </th>
                                        <th class="text-normalize">
                                            {{ __('localization.warehouse.row.racks') }}
                                        </th>
                                        <th class="text-normalize">
                                            {{ __('localization.warehouse.row.cells_in_rack') }}
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sector->rows as $row)
                                        <tr class="fs-5" data-id="{{ $row->id }}" data-parent="">
                                            <td class="fw-bold bg-white">
                                                <a
                                                    class="d-flex gap-50 align-items-center"
                                                    href="{{ route('rows.cells.index', ['row' => $row->id]) }}"
                                                >
                                                    <p class="fw-semibold mb-0">
                                                        {{ $row->name }}
                                                    </p>
                                                </a>
                                            </td>
                                            <td class="bg-white">{{ $row->floors }}</td>
                                            <td class="bg-white">{{ $row->racks }}</td>
                                            <td class="bg-white">{{ $row->cell_count }}</td>
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
                                                        <button
                                                            class="dropdown-item w-100 text-start"
                                                            id="print_row"
                                                            data-id="{{ $row->id }}"
                                                        >
                                                            {{ __('localization.warehouse.row.print_action') }}
                                                        </button>

                                                        <x-modal.modal-trigger-button
                                                            id="edit_row_button"
                                                            target="edit_row"
                                                            class="dropdown-item"
                                                            :text="__('localization.warehouse.row.edit_row')"
                                                            style="width: 100%"
                                                            data-edit-id="{{ $row->id }}"
                                                            :data-row-name="$row->name"
                                                            :data-row-racks="$row->racks"
                                                            :data-row-floors="$row->floors"
                                                            :data-row-cells="$row->cell_count"
                                                            :data-row-weight-brutto="$row->weight_brutto"
                                                            :data-height="$row->cell_props['height']"
                                                            :data-width="$row->cell_props['width']"
                                                            :data-length="$row->length"
                                                            :data-deep="$row->cell_props['deep']"
                                                            :data-max-weight="$row->cell_props['max_weight']"
                                                            :data-has-leftovers="$row->hasLeftovers()"
                                                        />

                                                        <button
                                                            class="dropdown-item w-100 text-start {{ $row->hasLeftovers() ? 'disabled opacity-50' : '' }}"
                                                            id="delete_row"
                                                            data-id="{{ $row->id }}"
                                                            {{ $row->hasLeftovers() ? 'disabled' : '' }}
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
                            id="add_row_button"
                            target="add_row"
                            class="btn btn-outline-secondary w-100"
                            :text="__('localization.warehouse.row.add_row')"
                            icon="plus"
                        />
                    </div>
                </x-slot>
            </x-card.nested>
        </x-slot>
    </x-layout.container>

    {{-- Модалка створення ряду --}}
    <x-modal.base id="add_row" size="modal-lg" style="max-width: 680px !important">
        <x-slot name="header">
            <x-ui.section-card-title level="2" class="modal-title">
                {{ __('localization.warehouse.row.create_row') }}
            </x-ui.section-card-title>
        </x-slot>

        <x-slot name="body">
            <div class="row mx-0">
                <x-form.input-group-wrapper wrapperClass="col-12 px-0 mb-1">
                    <x-form.input-text
                        class="col-12 mb-1"
                        id="row_count"
                        name="row_count"
                        :label="__('localization.warehouse.row.number_of_rows')"
                        placeholder="1"
                        type="number"
                        oninput="limitInputToNumbers(this,10)"
                    />

                    <x-form.input-text
                        class="col-12 col-md-4 mb-1 mb-md-0"
                        id="row_racks"
                        name="row_racks"
                        :label="__('localization.warehouse.row.number_of_racks')"
                        placeholder="8"
                        type="number"
                        oninput="limitInputToNumbers(this,10)"
                    />

                    <x-form.input-text
                        class="col-12 col-md-4 mb-1 mb-md-0"
                        id="row_floors"
                        name="row_floors"
                        :label="__('localization.warehouse.row.number_of_floors')"
                        placeholder="4"
                        oninput="limitInputToNumbers(this,10)"
                    />

                    <x-form.input-text
                        class="col-12 col-md-4 mb-1 mb-md-0"
                        id="row_cells"
                        name="row_cells"
                        :label="__('localization.warehouse.row.number_of_cells')"
                        placeholder="12"
                        oninput="limitInputToNumbers(this,10)"
                    />
                </x-form.input-group-wrapper>

                <x-ui.section-divider />

                <x-ui.section-card-title level="5" class="modal-title mb-1">
                    {{ __('localization.warehouse.row.cell_properties') }}
                </x-ui.section-card-title>

                <x-form.input-text-with-unit
                    id="row_weight_brutto"
                    name="row_weight_brutto"
                    :label="__('localization.warehouse.row.weight_brutto')"
                    placeholder="0000.0"
                    class="col-12 col-md-6 mb-1"
                    :unit="__('localization.warehouse.row.unit.kg')"
                    oninput="maskFractionalNumbers(this,5)"
                />

                <x-form.input-group-wrapper wrapperClass="col-12 px-0 mb-1">
                    <x-form.input-text-with-unit
                        id="row_height"
                        name="row_height"
                        :label="__('localization.warehouse.row.height')"
                        placeholder="0000.0"
                        :unit="__('localization.warehouse.row.unit.cm')"
                        oninput="maskFractionalNumbers(this,5)"
                        class="col-12 col-md-4 mb-1 mb-md-0"
                    />

                    <x-form.input-text-with-unit
                        id="row_width"
                        name="row_width"
                        :label="__('localization.warehouse.row.width')"
                        placeholder="0000.0"
                        :unit="__('localization.warehouse.row.unit.cm')"
                        oninput="maskFractionalNumbers(this,5)"
                        class="col-12 col-md-4 mb-1 mb-md-0"
                    />

                    <x-form.input-text-with-unit
                        id="row_length"
                        name="row_length"
                        :label="__('localization.warehouse.row.length')"
                        placeholder="0000.0"
                        :unit="__('localization.warehouse.row.unit.cm')"
                        oninput="maskFractionalNumbers(this,5)"
                        class="col-12 col-md-4 mb-1 mb-md-0"
                    />
                </x-form.input-group-wrapper>
            </div>
            <div id="row-message" class="mt-1"></div>
        </x-slot>

        <x-slot name="footer">
            <button
                type="button"
                class="btn btn-link"
                data-bs-target="#add_row"
                data-bs-toggle="modal"
                data-dismiss="modal"
            >
                {{ __('localization.warehouse.zone.cancel') }}
            </button>
            <button type="button" class="btn btn-primary" id="row_submit">
                <span>{{ __('localization.warehouse.zone.save') }}</span>
                <span
                    class="spinner-border spinner-border-sm d-none"
                    role="status"
                    aria-hidden="true"
                ></span>
            </button>
        </x-slot>
    </x-modal.base>

    {{-- Модалка редагування ряду --}}
    <x-modal.base id="edit_row" size="modal-lg" style="max-width: 680px !important">
        <x-slot name="header">
            <x-ui.section-card-title level="2" class="modal-title">
                {{ __('localization.warehouse.row.edit_row') }}
            </x-ui.section-card-title>
        </x-slot>

        <x-slot name="body">
            <div class="row mx-0">
                <x-form.input-group-wrapper wrapperClass="col-12 px-0 mb-1">
                    <x-form.input-text
                        class="col-12 col-md-4 mb-1 mb-md-0"
                        id="edit_row_racks"
                        name="row_racks"
                        :label="__('localization.warehouse.row.number_of_racks')"
                        placeholder="8"
                        oninput="limitInputToNumbers(this,10)"
                    />

                    <x-form.input-text
                        class="col-12 col-md-4 mb-1 mb-md-0"
                        id="edit_row_floors"
                        name="row_floors"
                        :label="__('localization.warehouse.row.number_of_floors')"
                        placeholder="4"
                        oninput="limitInputToNumbers(this,10)"
                    />

                    <x-form.input-text
                        class="col-12 col-md-4 mb-1 mb-md-0"
                        id="edit_row_cells"
                        name="row_cells"
                        :label="__('localization.warehouse.row.number_of_cells')"
                        placeholder="12"
                        oninput="limitInputToNumbers(this,10)"
                    />
                </x-form.input-group-wrapper>

                <x-ui.section-divider />

                <x-ui.section-card-title level="5" class="modal-title mb-1">
                    {{ __('localization.warehouse.row.cell_properties') }}
                </x-ui.section-card-title>

                <x-form.input-text-with-unit
                    id="edit_row_weight_brutto"
                    name="edit_row_weight_brutto"
                    :label="__('localization.warehouse.row.weight_brutto')"
                    placeholder="0000.0"
                    class="col-12 col-md-6 mb-1"
                    :unit="__('localization.warehouse.row.unit.kg')"
                    oninput="maskFractionalNumbers(this,5)"
                />

                <x-form.input-group-wrapper wrapperClass="col-12 px-0 mb-1">
                    <x-form.input-text-with-unit
                        id="edit_row_height"
                        name="edit_row_height"
                        :label="__('localization.warehouse.row.height')"
                        placeholder="0000.0"
                        :unit="__('localization.warehouse.row.unit.cm')"
                        oninput="maskFractionalNumbers(this,5)"
                        class="col-12 col-md-4 mb-1 mb-md-0"
                    />

                    <x-form.input-text-with-unit
                        id="edit_row_width"
                        name="edit_row_width"
                        :label="__('localization.warehouse.row.width')"
                        placeholder="0000.0"
                        :unit="__('localization.warehouse.row.unit.cm')"
                        oninput="maskFractionalNumbers(this,5)"
                        class="col-12 col-md-4 mb-1 mb-md-0"
                    />

                    <x-form.input-text-with-unit
                        id="edit_row_length"
                        name="edit_row_length"
                        :label="__('localization.warehouse.row.length')"
                        placeholder="0000.0"
                        :unit="__('localization.warehouse.row.unit.cm')"
                        oninput="maskFractionalNumbers(this,5)"
                        class="col-12 col-md-4 mb-1 mb-md-0"
                    />
                </x-form.input-group-wrapper>
            </div>

            <div id="row-message-edit" class="mt-1"></div>
        </x-slot>

        <x-slot name="footer">
            <div class="d-flex justify-content-between w-100">
                <button type="button" class="btn btn-flat-danger" id="edit_delete">
                    {{ __('localization.warehouse.zone.delete') }}
                </button>
                <div>
                    <button
                        type="button"
                        class="btn btn-link"
                        data-bs-target="#edit_row"
                        data-bs-toggle="modal"
                        data-dismiss="modal"
                    >
                        {{ __('localization.warehouse.zone.cancel') }}
                    </button>
                    <button type="button" class="btn btn-primary" id="edit_row_submit">
                        {{ __('localization.warehouse.zone.save') }}
                    </button>
                </div>
            </div>
        </x-slot>
    </x-modal.base>

    {{-- Модалка друку рядків --}}
    @if ($sector->rows->count() > 0)
        <x-modal.base id="print-modal" size="modal-lg" style="max-width: 680px !important">
            <x-slot name="header">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <x-ui.section-card-title level="3" class="modal-title fw-bolder pb-1">
                        {{ __('localization.warehouse.row.print_modal.header') }}
                    </x-ui.section-card-title>
                </div>
            </x-slot>

            <x-slot name="body">
                <div class="row mx-0 js-modal-form">
                    <x-form.select
                        id="rows_from_id"
                        name="rows_from_id"
                        :label="__('localization.warehouse.row.print_modal.rows_from_label')"
                        :placeholder="__('localization.warehouse.row.print_modal.rows_from_placeholder')"
                    >
                        @foreach ($sector->rows as $row)
                            <option value="{{ $row->id }}">
                                {{ $row->name }}
                            </option>
                        @endforeach
                    </x-form.select>

                    <x-form.select
                        id="rows_to_id"
                        name="rows_to_id"
                        :label="__('localization.warehouse.row.print_modal.rows_to_label')"
                        :placeholder="__('localization.warehouse.row.print_modal.rows_to_placeholder')"
                    >
                        @foreach ($sector->rows as $row)
                            <option value="{{ $row->id }}">
                                {{ $row->name }}
                            </option>
                        @endforeach
                    </x-form.select>
                    <x-form.select
                        id="print_id"
                        name="print_id"
                        :label="__('localization.warehouse.row.print_modal.printer_label')"
                        :placeholder="__('localization.warehouse.row.print_modal.printer_placeholder')"
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
                    {{ __('localization.warehouse.row.print_modal.cancel_button') }}
                </button>
                <x-ui.action-button
                    id="print"
                    class="btn btn-primary"
                    :text="__('localization.warehouse.row.print_modal.print_button')"
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
                {{ __('localization.warehouse.row.print_modal.loader_text') }}
            </span>
        </div>
    @endif

    <x-cancel-modal
        id="cancel_button_trigger"
        route="/warehouses/{{ $sector->zone->warehouse->id }}"
        title="{{ __('localization.warehouse.zone.cancel_modal') }}"
        content="{!! __('localization.warehouse.zone.cancel_modal_content') !!}"
        cancel-text="{{ __('localization.warehouse.zone.cancel_modal_cancel') }}"
        confirm-text="{{ __('localization.warehouse.zone.cancel_modal_confirm') }}"
    />
@endsection

@section('page-script')
    <script>
        const sectorId = '{{ $sector->id }}';
    </script>
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
    <script src="{{ asset('/js/scripts/tables/table-datatables-advanced.js') }}"></script>

    <script type="module" src="{{ asset('assets/js/entity/warehouse/row/row.js') }}"></script>
@endsection
