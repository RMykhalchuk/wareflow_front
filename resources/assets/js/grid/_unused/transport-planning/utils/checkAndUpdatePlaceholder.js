export function checkAndUpdatePlaceholder() {
    // Отримайте кількість елементів .goods-invoices-item всередині #sortable
    var sortableItemsCount = $('#sortable .goods-invoices-item').length;

    // Перевіряйте кількість цих елементів для визначення відображення плейсхолдера
    if (sortableItemsCount > 0) {
        $('#js-tp-sortable-placeholder').addClass('d-none');
    } else {
        $('#js-tp-sortable-placeholder').removeClass('d-none');
    }
}
