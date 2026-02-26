@extends('layouts.admin')
@section('title', __('localization.inventory.index.title_manual'))

@section('before-style')
    <link
        rel="stylesheet"
        href="{{ asset('assets/libs/jqwidget/jqwidgets/styles/jqx.base.css') }}"
        type="text/css"
    />
    <link
        rel="stylesheet"
        href="{{ asset('assets/libs/jqwidget/jqwidgets/styles/jqx.light-wms.css') }}"
        type="text/css"
    />
@endsection

@section('table-js')
    @include('layouts.table-scripts')
    <script
        type="module"
        src="{{ asset('assets/js/grid/inventory/manual/inventory-manual-table.js') }}"
    ></script>

    <script
        type="module"
        src="{{ asset('assets/js/grid/inventory/manual/inventory-manual-cell-table.js') }}"
    ></script>
@endsection

@section('content')
    @if ($inventoryCount)
        <x-layout.index-table-card
            :title="__('localization.inventory.index.title_manual')"
            tableId="inventory-manual-table"
            idOne="settingTable-manual"
            idTwo="changeFonts-manual"
            idThree="changeCol-manual"
            idFour="jqxlistbox-manual"
        />
    @else
        <x-layout.index-empty-message
            :title="__('localization.inventory.index.add_manual_inventory')"
            :message="__('localization.inventory.index.empty_message')"
        />
    @endif

    <x-modal.base id="target" class="js-table-popover-modal" size="modal-fullscreen">
        <x-slot name="header">
            <div class="d-flex flex-grow-1 justify-content-between gap-1 align-items-center">
                <div class="d-flex flex-column gap-50 col-4">
                    <div class="fw-bold">
                        {{ __('localization.inventory.view.modals.leftovers') }}
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <!-- Назва комірки -->
                        <div id="cell-name" class="mb-0 fw-bolder fs-4 w-auto">—</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-1">
                    <button
                        class="btn btn-flat-secondary p-1"
                        data-bs-dismiss="modal"
                        aria-label="{{ __('localization.inventory.view.modals.buttons.cancel') }}"
                    >
                        <i data-feather="x"></i>
                    </button>
                </div>
            </div>
        </x-slot>

        <x-slot name="body">
            <div class="card-grid" style="position: relative">
                @include(
                    'layouts.table-setting',
                    [
                        'idOne' => 'settingTable-manual-cell',
                        'idTwo' => 'changeFonts-manual-cell',
                        'idThree' => 'changeCol-manual-cell',
                        'idFour' => 'jqxlistbox-manual-cell',
                    ]
                )

                <!-- Таблиця -->
                <div class="table-block d-none" id="inventory-manual-cell-table"></div>
            </div>

            <!-- Порожній блок -->
            <div
                id="empty-cell-block"
                class="px-1 bg-secondary-100 d-flex flex-grow-1 flex-column gap-1 justify-content-center rounded align-items-center h-100 text-dark p-1 d-none"
            >
                <h3 id="placeholder_leftovers" class="d-block">
                    {{ __('localization.inventory.view.modals.empty_cell') }}
                </h3>
            </div>
        </x-slot>

        <x-slot name="footer"></x-slot>
    </x-modal.base>
@endsection

@section('page-script')
    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#inventory-manual-table'), '-manual', 100, 150);
        tableSetting($('#inventory-manual-cell-table'), '-manual-cell', 150, 200);
    </script>
    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#inventory-manual-table'), '-manual');
        offCanvasByBorder($('#inventory-manual-cell-table'), '-manual-cell');
    </script>
@endsection
