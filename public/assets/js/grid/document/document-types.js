import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { getLocalizedText } from '../../localization/document/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { translateDocumentColumnName } from '../../localization/document/translateDocumentColumnName.js';

const pagerRendererDocument = pagerRenderer.bind({});

const toolbarRendererDocument = toolbarRender.bind({});
const toolbarRendererDocumentModal = toolbarRender.bind({});

for (let i = 0; i < relatedDocuments.length; i++) {
    let el = JSON.parse(relatedDocuments[i].settings);
    let fieldsData = JSON.parse(JSON.stringify(el['fields']['header']));
    let customBlocks = el['custom_blocks'];

    let fields = [];
    let columns = [];
    let listSourceArray = [];

    //generate fields from header block
    for (let key in fieldsData) {
        if (fieldsData.hasOwnProperty(key)) {
            let item = fieldsData[key];
            let localizedText = translateDocumentColumnName(item.name);

            fields.push({ name: 'data->header->' + key, type: 'string' });
            columns.push({
                minwidth: '200',
                dataField: 'data->header->' + key,
                align: 'left',
                cellsalign: 'left',
                text: localizedText,
            });

            listSourceArray.push({
                label: localizedText,
                value: 'data->header->' + key,
                checked: true,
            });
        }
    }

    //generate fields from custom blocks
    for (let i = 0; i < Object.keys(customBlocks).length; i++) {
        for (let key in customBlocks[i]) {
            let item = customBlocks[i][key];
            let localizedText = translateDocumentColumnName(item.name);

            fields.push({ name: 'data->custom_blocks->' + i + '->' + key, type: 'string' });
            columns.push({
                minwidth: '200',
                dataField: 'data->custom_blocks->' + i + '->' + key,
                align: 'left',
                cellsalign: 'left',
                text: localizedText,
            });
            listSourceArray.push({
                label: localizedText,
                value: 'data->custom_blocks->' + i + '->' + key,
                checked: true,
            });
        }
    }

    $(document).ready(function () {
        let table = $('#document-' + relatedDocuments[i]['id']);
        let isRowHovered = false;

        let language = switchLang();

        var myDataSource = {
            datatype: 'json',
            datafields: [
                { name: 'id', type: 'number' },
                { name: 'count', type: 'number' },
                ...fields,
            ],
            localdata: [],
            deleteRow: function (rowID, commit) {
                let id = $('#document-' + relatedDocuments[i]['id']).jqxGrid(
                    'getrowdatabyid',
                    0
                ).id;
                const index = documentArray.indexOf(id);

                if (index !== -1) {
                    documentArray.splice(index, 1);
                }
                commit(true);
            },
        };

        let dataAdapter = new $.jqx.dataAdapter(myDataSource);
        let grid = table.jqxGrid({
            theme: 'light-wms',
            width: '100%',
            autoheight: true,
            pageable: true,
            source: dataAdapter,
            pagerRenderer: function () {
                return pagerRendererDocument(table);
            },
            // virtualmode: true,
            // rendergridrows: function () {
            //     return dataAdapter.records;
            // },
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
                return toolbarRendererDocument(
                    statusbar,
                    table,
                    true,
                    1,
                    columnCount - 1,
                    `-${relatedDocuments[i]['id']}`
                ); // Subtract 1 to exclude the action column
            },
            columns: [
                {
                    width: '70px',
                    dataField: 'id',
                    align: 'left',
                    cellsalign: 'right',
                    text: getLocalizedText('tableDocumentTypesID'),
                    editable: false,
                },
                ...columns,
                {
                    width: '70px',
                    dataField: 'action',
                    align: 'center',
                    cellsalign: 'center',
                    text: getLocalizedText('tableDocumentTypesAction'),
                    renderer: function () {
                        return (
                            '<div style="display: flex; align-items: center; justify-content: center; height: 100%;"><img src="' +
                            window.location.origin +
                            '/assets/libs/jqwidget/jqwidgets/styles/images/castom-light-wms/setting-button-table.svg" alt="setting-button-table"></div>'
                        );
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
                        let button =
                            '<button style="padding:0" class="btn btn-table-cell" type="button" data-bs-toggle="popover"> <img src="' +
                            window.location.origin +
                            '/assets/libs/jqwidget/jqwidgets/styles/images/castom-light-wms/menu_dots_vertical.svg" alt="menu_dots_vertical"> </button>';
                        $(document).on('click', '[data-bs-toggle="popover"]', function () {
                            $(this)
                                .popover({
                                    html: true,
                                    sanitize: false,
                                    content: function () {
                                        return (
                                            '<div>' +
                                            '<ul class="popover-castom" style="list-style: none">' +
                                            '<li><a class="dropdown-item delete-row" href="#">' +
                                            getLocalizedText('tableDocumentTypesActionDelete') +
                                            '</a></li>' +
                                            '</ul>' +
                                            '</div>'
                                        ); // Replace with your popover content
                                    },
                                    placement: 'left',
                                    trigger: 'focus',
                                })
                                .popover('show');
                        });
                        $(document).on('click', '.delete-row', function () {
                            var rowId = rowdata.uid;
                            grid.jqxGrid('deleterow', rowId);
                        });
                        return '<div class="jqx-popover-wrapper">' + button + '</div>';
                    },
                },
            ],
        });

        let listSource = [
            { label: getLocalizedText('tableDocumentTypesID'), value: 'id', checked: true },
            ...listSourceArray,
            { label: getLocalizedText('tableDocumentTypesAction'), value: 'action', checked: true },
        ];

        listbox(table, listSource, `-${relatedDocuments[i]['id']}`);
        hover(table, isRowHovered);
    });

    $(document).ready(function () {
        let table = $('#pop-up-document-' + relatedDocuments[i]['id']);
        let mainTable = $('#document-' + relatedDocuments[i]['id']).jqxGrid('getInstance');
        let isRowHovered = false;

        let language = switchLang();
        let languageBlock = language === 'en' ? '' : '/' + language;

        let addNewGoodsInvoicesItem = $('#add-document-row-' + relatedDocuments[i]['id']);
        addNewGoodsInvoicesItem.on('click', function () {
            let selectedRowIndexes = table.jqxGrid('getselectedrowindexes');
            let gridRows = table.jqxGrid('getrows');
            selectedRowIndexes.sort(function (a, b) {
                return a - b;
            });

            for (let k = 0; k < selectedRowIndexes.length; k++) {
                var rowData = table.jqxGrid('getrowdata', selectedRowIndexes[k]);
                console.log();
                if (!documentArray.includes(rowData.id)) {
                    mainTable.addrow(null, rowData);
                    documentArray.push(rowData.id);
                }
            }
            console.log(documentArray);
            table.jqxGrid('clearselection');
            $('#modal-document-' + i).modal('hide');
        });

        let dataSource = {
            dataType: 'json',
            datafields: [
                { name: 'id', type: 'number' },
                { name: 'count', type: 'number' },
                ...fields,
            ],
            url:
                window.location.origin +
                languageBlock +
                '/document-type/table/filter?type_id=' +
                relatedDocuments[i]['id'],
            beforeprocessing: function (data) {
                dataSource.totalrecords = data.total;
            },
            filter: function () {
                // update the grid and send a request to the server.
                table.jqxGrid('updatebounddata', 'filter');
            },
            sort: function () {
                // update the grid and send a request to the server.
                table.jqxGrid('updatebounddata', 'sort');
            },
        };

        let grid = table.jqxGrid({
            theme: 'light-wms',
            width: '100%',
            autoheight: true,
            pageable: true,
            source: new $.jqx.dataAdapter(dataSource),
            pagerRenderer: function () {
                return pagerRendererDocument(table);
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
                //console.log(columns)
                //console.log(columnCount)
                return toolbarRendererDocumentModal(
                    statusbar,
                    table,
                    false,
                    1,
                    columnCount - 1,
                    `-${relatedDocuments[i]['id']}`
                ); // Subtract 1 to exclude the action column
            },

            columns: [
                {
                    width: '70px',
                    dataField: 'id',
                    align: 'left',
                    cellsalign: 'right',
                    text: getLocalizedText('tableDocumentTypesID'),
                    editable: false,
                },
                ...columns,
                {
                    width: '70px',
                    dataField: 'action',
                    align: 'center',
                    cellsalign: 'center',
                    text: getLocalizedText('tableDocumentTypesAction'),
                    renderer: function () {
                        return (
                            '<div style="display: flex; align-items: center; justify-content: center; height: 100%;"><img src="' +
                            window.location.origin +
                            '/assets/libs/jqwidget/jqwidgets/styles/images/castom-light-wms/setting-button-table.svg" alt="setting-button-table"></div>'
                        );
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
                        let button =
                            '<button style="padding:0" class="btn btn-table-cell" type="button" data-bs-toggle="popover"> <img src="' +
                            window.location.origin +
                            '/assets/libs/jqwidget/jqwidgets/styles/images/castom-light-wms/menu_dots_vertical.svg" alt="menu_dots_vertical"> </button>';
                        $(document).on('click', '[data-bs-toggle="popover"]', function () {
                            $(this)
                                .popover({
                                    html: true,
                                    sanitize: false,
                                    content: function () {
                                        return (
                                            '<div>' +
                                            '<ul class="popover-castom" style="list-style: none">     ' +
                                            '<li><a class="dropdown-item delete-row" href="#">' +
                                            getLocalizedText('tableDocumentTypesActionDelete') +
                                            '</a></li>' +
                                            '</ul>' +
                                            '</div>'
                                        ); // Replace with your popover content
                                    },
                                    placement: 'left',
                                    trigger: 'focus',
                                })
                                .popover('show');
                        });
                        $(document).on('click', '.delete-row', function () {
                            var rowId = rowdata.uid;
                            grid.jqxGrid('deleterow', rowId);
                        });
                        return '<div class="jqx-popover-wrapper">' + button + '</div>';
                    },
                },
            ],
        });

        let listSource = [
            { label: getLocalizedText('tableDocumentTypesID'), value: 'id', checked: true },
            ...listSourceArray,
            { label: getLocalizedText('tableDocumentTypesAction'), value: 'action', checked: true },
        ];

        hover(table, isRowHovered);
        listbox(table, listSource, `-${relatedDocuments[i]['id']}`);

        var updateSelectedCount = function () {
            var selectedCount = table.jqxGrid('getselectedrowindexes').length;
            addNewGoodsInvoicesItem.text(
                getLocalizedText('updateSelectedCountText') + ' ' + selectedCount
            );
            console.log(selectedCount);
            if (selectedCount > 0) {
                addNewGoodsInvoicesItem.removeAttr('disabled');
            } else {
                addNewGoodsInvoicesItem.attr('disabled', '');
            }
        };

        // додаємо обробник події на зміну вибраних рядків у таблиці jqxGrid
        table.on('rowselect rowunselect', function () {
            updateSelectedCount();
        });

        addNewGoodsInvoicesItem.on('click', function () {});
    });
}
