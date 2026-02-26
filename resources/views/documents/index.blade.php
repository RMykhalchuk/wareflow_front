@extends('layouts.admin')

@section('title', __('localization.documents.index.title'))

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
    <script type="module" src="{{ asset('assets/js/grid/document/document-table.js') }}"></script>
@endsection

@section('content')
    <div class="d-flex align-items-center flex-column flex-lg-row justify-content-between px-2">
        @include(
            'panels.breadcrumb',
            [
                'options' => [
                    ['url' => '/document', 'name' => __('localization.documents.index.breadcrumb_start')],
                    ['name' => $documentType->name],
                ],
            ]
        )
    </div>

    @php
        /** @var User $user */
        $user = \Auth::user()->load(['workingData.warehouses', 'workingData.currentWarehouse']);

        $wd = $user->workingDataByGuard;
        $warehouses = $wd?->warehouses ?? collect();
        $disabled = $warehouses->isEmpty();
        // Встановлюємо ID складу, який буде використовуватися для фільтрації при завантаженні
        $warehouseId = $wd?->current_warehouse_id;

        // Передаємо лише базовий URL без фільтрації для ініціалізації сітки
        $language = \App::getLocale() === 'en' ? '' : '/' . \App::getLocale();
        $baseUrl = url($language . '/tasks/table/filter');

        $isTestMode = true; // або false, щоб приховати кнопку
    @endphp

    @php
        $routeParams = ['document_type' => $documentType->id];

        if ($warehouseId) {
            $routeParams['warehouse_id'] = $warehouseId;
        }

        $buttonRoute = route('document.create', $routeParams);
    @endphp

    @if ($documentsCount)
        <x-layout.index-table-card
            class="m-2"
            :title="__('localization.documents.index.document_title', ['name' => strtolower($documentType->name)])"
            :buttonText="__('localization.documents.index.create_document')"
            :buttonRoute="$buttonRoute"
            tableId="documentDataTable"
        ></x-layout.index-table-card>
    @else
        <x-layout.index-empty-message
            :title="__('localization.documents.index.no_documents_message')"
            :message="__('localization.documents.index.create_document_message')"
            :buttonText="__('localization.documents.index.create_document_no_message_btn')"
            :buttonRoute="$buttonRoute"
        />
    @endif
@endsection

@section('page-script')
    @if ($documentsCount)
        <script type="module">
            import { tableSetting } from '{{ asset('assets/js/grid/components/table-setting.js') }}';

            tableSetting($('#documentDataTable'));
        </script>

        <script type="module">
            import { offCanvasByBorder } from '{{ asset('assets/js/utils/offCanvasByBorder.js') }}';

            offCanvasByBorder($('#documentDataTable'));
        </script>

        <script>
            let settings = {!! $documentType->settings !!};
            const settingsType = @json($documentStructure->settings);

            const fieldsDataSets = [
                settingsType?.fields?.header || {},
                settings?.fields?.header || {},
            ];

            const customBlocks = settings?.custom_blocks || [];

            const fields = [];
            const columns = [];
            const listSourceArray = [];
        </script>

        <script type="module">
            import { translateDocumentColumnName } from '{{ asset('assets/js/localization/document/translateDocumentColumnName.js') }}';

            /**
             * 🧩 Універсальна функція для створення конфігів колонок і полів
             */
            function processFields(data, prefix) {
                Object.entries(data).forEach(([key, item]) => {
                    const localizedText = translateDocumentColumnName(item.name);
                    const dataField = `${prefix}${key}`;

                    fields.push({ name: dataField, type: 'string' });

                    const baseColumn = {
                        minwidth: 200,
                        dataField,
                        align: 'left',
                        cellsalign: 'left',
                        text: localizedText,
                    };

                    if (key.includes('uploadFile_')) {
                        baseColumn.cellsrenderer = (row, column, rowData) => {
                            const rowObj = $('#documentDataTable').jqxGrid('getrowdata', row);
                            const filesArray = rowData.split(', ');
                            const html = filesArray
                                .map((file) => {
                                    const [name, ext] = file.split('.');
                                    const md5String = md5(`${name}_${rowObj.id}`) + `.${ext}`;
                                    const url = `${window.location.origin}/uploads/documents/${md5String}`;
                                    return `<a href="${url}" class="text-decoration-none" download>${file}</a>`;
                                })
                                .join(' ');
                            return `<div>${html}</div>`;
                        };
                    }

                    columns.push(baseColumn);
                    listSourceArray.push({
                        label: localizedText,
                        value: dataField,
                        checked: true,
                    });
                });
            }

            // 🔁 Обробляємо звичайні дані (header з settings і settingsType)
            fieldsDataSets.forEach((data) => processFields(data, 'data->header->'));

            // 🔁 Обробляємо custom_blocks
            customBlocks.forEach((block, i) => {
                processFields(block, `data->custom_blocks->${i}->`);
            });

            // console.log(fields, columns, listSourceArray);
        </script>

        <script src="http://www.myersdaily.org/joseph/javascript/md5.js"></script>
    @endif

    <script>
        window.baseUrlDocuments = '{{ $baseUrl }}';
    </script>

    <script type="module">
        import { translateDocTypesName } from '{{ asset('assets/js/localization/document-type/translateDocTypesName.js') }}';

        translateDocTypesName('.breadcrumb-item-name-active-js');
        translateDocTypesName('.card-title');
    </script>
@endsection
