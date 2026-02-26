import { pagerRenderer } from '../../components/pager.js';
import { toolbarRender } from '../../components/toolbar-advanced.js';
import { listbox } from '../../components/listbox.js';
import { hover } from '../../components/hover.js';
import { getLocalizedText } from '../../../localization/_unused/transport-planning/getLocalizedText.js';
import { switchLang } from '../../components/switch-lang.js';

import { updateSelectedCount } from './utils/updateSelectedCount.js';
import { getIdGoodsInvoice } from './utils/getIdGoodsInvoice.js';
import { updateDataIds } from './utils/updateDataIds.js';
import { checkAndUpdatePlaceholder } from './utils/checkAndUpdatePlaceholder.js';
import { initSortableDocItem } from './utils/initSortableDocItem.js';

const pagerRendererLeftovers = pagerRenderer.bind({});
const toolbarRendererLeftovers = toolbarRender.bind({});

$(document).ready(function () {
    let table = $('#commodityInvoiceDataTable');

    let table1 = $('#commodityInvoiceDataTable');
    let table2 = $('#transportRequestDataTable');

    let addNewTransportRequestItem = $('#add-new-transport-request-item');
    let addNewGoodsInvoicesItem = $('#add-new-goods-invoices-item');
    let isRowHovered = false;

    // Clear list to select TTNs a route
    localStorage.removeItem('selectedRowsData');

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'type', type: 'string' },
        { name: 'loading', type: 'string' },
        { name: 'unloading', type: 'string' },
        { name: 'goods', type: 'string' },
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
            languageBlock +
            '/transport-planning/table/goods-invoice-filter',
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

    function goodsRender(row, column, value, defaultHtml, columnSettings, rowData) {
        let html = defaultHtml.split('><');
        //console.log(rowData.patronymic)
        let wrappedContent =
            html[0] +
            "id='goods_render_" +
            row +
            "'>" +
            `<div>${getLocalizedText('tableCommodityInvoiceGoodsRenderWeight')} <span class='fw-bold'>${rowData.weight} ${getLocalizedText('tableCommodityInvoiceGoodsRenderWeightUnits')}</span></div>` +
            `<div>${getLocalizedText('tableCommodityInvoiceGoodsRenderPallet')} <span class='fw-bold'>${rowData.pallet}</span></div>` +
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
            `<div><span class='fw-bold'>${rowData.loading_info.warehouse}</span></div>` +
            `<div><span class=''>${rowData.loading_info.address}</span></div>` +
            `<div><span class=''>${rowData.loading_info.date}</span> <span>${rowData.loading_info.start_at}</span> <span>${rowData.loading_info.end_at}</span></div>` +
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
            `<div><span class='fw-bold'>${rowData.unloaidng_info.warehouse}</span></div>` +
            `<div><span class=''>${rowData.unloaidng_info.address}</span></div>` +
            `<div><span class=''>${rowData.unloaidng_info.date}</span> <span>${rowData.unloaidng_info.start_at}</span> <span>${rowData.unloaidng_info.end_at}</span></div>` +
            '<' +
            html[1];
        return "<div class=''>" + wrappedContent + '</div>';
    }

    let dataAdapter = new $.jqx.dataAdapter(myDataSource);
    var grid = table.jqxGrid({
        theme: 'light-wms',
        width: '100%',
        autoheight: true,
        pagesize: window.location.pathname.includes('transport-planning/create') ? 5 : 10,
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
            return toolbarRendererLeftovers(statusbar, table, false, 1, columnCount - 1); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                dataField: 'type',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableCommodityInvoiceNumber'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    // Розділяємо рядок за допомогою регулярного виразу і вибираємо числову частину
                    const idNumber = value.match(/\d+/)[0];
                    return `<a href='${window.location.origin + languageBlock}/document/${idNumber}' class="mb-0 d-flex fw-bold">${value}</a>`;
                },
                minwidth: 300,
            },
            {
                dataField: 'loading_render',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('tableCommodityInvoiceLoading'),
                minwidth: 100,
                editable: false,
                cellsrenderer: loadingRender,
            },
            {
                minwidth: 100,
                dataField: 'unloading_render',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('tableCommodityInvoiceUnloading'),
                editable: false,
                cellsrenderer: unloadingRender,
            },
            {
                minwidth: 350,
                dataField: 'goods_render',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('tableCommodityInvoiceGoods'),
                editable: false,
                cellsrenderer: goodsRender,
            },
        ],
    });

    let listSource = [
        { label: getLocalizedText('tableCommodityInvoiceNumber'), value: 'type', checked: true },
        {
            label: getLocalizedText('tableCommodityInvoiceLoading'),
            value: 'loading_render',
            checked: true,
        },
        {
            label: getLocalizedText('tableCommodityInvoiceUnloading'),
            value: 'unloading_render',
            checked: true,
        },
        {
            label: getLocalizedText('tableCommodityInvoiceGoods'),
            value: 'goods_render',
            checked: true,
        },
    ];

    listbox(table, listSource);

    initSortableDocItem();

    // додаємо обробник події на зміну вибраних рядків у таблиці jqxGrid
    table.on('rowselect rowunselect', function () {
        updateSelectedCount(table1, table2, addNewGoodsInvoicesItem, addNewTransportRequestItem);
    });

    $('body').on('click', '.delete-invoices', function () {
        $(this).closest('.goods-invoices-item').remove();
        updateDataIds(); // Call the function to update data-id
        checkAndUpdatePlaceholder(); // Перевірка після видалення елементів
    });

    addNewGoodsInvoicesItem.on('click', function () {
        // Очистіть #sortable, видаляючи всі внутрішні елементи
        $('#sortable').empty();

        // Read selected rows
        var selectedRows = table.jqxGrid('getselectedrowindexes');
        var jsonData = [];
        getIdGoodsInvoice();

        // Iterate through selected rows and extract data
        selectedRows.forEach(function (index) {
            var rowData = table.jqxGrid('getrowdata', index);
            if (!getIdGoodsInvoice().includes(rowData.id.toString())) {
                jsonData.push(rowData);
            }
        });

        // Convert JSON data to string
        var jsonString = JSON.stringify(jsonData);

        //Clear before
        localStorage.removeItem('selectedRowsData');
        // Save data to local storage
        localStorage.setItem('selectedRowsData', jsonString);

        $('#add_sku_tp').modal('hide');
        //console.log(jsonData)

        // Create and append elements for each selected row
        jsonData.forEach(function (rowData) {
            // Розділяємо рядок за допомогою регулярного виразу і вибираємо числову частину
            const idNumber = rowData.id;
            // console.log(rowData.id)
            var newBlock = `  <div class="goods-invoices-item ui-state-default mt-1" data-goods-invoice-id="${rowData.id}">
                    <div class="goods-invoices-item-bg p-2 d-flex flex-row gap-1">
                        <div class="d-flex flex-column gap-3 align-items-center goods-invoices-item-order">
                            <h5 data-id-invoices="1" class="my-0">1</h5>
                            <img id="grip-vertical-invoices" class="grip-vertical-invoices"
                                 src="/assets/icons/entity/_unused/transport-planning/grip-vertical-invoices.svg"
                                 alt="grip-vertical-invoices">
                        </div>
                        <div class="d-flex flex-column flex-grow-1">
                            <div class="goods-invoices-item-title d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center gap-1">
                                        <img src="/assets/icons/entity/_unused/transport-planning/info-square.svg" alt="info-square">
                                        <h5 class="mb-0 d-flex gap-1">${getLocalizedText('tableCommodityInvoiceAddNewItemTitle')}
                                        <a class="link-primary"
                                           href='${window.location.origin + languageBlock}/document/${idNumber}'>${rowData.type}
                                        </a>
                                        </h5>
                                    </div>
                                </div>

                                <button class="btn p-0 delete-invoices"><img
                                        src="/assets/icons/entity/_unused/transport-planning/delete-invoices.svg"
                                        alt="delete-invoices">
                                </button>
                            </div>
                            <div class="goods-invoices-item-content mt-1 justify-content-between row mx-0">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xxl-4 px-0 pe-lg-1">
                                    <p>${getLocalizedText('tableCommodityInvoiceAddNewItemDataLoading')}</p>
                                    <p><b>${rowData.loading_info.warehouse}</b>,</p>
                                    <div class="row mx-0 gap-1">
                                        <div class="col-4 col-md-4 px-0" style="width: 126px">
                                            <input style="background-color: #fff" type="text" id="loading-date"
                                                   class="form-control flatpickr-basic "
                                                   value="${rowData.loading_info.date}"
                                                   placeholder="YYYY-MM-DD"/>
                                        </div>
                                        <div class="row mx-0 col-6 col-md-6 ps-0 d-flex flex-row flex-grow-1 ">
                                            <div class="p-0 col-auto ">
                                                <input style="background-color: #fff; width: 85px" type="text"
                                                       id="loading-start-at"
                                                       value="${rowData.loading_info.start_at}"
                                                       class="form-control flatpickr-time text-start"
                                                       placeholder="HH:MM"/>
                                            </div>
                                            <div style=" width: 10px"
                                                 class="p-0 col-auto d-flex align-items-center justify-content-center  ">
                                                -
                                            </div>
                                            <div class="  col-auto p-0 ">

                                                <input style="background-color: #fff; width: 85px" type="text"
                                                       id="loading-end-at"
                                                       value="${rowData.loading_info.end_at}"
                                                       class="form-control flatpickr-time text-start"
                                                       placeholder="HH:MM"/>
                                            </div>
                                        </div>

                                    </div>


                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xxl-4 px-0 px-lg-1">
                                    <p>${getLocalizedText('tableCommodityInvoiceAddNewItemDataUnloading')}</p>
                                    <p><b>${rowData.unloaidng_info.warehouse}</b>,</p>
                                    <div class="row mx-0 gap-1">
                                        <div class="col-4 col-md-4 px-0" style="width: 126px">
                                            <input style="background-color: #fff" type="text" id="unloading-date"
                                                   class="form-control flatpickr-basic "
                                                   value="${rowData.unloaidng_info.date}"
                                                   placeholder="YYYY-MM-DD"/>
                                        </div>
                                        <div class="row mx-0 col-6 col-md-6 ps-0 d-flex flex-row flex-grow-1">
                                            <div class="p-0 col-auto ">
                                                <input style="background-color: #fff; width: 85px" type="text"
                                                       id="unloading-start-at"
                                                       value="${rowData.unloaidng_info.start_at}"
                                                       class="form-control flatpickr-time text-start"
                                                       placeholder="HH:MM"/>
                                            </div>
                                            <div style=" width: 10px"
                                                 class="p-0 col-auto d-flex align-items-center justify-content-center  ">
                                                -
                                            </div>
                                            <div class="  col-auto p-0 ">

                                                <input style="background-color: #fff; width: 85px" type="text"
                                                       id="unloading-end-at"
                                                       value="${rowData.unloaidng_info.end_at}"
                                                       class="form-control flatpickr-time text-start"
                                                       placeholder="HH:MM"/>
                                            </div>
                                        </div>

                                    </div>


                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xxl-3 px-0 px-lg-1">
                                    <p>${getLocalizedText('tableCommodityInvoiceAddNewItemDataTotalWeight')} <b>${rowData.weight} ${getLocalizedText('tableCommodityInvoiceAddNewItemDataTotalWeightUnits')}</b></p>
                                    <p>${getLocalizedText('tableCommodityInvoiceAddNewItemDataActualPallets')} <b>${rowData.pallet}</b></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;

            var sortableBlock = document.getElementById('sortable');

            sortableBlock.insertAdjacentHTML('beforeend', newBlock);

            // Ініціалізація flatpickr для нових елементів, якщо потрібно
            var newlyAddedElement = sortableBlock.lastElementChild;
            var flatpickrInputs = newlyAddedElement.querySelectorAll('.flatpickr-basic');
            flatpickrInputs.forEach(function (input) {
                flatpickr(input, { locale: { firstDayOfWeek: 1 } });
            });
            var flatpickrTimeInputs = newlyAddedElement.querySelectorAll('.flatpickr-time');
            flatpickrTimeInputs.forEach(function (input) {
                flatpickr(input, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: 'H:i',
                    time_24hr: true,
                });
            });
        });

        updateDataIds(); // Call the function to update data-id

        checkAndUpdatePlaceholder(); // Перевірка після додавання елементів
    });

    hover(table, isRowHovered);
});

getIdGoodsInvoice();
