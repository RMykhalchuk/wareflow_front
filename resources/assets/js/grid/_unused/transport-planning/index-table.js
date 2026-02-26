import { pagerRenderer } from '../../components/pager.js';
import { toolbarRender } from '../../components/toolbar-advanced.js';
import { listbox } from '../../components/listbox.js';
import { hover } from '../../components/hover.js';
import { getLocalizedText } from '../../../localization/_unused/transport-planning/getLocalizedText.js';
import { switchLang } from '../../components/switch-lang.js';

const pagerRendererLeftovers = pagerRenderer.bind({});
const toolbarRendererLeftovers = toolbarRender.bind({});

$(document).ready(function () {
    let table = $('#transportPlanningDataTable');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;
    let dataFields = [
        { name: 'data', type: 'string' },
        { name: 'day', type: 'string' },
        { name: 'countAuto', type: 'number' },
        { name: 'initialization', type: 'number' },
        { name: 'delete', type: 'number' },
    ];

    var myDataSource = {
        datatype: 'json',
        datafields: dataFields,
        url: window.location.origin + '/transport-planning/table/filter',
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
            //console.log(columns)
            //console.log(columnCount)
            return toolbarRendererLeftovers(statusbar, table, true, 1, columnCount - 4); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                dataField: 'data',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexData'),
                editable: false,
                minwidth: 100,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<a href="${window.location.origin + languageBlock}/transport-planning/list/${value}" class="mb-0 d-flex  text-primary fw-bold">${value}</a>`;
                },
            },
            {
                dataField: 'day',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('tableIndexDay'),
                minwidth: 100,
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    //console.log(value)

                    const valuesInCell = {
                        Неділя: { text: getLocalizedText('daySun') },
                        Понеділок: { text: getLocalizedText('dayMon') },
                        Вівторок: { text: getLocalizedText('dayTue') },
                        Середа: { text: getLocalizedText('dayWed') },
                        Четвер: { text: getLocalizedText('dayThu') },
                        "П'ятниця": { text: getLocalizedText('dayFri') },
                        Субота: { text: getLocalizedText('daySat') },
                    };

                    const translateValue = valuesInCell[value] || { text: value };

                    return `<p class="ps-25 text-secondary my-auto" title="${translateValue.text || value}"> ${translateValue.text || value} </p>`;
                },
            },
            {
                minwidth: 100,
                dataField: 'countAuto',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexCountAuto'),
                editable: false,
                filterable: false,
                sortable: false,
            },
            {
                minwidth: 100,
                dataField: 'initialization',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexInitialization'),
                editable: false,
                filterable: false,
                sortable: false,
            },
            {
                minwidth: 100,
                dataField: 'delete',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexDelete'),
                editable: false,
                filterable: false,
                sortable: false,
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
                                '/transport-planning/list/' +
                                rowdata.data +
                                '">' +
                                getLocalizedText('tableIndexViewTransportPlanning') +
                                '</a></li></ul></div>    '
                            );
                            // + ' <li><a class="dropdown-item delete-row" href="#">' + getLocalizedText("tableIndexDeleteTransportPlanning") + '</a></li>    '
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
        { label: getLocalizedText('tableIndexData'), value: 'data', checked: true },
        { label: getLocalizedText('tableIndexDay'), value: 'day', checked: true },
        { label: getLocalizedText('tableIndexCountAuto'), value: 'countAuto', checked: true },
        {
            label: getLocalizedText('tableIndexInitialization'),
            value: 'initialization',
            checked: true,
        },
        { label: getLocalizedText('tableIndexDelete'), value: 'delete', checked: true },
        { label: getLocalizedText('tableIndexAction'), value: 'action', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});
