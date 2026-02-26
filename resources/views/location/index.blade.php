@extends('layouts.admin')
@section('title', __('localization.location.index.title'))
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
    <script type="module" src="{{ asset('assets/js/grid/location/location-table.js') }}"></script>
@endsection

@section('content')
    @if ($goodsCount)
        <x-layout.index-table-card
            :title="__('localization.location.index.title_header')"
            :buttonText="__('localization.location.index.add_location_button')"
            :buttonRoute="route('locations.create')"
            tableId="location-table"
        />
    @else
        <x-layout.index-empty-message
            :title="__('localization.location.index.empty_message')"
            :message="__('localization.location.index.add_location_prompt')"
            :buttonText="__('localization.location.index.add_location_button_text')"
            :buttonRoute="route('locations.create')"
        />
    @endif
@endsection

@section('page-script')
    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#location-table'));
    </script>

    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#location-table'));
    </script>
@endsection
