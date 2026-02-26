@extends('layouts.admin')

@section('title', __('localization.documents.create.title'))

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
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}"
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

    <script type="module" src="{{ asset('assets/js/grid/document/sku-table.js') }}"></script>
@endsection

@section('content')
    <x-layout.container
        fluid
        id="document-container"
        data-warehouse-id="{{request('warehouse_id')}}"
        data-document-type-id="{{$documentType->id}}"
        data-document-type-kind="{{$documentType->kind}}"
    >
        <x-slot:header>
            @include(
                'panels.breadcrumb',
                [
                    'options' => [
                        ['url' => '/document', 'name' => __('localization.documents.create.documents')],
                        [
                            'url' =>
                                '/document/table/' .
                                $documentType->id .
                                (request('warehouse_id') ? '?warehouse_id=' . request('warehouse_id') : ''),
                            'name' => $documentType->name,
                        ],
                        ['name' => __('localization.documents.create.creating')],
                    ],
                ]
            )

            <x-ui.header-actions>
                <x-modal.modal-trigger-button
                    id="cancel_button"
                    target="cancel_button_modal"
                    class="btn btn-flat-secondary"
                    icon="x"
                    iconStyle="mr-0"
                />
            </x-ui.header-actions>
        </x-slot>

        <x-slot:slot>
            <x-page-title
                :title="__('localization.documents.create.new_document_title') . ' “<span class=\'x-page-title-document-name\'>' . $documentType->name . '</span>”'"
            />

            <x-card.nested>
                <x-slot:header>
                    @php
                        $headerName = $documentType->settings()['header_name'];
                        $translatedHeaderKey = Str::slug($headerName, '_');
                        $localizationHeaderKey = 'localization.documents.fields.' . $documentType->kind . '.title.' . $translatedHeaderKey;
                    @endphp

                    <x-section-title>
                        {{ Lang::has($localizationHeaderKey) ? __($localizationHeaderKey) : $headerName }}
                    </x-section-title>
                </x-slot>

                <x-slot:body>
                    <form method="post" id="header_form" data-type="{{ $documentType->id }}">
                        <div class="card-body px-0 pt-1">
                            <div class="row">
                                @php
                                    $isStructure = true;
                                @endphp

                                @foreach (collect($documentStructure->settings['fields']['header'])->sortBy('id') as $key => $field)
                                    @include('documents.fields.create-block-generator')
                                @endforeach

                                @php
                                    $isStructure = false;
                                @endphp

                                @foreach (collect(json_decode($documentType->settings, true)['fields']['header'])->sortBy('id') as $key => $field)
                                    @include('documents.fields.create-block-generator')
                                @endforeach

                                <div id="validation-message"></div>
                            </div>
                        </div>
                    </form>
                </x-slot>
            </x-card.nested>

            @if (array_key_exists('custom_blocks', $documentType->settings()))
                @foreach ($documentType->settings()['custom_blocks'] as $i => $block)
                    <x-card.nested>
                        <x-slot:header>
                            @php
                                $blockName = $documentType->settings()['block_names'][$i];
                                $translatedBlockKey = Str::slug($blockName, '_');
                                $localizationBlockKey = 'localization.documents.fields.' . $documentType->kind . '.title.' . $translatedBlockKey;
                            @endphp

                            <x-section-title>
                                {{ Lang::has($localizationBlockKey) ? __($localizationBlockKey) : $blockName }}
                            </x-section-title>
                        </x-slot>

                        <x-slot:body>
                            <form method="post" id="custom_form_{{ $i }}" class="custom-block">
                                <div class="card-body px-0 pt-1">
                                    <div class="row">
                                        @foreach (collect($block)->sortBy('id') as $key => $field)
                                            @include('documents.fields.create-custom-block-generator')
                                        @endforeach

                                        <div id="validation-message"></div>
                                    </div>
                                </div>
                            </form>
                        </x-slot>
                    </x-card.nested>
                @endforeach
            @endif

            <x-card.nested>
                <x-slot:body>
                    <x-layout.index-table-card
                        :title="__('localization.documents.create.sku')"
                        tableId="sku-table"
                        class="mx-0 d-none table-block"
                    />

                    <div
                        class="js-add-block d-block px-1 mt-1 gap-1 bg-secondary-100 d-flex flex-column justify-content-center rounded align-items-center text-dark height-200 p-1"
                    >
                        <h3 class="text-dark">
                            {{ __('localization.documents.create.add_sku') }}
                        </h3>

                        <x-modal.modal-trigger-button
                            id="add_table_button"
                            target="add_table"
                            class="btn btn-outline-secondary"
                            :text="__('localization.documents.create.add')"
                            icon="plus"
                        />
                    </div>

                    <div class="mt-1 px-0" id="sku-error"></div>

                    <!-- Нижня кнопка (спочатку ховаємо) -->
                    <div class="d-flex justify-content-end mt-2 js-add-bottom-button d-none">
                        <x-modal.modal-trigger-button
                            id="add_table_button"
                            target="add_table"
                            class="btn btn-outline-secondary"
                            :text="__('localization.documents.create.add')"
                            icon="plus"
                        />
                    </div>

                    <div class="mt-1" id="sku-data-message_full"></div>
                </x-slot>
            </x-card.nested>

            <div class="d-flex justify-content-end">
                <x-ui.action-button
                    id="document-save"
                    class="btn btn-primary mb-2"
                    :text="__('localization.documents.create.save')"
                />
            </div>
        </x-slot>
    </x-layout.container>

    <x-cancel-modal
        id="cancel_button_modal"
        route="{{ '/document/table/' . $documentType->id . (request('warehouse_id') ? '?warehouse_id=' . request('warehouse_id') : '') }}"
        title="{{ __('localization.documents.cancel.create.modal.title') }}"
        content="{!! __('localization.documents.cancel.create.modal.content') !!}"
        cancel-text="{{ __('localization.documents.cancel.cancel_button') }}"
        confirm-text="{{ __('localization.documents.cancel.confirm_button') }}"
    />

    <x-modal.base id="add_table" size="modal-lg" style="max-width: 680px !important">
        <x-slot name="header">
            <div class="d-flex align-items-start flex-grow-1 px-50 flex-column">
                <x-ui.section-card-title level="2" class="modal-title">
                    {{ __('localization.documents.create.add_sku') }}
                </x-ui.section-card-title>
                <div>{{ __('localization.documents.create.select_goods') }}</div>
            </div>
        </x-slot>

        <x-slot name="body">
            <div class="row mx-0 js-modal-form">
                <div class="fw-bold text-dark">
                    {{ __('localization.documents.create.goods_title') }}
                </div>

                <x-form.select
                    id="goods"
                    name="goods"
                    label=""
                    placeholder="{{ __('localization.documents.create.goods_name') }}"
                    class="col-12"
                    data-dictionary="goods"
                    data-full-object="true"
                />

                <x-form.input-text-with-unit
                    id="goods_count"
                    name="goods_count"
                    label=""
                    placeholder="{{ __('localization.documents.create.goods_count') }}"
                    unit="-"
                    oninput="maskFractionalNumbers(this,10)"
                    class="col-12"
                />

                <div id="goods_available_hint_create" class="text-muted small mt-1 d-none"></div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <button
                type="button"
                class="btn btn-link"
                data-bs-target="#add_table"
                data-bs-toggle="modal"
                data-dismiss="modal"
            >
                {{ __('localization.documents.create.cancel') }}
            </button>
            <button type="button" class="btn btn-primary" id="submit">
                {{ __('localization.documents.create.save') }}
            </button>
        </x-slot>
    </x-modal.base>

    <x-modal.base id="edit_table" size="modal-lg" style="max-width: 680px !important">
        <x-slot name="header">
            <div class="d-flex align-items-start flex-grow-1 px-50 flex-column">
                <x-ui.section-card-title level="2" class="modal-title">
                    {{ __('localization.documents.create.edit_sku') }}
                </x-ui.section-card-title>
                <div>{{ __('localization.documents.create.select_goods') }}</div>
            </div>
        </x-slot>

        <x-slot name="body">
            <div class="row mx-0 js-modal-form2">
                <div class="fw-bold text-dark">
                    {{ __('localization.documents.create.goods_title') }}
                </div>

                <x-form.select
                    id="edit_goods"
                    name="edit_goods"
                    label=""
                    placeholder="{{ __('localization.documents.create.goods_name') }}"
                    class="col-12"
                    data-dictionary="goods"
                    data-full-object="true"
                />

                <x-form.input-text-with-unit
                    id="edit_goods_count"
                    name="edit_goods_count"
                    label=""
                    placeholder="{{ __('localization.documents.create.goods_count') }}"
                    unit="-"
                    oninput="maskFractionalNumbers(this,10)"
                    class="col-12"
                />

                <div id="goods_available_hint_edit" class="text-muted small mt-1 d-none"></div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <button
                type="button"
                class="btn btn-link"
                data-bs-target="#edit_table"
                data-bs-toggle="modal"
                data-dismiss="modal"
            >
                {{ __('localization.documents.create.cancel') }}
            </button>
            <button type="button" class="btn btn-primary" id="edit_submit">
                {{ __('localization.documents.create.save') }}
            </button>
        </x-slot>
    </x-modal.base>
