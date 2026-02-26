import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { getLocalizedText } from '../../localization/user/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';

$(document).ready(function () {
    let table = $('#usersDataTable');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    function fullnameRender(row, column, value, defaultHtml, columnSettings, rowData) {
        const html = defaultHtml.split('><');
        const wrappedContent = `
        ${html[0]} id="full_name-${row}">
            ${rowData.surname} ${rowData.name} ${rowData.patronymic}<${html[1]}
    `.trim();

        return `
        <a class="fw-bold" href="${window.location.origin}${languageBlock}/users/show/${rowData.id}">
            ${wrappedContent}
        </a>
    `;
    }

    function isOnlineRender(row, column, value, defaulthtml, columnproperties) {
        let data = table.jqxGrid('getrowdatabyid', row);

        function calculateStatus() {
            if (data && data.is_online === true) {
                return `<p class='badge badge-light-success' style='margin-left:4px; margin-top: 8px'> ${getLocalizedText('online')}</p>`;
            } else {
                return `<p class='badge badge-light-danger' style='margin-left:4px; margin-top: 8px'> ${getLocalizedText('offline')}</p>`;
            }
        }

        table.on('pagechanged', function () {
            // Calculate the status when the page is changed
            value = calculateStatus();
            table.jqxGrid('refresh');
        });

        return calculateStatus();
    }

    var source = {
        dataType: 'json',
        dataFields: [
            { name: 'id', type: 'string' },
            { name: 'name', type: 'string' },
            { name: 'surname', type: 'string' },
            { name: 'patronymic', type: 'string' },
            { name: 'company', type: 'string' },
            { name: 'position', type: 'string' },
            { name: 'role', type: 'string' },
            { name: 'birthday', type: 'string' },
            { name: 'phone', type: 'string' },
            { name: 'email', type: 'string' },
            { name: 'created_at', type: 'date' },
            { name: 'updated_at', type: 'date' },
            { name: 'last_seen', type: 'date' },
            { name: 'is_online', type: 'string' },
        ],
        url: window.location.origin + languageBlock + '/users/filter',
        root: 'data',
        beforeprocessing: function (data) {
            source.totalrecords = data.total;
        },
        filter: function () {
            // update the grid and send a request to the server.
            table.jqxGrid('updatebounddata', 'filter');
        },
        sort: function () {
            $('.search-btn')[0].click();
        },
        delete: function (data) {
            $.ajax({
                url: '/users/delete/' + data.id,
                type: 'POST',
                data: {
                    _token: document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),
                    _method: 'DELETE',
                },
                success: function () {
                    $('#usersDataTable').jqxGrid('updatebounddata');
                },
            });
        },
    };

    showLoader(); // ПЕРЕД створенням dataAdapter

    let dataAdapter = new $.jqx.dataAdapter(source, {
        loadComplete: function () {
            hideLoader();
        },
        loadError: function (xhr, status, error) {
            hideLoader();
            // console.error('Load error:', status, error);
        },
    });

    table.jqxGrid({
        theme: 'light-wms',
        width: '100%',
        autoheight: true,
        pageable: true,
        showdefaultloadelement: false,
        // використання pagerRenderer для створення кастомної пагінації
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
        source: dataAdapter,
        sortable: false,
        columnsResize: false,
        editable: false,
        filterable: false,
        filtermode: 'default',
        localization: getLocalization(language),
        selectionMode: 'checkbox',
        columnsreorder: false,
        autoshowfiltericon: true,
        pagermode: 'simple',
        rowsheight: 35,
        filterbarmode: 'simple',
        showToolbar: true,
        toolbarHeight: 55,
        rendertoolbar: function (statusbar) {
            var columns = table.jqxGrid('columns').records;
            var columnCount = columns.length;
            //console.log(columns)
            //console.log(columnCount)
            return toolbarRender(statusbar, table, false, 1, columnCount - 1); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                minwidth: 250,
                dataField: 'full_name',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('full_name'),
                cellsrenderer: fullnameRender,
            },
            {
                dataField: 'position',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('position'),
                width: 200,
                cellsrenderer: function (row, column, value, rowData) {
                    if (!value) {
                        return '<div class="ps-25">-</div>';
                    }
                    let parsed = value;

                    if (typeof value === 'string') {
                        try {
                            parsed = JSON.parse(value);
                        } catch (e) {
                            return `<div class="ps-25">${value}</div>`;
                        }
                    }

                    const locale = document.documentElement.lang || 'uk';

                    return `<div class="ps-25">${parsed[locale] ?? '-'}</div>`;
                },
            },
            {
                dataField: 'role',
                align: 'center',
                cellsAlign: 'center',
                text: getLocalizedText('role'),
                hidden: true,
                minwidth: 300,
                cellsrenderer: function (row, column, value, rowData) {
                    const valuesInCell = {
                        'Адміністратор системи': { text: getLocalizedText('roleSuperAdmin') },
                        Адміністратор: { text: getLocalizedText('roleAdmin') },
                        Користувач: { text: getLocalizedText('roleUser') },
                        Логіст: { text: getLocalizedText('roleLogistic') },
                        Диспечер: { text: getLocalizedText('roleDispatcher') },
                    };

                    const translateValue = valuesInCell[value] || { text: '-' };

                    return `<div class="ps-25">${translateValue.text}</div>`;
                },
            },
            {
                dataField: 'email',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('email'),
                width: 300,
            },
            {
                dataField: 'phone',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('phone'),
                minwidth: 200,
            },
            {
                dataField: 'company',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('company'),
                minwidth: 200,
            },
            {
                dataField: 'is_online',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('status'),
                cellsrenderer: isOnlineRender,
                filterable: false,
                width: 200,
            },
            {
                dataField: 'birthday',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('birthday'),
                hidden: true,
                minwidth: 100,
            },
            {
                dataField: 'last_seen',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('last_seen'),
                cellsformat: 'yyyy-M-d',
                hidden: true,
                minwidth: 100,
            },
            {
                dataField: 'updated_at',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('updated_at'),
                cellsformat: 'yyyy-M-d',
                hidden: true,
                minwidth: 100,
            },
            {
                dataField: 'created_at',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('created_at'),
                cellsformat: 'yyyy-M-d',
                hidden: true,
                minwidth: 100,
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
                    const buttonId = `button-${rowdata.uid}`;
                    const popoverId = `popover-${rowdata.uid}`;
                    const id = rowdata.id;

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
                                            <li>
                                                <a class="dropdown-item" href="${window.location.origin}${languageBlock}/users/show/${rowdata.id}">
                                                    ${getLocalizedText('view_user')}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="${window.location.origin}${languageBlock}/users/update/${rowdata.id}">
                                                    ${getLocalizedText('edit_profile')}
                                                </a>
                                            </li>
                                            <!--
                                            <li>
                                                <button class="dropdown-item ps-1 text-danger user-delete" onclick="deleteUser(${id})">
                                                    Деактивувати користувача
                                                </button>
                                            </li>
                                            -->
                                        </ul>
                                    </div>
                                `;
                        },
                    };

                    $(document)
                        .off('click', `#${buttonId}`)
                        .on('click', `#${buttonId}`, function () {
                            $(this).popover(popoverOptions).popover('show');
                        });

                    $(document)
                        .off('click', `#${popoverId} .delete-row`)
                        .on('click', `#${popoverId} .delete-row`, function () {
                            const rowId = rowdata.uid;
                            grid.jqxGrid('deleterow', rowId);
                            $(`#${buttonId}`).popover('hide');
                        });

                    return `<div class="jqx-popover-wrapper">${button}</div>`;
                },
            },
        ],
    });

    var listSource = [
        { label: getLocalizedText('full_name'), value: 'full_name', checked: true },
        { label: getLocalizedText('position'), value: 'position', checked: true },
        { label: getLocalizedText('company'), value: 'company', checked: true },
        { label: getLocalizedText('email'), value: 'email', checked: true },
        { label: getLocalizedText('phone'), value: 'phone', checked: true },
        { label: getLocalizedText('is_online'), value: 'is_online', checked: true },
        { label: getLocalizedText('birthday'), value: 'birthday', checked: false },
        { label: getLocalizedText('role'), value: 'role', checked: false },
        { label: getLocalizedText('created_at'), value: 'created_at', checked: false },
        { label: getLocalizedText('updated_at'), value: 'updated_at', checked: false },
        { label: getLocalizedText('last_seen'), value: 'last_seen', checked: false },
        { label: getLocalizedText('action'), value: 'action', checked: true },
    ];

    listbox(table, listSource);

    hover(table, isRowHovered);

    //End script
});
