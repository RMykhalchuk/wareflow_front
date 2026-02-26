import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { getLocalizedText } from '../../localization/leftovers/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';
import { hover } from '../components/hover.js';

$(document).ready(function () {
    let table = $('#leftovers-erp-table');
    let isRowHovered = false;
    let isTestMode = false; // <<< Перемикач тестового режиму

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },

        // name
        { name: 'name_id', map: 'goods>id', type: 'string' },
        { name: 'name_name', map: 'goods>name', type: 'string' },
        { name: 'unit_name', map: 'goods>measurement_unit>name', type: 'string' },

        // { name: 'name_barcode', map: 'goods>barcode', type: 'string' },
        // { name: 'name_manufacturer', map: 'goods>manufacturer', type: 'string' },
        // { name: 'name_category', map: 'goods>category', type: 'string' },
        // { name: 'name_provider', map: 'goods>provider', type: 'string' },

        // { name: 'leftovers_wms', type: 'number' },
        { name: 'warehouse_erp_id', map: 'warehouse_erp>id', type: 'string' },
        { name: 'warehouse_erp_name', map: 'warehouse_erp>name', type: 'string' },
        // { name: 'leftovers_erp', type: 'number' },
        { name: 'quantity_erp', type: 'string' },

        // { name: 'divergence', type: 'string' },
    ];

    // ======= Джерело даних =======
    let myDataSource = isTestMode
        ? {
              datatype: 'array',
              datafields: dataFields,
              localdata: customData,
          }
        : {
              datatype: 'json',
              datafields: dataFields,
              url: window.location.origin + languageBlock + '/leftovers-erp/table/filter',
              root: 'data',
              beforeprocessing: function (data) {
                  console.log(data);
                  myDataSource.totalrecords = data.total;
              },
              filter: function () {
                  showLoader();
                  table.jqxGrid('updatebounddata', 'filter');
              },
              sort: function () {
                  showLoader();
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
        enablehover: false,
        columnsreorder: false,
        autoshowfiltericon: true,
        pagermode: 'advanced',
        rowsheight: 50,
        filterbarmode: 'simple',
        toolbarHeight: 55,
        showToolbar: true,
        filter: function () {
            var columnindex = table.jqxGrid('getcolumnindex', 'Action');

            var filterinfo = table.jqxGrid('getfilterinformation')[columnindex];

            // Disable filtering for the "Name" column
            if (filterinfo != null && filterinfo.filter != null) {
                filterinfo.filter.setlogic('and');
                filterinfo.filter.setoperator(0);
                filterinfo.filter.setvalue('');
            }
        },
        rendertoolbar: function (statusbar) {
            var columns = table.jqxGrid('columns').records;
            var columnCount = columns.length;

            return toolbarRender(statusbar, table, false, 1, columnCount - 1, ''); // Subtract 1 to exclude the action column
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
                text: getLocalizedText('name'),
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
                                <a href="${window.location.origin + languageBlock}/sku/${rowdata.name_id}" class="text-dark fw-bolder text-decoration-underline">${rowdata.name_name ?? '-'}</a>
                             </div>`;
                    // <span>${rowdata.name_barcode ?? '-'}</span>
                    // <span>${rowdata.name_manufacturer ?? '-'}</span>
                    // <span>${rowdata.name_category ?? '-'}</span>
                    // <span>${rowdata.name_provider ?? '-'}</span>
                },
            },
            // {
            //     text: getLocalizedText('leftovers_wms'),
            //     dataField: 'leftovers_wms',
            //     width: 150,
            //     cellsrenderer: function (row, column, value) {
            //         return `<p class="ps-50 my-auto">${value ? value : '-'}</p>`;
            //     },
            // },
            {
                text: getLocalizedText('warehouse_erp'),
                dataField: 'warehouse_erp_field',
                width: 150,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<p class="ps-50 my-auto">${rowdata.warehouse_erp_name ? rowdata.warehouse_erp_name : '-'}</p>`;
                },
            },
            {
                text: getLocalizedText('leftovers_erp'),
                dataField: 'leftovers_erp_field',
                width: 150,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const lang = document.documentElement.lang || 'uk';
                    const rawUnit = rowdata.unit_name;
                    const unitName =
                        rawUnit && typeof rawUnit === 'object'
                            ? (rawUnit[lang] ?? rawUnit['en'] ?? '')
                            : (rawUnit ?? '');
                    return `<p class="ps-50 my-auto">${rowdata.quantity_erp ? rowdata.quantity_erp + ' ' + unitName : '-'}</p>`;
                },
            },
            // {
            //     text: getLocalizedText('divergence'),
            //     dataField: 'divergence',
            //     width: 150,
            //     cellsrenderer: function (row, column, value) {
            //         const strValue = value?.toString?.() ?? '';
            //         const cellClass =
            //             strValue.startsWith('+') || strValue.startsWith('-')
            //                 ? 'bg-light-danger '
            //                 : '';
            //         return `<div class="flex w-100 h-100 ps-50 align-content-center ${cellClass}"><span class="text-dark">${value ? value : '-'}</span></div>`;
            //     },
            // },
            {
                width: '70px',
                dataField: 'action',
                align: 'center',
                cellsalign: 'center',
                text: getLocalizedText('action'),
                renderer: function () {
                    return '<div></div>';
                },
                filterable: false,
                sortable: false,
                id: 'action',
                cellClassName: 'action-table-drop ',
                className: 'action-table',
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    var buttonId = 'button-' + rowdata.uid;
                    var popoverId = 'popover-' + rowdata.uid;

                    const button = `
                                            <button id="${buttonId}" style="padding:0" class="btn btn-table-cell" type="button" data-bs-toggle="popover">
                                                <img src="${window.location.origin}/assets/libs/jqwidget/jqwidgets/styles/images/castom-light-wms/menu_dots_vertical.svg" alt="menu_dots_vertical">
                                            </button>
                                        `;

                    var popoverOptions = {
                        html: true,
                        sanitize: false,
                        placement: 'left',
                        trigger: 'focus',
                        container: 'body',
                        content: function () {
                            return `
                                    <div id="${popoverId}">
                                        <ul class="popover-castom" class="list-unstyled">
                                            <li>
                                                <a class="dropdown-item" href="${window.location.origin}${languageBlock}/leftovers-erp/${rowdata.uid}">
                                                    ${getLocalizedText('actionBtn1')}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                `;
                        },
                    };

                    $(document)
                        .off('click', '#' + buttonId)
                        .on('click', '#' + buttonId, function () {
                            $(this).popover(popoverOptions).popover('show');
                        });

                    return '<div class="jqx-popover-wrapper">' + button + '</div>';
                },
            },
        ],
    });

    let listSource = [
        { label: getLocalizedText('id'), value: 'local_id', checked: true },
        { label: getLocalizedText('name'), value: 'name_field', checked: true },
        // {
        //     label: getLocalizedText('leftovers_wms'),
        //     value: 'leftovers_wms',
        //     checked: true,
        // },
        {
            label: getLocalizedText('warehouse_erp'),
            value: 'warehouse_erp_field',
            checked: true,
        },
        {
            label: getLocalizedText('leftovers_erp'),
            value: 'leftovers_erp_field',
            checked: true,
        },
        // { label: getLocalizedText('divergence'), value: 'divergence', checked: true },

        { label: getLocalizedText('action'), value: 'action', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});

let customData = [];
