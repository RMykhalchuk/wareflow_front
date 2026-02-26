import { getLocalizedText } from '../../localization/document/getLocalizedText.js';

const goodsSelect = $('#goods_to_kids_id');
const goodsSelectEdit = $('#edit_goods_to_kids_id');

const packageSelect = $('#package_to_kids_id');
const packageSelectEdit = $('#edit_package_to_kids_id');

function handleSelect2Change(e) {
    const data = e.params.data;
    if (!data.full) return;

    selectedItemData = {
        id: data.full.id,
        local_id: data.full.local_id,
        name: data.full.name ?? '-',
    };
}

function handleSelect2ChangePackage(e) {
    const data = e.params.data;
    if (!data.full) return;

    selectedItemDataPackage = {
        package_id: data.full.id,
        package_name: data.full.name ?? '-',
    };
}

// Прив’язка для обох select2
goodsSelect.on('select2:select', handleSelect2Change);
goodsSelectEdit.on('select2:select', handleSelect2Change);
packageSelect.on('select2:select', handleSelect2ChangePackage);
packageSelectEdit.on('select2:select', handleSelect2ChangePackage);

// --- UI рендер (показ/приховування блоків)
export function renderKitsUI(tableDataKits) {
    const tableContainer = document.getElementById('kits-table-container');

    const hasData = tableDataKits && tableDataKits.length > 0;

    if (hasData) {
        tableContainer.style.display = 'block';
    } else {
        tableContainer.style.display = 'none';
    }
}

// --- Таблиця (як у SKU)
export function renderKitsTable(data, showActions = true) {
    const container = document.getElementById('kits-table-container');
    container.innerHTML = '';

    const table = document.createElement('table');
    table.className = 'table table-hover align-middle';

    const thead = document.createElement('thead');
    thead.innerHTML = `
        <tr>
            <th>${getLocalizedText('kits_table.id') || '#'}</th>
            <th>${getLocalizedText('kits_table.name') || 'Назва'}</th>
            <th>${getLocalizedText('kits_table.package') || 'Пакування'}</th>
            <th>${getLocalizedText('kits_table.quantity') || 'Кількість'}</th>
            ${showActions ? `<th>${getLocalizedText('kits_table.actions.title') || 'Дії'}</th>` : ''}
        </tr>
    `;
    table.appendChild(thead);

    const tbody = document.createElement('tbody');
    if (!data || data.length === 0) {
        const emptyRow = document.createElement('tr');
        emptyRow.innerHTML = `
            <td colspan="${showActions ? 5 : 4}" class="text-center bg-white text-muted">
                ${getLocalizedText('no_data') || 'Немає даних'}
            </td>
        `;
        tbody.appendChild(emptyRow);
    } else {
        data.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.dataset.index = index;

            tr.innerHTML = `
                <td class="bg-white">${item.position ?? index + 1}</td>
                <td class="bg-white">${item.name ?? '-'}</td>
                <td class="bg-white">${item.package_name ?? '-'}</td>
                <td class="bg-white">${item.quantity ?? '-'}</td>
                ${
                    showActions
                        ? `<td class="bg-white">
                            <div class="d-flex gap-50 justify-content-start">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-flat-secondary edit-kit-btn"
                                    data-index="${index}"
                                    tabindex="-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#edit_kits"
                                >
                                    <i data-feather="edit-2"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-flat-danger delete-kit-btn"
                                    data-index="${index}"
                                >
                                    <i data-feather="trash-2"></i>
                                </button>
                            </div>
                          </td>`
                        : ''
                }
            `;
            tbody.appendChild(tr);
        });
    }

    table.appendChild(tbody);
    container.appendChild(table);

    if (window.feather) feather.replace();

    // Додаємо обробники подій
    tbody.addEventListener('click', (e) => {
        const editBtn = e.target.closest('.edit-kit-btn');
        const deleteBtn = e.target.closest('.delete-kit-btn');

        if (editBtn) {
            const idx = parseInt(editBtn.dataset.index);
            openEditKitModal(idx);
        }

        if (deleteBtn) {
            const idx = parseInt(deleteBtn.dataset.index);
            handleDeleteKit(idx);
        }
    });
}

// Додавання / редагування
$('#kits_submit').on('click', function () {
    const item = {
        ...selectedItemData,
        ...selectedItemDataPackage,
        quantity: $('#quantity_to_kids').val(),
        isEdit: true,
    };

    if (!item.name || !item.quantity) {
        alert(
            getLocalizedText('sku_table.quantity_required') ||
                'Будь ласка, заповніть усі обов’язкові поля'
        );
        return;
    }

    if (editIndex !== null) {
        window.tableDataKits[editIndex] = item;
        editIndex = null;
    } else {
        window.tableDataKits.push(item);
    }

    // console.log(window.tableDataKits);
    renderKitsUI(window.tableDataKits);

    updateKitsPositions();
    renderKitsTable(window.tableDataKits);
    $('#add_kits').modal('hide');
    resetKitsForm();
    editIndex = null;
    selectedItemData = null;
});

