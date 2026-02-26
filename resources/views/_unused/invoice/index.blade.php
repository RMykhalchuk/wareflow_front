@extends('layouts.admin')

@section('title', __('localization.invoice_index_title'))

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

    <script type="module" src="{{ asset('assets/js/grid/invoice/invoice-table.js') }}"></script>
@endsection

@section('content')
    <div class="px-2">
        <div>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        ['url' => '/document', 'name' => __('localization.invoice_index_breadcrumb_documents')],
                        ['name' => __('localization.invoice_index_breadcrumb_invoices')],
                    ],
                ]
            )
        </div>

        <div class="card my-2">
            <div class="card-header border-bottom row mx-0 gap-1">
                <h4 class="card-title col-auto fw-bolder">
                    {{ __('localization.invoice_index_title_content') }}
                </h4>
                <div class="col-auto d-flex flex-grow-1 justify-content-end pe-0">
                    <a class="btn btn-green" href="/invoices/create">
                        <img
                            class="plus-icon"
                            src="{{ asset('assets/icons/entity/invoice/plus-invoice.svg') }}"
                            alt="{{ __('localization.invoice_index_plus_alt') }}"
                        />
                        {{ __('localization.invoice_index_create_invoice') }}
                    </a>
                </div>
            </div>
            <div class="card-grid" style="position: relative">
                @include('layouts.table-setting')
                <div class="table-block" id="invoice-table"></div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#invoice-table'));
    </script>

    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#invoice-table'));
    </script>
@endsection
