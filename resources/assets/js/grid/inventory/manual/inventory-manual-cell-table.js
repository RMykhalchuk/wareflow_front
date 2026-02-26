import { pagerRenderer } from '../../components/pager.js';
import { toolbarRender } from '../../components/toolbar-advanced.js';
import { listbox } from '../../components/listbox.js';
import { getLocalizedText } from '../../../localization/inventory/getLocalizedText.js';
import { switchLang } from '../../components/switch-lang.js';
import { hideLoader, showLoader } from '../../components/loader.js';
import { toggleTableVisibility } from '../../document/arrival/preview-document-sku-table.js';
import { hover } from '../../components/hover.js';

export function initVenueAnimalGrid(cellId, isTestModeParam = false) {
    let table = $('#inventory-manual-cell-table');
    let isTestMode = isTestModeParam; // <<< Перемикач тестового режиму
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    const requestUrl = `${location.origin + languageBlock}/inventory/manual/group/${encodeURIComponent(cellId)}/leftovers`;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },

        // name
        { name: 'name_title', map: 'goods_name', type: 'string' },
        { name: 'name_barcode', map: 'barcode', type: 'string' },
        { name: 'name_manufacturer', map: 'manufactured', type: 'string' },
        { name: 'name_category', map: 'name>category', type: 'string' },
        { name: 'name_brand', map: 'name>brand', type: 'string' },

        { name: 'batch', map: 'party', type: 'string' },
        { name: 'created_date', type: 'string' },
        { name: 'expiry', map: 'expires_at', type: 'string' },
        { name: 'package', map: 'package>name', type: 'string' },
        { name: 'condition', type: 'string' },

        { name: 'container_name', map: 'container>code', type: 'string' },
        { name: 'container_id', map: 'container>id', type: 'string' },

        { name: 'current_leftovers', map: 'qty', type: 'number' },
        { name: 'leftovers_erp', type: 'number' },
        { name: 'divergence', type: 'string' },

        // responsible
        { name: 'responsible_name', map: 'responsible_name', type: 'string' },
        { name: 'responsible_date', map: 'responsible_date', type: 'string' },
        { name: 'responsible_time', map: 'responsible_time', type: 'string' },

        { name: 'real', type: 'bool' },
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
              url: `${requestUrl}?_ts=${Date.now()}`, // CHANGED: cache-buster
              root: 'leftovers', // CHANGED: correct root
              beforeprocessing: function (data) {
                  console.log(data);
                  myDataSource.totalrecords =
                      data && Array.isArray(data.leftovers) ? data.leftovers.length : 0; // CHANGED
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
        loadComplete: function (data) {
            hideLoader();
            const rows =
                data && Array.isArray(data.leftovers) ? data.leftovers : data?.data || data || []; // CHANGED
            toggleTableVisibility(table, rows);
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
        rowsheight: 150,
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
            var columns = table.jqxGrid('columns')?.records || [];
            var columnCount = columns.length > 0 ? columns.length : 0;

            return toolbarRender(
                statusbar,
                table,
                false,
                1,
                Math.max(columnCount - 1, 0),
                '-manual-cell'
            );
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
                    <span>${rowdata.name_manufacturer ?? '-'}</span>
                    <span>${rowdata.name_category ?? '-'}</span>
                    <span>${rowdata.name_brand ?? '-'}</span>
                </div>`;
                },
            },
            {
                text: getLocalizedText('viewAnimal.party'),
                dataField: 'batch',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('viewAnimal.manufactured'),
                dataField: 'name_manufacturer',
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
                dataField: 'container_field',
                text: getLocalizedText('viewAnimal.container'),
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
            <div class="text-dark fw-bolder my-auto">${place.container_name || '-'}</div>
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
                width: 150,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    console.log(rowdata);
                    if (!rowdata.responsible_name) {
                        return `<div class="text-dark ps-50">-</div>`;
                    }

                    return `<div class="d-flex flex-column ps-50">
                    <a class="text-dark fw-bolder my-auto" href='#'>${rowdata.responsible_name}</a>
                    <div>${rowdata.responsible_date}</div>
                    <div>${rowdata.responsible_time}</div>
                </div>`;
                },
            },
        ],
    });

    let listSource = [
        { label: getLocalizedText('viewAnimal.id'), value: 'local_id', checked: true },
        { label: getLocalizedText('viewAnimal.name'), value: 'name_field', checked: true },
        { label: getLocalizedText('viewAnimal.party'), value: 'batch', checked: true },
        {
            label: getLocalizedText('viewAnimal.manufactured'),
            value: 'created_date',
            checked: true,
        },
        { label: getLocalizedText('viewAnimal.expiry'), value: 'expiry', checked: true },
        { label: getLocalizedText('viewAnimal.unit'), value: 'unit', checked: true },
        {
            label: getLocalizedText('viewAnimal.container'),
            value: 'container_field',
            checked: true,
        },
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

    listbox(table, listSource, '-manual-cell');
    hover(table, isRowHovered);
}

let customData = [];
