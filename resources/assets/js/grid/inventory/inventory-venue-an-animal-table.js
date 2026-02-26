import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { getLocalizedText } from '../../localization/inventory/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';
import { toggleTableVisibility } from '../document/arrival/preview-document-sku-table.js';

export function initVenueAnimalGrid(cellId, isTestModeParam = false) {
    let table = $('#inventory-venue-an-animal-table');
    let isTestMode = isTestModeParam; // <<< Перемикач тестового режиму

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    const requestUrl = `${location.origin + languageBlock}/inventory/${encodeURIComponent(cellId)}/leftovers`;

    const inventoryStatusId = $('#inventory-container').data('id-status');

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },

        // name
        { name: 'name_title', map: 'name>title', type: 'string' },
        { name: 'name_barcode', map: 'name>barcode', type: 'string' },
        { name: 'name_manufacturer', map: 'name>manufacturer', type: 'string' },
        { name: 'name_category', map: 'name>category', type: 'string' },
        { name: 'name_brand', map: 'name>brand', type: 'string' },

        { name: 'party', type: 'string' },
        { name: 'manufactured', type: 'string' },
        { name: 'expiry', type: 'string' },
        { name: 'package', type: 'string' },
        { name: 'condition', type: 'string' },

        { name: 'current_leftovers', type: 'number' },
        { name: 'leftovers_erp', type: 'number' },
        { name: 'divergence', type: 'string' },

        // responsible
        { name: 'responsible_name', map: 'responsible_name', type: 'string' },
        { name: 'responsible_date', map: 'responsible_date', type: 'string' },
        { name: 'responsible_time', map: 'responsible_time', type: 'string' },

        { name: 'real', type: 'bool' },
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
                  myDataSource.totalrecords = data?.total || 0;
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
        loadComplete: function (data) {
            hideLoader();
            toggleTableVisibility(table, data?.data || data || []);
        },
        loadError: function (xhr, status, error) {
            hideLoader();
            toggleTableVisibility(table, []);
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
                '-venue-an-animal'
            );
        },
        columns: [
            {
                dataField: 'id',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('viewAnimal.id'),
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
                text: getLocalizedText('viewAnimal.name'),
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
                text: getLocalizedText('viewAnimal.party'),
                dataField: 'party',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('viewAnimal.manufactured'),
                dataField: 'manufactured',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('viewAnimal.expiry'),
                dataField: 'expiry',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                text: getLocalizedText('viewAnimal.unit'),
                dataField: 'package',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value}</p>`;
                },
            },
            {
                dataField: 'condition',
                align: 'left',
                cellsalign: 'center',
                text: getLocalizedText('viewAnimal.condition'),
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
                text: getLocalizedText('viewAnimal.current_leftovers'),
                dataField: 'current_leftovers',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value ? value : '-'}</p>`;
                },
            },
            {
                text: getLocalizedText('viewAnimal.leftovers_erp'),
                dataField: 'leftovers_erp',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    return `<p class="ps-50 my-auto">${value ? value : '-'}</p>`;
                },
            },
            {
                text: getLocalizedText('viewAnimal.divergence'),
                dataField: 'divergence',
                width: 150,
                cellsrenderer: function (row, column, value) {
                    const strValue = value?.toString?.() ?? '';
                    const cellClass =
                        strValue.startsWith('+') || strValue.startsWith('-')
                            ? 'bg-light-danger '
                            : '';
                    return `<div class="flex w-100 h-100 ps-50 align-content-center ${cellClass}"><span class="text-dark">${value ? value : '-'}</span></div>`;
                },
            },
            {
                dataField: 'responsible_field',
                text: getLocalizedText('viewAnimal.responsible'),
                width: 200,
                cellsrenderer: (row, column, value, defaulthtml, columnproperties, rowdata) => {
                    // console.log(rowdata);
                    if (!rowdata.responsible_name) {
                        return `<div class="text-dark ps-50">-</div>`;
                    }

                    return `
                                <div class="d-flex flex-column ps-50">
                                     <a
                                        class="text-dark fw-bolder my-auto d-inline-block text-truncate"
                                        title="${rowdata.responsible_name}"
                                        href="#"
                                        style="max-width: 200px;"
                                    >
                                        ${rowdata.responsible_name}
                                    </a>
                                    <div>${rowdata.responsible_date}</div>
                                    <div>${rowdata.responsible_time}</div>
                                </div>
                            `;
                },
            },
            {
                width: '70px',
                dataField: 'action',
                align: 'center',
                cellsalign: 'center',
                text: getLocalizedText('viewAnimal.action.name'),
                renderer: function () {
                    return '<div></div>';
                },
                filterable: false,
                sortable: false,
                id: 'action',
                cellClassName: 'action-table-drop ',
                className: 'action-table',
                hidden: inventoryStatusId !== 2 && inventoryStatusId !== 8, // показує для IN_PROGRESS та PENDING

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
                        container: '.js-table-popover-modal',
                        content: function () {
                            let correctionQuantityItem = '';
                            let editItem = '';
                            let deleteItem = '';

                            const hasName =
                                rowdata.name_title &&
                                String(rowdata.name_title).trim() !== '' &&
                                String(rowdata.name_title).trim() !== '-';

                            // Якщо є назва — показуємо пункт "Корекція кількості"
                            if (hasName && rowdata.real === true) {
                                correctionQuantityItem = `
                                                            <li>
                                                                <a class="dropdown-item correction-quantity-btn"
                                                                   href="#"
                                                                   data-current-leftovers="${rowdata.leftovers_erp}"
                                                                   data-current-unit="${rowdata.package}"
                                                                   data-item-id="${rowdata.local_id}">
                                                                    ${getLocalizedText('viewAnimal.action.correctionQuantity')}
                                                                </a>
                                                            </li>
                                                        `;
                            }

                            // Якщо real === false → додаємо "Редагувати" і "Видалити"
                            if (rowdata.real === false) {
                                editItem = `
                                                <li>
                                                    <a class="dropdown-item edit-btn"
                                                       href="#"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#edit_leftovers"
                                                       data-leftovers-id="${rowdata.local_id}">
                                                        ${getLocalizedText('viewAnimal.action.edit')}
                                                    </a>
                                                </li>

                                            `;

                                deleteItem = `
                                                <li>
                                                    <a class="dropdown-item delete-btn text-danger"
                                                       href="#"
                                                       data-item-id="${rowdata.local_id}">
                                                        ${getLocalizedText('viewAnimal.action.delete')}
                                                    </a>
                                                </li>
                                            `;
                            }

                            return `
                                        <div id="${popoverId}">
                                            <ul class="popover-castom list-unstyled">
                                                ${correctionQuantityItem}
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

                            // Корекція кількості
                            $popover
                                .find('.correction-quantity-btn')
                                .off('click')
                                .on('click', async function (e) {
                                    e.preventDefault();

                                    const currentLeftovers = $(this).data('current-leftovers');
                                    const currentUnit = $(this).data('current-unit');

                                    const itemId = $(this).data('item-id');

                                    $('#leftovers-quantity').text(
                                        currentLeftovers
                                            ? `${currentLeftovers} ${currentUnit}`
                                            : '-'
                                    );

                                    $('#correction_quantity').data('ctx', { itemId });

                                    // === 1. Отримуємо доступні пакування ===
                                    await loadPackageInfo(itemId);

                                    $('#correction_quantity').modal('show');
                                });

                            // Видалення
                            $popover
                                .find('.delete-btn')
                                .off('click')
                                .on('click', function (e) {
                                    e.preventDefault();

                                    const itemId = $(this).data('item-id');
                                    const csrf =
                                        document.querySelector('meta[name="csrf-token"]').content;
                                    const locale = getCurrentLocaleFromUrl();
                                    const languageBlock = locale === 'en' ? '' : `/${locale}`;
                                    const requestUrl = `${location.origin + languageBlock}/inventory/leftovers/${encodeURIComponent(itemId)}`;

                                    console.log('🗑️ Delete clicked:', itemId);

                                    fetch(requestUrl, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': csrf,
                                            Accept: 'application/json',
                                        },
                                    })
                                        .then((response) => {
                                            if (!response.ok)
                                                throw new Error('Помилка при видаленні');
                                            return response.json();
                                        })
                                        .then((data) => {
                                            console.log('✅ Видалено успішно:', data);
                                            // Закриваємо поповер
                                            $('#' + buttonId).popover('hide');

                                            // 2️⃣ Оновлюємо таблицю залишків
                                            const table = $('#inventory-venue-an-animal-table');
                                            table.jqxGrid('updatebounddata');
                                        })
                                        .catch((error) => {
                                            console.error('❌ Помилка при видаленні:', error);
                                            alert('Не вдалося видалити запис. Спробуйте ще раз.');
                                        });
                                });
                        });

                    return '<div class="jqx-popover-wrapper">' + button + '</div>';
                },
            },
        ],
    });

    let listSource = [
        { label: getLocalizedText('viewAnimal.id'), value: 'local_id', checked: true },
        { label: getLocalizedText('viewAnimal.name'), value: 'name_field', checked: true },
        { label: getLocalizedText('viewAnimal.party'), value: 'party', checked: true },
        {
            label: getLocalizedText('viewAnimal.manufactured'),
            value: 'manufactured',
            checked: true,
        },
        { label: getLocalizedText('viewAnimal.expiry'), value: 'expiry', checked: true },
        { label: getLocalizedText('viewAnimal.unit'), value: 'unit', checked: true },
        { label: getLocalizedText('viewAnimal.placing'), value: 'placing_field', checked: true },
        {
            label: getLocalizedText('viewAnimal.current_leftovers'),
            value: 'current_leftovers',
            checked: true,
        },
        {
            label: getLocalizedText('viewAnimal.leftovers_erp'),
            value: 'leftovers_erp',
            checked: true,
        },
        { label: getLocalizedText('viewAnimal.divergence'), value: 'divergence', checked: true },
        {
            label: getLocalizedText('viewAnimal.responsible'),
            value: 'responsible_field',
            checked: true,
        },

        { label: getLocalizedText('viewAnimal.action.name'), value: 'action', checked: true },
    ];

    listbox(table, listSource, '-venue-an-animal');

    // ===================== SUBMIT модалки =====================
    $(document)
        .off('click', '#package_submit')
        .on('click', '#package_submit', async function (e) {
            e.preventDefault();

            const $btn = $(this);
            const $modal = $('#correction_quantity');
            const ctx = $modal.data('ctx') || {};
            const itemId = ctx.itemId;

            if (!itemId) {
                console.error('Missing itemId in modal context');
                return;
            }

            // кількість з інпута
            let qtyStr = String($('#count_of_available_packages').val() ?? '')
                .trim()
                .replace(',', '.');
            const newQty = parseFloat(qtyStr);
            if (!isFinite(newQty)) {
                console.error('Invalid quantity');
                return;
            }

            let package_id = $('#available_packages').val();

            // CSRF (Laravel)
            const csrf =
                document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                (window.Laravel && window.Laravel.csrfToken) ||
                '';

            // Ендпоінт лише з itemId → /inventory/items/{itemId}/correction-quantity
            const buildCorrectionUrl = (langBlock, itemId) =>
                `${location.origin}${langBlock}/inventory/items/${encodeURIComponent(itemId)}/correction-quantity`;

            const endpoint = buildCorrectionUrl(languageBlock, itemId);

            try {
                $btn.prop('disabled', true);
                showLoader();

                const resp = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        Accept: 'application/json',
                    },
                    body: JSON.stringify({ package_id: package_id, quantity: newQty }),
                    credentials: 'same-origin',
                });

                if (!resp.ok) {
                    const text = await resp.text();
                    throw new Error(`HTTP ${resp.status}: ${text}`);
                }

                // Закриваємо модалку
                $modal.modal('hide');

                // Оновлення гріда (спроба точкова, інакше — повне)
                try {
                    const rows = table.jqxGrid('getrows') || [];
                    const rowIndex = rows.findIndex((r) => String(r.id) === String(itemId));
                    if (rowIndex >= 0) {
                        table.jqxGrid('setcellvalue', rowIndex, 'current_leftovers', newQty);
                    } else {
                        table.jqxGrid('updatebounddata');
                    }
                } catch (_) {
                    table.jqxGrid('updatebounddata');
                }
            } catch (err) {
                console.error('Correction submit failed:', err);
            } finally {
                hideLoader();
                $btn.prop('disabled', false);
            }
        });
    // =================== /SUBMIT модалки ======================

    // ======== Обробка кліку на "Редагувати" ========
    $(document)
        .off('click', '.edit-btn')
        .on('click', '.edit-btn', async function (e) {
            e.preventDefault();

            const leftoversId = $(this).data('leftovers-id');
            console.log('📝 Edit clicked:', leftoversId);

            // зберігаємо id у кнопці "Зберегти"
            $('#edit_leftovers_submit').data('id', leftoversId);

            const locale = getCurrentLocaleFromUrl();
            const languageBlock = locale === 'en' ? '' : `/${locale}`;

            const url = `${location.origin + languageBlock}/inventory/leftovers/${encodeURIComponent(leftoversId)}`;

            try {
                const res = await fetch(url);
                if (!res.ok) throw new Error(`HTTP ${res.status}`);

                const response = await res.json();
                const mockResponse = response.data;

                if (!mockResponse) {
                    console.error('❌ Дані не знайдено у відповіді:', response);
                    return;
                }

                // === Заповнюємо поля ===
                $('#edit_goods_id').val(mockResponse.goods_id).trigger('change');

                // === Завантажуємо пакування ===
                fetch(
                    `${window.location.origin}/dictionary/packages?goods_id=${encodeURIComponent(mockResponse.goods_id)}`
                )
                    .then((res) => res.json())
                    .then((response) => {
                        const data = Array.isArray(response) ? response : response.data;
                        if (!Array.isArray(data)) {
                            console.error('Очікував масив, отримав:', response);
                            return;
                        }

                        const $packages = $('#edit_packages_id');
                        $packages.empty();

                        if (data.length > 0) {
                            data.forEach((pkg) => {
                                $packages.append(
                                    $('<option>', {
                                        value: pkg.id,
                                        text: pkg.name,
                                    })
                                );
                            });

                            // Знімаємо disabled, якщо є пакування
                            $packages.prop('disabled', false);

                            // Встановлюємо поточне значення
                            $packages.val(mockResponse.package_id).trigger('change');
                        } else {
                            $packages
                                .append(
                                    $('<option>', { value: '', text: 'Немає доступних пакувань' })
                                )
                                .prop('disabled', true);
                        }
                    })
                    .catch((err) => {
                        console.error('Помилка при завантаженні пакувань:', err);
                        $('#edit_packages_id')
                            .empty()
                            .append($('<option>', { value: '', text: 'Помилка завантаження' }))
                            .prop('disabled', true);
                    });

                // === Завантажуємо терміни придатності ===
                fetch(
                    `${window.location.origin}/dictionary/goods-expiration?goods_id=${encodeURIComponent(mockResponse.goods_id)}`
                )
                    .then((res) => res.json())
                    .then((response) => {
                        const data = Array.isArray(response) ? response : response.data;
                        const $expiration = $('#edit_expiration_term');
                        $expiration.empty();

                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach((term) => {
                                $expiration.append(
                                    $('<option>', { value: term.id, text: term.name })
                                );
                            });
                            $expiration
                                .prop('disabled', false)
                                .val(mockResponse.expiration_term ?? '')
                                .trigger('change');
                        } else {
                            $expiration
                                .append(
                                    $('<option>', { value: '', text: 'Немає доступних термінів' })
                                )
                                .prop('disabled', true);
                        }
                    })
                    .catch((err) => {
                        console.error('Помилка при завантаженні термінів придатності:', err);
                        $('#edit_expiration_term')
                            .empty()
                            .append($('<option>', { value: '', text: 'Помилка завантаження' }))
                            .prop('disabled', true);
                    });

                // === Решта полів ===
                $('#edit_batch').val(mockResponse.batch);
                $('#edit_condition')
                    .val(
                        mockResponse.condition !== undefined && mockResponse.condition !== null
                            ? String(mockResponse.condition)
                            : 'true'
                    )
                    .trigger('change');
                $('#edit_expiration_term').val(mockResponse.expiration_term ?? '');
                $('#edit_manufacture_date').val(mockResponse.manufacture_date);
                $('#edit_bb_date').val(mockResponse.bb_date);
                $('#edit_quantity').val(mockResponse.current_leftovers);
                $('#edit_container_registers_id')
                    .val(mockResponse.container_registers_id ?? '')
                    .trigger('change');

                console.log('✅ Дані завантажені в модалку:', mockResponse);

                // (необов’язково) автоматично відкриваємо модалку
                // $('#edit_leftovers').modal('show');
            } catch (err) {
                console.error('❌ Помилка при отриманні даних для редагування:', err);
            }
        });
    // ======== Обробка кліку на "Редагувати" ========

    // === Функція запиту до API package-info ===
    async function loadPackageInfo(leftoverUuid) {
        const locale = getCurrentLocaleFromUrl();
        const languageBlock = locale === 'en' ? '' : `${locale}/`;

        const url = `/${languageBlock}inventory/leftovers/${leftoverUuid}/package-info`;

        try {
            const response = await fetch(url);
            const data = await response.json();

            if (!data?.packages) return;

            const $select = $('#available_packages');
            $select.empty();

            // Очікуємо що API повертає:
            // packages: [{id, name, max_quantity}, ...]
            data.packages.forEach((p) => {
                $select.append(
                    `<option value="${p.id}" data-max="${p.quantity}">
                    ${p.name}
                </option>`
                );
            });

            // === 2. Встановлюємо дефолтне пакування + макс кількість ===
            const firstMax = data.packages[0]?.quantity ?? 0;
            $('#count_of_available_packages').val(firstMax);

            // Збережемо всі max у селекті
            $select.data('packages-list', data.packages);
        } catch (err) {
            console.error('❌ Помилка при отриманні package-info:', err);
        }
    }

    // === 3. При зміні пакування — підставляємо max ===
    // $(document).on('change', '#available_packages', function () {
    //     const max = $(this).find(':selected').data('max');
    //     if (max !== undefined) {
    //         $('#count_of_available_packages').val(max);
    //     }
    // });

    // === 4. Обмеження введення вручну ===
    //     $(document).on('input', '#count_of_available_packages', function () {
    //         const $sel = $('#available_packages');
    //         const max = $sel.find(':selected').data('max');
    //
    //         let value = parseInt($(this).val() || 0);
    //
    //         if (value > max) {
    //             $(this).val(max);
    //         }
    //     });
}

let customData = [
    {
        id: '1',
        local_id: '1',
        name: {
            title: 'Calacatta 29,7×60',
            barcode: '4820394857216',
            manufacturer: 'Cersanit',
            category: 'Будматеріали',
            brand: 'BETONHOME',
        },
        party: '20250708-01',
        manufactured: '8.8.2025',
        expiry: '8.9.2025',
        package: 'Палета',
        condition: 'OK',
        placing: {
            pallet: 'Пасік 2',
            warehouse: 'Склад 1',
            zone: 'Зона 1',
            cell: 'A01-01-01',
            code: 'K1234',
        },
        current_leftovers: 50,
        leftovers_erp: 50,
        divergence: '50',
        responsible: {
            name: 'Ігнатенко В.І',
            date: '2025.08.05',
            time: '12:00',
        },
    },
    {
        id: '2',
        local_id: '2',
        name: {
            title: 'Calacatta 29,7×60',
            barcode: '4820394857216',
            manufacturer: 'Cersanit',
            category: 'Будматеріали',
            brand: 'BETONHOME',
        },
        party: '20250708-01',
        manufactured: '8.8.2025',
        expiry: '8.9.2025',
        package: 'Палета',
        condition: 'BAD',
        placing: {
            pallet: 'Пасік 2',
            warehouse: 'Склад 1',
            zone: 'Зона 1',
            cell: 'A01-01-01',
            code: 'K1234',
        },
        current_leftovers: 40,
        leftovers_erp: 50,
        divergence: '-10',
        responsible: {
            name: 'Ігнатенко В.І',
            date: '2025.08.05',
            time: '12:00',
        },
    },
    {
        id: '3',
        local_id: '3',
        name: {
            title: 'Calacatta 29,7×60',
            barcode: '4820394857216',
            manufacturer: 'Cersanit',
            category: 'Будматеріали',
            brand: 'BETONHOME',
        },
        party: '20250708-01',
        manufactured: '8.8.2025',
        expiry: '8.9.2025',
        package: 'Палета',
        condition: 'OK',
        placing: {
            pallet: 'Пасік 2',
            warehouse: 'Склад 1',
            zone: 'Зона 1',
            cell: 'A01-01-01',
            code: 'K1234',
        },
        current_leftovers: 60,
        leftovers_erp: 50,
        divergence: '+10',
        responsible: {
            name: 'Ігнатенко В.І',
            date: '2025.08.05',
            time: '12:00',
        },
    },
    {
        id: '4',
        local_id: '4',
        name: {
            title: 'Calacatta 29,7×60',
            barcode: '4820394857216',
            manufacturer: 'Cersanit',
            category: 'Будматеріали',
            brand: 'BETONHOME',
        },
        party: '20250708-01',
        manufactured: '8.8.2025',
        expiry: '8.9.2025',
        package: 'Палета',
        condition: 'OK',
        placing: {
            pallet: 'Пасік 2',
            warehouse: 'Склад 1',
            zone: 'Зона 1',
            cell: 'A01-01-01',
            code: 'K1234',
        },
        current_leftovers: null,
        leftovers_erp: null,
        divergence: null,
        responsible: null,
    },
];
