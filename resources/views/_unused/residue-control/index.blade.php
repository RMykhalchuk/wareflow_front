@extends('layouts.admin')
@section('title', __('localization.residue_control_index_title'))
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
        src="{{ asset('assets/js/grid/residue-control/residue-control-table.js') }}"
    ></script>
@endsection

@section('content')
    <div class="px-2">
        <div class="card my-2 mt-0">
            <div class="d-flex card-header border-bottom mx-0 px-2 gap-1">
                <div class="d-flex">
                    <h4 class="card-title fw-bolder">
                        {{ __('localization.residue_control_index_header') }}
                    </h4>
                </div>
                <div class="d-flex col-4 gap-1">
                    <div class="w-100">
                        <select
                            class="select2 form-select"
                            id="select2-basic"
                            data-placeholder="{{ __('localization.residue_control_index_today_placeholder') }}"
                        >
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="w-100">
                        <select
                            class="select2 form-select"
                            id="select2-basic2"
                            data-placeholder="{{ __('localization.residue_control_index_all_warehouses_placeholder') }}"
                        >
                            <option value=""></option>
                            <option selected value="all-warehouse">
                                {{ __('localization.residue_control_index_all_warehouses') }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-grid" style="position: relative">
                @include('layouts.table-setting')
                <div class="table-block" id="residue-control-table"></div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#residue-control-table'));

        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#residue-control-table'));

        // Get today's date and format it
        const today = new Date();
        const formattedDate = today.toLocaleDateString('uk-UA', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
        });

        // Create todayOption element with selected attribute
        const todayOption = document.createElement('option');
        todayOption.value = 'today';
        todayOption.textContent = `{{ __('localization.residue_control_index_today', ['date' => '${formattedDate}']) }}`;
        todayOption.setAttribute('selected', 'selected');

        // Append todayOption to select element with id select2-basic
        document.getElementById('select2-basic').appendChild(todayOption);
    </script>
@endsection
