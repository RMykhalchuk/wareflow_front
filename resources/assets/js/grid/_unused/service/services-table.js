import { pagerRenderer } from '../../components/pager.js';
import { toolbarRender } from '../../components/toolbar-advanced.js';
import { listbox } from '../../components/listbox.js';
import { hover } from '../../components/hover.js';
import { getLocalizedText } from '../../../localization/_unused/services/getLocalizedText.js';
import { switchLang } from '../../components/switch-lang.js';

const pagerRendererLeftovers = pagerRenderer.bind({});
const toolbarRendererLeftovers = toolbarRender.bind({});

$(document).ready(function () {
    let table = $('#services-table');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'name', type: 'string' },
        { name: 'id', type: 'string' },
        { name: 'category', type: 'string' },
    ];

    var myDataSource = {
        datatype: 'json',
        datafields: dataFields,
        url: window.location.origin + '/services/table/filter',
        root: 'data',
        beforeprocessing: function (data) {
            myDataSource.totalrecords = data.total;
        },
        filter: function () {
            // update the grid and send a request to the server.
            table.jqxGrid('updatebounddata', 'filter');
        },
        sort: function () {
            // update the grid and send a request to the server.
            table.jqxGrid('updatebounddata', 'sort');
        },
    };

    let dataAdapter = new $.jqx.dataAdapter(myDataSource);
    var grid = table.jqxGrid({
        theme: 'light-wms',
        width: '100%',
        autoheight: true,
        pageable: true,
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
        sortable: true,
        columnsResize: false,
        filterable: true,
        filtermode: 'default',
        localization: getLocalization(language),
        selectionMode: 'checkbox',
        enablehover: false,
        columnsreorder: true,
        autoshowfiltericon: true,
        pagermode: 'advanced',
        rowsheight: 35,
        filterbarmode: 'simple',
        toolbarHeight: 45,
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
                dataField: 'name',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('name'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<a class="mb-0 d-flex ps-1 "  href='${window.location.origin + languageBlock}/services/${rowdata.id}' >${value}</a>`;
                },
                minwidth: 170,
            },
            {
                dataField: 'id',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('id'),
                width: 110,
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<p class=" text-secondary ps-1  my-auto" style="" >${value}</p>`;
                },
            },
            {
                width: 200,
                dataField: 'category',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('category'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    //console.log(value)

                    const valuesInCell = {
                        'Прийом товару': { text: getLocalizedText('category_pryiom_tovaru') },
                        'Зберігання товару': {
                            text: getLocalizedText('category_zberihannia_tovaru'),
                        },
                        'Відвантаження товару': {
                            text: getLocalizedText('category_vidvantazhennia_tovaru'),
                        },
                        'Комплектація товару': {
                            text: getLocalizedText('category_komplektatsiia_tovaru'),
                        },
                        'Стікерування товару': {
                            text: getLocalizedText('category_stikeruvannia_tovaru'),
                        },
                        'Копакінг товару': { text: getLocalizedText('category_kopakinh_tovaru') },
                        Кросдок: { text: getLocalizedText('category_krosdok') },
                    };

                    const translateValue = valuesInCell[value] || { text: value };

                    return `<p class="ps-1 text-secondary my-auto" title="${translateValue.text || value}"> ${translateValue.text || value} </p>`;
                },
            },
            {
                width: '70px',
                dataField: 'action',
                align: 'center',
                cellsalign: 'center',
                text: getLocalizedText('action'),
                renderer: function () {
                    return (
                        '<div style="display: flex; align-items: center; justify-content: center; height: 100%;"><img src=' +
                        window.location.origin +
                        '"/assets/libs/jqwidget/jqwidgets/styles/images/castom-light-wms/setting-button-table.svg" alt="setting-button-table"></div>'
                    );
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

                    let button =
                        '<button id="' +
                        buttonId +
                        '" style="padding:0" class="btn btn-table-cell" type="button" data-bs-toggle="popover"> <img src=' +
                        window.location.origin +
                        '"/assets/libs/jqwidget/jqwidgets/styles/images/castom-light-wms/menu_dots_vertical.svg" alt="menu_dots_vertical"> </button>';

                    var popoverOptions = {
                        html: true,
                        sanitize: false,
                        placement: 'left',
                        trigger: 'focus',
                        container: 'body',
                        content: function () {
                            return `<div id="${popoverId}">
                                            <ul class="popover-castom" style="list-style: none">
                                                <li><a class="dropdown-item" href="${window.location.origin + languageBlock}/services/${rowdata.id}">${getLocalizedText('btnView')}</a></li>
                                                <li><a class="dropdown-item" href="${window.location.origin + languageBlock}/services/${rowdata.id}/edit">${getLocalizedText('btnEdit')}</a></li>
                                                <!-- <li><a class="dropdown-item ps-1 text-danger delete-row" href="#">Видалити послугу</a></li> -->
                                            </ul>
                                        </div>`;
                        },
                    };

                    $(document)
                        .off('click', '#' + buttonId)
                        .on('click', '#' + buttonId, function () {
                            $(this).popover(popoverOptions).popover('show');
                        });

                    $(document)
                        .off('click', '#' + popoverId + ' .delete-row')
                        .on('click', '#' + popoverId + ' .delete-row', function () {
                            var rowId = rowdata.uid;
                            grid.jqxGrid('deleterow', rowId);
                            $('#' + buttonId).popover('hide');
                        });

                    return '<div class="jqx-popover-wrapper">' + button + '</div>';
                },
            },
        ],
    });

    let listSource = [
        { label: getLocalizedText('name'), value: 'name', checked: true },
        { label: getLocalizedText('id'), value: 'id', checked: true },
        { label: getLocalizedText('category'), value: 'category', checked: true },
        { label: getLocalizedText('action'), value: 'action', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});
