import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hideLoader, showLoader } from '../components/loader.js';
import { hover } from '../components/hover.js';
import { getLocalizedText } from '../../localization/container/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';

$(document).ready(function () {
    let table = $('#container-table');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language == 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },
        { name: 'name', type: 'string' },
        { name: 'code_format', type: 'string' },
        { name: 'type', type: 'string' },
        // { name: 'status_id', type: 'string' },
        { name: 'reversible', type: 'number' },
    ];

    var myDataSource = {
        datatype: 'json',
        datafields: dataFields,
        url: window.location.origin + languageBlock + '/containers/table/filter',
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
            // update the grid and send a request to the server.
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

            const filterMenuData = [
                {
                    option: [
                        { label: getLocalizedText('name'), value: 'name' },
                        { label: getLocalizedText('code'), value: 'code' },
                        { label: getLocalizedText('type'), value: 'type' },
                        { label: getLocalizedText('status'), value: 'status_id' },
                        { label: getLocalizedText('reversibility'), value: 'reversible' },
                    ],
                },
                {
                    option: [{ label: getLocalizedText('company'), value: 'company' }],
                },
            ];

            return toolbarRender(statusbar, table, false, 1, columnCount - 1, '', filterMenuData); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                dataField: 'local_id',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('id'),
                width: 100,
                editable: false,
                cellsrenderer: function (row, column, value, rowdata) {
                    return `<p style="" class="text-secondary ps-50 my-auto">${value}</p>`;
                },
            },
            {
                minwidth: 250,
                dataField: 'name_and_code',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('name'), // або 'Name + Code'
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const name = rowdata.name;
                    const codeFormat = rowdata.code_format;
                    return `<div class="d-flex flex-column ps-50">
                                <a class="text-dark fw-bolder my-auto" href='${window.location.origin + languageBlock}/containers/${rowdata.id}'>${name}</a>
                                <span class="text-muted">${codeFormat}</span>
                            </div>`;
                },
            },
            {
                width: 250,
                dataField: 'type',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('type'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    //console.log(value)

                    const valuesInCell = {
                        'Тип 1': { text: getLocalizedText('type_1') },
                        'Тип 2': { text: getLocalizedText('type_2') },
                        'Тип 3': { text: getLocalizedText('type_3') },
                        'Тип 4': { text: getLocalizedText('type_4') },
                        'Тип 5': { text: getLocalizedText('type_5') },
                        'Тип 6': { text: getLocalizedText('type_6') },
                    };

                    const translateValue = valuesInCell[value] || { text: value };

                    return `<p class="ps-50 text-secondary my-auto" title="${translateValue.text || value}"> ${translateValue.text || value} </p>`;
                },
            },
            // {
            //     width: 250,
            //     dataField: 'status_id',
            //     align: 'left',
            //     cellsalign: 'center',
            //     text: getLocalizedText('status'),
            //     editable: false,
            //     cellsrenderer: function (row, column, value, rowdata) {
            //         const isUnreserved = value === '1';
            //         const badgeClass = isUnreserved ? 'bg-success' : 'bg-danger';
            //         const badgeText = isUnreserved
            //             ? getLocalizedText('status_unreserved')
            //             : getLocalizedText('status_other');
            //         return `<span class="badge ${badgeClass} text-white ms-50 py-50 px-1">${badgeText}</span>`;
            //     },
            // },
            {
                width: 250,
                dataField: 'reversible',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('reversibility'),
                editable: false,
                cellsrenderer: function (row, column, value, rowdata) {
                    const isReversible = value === 1 || value === '1';
                    return isReversible
                        ? `
                    <div class="d-flex align-items-center ps-50 gap-50">
                        <div class="bg-success border-radius-50" style="width: 10px; height: 10px;"></div>
                        <span class="fw-bolder">${getLocalizedText('reversible')}</span>
                    </div>`
                        : '';
                },
            },
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
                            return `<div id="${popoverId}">
                                        <ul class="popover-castom" style="list-style: none">
                                            <li><a class="dropdown-item" href="${window.location.origin + languageBlock}/containers/${rowdata.id}">${getLocalizedText('btnView')}</a></li>
                                            <li><a class="dropdown-item" href="${window.location.origin + languageBlock}/containers/${rowdata.id}/edit">${getLocalizedText('btnEdit')}</a></li>
                                        </ul>
                                    </div>`;
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
        { label: getLocalizedText('name'), value: 'name', checked: true },
        {
            label: getLocalizedText('id'),
            value: 'id',
            checked: true,
        },
        {
            label: getLocalizedText('type'),
            value: 'type',
            checked: true,
        },
        // { label: getLocalizedText('status'), value: 'status_id', checked: true },
        { label: getLocalizedText('reversibility'), value: 'reversible', checked: true },
        { label: getLocalizedText('action'), value: 'action', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});
