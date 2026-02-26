import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { getLocalizedText } from '../../localization/document/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';

$(document).ready(function () {
    let table = $('#documentDataTable');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language == 'en' ? '' : '/' + language;

    function getUrl() {
        const currentUrl = new URL(window.location.href);
        const documentId = currentUrl.pathname.split('/').pop();
        const STORAGE_KEY = 'global_warehouse_id';
        const storedWarehouse = localStorage.getItem(STORAGE_KEY);

        let newUrl =
            window.location.origin +
            languageBlock +
            '/document/table/filter?document_id=' +
            documentId;

        if (storedWarehouse) {
            newUrl += '&warehouse_id=' + storedWarehouse;
        }

        return newUrl;
    }

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'number' },
        { name: 'erp_id', type: 'string' },
        ...fields,
        // status object
        { name: 'status_key', map: 'status>key', type: 'number' },
        { name: 'status_name', map: 'status>name', type: 'number' },
        { name: 'status_id', type: 'number' },

        { name: 'created_name', map: 'created>name', type: 'string' },
        { name: 'created_date', map: 'created>date', type: 'string' },
        { name: 'created_time', map: 'created>time', type: 'string' },
    ];

    var source = {
        dataType: 'json',
        datafields: dataFields,
        url: getUrl(),
        root: 'data',
        beforeprocessing: function (data) {
            source.totalrecords = data.total;
        },
        filter: function () {
            showLoader();
            // update the grid and send a request to the server.
            table.jqxGrid('updatebounddata', 'filter');
        },
        sort: function () {
            // update the grid and send a request to the server.
            table.jqxGrid('updatebounddata', 'sort');
        },
        deleteRow: async function (rowID, commit) {
            var rowData = table.jqxGrid('getrowdata', rowID);

            let formData = new FormData();
            formData.append('_method', 'DELETE');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            fetch(window.location.origin + '/table/document/' + rowData.id, {
                method: 'POST',
                body: formData,
            }).then(() => {
                commit(true);
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

    //console.log(columns)
    var grid = table.jqxGrid({
        theme: 'light-wms',
        width: '100%',
        autoheight: true,
        pageable: true,
        showdefaultloadelement: false,
        pagerRenderer: function () {
            return pagerRenderer(table);
        },
        virtualmode: true,
        autoBind: true,
        rendergridrows: function () {
            return dataAdapter.records;
        },
        ready() {
            checkUrl();
        },
        source: dataAdapter,
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
        rowsheight: 65,
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
            //console.log(columns)
            //console.log(columnCount)
            return toolbarRender(statusbar, table, true, 1, columnCount - 1); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                dataField: 'local_id',
                text: getLocalizedText('tableDocumentID'),
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
                    const realId = rowdata.id ?? value;

                    return `<a href="${window.location.origin + languageBlock}/document/${realId}" class="text-dark fw-bolder ps-50 my-auto text-decoration-underline">${value}</a>`;
                },
            },
            // {
            //     dataField: 'erp_id',
            //     text: '№ документу ERP',
            //     width: 200,
            //     editable: false,
            //     cellsrenderer: function (row, column, value, rowdata) {
            //         return `<p style="" class="text-secondary ps-50 my-auto">${value ? value : '-'}</p>`;
            //     },
            // },
            ...columns,
            {
                minwidth: 250,
                dataField: 'status',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('tableDocumentStatus'),
                editable: false,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    let badgeClass = 'bg-secondary';

                    console.log(value);
                    switch (rowdata.status_key) {
                        case 'created':
                            badgeClass = 'bg-light-primary';
                            break;
                        case 'process':
                            badgeClass = 'bg-light-success';
                            break;
                        case 'done':
                            badgeClass = 'bg-light-secondary';
                            break;
                        default:
                            badgeClass = 'bg-warning';
                            break;
                    }

                    let localizedName = rowdata.status_name[language] ?? rowdata.status_name ?? '-';
                    return `<div class="badge ${badgeClass} ms-50 py-50 px-1">
                    <span class="text-dark">${localizedName}</span>
                </div>`;
                },
            },
            // {
            //     dataField: 'created_field',
            //     text: 'Створено',
            //     width: 200,
            //     cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
            //         return `<div class="d-flex flex-column ps-50">
            //                     <a class="text-dark fw-bolder my-auto" href='#'>${rowdata.created_name ?? '-'}</a>
            //                     <div>${rowdata.created_date ?? '-'} ${rowdata.created_time ?? '-'}</div>
            //                 </div>`;
            //     },
            // },
            {
                width: '70px',
                dataField: 'action',
                align: 'center',
                cellsalign: 'center',
                text: getLocalizedText('tableDocumentAction'),
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

                    // логіка доступів
                    const canEdit = !['process', 'done'].includes(rowdata.status_key);
                    const canDelete = rowdata.status_key !== 'done';

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
                                            <a class="dropdown-item" href="${window.location.origin}${languageBlock}/document/${rowdata.id}">
                                              ${getLocalizedText('tableDocumentActionView')}
                                            </a>
                                          </li>

                                          ${
                                              canEdit
                                                  ? `
                                          <li>
                                            <a class="dropdown-item" href="${window.location.origin}${languageBlock}/document/${rowdata.id}/edit">
                                              ${getLocalizedText('tableDocumentActionEdit')}
                                            </a>
                                          </li>
                                          `
                                                  : ''
                                          }

                                          ${
                                              canDelete
                                                  ? `
                                          <li>
                                            <a class="dropdown-item delete-row" href="#">
                                              ${getLocalizedText('tableDocumentActionDelete')}
                                            </a>
                                          </li>
                                          `
                                                  : ''
                                          }
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
                            table.jqxGrid('updatebounddata');
                            $(`#${buttonId}`).popover('hide');
                        });

                    return `<div class="jqx-popover-wrapper">${button}</div>`;
                },
            },
        ],
    });

    let listSource = [
        { label: getLocalizedText('tableDocumentID'), value: 'id', checked: true },
        // { label: 'ERP ID', value: 'erp_id', checked: true },
        ...listSourceArray,
        { label: getLocalizedText('tableDocumentStatus'), value: 'status', checked: true },
        // { label: 'Створено', value: 'created_field', checked: true },

        { label: getLocalizedText('tableDocumentAction'), value: 'action', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});
