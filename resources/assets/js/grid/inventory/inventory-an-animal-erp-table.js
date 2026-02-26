import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { getLocalizedText } from '../../localization/inventory/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';

$(document).ready(function () {
    let table = $('#inventory-an-animal-erp-table');
    let isTestMode = true; // <<< Перемикач тестового режиму

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },

        { name: 'title', map: 'name>title', type: 'string' },
        { name: 'barcode', map: 'name>barcode', type: 'string' },
        { name: 'manufacturer', map: 'name>manufacturer', type: 'string' },
        { name: 'category', map: 'name>category', type: 'string' },
        { name: 'brand', map: 'name>brand', type: 'string' },
        { name: 'unit', type: 'string' },

        { name: 'current_leftovers', type: 'number' },
        { name: 'leftovers_erp', type: 'number' },
        { name: 'divergence', type: 'string' },
    ];

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
              url: window.location.origin + languageBlock + '/inventory-erp/table/filter',
              root: 'data',
              beforeprocessing: function (data) {
                  console.log(data);
                  myDataSource.totalrecords = data.total;
              },
              filter: function () {
                  table.jqxGrid('updatebounddata', 'filter');
              },
              sort: function () {
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
        filterable: false,
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

            return toolbarRender(statusbar, table, false, 1, columnCount - 1, '-erp'); // Subtract 1 to exclude the action column
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
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                dataField: 'name',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('viewErp.name'),
                minwidth: 250,
                editable: false,
                cellsrenderer: (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) => {
                    // Можна зробити, щоб не показувало прогресбар, якщо нема weight
                    if (!rowdata.title) {
                        return `<div class="text-muted ps-50">Відсутнє</div>`;
                    }

                    const title = rowdata.title;
                    const barcode = rowdata.barcode;
                    const manufacturer = rowdata.manufacturer;
                    const category = rowdata.category;
                    const brand = rowdata.brand;

                    return `<div class="d-flex flex-column ps-50 gap-25">
                                <span class="fw-bolder">${title}</span>
                                <span>${barcode}</span>
                                <span>${manufacturer}</span>
                                <span>${category}</span>
                                <span>${brand}</span>
                            </div>`;
                },
            },
            {
                text: getLocalizedText('viewErp.unit'),
                dataField: 'unit',
                width: 200,
                cellsrenderer: function (row, column, value) {
                    let displayValue = value;

                    // Змінюємо відображення залежно від unit
                    if (value === 'm2') {
                        displayValue = getLocalizedText('viewErp.unit_1'); // локалізована одиниця
                    } else if (value === 'box') {
                        displayValue = getLocalizedText('viewErp.unit_2'); // приклад для коробки
                    }

                    return `<p class="ps-50 my-auto">${displayValue}</p>`;
                },
            },

            {
                text: getLocalizedText('viewErp.current_leftovers'),
                dataField: 'current_leftovers',
                width: 200,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('viewErp.leftovers_erp'),
                dataField: 'leftovers_erp',
                width: 200,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('viewErp.divergence'),
                dataField: 'divergence',
                width: 200,
                cellsrenderer: function (row, column, value) {
                    // Переводимо у рядок для перевірки наявності знаку
                    const strValue = value.toString();
                    const cellClass =
                        strValue.startsWith('+') || strValue.startsWith('-')
                            ? 'bg-light-danger '
                            : '';
                    return `<div class="flex w-100 h-100 ps-50 align-content-center ${cellClass}"><span class="text-dark">${value}</span></div>`;
                },
            },
        ],
    });

    let listSource = [
        { label: getLocalizedText('viewErp.id'), value: 'local_id', checked: true },
        { label: getLocalizedText('viewErp.name'), value: 'name', checked: true },
        { label: getLocalizedText('viewErp.unit'), value: 'unit', checked: true },
        {
            label: getLocalizedText('viewErp.current_leftovers'),
            value: 'current_leftovers',
            checked: true,
        },
        { label: getLocalizedText('viewErp.leftovers_erp'), value: 'leftovers_erp', checked: true },
        { label: getLocalizedText('viewErp.divergence'), value: 'divergence', checked: true },
    ];

    listbox(table, listSource, '-erp');
});

let customData = [
    {
        id: '1',
        local_id: '1',
        name: {
            title: 'Calacatta 29,7×60',
            barcode: '4820394857216',
            manufacturer: 'Cersanit',
            category: 'Будматеріали',
            brand: 'BETONHOME',
        },
        unit: 'm2',
        current_leftovers: 50,
        leftovers_erp: 50,
        divergence: '50',
    },
    {
        id: '2',
        local_id: '2',
        name: {
            title: 'Calacatta 29,7×70',
            barcode: '4820394857216',
            manufacturer: 'Cersanit',
            category: 'Будматеріали',
            brand: 'BETONHOME',
        },
        unit: 'box',
        current_leftovers: 40,
        leftovers_erp: 50,
        divergence: '-10',
    },
    {
        id: '3',
        local_id: '3',
        name: {
            title: 'Calacatta 29,7×80',
            barcode: '4820394857216',
            manufacturer: 'Cersanit',
            category: 'Будматеріали',
            brand: 'BETONHOME',
        },
        unit: 'm2',
        current_leftovers: 60,
        leftovers_erp: 50,
        divergence: '+10',
    },
    {
        id: '4',
        local_id: '4',
        name: {
            title: 'Calacatta 29,7×30',
            barcode: '4820394857216',
            manufacturer: 'Cersanit',
            category: 'Будматеріали',
            brand: 'BETONHOME',
        },
        unit: 'm2',
        current_leftovers: 50,
        leftovers_erp: 50,
        divergence: '50',
    },
];
