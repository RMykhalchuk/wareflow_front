@extends('layouts.admin')
@section('title', __('localization.warehouse_erp.title'))
@section('page-style')
    
@endsection

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
        src="{{ asset('assets/js/grid/warehouse-erp/warehouse-erp-table.js') }}"
    ></script>
@endsection

@section('content')
    @if ($warehouseCount)
        <x-layout.index-table-card
            :title="__('localization.warehouse_erp.title')"
            :buttonText="__('localization.warehouse_erp.create_button')"
            buttonModalToggle="modal"
            buttonModalTarget="#add"
            tableId="warehouses-erp-table"
        />
    @else
        <x-layout.index-empty-message
            :title="__('localization.warehouse_erp.empty_title')"
            :message="__('localization.warehouse_erp.empty_message')"
            :buttonText="__('localization.warehouse_erp.create_button')"
            buttonModalToggle="modal"
            buttonModalTarget="#add"
        />
    @endif

    <x-modal.base id="add" size="modal-lg" style="max-width: 680px !important">
        <x-slot name="header">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <x-ui.section-card-title level="2" class="modal-title">
                    {{ __('localization.warehouse_erp.add_modal_title') }}
                </x-ui.section-card-title>
            </div>
        </x-slot>

        <x-slot name="body">
            <div class="row mx-0 js-modal-form">
                <x-form.input-text
                    id="name"
                    name="name"
                    :label="__('localization.warehouse_erp.form.name')"
                    :placeholder="__('localization.warehouse_erp.form.name_placeholder')"
                    class="col-12 mb-1"
                />

                <x-form.input-text
                    id="erp-id"
                    name="erp-id"
                    :label="__('localization.warehouse_erp.form.erp_id')"
                    :placeholder="__('localization.warehouse_erp.form.erp_id_placeholder')"
                    class="col-12"
                />

                <div id="message" class="mt-1"></div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <button
                type="button"
                class="btn btn-link"
                data-bs-target="#add"
                data-bs-toggle="modal"
                data-dismiss="modal"
            >
                {{ __('localization.warehouse_erp.cancel') }}
            </button>
            <button type="button" class="btn btn-primary" id="submit">
                {{ __('localization.warehouse_erp.save') }}
            </button>
        </x-slot>
    </x-modal.base>
@endsection

@section('page-script')
    <script
        type="module"
        src="{{ asset('assets/js/entity/warehouses-erp/warehouses-erp.js') }}"
    ></script>

    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#warehouses-erp-table'));
    </script>

    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#warehouses-erp-table'));
    </script>
@endsection
