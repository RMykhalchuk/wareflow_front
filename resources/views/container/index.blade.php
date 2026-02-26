@extends('layouts.admin')
@section('title', __('localization.container_index_title'))

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
        src="{{ asset('assets/js/grid/container/container-table.js') }}"
    ></script>
@endsection

@section('content')
    @if (count($container))
        <x-layout.index-table-card
            :title="__('localization.container_index_container')"
            :buttonText="__('localization.container_index_add_container')"
            :buttonRoute="route('containers.create')"
            tableId="container-table"
        />
    @else
        <x-layout.index-empty-message
            :title="__('localization.container_index_no_container')"
            :message="__('localization.container_index_create_soon')"
            :buttonText="__('localization.container_index_create_container')"
            :buttonRoute="route('containers.create')"
        />
    @endif
@endsection

@section('page-script')
    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#container-table'), '', 45, 65);
    </script>
    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#container-table'));
    </script>
@endsection
