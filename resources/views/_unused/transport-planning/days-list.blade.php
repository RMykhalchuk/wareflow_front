@extends('layouts.admin')
@section('title', __('localization.transport_planning_index_title'))
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
        src="{{ asset('assets/js/grid/transport-planning/index-table.js') }}"
    ></script>
@endsection

@section('content')
    @if (count($transportPlanning))
        <div class="card mt-0 m-2">
            <div class="card-header border-bottom row">
                <h4 class="card-title col-12 col-sm-12 col-md-5 col-lg-5 col-xxl-5 fw-semibold">
                    {{ __('localization.transport_planning_index_title_table') }}
                </h4>
                <div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xxl-7">
                    <a class="btn btn-primary float-end" href="/transport-planning/create">
                        <img
                            class="plus-icon"
                            src="{{ asset('assets/icons/utils/plus.svg') }}"
                            alt="plus"
                        />
                        {{ __('localization.transport_planning_index_register_new') }}
                    </a>
                </div>
            </div>
            <div class="card-grid" style="position: relative">
                @include('layouts.table-setting')

                <div class="table-block" id="transportPlanningDataTable"></div>
            </div>
        </div>
    @else
        <div style="margin-top: 253px">
            <div class="empty-company text-center">
                {{ __('localization.transport_planning_index_no_transport_planning') }}
            </div>
            <div class="empty-company-title empty-company-title-m text-center mt-1">
                {{ __('localization.transport_planning_index_no_transport_planning_description') }}
            </div>
            <div class="text-center mt-2">
                <a href="/transport-planning/create" class="btn btn-primary">
                    <img
                        class="plus-icon"
                        src="{{ asset('assets/icons/utils/plus.svg') }}"
                        alt="plus"
                    />
                    {{ __('localization.transport_planning_index_add_transport') }}
                </a>
            </div>
        </div>
    @endif
@endsection

@section('page-script')
    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#transportPlanningDataTable'));
    </script>
    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#transportPlanningDataTable'));
    </script>
@endsection
