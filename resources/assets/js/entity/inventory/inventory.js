import { redirectWithLocale } from '../../utils/redirectWithLocale.js';
import { sendRequestBase } from '../../utils/sendRequestBase.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';
import { validateGeneric } from '../../utils/request/validate.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();

    const url =
        locale === 'en'
            ? `` // без префіксу
            : `${locale}/`;

    const inventoryType = $('#inventory-container').data('type');
    const inventoryId = $('#inventory-container').data('id');

    // Створення інвентаризації
    $('#create').on('click', async function () {
        let formData = getData(inventoryType);

        await sendRequestBase(
            `${url}inventory`,
            formData,
            (res) => validate(res, inventoryType),
            function (res) {
                // Беремо id створеної інвентаризації
                const inventoryId = res.inventory.id;
                // Редірект на сторінку з цим id
                redirectWithLocale(`/inventory/${inventoryId}`);
            }
        );
    });

    $('#update').on('click', async function () {
        let formData = getData(inventoryType);
        formData.append('_method', 'PUT');

        await sendRequestBase(
            `${url}inventory/${inventoryId}`,
            formData,
            (res) => validate(res, inventoryType),
            function () {
                redirectWithLocale(`/inventory/${inventoryId}`);
            }
        );
    });
});

function getData(type) {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    let formData = new FormData();

    // Додати csrf
    formData.append('_token', csrf);

    if (type === 'full') collectFullData(formData);
    else if (type === 'partly') collectPartData(formData);

    return formData;
}

// --- Full Inventory ---
function collectFullData(formData) {
    const getVal = (id) => $(`#${id}`).val();
    const getChecked = (id) => ($(`#${id}`).is(':checked') ? 1 : 0);

    // Основні дані
    formData.append('show_leftovers', getChecked('show_leftovers_full'));
    formData.append('restrict_goods_movement', getChecked('restrict_goods_movement_full'));

    appendMultiSelect(formData, 'performer_type', 'performer_type_full');

    formData.append('start_date', getVal('start_date_full'));
    formData.append('end_date', getVal('end_date_full'));
    formData.append('comment', getVal('comment_full'));

    // Локація
    formData.append('warehouse', getVal('warehouse_full'));
    formData.append('warehouse_erp', getVal('warehouse_erp_full'));

    // Пріоритет
    const priorityFull = $('#priority_full .priority-btn.active').data('value');
    if (priorityFull !== undefined) {
        formData.append('priority', priorityFull);
    }

    // Process cell (radio)
    const processCell = $(`input[name="process_cell_full"]:checked`).attr('id');
    if (processCell) {
        formData.append(
            'process_cell',
            processCell.replace('process_cell_', '').replace('_full', '')
        );
    }
}

// --- Partly Inventory ---
function collectPartData(formData) {
    const getVal = (id) => $(`#${id}`).val();
    const getChecked = (id) => ($(`#${id}`).is(':checked') ? 1 : 0);
    const appendVal = (key, id) => {
        const val = getVal(id);
        if (val) {
            formData.append(key, val === 'all' ? null : val);
        }
    };

    // Основні дані
    formData.append('show_leftovers', getChecked('show_leftovers_part'));
    formData.append('restrict_goods_movement', getChecked('restrict_goods_movement_part'));

    appendMultiSelect(formData, 'performer_type', 'performer_type_part');

    formData.append('start_date', getVal('start_date_part'));
    formData.append('end_date', getVal('end_date_part'));
    formData.append('comment', getVal('comment_part'));

    // Локація
    formData.append('warehouse', getVal('warehouse_part'));
    formData.append('warehouse_erp', getVal('warehouse_erp_part'));

    appendVal('zone', 'zone_part');
    appendVal('sector', 'sector_part');
    appendVal('row', 'row_part');
    appendVal('cell', 'cell_part');

    // Пріоритет
    const priorityPart = $('#priority_part .priority-btn.active').data('value');
    if (priorityPart !== undefined) {
        formData.append('priority', priorityPart);
    }

    // Process cell (radio)
    const processCell = $(`input[name="process_cell_partly"]:checked`).attr('id');
    if (processCell) {
        formData.append(
            'process_cell',
            processCell.replace('process_cell_', '').replace('_partly', '')
        );
    }

    // Номенклатура
    appendVal('category_subcategory', 'category_subcategory_part');
    appendVal('manufacturer', 'manufacturer_part');
    appendVal('brand', 'brand_part');
    appendVal('supplier', 'supplier_part');
    // appendVal('nomenclature', 'nomenclature_part');

    // новий стиль для мультиселекту
    // appendSelect('category_subcategory', 'category_subcategory_part');
    // appendSelect('manufacturer', 'manufacturer_part');
    // appendSelect('brand', 'brand_part');
    // appendSelect('supplier', 'supplier_part');
    appendSelect(formData, 'nomenclature_part', 'nomenclature', true);
}

async function validate(response, type) {
    if (type === 'full') await validateFull(response);
    else if (type === 'partly') await validatePartly(response);
}

async function validateFull(response) {
    const fieldGroups = [
        {
            fields: [
                'show_leftovers',
                'restrict_goods_movement',
                'performer_type',
                'start_date',
                'end_date',
            ],
            container: '#main-data-message_full',
        },
        {
            fields: [
                'warehouse',
                'warehouse_erp',
                'zone',
                'sector',
                'row',
                'cell',
                'process_cell_full',
            ],
            container: '#location-data-message_full',
        },
    ];

    await validateGeneric(response, fieldGroups, '#main-data-message_full');
}

async function validatePartly(response) {
    const fieldGroups = [
        {
            fields: [
                'show_leftovers',
                'restrict_goods_movement',
                'performer_type',
                'start_date',
                'end_date',
            ],
            container: '#main-data-message_part',
        },
        {
            fields: [
                'warehouse',
                'warehouse_erp',
                'zone',
                'sector',
                'row',
                'cell',
                'process_cell_partly',
            ],
            container: '#location-data-message_part',
        },
        {
            fields: ['category_subcategory', 'manufacturer', 'brand', 'supplier', 'nomenclature'],
            container: '#nomenclature-data-message_part',
        },
    ];

    await validateGeneric(response, fieldGroups, '#main-data-message_part');
}

function appendMultiSelect(formData, key, id) {
    const values = $(`#${id}`).val() || [];
    values.forEach((val, index) => {
        formData.append(`${key}[${index}]`, val);
    });
}

function appendSelect(formData, selectId, formKey, hasAll = false) {
    const selectedValues = $(`#${selectId}`).val() || [];

    if (hasAll && selectedValues.includes('all')) {
        formData.append(`${formKey}[]`, 'all');
        return;
    }

    selectedValues.filter(Boolean).forEach((id) => formData.append(`${formKey}[]`, id));
}
