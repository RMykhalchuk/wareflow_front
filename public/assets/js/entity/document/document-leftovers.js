import { sendRequestBase } from '../../utils/sendRequestBase.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';
import { validateGeneric } from '../../utils/request/validate.js';
import { redirectWithLocale } from '../../utils/redirectWithLocale.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();
    const languageBlock = locale === 'en' ? '' : `${locale}/`;
    const document_id = $('#document-container').data('id');

    // === Додавання залишків ===
    $('#add_leftovers_submit').on('click', async function () {
        const documentID = $('#document-container').data('id');

        const skuUniqueId = $(this).data('sku-unique-id');
        if (!skuUniqueId) {
            console.error('❌ skuUniqueId не знайдено!');
            return;
        }

        const requestAdd = `${languageBlock}document/income/leftover/${encodeURIComponent(documentID)}/${encodeURIComponent(skuUniqueId)}`;
        const formData = getAddLeftoversData();

        // ✅ Викликаємо sendRequestBase з callback
        await sendRequestBase(requestAdd, formData, validateAddLeftovers, async () => {
            // === CALLBACK після успішного виконання ===

            // 1️⃣ Закриваємо модалку
            const modalEl = document.getElementById('add_leftovers');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }

            // 2️⃣ Оновлюємо таблицю залишків
            await refreshProgress(documentID);

            // 3️⃣ Очищаємо форму
            clearAddLeftoversForm();

            console.log('✅ Залишки успішно додано, форму очищено, таблицю оновлено.');
        });
    });

    // Редагування залишків
    $('#edit_leftovers_submit').on('click', async function () {
        const documentID = $('#document-container').data('id');

        const skuUniqueId = $(this).data('sku-unique-id');
        if (!skuUniqueId) {
            console.error('❌ skuUniqueId не знайдено!');
            return;
        }

        const leftoversId = $(this).data('id'); // ✅ витягуємо ID із data-id
        if (!leftoversId) {
            console.error('leftoversId не знайдено!');
            return;
        }
        const formData = getEditLeftoversData();
        formData.append('_method', 'PUT');

        const requestEdit = `${languageBlock}document/income/leftover/${encodeURIComponent(leftoversId)}`;

        await sendRequestBase(requestEdit, formData, validateEditLeftovers, async () => {
            // === CALLBACK після успішного виконання ===

            // 1️⃣ Закриваємо модалку
            const modalEl = document.getElementById('edit_leftovers');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }
            // 2️⃣ Оновлюємо таблицю залишків
            await refreshProgress(documentID);
        });
    });

    const items = document.querySelectorAll('.js-doc-dropdown-item');

    items.forEach((item) => {
        item.addEventListener('click', async (e) => {
            e.preventDefault();
            const type = item.dataset.type;

            let formData = getData();
            formData.append('state', type);

            await sendRequestBase(
                `${languageBlock}document/state/${document_id}`,
                formData,
                null,
                function () {
                    redirectWithLocale(`/document/${document_id}`);
                }
            );
        });
    });

    //todo
    // --- Скасувати ---
    $('#cancelExecution').click(async function () {
        let formData = getDataCancel();

        await sendRequestBase(
            `${languageBlock}tasks/${document_id}/cancel`,
            formData,
            null,
            function () {
                redirectWithLocale(`/tasks/${document_id}`);
            }
        );
    });
});

// === Функція для збору даних з модалки "Додати залишки" ===
function getAddLeftoversData() {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const formData = new FormData();
    formData.append('_token', csrf);

    const getVal = (id) => $(`#${id}`).val();

    formData.append('batch', getVal('batch'));
    formData.append('has_condition', getVal('condition') === 'true' ? 1 : 0);
    formData.append('expiration_term', getVal('expiration_term'));
    formData.append('manufacture_date', getVal('manufacture_date'));
    formData.append('bb_date', getVal('bb_date'));
    formData.append('package_id', getVal('packages_id'));
    formData.append('quantity', getVal('quantity'));
    formData.append('container_id', getVal('container_registers_id'));

    return formData;
}

