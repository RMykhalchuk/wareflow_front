import { getLocalizedText } from '../../localization/sku/getLocalizedText.js';
import { truncate } from '../../utils/fonts/truncate.js';

// Функція рендера всієї UI, що залежить від tableData
function renderUI(tableData) {
    const tableContainer = document.getElementById('table-container');
    const addPackingBtn = document.getElementById('add_paking_button');
    const unitSkuError = document.getElementById('unit_sku_error');
    const measurementUnitSelect = document.getElementById('measurement_unit_id');

    const measurementUnitSelected =
        measurementUnitSelect && measurementUnitSelect.value.trim() !== '';

    if (!tableData || tableData.length === 0) {
        // Таблиця порожня — ховаємо її
        if (tableContainer) tableContainer.style.display = 'none';

        if (measurementUnitSelected) {
            // Якщо є вибір одиниці виміру — показуємо кнопку додавання і ховаємо помилку
            if (addPackingBtn) addPackingBtn.classList.remove('d-none');
            if (unitSkuError) unitSkuError.classList.add('d-none');
        } else {
            // Якщо вибір порожній — ховаємо кнопку і показуємо помилку
            if (addPackingBtn) addPackingBtn.classList.add('d-none');
            if (unitSkuError) unitSkuError.classList.remove('d-none');
        }
    } else {
        // Таблиця має дані — показуємо таблицю і кнопку
        if (tableContainer) tableContainer.style.display = 'block';
        if (addPackingBtn) addPackingBtn.classList.remove('d-none');
        if (unitSkuError) unitSkuError.classList.add('d-none');
    }
}

// Викликаємо renderUI при завантаженні сторінки і після будь-яких змін у tableData
document.addEventListener('DOMContentLoaded', () => {
    renderUI(window.tableData);
    updateMeasurementUnitState();
});

// Якщо є інші дії з tableData — можеш залишити їх нижче

