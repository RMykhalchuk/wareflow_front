import { getLocalizedText } from '../../localization/document/getLocalizedText.js';
import { refreshGrid, updatePositions } from '../../entity/document/sku-table.js';
import { hideLoader, showLoader } from '../components/loader.js';
import { initGoodsAvailability } from '../../entity/document/outcome/goods-leftovers-available.js';

$(document).ready(function () {
    const table = $('#sku-table');
    const document_type_kind = $('#document-container').data('document-type-kind');

    // Ініціалізуємо дані
    window.tableData = window.tableData || [];

    // jqxGrid
    const source = {
        datatype: 'array',
        localdata: window.tableData,
        datafields: [
            { name: 'position', type: 'number' },
            { name: 'name', type: 'string' },
            { name: 'barcode', type: 'string' },
            { name: 'brand', type: 'string' },
            { name: 'supplier', type: 'string' },
            { name: 'manufacturer', type: 'string' },
            { name: 'country', type: 'string' },
            { name: 'quantity', type: 'string' },
            { name: 'unit', type: 'string' },
        ],
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
        pageable: false,
        source: dataAdapter,
        selectionmode: 'none',
        showdefaultloadelement: false,
        columnsheight: 40,
        rowsheight: 150,
        enablehover: false,
        sortable: false,
        columnsresize: false,
        columnsreorder: false,
        showToolbar: false,
        columns: [
            {
                text: getLocalizedText('sku_table.id'),
                dataField: 'position',
                width: 60,
            },
            {
                text: getLocalizedText('sku_table.name'),
                dataField: 'name',
                minwidth: 300,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `
                        <div class="d-flex flex-column ps-50">
                            <strong>${rowdata.name}</strong>
                            <span>${getLocalizedText('sku_table.barcode')}: ${rowdata.barcode}</span>
                            <span>${getLocalizedText('sku_table.brand')}: ${rowdata.brand}</span>
                            <span>${getLocalizedText('sku_table.supplier')}: ${rowdata.supplier}</span>
                            <span>${getLocalizedText('sku_table.manufacturer')}: ${rowdata.manufacturer}</span>
                            <span>${getLocalizedText('sku_table.country')}: ${rowdata.country}</span>
                        </div>`;
                },
            },
            {
                text: getLocalizedText('sku_table.quantity'),
                dataField: 'quantity',
                width: 120,
            },
            {
                text: getLocalizedText('sku_table.unit'),
                dataField: 'unit',
                width: 100,
            },
            {
                dataField: 'action',
                width: '70px',
                align: 'center',
                cellsalign: 'center',
                renderer: function () {
                    return '<div></div>';
                },
                filterable: false,
                sortable: false,
                id: 'action',
                className: 'action-table',
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const buttonId = 'button-' + row; // Унікальний для кожного рядка
                    const popoverId = 'popover-' + row;

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
                                        <button class="btn btn-link js-edit" data-index="${row}">
                                            <i data-feather="edit-2"></i> ${getLocalizedText('sku_table.actions.edit')}
                                        </button><br>
                                        <button class="btn btn-link js-remove" data-index="${row}">
                                            <i data-feather="trash-2"></i> ${getLocalizedText('sku_table.actions.delete')}
                                        </button>
                                    </div>`;
                        },
                    };

                    // Прив’язка дій безпосередньо після відкриття поповера
                    $(document)
                        .off('click', '#' + buttonId)
                        .on('click', '#' + buttonId, function () {
                            $(this).popover(popoverOptions).popover('show');
                            const $popover = $('#' + popoverId);

                            // 📝 Редагування
                            $popover
                                .find('.js-edit')
                                .off('click')
                                .on('click', function (e) {
                                    e.preventDefault();
                                    const index = $(this).data('index');
                                    const item = window.tableData[index];
                                    // console.log(item);
                                    editIndex = index;

                                    selectedItemData = item;

                                    // Заповнюємо форму у модалці редагування
                                    $('#edit_goods')
                                        .append(new Option(item.name, item.id, true, true))
                                        .trigger('change');
                                    $('#edit_goods_count').val(item.quantity);

                                    const unitSpan = document
                                        .querySelector('#edit_goods_count')
                                        ?.closest('.input-group')
                                        ?.querySelector('.input-group-text');

                                    unitSpan.textContent = item.unit;

                                    initGoodsAvailability({
                                        selectId: '#edit_goods',
                                        inputId: '#edit_goods_count',
                                        hintId: '#goods_available_hint_edit',
                                        documentTypeKind: document_type_kind,
                                    });

                                    $('#edit_table').modal('show');

                                    $('#' + buttonId).popover('hide');
                                });

                            // 🗑️ Видалення
                            $popover
                                .find('.js-remove')
                                .off('click')
                                .on('click', function (e) {
                                    e.preventDefault();
                                    const index = $(this).data('index');
                                    window.tableData.splice(index, 1);
                                    updatePositions();
                                    refreshGrid(table);
                                    $('#' + buttonId).popover('hide');
                                });
                        });

                    return '<div class="jqx-popover-wrapper">' + button + '</div>';
                },
            },
        ],
    });
});
