import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { getLocalizedText } from '../../localization/inventory/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';
import { initVenueAnimalGrid } from './inventory-venue-an-animal-table.js';
import { hover } from '../components/hover.js';
import { toggleTableVisibility } from '../document/arrival/preview-document-sku-table.js';

$(document).ready(function () {
    let table = $('#inventory-venue-table');
    let isRowHovered = false;
    let isTestMode = false;
    let gridInitialized = false;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    const inventoryId = $('#inventory-container').data('id');

    const requestUrl = `${location.origin + languageBlock}/inventory/${encodeURIComponent(inventoryId)}/items`;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },

        { name: 'zone', type: 'string' },
        { name: 'cell', type: 'string' },

        // status object
        { name: 'status_value', map: 'status>value', type: 'number' },
        { name: 'status_label', map: 'status>label', type: 'string' },

        { name: 'leftovers_quantity', map: 'leftovers>quantity', type: 'number' },
        { name: 'leftovers_id', map: 'leftovers>id', type: 'string' },

        // responsible
        { name: 'invented_name', map: 'invented>name', type: 'string' },
        { name: 'invented_date', map: 'invented>date', type: 'string' },
        { name: 'invented_time', map: 'invented>time', type: 'string' },
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

    // Зберігаємо adapter у DOM, щоб обробник кліку мав доступ
    table.data('adapter', dataAdapter);

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
        rowsheight: 100,
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

            return toolbarRender(statusbar, table, false, 1, columnCount - 1, '-venue'); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                dataField: 'id',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('viewVenue.id'),
                width: 150,
                editable: false,
                cellsrenderer: (row, column, value) => `<p class="ps-50 my-auto">${value}</p>`,
            },
            {
                dataField: 'zone',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('viewVenue.zone'),
                minwidth: 150,
                editable: false,
                cellsrenderer: (row, column, value) => `<p class="ps-50 my-auto">${value}</p>`,
            },
            {
                dataField: 'cell',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('viewVenue.cell'),
                width: 150,
                editable: false,
                cellsrenderer: (row, column, value) => `<p class="ps-50 my-auto">${value}</p>`,
            },
            {
                dataField: 'status_field',
                align: 'left',
                text: getLocalizedText('viewVenue.status'),
                width: 200,
                editable: false,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    let badgeClass;

                    switch (rowdata.status_value) {
                        case 0:
                            badgeClass = 'bg-light-secondary';
                            break;
                        case 1:
                            badgeClass = 'bg-light-success';
                            break;
                        case 3:
                            badgeClass = 'bg-light-danger';
                            break;
                        default:
                            badgeClass = 'bg-light-info';
                    }
                    return `<span class="badge ${badgeClass} text-white ms-50 py-50 px-1 text-capitalize">${rowdata.status_label || '-'}</span>`;
                },
            },
            {
                dataField: 'leftovers_field',
                text: getLocalizedText('viewVenue.current_leftovers'),
                width: 200,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    // // Якщо статус = 1 — робимо кнопку активною
                    // if (
                    //     rowdata.status_value === 0 ||
                    //     rowdata.status_value === 1 ||
                    //     rowdata.status_value === 2
                    // ) {
                    //     return `
                    // <div type="button"
                    //      data-bs-toggle="modal"
                    //      data-bs-target="#target"
                    //      data-leftovers-id="${rowdata.local_id}"
                    //      data-cell-name="${rowdata.cell}"
                    //      data-cell-id="${rowdata.local_id}"
                    //      class="ps-50 fw-bold my-auto text-dark text-decoration-underline leftovers-btn"
                    //      style="cursor:pointer;">
                    //      ${rowdata.leftovers_quantity}
                    // </div>`;
                    // }
                    return `<div class="ps-50 fw-bold my-auto text-dark">${rowdata.leftovers_quantity}</div>`;
                },
            },
            {
                dataField: 'invented_field',
                text: getLocalizedText('viewVenue.invented'),
                width: 200,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    if (!rowdata.invented_name) {
                        return `<div class="text-dark ps-50">-</div>`;
                    }

                    return `
                            <div class="d-flex flex-column ps-50">
                                <a
                                    class="text-dark fw-bolder my-auto d-inline-block text-truncate"
                                    title="${rowdata.invented_name}"
                                    href="#"
                                    style="max-width: 200px;"
                                >
                                    ${rowdata.invented_name}
                                </a>
                                <div>${rowdata.invented_date}</div>
                                <div>${rowdata.invented_time}</div>
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
                cellClassName: 'action-table-drop',
                className: 'action-table',
                cellsrenderer: (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) => {
                    const isEnabled = [0, 1, 2].includes(rowdata.status_value); // Доступна кнопка тільки для цих статусів
                    const buttonId = 'button-' + rowdata.uid;
                    const popoverId = 'popover-' + rowdata.uid;

                    const button = `
            <button id="${buttonId}" style="padding:0" class="btn btn-table-cell" type="button" data-bs-toggle="popover">
                <img src="${window.location.origin}/assets/libs/jqwidget/jqwidgets/styles/images/castom-light-wms/menu_dots_vertical.svg" alt="menu_dots_vertical">
            </button>`;

                    const popoverOptions = {
                        html: true,
                        sanitize: false,
                        placement: 'left',
                        trigger: 'focus',
                        container: 'body',
                        content: () => `
                <div id="${popoverId}">
                    <ul class="popover-castom list-unstyled">
                        <li>
                            <a class="dropdown-item ${isEnabled ? 'open-leftovers-modal' : 'disabled'}" href="#"
                               data-leftovers-id="${rowdata.local_id}"
                               data-cell-name="${rowdata.cell}"
                               data-cell-id="${rowdata.local_id}">
                               ${getLocalizedText('viewVenue.action.view')}
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
        { label: getLocalizedText('viewVenue.id'), value: 'local_id', checked: true },
        { label: getLocalizedText('viewVenue.zone'), value: 'zone', checked: true },
        { label: getLocalizedText('viewVenue.cell'), value: 'cell', checked: true },
        { label: getLocalizedText('viewVenue.status'), value: 'status_field', checked: true },
        {
            label: getLocalizedText('viewVenue.current_leftovers'),
            value: 'current_leftovers',
            checked: true,
        },
        {
            label: getLocalizedText('viewVenue.invented'),
            value: 'invented_field',
            checked: true,
        },
        // { label: getLocalizedText('viewVenue.action.name'), value: 'action', checked: true },
    ];

    listbox(table, listSource, '-venue');
    hover(table, isRowHovered);

    // Обробник при кліку на комірку старий варіант
    $(document).on('click', '.leftovers-btn', function () {
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
                dataAdapter._source.url = `${location.origin + languageBlock}/inventory/${encodeURIComponent(cellId)}/leftovers`;
                // 🔹 Викликаємо оновлення даних
                $table.jqxGrid('updatebounddata');
            }
        }
        // 🔹 Обробка після завершення завантаження
        $table.off('bindingcomplete.leftovers').on('bindingcomplete.leftovers', async function () {
            const rows = $table.jqxGrid('getrows') || [];

            // 🔹 Показати / сховати таблицю
            toggleTableVisibility($table, rows);

            // Відключаємо обробник, щоб не накопичувалися події
            $table.off('bindingcomplete.leftovers');
        });
    });

    // Обробник відкриття модалки по кліку в поповері
    $(document).on('click', '.open-leftovers-modal', function (e) {
        e.preventDefault();
        const $item = $(this);

        // Якщо пункт disabled — не робимо нічого
        if ($item.hasClass('disabled')) return;

        const leftoversId = $item.data('leftovers-id');
        const cellName = $item.data('cell-name');
        const cellId = $item.data('cell-id');
        const $table = $('#inventory-venue-an-animal-table');

        $('#cell-name').text(cellName);
        $('#add_leftovers_submit').data('leftovers-id', leftoversId).data('cell-id', cellId);

        if (!gridInitialized) {
            initVenueAnimalGrid(leftoversId);
            gridInitialized = true;
        } else {
            const dataAdapter = $table.data('adapter');
            if (dataAdapter && dataAdapter._source) {
                dataAdapter._source.url = `${location.origin + languageBlock}/inventory/${encodeURIComponent(cellId)}/leftovers`;
                $table.jqxGrid('updatebounddata');
            }
        }

        $table.off('bindingcomplete.leftovers').on('bindingcomplete.leftovers', function () {
            const rows = $table.jqxGrid('getrows') || [];
            toggleTableVisibility($table, rows);
            $table.off('bindingcomplete.leftovers');
        });

        const modal = new bootstrap.Modal(document.getElementById('target'));
        modal.show();
    });

    // Обробник кнопки підтвердження
    $(document).on('click', '#target_submit', async function () {
        const $table = $('#inventory-venue-an-animal-table');
        const rows = $table.jqxGrid('getrows') || [];

        toggleTableVisibility($table, rows);
    });
});

