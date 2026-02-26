import { pagerRenderer } from '../../components/pager.js';
import { toolbarRender } from '../../components/toolbar-advanced.js';
import { listbox } from '../../components/listbox.js';
import { getLocalizedText } from '../../../localization/leftovers/getLocalizedText.js';
import { switchLang } from '../../components/switch-lang.js';
import { hideLoader, showLoader } from '../../components/loader.js';
import { hover } from '../../components/hover.js';
import { toggleTableVisibility } from './preview-document-sku-table.js';
import { refreshProgress } from '../../../entity/document/document-leftovers.js';

let localAdditions = {}; // ключ — id залишку, значення — сума доданих кількостей

export function initLeftoversGrid(skuUniqueId, uuid, unit, isTestModeParam = false) {
    let table = $('#leftoversDataTable');
    let isRowHovered = false;
    let isTestMode = isTestModeParam; // <<< Перемикач тестового режиму

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    const documentID = $('#document-container').data('id');

    const requestUrl = `${location.origin + languageBlock}/document/outcome/leftover/${encodeURIComponent(documentID)}/${encodeURIComponent(skuUniqueId)}/table/filter`;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },

        // status object
        { name: 'status_value', map: 'status>value', type: 'number' },
        { name: 'status_name', map: 'status>name', type: 'string' },
        { name: 'status_label', map: 'status>label', type: 'string' },
        { name: 'status_label_en', map: 'status>label_en', type: 'string' },
        { name: 'status_description', map: 'status>description', type: 'string' },
        { name: 'status_is_available', map: 'status>is_available', type: 'bool' },
        { name: 'status_is_reserved', map: 'status>is_reserved', type: 'bool' },

        { name: 'batch', type: 'string' },

        { name: 'manufacture_date', type: 'string' },
        { name: 'bb_date', type: 'string' },
        { name: 'package', type: 'string' },
        { name: 'has_condition', type: 'bool' },

        // allocation object (колишній placing)
        { name: 'allocation_zone', map: 'allocation>zone', type: 'string' },
        { name: 'allocation_sector', map: 'allocation>sector', type: 'string' },
        { name: 'allocation_warehouse', map: 'allocation>warehouse', type: 'string' },
        { name: 'allocation_location', map: 'allocation>location', type: 'string' },
        { name: 'allocation_cell', map: 'allocation>cell', type: 'string' },

        // quantity object
        {
            name: 'quantity_measurement_unit_quantity',
            map: 'quantity>measurement_unit_quantity',
            type: 'number',
        },
        { name: 'quantity_measurement_unit', map: 'quantity>measurement_unit', type: 'string' },
        { name: 'quantity_package_quantity', map: 'quantity>package_quantity', type: 'number' },
        { name: 'quantity_package', map: 'quantity>package', type: 'string' },

        // responsible
        { name: 'responsible_name', map: 'responsible_name', type: 'string' },
        { name: 'responsible_date', map: 'responsible_date', type: 'string' },
        { name: 'responsible_time', map: 'responsible_time', type: 'string' },
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
              url: requestUrl,
              root: 'data',
              beforeprocessing: function (data) {
                  console.log(data);
                  myDataSource.totalrecords = data?.total || data?.length;
              },
              filter: function () {
                  showLoader();
                  table.jqxGrid('updatebounddata', 'filter');
              },
              sort: function () {
                  showLoader();
                  table.jqxGrid('updatebounddata', 'sort');
              },
          };

    showLoader(); // ПЕРЕД створенням dataAdapter

    let dataAdapter = new $.jqx.dataAdapter(myDataSource, {
        loadComplete: function (data) {
            hideLoader();
            toggleTableVisibility(table, data?.data || data || []);
            localAdditions = {}; // скидаємо при оновленні
        },
        loadError: function (xhr, status, error) {
            hideLoader();
            console.error('Load error:', status, error);
            toggleTableVisibility(table, []);
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
            var columns = table.jqxGrid('columns')?.records || [];
            var columnCount = columns.length > 0 ? columns.length : 0;

            return toolbarRender(
                statusbar,
                table,
                false,
                1,
                Math.max(columnCount - 1, 0),
                '-leftovers'
            );
        },
        columns: [
            {
                dataField: 'local_id',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('id'),
                width: 50,
                editable: false,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const rowClass = highlightRow(rowdata);

                    return `<p class="${rowClass} ps-50 align-items-center my-auto" style="color: #0c0c0c!important;">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('party'),
                dataField: 'batch',
                minwidth: 100,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const rowClass = highlightRow(rowdata);

                    return `<p class="${rowClass} ps-50 align-items-center my-auto" style="color: #0c0c0c!important;">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('manufactured'),
                dataField: 'manufacture_date',
                width: 150,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const rowClass = highlightRow(rowdata);

                    return `<p class="${rowClass} ps-50 align-items-center my-auto" style="color: #0c0c0c!important;">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('expiry'),
                dataField: 'bb_date',
                width: 150,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const rowClass = highlightRow(rowdata);

                    return `<p class="${rowClass} ps-50 align-items-center my-auto" style="color: #0c0c0c!important;">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('package'),
                dataField: 'package',
                width: 150,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const rowClass = highlightRow(rowdata);

                    return `<p class="${rowClass} ps-50 align-items-center my-auto" style="color: #0c0c0c!important;">${value}</p>`;
                },
            },
            {
                dataField: 'has_condition',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('condition'),
                width: 180,
                editable: false,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const isTrue = value === true || value === 1 || value === 'true'; // підстрахуємося
                    const badgeClass = isTrue ? 'bg-success' : 'bg-danger';
                    const rowClass = highlightRow(rowdata);

                    // Локалізація тексту
                    const language = switchLang();
                    const badgeText = isTrue
                        ? language === 'en'
                            ? 'Not damaged'
                            : 'Не пошкоджений'
                        : language === 'en'
                          ? 'Damaged'
                          : 'Пошкоджений';

                    return `
                            <div class="${rowClass} d-flex align-items-center ps-50 gap-50" style="color: #0c0c0c!important;">
                                <div class="${badgeClass} border-radius-50" style="width: 10px; height: 10px;"></div>
                                <span class="fw-bolder">${badgeText}</span>
                            </div>`;
                },
            },
            {
                dataField: 'allocation',
                text: getLocalizedText('placing'),
                width: 250,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const place = rowdata || {};
                    const rowClass = highlightRow(rowdata);

                    return `<div class="${rowClass} flex-column justify-content-center align-items-start ps-50 gap-25" style="color: #0c0c0c!important;">
            <div class="text-dark fw-bolder">${place.allocation_warehouse || '-'}</div>
            <div>${place.allocation_zone || '-'}</div>
            <div>${place.allocation_sector || '-'}</div>
            <div>${place.allocation_location || '-'}</div>
            <div>${place.allocation_cell || '-'}</div>

        </div>`;
                },
            },
            {
                dataField: 'container_field',
                text: 'Контейнер',
                width: 250,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const place = rowdata || {};
                    const rowClass = highlightRow(rowdata);

                    return `<div class="${rowClass} flex-column align-items-start ps-50 gap-25" style="color: #0c0c0c!important;">
            <div class="text-dark fw-bolder my-auto">${place.container_name || '-'}</div>
        </div>`;
                },
            },

            {
                dataField: 'quantity_field',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('quantity'),
                width: 150,
                editable: false,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    // value вже об'єкт
                    const qty = rowdata || {};
                    const rowClass = highlightRow(rowdata);

                    // <span>${qty.area} ${getLocalizedText('areaUnit')}</span>;
                    // <span>${qty.pallets} ${getLocalizedText('palletsUnit')}</span>;

                    return `<div class="${rowClass} d-flex flex-column align-items-start justify-content-center gap-50 ps-50 gap-25" style="color: #0c0c0c!important;">
                                <span>${qty.quantity_measurement_unit_quantity}  ${qty.quantity_measurement_unit_quantity}</span>
                                <span>(${qty.quantity_package_quantity} ${qty.quantity_package})</span>
                            </div>`;
                },
            },
            {
                dataField: 'sample_field',
                align: 'left',
                cellsalign: 'center',
                text: 'Відібрати',
                width: 150,
                editable: false,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const buttonId = `sample-btn-${rowdata.uid}`;
                    const popoverId = `sample-popover-${rowdata.uid}`;
                    const rowClass = highlightRow(rowdata);
                    const id = rowdata.id;

                    // 🔹 Чи є локально збережене значення
                    const localExists = localAdditions[id];
                    const isEditing = !!localExists || !!rowdata.leftover_id;
                    const addedQty = localExists ? localExists.quantity : null;

                    setTimeout(() => {
                        const $btn = $('#' + buttonId);

                        $(document).off('click', '#' + buttonId);
                        $(document).on('click', '#' + buttonId, function (e) {
                            e.preventDefault();

                            // Ховаємо всі інші поповери
                            $('.btn[data-bs-toggle="popover"]').not(this).popover('hide');

                            // === Створюємо поповер ===
                            // всередині $(document).on('click', '#' + buttonId, function (e) { ... })
                            const $button = $(this);

                            // dispose попереднього інстансу, щоб не було старих підв'язок
                            $button.popover('dispose');

                            // створюємо поповер
                            $button.popover({
                                html: true,
                                sanitize: false,
                                placement: 'bottom',
                                trigger: 'manual',
                                container: '.modal',
                                content: `
        <div id="${popoverId}" class="row mx-0" style="width:270px;">
            <div class="mb-2 col-12">
                <select class="select2" id="package-outcome-${rowdata.id}" data-placeholder="Оберіть пакування" name="package-outcome">
                    <option value=""></option>
                </select>
            </div>
            <div class="mb-2 col-12">
                <input type="number" class="form-control" id="quantity-outcome-${rowdata.id}" name="quantity-outcome" placeholder="Вкажіть кількість" />
            </div>
            <div class="d-flex justify-content-end gap-1 col-12">
                <button type="button" class="btn btn-outline-secondary cancel-btn">Скасувати</button>
                <button type="button" class="btn btn-primary sample-save">${isEditing ? 'Оновити' : 'Додати'}</button>
            </div>
        </div>
    `,
                            });

                            // Показати (triggers shown.bs.popover)
                            $button.popover('show');

                            // one-time handler — спрацьовує коли popover вже вставлено в DOM
                            $button.one('shown.bs.popover', async function () {
                                const btnEl = document.getElementById(buttonId);
                                const $popover = $('#' + popoverId);
                                const $select = $popover.find(`#package-outcome-${rowdata.id}`);
                                const $quantityInput = $popover.find(
                                    `#quantity-outcome-${rowdata.id}`
                                );

                                // Ініціалізуємо select2 вже коли елемент є в DOM
                                $select.select2({ dropdownParent: $popover, width: '100%' });

                                // Завантаження пакувань
                                try {
                                    const res = await fetch(
                                        `${languageBlock}/package/leftovers/${rowdata.id}`
                                    );
                                    const data = await res.json();
                                    if (data?.data) {
                                        $select.empty().append('<option value=""></option>');
                                        data.data.forEach((item) => {
                                            const optionText = `${item.package.name} (Доступно: ${item.available_quantity})`;
                                            $select.append(
                                                new Option(
                                                    optionText,
                                                    item.package.id,
                                                    false,
                                                    false
                                                )
                                            );
                                        });
                                    }
                                } catch (err) {
                                    console.error('Помилка отримання пакувань:', err);
                                }

                                // підстановка існуючих значень (локально або з rowdata)
                                if (localExists) {
                                    $select.val(localExists.packageId).trigger('change');
                                    $quantityInput.val(localExists.quantity);
                                } else if (isEditing && rowdata.package_id) {
                                    $select.val(rowdata.package_id).trigger('change');
                                    $quantityInput.val(rowdata.quantity || '');
                                }

                                // Сильніша функція, яка ховає поповер через інстанс Bootstrap
                                const hidePopover = () => {
                                    try {
                                        const inst = bootstrap.Popover.getInstance(btnEl);
                                        if (inst) inst.hide();
                                        else $button.popover('hide');
                                    } catch (err) {
                                        // fallback
                                        $button.popover('hide');
                                    }
                                };

                                // === Save handler ===
                                $popover
                                    .find('.sample-save')
                                    .off('click.localSave')
                                    .on('click.localSave', function () {
                                        const packageId = $select.val();
                                        const quantity = parseFloat($quantityInput.val()) || 0;

                                        if (!packageId || !quantity) {
                                            alert('Будь ласка, виберіть пакування та кількість.');
                                            return;
                                        }

                                        localAdditions[id] = { packageId, quantity };
                                        table.jqxGrid('refreshdata');

                                        hidePopover();
                                        console.log(
                                            `💾 Локально ${isEditing ? 'оновлено' : 'додано'}:`,
                                            localAdditions[id]
                                        );
                                    });

                                // === Cancel handler ===
                                $popover
                                    .find('.cancel-btn')
                                    .off('click.localCancel')
                                    .on('click.localCancel', function () {
                                        hidePopover();
                                    });

                                // === Клік поза поповером: навішуємо ОДИН обробник на документ ===
                                const outsideClickHandler = function (ev) {
                                    const $t = $(ev.target);
                                    const isInside = $t.closest('#' + popoverId).length > 0;
                                    const isBtn = $t.closest('#' + buttonId).length > 0;
                                    if (!isInside && !isBtn) {
                                        hidePopover();
                                    }
                                };
                                $(document).on('click.popoverOutside', outsideClickHandler);

                                // === Коли поповер захований — чистимо тимчасові обробники ===
                                btnEl.addEventListener('hidden.bs.popover', function cleanup() {
                                    // знімемо обробники прив'язані до поповера/документу
                                    $popover.find('.sample-save').off('click.localSave');
                                    $popover.find('.cancel-btn').off('click.localCancel');
                                    $(document).off('click.popoverOutside', outsideClickHandler);

                                    // знімемо цей listener
                                    btnEl.removeEventListener('hidden.bs.popover', cleanup);
                                });
                            });
                        });
                    }, 0);

                    // === Рендер кнопки з відображенням доданої кількості ===
                    return `
            <div class="${rowClass} d-flex flex-column align-items-end w-100 pe-50 justify-content-center ps-50" style="color:#0c0c0c!important;">
                <button
                    id="${buttonId}"
                    type="button"
                    class="btn btn-sm ${isEditing ? 'btn-outline-primary' : 'btn-outline-secondary'}"
                    data-bs-toggle="popover"
                >
                    ${isEditing ? 'Редагувати' : 'Додати'}
                </button>
                ${addedQty ? `<small class="text-success mt-1">Додано: ${addedQty}</small>` : ''}
            </div>
        `;
                },
            },
            {
                dataField: 'responsible_field',
                text: 'Опрацьовано',
                width: 150,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    const rowClass = highlightRow(rowdata);

                    console.log(rowdata);
                    if (!rowdata.responsible_name) {
                        return `<div class="${rowClass} align-items-center justify-content-center text-dark ps-50" style="color: #0c0c0c!important;">-</div>`;
                    }

                    return `<div class="${rowClass} d-flex flex-column ps-50" style="color: #0c0c0c!important;">
                    <a class="text-dark fw-bolder my-auto" href='#'>${rowdata.responsible_name}</a>
                    <div>${rowdata.responsible_date}</div>
                    <div>${rowdata.responsible_time}</div>
                </div>`;
                },
            },
            {
                width: '70px',
                dataField: 'action',
                align: 'center',
                cellsalign: 'center',
                text: getLocalizedText('action'),
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
                    const rowClass = highlightRow(rowdata);

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
                        container: '.modal', // <<< ось тут важливо
                        content: function () {
                            let deleteItem = '';

                            deleteItem = `
                                                <li>
                                                    <a class="dropdown-item delete-btn-outcome text-danger"
                                                       href="#"
                                                       data-leftovers-id="${rowdata.id}">
                                                        ${getLocalizedText('delete')}
                                                    </a>
                                                </li>
                                            `;

                            return `
                                    <div id="${popoverId}">
                                        <ul class="popover-castom" class="list-unstyled">
                                           ${deleteItem}
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
                                .find('.delete-btn-outcome')
                                .off('click')
                                .on('click', async function (e) {
                                    e.preventDefault();
                                    const leftoversId = $(this).data('leftovers-id');
                                    const csrf =
                                        document.querySelector('meta[name="csrf-token"]').content;

                                    // 🔸 Якщо запис є у localAdditions — видаляємо лише локально
                                    if (localAdditions[leftoversId]) {
                                        delete localAdditions[leftoversId];
                                        table.jqxGrid('refreshdata');
                                        $('#' + buttonId).popover('hide');
                                        console.log(`🗑️ Локально видалено запис ${leftoversId}`);
                                        return;
                                    }

                                    // 🔹 Інакше — стандартне серверне видалення
                                    const requestUrl = `${languageBlock}/document/outcome/leftover/${encodeURIComponent(leftoversId)}`;
                                    try {
                                        const response = await fetch(requestUrl, {
                                            method: 'DELETE',
                                            headers: {
                                                'X-CSRF-TOKEN': csrf,
                                                Accept: 'application/json',
                                            },
                                        });
                                        if (!response.ok) throw new Error('Помилка при видаленні');
                                        const data = await response.json();
                                        await refreshProgress($('#document-container').data('id'));
                                        $('#' + buttonId).popover('hide');
                                        console.log('✅ Видалено успішно:', data);
                                    } catch (error) {
                                        console.error('❌ Помилка при видаленні:', error);
                                        alert('Не вдалося видалити запис. Спробуйте ще раз.');
                                    }
                                });
                        });

                    return `<div class="jqx-popover-wrapper ${rowClass}"> ${button}</div>`;
                },
            },
        ],
    });

    let listSource = [
        { label: getLocalizedText('id'), value: 'local_id', checked: true },
        { label: getLocalizedText('party'), value: 'batch', checked: true },
        { label: getLocalizedText('manufactured'), value: 'manufacture_date', checked: true },
        { label: getLocalizedText('expiry'), value: 'bb_date', checked: true },
        { label: getLocalizedText('package'), value: 'package_field', checked: true },
        { label: getLocalizedText('condition'), value: 'has_condition', checked: true },
        { label: 'Контейнер', value: 'allocation', checked: true },
        { label: getLocalizedText('quantity'), value: 'quantity_field', checked: true },
        { label: getLocalizedText('action'), value: 'action', checked: true },
    ];

    listbox(table, listSource, '-leftovers');
    hover(table, isRowHovered);
}

const customData = [
    {
        id: '9f164485-9e9a-4f98-866f-8c114c11e52b',
        local_id: 1,
        status: {
            value: 1,
            name: 'ACTIVE',
            label: 'Активний',
            label_en: 'active',
            description: 'Товар доступний...',
            is_available: true,
            is_reserved: false,
        },
        batch: '1',
        manufacture_date: '2025-09-05',
        bb_date: '2025-09-04',
        package: '1',
        has_condition: false,
        quantity: {
            measurement_unit_quantity: 2,
            measurement_unit: 'm²',
            package_quantity: 2,
            package: '1',
        },
    },
    {
        id: '9f164485-9e9a-4f98-866f-8c114c11e52b',
        local_id: 1,
        status: {
            value: 1,
            name: 'ACTIVE',
            label: 'Активний',
            label_en: 'active',
            description: 'Товар доступний...',
            is_available: true,
            is_reserved: false,
        },
        batch: '1',
        manufacture_date: '2025-09-05',
        bb_date: '2025-09-04',
        package: '1',
        has_condition: false,
        quantity: {
            measurement_unit_quantity: 2,
            measurement_unit: 'm²',
            package_quantity: 2,
            package: '1',
        },
    },
];

function highlightRow(rowdata) {
    const id = rowdata.id;
    const baseQty = Number(rowdata.quantity_measurement_unit_quantity) || 0;

    const addition = localAdditions[id];
    const addedQty = addition ? Number(addition.quantity) : 0;

    if (!addedQty) return ''; // без підсвітки, якщо нічого не додавали

    if (addedQty === baseQty) return 'bg-light-success h-100 w-100 d-flex gap-50';
    if (addedQty < baseQty) return 'bg-light-primary h-100 w-100 d-flex gap-50';
    if (addedQty > baseQty) return 'bg-light-danger h-100 w-100 d-flex gap-50';
}
