export function initSortableDocItem() {
    let sortableInvoices = $('#sortable');
    sortableInvoices.sortable({
        placeholder: 'ui-state-highlight-goods-invoices',
        stop: function () {
            $('.goods-invoices-item').each(function (i, el) {
                $(el).attr('data-id', i + 1);
                $(el)
                    .find('.goods-invoices-item-order h5')
                    .text(i + 1);
            });
        },
    });

    sortableInvoices.disableSelection();
}
