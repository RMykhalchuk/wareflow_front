import { pagerRenderer } from '../../components/pager.js';
import { toolbarRender } from '../../components/toolbar-advanced.js';
import { listbox } from '../../components/listbox.js';
import { hover } from '../../components/hover.js';
import { getLocalizedText } from '../../../localization/_unused/invoice/getLocalizedText.js';
import { switchLang } from '../../components/switch-lang.js';

const pagerRendererLeftovers = pagerRenderer.bind({});
const toolbarRendererLeftovers = toolbarRender.bind({});

$(document).ready(function () {
    let table = $('#invoice-table');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'number', type: 'string' },
        { name: 'inputOutput', type: 'string' },
        { name: 'performer', type: 'string' },
        { name: 'receiver', type: 'string' },
        { name: 'date', type: 'string' },
        { name: 'sum', type: 'string' },
        { name: 'status', type: 'string' },
    ];

    // var myDataSource = {
    //     datatype: "json",
    //     datafields: dataFields,
    //     url: window.location.origin + '/invoices/table/filter',
    //     root: 'data',
    //     beforeprocessing: function (data) {
    //         myDataSource.totalrecords = data.total;
    //     },
    //     filter: function () {
    //         // update the grid and send a request to the server.
    //         table.jqxGrid('updatebounddata', 'filter');
    //     },
    //     sort: function () {
    //         // update the grid and send a request to the server.
    //         table.jqxGrid('updatebounddata', 'sort');
    //     },
    // };

    var myDataSource = {
        datatype: 'array',
        datafields: dataFields,
        localdata: customData,
    };
    let dataAdapter = new $.jqx.dataAdapter(myDataSource);
    var grid = table.jqxGrid({
        theme: 'light-wms contract-table-custom',
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
                dataField: 'number',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexNumber'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<a style="" class=" ps-1 d-flex my-auto " href='${window.location.origin + languageBlock}/invoices/view'>${value}</p>`;
                },
                width: 150,
            },
            {
                dataField: 'inputOutput',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexInputOutput'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return ` <div class="d-flex align-items-center"><p  style="width: 100px" class="text-secondary ps-1 my-auto" >${value}</p> <i class="text-${
                        value === 'Вихідний' ? 'success' : 'danger'
                    }" data-feather='${
                        value === 'Вихідний' ? 'arrow-up-right' : 'arrow-down-left'
                    }'></i> </div>`;
                },
                width: 170,
            },
            {
                dataField: 'performer',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexPerformer'),
                width: 350,
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<a class="ps-1 text-secondary  my-auto fw-medium-c underline-on-hover" href='${window.location.origin + languageBlock}/companies/${rowdata.id}' >${value}</a>`;
                },
            },
            {
                minwidth: 150,
                dataField: 'receiver',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexReceiver'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<a class="ps-1 text-secondary  my-auto fw-medium-c underline-on-hover" href='${window.location.origin + languageBlock}/companies/${rowdata.id}' >${value}</a>`;
                },
            },
            {
                width: 150,
                dataField: 'date',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexDate'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<p class="text-secondary  ps-1  my-auto"  >${value}</p>`;
                },
            },
            {
                width: 150,
                dataField: 'sum',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexSum'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<p class="text-secondary  ps-1  my-auto"  >${value}</p>`;
                },
            },
            {
                width: 220,
                dataField: 'status',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexStatus'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<div class="ps-1"> <div class="alert alert-${
                        value === 'Відхилено вами'
                            ? 'danger'
                            : value === 'Відхилено контрагентом'
                              ? 'danger'
                              : value === 'Оплачено вами'
                                ? 'success'
                                : value === 'Оплачено контрагентом'
                                  ? 'success'
                                  : 'primary'
                    }" style="padding : 2px 10px !important;"
                    > ${value} </div> </div>`;
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
                            return `<div id=${popoverId}>
                                          <ul class="popover-castom" style="list-style: none">
                                          <li><a class="dropdown-item" href="${window.location.origin + languageBlock}/invoices/view/">${getLocalizedText('tableIndexActionView')}</a></li>
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
        { label: getLocalizedText('tableIndexNumber'), value: 'number', checked: true },
        { label: getLocalizedText('tableIndexInputOutput'), value: 'inputOutput', checked: true },
        { label: getLocalizedText('tableIndexPerformer'), value: 'performer', checked: true },
        { label: getLocalizedText('tableIndexReceiver'), value: 'receiver', checked: true },
        { label: getLocalizedText('tableIndexDate'), value: 'date', checked: true },
        { label: getLocalizedText('tableIndexSum'), value: 'sum', checked: true },
        { label: getLocalizedText('tableIndexStatus'), value: 'status', checked: true },
        { label: getLocalizedText('tableIndexAction'), value: 'action', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});

