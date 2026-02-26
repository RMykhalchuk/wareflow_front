// toggleBarcode.js
export function toggleBlock({ checkboxId, targetId }) {
    const checkbox = document.getElementById(checkboxId);
    const target = document.getElementById(targetId);

    if (!checkbox || !target) return;

    const updateVisibility = () => {
        target.style.display = checkbox.checked ? 'block' : 'none';
    };

    // Ініціалізація
    updateVisibility();

    // Вішаємо слухач подій, якщо треба оновлювати динамічно
    checkbox.addEventListener('change', updateVisibility);
}
