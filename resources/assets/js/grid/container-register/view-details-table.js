import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { switchLang } from '../components/switch-lang.js';
import { getLocalizedText } from '../../localization/container-register/getLocalizedText.js';
import { hideLoader, showLoader } from '../components/loader.js';

$(document).ready(function () {
    let table = $('#view-details-table');
    let isRowHovered = false;
    let isTestMode = false;

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

        // allocation object (колишній placing)
        { name: 'allocation_zone', map: 'allocation>zone', type: 'string' },
        { name: 'allocation_sector', map: 'allocation>sector', type: 'string' },
        { name: 'allocation_warehouse', map: 'allocation>warehouse', type: 'string' },
        { name: 'allocation_location', map: 'allocation>location', type: 'string' },
        { name: 'allocation_cell', map: 'allocation>cell', type: 'string' },

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
                  `/leftover-to-container/table/filter/${containerRegisterId}`,
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
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
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
                    return `<div class="d-flex flex-column ps-50 gap-25">
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
                width: 180,
                editable: false,
                cellsrenderer: function (row, column, value) {
                    const isTrue = value === true || value === 1 || value === 'true'; // підстрахуємося
                    const badgeClass = isTrue ? 'bg-success' : 'bg-danger';

                    // Локалізація тексту
                    const language = switchLang();
                    const badgeText = isTrue
                        ? language === 'en'
                            ? 'Not damaged'
                            : 'Не пошкоджений'
                        : language === 'en'
                          ? 'Damaged'
                          : 'Пошкоджений';

                    return `
                            <div class="d-flex align-items-center ps-50 gap-50">
                                <div class="${badgeClass} border-radius-50" style="width: 10px; height: 10px;"></div>
                                <span class="fw-bolder">${badgeText}</span>
                            </div>`;
                },
            },

            //     {
            //         dataField: 'allocation',
            //         text: getLocalizedText('tableColumns.placing'),
            //         width: 250,
            //         cellsrenderer: function (
            //             row,
            //             column,
            //             value,
            //             defaulthtml,
            //             columnproperties,
            //             rowdata
            //         ) {
            //             const place = rowdata || {};
            //
            //             return `<div class="d-flex flex-column ps-50 gap-25">
            //     <div class="text-dark fw-bolder my-auto">${place.allocation_warehouse || '-'}</div>
            //     <div>${place.allocation_zone || '-'}</div>
            //     <div>${place.allocation_sector || '-'}</div>
            //     <div>${place.allocation_location || '-'}</div>
            //     <div>${place.allocation_cell || '-'}</div>
            //
            // </div>`;
            //         },
            //     },

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
                    <span>${qty.quantity_package_quantity} ${qty.quantity_package}</span>

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
        { label: getLocalizedText('tableColumns.placing'), value: 'allocation', checked: true },
        {
            label: getLocalizedText('tableColumns.quantity'),
            value: 'quantity_field',
            checked: true,
        },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});

const customData = [];
