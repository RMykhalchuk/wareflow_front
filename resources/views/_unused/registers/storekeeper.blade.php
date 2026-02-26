@extends('layouts.admin')
@section('title', __('localization.registers_storekeeper_title'))

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
        src="{{ asset('assets/js/grid/registers/storekeeper-table.js') }}"
    ></script>
@endsection

@section('content')
    <div class="loader" style="display: none">
        <span></span>
    </div>

    <x-layout.index-table-card :title="__('localization.registers_storekeeper_header_title')">
        <x-slot name="headerRight">
            <label class="form-label" for="warehouse-select">
                {{ __('localization.registers_storekeeper_warehouse_label') }}
            </label>
            <div class="col-3">
                <select
                    class="select2 form-select hide-search"
                    id="warehouse-select"
                    data-placeholder="{{ __('localization.registers_storekeeper_warehouse_placeholder') }}"
                    multiple
                >
                    <option value=""></option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>
        </x-slot>

        <x-slot name="grid">
            <div class="table-block" id="storekeeperRegisterTable">
                <div id="cellbegineditevent"></div>
                <div style="margin-top: 10px" id="cellendeditevent"></div>
                <script
                    type="text/javascript"
                    src="{{ asset('assets/js/grid/registers/register-actions.js') }}"
                ></script>
            </div>
        </x-slot>
    </x-layout.index-table-card>
@endsection

@section('page-script')
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>

    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#storekeeperRegisterTable'));
    </script>

    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#storekeeperRegisterTable'));
    </script>

    <script>
        var storekeepers = {!! $storekeepers->toJson() !!};
        var transportDownload = {!! $transportDownload->toJson() !!};
        var downloadZone = {!! $downloadZone->toJson() !!};
    </script>
@endsection
