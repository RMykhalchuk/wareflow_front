export function updateDataIds() {
    $('.goods-invoices-item').each(function (i, el) {
        $(el).attr('data-id', i + 1);
        $(el)
            .find('.goods-invoices-item-order h5')
            .text(i + 1);
    });
}
