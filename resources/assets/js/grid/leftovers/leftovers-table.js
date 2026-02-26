import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { getLocalizedText } from '../../localization/leftovers/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';
import { hover } from '../components/hover.js';
$(document).ready(function () {
    let table = $('#leftoversDataTable');
    let isRowHovered = false;
    let isTestMode = false; // <<< Перемикач тестового режиму

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },

        // name
        { name: 'name_id', map: 'goods>id', type: 'string' },
        { name: 'name_name', map: 'goods>name', type: 'string' },
        { name: 'name_barcode', map: 'goods>barcode', type: 'string' },
        { name: 'name_manufacturer', map: 'goods>manufacturer', type: 'string' },
        { name: 'name_category', map: 'goods>category', type: 'string' },
        { name: 'name_provider', map: 'goods>provider', type: 'string' },

        // status object
        { name: 'status_value', map: 'status>value', type: 'number' },
        { name: 'status_name', map: 'status>name', type: 'string' },
        { name: 'status_label', map: 'status>label', type: 'string' },
        { name: 'status_label_en', map: 'status>label_en', type: 'string' },
        { name: 'status_description', map: 'status>description', type: 'string' },
        { name: 'status_is_available', map: 'status>is_available', type: 'bool' },
        { name: 'status_is_reserved', map: 'status>is_reserved', type: 'bool' },

        { name: 'batch', type: 'string' },

        { name: 'manufacture_date', type: 'string' },
        { name: 'bb_date', type: 'string' },
        { name: 'package', type: 'string' },
        { name: 'has_condition', type: 'bool' },

        // allocation object (колишній placing)
        { name: 'allocation_zone', map: 'allocation>zone', type: 'string' },
        { name: 'allocation_sector', map: 'allocation>sector', type: 'string' },
        { name: 'allocation_warehouse', map: 'allocation>warehouse', type: 'string' },
        { name: 'allocation_location', map: 'allocation>location', type: 'string' },
        { name: 'allocation_cell', map: 'allocation>cell', type: 'string' },
        { name: 'allocation_container', map: 'allocation>container', type: 'string' },

        // quantity object
        {
            name: 'quantity_measurement_unit_quantity',
            map: 'quantity>measurement_unit_quantity',
            type: 'number',
        },
        { name: 'quantity_measurement_unit', map: 'quantity>measurement_unit', type: 'string' },
        { name: 'quantity_package_quantity', map: 'quantity>package_quantity', type: 'number' },
        { name: 'quantity_package', map: 'quantity>package', type: 'string' },
    ];

    // ======= Отримуємо параметри з URL =======
    const urlParams = new URLSearchParams(window.location.search);

    const cellId = urlParams.get('cell_id');
    const zoneId = urlParams.get('zone_id');
    const sectorId = urlParams.get('sector_id');

    // ======= Формуємо URL для запиту =======
    let apiUrl;

    // Пріоритет: cell → zone → sector → default
    if (cellId) {
        apiUrl = `${window.location.origin}${languageBlock}/leftovers/table/filter?cell_id=${cellId}`;
    } else if (zoneId) {
        apiUrl = `${window.location.origin}${languageBlock}/leftovers/table/filter?zone_id=${zoneId}`;
    } else if (sectorId) {
        apiUrl = `${window.location.origin}${languageBlock}/leftovers/table/filter?sector_id=${sectorId}`;
    } else {
        apiUrl = `${window.location.origin}${languageBlock}/leftovers/table/filter`;
    }

    // ======= Джерело даних =======
    let myDataSource = isTestMode
        ? {
              datatype: 'array',
              datafields: dataFields,
              localdata: customData,
          }
        : {
              datatype: 'json',
              datafields: dataFields,
              url: apiUrl,
              root: 'data',
              beforeprocessing: function (data) {
                  console.log(data);
                  myDataSource.totalrecords = data.total;
              },
              filter: function () {
                  showLoader();
                  table.jqxGrid('updatebounddata', 'filter');
              },
              sort: function () {
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
        filterable: true,
        filtermode: 'default',
        localization: getLocalization(language),
        enablehover: false,
        columnsreorder: false,
        autoshowfiltericon: true,
        pagermode: 'advanced',
        rowsheight: 150,
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
                    label: 'Параметри залишків',
                    submenu: [
                        { label: 'Термін придатності', value: 'company' },
                        { label: 'Дата виготовлення', value: 'manufacture_date' },
                        { label: 'Вжити до', value: 'manufacture_date' },
                        { label: 'Партія', value: 'manufacture_date' },
                        { label: 'Пакінг', value: 'manufacture_date' },
                        { label: 'Кондиція', value: 'manufacture_date' },
                        { label: 'Статус', value: 'manufacture_date' },
                    ],
                },
                {
                    label: 'Параметри товару',
                    submenu: [
                        { label: 'Назва', value: 'use_by' },
                        { label: 'Категорія/підкатегорія', value: 'batch' },
                        { label: 'Штрих-код', value: 'batch' },
                        { label: 'Виробник', value: 'batch' },
                        { label: 'Бренд', value: 'batch' },
                    ],
                },
                {
                    label: 'Параметри розміщення',
                    submenu: [
                        { label: 'Локація', value: 'company' },
                        { label: 'Склад', value: 'warehouse' },
                        { label: 'Зона', value: 'company' },
                        { label: 'Ряд', value: 'company' },
                        { label: 'Комірка', value: 'company' },
                        { label: 'Контейнер', value: 'company' },
                    ],
                },
            ];

            return toolbarRender(statusbar, table, false, 1, columnCount - 1, '', filterMenuData); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                dataField: 'local_id',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('id'),
                width: 50,
                editable: false,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${myDataSource.totalrecords - row}</p>`;
                },
            },
            {
                dataField: 'name_field',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('name'),
                minwidth: 400,
                editable: false,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<div class="d-flex flex-column ps-50 gap-25">
                    <a href="${window.location.origin + languageBlock}/sku/${rowdata.name_id}" class="text-dark fw-bolder text-decoration-underline">${rowdata.name_name ?? '-'}</a>
                    <span>${rowdata.name_barcode ?? '-'}</span>
                    <span>${rowdata.name_manufacturer ?? '-'}</span>
                    <span>${rowdata.name_category ?? '-'}</span>
                    <span>${rowdata.name_provider ?? '-'}</span>
                </div>`;
                },
            },
            {
                dataField: 'status_field',
                align: 'left',
                text: getLocalizedText('status'),
                width: 150,
                editable: false,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    // тут value вже об'єкт, а не рядок
                    const isActive = rowdata.status_value === 1;
                    const badgeClass = isActive ? 'bg-light-success' : 'bg-light-danger';

                    // Визначаємо текст в залежності від мови
                    const language = switchLang();
                    const badgeText =
                        language === 'en'
                            ? rowdata.status_label_en || ''
                            : rowdata.status_label || '';

                    return `<span class="badge ${badgeClass} text-white ms-50 py-50 px-1 text-capitalize">${badgeText}</span>`;
                },
            },
            {
                text: getLocalizedText('party'),
                dataField: 'batch',
                width: 100,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('manufactured'),
                dataField: 'manufacture_date',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('expiry'),
                dataField: 'bb_date',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('package'),
                dataField: 'package',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                dataField: 'has_condition',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('condition'),
                width: 180,
                editable: false,
                cellsrenderer: function (row, column, value) {
                    const isTrue = value === true || value === 1 || value === 'true'; // підстрахуємося
                    const badgeClass = isTrue ? 'bg-success' : 'bg-danger';

                    // Локалізація тексту
                    const language = switchLang();
                    const badgeText = isTrue
                        ? language === 'en'
                            ? 'Not damaged'
                            : 'Не пошкоджений'
                        : language === 'en'
                          ? 'Damaged'
                          : 'Пошкоджений';

                    return `
                            <div class="d-flex align-items-center ps-50 gap-50">
                                <div class="${badgeClass} border-radius-50" style="width: 10px; height: 10px;"></div>
                                <span class="fw-bolder">${badgeText}</span>
                            </div>`;
                },
            },

            {
                dataField: 'allocation',
                text: getLocalizedText('placing'),
                width: 250,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const place = rowdata || {};

                    return `<div class="d-flex flex-column ps-50 gap-25">
                                <div>${place.allocation_zone || '-'}</div>
                                <div>${place.allocation_sector || '-'}</div>
                                <div>${place.allocation_cell || '-'}</div>
                                <div>${place.allocation_container || '-'}</div>
                            </div>`;
                },
            },

            {
                dataField: 'quantity_field',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('quantity'),
                width: 150,
                editable: false,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    // value вже об'єкт
                    const qty = rowdata || {};

                    // <span>${qty.area} ${getLocalizedText('areaUnit')}</span>;
                    // <span>${qty.pallets} ${getLocalizedText('palletsUnit')}</span>;

                    return `<div class="d-flex flex-column ps-50 gap-25">
                    <span>${qty.quantity_measurement_unit_quantity} ${qty.quantity_measurement_unit}</span>
                    <span>${qty.quantity_package_quantity} ${qty.quantity_package}</span>

                </div>`;
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
                            return `
                                    <div id="${popoverId}">
                                        <ul class="popover-castom" class="list-unstyled">
                                            <li>
                                                <a class="dropdown-item" href="${window.location.origin}${languageBlock}/leftovers/${rowdata.uid}">
                                                    ${getLocalizedText('actionBtn1')}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                `;
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
        { label: getLocalizedText('id'), value: 'local_id', checked: true },
        { label: getLocalizedText('name'), value: 'name_field', checked: true },
        {
            label: getLocalizedText('status'),
            value: 'status_field',
            checked: true,
        },
        { label: getLocalizedText('party'), value: 'batch', checked: true },
        { label: getLocalizedText('manufactured'), value: 'manufacture_date', checked: true },
        { label: getLocalizedText('expiry'), value: 'bb_date', checked: true },
        { label: getLocalizedText('package'), value: 'package', checked: true },
        { label: getLocalizedText('condition'), value: 'has_condition', checked: true },
        { label: getLocalizedText('placing'), value: 'allocation', checked: true },
        { label: getLocalizedText('quantity'), value: 'quantity_field', checked: true },
        { label: getLocalizedText('action'), value: 'action', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});

// let warehousesArr = '';
// $('#warehouse-type').change(function (e) {
//     let warehouses = $('#warehouse-type').val();
//
//     warehousesArr = '';
//
//     if (warehouses.length) {
//         warehousesArr += '?';
//     }
//
//     warehouses.forEach((item, index) => {
//         if (index + 1 === warehouses.length) {
//             warehousesArr += `warehouses_ids[]=${item}`;
//         } else {
//             warehousesArr += `warehouses_ids[]=${item}&`;
//         }
//     });
//
//     //change url in data source
//     let newSource = table.jqxGrid('source');
//     newSource._source.url = window.location.origin + `/leftovers/table/filter${warehousesArr}`;
//     table.jqxGrid('source', newSource);
// });

let customData = [];
