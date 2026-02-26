import { sendRequestBase } from '../../utils/sendRequestBase.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';

document.addEventListener('DOMContentLoaded', function () {
    const $select = $('#global_warehouse_id');

    if (!$select.length) return;

    const STORAGE_KEY = 'global_warehouse_id';

    const locale = getCurrentLocaleFromUrl();
    const basePrefix = locale === 'en' ? '' : `${locale}/`;

    // ======= 🔥 ОСНОВНА ФУНКЦІЯ ОНОВЛЕННЯ СКЛАДУ =======
    const updateWarehouse = async (warehouseId) => {
        let formData = new FormData();
        let csrf = document.querySelector('meta[name="csrf-token"]').content;
        formData.append('_token', csrf);

        if (warehouseId) {
            localStorage.setItem(STORAGE_KEY, warehouseId);
        } else {
            localStorage.removeItem(STORAGE_KEY);
        }

        const currentUrl = new URL(window.location.href);

        // 🔹 Спочатку відправляємо запит на оновлення складу
        if (warehouseId) {
            await sendRequestBase(
                `${basePrefix}users/update/current-warehouse/${warehouseId}`,
                formData,
                null,
                null
            );
        } else {
            await sendRequestBase(
                `${basePrefix}users/clear/current-warehouse`,
                formData,
                null,
                null
            );
        }

        // 🔹 Оновлюємо URL та перезавантажуємо сторінку
        if (warehouseId) {
            currentUrl.searchParams.set('warehouse_id', warehouseId);
        } else {
            currentUrl.searchParams.delete('warehouse_id');
        }

        window.location.href = currentUrl.toString();
    };

    // ======= Події Select =======
    $select.on('select2:select', function () {
        updateWarehouse($(this).val());
    });

    $select.on('select2:clear', function () {
        updateWarehouse(null);
    });

    // ======= Ініціалізація =======
    setTimeout(() => {
        const stored = localStorage.getItem(STORAGE_KEY);
        if (!stored) return;

        const optionExists = $select.find(`option[value="${stored}"]`).length > 0;
        if (!optionExists) {
            // Склад більше не доступний для цього користувача — очищаємо стале значення
            localStorage.removeItem(STORAGE_KEY);
            return;
        }

        // Відновлюємо тільки якщо сервер не відмалював вибране значення (select порожній)
        const currentVal = $select.val();
        if (!currentVal) {
            $select.val(stored).trigger('change.select2');
        }
    }, 200);
});
