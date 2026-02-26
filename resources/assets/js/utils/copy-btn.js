export function setupCopyButton(btnId, contentId) {
    document.getElementById(btnId).addEventListener('click', function () {
        var contentToCopy = document.getElementById(contentId).innerText;

        // Створюємо тимчасовий textarea для копіювання вмісту
        var textarea = document.createElement('textarea');
        textarea.value = contentToCopy;
        document.body.appendChild(textarea);

        // Виконуємо команду копіювання вмісту в буфер обміну
        textarea.select();
        document.execCommand('copy');

        // Видаляємо тимчасовий textarea
        document.body.removeChild(textarea);

        // Оповіщаємо користувача про копіювання
        //alert("Копіювання успaішне!");
    });
}
