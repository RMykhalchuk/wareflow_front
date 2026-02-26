import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { getLocalizedText } from '../../localization/tasks/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';

$(document).ready(function () {
    let table = $('#leftovers-table');
    let isTestMode = true; // <<< Перемикач тестового режиму

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

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
        { name: 'has_condition', type: 'bool' },

        // placing
        { name: 'placing_pallet', map: 'placing>pallet', type: 'string' },
        { name: 'placing_warehouse', map: 'placing>warehouse', type: 'string' },
        { name: 'placing_zone', map: 'placing>zone', type: 'string' },
        { name: 'placing_cell', map: 'placing>cell', type: 'string' },
        { name: 'placing_code', map: 'placing>code', type: 'string' },

        { name: 'before_moving', type: 'number' },
        { name: 'moved', type: 'number' },

        // moved_responsible
        { name: 'moved_responsible_name', map: 'moved_responsible>name', type: 'string' },
        { name: 'moved_responsible_date', map: 'moved_responsible>date', type: 'string' },
        { name: 'moved_responsible_time', map: 'moved_responsible>time', type: 'string' },
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
              url: window.location.origin + languageBlock + '/inventory-an-animal/table/filter',
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
        showToolbar: false,
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

            return toolbarRender(statusbar, table, false, 1, columnCount - 1, '-1'); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                dataField: 'id',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('view.id'),
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
                text: getLocalizedText('view.name'),
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
                text: getLocalizedText('view.party'),
                dataField: 'party',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value ? value : '-'}</p>`;
                },
            },
            {
                text: getLocalizedText('view.manufactured'),
                dataField: 'manufactured',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value ? value : '-'}</p>`;
                },
            },
            {
                text: getLocalizedText('view.expiry'),
                dataField: 'expiry',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value ? value : '-'}</p>`;
                },
            },
            {
                text: getLocalizedText('view.unit'),
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
                text: getLocalizedText('view.condition'),
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
                dataField: 'placing_field',
                text: getLocalizedText('view.placing'),
                width: 150,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    if (!rowdata.placing_warehouse) {
                        return `<div class="text-dark ps-50">-</div>`;
                    }

                    return `<div class="d-flex flex-column ps-50 gap-25">
                    <a class="text-dark fw-bolder my-auto" href='#'>${rowdata.placing_pallet}</a>
                    <div class="text-dark fw-bolder my-auto">${rowdata.placing_warehouse}</div>
                    <div>${rowdata.placing_zone}</div>
                    <div>${rowdata.placing_cell}</div>
                </div>`;
                },
            },

            {
                text: getLocalizedText('view.before_moving'),
                dataField: 'before_moving',
                width: 200,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value ? value : '-'}</p>`;
                },
            },
            // {
            //     text: getLocalizedText('view.moved'),
            //     dataField: 'moved',
            //     width: 150,
            //     cellsrenderer: function (row, column, value) {
            //         return `<p class="ps-50 my-auto">${value ? value : '-'}</p>`;
            //     },
            // },
            {
                dataField: 'moved_responsible_field',
                text: getLocalizedText('view.moved_responsible'),
                width: 150,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    if (!rowdata.moved_responsible_name) {
                        return `<div class="text-dark ps-50">-</div>`;
                    }

                    return `<div class="d-flex flex-column ps-50">
                    <a class="text-dark fw-bolder my-auto" href='#'>${rowdata.moved_responsible_name}</a>
                    <div>${rowdata.moved_responsible_date}</div>
                    <div>${rowdata.moved_responsible_time}</div>
                </div>`;
                },
            },
            // {
            //     dataField: 'action',
            //     width: '70px',
            //     align: 'center',
            //     cellsalign: 'center',
            //     renderer: function () {
            //         return '<div></div>';
            //     },
            //     filterable: false,
            //     sortable: false,
            //     id: 'action',
            //     className: 'action-table',
            //     cellsrenderer: function (
            //         row,
            //         columnfield,
            //         value,
            //         defaulthtml,
            //         columnproperties,
            //         rowdata
            //     ) {
            //         var buttonId = 'button-' + rowdata.uid;
            //         var popoverId = 'popover-' + rowdata.uid;
            //
            //         const button = `
            //                                 <button id="${buttonId}" style="padding:0" class="btn btn-table-cell" type="button" data-bs-toggle="popover">
            //                                     <img src="${window.location.origin}/assets/libs/jqwidget/jqwidgets/styles/images/castom-light-wms/menu_dots_vertical.svg" alt="menu_dots_vertical">
            //                                 </button>
            //                             `;
            //
            //         var popoverOptions = {
            //             html: true,
            //             sanitize: false,
            //             placement: 'left',
            //             trigger: 'focus',
            //             container: 'body',
            //             content: function () {
            //                 return `<div id="${popoverId}">
            //                             <ul class="popover-castom" style="list-style: none">
            //                                 <li><a class="dropdown-item" href="#">${getLocalizedText('view.action.action')}</a></li>
            //                             </ul>
            //                         </div>`;
            //             },
            //         };
            //
            //         $(document)
            //             .off('click', '#' + buttonId)
            //             .on('click', '#' + buttonId, function () {
            //                 $(this).popover(popoverOptions).popover('show');
            //             });
            //
            //         return '<div class="jqx-popover-wrapper">' + button + '</div>';
            //     },
            // },
        ],
    });

    let listSource = [
        { label: getLocalizedText('view.id'), value: 'local_id', checked: true },
        { label: getLocalizedText('view.name'), value: 'name_field', checked: true },
        { label: getLocalizedText('view.party'), value: 'party', checked: true },
        {
            label: getLocalizedText('view.manufactured'),
            value: 'manufactured',
            checked: true,
        },
        { label: getLocalizedText('view.expiry'), value: 'expiry', checked: true },
        { label: getLocalizedText('view.unit'), value: 'unit', checked: true },
        { label: getLocalizedText('view.placing'), value: 'placing_field', checked: true },
        {
            label: getLocalizedText('view.before_moving'),
            value: 'before_moving',
            checked: true,
        },
        {
            label: getLocalizedText('view.moved'),
            value: 'moved',
            checked: true,
        },
        {
            label: getLocalizedText('view.moved_responsible'),
            value: 'moved_responsible_field',
            checked: true,
        },

        { label: getLocalizedText('view.action.name'), value: 'action', checked: true },
    ];

    listbox(table, listSource, '-1');
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
        has_condition: true,
        placing: {
            pallet: 'Пасік 2',
            warehouse: 'Склад 1',
            zone: 'Зона 1',
            cell: 'A01-01-01',
            code: 'K1234',
        },
        before_moving: 50,
        moved: 50,
        moved_responsible: {
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
        has_condition: false,

        placing: {
            pallet: 'Пасік 2',
            warehouse: 'Склад 1',
            zone: 'Зона 1',
            cell: 'A01-01-01',
            code: 'K1234',
        },
        before_moving: 40,
        moved: 50,
        moved_responsible: {
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
        has_condition: false,

        placing: {
            pallet: null,
            warehouse: null,
            zone: null,
            cell: null,
            code: null,
        },
        before_moving: 60,
        moved: null,
        moved_responsible: null,
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
        party: null,
        manufactured: null,
        expiry: null,
        package: 'Палета',
        has_condition: false,
        placing: {
            pallet: null,
            warehouse: null,
            zone: null,
            cell: null,
            code: null,
        },
        before_moving: 50,
        moved: null,
        moved_responsible: null,
    },
];
