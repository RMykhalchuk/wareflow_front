import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { getLocalizedText } from '../../localization/company/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';

$(document).ready(function () {
    let table = $('#companies-table');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;
    let dataFields = [
        { name: 'name', type: 'string' },
        { name: 'property', type: 'string' },
        { name: 'type', type: 'string' },
        { name: 'edrpou', type: 'string' },
        { name: 'ipn', type: 'string' },
        { name: 'address', type: 'string' },
        { name: 'id', type: 'string' },
    ];

    var myDataSource = {
        datatype: 'json',
        datafields: dataFields,
        url: window.location.origin + languageBlock + '/companies/table/filter',
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
        theme: 'light-wms companies-table-custom',
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
                dataField: 'name',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexName'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<a style="" class=" ps-1 d-flex my-auto " href='${
                        window.location.origin + languageBlock
                    }/companies/${rowdata.id}'  >${value}</a>`;
                },
                minwidth: 170,
            },
            {
                dataField: 'property',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexProperty'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    //console.log(value)

                    const valuesInCell = {
                        'Моя компанія': { text: getLocalizedText('propertyMy') },
                        Контрагент: { text: getLocalizedText('propertyOur') },
                    };

                    const translateValue = valuesInCell[value] || { text: '-' };

                    return `<p class="text-secondary ps-1 my-auto" >${translateValue.text || '-'}</p>`;
                },

                width: 130,
                filterable: false,
            },
            {
                dataField: 'type',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexType'),
                minwidth: 100,
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    //console.log(value)

                    const valuesInCell = {
                        'Фізична особа': { text: getLocalizedText('typePhysical') },
                        'Юридична особа': { text: getLocalizedText('typeLegal') },
                    };

                    const translateValue = valuesInCell[value] || { text: '-' };

                    return `<p class="text-secondary ps-1 my-auto" >${translateValue.text || '-'}</p>`;
                },
            },
            {
                width: 120,
                dataField: 'edrpou',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexEdrpou'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<p class=" text-secondary ps-1 my-auto " style="" >${value || '-'}</p>`;
                },
            },
            {
                width: 120,
                dataField: 'ipn',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexIpn'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<p class=" text-secondary ps-1 my-auto " style="" >${value || '-'}</p>`;
                },
            },
            {
                minwidth: 100,
                dataField: 'address',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexAddress'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<p class="text-secondary  ps-1  my-auto" style="" >${value || '-'}</p>`;
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
                                      <li><a class="dropdown-item" href="${window.location.origin + languageBlock}/companies/${rowdata.id}">${getLocalizedText('tableIndexViewCompany')}</a></li>
                                      <li><a class="dropdown-item" href="${window.location.origin + languageBlock}/companies/${rowdata.id}/edit">${getLocalizedText('tableIndexEditCompany')}</a></li>
<!--                                      <li><a class="dropdown-item delete-row" href="#">${getLocalizedText('tableIndexDeleteCompany')}</a></li>-->
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
        { label: getLocalizedText('tableIndexName'), value: 'name', checked: true },
        { label: getLocalizedText('tableIndexProperty'), value: 'property', checked: true },
        { label: getLocalizedText('tableIndexType'), value: 'type', checked: true },
        { label: getLocalizedText('tableIndexEdrpou'), value: 'edrpou', checked: true },
        { label: getLocalizedText('tableIndexIpn'), value: 'ipn', checked: true },
        { label: getLocalizedText('tableIndexAddress'), value: 'address', checked: true },
        { label: getLocalizedText('tableIndexAction'), value: 'action', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});
