import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { getLocalizedText } from '../../localization/inventory/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';

$(document).ready(function () {
    let table = $('#inventory-table');
    let isRowHovered = false;
    let isTestMode = false;
    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'real_id', type: 'string' },
        { name: 'type', type: 'string' },
        { name: 'kind', type: 'string' },
        { name: 'status_id', type: 'string' },

        // доступ до вкладених
        { name: 'start_date', map: 'start>date', type: 'string' },
        { name: 'start_time', map: 'start>time', type: 'string' },

        { name: 'completion_type', map: 'completion>type', type: 'string' },
        { name: 'completion_percent', map: 'completion>percent', type: 'number' },
        { name: 'completion_currentKg', map: 'completion>currentKg', type: 'number' },
        { name: 'completion_maxKg', map: 'completion>maxKg', type: 'number' },
        { name: 'completion_date', map: 'completion>date', type: 'string' },
        { name: 'completion_time', map: 'completion>time', type: 'string' },

        { name: 'executor', type: 'string' },

        { name: 'created_name', map: 'created>name', type: 'string' },
        { name: 'created_date', map: 'created>date', type: 'string' },
        { name: 'created_time', map: 'created>time', type: 'string' },
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
              url: window.location.origin + languageBlock + '/inventory/table/filter',
              root: 'data',
              data: {
                  lang: language || 'uk',
              },
              beforeprocessing: function (data) {
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
        selectionMode: 'checkbox',
        enablehover: false,
        columnsreorder: false,
        autoshowfiltericon: true,
        pagermode: 'advanced',
        rowsheight: 100,
        filterbarmode: 'simple',
        toolbarHeight: 55,
        showToolbar: true,

        rendertoolbar: function (statusbar) {
            var columns = table.jqxGrid('columns').records;
            var columnCount = columns.length;
            return toolbarRender(statusbar, table, false, 1, columnCount - 1);
        },

        columns: [
            {
                dataField: 'id',
                text: getLocalizedText('id'),
                width: 60,
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const realId = rowdata.real_id ?? value;

                    return `<a href="${window.location.origin + languageBlock}/inventory/${realId}" class="text-dark fw-bolder ps-50 my-auto text-decoration-underline">${value}</a>`;
                },
            },
            {
                dataField: 'type',
                text: getLocalizedText('type'),
                minwidth: 120,
                cellsrenderer: (row, column, value) => {
                    let displayValue = value;

                    if (value === 'full') {
                        displayValue = getLocalizedText('full');
                    } else if (value === 'partly') {
                        displayValue = getLocalizedText('partly');
                    }

                    return `<p class="ps-50 my-auto">${displayValue}</p>`;
                },
            },
            {
                dataField: 'kind',
                text: getLocalizedText('kind'),
                width: 200,
                cellsrenderer: (row, column, value) =>
                    `<p class="ps-50 my-auto">${value ? value : '-'}</p>`,
            },
            {
                dataField: 'status_id',
                text: getLocalizedText('status_id'),
                width: 300,
                cellsrenderer: (row, column, value) => {
                    const status = statusMap[value] || {
                        class: 'bg-light text-dark',
                        text: getLocalizedText('status_unknown'),
                    };
                    return `<span class="badge ${status.class} text-white ms-50 py-50 px-1">${status.text}</span>`;
                },
            },
            {
                dataField: 'start_field',
                text: getLocalizedText('start'),
                width: 200,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    return `<div class="d-flex flex-column ps-50">
            <div>${rowdata.start_date || '<span class="text-muted">—</span>'}</div>
            <div>${rowdata.start_time || ''}</div>
        </div>`;
                },
            },
            {
                dataField: 'completion_field',
                text: getLocalizedText('completion'),
                width: 200,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    const type = rowdata.completion_type;

                    if (!type) return `<span class="text-muted">—</span>`;

                    if (type === 'progress') {
                        return `
            <div class="d-flex flex-column w-100 align-items-start ps-50 gap-75">
                <div class="d-flex align-items-center w-75">
                    <div class="progress w-75 me-50" style="height: 12px;">
                        <div class="progress-bar bg-success" role="progressbar"
                            aria-valuenow="${rowdata.completion_percent}"
                            aria-valuemin="0" aria-valuemax="100"
                            style="width: ${rowdata.completion_percent}%"></div>
                    </div>
                    <span class="small w-25">${rowdata.completion_percent}%</span>
                </div>
                <div class="d-flex align-items-center w-75">
                    <div class="progress w-100 me-50" style="height: 12px;">
                        <div class="progress-bar bg-warning" role="progressbar"
                            aria-valuenow="${(rowdata.completion_currentKg / rowdata.completion_maxKg) * 100}"
                            aria-valuemin="0" aria-valuemax="100"
                            style="width: ${(rowdata.completion_currentKg / rowdata.completion_maxKg) * 100}%"></div>
                    </div>
                    <span class="small w-25">${rowdata.completion_currentKg}/${rowdata.completion_maxKg} ${getLocalizedText('bootUnit')}</span>
                </div>
            </div>`;
                    }

                    if (type === 'done') {
                        return `<div class="d-flex flex-column ps-50">
                <div>${rowdata.completion_date}</div>
                <div>${rowdata.completion_time}</div>
            </div>`;
                    }

                    return `<span class="text-muted">—</span>`;
                },
            },
            {
                dataField: 'executor',
                text: getLocalizedText('executor'),
                width: 200,
                cellsrenderer: function (row, column, value, rowData) {
                    if (!value) {
                        return `<div>-</div>`;
                    }

                    // нормалізуємо роздільники (\r\n, \n, \r, або навіть ";")
                    const executors = value.split(/\r\n|\n|\r|;/);

                    // відфільтруємо пусті
                    const divs = executors
                        .filter((executor) => executor.trim() !== '')
                        .map(
                            (executor) =>
                                `<div class="ps-50 text-dark fw-bolder my-auto">${executor.trim()}</div>`
                        )
                        .join('');

                    return `<div class="d-flex flex-column align-items-start">${divs}</div>`;
                },
            },
            {
                dataField: 'created_field',
                text: getLocalizedText('created'),
                width: 200,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    return `<div class="d-flex flex-column ps-50">
                                <a class="text-dark fw-bolder my-auto" href='#'>${rowdata.created_name}</a>
                                <div>${rowdata.created_date}</div>
                                <div>${rowdata.created_time}</div>
                            </div>`;
                },
            },
            {
                dataField: 'action',
                width: '70px',
                align: 'center',
                cellsalign: 'center',
                renderer: function () {
                    return '<div></div>';
                },
                filterable: false,
                sortable: false,
                id: 'action',
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
                            var viewOption = `<li><a class="dropdown-item" href="${window.location.origin + languageBlock}/inventory/${rowdata.real_id}">${getLocalizedText('btnActionView')}</a></li>`;
                            var deleteOpiton = '';
                            var editOption = '';

                            if (rowdata.status_id == 1) {
                                deleteOpiton = `<li><a class="dropdown-item" href="${window.location.origin + languageBlock}/inventory/${rowdata.real_id}/delete">${getLocalizedText('btnActionDelete')}</a></li>`;
                                editOption = `<li><a class="dropdown-item" href="${window.location.origin + languageBlock}/inventory/${rowdata.real_id}/edit">${getLocalizedText('btnActionEdit')}</a></li>`;
                            }

                            var optionBlock = `<div id="${popoverId}">
                                        <ul class="popover-castom" style="list-style: none">
                                           ${viewOption}
                                           ${deleteOpiton}
                                           ${editOption}
                                        </ul>
                                    </div>`;

                            return optionBlock;
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
        { label: getLocalizedText('id'), value: 'id', checked: true },
        { label: getLocalizedText('type'), value: 'type', checked: true },
        { label: getLocalizedText('kind'), value: 'kind', checked: true },
        { label: getLocalizedText('status_id'), value: 'status_id', checked: true },
        { label: getLocalizedText('start'), value: 'start_field', checked: true },
        { label: getLocalizedText('completion'), value: 'completion_field', checked: true },
        { label: getLocalizedText('executor'), value: 'executor', checked: true },
        { label: getLocalizedText('created'), value: 'created_field', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});

