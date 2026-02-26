import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { getLocalizedText } from '../../localization/leftovers/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';
import { hover } from '../components/hover.js';
import { toggleTableVisibility } from '../document/arrival/preview-document-sku-table.js';

export function initTaskTerminalLeftoversGrid(skuId, isTestModeParam = false) {
    let table = $('#leftovers-by-sku-table');
    let isRowHovered = false;
    let isTestMode = isTestModeParam; // <<< Перемикач тестового режиму

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;
    const taskId = $('#task-container').data('id');

    const requestUrl = `${location.origin + languageBlock}/tasks/item/${taskId}/${skuId}/table/filter`;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },

        { name: 'batch', type: 'string' },

        { name: 'manufacture_date', type: 'string' },
        { name: 'bb_date', type: 'string' },
        { name: 'package', type: 'string' },
        { name: 'has_condition', type: 'bool' },

        { name: 'container', type: 'string' },

        // quantity object
        {
            name: 'quantity_measurement_unit_quantity',
            map: 'quantity>measurement_unit_quantity',
            type: 'number',
        },
        { name: 'quantity_measurement_unit', map: 'quantity>measurement_unit', type: 'string' },
        { name: 'quantity_package_quantity', map: 'quantity>package_quantity', type: 'number' },
        { name: 'quantity_package', map: 'quantity>package', type: 'string' },

        // responsible
        { name: 'responsible_name', map: 'user>name', type: 'string' },
        { name: 'responsible_patronymic', map: 'user>patronymic', type: 'string' },
        { name: 'responsible_surname', map: 'user>surname', type: 'string' },

        { name: 'processed_at', type: 'string' },
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
        loadComplete: function (data) {
            hideLoader();
            toggleTableVisibility(table, data?.data || data || []);
        },
        loadError: function (xhr, status, error) {
            hideLoader();
            toggleTableVisibility(table, []);
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
            var columns = table.jqxGrid('columns')?.records || [];
            var columnCount = columns.length > 0 ? columns.length : 0;

            return toolbarRender(statusbar, table, false, 1, Math.max(columnCount - 1, 0), '-2');
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
                text: getLocalizedText('party'),
                dataField: 'batch',
                minwidth: 100,
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
                dataField: 'container',
                text: getLocalizedText('container'),
                width: 200,
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
            <div class="text-dark fw-bolder my-auto">${place.container || '-'}</div>
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
                dataField: 'responsible_field',
                text: getLocalizedText('accepted'),
                width: 150,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    if (
                        !rowdata.responsible_name &&
                        !rowdata.responsible_surname &&
                        !rowdata.responsible_patronymic
                    ) {
                        return `<div class="text-dark ps-50">-</div>`;
                    }

                    const fullName =
                        `${rowdata.responsible_surname ?? ''} ${rowdata.responsible_name ? rowdata.responsible_name[0] + '.' : ''}${rowdata.responsible_patronymic ? rowdata.responsible_patronymic[0] + '.' : ''}`.trim();

                    let date = '';
                    let time = '';
                    if (rowdata.processed_at) {
                        const dateObj = new Date(rowdata.processed_at);
                        date = dateObj.toLocaleDateString('uk-UA');
                        time = dateObj.toLocaleTimeString('uk-UA', {
                            hour: '2-digit',
                            minute: '2-digit',
                        });
                    }

                    return `<div class="d-flex flex-column ps-50">
                                <a class="text-dark fw-bolder my-auto" href="#">${fullName || '-'}</a>
                                <div>${date}</div>
                                <div>${time}</div>
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
                hidden: true,
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
                        container: '.modal', // <<< ось тут важливо
                        content: function () {
                            return `
                                    <div id="${popoverId}">
                                        <ul class="popover-castom" class="list-unstyled">
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_leftovers">
                                                    Редагувати
                                                </a>

                                            </li>
                                             <li>
                                                <a class="dropdown-item" href="#">
                                                    Видалити
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
        { label: getLocalizedText('party'), value: 'batch', checked: true },
        { label: getLocalizedText('manufactured'), value: 'manufacture_date', checked: true },
        { label: getLocalizedText('expiry'), value: 'bb_date', checked: true },
        { label: getLocalizedText('package'), value: 'package', checked: true },
        { label: getLocalizedText('condition'), value: 'has_condition', checked: true },
        { label: getLocalizedText('container'), value: 'container', checked: true },
        { label: getLocalizedText('quantity'), value: 'quantity_field', checked: true },
        { label: getLocalizedText('accepted'), value: 'responsible_field', checked: true },
        { label: getLocalizedText('action'), value: 'action', checked: true },
    ];

    listbox(table, listSource, '-2');
    hover(table, isRowHovered);
}

let customData = [];
