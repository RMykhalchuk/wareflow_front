import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { getLocalizedText } from '../../localization/tasks/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';

$(document).ready(function () {
    let table = $('#tasks-table');
    let isRowHovered = false;
    let isTestMode = false;

    const STORAGE_KEY = 'global_warehouse_id';
    const storedWarehouse = localStorage.getItem(STORAGE_KEY);

    // 🔥 Отримуємо локаль і базовий префікс
    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    // 🔥 Якщо у Blade передали baseUrl — беремо його
    const baseUrlFromBlade = window.baseUrlTasks;

    // 🔥 Формуємо requestUrl з урахуванням складу зі storage
    const requestUrl = storedWarehouse
        ? `${baseUrlFromBlade}?warehouse_id=${storedWarehouse}`
        : baseUrlFromBlade;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'number' },
        // { name: 'kind', type: 'string' },
        { name: 'type_name', map: 'type>name', type: 'string' },
        { name: 'formation_type', type: 'string' },

        // status object
        { name: 'status_value', map: 'status_info>value', type: 'number' },
        { name: 'status_name', map: 'status_info>name', type: 'string' },
        { name: 'status_label', map: 'status_info>label', type: 'string' },

        { name: 'priority', type: 'string' },
        { name: 'started_at', type: 'string' },

        { name: 'progress_current', map: 'progress>current', type: 'number' },
        { name: 'progress_total', map: 'progress>total', type: 'number' },

        // { name: 'end_time', type: 'string' },
        { name: 'finished_at', type: 'string' },

        { name: 'executors', type: 'string' },
        // { name: 'executors_name', map: 'executors>name', type: 'string' },
        // { name: 'executors_surname', map: 'executors>surname', type: 'string' },
        // { name: 'executors_patronymic', map: 'executors>patronymic', type: 'string' },

        { name: 'created_at', type: 'string' },

        { name: 'document_id', map: 'document>id', type: 'string' },
        { name: 'document_local_id', map: 'document>local_id', type: 'string' },
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
            table.jqxGrid('sortby', 'local_id', 'desc');
        },
        sortable: true,
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
                dataField: 'local_id',
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
                    return `<a href="${window.location.origin + languageBlock}/tasks/${rowdata.id}" class="text-dark fw-bolder ps-50 my-auto text-decoration-underline">${value}</a>`;
                },
            },
            // {
            //     dataField: 'kind',
            //     text: getLocalizedText('kind'),
            //     minwidth: 250,
            //     cellsrenderer: (row, column, value) =>
            //         `<p class="ps-50 my-auto">${value ? value : '-'}</p>`,
            // },
            {
                dataField: 'type_field',
                text: getLocalizedText('type'),
                width: 250,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    let localizedName = rowdata.type_name ?? '-';
                    return `<p class="ps-50 my-auto">${localizedName}</p>`;
                },
            },
            {
                dataField: 'formation_type',
                text: getLocalizedText('type_of_task_formation'),
                minwidth: 250,
                cellsrenderer: (row, column, value) =>
                    `<p class="ps-50 my-auto">${value ? value : '-'}</p>`,
            },
            {
                dataField: 'status_field',
                align: 'left',
                text: getLocalizedText('status_id'),
                width: 150,
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
                        case 2:
                            badgeClass = 'bg-light-info';
                            break;
                        case 3:
                            badgeClass = 'bg-light-danger';
                            break;
                        default:
                            badgeClass = 'bg-dark';
                    }
                    return `<span class="badge ${badgeClass} text-white ms-50 py-50 px-1 text-capitalize">${rowdata.status_label || '-'}</span>`;
                },
            },
            {
                dataField: 'priority',
                text: getLocalizedText('priority'),
                width: 100,
                cellsrenderer: (row, column, value) => {
                    // Перетворюємо у число
                    const num = Number(value);

                    // Визначаємо колір залежно від групи
                    let color = '#6c757d'; // default (сірий)
                    if ([0, 1, 2].includes(num))
                        color = '#00cfe8'; // group1
                    else if ([3, 4, 5].includes(num))
                        color = '#28c76f'; // group2
                    else if ([6, 7, 8].includes(num))
                        color = '#ff9f43'; // group3
                    else if ([9, 10].includes(num)) color = '#ea5455'; // group4

                    // Якщо значення не вказано
                    const displayValue = value ?? '—';

                    return `
                            <div class="ps-50 my-auto">
                                <div class="badge rounded p-75" style="background-color: ${color};">
                                    ${displayValue}
                                </div>
                            </div>
                        `;
                },
            },
            {
                dataField: 'start_field',
                text: getLocalizedText('start'),
                width: 200,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    if (!rowdata.started_at) {
                        return `<div class="d-flex flex-column ps-50">
                                    <div><span class="text-muted">—</span></div>
                                    <div></div>
                                </div>`;
                    }

                    const date = new Date(rowdata.started_at);
                    const startDate = date.toLocaleDateString('uk-UA'); // або 'YYYY-MM-DD' якщо хочеш статично
                    const startTime = date.toLocaleTimeString('uk-UA', {
                        hour: '2-digit',
                        minute: '2-digit',
                    });

                    return `<div class="d-flex flex-column ps-50">
                                <div>${startDate}</div>
                                <div>${startTime}</div>
                            </div>`;
                },
            },
            {
                dataField: 'completion_field',
                text: getLocalizedText('completion'),
                width: 200,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    const status = rowdata.status_value; // 0 - created, 5 - in progress, 3 - done
                    const progress_total = rowdata.progress_total;
                    const progress_current = rowdata.progress_current;

                    // 1️⃣ Якщо ще не почато
                    if (status === 0 || !progress_total) {
                        return `<span class="text-muted">—</span>`;
                    }

                    // 2️⃣ Якщо в роботі
                    if (status === 5) {
                        // 5 - IN_PROGRESS, 2 - TO_DO можна додати при потребі
                        const percent =
                            progress_total > 0
                                ? Math.round((progress_current / progress_total) * 100)
                                : 0;
                        return `
                                <div class="d-flex flex-column w-100 align-items-start ps-50 gap-75">
                                    <div class="d-flex align-items-center w-75">
                                        <div class="progress w-75 me-50" style="height: 12px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                aria-valuenow="${percent}"
                                                aria-valuemin="0" aria-valuemax="100"
                                                style="width: ${percent}%"></div>
                                        </div>
                                        <span class="small w-25">${percent}%</span>
                                    </div>
                                    <div class="small">${progress_current}/${progress_total} ${getLocalizedText('unit')}</div>
                                </div>`;
                    }

                    // 3️⃣ Якщо завершено
                    if (status === 3) {
                        // DONE
                        const finishedAt = rowdata.finished_at
                            ? new Date(rowdata.finished_at)
                            : null;

                        const finishedDate = finishedAt
                            ? finishedAt.toLocaleDateString('uk-UA')
                            : '<span class="text-muted">—</span>';

                        const finishedTime = finishedAt
                            ? finishedAt.toLocaleTimeString('uk-UA', {
                                  hour: '2-digit',
                                  minute: '2-digit',
                              })
                            : '';
                        return `
                                <div class="d-flex flex-column ps-50">
                                    <div>${finishedDate}</div>
                                    <div>${finishedTime}</div>
                                </div>`;
                    }

                    // Якщо статус інший — пусто
                    return `<span class="text-muted">—</span>`;
                },
            },
            {
                dataField: 'executors_fields',
                text: getLocalizedText('executor'),
                width: 200,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    let executors = rowdata.executors;

                    if (Array.isArray(executors) && executors.length > 0) {
                        const names = executors
                            .map((ex) => {
                                const surname = ex.surname || '';
                                const initials = [
                                    ex.name ? ex.name.charAt(0).toUpperCase() : '',
                                    ex.patronymic ? ex.patronymic.charAt(0).toUpperCase() : '',
                                ]
                                    .filter(Boolean)
                                    .join('.');
                                return initials
                                    ? `${surname} ${initials}`
                                    : surname || ex.login || '';
                            })
                            .join(', ');

                        return `
                <div class="d-flex flex-column ps-50">
                    <span class="text-dark fw-bolder my-auto d-inline-block text-truncate"
style="max-width: calc(200px - 0.5rem);"
 title="${names}">${names}</span>
                </div>
            `;
                    }

                    // якщо пусто або null — показуємо "Усі"
                    return `
            <div class="d-flex flex-column ps-50">
                <span class="text-muted fw-bolder my-auto">Усі</span>
            </div>
        `;
                },
            },

            {
                dataField: 'created_field',
                text: getLocalizedText('created'),
                width: 200,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    // Форматуємо дату створення
                    const createdAt = rowdata.created_at ? new Date(rowdata.created_at) : null;

                    const createdDate = createdAt
                        ? createdAt.toLocaleDateString('uk-UA')
                        : '<span class="text-muted">—</span>';

                    const createdTime = createdAt
                        ? createdAt.toLocaleTimeString('uk-UA', {
                              hour: '2-digit',
                              minute: '2-digit',
                          })
                        : '';

                    const documentId = rowdata.document_id;
                    const documentLocalId = rowdata.document_local_id;

                    // Зроби правильний роут під себе
                    const documentUrl = `${window.location.origin + languageBlock}/document/${documentId}`;

                    return `
                            <div class="d-flex flex-column ps-50">
                                <a
                                    class="text-dark fw-bolder my-auto text-decoration-underline"
                                    href="${documentUrl}"
                                    title="Перейти до документа"
                                >
                                    ${getLocalizedText('document')} №${documentLocalId || '—'}
                                </a>
                                <div>${createdDate}</div>
                                <div>${createdTime}</div>
                            </div>
                        `;
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
                            return `<div id="${popoverId}">
                                        <ul class="popover-castom" style="list-style: none">
                                            <li><a class="dropdown-item" href="${window.location.origin + languageBlock}/tasks/${rowdata.id}">${getLocalizedText('btnActionView')}</a></li>
                                        </ul>
                                    </div>`;
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
        // { label: getLocalizedText('kind'), value: 'kind', checked: true },
        { label: getLocalizedText('type'), value: 'type_field', checked: true },
        {
            label: getLocalizedText('type_of_task_formation'),
            value: 'formation_type',
            checked: true,
        },
        { label: getLocalizedText('status_id'), value: 'status_field', checked: true },
        { label: getLocalizedText('priority'), value: 'priority', checked: true },
        { label: getLocalizedText('start'), value: 'start_field', checked: true },
        { label: getLocalizedText('completion'), value: 'completion_field', checked: true },
        { label: getLocalizedText('executor'), value: 'executors_fields', checked: true },
        { label: getLocalizedText('created'), value: 'created_field', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});

