@extends('layouts.admin')
@section('title', __('localization.warehouse.cells.title'))

@section('before-style')
    <link
        rel=" stylesheet"
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
                            'url' => '/warehouses/' . $row->sector->zone->warehouse->id,
                            'name' => __('localization.warehouse.view.breadcrumb.current'),
                            'name2' =>
                                $row->sector->zone->warehouse->name ??
                                __('localization.warehouse.view.no_name'),
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
                                {{ __('localization.warehouse.cells.title') }}
                            </x-section-title>
                            <div class="d-flex gap-1">
                                <a
                                    href="{{ route('warehouses.zones.index', ['warehouse' => $row->sector->zone->warehouse->id]) }}"
                                    class="btn btn-outline-secondary rounded-pill"
                                >
                                    {{ __('localization.warehouse.cells.breadcrumb.zone') }}:
                                    {{ $row->sector->zone->name }}
                                </a>
                                <a
                                    href="{{ route('zones.sectors.index', ['zone' => $row->sector->zone->id]) }}"
                                    class="btn btn-outline-secondary rounded-pill"
                                >
                                    {{ __('localization.warehouse.cells.breadcrumb.sector') }}:
                                    {{ $row->sector->name }}
                                </a>
                                <a
                                    href="{{ route('sectors.rows.index', ['sector' => $row->sector->id]) }}"
                                    class="btn btn-outline-secondary rounded-pill"
                                >
                                    {{ __('localization.warehouse.cells.breadcrumb.row') }}:
                                    {{ $row->name }}
                                </a>
                            </div>
                        </div>

                        @if ($row->cells->count() > 0)
                            <x-modal.modal-trigger-button
                                id="print_button"
                                target="print-modal"
                                class="btn btn-outline-dark mr-1"
                                icon="printer"
                                iconStyle="mr-1"
                                :text="__('localization.warehouse.cells.print_label')"
                            />
                        @endif
                    </div>
                </x-slot>

                <x-slot:body>
                    @if ($row->cells->count() > 0)
                        <div class="table-responsive pb-2 pt-1">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-normalize">
                                            {{ __('localization.warehouse.cells.code') }}
                                        </th>
                                        <th class="text-normalize">
                                            {{ __('localization.warehouse.cells.height') }} /
                                            {{ __('localization.warehouse.cells.width') }} /
                                            {{ __('localization.warehouse.cells.length') }}
                                        </th>
                                        <th class="text-normalize">
                                            {{ __('localization.warehouse.cells.max_weight') }}
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($row->cells as $cell)
                                        <tr class="fs-5" data-id="1" data-parent="">
                                            <td class="fw-bold bg-white">
                                                <a class="d-flex gap-50 align-items-center">
                                                    <p class="fw-semibold mb-0">
                                                        {{ $cell->code }}
                                                    </p>
                                                </a>
                                            </td>
                                            <td class="bg-white">
                                                {{ $cell->rowInfo->height ? $cell->rowInfo->height . '/' . $cell->rowInfo->width . '/' . $cell->rowInfo->deep . ' ' . __('localization.warehouse.cells.unit.cm') : '' }}
                                            </td>
                                            <td class="bg-white">
                                                {{ $cell->rowInfo->max_weight ? $cell->rowInfo->max_weight . ' ' . __('localization.warehouse.cells.unit.kg') : '' }}
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
                                                            href="{{ route('leftovers.index', ['cell_id' => $cell->id]) }}"
                                                        >
                                                            {{ __('localization.warehouse.cells.view_content') }}
                                                        </a>

                                                        <button
                                                            class="dropdown-item w-100 text-start"
                                                            id="print_cell"
                                                            data-id="{{ $cell->id }}"
                                                        >
                                                            {{ __('localization.warehouse.cells.print_action') }}
                                                        </button>

                                                        <x-modal.modal-trigger-button
                                                            id="edit_cell_button"
                                                            target="edit_cell"
                                                            class="dropdown-item"
                                                            :text="__('localization.warehouse.zone.edit')"
                                                            style="width: 100%"
                                                            data-edit-id="{{ $cell->id }}"
                                                            :data-cell-type="$cell->type?->label()"
                                                            :data-cell-height="$cell->rowInfo->height"
                                                            :data-cell-width="$cell->rowInfo->width"
                                                            :data-cell-length="$cell->rowInfo->deep"
                                                            :data-cell-max-weight="$cell->rowInfo->max_weight"
                                                            :data-has-leftovers="$cell->hasLeftovers()"
                                                            {{-- :data-cell-apply-properties="$cell->apply_properties" --}}
                                                        />

                                                        <button
                                                            class="dropdown-item w-100 text-start {{ $cell->hasLeftovers() ? 'disabled opacity-50' : '' }}"
                                                            id="delete_cell"
                                                            data-id="{{ $cell->id }}"
                                                            {{ $cell->hasLeftovers() ? 'disabled' : '' }}
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
                </x-slot>
            </x-card.nested>
        </x-slot>
    </x-layout.container>

    <x-modal.base id="edit_cell" size="modal-lg" style="max-width: 680px !important">
        <x-slot name="header">
            <x-ui.section-card-title level="2" class="modal-title">
                {{ __('localization.warehouse.cells.edit_cell') }}
            </x-ui.section-card-title>
        </x-slot>

        <x-slot name="body">
            <div class="flex gap-1 row mx-0">
                <x-form.input-group-wrapper wrapperClass="col-12 px-0 mb-0 mx-0">
                    <x-form.input-text
                        id="cell_height"
                        name="cell_height"
                        label="localization.warehouse.cells.height"
                        placeholder="4"
                        data-msg="localization.warehouse.cells.height"
                        class="col-4"
                        oninput="maskFractionalNumbers(this,5)"
                    />
                    <x-form.input-text
                        id="cell_width"
                        name="cell_width"
                        label="localization.warehouse.cells.width"
                        placeholder="12"
                        data-msg="localization.warehouse.cells.width"
                        class="col-4"
                        oninput="maskFractionalNumbers(this,5)"
                    />
                    <x-form.input-text
                        id="cell_length"
                        name="cell_length"
                        label="localization.warehouse.cells.length"
                        placeholder="8"
                        data-msg="localization.warehouse.cells.length"
                        class="col-4"
                        oninput="maskFractionalNumbers(this,5)"
                    />
                </x-form.input-group-wrapper>

                <x-form.input-text
                    class="w-full"
                    id="cell_max_weight"
                    name="cell_max_weight"
                    label="localization.warehouse.cells.max_weight"
                    placeholder="12"
                    data-msg="localization.warehouse.cells.max_weight"
                    oninput="maskFractionalNumbers(this,5)"
                />

                <x-form.checkbox
                    label="localization.warehouse.cells.apply_properties"
                    id="cell_apply_properties"
                    name="cell_apply_properties"
                    class="w-full"
                />
            </div>
            <div id="cell-message" class="mt-1"></div>
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
                        data-bs-target="#edit_cell"
                        data-bs-toggle="modal"
                        data-dismiss="modal"
                    >
                        {{ __('localization.warehouse.zone.cancel') }}
                    </button>
                    <button type="button" class="btn btn-primary" id="save_edit_cell">
                        {{ __('localization.warehouse.zone.save') }}
                    </button>
                </div>
            </div>
        </x-slot>
    </x-modal.base>

    @if ($row->cells->count() > 0)
        <x-modal.base id="print-modal" size="modal-lg" style="max-width: 680px !important">
            <x-slot name="header">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <x-ui.section-card-title level="3" class="modal-title fw-bolder pb-1">
                        {{ __('localization.warehouse.cells.print_modal.header') }}
                    </x-ui.section-card-title>
                </div>
            </x-slot>

            <x-slot name="body">
                <div class="row mx-0 js-modal-form">
                    <x-form.select
                        id="cells_from_id"
                        name="cells_from_id"
                        :label="__('localization.warehouse.cells.print_modal.cells_from_label')"
                        :placeholder="__('localization.warehouse.cells.print_modal.cells_from_placeholder')"
                    >
                        @foreach ($row->cells as $cell)
                            <option value="{{ $cell->id }}">
                                {{ $cell->code }}
                            </option>
                        @endforeach
                    </x-form.select>

                    <x-form.select
                        id="cells_to_id"
                        name="cells_to_id"
                        :label="__('localization.warehouse.cells.print_modal.cells_to_label')"
                        :placeholder="__('localization.warehouse.cells.print_modal.cells_to_placeholder')"
                    >
                        @foreach ($row->cells as $cell)
                            <option value="{{ $cell->id }}">
                                {{ $cell->code }}
                            </option>
                        @endforeach
                    </x-form.select>

                    <x-form.select
                        id="print_id"
                        name="print_id"
                        :label="__('localization.warehouse.cells.print_modal.printer_label')"
                        :placeholder="__('localization.warehouse.cells.print_modal.printer_placeholder')"
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
                    {{ __('localization.warehouse.cells.print_modal.cancel_button') }}
                </button>
                <x-ui.action-button
                    id="print"
                    class="btn btn-primary"
                    :text="__('localization.warehouse.cells.print_modal.print_button')"
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
                {{ __('localization.warehouse.cells.print_modal.loader_text') }}
            </span>
        </div>
    @endif

    <x-cancel-modal
        id="cancel_button_trigger"
        route="/warehouses/{{ $row->sector->zone->warehouse->id }}"
        title="{{ __('localization.warehouse.zone.cancel_modal') }}"
        content="{!! __('localization.warehouse.zone.cancel_modal_content') !!}"
        cancel-text="{{ __('localization.warehouse.zone.cancel_modal_cancel') }}"
        confirm-text="{{ __('localization.warehouse.zone.cancel_modal_confirm') }}"
    />
@endsection

@section('page-script')
    <script>
        const rowId = '{{ $row->id }}';
    </script>

    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
    <script src="{{ asset('/js/scripts/tables/table-datatables-advanced.js') }}"></script>
    <script type="module" src="{{ asset('assets/js/entity/warehouse/cell/cell.js') }}"></script>
@endsection
