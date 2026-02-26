// Створюємо слухача подій для інпута 'titleInput_'
export function bindTitleInput(containerSelector, inputSelector, targetSelector) {
    $(containerSelector).on('input', inputSelector, function () {
        const inputText = $(this).val();
        const group = $(this).closest('.group'); // або можна теж передати параметром

        // Оновлюємо текст в targetSelector
        group.find(targetSelector).text(inputText);

        // Ставимо value
        $(this).attr('value', inputText);
    });
}

// Функція для обробки зміни стану чекбокса та відповідного поля
function handleSectionCheckboxChange(section) {
    const $checkbox = $(`#${section}_checked`);
    const $field = $(`#default_${section}_fields`);

    $checkbox.on('change', function () {
        if (this.checked) {
            $field.removeClass('d-none');
        } else {
            $field.addClass('d-none');
        }
    });
}

// const sections = ['nomenclature', 'container', 'services'];
//
// // Виклик функції для кожної секції
// sections.forEach((section) => {
//     handleSectionCheckboxChange(section);
// });
