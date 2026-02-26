import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { getLocalizedText } from '../../localization/location/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';

$(document).ready(function () {
    let table = $('#location-table');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },
        { name: 'name', type: 'string' },
        { name: 'company_name', type: 'string' },
        { name: 'company_id', type: 'string' },
        { name: 'country_name', type: 'string' },
        { name: 'settlement_name', type: 'string' },
        { name: 'street_info', type: 'string' },
    ];

    var myDataSource = {
        datatype: 'json',
        datafields: dataFields,
        url: window.location.origin + languageBlock + '/locations/table/filter',
        root: 'data',
        beforeprocessing: function (data) {
            // console.log(data);
            myDataSource.totalrecords = data.total;
        },
        filter: function () {
            showLoader();
            // update the grid and send a request to the server.
            table.jqxGrid('updatebounddata', 'filter');
        },
        sort: function () {
            // update the grid and send a request to the server.
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

            return toolbarRender(statusbar, table, false, 1, columnCount - 1); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                dataField: 'local_id',
                align: 'left',
                cellsalign: 'right',
                text: '№',
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
                    return `<a href="${window.location.origin + languageBlock + '/locations/' + rowData.id}" class="ps-50 fw-bold my-auto text-dark">${value}</a>`;
                },
            },
            {
                dataField: 'company',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexCompany'),
                width: 160,
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowData
                ) {
                    const company_id = rowData.company_id;
                    const company_name = rowData.company_name;

                    return `<a class="ps-50 my-auto fw-bold text-dark" href="${window.location.origin + languageBlock + '/companies/' + company_id}">${company_name}</a>`;
                },
            },
            {
                minwidth: 300,
                dataField: 'address_data',
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
                    rowData
                ) {
                    const country_name = rowData.country_name;
                    const settlement_name = rowData.settlement_name;
                    const street_info_title = rowData.street_info.title;
                    const street_info_building = rowData.street_info.building;

                    return `<p class="ps-50 text-secondary my-auto" href="#" >${street_info_title} ${street_info_building} ${settlement_name} ${country_name} </p>`;
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
                                                <a class="dropdown-item" href="${window.location.origin}${languageBlock}/locations/${rowdata.id}">
                                                    ${getLocalizedText('tableIndexViewWarehouse')}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="${window.location.origin}${languageBlock}/locations/${rowdata.id}/edit">
                                                    ${getLocalizedText('tableIndexEditWarehouseProfile')}
                                                </a>
                                            </li>
                                            <!--
                                            <li>
                                                <a class="dropdown-item" href="${window.location.origin}/users/update/${rowdata.uid + 1}">
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

                    $(document)
                        .off('click', `#${popoverId} .delete-row`)
                        .on('click', `#${popoverId} .delete-row`, function () {
                            const rowId = rowdata.uid;
                            grid.jqxGrid('deleterow', rowId);
                            $(`#${buttonId}`).popover('hide');
                        });

                    return `<div class="jqx-popover-wrapper">${button}</div>`;
                },
            },
        ],
    });

    let listSource = [
        { label: '№', value: 'local_id', checked: true },
        { label: getLocalizedText('tableIndexName'), value: 'name', checked: true },
        { label: getLocalizedText('tableIndexCompany'), value: 'company', checked: true },
        { label: getLocalizedText('tableIndexAddress'), value: 'address', checked: true },
        { label: getLocalizedText('tableIndexAction'), value: 'action', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});