let customData = [
    {
        id: '1',
        local_id: '1',
        zone: 'Зона 1',
        cell: 'A11-01-01',
        leftovers: {
            id: '24321',
            quantity: 50,
        },
        status: {
            value: 1,
            label: 'До інвентаризації',
        },
        invented: {
            name: 'Ігнатенко В.І',
            date: '2025.08.05',
            time: '12:00',
        },
    },
    {
        id: '2',
        local_id: '2',
        zone: 'Зона 1',
        cell: 'A11-01-01',
        leftovers: {
            id: '24321',
            quantity: 50,
        },
        status: {
            value: 2,
            label: 'Без розбіжностей',
        },
        invented: {
            name: 'Ігнатенко В.І',
            date: '2025.08.05',
            time: '12:00',
        },
    },
    {
        id: '3',
        local_id: '3',
        zone: 'Зона 1',
        cell: 'A11-01-01',
        leftovers: {
            id: '24321',
            quantity: 50,
        },
        status: {
            value: 3,
            label: 'З розбіжностями',
        },
        invented: {
            name: 'Ігнатенко В.І',
            date: '2025.08.05',
            time: '12:00',
        },
    },
    {
        id: '4',
        local_id: '4',
        zone: 'Зона 1',
        cell: 'A11-01-01',
        leftovers: {
            id: '24321',
            quantity: 50,
        },
        status: {
            value: 1,
            label: 'До інвентаризації',
        },
        invented: {
            name: 'Ігнатенко В.І',
            date: '2025.08.05',
            time: '12:00',
        },
    },
];
