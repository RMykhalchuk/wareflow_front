import { pagerRenderer } from '../../components/pager.js';
import { toolbarRender } from '../../components/toolbar-advanced.js';
import { listbox } from '../../components/listbox.js';
import { hover } from '../../components/hover.js';
import { getLocalizedText } from '../../../localization/_unused/residue-control/getLocalizedText.js';
import { switchLang } from '../../components/switch-lang.js';

const pagerRendererLeftovers = pagerRenderer.bind({});
const toolbarRendererLeftovers = toolbarRender.bind({});

$(document).ready(function () {
    let table = $('#residue-control-view-table');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'loading', type: 'string' },
        { name: 'plannedDate', type: 'string' },
        { name: 'customer', type: 'string' },
        { name: 'adress', type: 'string' },
        { name: 'amount', type: 'number' },
        { name: 'compiled', type: 'number' },
        { name: 'fine', type: 'number' },
    ];

    var myDataSource = {
        datatype: 'array',
        datafields: dataFields,
        localdata: customData,
    };
    let dataAdapter = new $.jqx.dataAdapter(myDataSource);
    var grid = table.jqxGrid({
        theme: 'light-wms residue-control-table-custom',
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
        autorowheight: true,
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

            return toolbarRendererLeftovers(statusbar, table, true, 1, columnCount - 1); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                dataField: 'id',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableViewItemIndexID'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<p class="text-secondary  ps-1  my-auto"  >${value}</p>`;
                },
                width: 120,
            },
            {
                dataField: 'loading',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableViewItemLoading'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    const data = JSON.parse(value);
                    return `<div ><p class="mb-0 ps-1 text-secondary" >${data.time}</p><p class="mb-0 ps-1 text-secondary">${data.date}</p></div>`;
                },
                width: 170,
            },
            {
                dataField: 'plannedDate',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableViewItemPlannedDate'),
                width: 150,
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<p class="text-secondary  ps-1  my-auto"  >${value}</p>`;
                },
            },
            {
                minwidth: 150,
                dataField: 'customer',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableViewItemCustomer'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<p class="text-secondary  ps-1  my-auto"  >${value}</p>`;
                },
            },
            {
                minwidth: 160,
                dataField: 'adress',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableViewItemAdress'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<p class="text-secondary  ps-1  my-auto"  >${value}</p>`;
                },
            },
            {
                width: 120,
                dataField: 'amount',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableViewItemAmount'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<p class="text-secondary  ps-1  my-auto"  >${value}</p>`;
                },
            },
            {
                width: 120,
                dataField: 'compiled',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableViewItemCompiled'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<p class="text-secondary  ps-1  my-auto"  >${value}</p>`;
                },
            },
            {
                width: 140,
                dataField: 'fine',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableViewItemFine'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<p class="text-secondary  ps-1  my-auto"  >${value} ${getLocalizedText('tableViewItemFineInCellCurrency')}</p>`;
                },
            },
            {
                width: '70px',
                dataField: 'action',
                align: 'center',
                cellsalign: 'center',
                text: getLocalizedText('tableViewItemAction'),
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
                            return `<div id=${popoverId}>
                                          <ul class="popover-castom" style="list-style: none">
                                          <li><a class="dropdown-item" href="${window.location.origin + languageBlock}/residue-control/${rowdata.id}/edit">${getLocalizedText('tableViewItemEdit')}</a></li>
                                          <li><a class="dropdown-item" href="${window.location.origin + languageBlock}/residue-control/${rowdata.id}">${getLocalizedText('tableViewItemView')}</a></li>
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
        { label: getLocalizedText('tableViewItemIndexID'), value: 'id', checked: true },
        { label: getLocalizedText('tableViewItemLoading'), value: 'loading', checked: true },
        {
            label: getLocalizedText('tableViewItemPlannedDate'),
            value: 'plannedDate',
            checked: true,
        },
        { label: getLocalizedText('tableViewItemCustomer'), value: 'customer', checked: true },
        { label: getLocalizedText('tableViewItemAdress'), value: 'adress', checked: true },
        { label: getLocalizedText('tableViewItemAmount'), value: 'amount', checked: true },
        { label: getLocalizedText('tableViewItemCompiled'), value: 'compiled', checked: true },
        { label: getLocalizedText('tableViewItemFine'), value: 'fine', checked: true },
        { label: getLocalizedText('tableViewItemAction'), value: 'action', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});

var customData = [
    {
        id: 'ТН00023421',
        loading: '{"time": "12:00","date":"05.07.2023"}',
        plannedDate: '05.07.2023',
        customer: 'АТБ- маркет ТОВ',
        adress: 'РЦ-22 ',
        amount: 20,
        compiled: 0,
        fine: 10200,
    },
    {
        id: 'ТН000323421',
        loading: '{"time": "12:00","date":"05.07.2023"}',
        plannedDate: '05.07.2023',
        customer: 'АТБ- маркет ТОВ',
        adress: 'м. Суми. вул. Дмитра Дорошенка, 8А ',
        amount: 20,
        compiled: 0,
        fine: 10200,
    },
    {
        id: 'ТН0002342109',
        loading: '{"time": "12:00","date":"05.07.2023"}',
        plannedDate: '05.07.2023',
        customer: 'АТБ- маркет ТОВ',
        adress: 'Форум Львів',
        amount: 20,
        compiled: 0,
        fine: 10200,
    },
];