export function renderTable(data, showActions = true) {
    const container = document.getElementById('table-container');
    container.innerHTML = '';

    const table = document.createElement('table');
    table.className = 'table table-hover';

    const thead = document.createElement('thead');
    thead.innerHTML = `
        <tr>
            <th>${getLocalizedText('name')}</th>
            <th>${getLocalizedText('type')}</th>
            <th>${getLocalizedText('barcode')}</th>
            <th>${getLocalizedText('quantity')}</th>
            <th>${getLocalizedText('weight')}</th>
            <th>${getLocalizedText('dimensions')}</th>
            ${showActions ? `<th>${getLocalizedText('actions')}</th>` : ''}
        </tr>
    `;
    table.appendChild(thead);

    const tbody = document.createElement('tbody');
    table.appendChild(tbody);

    // Побудова childrenMap
    const childrenMap = {};

    data.forEach((item) => {
        const pid = item.parent_id ?? 'root';
        const key = String(pid);

        if (!childrenMap[key]) childrenMap[key] = [];
        childrenMap[key].push(item);
    });

    function renderRows(items, level = 0) {
        items.forEach((item) => {
            const tr = document.createElement('tr');
            tr.dataset.uuid = item.id;

            if (item.parent_id) {
                tr.dataset.parentId = item.parent_id;
                tr.classList.add('has-parent', 'd-none');
            }

            const paddingLeft = `${2 + level * 2}rem`;
            const childList = childrenMap[String(item.id)] || [];

            // назва + іконка розвороту
            const titleCell = document.createElement('td');
            titleCell.className = 'expand-btn fw-bold bg-white';
            titleCell.style.paddingLeft = paddingLeft;
            titleCell.innerHTML = `
                <div class="${childList.length ? 'tree-toggle' : ''}">
                    ${item.name || '-'}
                </div>
            `;
            tr.appendChild(titleCell);

            // решта колонок
            const language = document.documentElement.lang || 'uk';
            const localizedTypeName = item.type_name[language] ?? item.type_name ?? '-';
            const cells = [
                truncate(localizedTypeName, 15),
                item.barcode || '-',
                getQuantityString(item, data) || '-',
                getWeightString(item) || '-',
                getDimensionsString(item) || '-',
            ];

            cells.forEach((val, index) => {
                const td = document.createElement('td');
                td.className = 'bg-white';
                // td.textContent = val;

                if (index === 2) {
                    // quantity
                    td.innerHTML = val || '-'; // тут відображаємо HTML
                } else {
                    td.textContent = val || '-';
                }

                // const fullText = [
                //     item.type_name,
                //     item.barcode,
                //     getQuantityString(item, data),
                //     getWeightString(item),
                //     getDimensionsString(item),
                // ][index] ?? '-';                         // ← оригінальний текст для title
                //
                // td.title = fullText;                     // ← показ при наведенні

                // 👉 title тільки для type_name (index === 0)
                if (index === 0) {
                    td.title = localizedTypeName || '-';
                }

                tr.appendChild(td);
            });

            if (showActions) {
                const td = document.createElement('td');
                td.className = 'bg-white';

                if (item.canEdit) {
                    const content = `
                                            <div class='popover-actions' data-id='${item.id}'>
                                                <button class='btn btn-sm btn-link add-below'>
                                                    <i data-feather='arrow-down'></i> ${getLocalizedText('btnAddPackingBelow')}
                                                </button>
                                            </div>
                                        `;
                    td.innerHTML = `
                                        <div class="d-flex gap-25 align-items-center">
                                            <button
                                                type="button"
                                                class="btn p-50 btn-popover"
                                                data-bs-toggle="popover"
                                                data-bs-html="true"
                                                data-bs-content="${content.replace(/"/g, '&quot;')}"
                                            >
                                                <i data-feather="plus"></i>
                                            </button>
                                            <button
                                                data-bs-toggle="modal"
                                                data-bs-target="#edit_paking"
                                                type="button"
                                                class="btn p-50 edit-packing-btn"
                                                data-id="${item.id}"
                                            >
                                                <i data-feather="edit-2"></i>
                                            </button>
                                        </div>
                                    `;
                } else {
                    td.innerHTML = `
        <div class="d-flex gap-25 align-items-center opacity-50">
            <button
                type="button"
                class="btn p-50"
                title="${getLocalizedText('actionNotAllowed')}"
            >
                <i data-feather="plus"></i>
            </button>
            <button
                type="button"
                class="btn p-50"
                title="${getLocalizedText('actionNotAllowed')}"
            >
                <i data-feather="edit-2"></i>
            </button>
        </div>
    `;
                }

                tr.appendChild(td);
            }

            tbody.appendChild(tr);

            if (childList.length) renderRows(childList, level + 1);
        });
    }

    const roots = childrenMap['root'] || [];
    renderRows(roots);
    container.appendChild(table);

    if (window.feather) feather.replace();

    // --- Ініціалізація поповерів ---
    const popoverInstances = [];
    const popoverTriggerList = [].slice.call(
        container.querySelectorAll('[data-bs-toggle="popover"]')
    );
    popoverTriggerList.forEach((popoverTriggerEl) => {
        const popoverInstance = new bootstrap.Popover(popoverTriggerEl, {
            html: true,
            sanitize: false,
        });
        popoverInstances.push(popoverInstance);

        popoverTriggerEl.addEventListener('click', () => {
            popoverInstances.forEach((inst) => {
                if (inst._element !== popoverTriggerEl) inst.hide();
            });
        });
    });

    document.addEventListener('click', (event) => {
        if (
            !event.target.closest('[data-bs-toggle="popover"]') &&
            !event.target.closest('.popover')
        ) {
            popoverInstances.forEach((inst) => inst.hide());
        }
    });

    // Tree logic
    const rows = container.querySelectorAll('tr[data-uuid]');
    rows.forEach((row) => {
        const expandBtn = row.querySelector('.expand-btn');
        if (!expandBtn) return;

        expandBtn.addEventListener('click', () => {
            const uuid = row.dataset.uuid;
            const toggleDiv = expandBtn.querySelector('.tree-toggle');
            if (toggleDiv) toggleDiv.classList.toggle('rotated');
            toggleChildren(uuid, toggleDiv?.classList.contains('rotated'));
        });
    });

    function toggleChildren(parentUuid, show) {
        const children = container.querySelectorAll(`tr[data-parent-id='${parentUuid}']`);

        children.forEach((child) => {
            if (show) {
                child.classList.remove('d-none');
            } else {
                child.classList.add('d-none');

                // ❗ при закритті батьківського — закриваємо також вкладені
                toggleChildren(child.dataset.uuid, false);
            }
        });
    }
}

