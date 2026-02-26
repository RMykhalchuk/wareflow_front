import { pagerRenderer } from '../../components/pager.js';
import { toolbarRender } from '../../components/toolbar-advanced.js';
import { listbox } from '../../components/listbox.js';
import { hover } from '../../components/hover.js';
import { getLocalizedText } from '../../../localization/_unused/residue-control/getLocalizedText.js';
import { switchLang } from '../../components/switch-lang.js';

const pagerRendererLeftovers = pagerRenderer.bind({});
const toolbarRendererLeftovers = toolbarRender.bind({});

$(document).ready(function () {
    let table = $('#residue-control-table');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;
    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'name', type: 'string' },
        { name: 'leftovers', type: 'string' },
        { name: 'reserves', type: 'string' },
        { name: 'orders', type: 'string' },
        { name: 'balance', type: 'string' },
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
        rowsheight: 87,
        // autorowheight: true,
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
                text: getLocalizedText('tableIndexID'),
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
                width: 150,
            },
            {
                dataField: 'name',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexName'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<a href="${window.location.origin + languageBlock}/residue-control/catalog" class="text-secondary fw-bold  ps-1  my-auto"  >${value}</a>`;
                },
                minwidth: 350,
            },
            {
                dataField: 'leftovers',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexLeftovers'),
                width: 190,
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const leftoversValue = JSON.parse(value);
                    return `<div><p class="mb-0 ps-1" style="">${getLocalizedText('tableIndexLeftoversInCellAll') + ' ' + leftoversValue.total}</p><p class="mb-0 ps-1 text-secondary">${leftoversValue.yarych}</p><p class="mb-0 ps-1 text-secondary">${leftoversValue.bolero}</p></div>`;
                },
            },
            {
                width: 190,
                dataField: 'reserves',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexReserves'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const reservesValue = JSON.parse(value);
                    return `<div><p class="mb-0 ps-1" style="">${getLocalizedText('tableIndexReservesInCellAll') + ' ' + reservesValue.total}</p><p class="mb-0 ps-1 text-secondary">${reservesValue.wmsyarych}</p><p class="mb-0 ps-1 text-secondary">${reservesValue.wmsbolero}</p></div>`;
                },
            },
            {
                width: 190,
                dataField: 'orders',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexOrders'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    const ordersValue = JSON.parse(value);
                    return `<div><p class="mb-0 ps-1" style="">${getLocalizedText('tableIndexOrdersInCellAll') + ' ' + ordersValue.total}</p><p class="mb-0 ps-1 text-secondary">${ordersValue.yarych}</p><p class="mb-0 ps-1 text-secondary">${ordersValue.bolero}</p></div>`;
                },
            },
            {
                width: 190,
                dataField: 'balance',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexBalance'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const balanceValue = JSON.parse(value);
                    const el = $(`#row${row}residue-control-table`)[0];

                    if (+balanceValue.yarych < 0 && +balanceValue.bolero < 0) {
                        renderColorRow(el, '#fbdddd');
                    } else if (+balanceValue.yarych < 0 || +balanceValue.bolero < 0) {
                        renderColorRow(el, '#ffecd9');
                    }

                    function renderColorRow(el, color) {
                        setTimeout(function () {
                            $(el)
                                .children()
                                .each(function () {
                                    const child = $(this)[0];
                                    // $(child).addClass(className);
                                    $(child).css('background-color', color);
                                });
                        }, 0);
                    }

                    return `<div ><p class="mb-0 ps-1" style="">${getLocalizedText('tableIndexBalanceInCellAll') + ' ' + balanceValue.total}</p><p class="mb-0 ps-1 text-secondary">Ярич: ${balanceValue.yarych}</p><p class="mb-0 ps-1 text-secondary">Болеро: ${balanceValue.bolero}</p></div>`;
                },
            },
            {
                width: '70px',
                dataField: 'action',
                align: 'center',
                cellsalign: 'center',
                text: getLocalizedText('tableIndexAction'),
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
                            return (
                                '<div id="' +
                                popoverId +
                                '"><ul class="popover-castom" style="list-style: none">     ' +
                                '<li><a class="dropdown-item" href="' +
                                window.location.origin +
                                languageBlock +
                                '/residue-control/catalog/' +
                                '">' +
                                getLocalizedText('tableIndexViewResidueControl') +
                                '</a></li>'
                            );
                            // + '<li><a class="dropdown-item delete-row" href="#">' + getLocalizedText("tableIndexDeleteLeftovers") + '</a></li></ul></div>    '
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
        { label: getLocalizedText('tableIndexID'), value: 'id', checked: true },
        { label: getLocalizedText('tableIndexName'), value: 'name', checked: true },
        { label: getLocalizedText('tableIndexLeftovers'), value: 'leftovers', checked: true },
        { label: getLocalizedText('tableIndexReserves'), value: 'reserves', checked: true },
        { label: getLocalizedText('tableIndexOrders'), value: 'orders', checked: true },
        { label: getLocalizedText('tableIndexBalance'), value: 'balance', checked: true },
        { label: getLocalizedText('tableIndexAction'), value: 'action', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});

