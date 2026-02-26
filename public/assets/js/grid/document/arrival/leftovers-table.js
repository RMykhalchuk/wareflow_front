import { pagerRenderer } from '../../components/pager.js';
import { toolbarRender } from '../../components/toolbar-advanced.js';
import { listbox } from '../../components/listbox.js';
import { getLocalizedText } from '../../../localization/leftovers/getLocalizedText.js';
import { switchLang } from '../../components/switch-lang.js';
import { hideLoader, showLoader } from '../../components/loader.js';
import { hover } from '../../components/hover.js';
import { getCurrentLocaleFromUrl } from '../../../utils/getCurrentLocaleFromUrl.js';
import { toggleTableVisibility } from './preview-document-sku-table.js';
import { refreshProgress } from '../../../entity/document/document-leftovers.js';

export function initLeftoversGrid(skuUniqueId, uuid, unit, isTestModeParam = false) {
    let table = $('#leftoversDataTable');
    let isRowHovered = false;
    let isTestMode = isTestModeParam; // <<< Перемикач тестового режиму

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    const documentID = $('#document-container').data('id');

    const requestUrl = `${location.origin + languageBlock}/document/income/leftover/${encodeURIComponent(documentID)}/${encodeURIComponent(skuUniqueId)}/table/filter`;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },
        { name: 'table_id', type: 'string' },

        { name: 'batch', type: 'string' },

        { name: 'manufacture_date', type: 'string' },
        { name: 'expiration_term', type: 'string' },

        { name: 'bb_date', type: 'string' },
        { name: 'package_id', map: 'package>id', type: 'string' },

        { name: 'package_name', map: 'package>name', type: 'string' },
        { name: 'package_main_units_number', map: 'package>main_units_number', type: 'number' },
        {
            name: 'package_measurement_unit_count',
            map: 'package>measurement_unit_count',
            type: 'number',
        },

        { name: 'has_condition', type: 'bool' },
        { name: 'container_id', type: 'string' },

        { name: 'container_name', map: 'container>code', type: 'string' },
        { name: 'container_id', map: 'container>id', type: 'string' },

        { name: 'quantity', type: 'string' },
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
        rowsheight: 50,
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
                dataField: 'table_id',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('id'),
                width: 50,
                editable: false,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('party'),
                dataField: 'batch',
                minwidth: 100,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('manufactured'),
                dataField: 'manufacture_date',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('expiry'),
                dataField: 'bb_date',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('package'),
                dataField: 'package_field',
                width: 150,
                cellsrenderer: function (
                    row,
                    column,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    // value = package_name
                    // rowData.package_main_units_number = main_units_number
                    return `<p class="ps-50 my-auto">${rowdata.package_name}</p>`;
                },
            },

            {
                dataField: 'has_condition',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('condition'),
                width: 180,
                editable: false,
                cellsrenderer: function (row, column, value) {
                    const isTrue =
                        value === true || value === 1 || value === 'true' || value === 'OK'; // підстрахуємося
                    const badgeClass = isTrue ? 'bg-success' : 'bg-danger';

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
                            <div class="d-flex align-items-center ps-50 gap-50">
                                <div class="${badgeClass} border-radius-50" style="width: 10px; height: 10px;"></div>
                                <span class="fw-bolder">${badgeText}</span>
                            </div>`;
                },
            },

            {
                dataField: 'allocation',
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

                    return `<div class="d-flex flex-column ps-50 gap-25">
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

                    // <span>${qty.area} ${getLocalizedText('areaUnit')}</span>;
                    // <span>${qty.pallets} ${getLocalizedText('palletsUnit')}</span>;

                    return `<div class="d-flex ps-50 gap-25">
                                <span>${qty.package_measurement_unit_count} ${unit}</span>
                                <span>(${rowdata.quantity} ${rowdata.package_name})</span>
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
                            let editItem = '';
                            let deleteItem = '';

                            editItem = `
                                                <li>
                                                    <a class="dropdown-item edit-btn"
                                                       href="#"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#edit_leftovers"
                                                       data-leftovers-id="${rowdata.id}"
                                                       data-sku-unique-id="${skuUniqueId}"
                                                       data-sku-uuid="${uuid}"
                                                       data-batch="${rowdata.batch ?? ''}"
                                                       data-manufacture-date="${rowdata.manufacture_date ?? ''}"
                                                       data-expiration-term="${rowdata.expiration_term ?? ''}"
                                                       data-bb-date="${rowdata.bb_date ?? ''}"
                                                       data-package="${rowdata.package ?? ''}"
                                                       data-package-id="${rowdata.package_id ?? ''}"
                                                       data-has-condition="${rowdata.has_condition ?? ''}"
                                                       data-quantity="${rowdata.quantity ?? ''}"
                                                       data-container-id="${rowdata.container_id ?? ''}"

                                                       >

                                                        ${getLocalizedText('edit')}
                                                    </a>
                                                </li>

                                            `;

                            deleteItem = `
                                                <li>
                                                    <a class="dropdown-item delete-btn text-danger"
                                                       href="#"
                                                       data-leftovers-id="${rowdata.id}"
                                                       data-sku-unique-id="${skuUniqueId}">
                                                        ${getLocalizedText('delete')}
                                                    </a>
                                                </li>
                                            `;

                            return `
                                    <div id="${popoverId}">
                                        <ul class="popover-castom" class="list-unstyled">
                                           ${editItem}
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
                                .find('.delete-btn')
                                .off('click')
                                .on('click', async function (e) {
                                    e.preventDefault();

                                    const leftoversId = $(this).data('leftovers-id');
                                    const skuUniqueId = $(this).data('sku-unique-id');
                                    const csrf =
                                        document.querySelector('meta[name="csrf-token"]').content;
                                    const locale = getCurrentLocaleFromUrl();
                                    const languageBlock = locale === 'en' ? '' : `/${locale}`;
                                    const requestUrl = `${location.origin + languageBlock}/document/income/leftover/${encodeURIComponent(leftoversId)}`;

                                    console.log('🗑️ Delete clicked:', skuUniqueId);

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
                                        console.log('✅ Видалено успішно:', data);

                                        // 🕐 Чекаємо завершення оновлення таблиці залишків
                                        await refreshProgress($('#document-container').data('id'));

                                        // Закриваємо поповер
                                        $('#' + buttonId).popover('hide');
                                        console.log('🔄 Таблицю залишків оновлено');
                                    } catch (error) {
                                        console.error('❌ Помилка при видаленні:', error);
                                        alert('Не вдалося видалити запис. Спробуйте ще раз.');
                                    }
                                });
                        });

                    return '<div class="jqx-popover-wrapper">' + button + '</div>';
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

    // ======== Обробка кліку на "Редагувати" ========
    $(document)
        .off('click', '.edit-btn')
        .on('click', '.edit-btn', function (e) {
            e.preventDefault();

            const leftoversId = $(this).data('leftovers-id');
            const skuUniqueId = $(this).data('sku-unique-id');
            const skuUUID = $(this).data('sku-uuid');
            const packagesId = $(this).data('package-id');
            const hasCondition = $(this).data('has-condition'); // '1' або '0'

            const expirationTerm = $(this).data('expiration-term');
            const expirationTermSelect = $('#edit_expiration_term');
            expirationTermSelect.val(expirationTerm).trigger('change');

            $('#edit_leftovers_submit').data('id', leftoversId);
            $('#edit_leftovers_submit').data('sku-unique-id', skuUniqueId);

            const $goodsSelect = $('#edit_goods_id');
            const $packageSelect = $('#edit_packages_id');

            // === 1️⃣ Встановлюємо товар у Select2 ===
            const newOption = new Option('Товар-' + skuUUID, skuUUID, true, true);
            $goodsSelect.append(newOption).trigger('change');

            // 🔥 Імітуємо подію вибору товару, щоб розблокувати Select пакування
            $goodsSelect.trigger({
                type: 'select2:select',
                params: { data: { id: skuUUID } },
            });

            // === 2️⃣ Чекаємо, поки Select пакування стане активним, тоді виставляємо значення ===
            const waitForPackages = setInterval(() => {
                if (!$packageSelect.prop('disabled')) {
                    clearInterval(waitForPackages);

                    if (packagesId) {
                        if ($packageSelect.find(`option[value="${packagesId}"]`).length === 0) {
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
            }, 400); // перевіряємо кожні 100мс

            // === 3️⃣ Встановлюємо стан (Select: true/false) ===
            const conditionValue =
                hasCondition === '1' || hasCondition === 1 || hasCondition === true
                    ? 'true'
                    : 'false';
            $('#edit_condition').val(conditionValue).trigger('change');

            // === 4️⃣ Решта полів ===
            $('#edit_batch').val($(this).data('batch'));
            $('#edit_manufacture_date').val($(this).data('manufacture-date'));
            $('#edit_expiration_term').val($(this).data('expiration-term'));

            $('#edit_bb_date').val($(this).data('bb-date'));
            $('#edit_quantity').val($(this).data('quantity'));
            $('#edit_container_registers_id').val($(this).data('container-id')).trigger('change');
        });
}

let customData = [];
