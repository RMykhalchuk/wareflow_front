import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { switchLang } from '../components/switch-lang.js';
import { getLocalizedText } from '../../localization/tasks/getLocalizedText.js';
import { hideLoader, showLoader } from '../components/loader.js';

$(document).ready(function () {
    let table = $('#view-details-table');
    let isRowHovered = false;
    let isTestMode = true;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },

        // name
        { name: 'name_name', map: 'goods>name', type: 'string' },
        { name: 'name_barcode', map: 'goods>barcode', type: 'string' },
        { name: 'name_manufacturer', map: 'goods>manufacturer', type: 'string' },
        { name: 'name_category', map: 'goods>category', type: 'string' },
        { name: 'name_provider', map: 'goods>provider', type: 'string' },

        { name: 'batch', type: 'string' },

        { name: 'manufacture_date', type: 'string' },
        { name: 'bb_date', type: 'string' },
        { name: 'package', type: 'string' },
        { name: 'has_condition', type: 'bool' },

        // quantity object
        {
            name: 'quantity_measurement_unit_quantity',
            map: 'quantity>measurement_unit_quantity',
            type: 'number',
        },
        { name: 'quantity_measurement_unit', map: 'quantity>measurement_unit', type: 'string' },
        { name: 'quantity_package_quantity', map: 'quantity>package_quantity', type: 'number' },
        { name: 'quantity_package', map: 'quantity>package', type: 'string' },
    ];

    let myDataSource = isTestMode
        ? {
              datatype: 'array',
              datafields: dataFields,
              localdata: customData,
          }
        : {
              datatype: 'json',
              datafields: dataFields,
              url:
                  window.location.origin +
                  languageBlock +
                  `/leftovers-to-cell/table/filter/${cellId}`,
              root: 'data',
              beforeprocessing: function (data) {
                  console.log(data);
                  myDataSource.totalrecords = data.total;
              },
              filter: function () {
                  table.jqxGrid('updatebounddata', 'filter');
              },
              sort: function () {
                  table.jqxGrid('updatebounddata', 'sort');
              },
          };

    showLoader(); // ПЕРЕД створенням dataAdapter

    let dataAdapter = new $.jqx.dataAdapter(myDataSource, {
        loadComplete: function () {
            hideLoader();
        },
        loadError: function (xhr, status, error) {
            hideLoader();
            // console.error('Load error:', status, error);
        },
    });

    var grid = table.jqxGrid({
        theme: 'light-wms',
        width: '100%',
        autoheight: true,
        pageable: true,
        showdefaultloadelement: false,
        source: dataAdapter,
        pagerRenderer: function () {
            return pagerRenderer(table);
        },
        virtualmode: true,
        rendergridrows: function () {
            return dataAdapter.records;
        },
        ready() {
            checkUrl();
        },
        sortable: false,
        columnsResize: false,
        filterable: false,
        filtermode: 'default',
        localization: getLocalization(language),
        selectionMode: 'checkbox',
        enablehover: false,
        columnsreorder: false,
        autoshowfiltericon: true,
        pagermode: 'advanced',
        rowsheight: 200,
        filterbarmode: 'simple',
        toolbarHeight: 55,
        showToolbar: true,
        rendertoolbar: function (statusbar) {
            var columns = table.jqxGrid('columns').records;
            var columnCount = columns.length;
            return toolbarRender(statusbar, table, false, 1, columnCount - 1);
        },
        columns: [
            {
                dataField: 'local_id',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('id'),
                width: 50,
                editable: false,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const rowClass = highlightRow(rowdata);
                    return `<div class="${rowClass} d-flex flex-column justify-content-center ps-50 my-auto">${value}</div>`;
                },
            },
            {
                dataField: 'name_field',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('tableColumns.name'),
                minwidth: 400,
                editable: false,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const rowClass = highlightRow(rowdata);
                    return `<div class="${rowClass} d-flex flex-column justify-content-center ps-50 gap-25">
            <span class="fw-bolder">${rowdata.name_name ?? '-'}</span>
            <span>${rowdata.name_barcode ?? '-'}</span>
            <span>${rowdata.name_manufacturer ?? '-'}</span>
            <span>${rowdata.name_category ?? '-'}</span>
            <span>${rowdata.name_provider ?? '-'}</span>
        </div>`;
                },
            },
            {
                dataField: 'batch',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('tableColumns.batch'),
                width: 150,
                editable: false,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('tableColumns.manufactured'),
                dataField: 'manufacture_date',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('tableColumns.usedUntil'),
                dataField: 'bb_date',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('tableColumns.pack'),
                dataField: 'package',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                dataField: 'has_condition',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('tableColumns.condition'),
                width: 150,
                editable: false,
                cellsrenderer: function (row, column, value) {
                    const isTrue = value === true || value === 1 || value === 'true'; // підстрахуємося
                    const badgeClass = isTrue ? 'bg-success' : 'bg-danger';

                    // Локалізація тексту
                    const language = switchLang();
                    const badgeText = isTrue
                        ? language === 'en'
                            ? 'GOOD'
                            : 'ДОБРА'
                        : language === 'en'
                          ? 'BAD'
                          : 'ПОГАНА';

                    return `<span class="badge ${badgeClass} text-white ms-50 py-50 px-1">${badgeText}</span>`;
                },
            },
            {
                text: 'Container',
                dataField: 'container_field',
                width: 150,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const containerName = `К${rowdata.batch ?? rowdata.local_id}`;
                    const isSelected = window.selectedContainers?.includes(containerName);

                    // обгортка: додаємо клас на всю клітинку (і через CSS розтягуємо на рядок)
                    return `
                    <div class="d-flex align-items-center gap-25 ${isSelected ? 'row-selected' : ''}">
                        <input type="checkbox"
                               class="container-checkbox"
                               data-container="${containerName}"
                               ${isSelected ? 'checked' : ''} />
                        <span class="fw-bolder">${containerName}</span>
                    </div>
                `;
                },
            },

            {
                dataField: 'quantity_field',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('tableColumns.quantity'),
                width: 150,
                editable: false,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    // value вже об'єкт
                    const qty = rowdata || {};

                    // <span>${qty.area} ${getLocalizedText('areaUnit')}</span>;
                    // <span>${qty.pallets} ${getLocalizedText('palletsUnit')}</span>;

                    return `<div class="d-flex flex-column ps-50 gap-25">
                    <span>${qty.quantity_measurement_unit_quantity} ${qty.quantity_measurement_unit}</span>
                    <span>(${qty.quantity_package_quantity} ${qty.quantity_package})</span>

                </div>`;
                },
            },
        ],
    });

    let listSource = [
        { label: getLocalizedText('id'), value: 'local_id', checked: true },
        { label: getLocalizedText('tableColumns.name'), value: 'name_field', checked: true },
        { label: getLocalizedText('tableColumns.batch'), value: 'batch', checked: true },
        {
            label: getLocalizedText('tableColumns.manufactured'),
            value: 'manufacture_date',
            checked: true,
        },
        { label: getLocalizedText('tableColumns.usedUntil'), value: 'bb_date', checked: true },
        { label: getLocalizedText('tableColumns.pack'), value: 'package', checked: true },
        {
            label: getLocalizedText('tableColumns.condition'),
            value: 'has_condition',
            checked: true,
        },
        {
            label: getLocalizedText('tableColumns.quantity'),
            value: 'quantity_field',
            checked: true,
        },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);

    $('#view-details-table').on('change', '.container-checkbox', function () {
        const containerName = $(this).data('container');

        if ($(this).is(':checked')) {
            if (!window.selectedContainers.includes(containerName)) {
                window.selectedContainers.push(containerName);
            }
        } else {
            window.selectedContainers = window.selectedContainers.filter(
                (c) => c !== containerName
            );
        }

        $('#view-details-table').jqxGrid('refresh'); // 🔄 перерендер рядків
    });
});

