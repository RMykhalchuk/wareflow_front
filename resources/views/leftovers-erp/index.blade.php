@extends('layouts.admin')
@section('title', __('localization.leftovers_erp.title'))

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
        src="{{ asset('assets/js/grid/leftovers-erp/leftovers-erp-table.js') }}"
    ></script>
@endsection

@php
    $isTestMode = false; // або false, щоб приховати кнопку
@endphp

@section('content')
    @if ($leftoverCount)
        @if (! $isTestMode)
            {{-- 🔵 Бойовий режим — показуємо кнопку ДОДАТИ --}}
            <x-layout.index-table-card
                :title="__('localization.leftovers_erp.title')"
                tableId="leftovers-erp-table"
                :buttonText="__('localization.leftovers.add')"
                buttonModalToggle="modal"
                buttonModalTarget="#add"
            />
        @else
            {{-- 🟡 Тестовий режим — без кнопки, можна додати select або інші елементи --}}
            <x-layout.index-table-card
                :title="__('localization.leftovers_erp.title')"
                tableId="leftovers-erp-table"
            />
        @endif
    @else
        @if (! $isTestMode)
            {{-- 🔵 Бойовий режим — показуємо кнопку в пустому стані --}}
            <x-layout.index-empty-message
                :title="__('localization.leftovers_erp.title')"
                :message="__('localization.leftovers_erp.empty_message')"
                :buttonText="__('localization.leftovers.add')"
                buttonModalToggle="modal"
                buttonModalTarget="#add"
            />
        @else
            {{-- 🟡 Тестовий режим — без кнопки --}}
            <x-layout.index-empty-message
                :title="__('localization.leftovers_erp.title')"
                :message="__('localization.leftovers_erp.empty_message')"
            />
        @endif
    @endif

    {{-- 🔵 Модалка показується тільки в бойовому режимі --}}
    @if (! $isTestMode)
        <x-modal.base id="add" size="modal-lg" style="max-width: 680px !important">
            <x-slot name="header">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <x-ui.section-card-title level="2" class="modal-title">
                        {{ __('localization.leftovers.add') }}
                    </x-ui.section-card-title>
                </div>
            </x-slot>

            <x-slot name="body">
                {{-- твоя форма без змін --}}
                <div class="row mx-0 js-modal-form">
                    <x-form.input-text
                        id="warehouse_erp_id"
                        name="warehouse_erp_id"
                        :label="'warehouse_erp_id з бд'"
                        :placeholder="'warehouse_erp_id з бд'"
                    />

                    <x-form.input-text
                        id="goods_erp_id"
                        name="goods_erp_id"
                        :label="'goods_erp_id з бд'"
                        :placeholder="'goods_erp_id з бд'"
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

                    <div id="message" class="mt-1"></div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <button type="button" class="btn btn-primary" id="submit">
                    {{ __('localization.leftovers.add') }}
                </button>
            </x-slot>
        </x-modal.base>
    @endif
@endsection

@section('page-script')
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/l10n/uk.js') }}"></script>

    <script
        type="module"
        src="{{ asset('assets/js/entity/leftovers-erp/leftovers-erp.js') }}"
    ></script>

    <script type="module">
        import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

        tableSetting($('#leftovers-erp-table'), '', 50, 65);
    </script>

    <script type="module">
        import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

        offCanvasByBorder($('#leftovers-erp-table'), '');
    </script>
@endsection
