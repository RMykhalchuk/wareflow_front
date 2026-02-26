@extends('layouts.admin')
@section('title', __('localization.services_view_title'))
@section('page-style')
    
@endsection

@section('before-style')
    {{-- <link rel="stylesheet" href="{{asset('assets/libs/jqwidget/jqwidgets/styles/jqx.base.css')}}" type="text/css"/> --}}
    {{-- <link rel="stylesheet" href="{{asset('assets/libs/jqwidget/jqwidgets/styles/jqx.light-wms.css')}}" type="text/css"/> --}}
@endsection

@section('table-js')
    {{-- @include('layouts.table-scripts') --}}

    {{-- <script type="text/javascript"> --}}
    {{-- $('#tabs').jqxTabs({ --}}
    {{-- width: '100%', --}}
    {{-- height: '100%' --}}
    {{-- }); --}}
    {{-- </script> --}}

    {{-- <script type="module" src="{{ asset('assets/js/grid/service/view-log-1-table.js') }}"></script> --}}
    {{-- <script type="module" src="{{ asset('assets/js/grid/service/view-log-2-table.js') }}"></script> --}}
@endsection

@section('content')
    <div class="container-fluid px-2">
        <div class="d-flex justify-content-between js-breadcrumb-wrapper">
            <div class="pb-2">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-slash">
                        <li class="breadcrumb-item">
                            <a class="link-secondary" href="/services">
                                {{ __('localization.services_view_breadcrumb_services') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item fw-bolder active" aria-current="page">
                            {{ __('localization.services_view_breadcrumb_view_service', ['name' => $service->name]) }}
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <button
                    class="btn p-25 h-50"
                    id="jsPrintBtn"
                    title="{{ __('localization.services_view_print_tooltip') }}"
                >
                    <i data-feather="printer" style="cursor: pointer; transform: scale(1.2)"></i>
                </button>
                <button
                    class="btn p-25 h-50"
                    id="jsCopyBtn"
                    title="{{ __('localization.services_view_copy_tooltip') }}"
                >
                    <i data-feather="copy" style="cursor: pointer; transform: scale(1.2)"></i>
                </button>
                <button class="btn p-25 h-50">
                    <a
                        class="text-secondary"
                        href="/services/{{ $service->id }}/edit"
                        title="{{ __('localization.services_view_edit_tooltip') }}"
                    >
                        <i data-feather="edit" style="cursor: pointer; transform: scale(1.2)"></i>
                    </a>
                </button>
                <div>
                    <div class="btn-group">
                        <i
                            data-feather="more-horizontal"
                            id="tn-details-header-dropdown"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            style="cursor: pointer; transform: scale(1.2)"
                        ></i>
                        <div class="dropdown-menu" aria-labelledby="tn-details-header-dropdown">
                            <a class="dropdown-item" href="#">
                                {{ __('localization.services_view_dropdown_action_1') }}
                            </a>
                            <a class="dropdown-item" href="#">
                                {{ __('localization.services_view_dropdown_action_2') }}
                            </a>
                            <a class="dropdown-item" href="#">
                                {{ __('localization.services_view_dropdown_action_3') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-xl-4" id="jsCopyEventScope">
                <div class="card px-2 py-1">
                    <div class="card-header px-0 pb-0">
                        <h4 class="card-title fw-bolder w-100 mb-0" style="padding-left: 6px">
                            {{ $service->name }}
                        </h4>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-6 card-body px-1 mt-0">
                            <p
                                class="text-uppercase mb-0 text-decoration-underline"
                                style="color: rgb(168, 170, 174); padding-left: 6px"
                            >
                                {{ __('localization.services_view_base_data') }}
                            </p>
                            <div class="mx-0 gap-2 card-data-reverse-darker">
                                <div class="mx-0 py-1" style="padding-left: 6px">
                                    <p class="fs-6 m-0 mb-50">
                                        {{ __('localization.services_view_label_category') }}
                                    </p>
                                    <p class="fs-5 m-0 fw-medium-c">
                                        @switch($service->category->key ?? null)
                                            @case('pryiom_tovaru')
                                                {{ __('localization.services_view_label_category_pryiom_tovaru') }}

                                                @break
                                            @case('zberihannia_tovaru')
                                                {{ __('localization.services_view_label_category_zberihannia_tovaru') }}

                                                @break
                                            @case('vidvantazhennia_tovaru')
                                                {{ __('localization.services_view_label_category_vidvantazhennia_tovaru') }}

                                                @break
                                            @case('komplektatsiia_tovaru')
                                                {{ __('localization.services_view_label_category_komplektatsiia_tovaru') }}

                                                @break
                                            @case('stikeruvannia_tovaru')
                                                {{ __('localization.services_view_label_category_stikeruvannia_tovaru') }}

                                                @break
                                            @case('kopakinh_tovaru')
                                                {{ __('localization.services_view_label_category_kopakinh_tovaru') }}

                                                @break
                                            @case('krosdok')
                                                {{ __('localization.services_view_label_category_krosdok') }}

                                                @break
                                            @default
                                                {{ $service->category?->name }}
                                        @endswitch
                                    </p>
                                </div>
                                <div class="mx-0 py-1" style="padding-left: 6px">
                                    <p class="fs-6 m-0 mb-50">
                                        {{ __('localization.services_view_label_comment') }}
                                    </p>
                                    <p class="fs-5 m-0 fw-medium-c">{{ $service->comment }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-8">
                {{-- <div id="tabs" class="w-100 transport-planning-table-tabs" style="overflow: visible"> --}}
                {{-- <ul class="d-flex"> --}}
                {{-- <li>{{ __('localization.services_view_tab_logs') }}</li> --}}
                {{-- <li>{{ __('localization.services_view_tab_logs_2') }}</li> --}}
                {{-- </ul> --}}
                {{-- <div> --}}
                {{-- <div> --}}
                {{-- <div class="card-grid" style="position: relative;"> --}}
                {{-- @include('layouts.table-setting') --}}
                {{-- <div class="table-block" id="view-log-1-table"></div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- <div> --}}
                {{-- <div> --}}
                {{-- <div class="card-grid" style="position: relative;"> --}}
                {{-- @include('layouts.table-setting', ['idOne' => 'settingTable-log-2','idTwo' => 'changeFonts-log-2','idThree' => 'changeCol-log-2', 'idFour' => 'jqxlistbox-log-2']) --}}
                {{-- <div class="table-block" id="view-log-2-table"></div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script type="module">
        import { setupPrintButton } from '{{ asset('assets/js/utils/print-btn.js') }}';

        setupPrintButton('jsPrintBtn', 'jsCopyEventScope');
    </script>

    <script type="module">
        import { setupCopyButton } from '{{ asset('assets/js/utils/copy-btn.js') }}';

        setupCopyButton('jsCopyBtn', 'jsCopyEventScope');
    </script>

    {{-- <script type="module"> --}}
    {{-- import {tableSetting} from '{{ asset('assets/js/grid/components/table-setting.js') }}'; --}}

    {{-- tableSetting($('#view-log-1-table')); --}}
    {{-- tableSetting($('#view-log-2-table'), '-log-2'); --}}
    {{-- </script> --}}

    {{-- <script type="module"> --}}
    {{-- import {offCanvasByBorder} from '{{asset('assets/js/utils/offCanvasByBorder.js')}}'; --}}

    {{-- offCanvasByBorder($('#view-log-1-table')); --}}
    {{-- offCanvasByBorder($('#view-log-2-table'), '-log-2'); --}}
    {{-- </script> --}}
@endsection
