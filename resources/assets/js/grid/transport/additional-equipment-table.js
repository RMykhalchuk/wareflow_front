import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { getLocalizedText } from '../../localization/additional-equipment/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';

$(document).ready(function () {
    let table = $('#additional-equipment-table');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language == 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },
        { name: 'model', type: 'string' },
        { name: 'dnz', type: 'string' },
        { name: 'typeLoad', type: 'string' },
        { name: 'company', type: 'string' },
        { name: 'car', type: 'string' },
    ];

    var myDataSource = {
        datatype: 'json',
        datafields: dataFields,
        url: window.location.origin + languageBlock + '/transport-equipments/table/filter',
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
        theme: 'light-wms additional-equipment-custom',
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
                width: 250,
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<a  class="text-primary ps-1 my-auto" href='${window.location.origin + languageBlock}/transport-equipments/${rowdata.id}'>${value}</a>`;
                },
            },
            {
                dataField: 'dnz',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexDnz'),
                width: 180,
                editable: false,
                cellsrenderer: function (row, column, value, rowdata) {
                    return `<p class=" text-secondary ps-1 my-auto">${value}</p>`;
                },
            },
            {
                minwidth: 90,
                dataField: 'typeLoad',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexTypeLoad'),
                editable: false,
                cellsrenderer: function (row, column, value, rowdata) {
                    return `<p class=" text-secondary ps-1 my-auto">${value}</p>`;
                },
            },
            {
                minwidth: 90,
                dataField: 'company',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexCompany'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<a class="ps-1 text-secondary  my-auto fw-medium-c underline-on-hover" href='${window.location.origin + languageBlock}/companies/${rowdata.id}' >${value}</a>`;
                },
            },
            {
                width: 220,
                dataField: 'car',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexCar'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<a class="ps-1 text-secondary  my-auto underline-on-hover" href='${window.location.origin + languageBlock}/transports/${1}' >${value}</a>`;
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
                        <li><a class="dropdown-item" href="${window.location.origin + languageBlock}/transport-equipments/${rowdata.id}">${getLocalizedText('tableIndexViewEquipment')}</a></li>
                        <li><a class="dropdown-item" href="${window.location.origin + languageBlock}/transport-equipments/${rowdata.id}/edit">${getLocalizedText('tableIndexEditEquipment')}</a></li>
                        <!--<li><a class="dropdown-item delete-row" href="#">Видалити обладнання</a></li>-->
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
        { label: getLocalizedText('tableIndexDnz'), value: 'dnz', checked: true },
        { label: getLocalizedText('tableIndexTypeLoad'), value: 'typeLoad', checked: true },
        { label: getLocalizedText('tableIndexCompany'), value: 'company', checked: true },
        { label: getLocalizedText('tableIndexCar'), value: 'car', checked: true },
        { label: getLocalizedText('tableIndexAction'), value: 'action', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});
