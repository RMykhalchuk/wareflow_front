import { getLocalizedText } from '../../localization/document/getLocalizedText.js';
import { initGoodsAvailability } from './outcome/goods-leftovers-available.js';

const table = $('#sku-table');
const goodsSelect = $('#goods');
const goodsSelectEdit = $('#edit_goods');
const document_type_kind = $('#document-container').data('document-type-kind');

function handleSelect2Change(e) {
    const data = e.params.data;
    if (!data.full) return;

    selectedItemData = {
        id: data.full.id,
        local_id: data.full.local_id,
        name: data.full.name ?? '-',
        barcode: data.full.barcode ?? '-',
        brand: data.full.brand_company?.name ?? '-',
        supplier: data.full.provider_company?.name ?? '-',
        manufacturer: data.full.manufacturer_company?.name ?? '-',
        country: data.full.manufacturer_country?.name ?? '-',
        unit: data.full.measurement_unit?.name ?? '-',
        country_id: data.full.manufacturer_country_id ?? '-',
        unit_id: data.full.measurement_unit_id ?? '-',
    };

    // вивід на форму (щоб юзер бачив)
    $('#sku_name').text(selectedItemData.name);
    $('#sku_barcode').text(selectedItemData.barcode);
    $('#sku_brand').text(selectedItemData.brand);
    $('#sku_supplier').text(selectedItemData.supplier);
    $('#sku_manufacturer').text(selectedItemData.manufacturer);
    $('#sku_country').text(selectedItemData.country);
    $('#measurement_unit').text(selectedItemData.unit);

    const unitSpan = document
        .querySelector('#goods_count')
        ?.closest('.input-group')
        ?.querySelector('.input-group-text');

    unitSpan.textContent = selectedItemData.unit;

    // Працюємо ТІЛЬКИ для outcome
    if (document_type_kind === 'outcome') {
        initGoodsAvailability({
            selectId: '#goods',
            inputId: '#goods_count',
            hintId: '#goods_available_hint_create',
        });
    }
}

function handleSelect2ChangeEdit(e) {
    const data = e.params.data;
    if (!data.full) return;

    selectedItemData = {
        id: data.full.id,
        local_id: data.full.local_id,
        name: data.full.name ?? '-',
        barcode: data.full.barcode ?? '-',
        brand: data.full.brand_company?.name ?? '-',
        supplier: data.full.provider_company?.name ?? '-',
        manufacturer: data.full.manufacturer_company?.name ?? '-',
        country: data.full.manufacturer_country?.name ?? '-',
        unit: data.full.measurement_unit?.name ?? '-',
        country_id: data.full.manufacturer_country_id ?? '-',
        unit_id: data.full.measurement_unit_id ?? '-',
    };

    // вивід на форму (щоб юзер бачив)
    $('#sku_name').text(selectedItemData.name);
    $('#sku_barcode').text(selectedItemData.barcode);
    $('#sku_brand').text(selectedItemData.brand);
    $('#sku_supplier').text(selectedItemData.supplier);
    $('#sku_manufacturer').text(selectedItemData.manufacturer);
    $('#sku_country').text(selectedItemData.country);
    $('#measurement_unit').text(selectedItemData.unit);

    const unitSpan = document
        .querySelector('#edit_goods_count')
        ?.closest('.input-group')
        ?.querySelector('.input-group-text');

    unitSpan.textContent = selectedItemData.unit;

    // Працюємо ТІЛЬКИ для outcome
    if (document_type_kind === 'outcome') {
        initGoodsAvailability({
            selectId: '#edit_goods',
            inputId: '#edit_goods_count',
            hintId: '#goods_available_hint_edit',
        });
    }
}

// Прив’язка для обох select2
goodsSelect.off('select2:select').on('select2:select', handleSelect2Change);
goodsSelectEdit.off('select2:select').on('select2:select', handleSelect2ChangeEdit);

// Додавання / редагування
$('#submit').on('click', function () {
    const item = {
        ...selectedItemData,
        quantity: $('#goods_count').val(),
    };

    if (!item.name || !item.quantity) {
        alert(
            getLocalizedText('sku_table.quantity_required') ||
                'Будь ласка, заповніть усі обов’язкові поля'
        );
        return;
    }

    if (editIndex !== null) {
        window.tableData[editIndex] = item;
        editIndex = null;
    } else {
        const isDuplicate = window.tableData.some((existingItem) => existingItem.id === item.id);
        if (isDuplicate) {
            alert(getLocalizedText('sku_table.duplicate_goods'));
            return;
        }
        window.tableData.push(item);
    }

    updatePositions();

    refreshGrid(table);
    $('#add_table').modal('hide');
    resetForm();
    editIndex = null;
    selectedItemData = null;
});

// Збереження редагування
$('#edit_submit').on('click', function () {
    if (editIndex === null) return;

    const item = {
        ...selectedItemData,
        quantity: $('#edit_goods_count').val(),
    };

    // console.log(item);

    if (!item.name || !item.quantity) {
        alert(
            getLocalizedText('sku_table.quantity_required') ||
                'Будь ласка, заповніть усі обов’язкові поля'
        );
        return;
    }

    window.tableData[editIndex] = item;

    updatePositions();

    refreshGrid(table);
    $('#edit_table').modal('hide');
    resetForm();
    editIndex = null;
    selectedItemData = null;
});

// Хелпери
export function refreshGrid(table) {
    table.jqxGrid('clear');

    // Додаємо всі рядки
    window.tableData.forEach((item) => {
        table.jqxGrid('addrow', null, item);
    });

    // Чекаємо, поки таблиця відрендериться, перед тим як показувати блоки
    setTimeout(() => {
        toggleBlocks();
    }, 50); // 50 мс — цього зазвичай достатньо
}

export function resetForm() {
    $('#goods').val(null).trigger('change');
    $('#goods_count').val('');
    $(
        '#sku_name, #sku_barcode, #sku_brand, #sku_supplier, #sku_manufacturer, #sku_country, #measurement_unit'
    ).text('-');
    $('#goods_available_hint_create').text('');
}

// Перемикання блоків залежно від наявності даних
function toggleBlocks() {
    const hasData = window.tableData.length > 0;

    if (hasData) {
        $('.js-add-block').addClass('d-none');
        $('.table-block').removeClass('d-none');
        $('.js-add-bottom-button').removeClass('d-none');
    } else {
        $('.js-add-block').removeClass('d-none');
        $('.table-block').addClass('d-none');
        $('.js-add-bottom-button').addClass('d-none');
    }
}

export function updatePositions() {
    window.tableData.forEach((item, index) => {
        item.position = index + 1;
    });
}
