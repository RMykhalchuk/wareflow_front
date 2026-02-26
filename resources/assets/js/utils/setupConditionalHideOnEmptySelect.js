/**
 * Ховає targetElement, якщо select2 selectId порожній
 * @param {string} selectId - ID select2 селектора
 * @param {string} targetId - ID елемента, який треба показувати/ховати
 */
export function setupConditionalHideOnEmptySelect(selectId, targetId) {
    const select = $('#' + selectId);
    const target = document.getElementById(targetId);

    if (!select.length || !target) return;

    const updateVisibility = () => {
        const val = select.val();
        const isEmpty = !val || val === '0';
        target.style.display = isEmpty ? 'none' : 'block';
    };

    // Слідкуємо за зміною Select2
    select.on('select2:select select2:unselect change', updateVisibility);

    // Викликаємо одразу при завантаженні
    updateVisibility();
}
