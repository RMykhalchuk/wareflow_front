import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { getLocalizedText } from '../../localization/container-register/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';
import { sendRequestBase } from '../../utils/sendRequestBase.js';

$(document).ready(function () {
    let table = $('#container-register-table');
    let isRowHovered = false;
    let isTestMode = false; // <<< Перемикач тестового режиму

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;
    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },
        { name: 'code', type: 'string' },

        // status object
        { name: 'status_value', map: 'status>value', type: 'number' },
        { name: 'status_label', map: 'status>label', type: 'string' },

        { name: 'type', type: 'string' },
        { name: 'location', type: 'string' },
        // замість 'object' розкладаємо поля cell
        { name: 'cell_id', map: 'cell>cell>id', type: 'string' },
        { name: 'cell_code', map: 'cell>cell>code', type: 'string' },
        { name: 'cell_max_weight', map: 'cell>cell>max_weight', type: 'number' },
        { name: 'weight', type: 'string' },
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
              url: window.location.origin + languageBlock + '/container-register/table/filter',
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
        source: dataAdapter,
        showdefaultloadelement: false,
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

            // const menuData = [
            //     {
            //         label: 'Параметри залишків',
            //         submenu: [
            //             { label: 'Термін придатності', value: 'company' },
            //             { label: 'Дата виготовлення', value: 'manufacture_date' },
            //         ],
            //     },
            //     {
            //         label: 'Параметри товару',
            //         submenu: [
            //             { label: 'Вжити до', value: 'use_by' },
            //             { label: 'Партія', value: 'batch' },
            //         ],
            //     },
            //     {
            //         label: 'Параметри розміщення',
            //         submenu: [
            //             { label: 'Компанія', value: 'company' },
            //         ],
            //     },
            // ];

            return toolbarRender(statusbar, table, false, 1, columnCount - 1); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                dataField: 'local_id',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('id'),
                width: 100,
                editable: false,
                cellsrenderer: function (row, column, value, rowdata) {
                    return `<p style="" class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                minwidth: 250,
                dataField: 'code',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('code'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<a href="${window.location.origin + languageBlock}/container-register/${rowdata.id}" class="text-decoration-underline text-dark fw-bolder ps-50 my-auto">${value}</a>`;
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
                    let badgeClass;

                    switch (rowdata.status_value) {
                        case 0:
                            badgeClass = 'bg-light-danger';
                            break;
                        case 1:
                            badgeClass = 'bg-light-success';
                            break;
                        default:
                            badgeClass = 'bg-light-secondary';
                    }

                    return `<span class="badge ${badgeClass} text-white ms-50 py-50 px-1 text-capitalize">${rowdata.status_label || '-'}</span>`;
                },
            },

            {
                width: 250,
                dataField: 'type',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('type'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<p class="ps-50  my-auto""> ${value} </p>`;
                },
            },
            {
                dataField: 'location',
                text: getLocalizedText('placing'),
                width: 250,
                cellsrenderer: (row, columnfield, value) => {
                    if (!value) {
                        return `<div class="text-muted ps-50">${getLocalizedText('placeholder')}</div>`;
                    }

                    try {
                        // тут value вже = "Test Location - test wh - zona 1 - 1233123"
                        let parts = value.split(' - ');

                        return `
                                    <div class="d-flex flex-column ps-50">
                                        <a class="text-dark fw-bolder my-auto" href="#">${parts[0] ?? '-'}</a>
                                        <div class="text-dark fw-bolder my-auto">${parts[1] ?? '-'}</div>
                                        <div>${parts[2] ?? '-'}</div>
                                        <div>${parts[3] ?? '-'}</div>
                                    </div>
                                `;
                    } catch (e) {
                        return `<div class="text-muted ps-50">${getLocalizedText('placeholder')}</div>`;
                    }
                },
            },
            {
                dataField: 'boot',
                text: getLocalizedText('boot'),
                width: 250,
                cellsrenderer: (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) => {
                    // Можна зробити, щоб не показувало прогресбар, якщо нема weight
                    if (!rowdata.weight) {
                        return `<div class="text-muted ps-50">${getLocalizedText('placeholder')}</div>`;
                    }

                    const maxKg = rowdata.cell_max_weight ?? 0;
                    const currentKg = rowdata.weight ?? 0;
                    const percent = maxKg ? Math.round((currentKg / maxKg) * 100) : 0;

                    return `
                            <div class="d-flex flex-column w-100 align-items-start ps-50 gap-75">
                                <div class="d-flex align-items-center w-75">
                                    <div class="progress w-75 me-50" style="height: 12px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            aria-valuenow="${percent}"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                            style="width: ${percent}%"></div>
                                    </div>
                                    <span class="small w-25">${percent}%</span>
                                </div>
                                <div class="d-flex align-items-center w-75">
                                    <div class="progress w-100 me-50" style="height: 12px;">
                                        <div class="progress-bar bg-warning" role="progressbar"
                                            aria-valuenow="${currentKg}"
                                            aria-valuemin="0"
                                            aria-valuemax="${maxKg}"
                                            style="width: ${percent}%"></div>
                                    </div>
                                    <span class="small w-25">${currentKg}/${maxKg} ${getLocalizedText('bootUnit')}</span>
                                </div>
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
                    const buttonId = 'button-' + rowdata.uid;
                    const popoverId = 'popover-' + rowdata.uid;
                    const languageBlock = switchLang() === 'en' ? '' : '/' + switchLang();
                    const url = switchLang() === 'en' ? '' : `${switchLang()}/`;

                    const button = `
                                            <button id="${buttonId}" style="padding:0" class="btn btn-table-cell" type="button" data-bs-toggle="popover">
                                                <img src="${window.location.origin}/assets/libs/jqwidget/jqwidgets/styles/images/castom-light-wms/menu_dots_vertical.svg" alt="menu_dots_vertical">
                                            </button>
                                        `;

                    const popoverOptions = {
                        html: true,
                        sanitize: false,
                        placement: 'left',
                        trigger: 'focus',
                        container: 'body',
                        content: function () {
                            return `
                                    <div id="${popoverId}">
                                        <ul class="popover-castom" style="list-style: none">
                                            <li><a class="dropdown-item" href="${window.location.origin + languageBlock}/container-register/${rowdata.id}">${getLocalizedText('btnView')}</a></li>
                                            <li><a class="dropdown-item print-container" href="#" data-id="${rowdata.id}">${getLocalizedText('btnActionPrint')}</a></li>
                                        </ul>
                                    </div>`;
                        },
                    };

                    // ініціалізуємо поповер при кліку
                    $(document)
                        .off('click', '#' + buttonId)
                        .on('click', '#' + buttonId, function () {
                            $(this).popover(popoverOptions).popover('show');
                        });

                    // === Обробка кліку "Друк" ===
                    $(document)
                        .off('click', `#${popoverId} .print-container`)
                        .on('click', `#${popoverId} .print-container`, async function (e) {
                            e.preventDefault();

                            const csrf = document.querySelector('meta[name="csrf-token"]').content;
                            const formData = new FormData();
                            formData.append('_token', csrf);
                            formData.append('type', 'container');
                            formData.append('items[]', rowdata.id);

                            const $loader = $('#print-loader');

                            // Показуємо лоадер
                            $loader.removeClass('d-none').addClass('d-flex');

                            await sendRequestBase(
                                `${url}stickers/print-labels`,
                                formData,
                                null,
                                function (response) {
                                    // Ховаємо лоадер після відповіді
                                    $loader.removeClass('d-flex').addClass('d-none');

                                    if (response?.success && response?.file_url) {
                                        // Закриваємо модалку після успіху
                                        $('#print-modal').modal('hide');

                                        // Відкриваємо PDF для друку
                                        window.open(response.file_url, '_blank');
                                    } else {
                                        $loader.removeClass('d-flex').addClass('d-none');
                                        console.error(
                                            'Помилка: неправильна відповідь від сервера',
                                            response
                                        );
                                        alert('Не вдалося сформувати файл для друку.');
                                    }
                                }
                            );
                        });

                    return `<div class="jqx-popover-wrapper">${button}</div>`;
                },
            },
        ],
    });

    let listSource = [
        { label: getLocalizedText('code'), value: 'code', checked: true },
        { label: getLocalizedText('status'), value: 'status_field', checked: true },
        { label: getLocalizedText('type'), value: 'type', checked: true },
        { label: getLocalizedText('placing'), value: 'location', checked: true },
        { label: getLocalizedText('boot'), value: 'boot', checked: true },
        { label: getLocalizedText('action'), value: 'action', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});

