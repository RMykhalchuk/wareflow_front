@extends('layouts.admin')
@section('title', __('localization.services_index_title'))
@section('page-style')
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
    <script type="module" src="{{ asset('assets/js/grid/service/services-table.js') }}"></script>
@endsection

@section('content')
    @if (count($services))
        <div class="card m-2 mt-0">
            <div class="card-header border-bottom row mx-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title col-9 fw-semibold">
                        {{ __('localization.services_index_services') }}
                    </h4>
                    <a class="btn btn-primary" href="/services/create">
                        <i data-feather="plus" class="mr-1"></i>
                        {{ __('localization.services_index_add_service') }}
                    </a>
                </div>
            </div>
            <div class="card-grid" style="position: relative">
                @include('layouts.table-setting')

                <div class="table-block" id="services-table"></div>
            </div>
        </div>
    @else
        <div style="margin-top: 253px">
            <div class="empty-company text-center">
                {{ __('localization.services_index_no_services') }}
            </div>
            <div class="empty-company-title text-center">
                {{ __('localization.services_index_create_service') }}
            </div>
            <div class="text-center mt-2">
                <a class="btn btn-primary" href="{{ route('services.create') }}">
                    <img
                        class="plus-icon"
                        src="{{ asset('assets/icons/entity/service/plus-service.svg') }}"
                    />
                    {{ __('localization.services_index_create_button') }}
                </a>
            </div>
        </div>
    @endif
@endsection

@section('page-script')
    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#services-table'));
    </script>

    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#services-table'));
    </script>
@endsection