document.body.addEventListener('click', function (e) {
    const target = e.target.closest('.popover-actions button');
    if (!target) return;

    const wrapper = target.closest('.popover-actions');
    const id = wrapper?.dataset.id;
    if (!id) return;

    const item = findItemById(window.tableData, id);
    if (!item) return;

    if (target.classList.contains('add-above')) {
        $('#parent_id')
            .val(item.parent_id ?? null)
            .trigger('change'); // новий має успадкувати parent старого
        $('#add_paking').data('make-parent-for', item.id); // зберігаємо кому він стане parent
        $('#add_paking').modal('show');
    }

    if (target.classList.contains('add-below')) {
        $('#parent_id').val(item.id).trigger('change');

        updatePackageCountUnit({
            parentId: item.id,
            inputId: 'package_count',
        });

        const mainUnitsInput = document.getElementById('main_units_number');
        if (mainUnitsInput) {
            mainUnitsInput.disabled = true;
            mainUnitsInput.value = '';
        }

        const packageCountInput = document.getElementById('package_count');
        if (packageCountInput) {
            packageCountInput.oninput = () => {
                recalcMainUnitsFromParent({
                    parentId: item.id,
                    packageCountInputId: 'package_count',
                    mainUnitsInputId: 'main_units_number',
                });
            };
        }

        $('#add_paking').modal('show');
    }

    // Закрити поповер
    const row = document.querySelector(`#table-container tr[data-id="${id}"]`);
    bootstrap.Popover.getInstance(row?.querySelector('.btn-popover'))?.hide();
});

// При сабміті форми додавання пакування:
$('#package_submit').on('click', function () {
    const parentId = $('#parent_id').val() || null;

    let main_units;

    if (parentId) {
        const parent = findItemById(window.tableData, parentId);
        const packageCount = parseInt($('#package_count').val()) || 0;
        main_units = parent ? parent.main_units_number * packageCount : null;
    } else {
        main_units = parseFloat($('#main_units_number').val()) || null;
    }

    const newId = generateSequentialId(window.tableData);

    const forId = $('#add_paking').data('make-parent-for'); // якщо є — значить Add Above
    const isAddAbove = Boolean(forId);

    // console.log(selectedTypeData);
    // console.log(window.tableData);

    const item = {
        id: newId,
        parent_id: parentId,
        type_id: selectedTypeData ? selectedTypeData.id : null, // ✅ тепер не буде null
        type_name: selectedTypeData ? selectedTypeData.name : null,
        name: $('#add_name').val().trim(),
        barcode: $('#barcode').val().trim(),
        main_units_number: main_units,

        // ▪ якщо Add Above → package_count = main_units_number
        // ▪ інакше беремо з input як раніше
        // package_count: isAddAbove
        //     ? main_units // <<< автоматичний розрахунок
        //     : parseInt($('#package_count').val()) || null,
        //
        package_count: parseInt($('#package_count').val()) || null,

        weight_netto: parseFloat($('#add_weight_netto').val()) || null,
        weight_brutto: parseFloat($('#add_weight_brutto').val()) || null,
        height: parseFloat($('#add_height').val()) || null,
        width: parseFloat($('#add_width').val()) || null,
        length: parseFloat($('#add_length').val()) || null,
        canEdit: true, // 🔑 завжди true при створенні на фронті
        uuid: false,
    };

    // console.log(item);

    window.tableData.push(item);

    // Якщо це було Add Above — переміщаємо старий елемент під новий
    if (isAddAbove) {
        const target = findItemById(window.tableData, forId);
        if (target) target.parent_id = item.id;
        $('#add_paking').removeData('make-parent-for');
    }

    renderUI(window.tableData);
    // Рендеримо таблицю
    renderTable(window.tableData, true);
    updateParentPackagingOptions(window.tableData);
    updateMeasurementUnitState();

    const modalEl = document.getElementById('add_paking');
    const modal = bootstrap.Modal.getInstance(modalEl);
    if (modal) modal.hide();

    const fieldsToClear = [
        '#add_name',
        '#barcode',
        '#main_units_number',
        '#package_count',
        '#add_weight_netto',
        '#add_weight_brutto',
        '#add_height',
        '#add_width',
        '#add_length',
    ];
    fieldsToClear.forEach((selector) => $(selector).val(''));

    $('#parent_id').val(null).trigger('change');
    $('#type_id').val(null).trigger('change');
});