const customData = [
    {
        id: '9f164485-9e9a-4f98-866f-8c114c11e52b',
        local_id: 1,
        goods: {
            name: 'Pavlo',
            barcode: null,
            manufacturer: 'івапрол',
            category: 'Продукти харчування, б/а напої без дотримання температурного режиму',
            provider: '11',
        },
        status: {
            value: 1,
            name: 'ACTIVE',
            label: 'Активний',
            label_en: 'active',
            description: 'Товар доступний...',
            is_available: true,
            is_reserved: false,
        },
        batch: '1',
        manufacture_date: '2025-09-05',
        bb_date: '2025-09-04',
        package: '1',
        has_condition: false,
        quantity: {
            measurement_unit_quantity: 2,
            measurement_unit: 'm²',
            package_quantity: 2,
            package: '1',
        },
    },
    {
        id: '9f164485-9e9a-4f98-866f-8c114c11e52b',
        local_id: 1,
        goods: {
            name: 'Pavlo',
            barcode: null,
            manufacturer: 'івапрол',
            category: 'Продукти харчування, б/а напої без дотримання температурного режиму',
            provider: '11',
        },
        status: {
            value: 1,
            name: 'ACTIVE',
            label: 'Активний',
            label_en: 'active',
            description: 'Товар доступний...',
            is_available: true,
            is_reserved: false,
        },
        batch: '1',
        manufacture_date: '2025-09-05',
        bb_date: '2025-09-04',
        package: '1',
        has_condition: false,
        quantity: {
            measurement_unit_quantity: 2,
            measurement_unit: 'm²',
            package_quantity: 2,
            package: '1',
        },
    },
];

const highlightRow = function (rowData) {
    const containerName = `К${rowData.batch ?? rowData.local_id}`;
    return window.selectedContainers.includes(containerName) ? 'bg-success h-100 w-100' : '';
};
