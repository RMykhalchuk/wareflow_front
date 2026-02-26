export function getIdGoodsInvoice() {
    const goodsInvoicesItems = document.querySelectorAll('.goods-invoices-item');

    const goodsInvoiceIds = [];

    goodsInvoicesItems.forEach(function (item) {
        const goodsInvoiceId = item.getAttribute('data-goods-invoice-id');
        goodsInvoiceIds.push(goodsInvoiceId);
    });
    return goodsInvoiceIds;
}