document.getElementById('edit_condition_submit')?.addEventListener('click', () => {
    const submitBtn = document.getElementById('edit_condition_submit');
    const id = submitBtn?.dataset.editId;
    if (!id) return;

    const item = findItemById(window.tableData, id);
    if (!item) return;

    const getValue = (id) => document.getElementById(id)?.value.trim() || '';
    const getFloat = (id) => parseFloat(getValue(id)) || null;
    const getInt = (id) => parseInt(getValue(id)) || null;

    item.name = getValue('edit_name');
    item.barcode = getValue('edit_barcode');
    item.type_id = getInt('edit_type_id');
    item.type_name = selectedEditTypeData?.name ?? item.type_name;
    item.parent_id = getInt('edit_parent_id');

    if (item.parent_id) {
        const parent = findItemById(window.tableData, item.parent_id);
        const packageCount = getInt('edit_package_count');

        if (parent && packageCount) {
            item.main_units_number = parent.main_units_number * packageCount;
        }
    } else {
        item.main_units_number = getFloat('edit_main_units_number');
    }

    item.package_count = getInt('edit_package_count');

    item.weight_netto = getFloat('edit_weight_netto');
    item.weight_brutto = getFloat('edit_weight_brutto');

    item.height = getFloat('edit_height');
    item.width = getFloat('edit_width');
    item.length = getFloat('edit_length');

    // console.log(item);
    renderTable(window.tableData);

    const modal = bootstrap.Modal.getInstance(document.getElementById('edit_paking'));
    modal?.hide();
});

document.getElementById('edit_condition_delete')?.addEventListener('click', () => {
    const deleteBtn = document.getElementById('edit_condition_delete');
    const id = deleteBtn?.dataset.editId;
    if (!id) return;

    const index = window.tableData.findIndex((el) => el.id === id);
    if (index !== -1) {
        window.tableData.splice(index, 1);

        renderTable(window.tableData);
        renderUI(window.tableData);
        updateParentPackagingOptions(window.tableData);
        updateMeasurementUnitState();

        const modal = bootstrap.Modal.getInstance(document.getElementById('edit_paking'));
        modal?.hide();
    }
});

document.addEventListener('click', function (e) {
    const button = e.target.closest('.edit-packing-btn');
    if (!button) return;

    const id = button.dataset.id;
    const item = findItemById(window.tableData, id);
    if (!item) return;

    if (!item.canEdit) {
        alert(getLocalizedText('editForbidden')); // або зробити disabled inputs
        return;
    }

    const setValue = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.value = val ?? '';
    };

    setValue('edit_name', item.name);
    setValue('edit_barcode', item.barcode);
    $('#edit_type_id')
        .val(item.type_id ?? '')
        .trigger('change');
    $('#edit_parent_id')
        .val(item.parent_id ?? '')
        .trigger('change');

    updatePackageCountUnit({
        parentId: item.parent_id,
        inputId: 'edit_package_count',
    });

    const mainUnitsInput = document.getElementById('edit_main_units_number');
    setValue('edit_main_units_number', item.main_units_number);

    if (item.parent_id) {
        if (mainUnitsInput) {
            mainUnitsInput.disabled = true;
        }

        const packageCountInput = document.getElementById('edit_package_count');
        if (packageCountInput) {
            packageCountInput.oninput = () => {
                recalcMainUnitsFromParent({
                    parentId: item.parent_id,
                    packageCountInputId: 'edit_package_count',
                    mainUnitsInputId: 'edit_main_units_number',
                });
            };
        }
    } else {
        if (mainUnitsInput) {
            mainUnitsInput.disabled = false;
        }
    }

    setValue('edit_package_count', item.package_count);

    setValue('edit_weight_netto', item.weight_netto);
    setValue('edit_weight_brutto', item.weight_brutto);

    setValue('edit_height', item.height);
    setValue('edit_width', item.width);
    setValue('edit_length', item.length);

    document.getElementById('edit_condition_submit').dataset.editId = id;
    document.getElementById('edit_condition_delete').dataset.editId = id;
});

