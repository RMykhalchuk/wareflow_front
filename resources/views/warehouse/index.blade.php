@extends('layouts.admin')
@section('title', __('localization.warehouse.index.title'))
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
        src="{{ asset('assets/js/grid/warehouse/warehouse-table.js') }}"
    ></script>
@endsection

@section('content')
    @if ($warehouseCount)
        <x-layout.index-table-card
            :title="__('localization.warehouse.index.title_header')"
            :buttonText="__('localization.warehouse.index.add_location_button')"
            :buttonRoute="route('warehouses.create')"
            tableId="warehouse-table"
        />
    @else
        <x-layout.index-empty-message
            :title="__('localization.warehouse.index.empty_message')"
            :message="__('localization.warehouse.index.add_location_prompt')"
            :buttonText="__('localization.warehouse.index.add_location_button_text')"
            :buttonRoute="route('warehouses.create')"
        />
    @endif
@endsection

@section('page-script')
    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#warehouse-table'));
    </script>

    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#warehouse-table'));
    </script>
@endsection
