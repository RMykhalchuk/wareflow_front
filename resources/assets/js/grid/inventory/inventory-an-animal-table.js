import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { getLocalizedText } from '../../localization/inventory/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';
import { initVenueAnimalGrid } from './inventory-venue-an-animal-table.js';
import { hover } from '../components/hover.js';
import { toggleTableVisibility } from './utils/toggleTableVisibility.js';
import { formatPlacementValue } from '../../utils/formatPlacementValue.js';

$(document).ready(function () {
    let table = $('#inventory-an-animal-table');
    let isTestMode = false; // <<< Перемикач тестового режиму
    let isRowHovered = false;
    let gridInitialized = false;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;
    const el = document.querySelector('h1[data-inventory-id]');
    const inventoryId = el?.getAttribute('data-inventory-id')?.trim();

    if (!inventoryId) {
        console.error('Missing data-inventory-id on <h1>.');
    }
    const requestUrl = `${location.origin + languageBlock}/inventory/${encodeURIComponent(inventoryId)}/leftover/items`;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },

        // name
        { name: 'name_title', map: 'name>title', type: 'string' },
        { name: 'name_barcode', map: 'name>barcode', type: 'string' },
        { name: 'name_manufacturer', map: 'name>manufacturer', type: 'string' },
        { name: 'name_category', map: 'name>category', type: 'string' },
        { name: 'name_brand', map: 'name>brand', type: 'string' },

        { name: 'party', type: 'string' },
        { name: 'manufactured', type: 'string' },
        { name: 'expiry', type: 'string' },
        { name: 'package', type: 'string' },
        { name: 'condition', type: 'string' },

        // placing
        { name: 'placing_pallet', map: 'placing>pallet', type: 'string' },
        { name: 'placing_warehouse', map: 'placing>warehouse', type: 'string' },
        { name: 'placing_zone', map: 'placing>zone', type: 'string' },
        { name: 'placing_sector', map: 'placing>sector', type: 'string' },
        { name: 'placing_row', map: 'placing>row', type: 'string' },
        { name: 'placing_cell', map: 'placing>cell', type: 'string' },
        { name: 'placing_cell_id', map: 'placing>cell_id', type: 'string' },
        { name: 'placing_code', map: 'placing>code', type: 'string' },

        { name: 'current_leftovers', type: 'number' },
        { name: 'leftovers_erp', type: 'number' },
        { name: 'divergence', type: 'string' },

        // responsible
        { name: 'responsible_name', map: 'responsible_name', type: 'string' },
        { name: 'responsible_date', map: 'responsible_date', type: 'string' },
        { name: 'responsible_time', map: 'responsible_time', type: 'string' },
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
              url: requestUrl,
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

            return toolbarRender(statusbar, table, false, 1, columnCount - 1, '-base'); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                dataField: 'id',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('viewAnimal.id'),
                width: 50,
                editable: false,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                dataField: 'name_field',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('viewAnimal.name'),
                minwidth: 150,
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
                    <span class="fw-bolder">${rowdata.name_title}</span>
                    <span>${rowdata.name_barcode}</span>
                    <span>${rowdata.name_manufacturer}</span>
                    <span>${rowdata.name_category}</span>
                    <span>${rowdata.name_brand}</span>
                </div>`;
                },
            },
            {
                text: getLocalizedText('viewAnimal.party'),
                dataField: 'party',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('viewAnimal.manufactured'),
                dataField: 'manufactured',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('viewAnimal.expiry'),
                dataField: 'expiry',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('viewAnimal.unit'),
                dataField: 'package',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                dataField: 'condition',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('viewAnimal.condition'),
                width: 180,
                editable: false,
                cellsrenderer: function (row, column, value) {
                    const isTrue =
                        value === true || value === 1 || value === 'true' || value === 'OK'; // підстрахуємося
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
                dataField: 'placing_field',
                text: getLocalizedText('viewAnimal.placing'),
                width: 200,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    return `<div class="d-flex flex-column ps-50 gap-25">
                                <div class="text-dark fw-bolder my-auto">
                                    ${formatPlacementValue(rowdata.placing_warehouse)}
                                </div>

                                <div>${formatPlacementValue(rowdata.placing_zone)}</div>
                                <div>${formatPlacementValue(rowdata.placing_sector)}</div>
                                <div>${formatPlacementValue(rowdata.placing_row)}</div>
                                <div>${formatPlacementValue(rowdata.placing_cell)}</div>
                                <div>${formatPlacementValue(rowdata.placing_code)}</div>
                           </div>`;
                },
            },

            {
                text: getLocalizedText('viewAnimal.leftovers_erp'),
                dataField: 'leftovers_erp',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value ? value : '-'}</p>`;
                },
            },
            {
                text: getLocalizedText('viewAnimal.current_leftovers'),
                dataField: 'current_leftovers',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value ? value : '-'}</p>`;
                },
            },
            {
                text: getLocalizedText('viewAnimal.divergence'),
                dataField: 'divergence',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    const strValue = value?.toString?.() ?? '';
                    const cellClass =
                        strValue.startsWith('+') || strValue.startsWith('-')
                            ? 'bg-light-danger '
                            : '';
                    return `<div class="flex w-100 h-100 ps-50 align-content-center ${cellClass}"><span class="text-dark">${value ? value : '-'}</span></div>`;
                },
            },
            {
                dataField: 'responsible_field',
                text: getLocalizedText('viewAnimal.responsible'),
                width: 200,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    // console.log(rowdata);
                    if (!rowdata.responsible_name) {
                        return `<div class="text-dark ps-50">-</div>`;
                    }

                    return `
                                <div class="d-flex flex-column ps-50">
                                     <a
                                        class="text-dark fw-bolder my-auto d-inline-block text-truncate"
                                        title="${rowdata.responsible_name}"
                                        href="#"
                                        style="max-width: 200px;"
                                    >
                                        ${rowdata.responsible_name}
                                    </a>
                                    <div>${rowdata.responsible_date}</div>
                                    <div>${rowdata.responsible_time}</div>
                                </div>
                            `;
                },
            },
            {
                width: '70px',
                dataField: 'action',
                align: 'center',
                cellsalign: 'center',
                text: getLocalizedText('viewVenue.action.name'),
                renderer: () => '<div></div>',
                filterable: false,
                sortable: false,
                id: 'action',
                cellClassName: 'action-table-drop ',
                className: 'action-table',
                cellsrenderer: (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) => {
                    var buttonId = 'button-' + rowdata.uid;
                    var popoverId = 'popover-' + rowdata.uid;

                    const button = `
                                            <button id="${buttonId}" style="padding:0" class="btn btn-table-cell" type="button" data-bs-toggle="popover">
                                                <img src="${window.location.origin}/assets/libs/jqwidget/jqwidgets/styles/images/castom-light-wms/menu_dots_vertical.svg" alt="menu_dots_vertical">
                                            </button>`;
                    var popoverOptions = {
                        html: true,
                        sanitize: false,
                        placement: 'left',
                        trigger: 'focus',
                        container: 'body',
                        content: () => `
                    <div id="${popoverId}">
                        <ul class="popover-castom list-unstyled">
                            <li>
                                 <a type="button"
                                     data-bs-toggle="modal"
                                     data-bs-target="#target"
                                     data-leftovers-id="${rowdata.local_id}"
                                     data-cell-name="${rowdata.placing_cell}"
                                     data-cell-id="${rowdata.placing_cell_id}"
                                     class="ps-50 fw-bold my-auto text-dark dropdown-item leftovers-btn-an-animal"
                                     style="cursor:pointer;">
                                        Переглянути результати
                                </a>
                            </li>

                        </ul>
                    </div>
                `,
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
        { label: getLocalizedText('viewAnimal.id'), value: 'local_id', checked: true },
        { label: getLocalizedText('viewAnimal.name'), value: 'name_field', checked: true },
        { label: getLocalizedText('viewAnimal.party'), value: 'party', checked: true },
        {
            label: getLocalizedText('viewAnimal.manufactured'),
            value: 'manufactured',
            checked: true,
        },
        { label: getLocalizedText('viewAnimal.expiry'), value: 'expiry', checked: true },
        { label: getLocalizedText('viewAnimal.unit'), value: 'unit', checked: true },
        { label: getLocalizedText('viewAnimal.placing'), value: 'placing_field', checked: true },
        {
            label: getLocalizedText('viewAnimal.current_leftovers'),
            value: 'current_leftovers',
            checked: true,
        },
        {
            label: getLocalizedText('viewAnimal.leftovers_erp'),
            value: 'leftovers_erp',
            checked: true,
        },
        { label: getLocalizedText('viewAnimal.divergence'), value: 'divergence', checked: true },
        {
            label: getLocalizedText('viewAnimal.responsible'),
            value: 'responsible_field',
            checked: true,
        },
    ];

    listbox(table, listSource, '-base');
    hover(table, isRowHovered);

    $(document).on('click', '.leftovers-btn-an-animal', function () {
        const leftoversId = $(this).data('leftovers-id'); // локальний id залишків
        const cellName = $(this).data('cell-name');
        const cellId = $(this).data('cell-id'); // id комірки
        const $table = $('#inventory-venue-an-animal-table');

        // Назва комірки
        $('#cell-name').text(cellName);

        // Записуємо значення у кнопку підтвердження
        $('#add_leftovers_submit').data('leftovers-id', leftoversId).data('cell-id', cellId);

        if (!gridInitialized) {
            initVenueAnimalGrid(leftoversId);

            gridInitialized = true;
        } else {
            // 🔹 Отримуємо збережений dataAdapter
            const dataAdapter = $table.data('adapter');

            if (dataAdapter && dataAdapter._source) {
                dataAdapter._source.url = `${location.origin + languageBlock}/inventory/${encodeURIComponent(leftoversId)}/leftovers`;
                // 🔹 Викликаємо оновлення даних
                $table.jqxGrid('updatebounddata');
            }
        }
        // 🔹 Обробка після завершення завантаження
        $table
            .off('bindingcomplete.leftovers-an-animal')
            .on('bindingcomplete.leftovers-an-animal', async function () {
                const rows = $table.jqxGrid('getrows') || [];

                // 🔹 Показати / сховати таблицю
                toggleTableVisibility($table, rows);

                // Відключаємо обробник, щоб не накопичувалися події
                $table.off('bindingcomplete.leftovers-an-animal');
            });
    });
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
        party: '20250708-01',
        manufactured: '8.8.2025',
        expiry: '8.9.2025',
        package: 'Палета',
        condition: 'OK',
        placing: {
            pallet: 'Пасік 2',
            warehouse: 'Склад 1',
            zone: 'Зона 1',
            cell: 'A01-01-01',
            code: 'K1234',
        },
        current_leftovers: 50,
        leftovers_erp: 50,
        divergence: '50',
        responsible: {
            name: 'Ігнатенко В.І',
            date: '2025.08.05',
            time: '12:00',
        },
    },
    {
        id: '2',
        local_id: '2',
        name: {
            title: 'Calacatta 29,7×60',
            barcode: '4820394857216',
            manufacturer: 'Cersanit',
            category: 'Будматеріали',
            brand: 'BETONHOME',
        },
        party: '20250708-01',
        manufactured: '8.8.2025',
        expiry: '8.9.2025',
        package: 'Палета',
        condition: 'BAD',
        placing: {
            pallet: 'Пасік 2',
            warehouse: 'Склад 1',
            zone: 'Зона 1',
            cell: 'A01-01-01',
            code: 'K1234',
        },
        current_leftovers: 40,
        leftovers_erp: 50,
        divergence: '-10',
        responsible: {
            name: 'Ігнатенко В.І',
            date: '2025.08.05',
            time: '12:00',
        },
    },
    {
        id: '3',
        local_id: '3',
        name: {
            title: 'Calacatta 29,7×60',
            barcode: '4820394857216',
            manufacturer: 'Cersanit',
            category: 'Будматеріали',
            brand: 'BETONHOME',
        },
        party: '20250708-01',
        manufactured: '8.8.2025',
        expiry: '8.9.2025',
        package: 'Палета',
        condition: 'OK',
        placing: {
            pallet: 'Пасік 2',
            warehouse: 'Склад 1',
            zone: 'Зона 1',
            cell: 'A01-01-01',
            code: 'K1234',
        },
        current_leftovers: 60,
        leftovers_erp: 50,
        divergence: '+10',
        responsible: {
            name: 'Ігнатенко В.І',
            date: '2025.08.05',
            time: '12:00',
        },
    },
    {
        id: '4',
        local_id: '4',
        name: {
            title: 'Calacatta 29,7×60',
            barcode: '4820394857216',
            manufacturer: 'Cersanit',
            category: 'Будматеріали',
            brand: 'BETONHOME',
        },
        party: '20250708-01',
        manufactured: '8.8.2025',
        expiry: '8.9.2025',
        package: 'Палета',
        condition: 'OK',
        placing: {
            pallet: 'Пасік 2',
            warehouse: 'Склад 1',
            zone: 'Зона 1',
            cell: 'A01-01-01',
            code: 'K1234',
        },
        current_leftovers: null,
        leftovers_erp: null,
        divergence: null,
        responsible: null,
    },
];