// ======= ТЕСТОВІ ДАНІ =======
let customData = [
    {
        id: '741d5593-d84b-47c7-abb5-189e681fd083',
        local_id: 5,
        processing_type: 'default',
        type_id: 1,
        kind: '',
        executors: [],
        status: 1,
        document_id: '99f5493f-fab7-47fe-98b6-e48e47c31d4a',
        priority: 0,
        comment: null,
        task_data: null,
        creator_company_id: 'b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad',
        created_at: '2025-12-15T12:10:01.000000Z',
        updated_at: '2025-12-15T12:10:01.000000Z',
        deleted_at: null,
        cell_id: '3c58d76c-bc16-410e-ac2f-9e61fce5b8ab',
        started_at: null,
        finished_at: null,
        formation_type: null,
        status_info: {
            value: 1,
            name: 'CREATED',
            label: 'Created',
        },
        type: {
            id: 1,
            key: 'income',
            name: '\u041d\u0430\u0434\u0445\u043e\u0434\u0436\u0435\u043d\u043d\u044f',
            is_system: true,
            creator_company_id: null,
            deleted_at: null,
            created_at: '2025-12-12T09:12:53.000000Z',
            updated_at: '2025-12-12T09:12:53.000000Z',
        },
        document: {
            id: '99f5493f-fab7-47fe-98b6-e48e47c31d4a',
            local_id: 5,
            status_id: 2,
            type_id: 'a6307a02-b1cc-4f89-aebe-3653b2c6c6ab',
            data: '{"header":{"1select_field_1":"\u041e\u043b\u0435\u043a\u0441\u0430\u043d\u0434\u0440 \u041c\u0438\u043a\u043e\u043b\u0430\u0454\u043d\u043a\u043e","2select_field_2":"A01-01-02-02","3text_field_3":"\u0441\u043a\u0430\u0441\u0443\u0432\u0430\u0442\u0438 \u0437\u0430\u0432\u0434\u0430\u043d\u043d\u044f"},"header_ids":{"1select_field_1_id":"1112e3fb-5188-4c3e-9ede-2df58d1ad5a5","2select_field_2_id":"3c58d76c-bc16-410e-ac2f-9e61fce5b8ab"},"custom_blocks":{},"sku_table":[{"id":"0c28a0ec-183d-4b2f-be30-ded67f8c54d7","local_id":3,"name":"\u041f\u043e\u043b\u0438\u0447\u043a\u0438","barcode":3,"brand":"b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad","supplier":"b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad","manufacturer":"b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad","country":"\u0423\u043a\u0440\u0430\u0457\u043d\u0430","unit":"\u0448\u0442","country_id":1,"unit_id":1,"quantity":"1","position":1,"uid":0,"boundindex":0,"uniqueid":"2918-24-25-27-162717","visibleindex":0}],"activity_state":"tasks"}',
            is_active: true,
            created_at: '2025-12-15T12:09:50.000000Z',
            updated_at: '2025-12-15T12:10:01.000000Z',
            deleted_at: null,
            additional_properties: null,
            is_reserved: false,
            creator_company_id: 'b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad',
            warehouse_id: '46e2a526-2d4b-4482-89ba-4340c5acbf2a',
            state: null,
            created_by_system: false,
        },
        end_time: ' - ',
        name: null,
        progress: {
            total: 1,
            current: 0,
        },
    },
    {
        id: 'b3960dc7-0536-460f-8e72-06fe2ec33e93',
        local_id: 10,
        processing_type: 'default',
        type_id: 1,
        kind: '',
        executors: [
            {
                id: '461197d1-6d3a-4685-bd60-de87ce4cb5cd',
                name: "\u0406\u043c'\u044f",
                surname: '\u041f\u0440\u0456\u0437\u0432\u0438\u0449\u0435',
                patronymic: '\u041f\u043e \u0431\u0430\u0442\u044c\u043a\u043e\u0432\u0456',
            },
        ],
        status: 2,
        document_id: 'acb15ec4-17eb-4c2e-84b4-0f73faf4b18c',
        priority: 3,
        comment: null,
        task_data: null,
        creator_company_id: 'b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad',
        created_at: '2025-12-16T13:20:07.000000Z',
        updated_at: '2025-12-16T13:20:44.000000Z',
        deleted_at: null,
        cell_id: '97578ac5-2633-4bb0-9793-39069e1cd195',
        started_at: null,
        finished_at: null,
        formation_type: null,
        status_info: {
            value: 2,
            name: 'TO_DO',
            label: 'To do',
        },
        type: {
            id: 1,
            key: 'income',
            name: '\u041d\u0430\u0434\u0445\u043e\u0434\u0436\u0435\u043d\u043d\u044f',
            is_system: true,
            creator_company_id: null,
            deleted_at: null,
            created_at: '2025-12-12T09:12:53.000000Z',
            updated_at: '2025-12-12T09:12:53.000000Z',
        },
        document: {
            id: 'acb15ec4-17eb-4c2e-84b4-0f73faf4b18c',
            local_id: 14,
            status_id: 2,
            type_id: 'a6307a02-b1cc-4f89-aebe-3653b2c6c6ab',
            data: '{"header":{"1select_field_1":"\u0421\u043a\u043b\u0430\u0434 \u0422\u0426","2select_field_2":"A01-01-01-01","3text_field_3":"\u0412 \u043a\u043e\u043d\u0442\u0435\u0439\u043d\u0435\u0440"},"header_ids":{"1select_field_1_id":"c12c8d35-0cc2-47e3-83c6-1c446c625a1e","2select_field_2_id":"97578ac5-2633-4bb0-9793-39069e1cd195"},"custom_blocks":{},"sku_table":[{"id":"e1f69440-3a2e-47f2-825b-4b78e6e2e641","local_id":2,"name":"\u041c\u0443\u043a\u0430","barcode":2,"brand":"b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad","supplier":"b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad","manufacturer":"b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad","country":"\u0423\u043a\u0440\u0430\u0457\u043d\u0430","unit":"\u043a\u0433","country_id":1,"unit_id":2,"quantity":"10","position":1,"uid":0,"boundindex":0,"uniqueid":"2521-22-17-27-232826","visibleindex":0},{"id":"ba30da4b-d4e5-4b33-b5f4-f05628f16a6a","local_id":1,"name":"\u0411\u0443\u043b\u043a\u0430","barcode":1,"brand":"b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad","supplier":"b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad","manufacturer":"b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad","country":"\u0423\u043a\u0440\u0430\u0457\u043d\u0430","unit":"\u0448\u0442","country_id":1,"unit_id":1,"quantity":"10","position":2,"uid":1,"boundindex":1,"uniqueid":"1622-28-16-30-172129","visibleindex":1}],"activity_state":"tasks"}',
            is_active: true,
            created_at: '2025-12-16T13:19:57.000000Z',
            updated_at: '2025-12-16T13:20:07.000000Z',
            deleted_at: null,
            additional_properties: null,
            is_reserved: false,
            creator_company_id: 'b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad',
            warehouse_id: '46e2a526-2d4b-4482-89ba-4340c5acbf2a',
            state: null,
            created_by_system: false,
        },
        end_time: ' - ',
        progress: {
            total: 2,
            current: 0,
        },
        name: null,
    },
    {
        id: 'bee58a65-0efc-40ed-9be9-3920c1caafa1',
        local_id: 20,
        processing_type: null,
        type_id: 1,
        kind: 'on_arrival',
        executors: [
            {
                id: '33decb15-eed9-40dd-924c-27748c154865',
                name: '\u042e\u043b\u0456\u044f',
                surname: '\u041c\u043e\u0439\u0441\u044e\u043a',
                patronymic: '\u041c\u0438\u0445\u0430\u0439\u043b\u0456\u0432\u043d\u0430',
            },
        ],
        status: 5,
        document_id: '33158844-e89b-4600-9760-7d9424dcf1ec',
        priority: 1,
        comment: null,
        task_data: null,
        creator_company_id: 'b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad',
        created_at: '2026-01-23T13:55:03.000000Z',
        updated_at: '2026-01-23T13:55:03.000000Z',
        deleted_at: null,
        cell_id: 'f3179b37-a856-4ecf-b0dc-19128d035b09',
        started_at: '2026-01-23 15:55:03',
        finished_at: null,
        formation_type: 2,
        status_info: {
            value: 5,
            name: 'IN_PROGRESS',
            label: 'In progress',
        },
        type: {
            id: 1,
            key: 'income',
            name: '\u041d\u0430\u0434\u0445\u043e\u0434\u0436\u0435\u043d\u043d\u044f',
            is_system: true,
            creator_company_id: null,
            deleted_at: null,
            created_at: '2025-12-12T09:12:53.000000Z',
            updated_at: '2025-12-12T09:12:53.000000Z',
        },
        document: {
            id: '33158844-e89b-4600-9760-7d9424dcf1ec',
            local_id: 39,
            status_id: 2,
            type_id: 'a6307a02-b1cc-4f89-aebe-3653b2c6c6ab',
            data: '{"header":{"1select_field_1":"Company Name","2select_field_2":"A02-01-02-01","3text_field_3":"Document from terminal"},"header_ids":{"1select_field_1_id":"e639dfb5-e085-44ee-a4f1-9caa0a7afac9","2select_field_2_id":"f3179b37-a856-4ecf-b0dc-19128d035b09"},"custom_blocks":[],"sku_table":[{"id":"58250c97-925b-42cb-9374-54f5d8faa0a6","local_id":1,"quantity":100,"position":1,"uid":0,"boundindex":0,"uniqueid":"69737db7bbb10","visibleindex":0}],"activity_state":"tasks"}',
            is_active: true,
            created_at: '2026-01-23T13:55:03.000000Z',
            updated_at: '2026-01-23T13:55:03.000000Z',
            deleted_at: null,
            additional_properties: null,
            is_reserved: false,
            creator_company_id: 'b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad',
            warehouse_id: '46e2a526-2d4b-4482-89ba-4340c5acbf2a',
            state: null,
            created_by_system: true,
        },
        end_time: ' - ',
        name: null,
        progress: {
            total: 1,
            current: 0,
        },
    },
    {
        id: '84ece6f2-759e-441d-9d17-b7923c7c8cc2',
        local_id: 2,
        processing_type: 'default',
        type_id: 1,
        kind: '',
        executors: [],
        status: 3,
        document_id: '51aca9f3-f3c4-47cf-a5ad-ef55aabbc2f2',
        priority: 0,
        comment: null,
        task_data: null,
        creator_company_id: 'b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad',
        created_at: '2025-12-15T11:31:16.000000Z',
        updated_at: '2025-12-16T13:18:02.000000Z',
        deleted_at: null,
        cell_id: '38dcc999-e87f-4c2d-8500-dd55bd41a799',
        started_at: null,
        finished_at: null,
        formation_type: null,
        status_info: {
            value: 3,
            name: 'DONE',
            label: 'Done',
        },
        type: {
            id: 1,
            key: 'income',
            name: '\u041d\u0430\u0434\u0445\u043e\u0434\u0436\u0435\u043d\u043d\u044f',
            is_system: true,
            creator_company_id: null,
            deleted_at: null,
            created_at: '2025-12-12T09:12:53.000000Z',
            updated_at: '2025-12-12T09:12:53.000000Z',
        },
        document: {
            id: '51aca9f3-f3c4-47cf-a5ad-ef55aabbc2f2',
            local_id: 2,
            status_id: 3,
            type_id: 'a6307a02-b1cc-4f89-aebe-3653b2c6c6ab',
            data: '{"header":{"1select_field_1":"\\u0422\\u0426 \\u041c\\u0443\\u0445\\u0430","2select_field_2":"A01-01-02-01","3text_field_3":"\\u0422\\u0435\\u0441\\u0442"},"header_ids":{"1select_field_1_id":"b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad","2select_field_2_id":"38dcc999-e87f-4c2d-8500-dd55bd41a799"},"custom_blocks":[],"sku_table":[{"id":"ba30da4b-d4e5-4b33-b5f4-f05628f16a6a","local_id":1,"name":"\\u0411\\u0443\\u043b\\u043a\\u0430","barcode":1,"brand":"b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad","supplier":"b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad","manufacturer":"b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad","country":"\\u0423\\u043a\\u0440\\u0430\\u0457\\u043d\\u0430","unit":"\\u0448\\u0442","country_id":1,"unit_id":1,"quantity":"20","position":1,"uid":0,"boundindex":0,"uniqueid":"2516-17-16-29-252928","visibleindex":0,"processed":true},{"id":"ba30da4b-d4e5-4b33-b5f4-f05628f16a6a","local_id":1,"name":"\\u0411\\u0443\\u043b\\u043a\\u0430","barcode":1,"brand":"b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad","supplier":"b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad","manufacturer":"b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad","country":"\\u0423\\u043a\\u0440\\u0430\\u0457\\u043d\\u0430","unit":"\\u0448\\u0442","country_id":1,"unit_id":1,"quantity":"10","position":2,"uid":1,"boundindex":1,"uniqueid":"2327-25-16-27-172825","visibleindex":1}],"activity_state":"tasks"}',
            is_active: true,
            created_at: '2025-12-15T11:29:16.000000Z',
            updated_at: '2025-12-16T13:18:02.000000Z',
            deleted_at: null,
            additional_properties: null,
            is_reserved: false,
            creator_company_id: 'b1f9a8c4-b0a8-43e6-86ee-19cf3845d5ad',
            warehouse_id: '46e2a526-2d4b-4482-89ba-4340c5acbf2a',
            state: null,
            created_by_system: false,
        },
        end_time: '2025-12-15T11:31:16.000000Z',
        name: null,
        progress: {
            total: 2,
            current: 1,
        },
    },
];