const statusMap = {
    1: {
        class: 'bg-secondary',
        text: getLocalizedText('status_created'),
    },
    8: {
        class: 'bg-warning',
        text: getLocalizedText('status_pending'),
    },
    2: {
        class: 'bg-primary',
        text: getLocalizedText('status_in_progress'),
    },
    3: {
        class: 'bg-danger',
        text: getLocalizedText('status_paused'),
    },
    4: {
        class: 'bg-dark',
        text: getLocalizedText('status_finished'),
    },
    6: {
        class: 'bg-info',
        text: getLocalizedText('finished_before'),
    },
    7: {
        class: 'bg-warning',
        text: getLocalizedText('status_cancelled'),
    },
};

let customData = [
    {
        id: '1',
        type: 'Повна',
        kind: 'Планова',
        status_id: 'planned',
        start: { date: '2025.08.05', time: '12:00' },
        completion: null,
        executor: 'Ігнатенко В.І',
        created: { name: 'Ігнатенко В.І', date: '2025.08.05', time: '12:00' },
    },
    {
        id: '2',
        type: 'Повна',
        kind: 'Планова',
        status_id: 'rescan',
        start: { date: '2025.08.05', time: '12:00' },
        completion: { type: 'progress', percent: 50, currentKg: 90, maxKg: 120 },
        executor: 'Ігнатенко В.І',
        created: { name: 'Ігнатенко В.І', date: '2025.08.05', time: '12:00' },
    },
    {
        id: '5',
        type: 'Повна',
        kind: 'Планова',
        status_id: 'completed',
        start: { date: '2025.08.05', time: '12:00' },
        completion: { type: 'done', date: '2025.09.05', time: '12:00' },
        executor: 'Ігнатенко В.І',
        created: { name: 'Ігнатенко В.І', date: '2025.08.05', time: '12:00' },
    },
];
