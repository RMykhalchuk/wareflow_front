import { sendRequestBase, sendRequestBaseWithMethod } from '../../utils/sendRequestBase.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';
import { validateGeneric } from '../../utils/request/validate.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();
    const languageBlock = locale === 'en' ? '' : `${locale}/`;

    const noopValidate = async () => true;

    $(document).on('click', '#target_submit', async function () {
        if (this.disabled) return;

        const $btn = $(this);

        const cellId =
            $btn.data('cell-id') ||
            $('#add_leftovers_submit').data('cell-id') ||
            $('#edit_leftovers_submit').data('cell-id') ||
            $('#cell_id').val() ||
            $('[name="cell_id"]').val();

        if (!cellId) {
            console.error(
                '❌ cellId (item id) not found. Provide data-cell-id on #target_submit or #add_leftovers_submit.'
            );

            return;
        }

        const requestUrl = `${languageBlock}inventory/${encodeURIComponent(cellId)}/submit`;

        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const formData = new FormData();
        formData.append('_token', csrf);
        formData.append('status', 2);

        await sendRequestBase(requestUrl, formData, noopValidate, () => {
            $('#inventory-venue-an-animal-table').jqxGrid('updatebounddata');

            const redirectTo =
                $btn.data('redirect') ||
                $('#add_leftovers_submit').data('redirect') ||
                window.location.href;

            window.location.href = redirectTo;
        });
    });

    // === Додавання залишків ===
    $('#add_leftovers_submit').on('click', async function () {
        const cellId = $(this).data('cell-id'); // ✅ витягуємо ID із data-атрибуту
        if (!cellId) {
            console.error('❌ cellId не знайдено!');
            return;
        }

        const requestAdd = `${languageBlock}inventory/${encodeURIComponent(cellId)}/leftovers`;
        const formData = getAddLeftoversData();

        // ✅ Викликаємо sendRequestBase з callback
        await sendRequestBase(requestAdd, formData, validateAddLeftovers, () => {
            // === CALLBACK після успішного виконання ===

            // 1️⃣ Закриваємо модалку
            const modalEl = document.getElementById('add_leftovers');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }

            // 2️⃣ Оновлюємо таблицю залишків
            const table = $('#inventory-venue-an-animal-table');
            table.jqxGrid('updatebounddata');

            // 3️⃣ Очищаємо форму
            clearAddLeftoversForm();

            console.log('✅ Залишки успішно додано, форму очищено, таблицю оновлено.');
        });
    });

    $('#edit_leftovers_submit').on('click', async function () {
        const leftoversId = $(this).data('id');

        if (!leftoversId) return console.error('leftoversId не знайдено!');

        const formData = getEditLeftoversData();

        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        if (csrfToken) {
            formData.append('_token', csrfToken);
        }

        formData.append('_method', 'PATCH');

        const requestEdit = `/inventory/leftovers/${encodeURIComponent(leftoversId)}`;

        await sendRequestBaseWithMethod(
            requestEdit,
            formData,
            validateEditLeftovers,
            () => {
                bootstrap.Modal.getInstance(document.getElementById('edit_leftovers'))?.hide();
                $('#inventory-venue-an-animal-table').jqxGrid('updatebounddata');
            },
            'POST'
        );
    });
});

// === Функція для збору даних з модалки "Додати залишки" ===
function getAddLeftoversData() {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const formData = new FormData();
    formData.append('_token', csrf);

    const getVal = (id) => $(`#${id}`).val();

    formData.append('goods_id', getVal('goods_id'));
    formData.append('batch', getVal('batch'));
    formData.append('condition', getVal('condition'));
    formData.append('expiration_term', getVal('expiration_term'));
    formData.append('manufacture_date', getVal('manufacture_date'));
    formData.append('bb_date', getVal('bb_date'));
    formData.append('packages_id', getVal('packages_id'));
    formData.append('quantity', getVal('quantity'));
    formData.append('container_registers_id', getVal('container_registers_id'));

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

    formData.append('goods_id', getVal('edit_goods_id'));
    formData.append('batch', getVal('edit_batch'));
    formData.append('condition', getVal('edit_condition'));
    formData.append('expiration_term', getVal('edit_expiration_term'));
    formData.append('manufacture_date', getVal('edit_manufacture_date'));
    formData.append('bb_date', getVal('edit_bb_date'));
    formData.append('package_id', getVal('edit_packages_id'));
    formData.append('quantity', getVal('edit_quantity'));
    formData.append('container_registers_id', getVal('edit_container_registers_id'));

    return formData;
}

// === Валідація для "Додати залишки" ===
async function validateAddLeftovers(response) {
    const fieldGroups = [
        {
            fields: [
                'goods_id',
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
                'edit_goods_id',
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

// === Очищення форми після закриття модалки "Додати залишки" ===
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('add_leftovers');

    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function () {
            clearAddLeftoversForm();
            console.log('🧹 Модалка закрита — форму очищено');
        });
    }
});
