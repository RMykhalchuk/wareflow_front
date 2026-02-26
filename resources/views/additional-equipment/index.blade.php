@extends('layouts.admin')
@section('title', __('localization.additional_equipment_index_title'))
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
        src="{{ asset('assets/js/grid/transport/additional-equipment-table.js') }}"
    ></script>
@endsection

@section('content')
    @if (count($additionalEquipments))
        <x-layout.index-table-card
            :title="__('localization.additional_equipment_index_title_table')"
            :buttonText="__('localization.additional_equipment_create_new_transport')"
            :buttonRoute="route('transport-equipments.create')"
            tableId="additional-equipment-table"
        />
    @else
        <x-layout.index-empty-message
            :title="__('localization.additional_equipment_empty_message')"
            :message="__('localization.additional_equipment_empty_message_subtitle')"
            :buttonText="__('localization.additional_equipment_add_equipment')"
            :buttonRoute="route('transport-equipments.create')"
        />
    @endif
@endsection

@section('page-script')
    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#additional-equipment-table'));
    </script>

    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#additional-equipment-table'));
    </script>
@endsection
