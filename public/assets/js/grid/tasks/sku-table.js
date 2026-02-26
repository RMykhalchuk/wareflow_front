import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { getLocalizedText } from '../../localization/document/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';
import { toggleTableVisibility } from '../document/arrival/preview-document-sku-table.js';
import { initTaskTerminalLeftoversGrid } from './leftovers-by-sku-table.js';
import { refreshProgress } from '../../entity/document/document-leftovers.js';

$(document).ready(function () {
    let table = $('#sku-table');
    let isRowHovered = false;
    let isTestMode = true;
    let gridInitialized = false;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    const taskId = $('#task-container').data('id');
    const documentId = $('#task-container').data('document-id');

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },

        // name
        { name: 'name_title', type: 'string' },
        { name: 'name_barcode', type: 'string' },
        { name: 'name_manufacturer', type: 'string' },
        { name: 'name_category', type: 'string' },
        { name: 'name_brand', type: 'string' },

        { name: 'package', type: 'string' },
        { name: 'quantity', type: 'number' },

        { name: 'leftovers_current', type: 'number' },
        { name: 'leftovers_max', type: 'number' },
    ];

    function getUrl() {
        let url = window.location.href;

        const documentId = url.split('/').pop();

        return (
            window.location.origin +
            languageBlock +
            '/document/sku/table/filter?document_id=' +
            documentId
        );
    }

    var customData = [];

    // Якщо дані приходять з Blade
    if (window.tableData && Array.isArray(window.tableData)) {
        customData = window.tableData.map((item) => ({
            id: item.id || '', // або item.id
            local_id: item.local_id || '', // можна адаптувати під свої дані
            name_title: item.name || '-',
            name_barcode: item.barcode || '-',
            name_manufacturer: item.manufacturer || '-',
            name_category: item.country || '-', // у тебе немає category, але є country
            name_brand: item.brand || '-',
            package: item.unit || '-',
            quantity: item.quantity || 0,
            // поки що тестове значення
        }));
    } else {
        customData = [];
    }

    // ======= Джерело даних =======
    let source = isTestMode
        ? {
              datatype: 'array',
              datafields: dataFields,
              localdata: customData,
          }
        : {
              datatype: 'json',
              datafields: dataFields,
              url: getUrl(),
              root: 'data',
              beforeprocessing: function (data) {
                  source.totalrecords = data.total;
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

    // Зберігаємо adapter у DOM, щоб обробник кліку мав доступ
    table.data('adapter', dataAdapter);

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
        selectionMode: 'checkbox',
        enablehover: false,
        columnsreorder: false,
        autoshowfiltericon: true,
        pagermode: 'advanced',
        rowsheight: 150,
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
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('tablePreviewSkuID'),
                width: 50,
                editable: false,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                dataField: 'name_field',
                align: 'left',
                cellsalign: 'left',
                text: getLocalizedText('tablePreviewSkuName'),
                minwidth: 150,
                editable: false,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<div class="d-flex flex-column ps-50 gap-25">
                    <span class="fw-bolder">${rowdata.name_title}</span>
                    <span>${rowdata.name_barcode}</span>
                    <span>${rowdata.name_manufacturer}</span>
                    <span>${rowdata.name_category}</span>
                    <span>${rowdata.name_brand}</span>
                </div>`;
                },
            },
            {
                text: getLocalizedText('tablePreviewSkuPackage'),
                dataField: 'package',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value ? value : '-'}</p>`;
                },
            },
            {
                text: getLocalizedText('tablePreviewSkuCount'),
                dataField: 'quantity',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value ? value : '-'}</p>`;
                },
            },
            {
                dataField: 'leftovers_field',
                text: getLocalizedText('tablePreviewSkuLeftovers'),
                width: 200,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    const max = rowdata.quantity || 1;
                    // console.log('goodsId:', rowdata.id, 'progressData:', window.progressData);
                    // const currentProgress = 0; // можливо треба rowdata.uuid

                    const currentProgress = window.progressData[rowdata.id] || 0; // можливо треба rowdata.uuid

                    const percent = Math.round((currentProgress / max) * 100);
                    const progressColor = currentProgress < max ? 'bg-warning' : 'bg-success';

                    // якщо activity_state = 'manual' — показуємо кнопку
                    const buttonHtml = `<div
                    type="button"
                    data-bs-toggle="modal"
                    data-bs-target="#target"
                    data-sku-id="${rowdata.id}"
                    data-leftovers-name="${rowdata.name_title}"
                    data-quantity="${rowdata.quantity}"
                    data-unit="${rowdata.package}"
                    data-uuid="${rowdata.id}"
                    class="ps-50 fw-bold my-auto text-dark text-decoration-underline leftovers-btn"
                    style="cursor:pointer;"
                >
                ${getLocalizedText('tablePreviewSkuLeftoversActionView')}
                </div>`;

                    return `
            <div class="d-flex flex-column w-100 align-items-center ps-50 gap-75">
                <div class="d-flex align-items-center w-100">
                    <div class="progress w-75 me-50" style="height: 12px;">
                        <div class="progress-bar ${progressColor}" role="progressbar"
                            aria-valuenow="${percent}"
                            aria-valuemin="0" aria-valuemax="100"
                            style="width: ${percent}%"></div>
                    </div>
                    <span class="small w-25">${currentProgress}</span>
                </div>
                ${buttonHtml}
            </div>`;
                },
            },

            {
                width: '70px',
                dataField: 'action',
                align: 'center',
                cellsalign: 'center',
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

                    const button = `
                                            <button id="${buttonId}" style="padding:0" class="btn btn-table-cell" type="button" data-bs-toggle="popover">
                                                <img src="${window.location.origin}/assets/libs/jqwidget/jqwidgets/styles/images/castom-light-wms/menu_dots_vertical.svg" alt="menu_dots_vertical">
                                            </button>
                                        `;

                    var popoverOptions = {
                        html: true,
                        sanitize: false,
                        placement: 'left',
                        trigger: 'focus',
                        container: 'body',
                        content: function () {
                            return `
                                    <div id="${popoverId}">
                                        <ul class="popover-castom" class="list-unstyled">
                                            <li>
                                                <a class="dropdown-item">
                                                    Дія 1
                                                </a>
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
                        });

                    return '<div class="jqx-popover-wrapper">' + button + '</div>';
                },
            },
        ],
    });

    let listSource = [
        { label: getLocalizedText('tablePreviewSkuID'), value: 'local_id', checked: true },
        { label: getLocalizedText('tablePreviewSkuName'), value: 'name_field', checked: true },
        { label: getLocalizedText('tablePreviewSkuPackage'), value: 'package', checked: true },
        { label: getLocalizedText('tablePreviewSkuCount'), value: 'quantity', checked: true },
        {
            label: getLocalizedText('tablePreviewSkuLeftovers'),
            value: 'leftovers_field',
            checked: true,
        },
    ];

    listbox(table, listSource, '');
    hover(table, isRowHovered);

    // Обробник при кліку на комірку
    $(document).on('click', '.leftovers-btn', function () {
        const cellName = $(this).data('leftovers-name');
        const quantity = $(this).data('quantity');
        const unit = $(this).data('unit');
        const uuid = $(this).data('uuid');
        const skuId = $(this).data('sku-id');

        const $table = $('#leftovers-by-sku-table');
        // Назва комірки
        // 🔹 Встановлюємо заголовок комірки та атрибути
        $('#leftovers-name')
            .text(cellName)
            .attr('data-goods-id', uuid)
            .attr('data-quantity', quantity)
            .attr('data-leftovers-unit', unit);

        $('#leftovers-unit').text(unit);

        if (!gridInitialized) {
            initTaskTerminalLeftoversGrid(skuId);

            gridInitialized = true;
        } else {
            // 🔹 Отримуємо збережений dataAdapter
            const dataAdapter = $table.data('adapter');

            if (dataAdapter && dataAdapter._source) {
                dataAdapter._source.url = `${location.origin + languageBlock}/tasks/item/${taskId}/${skuId}/table/filter`;
                // 🔹 Викликаємо оновлення даних
                $table.jqxGrid('updatebounddata');
            }
        }
        // 🔹 Обробка після завершення завантаження
        $table.off('bindingcomplete.leftovers').on('bindingcomplete.leftovers', async function () {
            const rows = $table.jqxGrid('getrows') || [];

            // 🔹 Показати / сховати таблицю
            toggleTableVisibility($table, rows);

            // 🔹 Оновлюємо прогресбар
            await refreshProgress(documentId, true);

            // Відключаємо обробник, щоб не накопичувалися події
            $table.off('bindingcomplete.leftovers');
        });
    });
});
