import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { getLocalizedText } from '../../localization/transport-planning/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';

import { updateSelectedCount } from './utils/updateSelectedCount.js';
import { getIdGoodsInvoice } from './utils/getIdGoodsInvoice.js';
import { updateDataIds } from './utils/updateDataIds.js';
import { checkAndUpdatePlaceholder } from './utils/checkAndUpdatePlaceholder.js';
import { initSortableDocItem } from './utils/initSortableDocItem.js';

const toolbarRendererLeftovers = toolbarRender.bind({});
const pagerRendererLeftovers = pagerRenderer.bind({});

$(document).ready(function () {
    let table = $('#transportRequestDataTable');
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
            `<div>${getLocalizedText('tableRequestForTransportGoodsRenderWeight')} <span class='fw-bold'>${rowData.weight} ${getLocalizedText('tableRequestForTransportGoodsRenderWeightUnits')}</span></div>` +
            `<div>${getLocalizedText('tableRequestForTransportGoodsRenderPallet')} <span class='fw-bold'>${rowData.pallet}</span></div>` +
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
        url: window.location.origin + '/transport-planning/table/transport-request-filter',
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
                text: getLocalizedText('tableRequestForTransportNumber'),
                editable: false,

                cellsrenderer: function (row, column, value, defaultHtml, columnSettings, rowData) {
                    return `<a href='${window.location.origin + languageBlock}/document/${rowData.id}' class="mb-0 d-flex fw-bold">№ ${rowData.id}</a>`;
                },
                minwidth: 300,
            },
            {
                dataField: 'loading_render',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('tableRequestForTransportLoading'),
                minwidth: 100,
                editable: false,
                cellsrenderer: loadingRender,
            },
            {
                minwidth: 100,
                dataField: 'unloading_render',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('tableRequestForTransportUnloading'),
                editable: false,
                cellsrenderer: unloadingRender,
            },
            {
                minwidth: 350,
                dataField: 'goods_render',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('tableRequestForTransportGoods'),
                editable: false,
                cellsrenderer: goodsRender,
            },
        ],
    });

    let listSource = [
        {
            label: getLocalizedText('tableRequestForTransportNumber'),
            value: 'number',
            checked: true,
        },
        {
            label: getLocalizedText('tableRequestForTransportLoading'),
            value: 'loading_render',
            checked: true,
        },
        {
            label: getLocalizedText('tableRequestForTransportUnloading'),
            value: 'unloading_render',
            checked: true,
        },
        {
            label: getLocalizedText('tableRequestForTransportGoods'),
            value: 'goods_render',
            checked: true,
        },
    ];

    listbox(table, listSource, '-tr-request');

    // додаємо обробник події на зміну вибраних рядків у таблиці jqxGrid
    table.on('rowselect rowunselect', function () {
        updateSelectedCount(table2, table1, addNewTransportRequestItem, addNewGoodsInvoicesItem);
    });

    initSortableDocItem();

    $('body').on('click', '.delete-invoices', function () {
        $(this).closest('.goods-invoices-item').remove();
        updateDataIds(); // Call the function to update data-id
        checkAndUpdatePlaceholder(); // Перевірка після видалення елементів
    });

    addNewTransportRequestItem.on('click', function () {
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
            console.log(rowData);
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
                                        <h5 class="mb-0 d-flex gap-1">${getLocalizedText('tableRequestForTransportAddNewItemTitle')}
                                        <a class="link-primary"
                                           href='${window.location.origin + languageBlock}/document/${idNumber}'>${getLocalizedText('tableRequestForTransportAddNewItemTitleNumber')} ${rowData.id}</a>
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
                                    <p>${getLocalizedText('tableRequestForTransportAddNewItemDataLoading')}</p>
                                    <p><b>${rowData.loading.company}</b>,</p>
                                    <div class="row mx-0 gap-1">
                                        <div class="col-4 col-md-4 px-0" style="width: 126px">
                                            <input style="background-color: #fff" type="text" id="loading-date"
                                                   class="form-control flatpickr-basic "
                                                   value="${rowData.loading.date}"
                                                   placeholder="YYYY-MM-DD"/>
                                        </div>
                                        <div class="row mx-0 col-6 col-md-6 ps-0 d-flex flex-row flex-grow-1 ">
                                            <div class="p-0 col-auto ">
                                                <input style="background-color: #fff; width: 85px" type="text"
                                                       id="loading-start-at"
                                                       value="${rowData.loading.start_at}"
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
                                                       value="${rowData.loading.end_at}"
                                                       class="form-control flatpickr-time text-start"
                                                       placeholder="HH:MM"/>
                                            </div>
                                        </div>

                                    </div>


                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xxl-4 px-0 px-lg-1">
                                    <p>${getLocalizedText('tableRequestForTransportAddNewItemDataUnloading')}</p>
                                    <p><b>${rowData.unloading.company}</b>,</p>
                                    <div class="row mx-0 gap-1">
                                        <div class="col-4 col-md-4 px-0" style="width: 126px">
                                            <input style="background-color: #fff" type="text" id="unloading-date"
                                                   class="form-control flatpickr-basic "
                                                   value="${rowData.unloading.date}"
                                                   placeholder="YYYY-MM-DD"/>
                                        </div>
                                        <div class="row mx-0 col-6 col-md-6 ps-0 d-flex flex-row flex-grow-1">
                                            <div class="p-0 col-auto ">
                                                <input style="background-color: #fff; width: 85px" type="text"
                                                       id="unloading-start-at"
                                                       value="${rowData.unloading.start_at}"
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
                                                       value="${rowData.unloading.end_at}"
                                                       class="form-control flatpickr-time text-start"
                                                       placeholder="HH:MM"/>
                                            </div>
                                        </div>

                                    </div>


                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xxl-3 px-0 px-lg-1">
                                    <p>${getLocalizedText('tableRequestForTransportAddNewItemDataTotalWeight')} <b>${rowData.weight} ${getLocalizedText('tableRequestForTransportAddNewItemDataTotalWeightUnits')}</b></p>
                                    <p>${getLocalizedText('tableRequestForTransportAddNewItemDataActualPallets')} <b>${rowData.pallet}</b></p>
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
        checkAndUpdatePlaceholder(); // Перевірка після видалення елементів
    });

    hover(table, isRowHovered);
});

getIdGoodsInvoice();