@endsection

@section('page-script')
    <script>
        let settings = {!! $documentType->settings !!};
        let fieldData;
        let selectedItemData = null;
        let editIndex = null;
    </script>
    <script>
        // Використовуємо глобальну змінну
        window.tableData = window.tableData || [];
    </script>

    <script src="{{ asset('vendors/js/ui/jquery.sticky.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/flatpickr/l10n/uk.js') }}"></script>

    <script type="module" src="{{ asset('assets/js/entity/document/document.js') }}"></script>
    <script type="module" src="{{ asset('assets/js/entity/document/sku-table.js') }}"></script>

    @include('documents.module', ['folder' => 'create'])

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Отримання всіх елементів з класом "date"
            var dateInputs = document.querySelectorAll('.js-current-data');
            const lang = '{{ app()->getLocale() }}'; // Laravel локаль

            // Ітерація через кожен елемент і встановлення Flatpickr
            dateInputs.forEach(function (dateInput) {
                flatpickr(dateInput, {
                    defaultDate: 'today', // Встановлення сьогоднішньої дати за замовчуванням
                    locale: lang,
                });
            });

            flatpickr('.flatpickr-time', {
                enableTime: true, // Увімкнути вибір часу
                noCalendar: true, // Приховати календар (тільки час)
                dateFormat: 'H:i', // Формат значення у value
                locale: lang, // Локаль з Laravel
                // altInput: true, // Альтернативний інпут для красивого вигляду
                altFormat: 'H:i', // Формат відображення
            });
        });
    </script>

    <script type="module">
        import { translateDocTypesName } from '{{ asset('assets/js/localization/document-type/translateDocTypesName.js') }}';

        translateDocTypesName('.breadcrumb-item-name-active-js');
        translateDocTypesName('.breadcrumb-item-name-no-active-js');
        translateDocTypesName('.js-add-new-item-in-table-btn-modal-text');
        translateDocTypesName('.x-page-title-document-name');
    </script>

    <script type="module">
        import { translateDocTypesFieldNameRender } from '{{ asset('assets/js/localization/document-type/translateDocTypesFieldNameRender.js') }}';
        translateDocTypesFieldNameRender('.js-translate-field-title');
    </script>
@endsection
