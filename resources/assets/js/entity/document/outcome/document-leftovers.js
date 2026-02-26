import { sendRequestBase } from '../../../utils/sendRequestBase.js';
import { getCurrentLocaleFromUrl } from '../../../utils/getCurrentLocaleFromUrl.js';
import { validateGeneric } from '../../../utils/request/validate.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();
    const languageBlock = locale === 'en' ? '' : `${locale}/`;

    // === Додавання залишків ===
    $('#add_leftovers_submit').on('click', async function () {
        const documentID = $('#document-container').data('id');

        const requestAdd = `${languageBlock}document/outcome/leftover/${encodeURIComponent(documentID)}`;
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

        const leftoversId = $(this).data('id'); // ✅ витягуємо ID із data-id
        if (!leftoversId) {
            console.error('leftoversId не знайдено!');
            return;
        }

        const formData = getEditLeftoversData();
        formData.append('_method', 'PUT');

        const requestEdit = `${languageBlock}document/outcome/leftover/${encodeURIComponent(leftoversId)}`;

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
});

// === Функція для збору даних з модалки "Додати залишки" ===
function getAddLeftoversData() {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const formData = new FormData();
    formData.append('_token', csrf);

    const getVal = (id) => $(`#${id}`).val();

    formData.append('package_id', getVal('packages_id'));
    formData.append('quantity', getVal('quantity'));
    formData.append('leftover_id', '-----');
    formData.append('processing_type', 'manual');

    return formData;
}

// === Функція для очищення форми "Додати залишки" ===
function clearAddLeftoversForm() {
    const fields = ['packages_id', 'quantity'];

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

    formData.append('package_id', getVal('edit_packages_id'));
    formData.append('quantity', getVal('edit_quantity'));
    formData.append('leftover_id', '-----');
    formData.append('processing_type', 'manual');

    return formData;
}

// === Валідація для "Додати залишки" ===
async function validateAddLeftovers(response) {
    const fieldGroups = [
        {
            fields: ['packages_id', 'quantity'],
            container: '#add_leftovers_error_msg',
        },
    ];

    await validateGeneric(response, fieldGroups, '#add_leftovers_error_msg');
}

// === Валідація для "Редагувати залишки" ===
async function validateEditLeftovers(response) {
    const fieldGroups = [
        {
            fields: ['edit_packages_id', 'edit_quantity'],
            container: '#edit_leftovers_error_msg',
        },
    ];

    await validateGeneric(response, fieldGroups, '#edit_leftovers_error_msg');
}

// 2️⃣ Функція оновлення прогресу з API
export async function refreshProgress(documentID) {
    try {
        const locale = getCurrentLocaleFromUrl();
        const languageBlock = locale === 'en' ? '' : `/${locale}`;
        const response = await fetch(
            `${location.origin + languageBlock}/document/outcome/leftover/${encodeURIComponent(documentID)}/progress`,
            {
                headers: { Accept: 'application/json' },
            }
        );
        const result = await response.json();

        // оновлюємо глобальний об’єкт
        window.progressData = { ...window.progressData, ...result.data };

        // 🔥 примусово оновлюємо таблиці
        $('#leftoversDataTable').jqxGrid('updatebounddata');
        $('#previewSkuDataTable').jqxGrid('updatebounddata');

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
