// selectToggle.js
export function initSelectWithValidationToggle(selectId, errorId, buttonId) {
    const select = $(`#${selectId}`);
    const error = document.getElementById(errorId);
    const button = document.getElementById(buttonId);

    function toggleElements() {
        const value = select.val();
        if (value) {
            error.classList.add('d-none');
            error.classList.remove('d-block');

            button.classList.remove('d-none');
            button.classList.add('d-block');
        } else {
            error.classList.remove('d-none');
            error.classList.add('d-block');

            button.classList.add('d-none');
            button.classList.remove('d-block');
        }
    }

    // Запуск перевірки при завантаженні
    toggleElements();

    // Обробка події Select2
    select.on('change', toggleElements);
}
