@extends('layouts.admin')
@section('title', __('localization.residue_control_catalog_item_view_title', ['item_name' => 'Крекер "Вершковий "Yarych" 3.78 кг 21*180 г']))
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
        src="{{ asset('assets/js/grid/residue-control/residue-control-view-table.js') }}"
    ></script>
@endsection

@section('content')
    <div class="px-2">
        <div class="">
            <div class="pb-2">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-slash">
                        <li class="breadcrumb-item">
                            <a class="link-secondary" href="/residue-control">
                                {{ __('localization.residue_control_catalog_item_view_breadcrumb') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item fw-bolder active" aria-current="page">
                            {{ __('localization.residue_control_catalog_item_view_breadcrumb_active', ['item_name' => 'Крекер "Вершковий "Yarych" 3.78 кг 21*180 г']) }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card my-2 mt-0">
            <div class="d-flex card-header border-bottom mx-0 px-2 gap-1">
                <div class="d-flex">
                    <h4 class="card-title fw-bolder">
                        {{ __('localization.residue_control_catalog_item_view_header', ['item_name' => 'Крекер "Вершковий "Yarych" 3.78 кг 21*180 г']) }}
                    </h4>
                </div>
            </div>
            <div class="card-grid" style="position: relative">
                @include('layouts.table-setting')
                <div class="table-block" id="residue-control-view-table"></div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#residue-control-view-table'));
    </script>
    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#residue-control-view-table'));
    </script>
@endsection