var customData = [
    {
        id: '000014342',
        name: "Крекер 'Вершковий 'Yarych' 3.78 кг 21*180г",
        leftovers: '{"total": "120 (120)", "yarych": "Ярич: 30", "bolero": "Болеро: 90"}',
        reserves: '{"total": "120", "wmsyarych": "WMS Ярич: 30", "wmsbolero": "WMS Болеро: 90"}',
        orders: '{"total": "50", "yarych": "Ярич: 0", "bolero": "Болеро: 50"}',
        balance: '{"total": "70", "yarych": "30", "bolero": "40"}',
    },
    {
        id: '00001342',
        name: "Крекер 'Вершковий 'Yarych' 3.78 кг 21*180г",
        leftovers: '{"total": "120 (120)", "yarych": "Ярич: 30", "bolero": "Болеро: 90"}',
        reserves: '{"total": "120", "wmsyarych": "WMS Ярич: 30", "wmsbolero": "WMS Болеро: 90"}',
        orders: '{"total": "50", "yarych": "Ярич: 0", "bolero": "Болеро: 50"}',
        balance: '{"total": "22", "yarych": "-30", "bolero": "-40"}',
    },
    {
        id: '000013342',
        name: "Крекер 'Вершковий 'Yarych' 3.78 кг 21*180г",
        leftovers: '{"total": "120 (120)", "yarych": "Ярич: 30", "bolero": "Болеро: 90"}',
        reserves: '{"total": "120", "wmsyarych": "WMS Ярич: 30", "wmsbolero": "WMS Болеро: 90"}',
        orders: '{"total": "50", "yarych": "Ярич: 0", "bolero": "Болеро: 50"}',
        balance: '{"total": "70", "yarych": "30", "bolero": "40"}',
    },
    {
        id: '00001642',
        name: "Крекер 'Вершковий 'Yarych' 3.78 кг 21*180г",
        leftovers: '{"total": "120 (120)", "yarych": "Ярич: 30", "bolero": "Болеро: 90"}',
        reserves: '{"total": "120", "wmsyarych": "WMS Ярич: 30", "wmsbolero": "WMS Болеро: 90"}',
        orders: '{"total": "50", "yarych": "Ярич: 0", "bolero": "Болеро: 50"}',
        balance: '{"total": "10", "yarych": "-30", "bolero": "40"}',
    },
    {
        id: '000601342',
        name: "Крекер 'Вершковий 'Yarych' 3.78 кг 21*180г",
        leftovers: '{"total": "120 (120)", "yarych": "Ярич: 30", "bolero": "Болеро: 90"}',
        reserves: '{"total": "120", "wmsyarych": "WMS Ярич: 30", "wmsbolero": "WMS Болеро: 90"}',
        orders: '{"total": "50", "yarych": "Ярич: 0", "bolero": "Болеро: 50"}',
        balance: '{"total": "-70", "yarych": "-30", "bolero": "-40"}',
    },
];
