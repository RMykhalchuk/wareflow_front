@extends('layouts.admin')
@section('title', __('localization.leftovers.title'))

@section('page-style')
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}"
    />
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}"
    />
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
        src="{{ asset('assets/js/grid/leftovers/leftovers-table.js') }}"
    ></script>
@endsection

@section('content')
    @if ($leftoverCount)
        <x-layout.index-table-card
            :title="__('localization.leftovers.tab1')"
            :buttonText="__('localization.leftovers.add')"
            buttonModalToggle="modal"
            buttonModalTarget="#add"
            tableId="leftoversDataTable"
        />
    @else
        <x-layout.index-empty-message
            :title="__('localization.leftovers.empty')"
            :message="__('localization.leftovers.empty_subtitle')"
            :buttonText="__('localization.leftovers.add')"
            buttonModalToggle="modal"
            buttonModalTarget="#add"
        />
    @endif

    <x-modal.base id="add" size="modal-lg" style="max-width: 680px !important">
        <x-slot name="header">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <x-ui.section-card-title level="2" class="modal-title">
                    {{ __('localization.leftovers.add') }}
                </x-ui.section-card-title>
            </div>
        </x-slot>

        <x-slot name="body">
            <div class="row mx-0 js-modal-form">
                <x-form.select
                    id="goods_id"
                    name="goods_id"
                    :label="__('localization.leftovers.form.goods')"
                    :placeholder="__('localization.leftovers.form.goods_placeholder')"
                    data-dictionary="goods"
                />

                <x-form.select
                    id="packages_id"
                    name="packages_id"
                    :label="__('localization.leftovers.form.packages')"
                    :placeholder="__('localization.leftovers.form.packages_placeholder')"
                    data-dependent="goods_id"
                    data-dependent-param="goods_id"
                    data-dictionary-base="packages"
                />

                <x-form.input-text
                    id="batch"
                    name="batch"
                    :label="__('localization.leftovers.form.batch')"
                    :placeholder="__('localization.leftovers.form.batch_placeholder')"
                    type="number"
                />

                <x-form.input-text
                    id="quantity"
                    name="quantity"
                    :label="__('localization.leftovers.form.quantity')"
                    :placeholder="__('localization.leftovers.form.quantity_placeholder')"
                    type="number"
                    min="1"
                    oninput="validatePositive(this)"
                    required
                />

                <x-form.date-input
                    id="manufacture_date"
                    name="manufacture_date"
                    :label="__('localization.leftovers.form.manufacture_date')"
                    :placeholder="__('localization.leftovers.form.date_placeholder')"
                    :required
                />

                <x-form.date-input
                    id="bb_date"
                    name="bb_date"
                    :label="__('localization.leftovers.form.bb_date')"
                    :placeholder="__('localization.leftovers.form.date_placeholder')"
                    :required
                />

                <x-form.select
                    id="expiration_term"
                    name="expiration_term"
                    :label="__('localization.inventory.view.modals.add_leftovers.expiration.label')"
                    :placeholder="__('localization.inventory.view.modals.add_leftovers.expiration.placeholder')"
                    data-dependent="goods_id"
                    data-dependent-param="goods_id"
                    data-dictionary-base="goods-expiration"
                    class="col-6 mb-1"
                />

                <x-ui.section-divider />

                <x-form.select
                    id="container_registers_id"
                    name="container_registers_id"
                    :label="__('localization.leftovers.form.container')"
                    :placeholder="__('localization.leftovers.form.container_placeholder')"
                    data-dictionary="container_registers"
                />

                <x-form.switch
                    id="has_condition"
                    name="has_condition"
                    :label="__('localization.leftovers.form.condition')"
                    :checked="false"
                />

                <x-ui.section-divider />

                <x-form.select
                    id="warehouses_id"
                    name="warehouses_id"
                    :label="__('localization.leftovers.form.warehouses')"
                    :placeholder="__('localization.leftovers.form.warehouses_placeholder')"
                    data-dictionary="warehouses"
                />

                <x-form.select
                    id="cell_id"
                    name="cell_id"
                    :label="__('localization.leftovers.form.cell')"
                    :placeholder="__('localization.leftovers.form.cell_placeholder')"
                    data-dependent="warehouses_id"
                    data-dependent-param="warehouse_id"
                    data-dictionary-base="cells_by_warehouse"
                />

                <div id="message" class="mt-1"></div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <button type="button" class="btn btn-primary" id="submit">
                {{ __('localization.leftovers.add') }}
            </button>
        </x-slot>
    </x-modal.base>
@endsection

@section('page-script')
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/l10n/uk.js') }}"></script>

    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#leftoversDataTable'), '', 80, 120);
    </script>

    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#leftoversDataTable'), '');
    </script>

    <script type="module" src="{{ asset('assets/js/entity/leftovers/leftovers.js') }}"></script>

    <script
        type="module"
        src="{{ asset('assets/js/utils/dictionary/selectDictionaryRelated.js') }}"
    ></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lang = '{{ app()->getLocale() }}'; // Laravel локаль

            flatpickr('.flatpickr-basic', {
                dateFormat: 'Y-m-d',
                locale: lang,
            });
        });
    </script>

    <script src="{{ asset('assets/js/entity/leftovers/utils/expiration-calculator.js') }}"></script>
@endsection
