@extends('layouts.admin')
@section('title', __('localization.transport_index_title'))
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
        src="{{ asset('assets/js/grid/transport/transport-table.js') }}"
    ></script>
@endsection

@section('content')
    @if (count($transports))
        <x-layout.index-table-card
            :title="__('localization.transport_index_title_table')"
            :buttonText="__('localization.transport_index_register_new')"
            :buttonRoute="route('transports.create')"
            tableId="transport-table"
        />
    @else
        <x-layout.index-empty-message
            :title="__('localization.transport_index_no_transport')"
            :message="__('localization.transport_index_no_transport_description')"
            :buttonText="__('localization.transport_index_add_transport')"
            :buttonRoute="route('transports.create')"
        />
    @endif
@endsection

@section('page-script')
    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#transport-table'));
    </script>
    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#transport-table'));
    </script>
@endsection
