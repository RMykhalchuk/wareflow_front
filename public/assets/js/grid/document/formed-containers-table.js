import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { getLocalizedText } from '../../localization/document/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';

$(document).ready(function () {
    let table = $('#formed-containers-table');
    let isRowHovered = false;
    let isTestMode = false;
    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;
    const documentID = $('#document-container').data('id');

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },

        { name: 'code', map: 'code', type: 'string' },

        { name: 'created_at', type: 'date' },
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
              url:
                  window.location.origin +
                  languageBlock +
                  `/document/${documentID}/containers/table/filter`,

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
        },
        sortable: false,
        columnsResize: false,
        filterable: false,
        filtermode: 'default',
        localization: getLocalization(language),
        selectionMode: 'none',
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
            return toolbarRender(statusbar, table, false, 1, columnCount - 1, '-formedContainers');
        },

        columns: [
            {
                dataField: 'local_id',
                text: getLocalizedText('container.id'),
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
            {
                dataField: 'code',
                text: getLocalizedText('container.code'),
                minwidth: 250,
                cellsrenderer: (row, column, value) =>
                    `<p class="ps-50 my-auto">${value ? value : '-'}</p>`,
            },
            {
                dataField: 'created_at',
                text: getLocalizedText('container.created'),
                width: 200,
                cellsrenderer: (row, column, value) => {
                    if (!value) {
                        return `<div class="ps-50">-</div>`;
                    }

                    const d = new Date(value);

                    const date = d.toLocaleDateString(language, {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                    });

                    const time = d.toLocaleTimeString(language, {
                        hour: '2-digit',
                        minute: '2-digit',
                    });

                    return `
                        <div class="d-flex flex-column ps-50">
                        <div>${date}</div>
                        <div>${time}</div>
                        </div>
                           `;
                },
            },
        ],
    });

    let listSource = [
        { label: getLocalizedText('container.id'), value: 'id', checked: true },
        { label: getLocalizedText('container.code'), value: 'code', checked: true },
        { label: getLocalizedText('container.created'), value: 'created_at', checked: true },
    ];

    listbox(table, listSource, '-formedContainers');
    hover(table, isRowHovered);
});

// ======= ТЕСТОВІ ДАНІ =======
let customData = [
    {
        id: '1',
        local_id: '1',
        code: 'K12334',
        created_at: '2025-08-05T12:00:00.000Z',
    },
];
