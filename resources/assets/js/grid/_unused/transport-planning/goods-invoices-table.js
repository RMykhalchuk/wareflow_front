import { pagerRenderer } from '../../components/pager.js';
import { toolbarRender } from '../../components/toolbar-advanced.js';
import { listbox } from '../../components/listbox.js';
import { hover } from '../../components/hover.js';
import { getLocalizedText } from '../../../localization/_unused/transport-planning/getLocalizedText.js';
import { switchLang } from '../../components/switch-lang.js';

const listBoxSetting = listbox.bind({});
const toolbarRendererLeftovers = toolbarRender.bind({});
const pagerRendererLeftovers = pagerRenderer.bind({});

$(document).ready(function () {
    let table = $('#goods-invoices-table');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'number', type: 'string' },
        { name: 'download', type: 'string' },
        { name: 'unload', type: 'string' },
        { name: 'products', type: 'string' },
        { name: 'weight', type: 'string' },
        { name: 'pallet', type: 'string' },
        { name: 'loading_info', type: 'string' },
        { name: 'unloaidng_info', type: 'string' },
    ];

    var myDataSource = {
        datatype: 'json',
        datafields: dataFields,
        url:
            window.location.origin +
            `/transport-planning/table/${planning_id}/goods-invoice-filter`,
        root: 'data',
        beforeprocessing: function (data) {
            // console.log('data', data)
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
        theme: 'light-wms goods-invoices-table-custom',
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
        rowsheight: 70,
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
            return toolbarRendererLeftovers(statusbar, table, false, 1, columnCount - 1); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                dataField: 'number',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableGoodsInvoicesNumber'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<a href='${window.location.origin + languageBlock}/document/${value}' class="mb-0 ps-1 d-flex fw-bold">${getLocalizedText('tableGoodsInvoicesNumberInCell')} ${value}</a>`;
                },
                width: '100px',
            },
            {
                dataField: 'download',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableGoodsInvoicesLoading'),
                minwidth: '200',
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const { warehouse, date, start_at, end_at, address } = rowdata.loading_info;
                    //console.log(rowdata.loading_info)
                    const company = rowdata.download.slice(0, rowdata.download.indexOf(warehouse));
                    return `<div><p class="mb-0 ps-1 fw-bold" >${company}</p><p class="mb-0 ps-1 text-secondary">${address}</p><p class="mb-0 ps-1 text-secondary">${date} ${start_at}-${end_at}</p></div>`;
                },
            },
            {
                minwidth: '200',
                dataField: 'unload',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableGoodsInvoicesUnloading'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const { warehouse, date, start_at, end_at, address } = rowdata.unloaidng_info;
                    const company = rowdata.unload.slice(0, rowdata.unload.indexOf(warehouse));
                    return `<div><p class="mb-0 ps-1 fw-bold" style="">${company}</p><p class="mb-0 ps-1 text-secondary">${address}</p><p class="mb-0 ps-1 text-secondary">${date} ${start_at}-${end_at}</p></div>`;
                },
            },
            {
                minwidth: '200',
                dataField: 'products',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableGoodsInvoicesProducts'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<div><div><span class="mb-0 ps-1 text-secondary">${getLocalizedText('tableGoodsInvoicesProductsInCellWeight')} </span> <span class="fw-bold">${rowdata.weight} ${getLocalizedText('tableGoodsInvoicesProductsInCellWeightUnit')}</span></div><div><span class="mb-0 ps-1 text-secondary">${getLocalizedText('tableGoodsInvoicesProductsInCellPallet')} </span><span class="fw-bold">${rowdata.pallet}</span></div></div>`;
                },
            },
        ],
    });

    let listSource = [
        { label: getLocalizedText('tableGoodsInvoicesNumber'), value: 'number', checked: true },
        { label: getLocalizedText('tableGoodsInvoicesLoading'), value: 'download', checked: true },
        { label: getLocalizedText('tableGoodsInvoicesUnloading'), value: 'unload', checked: true },
        { label: getLocalizedText('tableGoodsInvoicesProducts'), value: 'products', checked: true },
    ];

    listBoxSetting(table, listSource);

    hover(table, isRowHovered);
});