let customData = [
    {
        id: '1',
        local_id: '1',
        code: 'BCRTR0000001234',
        status_id: '1',
        type: 'Європалета',
        location: 'Test Location - test wh - zona 1 - 1233123',
        weight: 90,
        cell: {
            cell: {
                id: 1,
                code: '1233123',
                height: 1,
                width: 1,
                deep: 1,
                max_weight: 120,
                type: 1,
                status_id: 1,
            },
            allocation: {
                sector: 'zona 1',
                warehouse: 'test wh',
                location: 'Test Location',
                cell: '1233123',
            },
        },
    },
    {
        id: '2',
        local_id: '2',
        code: 'BCRTR0000001235',
        status_id: '1',
        type: 'Європалета',
        location: 'Test Location - test wh - zona 1 - 1233123',
        weight: 90,
        cell: {
            cell: {
                id: 2,
                code: '1233123',
                height: 1,
                width: 1,
                deep: 1,
                max_weight: 120,
                type: 1,
                status_id: 1,
            },
            allocation: {
                sector: 'zona 1',
                warehouse: 'test wh',
                location: 'Test Location',
                cell: '1233123',
            },
        },
    },
    {
        id: '3',
        local_id: '3',
        code: 'BCRTR0000001236',
        status_id: '1',
        type: 'Європалета',
        location: 'Test Location - test wh - zona 1 - 1233123',
        weight: 90,
        cell: {
            cell: {
                id: 3,
                code: '1233123',
                height: 1,
                width: 1,
                deep: 1,
                max_weight: 120,
                type: 1,
                status_id: 1,
            },
            allocation: {
                sector: 'zona 1',
                warehouse: 'test wh',
                location: 'Test Location',
                cell: '1233123',
            },
        },
    },
    {
        id: '4',
        local_id: '4',
        code: 'BCRTR0000001237',
        status_id: '1',
        type: 'Європалета',
        location: 'Test Location - test wh - zona 1 - 1233123',
        cell: {
            cell: {
                id: 4,
                code: '1233123',
                height: 1,
                width: 1,
                deep: 1,
                max_weight: 120,
                type: 1,
                status_id: 1,
            },
            allocation: {
                sector: 'zona 1',
                warehouse: 'test wh',
                location: 'Test Location',
                cell: '1233123',
            },
        },
    },
];