var customData = [
    {
        number: '№ 123456789',
        inputOutput: 'Вхідний',
        performer: "ТОВ 'КОНДИТЕРСЬКА ФАБРИКА'ЯРИЧ'",
        receiver: 'АТБ-маркет ТОВ',
        date: '27.05.2023',
        sum: '20 392, 00',
        status: 'Оплачено вами',
    },
    {
        number: '№ 123456789',
        inputOutput: 'Вихідний',
        performer: "ТОВ 'КОНДИТЕРСЬКА ФАБРИКА'ЯРИЧ'",
        receiver: 'АТБ-маркет ТОВ',
        date: '27.05.2023',
        sum: '20 392, 00',
        status: 'Відхилено контрагентом',
    },
    {
        number: '№ 123456789',
        inputOutput: 'Вихідний',
        performer: "ТОВ 'КОНДИТЕРСЬКА ФАБРИКА'ЯРИЧ'",
        receiver: 'АТБ-маркет ТОВ',
        date: '27.05.2023',
        sum: '20 392, 00',
        status: 'Оплачено контрагентом',
    },
    {
        number: '№ 123456789',
        inputOutput: 'Вхідний',
        performer: "ТОВ 'КОНДИТЕРСЬКА ФАБРИКА'ЯРИЧ'",
        receiver: 'АТБ-маркет ТОВ',
        date: '27.05.2023',
        sum: '20 392, 00',
        status: 'Очікує на вашу оплату',
    },
    {
        number: '№ 123456789',
        inputOutput: 'Вхідний',
        performer: "ТОВ 'КОНДИТЕРСЬКА ФАБРИКА'ЯРИЧ'",
        receiver: 'АТБ-маркет ТОВ',
        date: '27.05.2023',
        sum: '20 392, 00',
        status: 'Відхилено вами',
    },
    {
        number: '№ 123456789',
        inputOutput: 'Вхідний',
        performer: "ТОВ 'КОНДИТЕРСЬКА ФАБРИКА'ЯРИЧ'",
        receiver: 'АТБ-маркет ТОВ',
        date: '27.05.2023',
        sum: '20 392, 00',
        status: 'Оплачено вами',
    },
    {
        number: '№ 123456789',
        inputOutput: 'Вихідний',
        performer: "ТОВ 'КОНДИТЕРСЬКА ФАБРИКА'ЯРИЧ'",
        receiver: 'АТБ-маркет ТОВ',
        date: '27.05.2023',
        sum: '20 392, 00',
        status: 'Відхилено контрагентом',
    },
    {
        number: '№ 123456789',
        inputOutput: 'Вихідний',
        performer: "ТОВ 'КОНДИТЕРСЬКА ФАБРИКА'ЯРИЧ'",
        receiver: 'АТБ-маркет ТОВ',
        date: '27.05.2023',
        sum: '20 392, 00',
        status: 'Оплачено контрагентом',
    },
    {
        number: '№ 123456789',
        inputOutput: 'Вхідний',
        performer: "ТОВ 'КОНДИТЕРСЬКА ФАБРИКА'ЯРИЧ'",
        receiver: 'АТБ-маркет ТОВ',
        date: '27.05.2023',
        sum: '20 392, 00',
        status: 'Очікує на вашу оплату',
    },
    {
        number: '№ 123456789',
        inputOutput: 'Вхідний',
        performer: "ТОВ 'КОНДИТЕРСЬКА ФАБРИКА'ЯРИЧ'",
        receiver: 'АТБ-маркет ТОВ',
        date: '27.05.2023',
        sum: '20 392, 00',
        status: 'Відхилено вами',
    },
    {
        number: '№ 123456789',
        inputOutput: 'Вхідний',
        performer: "ТОВ 'КОНДИТЕРСЬКА ФАБРИКА'ЯРИЧ'",
        receiver: 'АТБ-маркет ТОВ',
        date: '27.05.2023',
        sum: '20 392, 00',
        status: 'Оплачено вами',
    },
    {
        number: '№ 123456789',
        inputOutput: 'Вихідний',
        performer: "ТОВ 'КОНДИТЕРСЬКА ФАБРИКА'ЯРИЧ'",
        receiver: 'АТБ-маркет ТОВ',
        date: '27.05.2023',
        sum: '20 392, 00',
        status: 'Відхилено контрагентом',
    },
    {
        number: '№ 123456789',
        inputOutput: 'Вихідний',
        performer: "ТОВ 'КОНДИТЕРСЬКА ФАБРИКА'ЯРИЧ'",
        receiver: 'АТБ-маркет ТОВ',
        date: '27.05.2023',
        sum: '20 392, 00',
        status: 'Оплачено контрагентом',
    },
    {
        number: '№ 123456789',
        inputOutput: 'Вхідний',
        performer: "ТОВ 'КОНДИТЕРСЬКА ФАБРИКА'ЯРИЧ'",
        receiver: 'АТБ-маркет ТОВ',
        date: '27.05.2023',
        sum: '20 392, 00',
        status: 'Очікує на вашу оплату',
    },
    {
        number: '№ 123456789',
        inputOutput: 'Вхідний',
        performer: "ТОВ 'КОНДИТЕРСЬКА ФАБРИКА'ЯРИЧ'",
        receiver: 'АТБ-маркет ТОВ',
        date: '27.05.2023',
        sum: '20 392, 00',
        status: 'Відхилено вами',
    },
];
