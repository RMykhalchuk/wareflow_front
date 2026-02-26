import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { getLocalizedText } from '../../localization/transport-planning/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';

const listBoxSetting = listbox.bind({});
const toolbarRendererLeftovers = toolbarRender.bind({});
const pagerRendererLeftovers = pagerRenderer.bind({});

$(document).ready(function () {
    let table = $('#transport-request-table');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;
    let dataFields = [
        { name: 'id', type: 'number' },
        { name: 'number', type: 'string' },
        { name: 'loading', type: 'string' },
        { name: 'unloading', type: 'string' },
        { name: 'weight', type: 'string' },
        { name: 'pallet', type: 'string' },
    ];

    function goodsRender(row, column, value, defaultHtml, columnSettings, rowData) {
        let html = defaultHtml.split('><');
        //console.log(rowData.patronymic)
        let wrappedContent =
            html[0] +
            "id='goods_render_" +
            row +
            "'>" +
            `<div>${getLocalizedText('tableRequestForTransportDetailsProductsInCellWeight')} <span class='fw-bold'>${rowData.weight} ${getLocalizedText('tableRequestForTransportDetailsProductsInCellWeightUnit')}</span></div>` +
            `<div>${getLocalizedText('tableRequestForTransportDetailsProductsInCellPallet')} <span class='fw-bold'>${rowData.pallet}</span></div>` +
            '<' +
            html[1];
        return "<div class=''>" + wrappedContent + '</div>';
    }

    function loadingRender(row, column, value, defaultHtml, columnSettings, rowData) {
        let html = defaultHtml.split('><');
        //console.log(rowData.patronymic)
        let wrappedContent =
            html[0] +
            "id='loading_render_" +
            row +
            "'>" +
            `<div><span class='fw-bold'>${rowData.loading.company}</span></div>` +
            `<div><span class=''>${rowData.loading.location}</span></div>` +
            `<div><span class=''>${rowData.loading.date}</span> <span>${rowData.loading.start_at}</span> <span>${rowData.loading.end_at}</span></div>` +
            '<' +
            html[1];
        return "<div class=''>" + wrappedContent + '</div>';
    }

    function unloadingRender(row, column, value, defaultHtml, columnSettings, rowData) {
        let html = defaultHtml.split('><');
        //console.log(rowData.patronymic)
        let wrappedContent =
            html[0] +
            "id='unloading_render_" +
            row +
            "'>" +
            `<div><span class='fw-bold'>${rowData.unloading.company}</span></div>` +
            `<div><span class=''>${rowData.unloading.location}</span></div>` +
            `<div><span class=''>${rowData.unloading.date}</span> <span>${rowData.unloading.start_at}</span> <span>${rowData.unloading.end_at}</span></div>` +
            '<' +
            html[1];
        return "<div class=''>" + wrappedContent + '</div>';
    }

    var myDataSource = {
        datatype: 'json',
        datafields: dataFields,
        url:
            window.location.origin +
            `/transport-planning/table/${planning_id}/transport-request-filter`,
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
        pagesize: window.location.pathname.includes('transport-planning/create') ? 5 : 10,
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
        rowsheight: 85,
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
            return toolbarRendererLeftovers(
                statusbar,
                table,
                false,
                1,
                columnCount - 1,
                '-tr-request'
            ); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                dataField: 'number',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableRequestForTransportDetailsNumber'),
                editable: false,
                cellsrenderer: function (row, column, value, defaultHtml, columnSettings, rowData) {
                    return `<a href='${window.location.origin + languageBlock}/document/${rowData.id}' class="mb-0 ps-1 d-flex fw-bold">${getLocalizedText('tableRequestForTransportDetailsNumberInCell')} ${rowData.id}</a>`;
                },
                width: '15%',
            },

            {
                dataField: 'loading_render',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('tableRequestForTransportDetailsLoading'),
                minwidth: 100,
                editable: false,
                cellsrenderer: loadingRender,
            },
            {
                minwidth: 100,
                dataField: 'unloading_render',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('tableRequestForTransportDetailsUnloading'),
                editable: false,
                cellsrenderer: unloadingRender,
            },
            {
                minwidth: 350,
                dataField: 'goods_render',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('tableRequestForTransportDetailsProducts'),
                editable: false,
                cellsrenderer: goodsRender,
            },
        ],
    });

    let listSource = [
        {
            label: getLocalizedText('tableRequestForTransportDetailsNumber'),
            value: 'number',
            checked: true,
        },
        {
            label: getLocalizedText('tableRequestForTransportDetailsLoading'),
            value: 'loading_render',
            checked: true,
        },
        {
            label: getLocalizedText('tableRequestForTransportDetailsUnloading'),
            value: 'unloading_render',
            checked: true,
        },
        {
            label: getLocalizedText('tableRequestForTransportDetailsProducts'),
            value: 'goods_render',
            checked: true,
        },
    ];

    listbox(table, listSource, '-tr-request');

    hover(table, isRowHovered);
});