// --- Відкрити модал редагування
function openEditKitModal(index) {
    const item = window.tableDataKits[index];
    if (!item) return;

    window.editIndex = index;

    const $goodsSelect = $('#edit_goods_to_kids_id');
    const $packageSelect = $('#edit_package_to_kids_id');
    const $quantityInput = $('#edit_quantity_to_kids');

    // === 1️⃣ Встановлюємо товар у Select2 ===
    const newOption = new Option(item.name, item.id, true, true);
    $goodsSelect.append(newOption).trigger('change');

    // Встановлюємо дані для збереження
    selectedItemData = {
        id: item.id,
        local_id: item.local_id,
        name: item.name,
    };

    // 🔥 Імітуємо подію вибору товару, щоб розблокувати Select пакування
    $goodsSelect.trigger({
        type: 'select2:select',
        params: { data: { id: item.id, text: item.name } },
    });

    // === 2️⃣ Чекаємо, поки Select пакування стане активним ===
    const waitForPackages = setInterval(() => {
        if (!$packageSelect.prop('disabled')) {
            clearInterval(waitForPackages);

            if (item.package_id) {
                // Якщо опції ще немає — додаємо вручну
                if ($packageSelect.find(`option[value="${item.package_id}"]`).length === 0) {
                    const newPackOption = new Option(
                        item.package_name || `Пакування-${item.package_id}`,
                        item.package_id,
                        true,
                        true
                    );
                    $packageSelect.append(newPackOption);
                }

                // Встановлюємо пакування
                $packageSelect.val(item.package_id).trigger('change');

                // Імітуємо подію вибору пакування для Select2
                $packageSelect.trigger({
                    type: 'select2:select',
                    params: { data: { id: item.package_id } },
                });
            }
        }
    }, 100); // перевіряємо кожні 100 мс, поки select не стане активним

    // === 3️⃣ Встановлюємо кількість ===
    $quantityInput.val(item.quantity ?? '');

    // === 4️⃣ Відкриваємо модалку редагування ===
    $('#edit_kits').modal('show');
}

// --- Редагування комплекту
$('#edit_kits_submit').on('click', function () {
    if (window.editIndex === null) return;

    const item = {
        ...selectedItemData, // дані товару
        ...selectedItemDataPackage, // дані пакування
        quantity: $('#edit_quantity_to_kids').val(),
        isEdit: true,
    };

    // Валідація
    if (!item.name || !item.quantity) {
        alert(
            getLocalizedText('sku_table.quantity_required') ||
                'Будь ласка, заповніть усі обов’язкові поля'
        );
        return;
    }

    // --- Оновлюємо запис у таблиці
    window.tableDataKits[window.editIndex] = item;

    // --- Оновлюємо UI
    renderKitsUI(window.tableDataKits);
    updateKitsPositions();
    renderKitsTable(window.tableDataKits);

    // --- Закриваємо модалку та чистимо форму
    $('#edit_kits').modal('hide');
    resetKitsFormEdit();

    // --- Скидаємо стан
    window.editIndex = null;
    selectedItemData = null;
    selectedItemDataPackage = null;
});

// --- Видалення
function handleDeleteKit(index) {
    const item = window.tableDataKits[index];
    if (!item) return;

    if (confirm(getLocalizedText('kits_table.confirm_delete_kit'))) {
        window.tableDataKits.splice(index, 1);
        updateKitsPositions();
        renderKitsUI(window.tableDataKits);
        renderKitsTable(window.tableDataKits);
    }
}

// --- Позиції
export function updateKitsPositions() {
    window.tableDataKits.forEach((item, index) => {
        item.position = index + 1;
    });
}

// --- Reset форми
export function resetKitsForm() {
    $('#goods_to_kids_id').val(null).trigger('change');
    $('#package_to_kids_id').val(null).trigger('change');
    $('#quantity_to_kids').val('');
}

// --- Reset форми редагування
export function resetKitsFormEdit() {
    $('#edit_goods_to_kids_id').val(null).trigger('change');
    $('#edit_package_to_kids_id').val(null).trigger('change');
    $('#edit_quantity_to_kids').val('');
}

// === Очищення форми після закриття модалки "Додати залишки" ===
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('add_kits');

    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function () {
            resetKitsForm();
            console.log('🧹 Модалка закрита — форму очищено');
        });
    }
});

// Скидаємо фокус при відкритті модалки
$('#edit_kits').on('shown.bs.modal', function () {
    document.activeElement?.blur();
});

// Скидаємо фокус при початку закриття
$('#edit_kits').on('hide.bs.modal', function () {
    document.activeElement?.blur();
});

// Скидаємо фокус після закриття (щоб Bootstrap не повернув його назад)
$('#edit_kits').on('hidden.bs.modal', function () {
    setTimeout(() => document.activeElement?.blur(), 10);
});

// === Скидаємо фокус після confirm() при видаленні ===
$(document).on('click', '.delete-kit-btn', function () {
    const btn = this;
    setTimeout(() => btn.blur(), 10);
});
