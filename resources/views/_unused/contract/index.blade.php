@extends('layouts.admin')
@section('title', __('localization.contract_index_title'))

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
    <script type="module" src="{{ asset('assets/js/grid/contract/contract-table.js') }}"></script>
@endsection

@section('content')
    @if (count($contracts))
        <div class="card m-2">
            <div class="card-header border-bottom row mx-0 gap-1">
                <h4 class="card-title col-auto fw-bolder">
                    {{ __('localization.contract_index_title_table') }}
                </h4>
                <div class="col-auto d-flex flex-grow-1 justify-content-end pe-0">
                    <a class="btn btn-green" href="/contracts/create">
                        <img
                            class="plus-icon"
                            src="{{ asset('assets/icons/entity/contract/plus-contract.svg') }}"
                            alt="plus"
                        />
                        {{ __('localization.contract_index_create_button_table') }}
                    </a>
                </div>
            </div>
            <div class="card-grid" style="position: relative">
                @include('layouts.table-setting')
                <div class="table-block" id="contract-table"></div>
            </div>
        </div>
    @else
        <div style="margin-top: 253px">
            <div class="empty-company text-center">
                {{ __('localization.contract_index_empty_message') }}
            </div>
            <div class="empty-company-title text-center">
                {{ __('localization.contract_index_empty_sub_message') }}
            </div>
            <div class="text-center mt-2">
                <a class="btn btn-green" href="/contracts/create">
                    <img
                        class="plus-icon"
                        src="{{ asset('assets/icons/entity/contract/plus-contract.svg') }}"
                        alt="plus"
                    />
                    {{ __('localization.contract_index_empty_create_button') }}
                </a>
            </div>
        </div>
    @endif
@endsection

@section('page-script')
    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#contract-table'));
    </script>
    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#contract-table'));
    </script>
@endsection
