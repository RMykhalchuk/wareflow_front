import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { getLocalizedText } from '../../localization/transport/getLocalizedText.js';
import { hideLoader, showLoader } from '../components/loader.js';
import { switchLang } from '../components/switch-lang.js';

$(document).ready(function () {
    let table = $('#transport-table');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language == 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },
        { name: 'model', type: 'string' },
        { name: 'licensePlate', type: 'string' },
        { name: 'type', type: 'string' },
        { name: 'category', type: 'string' },
        { name: 'company', type: 'string' },
        { name: 'defaultDriver', type: 'string' },
    ];

    var myDataSource = {
        datatype: 'json',
        datafields: dataFields,
        url: window.location.origin + languageBlock + '/transports/table/filter',
        root: 'data',
        beforeprocessing: function (data) {
            myDataSource.totalrecords = data.total;
        },
        filter: function () {
            // update the grid and send a request to the server.
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
        theme: 'light-wms transport-table-custom',
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
                    return `<p style="" class="text-secondary ps-1 my-auto">${value}</p>`;
                },
            },
            {
                dataField: 'model',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexModel'),
                minwidth: 200,
                editable: false,
                cellsrenderer: function (row, column, value, defaultHtml, columnSettings, rowData) {
                    return `<a href='${window.location.origin + languageBlock}/transports/${rowData.id}' class="link-primary fw-bold ps-1 my-auto">${value}</a>`;
                },
            },
            {
                dataField: 'licensePlate',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexLicensePlate'),
                width: 200,
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<p class=" text-secondary ps-1 my-auto">${value}</p>`;
                },
            },
            {
                minwidth: 90,
                dataField: 'type',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexType'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<p class=" text-secondary ps-1 my-auto">${value}</p>`;
                },
            },
            {
                minwidth: 90,
                dataField: 'category',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexCategory'),
                editable: false,

                cellsrenderer: function (row, column, value, rowData) {
                    //console.log(value)

                    const valuesInCell = {
                        Вантажівка: { text: getLocalizedText('categoryLorry') },
                        'Вантажівка з причіпом': {
                            text: getLocalizedText('categoryLorryWithTrailer'),
                        },
                        Тягач: { text: getLocalizedText('categoryTruckTractor') },
                        Бус: { text: getLocalizedText('categoryVan') },
                    };

                    const translateValue = valuesInCell[value] || { text: '-' };

                    return `<p class="text-secondary ps-1 my-auto" >${translateValue.text || '-'}</p>`;
                },
            },
            {
                minwidth: 90,
                dataField: 'company',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexCompany'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<a href='${window.location.origin + languageBlock}/companies/${row + 1}' class="link-secondary link-hover-underline fw-bold ps-1 my-auto">${value}</a>`;
                },
            },
            {
                minwidth: 90,
                dataField: 'defaultDriver',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexDriver'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<a href='${window.location.origin + languageBlock}/users/show/${row + 1}' class="link-secondary link-hover-underline fw-bold ps-1 my-auto">${value}</a>`;
                },
            },
            {
                width: '70px',
                dataField: 'action',
                align: 'center',
                cellsalign: 'center',
                text: getLocalizedText('tableIndexAction'),
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
                    var buttonId = 'button-' + rowdata.uid;
                    var popoverId = 'popover-' + rowdata.uid;

                    let button =
                        '<button id="' +
                        buttonId +
                        '" style="padding:0" class="btn btn-table-cell" type="button" data-bs-toggle="popover"> <img src="' +
                        window.location.origin +
                        '/assets/libs/jqwidget/jqwidgets/styles/images/castom-light-wms/menu_dots_vertical.svg" alt="menu_dots_vertical"> </button>';

                    var popoverOptions = {
                        html: true,
                        sanitize: false,
                        placement: 'left',
                        trigger: 'focus',
                        container: 'body',
                        content: function () {
                            return `<div id=${popoverId}>
                            <ul class="popover-castom" style="list-style: none">
                                <li><a class="dropdown-item" href="${window.location.origin + languageBlock}/transports/${row + 1}">${getLocalizedText('tableIndexViewTransport')}</a></li>
                                <li><a class="dropdown-item" href="${window.location.origin + languageBlock}/transports/${row + 1}/edit">${getLocalizedText('tableIndexEditTransport')}</a></li>
                            <!--                                        <li><a class="dropdown-item delete-row" href="#">Видалити транспорт</a></li>-->
                            </ul>
                        </div>`;
                        },
                    };

                    $(document)
                        .off('click', '#' + buttonId)
                        .on('click', '#' + buttonId, function () {
                            $(this).popover(popoverOptions).popover('show');
                        });

                    $(document)
                        .off('click', '#' + popoverId + ' .delete-row')
                        .on('click', '#' + popoverId + ' .delete-row', function () {
                            var rowId = rowdata.uid;
                            grid.jqxGrid('deleterow', rowId);
                            $('#' + buttonId).popover('hide');
                        });

                    return '<div class="jqx-popover-wrapper">' + button + '</div>';
                },
            },
        ],
    });

    let listSource = [
        { label: getLocalizedText('id'), value: 'local_id', checked: true },
        { label: getLocalizedText('tableIndexModel'), value: 'model', checked: true },
        { label: getLocalizedText('tableIndexLicensePlate'), value: 'licensePlate', checked: true },
        { label: getLocalizedText('tableIndexType'), value: 'type', checked: true },
        { label: getLocalizedText('tableIndexCategory'), value: 'category', checked: true },
        { label: getLocalizedText('tableIndexCompany'), value: 'company', checked: true },
        { label: getLocalizedText('tableIndexDriver'), value: 'defaultDriver', checked: true },
        { label: getLocalizedText('tableIndexAction'), value: 'action', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});
