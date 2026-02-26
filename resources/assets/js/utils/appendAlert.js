export function appendAlert(selector, type, message) {
    $(selector).each(function () {
        $(this)[0].innerHTML = null;

        // Використовуємо шаблонні рядки для побудови HTML-рядка
        let block = $(`
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <div type="button" data-bs-dismiss="alert" aria-label="Close" class="close">
                    <span aria-hidden="true">&times;</span>
                </div>
            </div>
        `);

        $(this).append(block);

        // Додамо затримку для видалення блоку повідомлення через 7 секунд
        setTimeout(function () {
            block.removeClass('show');
            // Додамо затримку для видалення самого елементу після видалення класу show
            block.one('transitionend', function () {
                block.remove();
            });
        }, 7000); // 7000 мілісекунд = 7 секунд
    });
}