// === Функція для очищення форми "Додати залишки" ===
function clearAddLeftoversForm() {
    const fields = [
        'goods_id',
        'batch',
        'condition',
        'expiration_term',
        'manufacture_date',
        'bb_date',
        'packages_id',
        'quantity',
        'container_registers_id',
    ];

    fields.forEach((id) => {
        const el = $(`#${id}`);
        if (el.is('select')) {
            el.prop('selectedIndex', 0).trigger('change'); // для select2 або звичайного select
        } else {
            el.val(''); // для input/textarea
        }
    });
}

// === Функція для збору даних з модалки "Редагувати залишки" ===
function getEditLeftoversData() {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const formData = new FormData();
    formData.append('_token', csrf);

    const getVal = (id) => $(`#${id}`).val();

    formData.append('batch', getVal('edit_batch'));
    formData.append('has_condition', getVal('edit_condition') === 'true' ? 1 : 0);
    formData.append('expiration_term', getVal('edit_expiration_term'));
    formData.append('manufacture_date', getVal('edit_manufacture_date'));
    formData.append('bb_date', getVal('edit_bb_date'));
    formData.append('package_id', getVal('edit_packages_id'));
    formData.append('quantity', getVal('edit_quantity'));
    formData.append('container_id', getVal('edit_container_registers_id'));

    return formData;
}

// === Валідація для "Додати залишки" ===
async function validateAddLeftovers(response) {
    const fieldGroups = [
        {
            fields: [
                'batch',
                'condition',
                'expiration_term',
                'manufacture_date',
                'bb_date',
                'packages_id',
                'quantity',
                'container_registers_id',
            ],
            container: '#add_leftovers_error_msg',
        },
    ];

    await validateGeneric(response, fieldGroups, '#add_leftovers_error_msg');
}

// === Валідація для "Редагувати залишки" ===
async function validateEditLeftovers(response) {
    const fieldGroups = [
        {
            fields: [
                'edit_batch',
                'edit_condition',
                'edit_expiration_term',
                'edit_manufacture_date',
                'edit_bb_date',
                'edit_packages_id',
                'edit_quantity',
                'edit_container_registers_id',
            ],
            container: '#edit_leftovers_error_msg',
        },
    ];

    await validateGeneric(response, fieldGroups, '#edit_leftovers_error_msg');
}

// 2️⃣ Функція оновлення прогресу з API
export async function refreshProgress(documentID, isTask = false) {
    try {
        const locale = getCurrentLocaleFromUrl();
        const languageBlock = locale === 'en' ? '' : `/${locale}`;
        const response = await fetch(
            `${location.origin + languageBlock}/document/income/leftover/${encodeURIComponent(documentID)}/progress`,
            {
                headers: { Accept: 'application/json' },
            }
        );
        const result = await response.json();

        // оновлюємо глобальний об’єкт
        window.progressData = { ...window.progressData, ...result.data };

        if (!isTask) {
            // 🔥 примусово оновлюємо таблиці
            $('#leftoversDataTable').jqxGrid('updatebounddata');
            $('#previewSkuDataTable').jqxGrid('updatebounddata');
        }

        // 🔹 оновлюємо модалку
        updateLeftoversModal();
    } catch (err) {
        console.error('❌ Помилка оновлення прогресу:', err);
    }
}

export function updateLeftoversModal() {
    const nameEl = document.getElementById('leftovers-name');
    const goodsId = nameEl.dataset.goodsId;
    const quantity = parseFloat(nameEl.dataset.quantity) || 1;
    const current = window.progressData[goodsId] || 0;
    const percent = Math.round((current / quantity) * 100);

    const bar = document.getElementById('leftovers-progress-bar');
    if (bar) {
        bar.style.width = percent + '%';
        bar.setAttribute('aria-valuenow', percent);
        bar.className = current < quantity ? 'progress-bar bg-warning' : 'progress-bar bg-success';
    }

    document.getElementById('leftovers-current').textContent = current;
    document.getElementById('leftovers-max').textContent = quantity;
}

$('#target').on('hide.bs.modal', function () {
    document.activeElement?.blur();
});

$('#add_leftovers').on('hide.bs.modal', function () {
    document.activeElement?.blur();
});

$('#edit_leftovers').on('hide.bs.modal', function () {
    document.activeElement?.blur();
});

function getData() {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    let formData = new FormData();
    formData.append('_token', csrf);
    return formData;
}

function getDataCancel() {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    let formData = new FormData();
    formData.append('_token', csrf);
    return formData;
}
