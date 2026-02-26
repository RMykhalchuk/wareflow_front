import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { getLocalizedText } from '../../localization/warehouse/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';

const pagerRendererLeftovers = pagerRenderer.bind({});
const toolbarRendererLeftovers = toolbarRender.bind({});

$(document).ready(function () {
    let table = $('#warehouse-table');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },
        { name: 'name', type: 'string' },
        // замість 'object' розкладаємо поля cell
        { name: 'location_name', map: 'location>name', type: 'string' },
        { name: 'location_id', map: 'location>id', type: 'string' },
        { name: 'location', type: 'string' },
        { name: 'type', type: 'string' },
        { name: 'erp', type: 'string' },
    ];

    var myDataSource = {
        datatype: 'json',
        datafields: dataFields,
        url: window.location.origin + languageBlock + '/warehouses/table/filter',
        root: 'data',
        beforeprocessing: function (data) {
            // console.log(data);
            myDataSource.totalrecords = data.total;
        },
        filter: function () {
            // update the grid and send a request to the server.
            showLoader();
            table.jqxGrid('updatebounddata', 'filter');
        },
        sort: function () {
            $('.search-btn')[0].click();
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
            return pagerRendererLeftovers(table);
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
        rowsheight: 35,
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

            return toolbarRendererLeftovers(statusbar, table, false, 1, columnCount - 1); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                dataField: 'local_id',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('id'),
                width: 70,
                editable: false,
                cellsrenderer: function (row, column, value, rowdata) {
                    return `<p style="" class="text-secondary ps-50 my-auto">${value}</p>`;
                },
            },
            {
                dataField: 'name',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexName'),
                minwidth: 120,
                editable: false,
                cellsrenderer: function (row, column, value, defaultHtml, columnSettings, rowData) {
                    return `<a href="${window.location.origin + languageBlock + '/warehouses/' + rowData.id}"  style="color: #D9B414;" class="ps-50 text-dark fw-bold my-auto">${value}</a>`;
                },
            },
            {
                minwidth: 300,
                dataField: 'location_data',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexAddress'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<a class="ps-50 text-dark fw-bold my-auto" href="${window.location.origin + languageBlock + '/locations/' + rowdata.location_id}" >${rowdata.location_name}</a>`;
                },
            },
            {
                width: 140,
                dataField: 'type',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexType'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<p class=" text-secondary ps-50 my-auto">${value}</p>`;
                },
            },

            {
                width: 180,
                dataField: 'erp',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('erp'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    if (!value) return `<p class="text-secondary ps-50 my-auto">-</p>`;
                    return `
                        <div class="d-flex flex-column ps-50">
                            <span class="text-dark my-auto d-inline-block text-truncate"
                                style="max-width: calc(180px - 0.5rem);"
                                title="${value}">${value}</span>
                        </div>`;
                },
            },
            {
                width: '70px',
                dataField: 'action',
                align: 'center',
                cellsalign: 'center',
                text: getLocalizedText('tableIndexAction'),
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
                    const buttonId = `button-${rowdata.uid}`;
                    const popoverId = `popover-${rowdata.uid}`;

                    const button = `
                                            <button id="${buttonId}" style="padding:0" class="btn btn-table-cell" type="button" data-bs-toggle="popover">
                                                <img src="${window.location.origin}/assets/libs/jqwidget/jqwidgets/styles/images/castom-light-wms/menu_dots_vertical.svg" alt="menu_dots_vertical">
                                            </button>
                                        `;

                    const popoverOptions = {
                        html: true,
                        sanitize: false,
                        placement: 'left',
                        trigger: 'focus',
                        container: 'body',
                        content: function () {
                            return `
                                    <div id="${popoverId}">
                                        <ul class="popover-castom" style="list-style: none">
                                            <li>
                                                <a class="dropdown-item" href="${window.location.origin}${languageBlock}/warehouses/${rowdata.id}">
                                                    ${getLocalizedText('tableIndexViewWarehouse')}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="${window.location.origin}${languageBlock}/warehouses/${rowdata.id}/edit">
                                                    ${getLocalizedText('tableIndexEditWarehouseProfile')}
                                                </a>
                                            </li>
                                            <!--
                                            <li>
                                                <a class="dropdown-item" href="${window.location.origin}/user/update/${rowdata.uid + 1}">
                                                    Редагувати розклад <br> роботи складу
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#">Деактивувати склад</a>
                                            </li>
                                            -->
                                        </ul>
                                    </div>
                                `;
                        },
                    };

                    $(document)
                        .off('click', `#${buttonId}`)
                        .on('click', `#${buttonId}`, function () {
                            $(this).popover(popoverOptions).popover('show');
                        });

                    return `<div class="jqx-popover-wrapper">${button}</div>`;
                },
            },
        ],
    });

    let listSource = [
        { label: getLocalizedText('id'), value: 'local_id', checked: true },
        { label: getLocalizedText('tableIndexName'), value: 'name', checked: true },
        { label: getLocalizedText('tableIndexAddress'), value: 'location_data', checked: true },
        { label: getLocalizedText('tableIndexType'), value: 'type', checked: true },
        { label: getLocalizedText('erp'), value: 'erp', checked: true },
        { label: getLocalizedText('tableIndexAction'), value: 'action', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});
