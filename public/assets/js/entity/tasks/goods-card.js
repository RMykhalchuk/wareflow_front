$(document).ready(function () {
    const goodsSelect = $('#goods');
    const categoryBlock = $('.js-category-block');
    const skuBlock = $('.js-full-block');

    // спочатку ховаємо блок SKU
    skuBlock.addClass('d-none');

    // вибір товару
    goodsSelect.on('select2:select', function (e) {
        const data = e.params.data;
        if (!data.full) return;

        // Заповнюємо дані SKU
        $('#sku_name').text(data.full.name ?? '-');
        $('#sku_barcode').text(data.full.local_id ?? '-');
        $('#sku_brand').text(data.full.brand ?? '-');
        $('#sku_supplier').text(data.full.provider ?? '-');
        $('#sku_manufacturer').text(data.full.manufacturer ?? '-');
        $('#sku_country').text(data.full.manufacturer_country_id ?? '-');
        $('#measurement_unit').text(data.full.measurement_unit_id ?? '-');

        // показуємо блок SKU, ховаємо блок категорії
        categoryBlock.addClass('d-none');
        skuBlock.removeClass('d-none');
    });

    // очищення select
    goodsSelect.on('select2:clear', function () {
        clearSku();
    });

    // кнопка хрестика для закриття SKU блоку
    skuBlock.find('.js-close-sku').on('click', function () {
        goodsSelect.val(null).trigger('change'); // очищаємо select
        clearSku();
    });

    function clearSku() {
        // очищаємо дані SKU
        $(
            '#sku_name, #sku_barcode, #sku_brand, #sku_supplier, #sku_manufacturer, #sku_country, #measurement_unit'
        ).text('-');
        // повертаємо видимість блоків
        categoryBlock.removeClass('d-none');
        skuBlock.addClass('d-none');
    }
});
