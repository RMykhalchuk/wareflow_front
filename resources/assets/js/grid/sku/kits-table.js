import { getLocalizedText } from '../../localization/document/getLocalizedText.js';
import { refreshGrid, updatePositions } from '../../entity/document/sku-table.js';
import { hideLoader, showLoader } from '../components/loader.js';

$(document).ready(function () {
    const table = $('#kits-table');

    // Ініціалізуємо дані
    window.tableDataKits = window.tableDataKits || [];

    // jqxGrid
    const source = {
        datatype: 'array',
        localdata: window.tableDataKits,
        datafields: [
            { name: 'position', type: 'number' },
            { name: 'name', type: 'string' },
            { name: 'package_id', type: 'string' },
            { name: 'package_name', type: 'string' },
            { name: 'quantity', type: 'string' },
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
        rowsheight: 55,
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
                        <div class="d-flex flex-column ps-50">${rowdata.name}</div>`;
                },
            },
            {
                text: 'Пакінг',
                dataField: 'package_name',
                width: 300,
            },
            {
                text: getLocalizedText('sku_table.quantity'),
                dataField: 'quantity',
                width: 300,
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
                                        <button class="btn btn-link js-edit ${rowdata.isEdit ? '' : 'disabled'}"
                                                                                               data-package-id="${rowdata.package_id ?? ''}"

                                         data-index="${row}">
                                            <i data-feather="edit-2"></i> ${getLocalizedText('sku_table.actions.edit')}
                                        </button><br>
                                        <button class="btn btn-link js-remove ${rowdata.isEdit ? '' : 'disabled'}" data-index="${row}">
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
                                    const packagesId = $(this).data('package-id');
                                    const item = window.tableDataKits[index];
                                    editIndex = index;

                                    const $goodsSelect = $('#edit_goods_to_kids_id');
                                    const $packageSelect = $('#edit_package_to_kids_id');

                                    // // Заповнюємо форму у модалці редагування
                                    // $('#edit_goods_to_kids_id')
                                    //     .append(new Option(item.name, item.name, true, true))
                                    //     .trigger('change');
                                    //
                                    // $('#edit_package_to_kids_id')
                                    //     .append(new Option(item.package, item.package, true, true))
                                    //     .trigger('change');
                                    //
                                    //

                                    // === 1️⃣ Встановлюємо товар у Select2 ===
                                    const newOption = new Option(
                                        rowdata.name,
                                        rowdata.name,
                                        true,
                                        true
                                    );
                                    $goodsSelect.append(newOption).trigger('change');

                                    // 🔥 Імітуємо подію вибору товару, щоб розблокувати Select пакування
                                    $goodsSelect.trigger({
                                        type: 'select2:select',
                                        params: { data: { id: rowdata.id } },
                                    });

                                    // === 2️⃣ Чекаємо, поки Select пакування стане активним, тоді виставляємо значення ===
                                    const waitForPackages = setInterval(() => {
                                        if (!$packageSelect.prop('disabled')) {
                                            clearInterval(waitForPackages);

                                            if (packagesId) {
                                                if (
                                                    $packageSelect.find(
                                                        `option[value="${packagesId}"]`
                                                    ).length === 0
                                                ) {
                                                    const newPackOption = new Option(
                                                        'Пакування-' + packagesId,
                                                        packagesId,
                                                        true,
                                                        true
                                                    );
                                                    $packageSelect.append(newPackOption);
                                                }

                                                $packageSelect.val(packagesId).trigger('change');

                                                // Імітуємо подію вибору для Select2
                                                $packageSelect.trigger({
                                                    type: 'select2:select',
                                                    params: { data: { id: packagesId } },
                                                });
                                            }
                                        }
                                    }, 100); // перевіряємо кожні 100мс

                                    $('#edit_quantity_to_kids').val(item.quantity);

                                    $('#edit_kits').modal('show');

                                    $('#' + buttonId).popover('hide');
                                });

                            // 🗑️ Видалення
                            $popover
                                .find('.js-remove')
                                .off('click')
                                .on('click', function (e) {
                                    e.preventDefault();
                                    const index = $(this).data('index');
                                    window.tableDataKits.splice(index, 1);
                                    updatePositions();
                                    refreshGrid(table);
                                    $('#' + buttonId).tableDataKits('hide');
                                });
                        });

                    return '<div class="jqx-popover-wrapper">' + button + '</div>';
                },
            },
        ],
    });
});