function getQuantityString(item, tableData) {
    const mainUnits = item.main_units_number;
    const packageCount = item.package_count;
    const parent = item.parent_id ? findItemById(tableData, item.parent_id) : null;

    const unitData = $('#measurement_unit_id').select2('data');
    const selectedTextMainUnit =
        unitData?.[0]?.text ||
        document.getElementById('measurement_unit_id')?.dataset?.unitText ||
        '';

    if (!mainUnits) return '-';

    // ✅ ДИТИНА → показуємо назву вищого пакування + (довга одиниця)
    if (parent) {
        const parentPackagingName = getAutoShortName(parent.name || parent.type_name || '');
        return `
            <i><u>${packageCount ?? ''} ${parentPackagingName}</u></i>
            <b>(${mainUnits} ${selectedTextMainUnit}.)</b>
        `;
    }

    // ✅ БАТЬКО → тільки основна одиниця
    return `${mainUnits} ${selectedTextMainUnit}.`;
}

function getWeightString(item) {
    const net = item.weight_netto;
    const gross = item.weight_brutto;
    if (!net && !gross) return '';

    // Для "кг" і "Нетто" створимо окремі ключі локалізації
    const kg = getLocalizedText('unit_kg'); // 'кг' / 'kg'
    const netLabel = getLocalizedText('net_label'); // 'Нетто' / 'Netto'

    return `${gross} ${kg}. (${netLabel} ${net} ${kg}.)`;
}

function getDimensionsString(item) {
    const h = item.height;
    const w = item.width;
    const l = item.length;
    if (!h && !w && !l) return '';
    return `${h}×${w}×${l}`;
}

function updateParentPackagingOptions(tableData) {
    const $selects = $('#parent_id, #edit_parent_id');
    $selects.empty();

    tableData.forEach((item) => {
        const $option = $('<option></option>').val(item.id).text(item.name);
        $selects.append($option.clone());
    });
}

// Пошук по id
function findItemById(items, id) {
    return items.find((item) => String(item.id) === String(id)) || null;
}

function generateSequentialId(data) {
    const ids = data.map((item) => Number(item.id)).filter((n) => !isNaN(n));
    const maxId = ids.length ? Math.max(...ids) : 0;
    return (maxId + 1).toString();
}

function updateMeasurementUnitState() {
    if (window.tableData.length > 0) {
        $('#measurement_unit_id').prop('disabled', true);
    } else {
        $('#measurement_unit_id').prop('disabled', false);
    }
}

// Disable add packing button if inputs are empty
function checkFields() {
    const parentId = $('#parent_id').val();

    const typeVal = $('#type_id').val();
    const nameVal = $('#add_name').val();
    const barCodeVal = $('#barcode').val();
    const quantityVal = $('#main_units_number').val();
    const packageCountVal = $('#package_count').val();
    const netWeightVal = $('#add_weight_netto').val();
    const grossWeightVal = $('#add_weight_brutto').val();
    const heightVal = $('#add_height').val();
    const widthVal = $('#add_width').val();
    const lengthVal = $('#add_length').val();

    const isFilled = [
        typeVal,
        nameVal,
        barCodeVal,
        parentId ? packageCountVal : quantityVal,
        netWeightVal,
        grossWeightVal,
        heightVal,
        widthVal,
        lengthVal,
    ].every((val) => val && val.trim() !== '');

    $('#package_submit').prop('disabled', !isFilled);
}

