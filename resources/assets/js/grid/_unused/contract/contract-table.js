import { pagerRenderer } from '../../components/pager.js';
import { toolbarRender } from '../../components/toolbar-advanced.js';
import { listbox } from '../../components/listbox.js';
import { hover } from '../../components/hover.js';
import { getLocalizedText } from '../../../localization/_unused/contract/getLocalizedText.js';
import { switchLang } from '../../components/switch-lang.js';

const pagerRendererLeftovers = pagerRenderer.bind({});
const toolbarRendererLeftovers = toolbarRender.bind({});

$(document).ready(function () {
    let table = $('#contract-table');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language == 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'number', type: 'string' },
        { name: 'inputOutput', type: 'string' },
        { name: 'yourCompany', type: 'string' },
        { name: 'yourCompanyId', type: 'string' },
        { name: 'contractor', type: 'string' },
        { name: 'contractorId', type: 'string' },
        { name: 'type', type: 'string' },
        { name: 'status', type: 'string' },
        { name: 'id', type: 'string' },
    ];

    var myDataSource = {
        datatype: 'json',
        datafields: dataFields,
        url: window.location.origin + '/contracts/table/filter',
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
    let dataAdapter = new $.jqx.dataAdapter(myDataSource);
    var grid = table.jqxGrid({
        theme: 'light-wms contract-table-custom',
        width: '100%',
        autoheight: true,
        pageable: true,
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
        sortable: true,
        columnsResize: false,
        filterable: true,
        filtermode: 'default',
        localization: getLocalization(language),
        selectionMode: 'checkbox',
        enablehover: false,
        columnsreorder: true,
        autoshowfiltericon: true,
        pagermode: 'advanced',
        rowsheight: 35,
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
            var columns = table.jqxGrid('columns').records;
            var columnCount = columns.length;

            return toolbarRendererLeftovers(statusbar, table, true, 1, columnCount - 1); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                dataField: 'number',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexNumber'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<a class=" ps-1 d-flex my-auto fw-bold" href='${window.location.origin + languageBlock}/contracts/${value}'>${value}</p>`;
                },
                width: 150,
            },
            {
                dataField: 'inputOutput',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexInputOutput'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `
                          <div class="d-flex align-items-center">
                            <p style="width: 100px" class="text-secondary ps-1 my-auto">${value === 'Вихідний' ? getLocalizedText('tableIndexInputOutputCellValueOut') : getLocalizedText('tableIndexInputOutputCellValueIn')}</p>
                            <img class="text-${value === 'Вихідний' ? 'success' : 'danger'}"
                                 src="${window.location.origin}/assets/icons/entity/contract/arrow-${value === 'Вихідний' ? 'right' : 'left'}.svg"
                                 alt="triangle">
                          </div>`;
                },
                width: 170,
            },
            {
                dataField: 'yourCompany',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexYourCompany'),
                minwidth: 150,
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return ` <a class= "ps-1 text-secondary  my-auto fw-medium-c underline-on-hover text-truncate"
                    href = '${window.location.origin + languageBlock}/companies/${rowdata.yourCompanyId}' >${value} </a>`;
                },
            },
            {
                minwidth: 150,
                dataField: 'contractor',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexContractor'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<a class="ps-1 text-secondary  my-auto fw-medium-c underline-on-hover text-truncate" href='${window.location.origin + languageBlock}/companies/${rowdata.contractorId}' >${value}</a>`;
                },
            },
            {
                minwidth: 300,
                dataField: 'type',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexType'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<p class="text-secondary  ps-1  my-auto"  >${value === 'Договір на торгові послуги' ? getLocalizedText('tableIndexTypeCellValue1') : value === 'Договір на складські послуги' ? getLocalizedText('tableIndexTypeCellValue2') : getLocalizedText('tableIndexTypeCellValue3')}</p>`;
                },
            },
            {
                width: 230,
                dataField: 'status',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableIndexStatus'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    console.log(value);

                    const statusValues = {
                        Створено: {
                            text: getLocalizedText('tableIndexStatusCellValue1'),
                            class: 'secondary',
                        },
                        'Надіслано на розгляд': {
                            text: getLocalizedText('tableIndexStatusCellValue2'),
                            class: 'primary',
                        },
                        'Очікує на розгляд': {
                            text: getLocalizedText('tableIndexStatusCellValue3'),
                            class: 'primary',
                        },
                        'Очікує на підпис': {
                            text: getLocalizedText('tableIndexStatusCellValue4'),
                            class: 'primary',
                        },
                        'Підписано всіма': {
                            text: getLocalizedText('tableIndexStatusCellValue5'),
                            class: 'success',
                        },
                        Розірвано: {
                            text: getLocalizedText('tableIndexStatusCellValue6'),
                            class: 'secondary',
                        },
                        Відхилено: {
                            text: getLocalizedText('tableIndexStatusCellValue7'),
                            class: 'danger',
                        },
                        'Відхилено контрагентом': {
                            text: getLocalizedText('tableIndexStatusCellValue8'),
                            class: 'danger',
                        },
                    };

                    const status = statusValues[value] || { text: '-', class: 'primary' };

                    return `<div class="ps-1">
                                <div class="fw-bolder alert alert-${status.class}"
                                    style="padding: 2px 10px !important;">
                                    ${status.text}
                                </div>
                            </div>`;
                },
            },
            {
                width: '70px',
                dataField: 'action',
                align: 'center',
                cellsalign: 'center',
                text: getLocalizedText('tableIndexAction'),
                renderer: function () {
                    return (
                        '<div style="display: flex; align-items: center; justify-content: center; height: 100%;"><img src=' +
                        window.location.origin +
                        '"/assets/libs/jqwidget/jqwidgets/styles/images/castom-light-wms/setting-button-table.svg" alt="setting-button-table"></div>'
                    );
                },
                filterable: false,
                sortable: false,
                id: 'action',
                cellClassName: 'action-table-drop',
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
                        '" style="padding:0" class="btn btn-table-cell" type="button" data-bs-toggle="popover"> <img src=' +
                        window.location.origin +
                        '"/assets/libs/jqwidget/jqwidgets/styles/images/castom-light-wms/menu_dots_vertical.svg" alt="menu_dots_vertical"> </button>';

                    var popoverOptions = {
                        html: true,
                        sanitize: false,
                        placement: 'left',
                        trigger: 'focus',
                        container: 'body',
                        content: function () {
                            return `<div id=${popoverId}>
                                          <ul class="popover-castom" style="list-style: none">
                                          <li><a class="dropdown-item" href="${window.location.origin + languageBlock}/contracts/${rowdata.id}">${getLocalizedText('tableIndexViewContract')}</a></li>
                                          <li><a class="dropdown-item delete-row" href="#">${getLocalizedText('tableIndexDeleteContract')}</a></li>
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
                            fetch(window.location.origin + `/contracts/${rowdata.id}`, {
                                method: 'delete',
                                headers: {
                                    'X-CSRF-Token':
                                        document.querySelector('meta[name="csrf-token"]').content,
                                },
                            }).then(async function (res) {
                                if (res.status === 200) {
                                    var rowId = rowdata.uid;
                                    grid.jqxGrid('deleterow', rowId);
                                    $('#' + buttonId).popover('hide');
                                } else {
                                    console.log((await res.json()).message);
                                }
                            });
                        });

                    return '<div class="jqx-popover-wrapper">' + button + '</div>';
                },
            },
        ],
    });

    let listSource = [
        { label: getLocalizedText('tableIndexNumber'), value: 'number', checked: true },
        { label: getLocalizedText('tableIndexInputOutput'), value: 'inputOutput', checked: true },
        { label: getLocalizedText('tableIndexYourCompany'), value: 'yourCompany', checked: true },
        { label: getLocalizedText('tableIndexContractor'), value: 'contractor', checked: true },
        { label: getLocalizedText('tableIndexType'), value: 'type', checked: true },
        { label: getLocalizedText('tableIndexStatus'), value: 'status', checked: true },
        { label: getLocalizedText('tableIndexAction'), value: 'action', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});
