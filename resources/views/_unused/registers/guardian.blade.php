@extends('layouts.admin')
@section('title', __('localization.registers_guardian_title'))
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
    <script type="module" src="{{ asset('assets/js/grid/registers/guardian-table.js') }}"></script>
@endsection

@section('content')
    <div class="loader" style="display: none">
        <span></span>
    </div>

    <x-layout.index-table-card :title="__('localization.registers_guardian_header_title')">
        <x-slot name="headerRight">
            <label class="form-label" for="warehouse-select">
                {{ __('localization.registers_guardian_warehouse_label') }}
            </label>
            <div class="col-3">
                <select
                    class="select2 form-select hide-search"
                    id="warehouse-select"
                    data-placeholder="{{ __('localization.registers_guardian_warehouse_placeholder') }}"
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
            <div class="table-block" id="guardRegisterDataTable">
                <div id="cellbegineditevent"></div>
                <div style="margin-top: 10px" id="cellendeditevent"></div>
                <script
                    type="text/javascript"
                    src="{{ asset('assets/js/grid/registers/register-actions.js') }}"
                ></script>
            </div>
        </x-slot>
    </x-layout.index-table-card>

    <div
        class="toast basic-toast position-fixed top-0 end-0 mt-5 me-2 fade hide"
        style="background: rgb(255, 255, 255)"
    >
        <div class="toast-header">
            <img
                src="{{ asset('assets/icons/entity/registers/check-new-route.svg') }}"
                class="me-1"
                alt="Toast image"
                height="18"
                width="25"
            />
            <strong style="font-weight: 600; font-size: 15px" class="me-auto">
                {{ __('localization.registers_guardian_toast_title') }}
            </strong>
            <button
                type="button"
                class="ms-1 btn-close"
                data-bs-dismiss="toast"
                aria-label="Close"
            ></button>
        </div>
        <div id="alert-body" style="margin-left: 50px; font-size: 14px; margin-top: 5px">
            <div>
                {{ __('localization.registers_guardian_toast_id') }}:
                <b><span id="row-id"></span></b>
            </div>
            <div>
                {{ __('localization.registers_guardian_toast_name') }}:
                <b><span id="car-name"></span></b>
            </div>
            <div>
                {{ __('localization.registers_guardian_toast_car_number') }}:
                <b><span id="car-number"></span></b>
            </div>
        </div>
        <div class="mt-1 mb-1" style="margin-left: 50px">
            <button id="goto" class="btn btn-primary">
                {{ __('localization.registers_guardian_toast_button') }}
            </button>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>
    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#guardRegisterDataTable'));
    </script>

    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#guardRegisterDataTable'));
    </script>
@endsection