$('#add_paking_button, .btn[data-bs-target="#add_paking"]').on('click', function (e) {
    checkFields(); // одразу перевірка

    const $modal = $('#add_paking');

    // input
    $modal.find('input').off('input').on('input', checkFields);

    // select без select2
    $modal.find('select').not('.select2-hidden-accessible').off('change').on('change', checkFields);

    // select2 — через подію select2:select
    $modal
        .find('select.select2-hidden-accessible')
        .off('select2:select')
        .on('select2:select', checkFields);
});

$(document).on('select2:select', '#type_id', function (e) {
    const data = e.params.data;
    if (!data.full) return;

    console.log('✅ full:', data.full);

    selectedTypeData = {
        id: parseInt(data.full.id),
        name: data.full.name ?? '-',
    };
});

$(document).on('select2:select', '#edit_type_id', function (e) {
    const data = e.params.data;
    if (!data.full) return;

    selectedEditTypeData = {
        id: parseInt(data.full.id),
        name: data.full.name ?? '-',
    };
});

// Скидаємо фокус при відкритті модалки
$('#add_paking').on('shown.bs.modal', function () {
    document.activeElement?.blur();
});

// Скидаємо фокус при початку закриття
$('#edit_paking').on('hide.bs.modal', function () {
    document.activeElement?.blur();
});

// Автоочищення при закритті модалки "Add Packing"
$('#add_paking').on('hidden.bs.modal', function () {
    clearModalFields('#add_paking');
});

// Автоочищення при закритті модалки "Edit Packing"
$('#edit_paking').on('hidden.bs.modal', function () {
    clearModalFields('#edit_paking');
    selectedEditTypeData = null;
});

function recalcMainUnitsFromParent({ parentId, packageCountInputId, mainUnitsInputId }) {
    const parent = findItemById(window.tableData, parentId);
    if (!parent) return;

    const parentMainUnits = Number(parent.main_units_number);
    if (!parentMainUnits) return;

    const packageCountInput = document.getElementById(packageCountInputId);
    const mainUnitsInput = document.getElementById(mainUnitsInputId);

    if (!packageCountInput || !mainUnitsInput) return;

    const packageCount = Number(packageCountInput.value);
    if (!packageCount) {
        mainUnitsInput.value = '';
        return;
    }

    mainUnitsInput.value = parentMainUnits * packageCount;
}

// Функція для очищення полів у формі модалки
function clearModalFields(modalSelector) {
    const $modal = $(modalSelector);

    // Очистка всіх input
    $modal.find('input').val('');

    // Очистка всіх select
    $modal.find('select').val(null).trigger('change');

    // Для Select2 — скидання
    $modal.find('select.select2-hidden-accessible').val(null).trigger('change');

    // Додатково можна відключити main_units_number, якщо потрібно
    $modal.find('#main_units_number, #edit_main_units_number').prop('disabled', false);

    // При потребі скинути додаткові data-атрибути
    $modal.removeData('make-parent-for');

    // Скидання кнопки submit
    $modal.find('#package_submit').prop('disabled', true);

    resetPackageCountUnit('package_count');
    resetPackageCountUnit('edit_package_count');
}

function updatePackageCountUnit({ parentId, inputId }) {
    const parent = findItemById(window.tableData, parentId);

    let unitText;

    if (parent) {
        const fullName = parent.name || parent.type_name || '';
        unitText = getAutoShortName(fullName);
    } else {
        unitText = getLocalizedText('units_piece_singular'); // шт
    }

    const $input = $('#' + inputId);
    const $unitEl = $input.closest('.input-group').find('.input-group-text');

    if ($unitEl.length) {
        $unitEl.text(unitText);
    }
}

function resetPackageCountUnit(inputId) {
    const $input = $('#' + inputId);
    const $unitEl = $input.closest('.input-group').find('.input-group-text');

    if ($unitEl.length) {
        $unitEl.text(getLocalizedText('units_piece_singular'));
    }
}

function getAutoShortName(name) {
    if (!name) return '';

    const trimmed = name.trim().toLowerCase();

    const len = trimmed.length;

    if (len >= 3) return trimmed.slice(0, 3) + '.';
    if (len === 2) return trimmed.slice(0, 2) + '.';
    if (len === 1) return trimmed.slice(0, 1) + '.';

    return '';
}
