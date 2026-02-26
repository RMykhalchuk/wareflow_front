import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { getLocalizedText } from '../../localization/warehouse-erp/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';

const pagerRendererLeftovers = pagerRenderer.bind({});
const toolbarRendererLeftovers = toolbarRender.bind({});

$(document).ready(function () {
    let table = $('#warehouses-erp-table');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },
        { name: 'name', type: 'string' },
        { name: 'id_erp', type: 'string' },
    ];

    var myDataSource = {
        datatype: 'json',
        datafields: dataFields,
        url: window.location.origin + languageBlock + '/warehouses-erp/table/filter',
        root: 'data',
        beforeprocessing: function (data) {
            // console.log(data);
            myDataSource.totalrecords = data.total;
        },
        filter: function () {
            // update the grid and send a request to the server.
            showLoader();
            table.jqxGrid('updatebounddata', 'filter');
        },
        sort: function () {
            $('.search-btn')[0].click();
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
            return pagerRendererLeftovers(table);
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
        rowsheight: 35,
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

            return toolbarRendererLeftovers(statusbar, table, false, 1, columnCount - 1); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                dataField: 'local_id',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('index.id'),
                width: 70,
                editable: false,
                cellsrenderer: function (row, column, value, rowdata) {
                    return `<p style="" class="text-secondary ps-50 my-auto">${value}</p>`;
                },
            },
            {
                dataField: 'name',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('index.name'),
                minwidth: 120,
                editable: false,
                cellsrenderer: function (row, column, value, defaultHtml, columnSettings, rowData) {
                    // return `<a href="${window.location.origin + languageBlock + '/warehouses/' + rowData.id}"  style="color: #D9B414;" class="ps-50 text-dark fw-bold my-auto">${value}</a>`;
                    return `<p  style="color: #D9B414;" class="ps-50 text-dark fw-bold my-auto">${value}</p>`;
                },
            },
            {
                width: 330,
                dataField: 'id_erp',
                align: 'left',
                cellsalign: 'right',
                text: 'ID ERP',
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    if (!value) return `<p class="text-secondary ps-50 my-auto">-</p>`;
                    return `
                        <div class="d-flex flex-column ps-50">
                            <span class="text-dark my-auto d-inline-block text-truncate"
                                style="max-width: calc(330px - 0.5rem);"
                                title="${value}">${value}</span>
                        </div>`;
                },
            },
            {
                width: '70px',
                dataField: 'action',
                align: 'center',
                cellsalign: 'center',
                text: getLocalizedText('index.actions.title'),
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
                                                <a class="dropdown-item text-danger  delete-btn" href="#">${getLocalizedText('index.actions.delete')}</a>
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

                            const $popover = $('#' + popoverId);

                            // Видалення
                            $popover
                                .find('.delete-btn')
                                .off('click')
                                .on('click', async function (e) {
                                    e.preventDefault();

                                    const csrf =
                                        document.querySelector('meta[name="csrf-token"]').content;
                                    const locale = getCurrentLocaleFromUrl();
                                    const languageBlock = locale === 'en' ? '' : `/${locale}`;
                                    const requestUrl = `${location.origin + languageBlock}/warehouse-erp/${rowdata.id}`;

                                    try {
                                        const response = await fetch(requestUrl, {
                                            method: 'DELETE',
                                            headers: {
                                                'X-CSRF-TOKEN': csrf,
                                                Accept: 'application/json',
                                            },
                                        });

                                        table.jqxGrid('updatebounddata');

                                        // Пауза, щоб грід встиг оновитися
                                        setTimeout(() => {
                                            const rows = table.jqxGrid('getrows');

                                            if (!rows || rows.length === 0) {
                                                location.reload();
                                            }
                                        }, 200);

                                        if (!response.ok) throw new Error('Помилка при видаленні');

                                        // Закриваємо поповер
                                        $('#' + buttonId).popover('hide');
                                    } catch (error) {
                                        console.error('❌ Помилка при видаленні:', error);
                                        alert('Не вдалося видалити запис. Спробуйте ще раз.');
                                    }
                                });
                        });

                    return `<div class="jqx-popover-wrapper">${button}</div>`;
                },
            },
        ],
    });

    let listSource = [
        { label: getLocalizedText('index.id'), value: 'local_id', checked: true },
        { label: getLocalizedText('index.name'), value: 'name', checked: true },
        { label: 'ID ERP', value: 'id_erp', checked: true },
        { label: getLocalizedText('index.actions.title'), value: 'action', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});
