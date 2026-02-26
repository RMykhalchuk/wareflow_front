@extends('layouts.admin')
@section('title', __('localization.inventory.index.title'))

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
        src="{{ asset('assets/js/grid/inventory/inventory-table.js') }}"
    ></script>
@endsection

@section('content')
    @if ($inventoryCount)
        <x-layout.index-table-card
            :title="__('localization.inventory.index.title_header')"
            :buttonText="__('localization.inventory.index.add_location_button')"
            buttonModalToggle="modal"
            buttonModalTarget="#select-type"
            tableId="inventory-table"
        />
    @else
        <x-layout.index-empty-message
            :title="__('localization.inventory.index.add_location_prompt')"
            :message="__('localization.inventory.index.empty_message')"
            :buttonText="__('localization.inventory.index.add_location_button_text')"
            buttonModalToggle="modal"
            buttonModalTarget="#select-type"
        />
    @endif

    <x-modal.base id="select-type" size="modal-lg" style="max-width: 680px !important">
        <x-slot name="header">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <x-ui.section-card-title level="2" class="modal-title">
                    {{ __('localization.inventory.modal.select_type_title') }}
                </x-ui.section-card-title>
            </div>
        </x-slot>

        <x-slot name="body">
            <div class="row g-2 mx-0 mb-4">
                <div class="col-6">
                    <a
                        href="{{ route('inventory.create', ['type' => 'full']) }}"
                        class="card h-100 border shadow-sm text-decoration-none"
                    >
                        <div class="card-body text-start">
                            <h5 class="fw-bold mb-1">
                                {{ __('localization.inventory.modal.full_title') }}
                            </h5>
                            <p class="mb-0 text-muted">
                                {{ __('localization.inventory.modal.full_description') }}
                            </p>
                        </div>
                    </a>
                </div>
                <div class="col-6">
                    <a
                        href="{{ route('inventory.create', ['type' => 'partly']) }}"
                        class="card h-100 border shadow-sm text-decoration-none"
                    >
                        <div class="card-body text-start">
                            <h5 class="fw-bold mb-1">
                                {{ __('localization.inventory.modal.partly_title') }}
                            </h5>
                            <p class="mb-0 text-muted">
                                {{ __('localization.inventory.modal.partly_description') }}
                            </p>
                        </div>
                    </a>
                </div>
            </div>
        </x-slot>
    </x-modal.base>
@endsection

@section('page-script')
    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#inventory-table'), '', 100, 150);
    </script>
    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#inventory-table'));
    </script>
@endsection
