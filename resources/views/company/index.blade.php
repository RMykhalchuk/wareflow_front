@extends('layouts.admin')
@section('title', __('localization.company_index_title'))

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
    <script type="module" src="{{ asset('assets/js/grid/company/companies-table.js') }}"></script>
@endsection

@section('content')
    @if ($companies)
        <x-layout.index-table-card
            :title="__('localization.company_index_title_table')"
            :buttonText="__('localization.company_index_add_button')"
            :buttonRoute="route('companies.create')"
            tableId="companies-table"
        />
    @else
        <x-layout.index-empty-message
            :title="__('localization.company_index_empty_message')"
            :message="__('localization.company_index_add_company_prompt')"
            :buttonText="__('localization.company_index_add_company_button')"
            :buttonRoute="route('companies.create')"
        />
    @endif
@endsection

@section('page-script')
    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#companies-table'));
    </script>

    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#companies-table'));
    </script>
@endsection
