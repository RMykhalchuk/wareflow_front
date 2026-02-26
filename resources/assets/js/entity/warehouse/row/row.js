import { redirectWithLocale } from '../../../utils/redirectWithLocale.js';
import { sendRequestBase } from '../../../utils/sendRequestBase.js';
import { getCurrentLocaleFromUrl } from '../../../utils/getCurrentLocaleFromUrl.js';
import { validateGeneric } from '../../../utils/request/validate.js';
import { pollLabelJob } from '../../../utils/pollLabelJob.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();
    const url = locale === 'en' ? '' : `${locale}/`;

    let currentRowId;

    // додавання
    $('#row_submit').on('click', async function () {
        const $btn = $(this);
        const $spinner = $btn.find('.spinner-border');

        // Заблокуємо кнопку та покажемо лоадер
        $btn.prop('disabled', true);
        $spinner.removeClass('d-none');

        let formData = getRowData('row_');

        await sendRequestBase(`${url}sectors/${sectorId}/rows`, formData, validateRow, function () {
            redirectWithLocale(`/sectors/${sectorId}/rows`);
        });

        // Розблокуємо кнопку та сховаємо лоадер (якщо редірект не стався)
        $btn.prop('disabled', false);
        $spinner.addClass('d-none');
    });

    // відкриття модалки редагування
    $('#edit_row').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        currentRowId = button.data('edit-id');

        const hasLeftovers = button.data('has-leftovers');
        if (hasLeftovers) {
            $('#edit_delete').prop('disabled', true);
        } else {
            $('#edit_delete').prop('disabled', false);
        }

        const racks = button.data('row-racks');
        const floors = button.data('row-floors');
        const cells = button.data('row-cells');

        const weightBrutto = button.data('max-weight');
        const height = button.data('height');
        const width = button.data('width');
        const length = button.data('deep');

        $('#edit_row_racks').val(racks);
        $('#edit_row_floors').val(floors);
        $('#edit_row_cells').val(cells);

        $('#edit_row_weight_brutto').val(racks);
        $('#edit_row_height').val(height);
        $('#edit_row_width').val(width);
        $('#edit_row_length').val(length);

        $('#edit_row_weight_brutto').val(weightBrutto);
        $('#edit_row_height').val(height);
        $('#edit_row_width').val(width);
        $('#edit_row_length').val(length);
    });

    // збереження редагування
    $('#edit_row_submit').on('click', async function () {
        let formData = getRowData('edit_row_');
        formData.append('_method', 'PUT');

        await sendRequestBase(
            `${url}sectors/${sectorId}/rows/${currentRowId}`,
            formData,
            (res) => validateRow(res, true),
            function () {
                redirectWithLocale(`/sectors/${sectorId}/rows`);
            }
        );
    });

    // видалення з модалки
    $('#edit_delete').on('click', async function () {
        let formData = new FormData();
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        formData.append('_method', 'DELETE');
        formData.append('_token', csrf);

        await sendRequestBase(
            `${url}sectors/${sectorId}/rows/${currentRowId}`,
            formData,
            null,
            function () {
                redirectWithLocale(`/sectors/${sectorId}/rows`);
            }
        );
    });

    $(document).on('click', '#delete_row', async function (e) {
        e.preventDefault();

        const rowId = $(this).data('id');
        let formData = new FormData();
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        formData.append('_method', 'DELETE');
        formData.append('_token', csrf);

        await sendRequestBase(
            `${url}sectors/${sectorId}/rows/${rowId}`,
            formData,
            null,
            function () {
                redirectWithLocale(`/sectors/${sectorId}/rows`);
            }
        );
    });

    // 🔹 --- НОВИЙ БЛОК: логіка для модалки друку ---
    $('#print').on('click', async function (e) {
        e.preventDefault();

        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const itemFrom = $('#rows_from_id').val();
        const itemTo = $('#rows_to_id').val();
        // const printId = $('#print_id').val();

        let formData = new FormData();
        formData.append('_token', csrf);
        formData.append('type', 'row');
        formData.append('item_from', itemFrom);
        formData.append('item_to', itemTo);

        // formData.append('print_id', printId);

        const $loader = $('#print-loader');

        // Показуємо лоадер
        $loader.removeClass('d-none').addClass('d-flex');

        await sendRequestBase(
            `${url}stickers/print-labels`,
            formData,
            (res) => validatePrint(res),
            function (response) {
                if (response?.job_id) {
                    pollLabelJob(url, response.job_id, $loader);
                } else {
                    $loader.removeClass('d-flex').addClass('d-none');
                    alert('Не вдалося сформувати файл для друку.');
                }
            }
        );
    });

    // 🔹 Друк одного рядка
    $(document).on('click', '#print_row', async function (e) {
        e.preventDefault();

        const rowId = $(this).data('id');
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        let formData = new FormData();
        formData.append('_token', csrf);
        formData.append('type', 'row');
        formData.append('item_from', rowId);
        formData.append('item_to', rowId);

        const $loader = $('#print-loader');

        // Показуємо лоадер
        $loader.removeClass('d-none').addClass('d-flex');

        await sendRequestBase(`${url}stickers/print-labels`, formData, null, function (response) {
            if (response?.job_id) {
                pollLabelJob(url, response.job_id, $loader);
            } else {
                $loader.removeClass('d-flex').addClass('d-none');
                alert('Не вдалося сформувати файл для друку.');
            }
        });
    });
});

function getRowData(prefix = 'row_') {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const formData = new FormData();

    const getVal = (id) => $(`#${prefix}${id}`).val();

    // Якщо це режим редагування — count НЕ читаємо
    const isEdit = prefix.startsWith('edit_row_');

    const count = isEdit ? null : getVal('count');
    const racks = getVal('racks');
    const floors = getVal('floors');
    const cells = getVal('cells');

    // cell fields
    const weightBrutto = getVal('weight_brutto');
    const height = getVal('height');
    const width = getVal('width');
    const length = getVal('length');

    formData.append('_token', csrf);

    if (!isEdit) {
        formData.append('count', count);
    }

    formData.append('racks', racks);
    formData.append('floors', floors);
    formData.append('cell_count', cells);

    // об’єкт cell
    formData.append('cell[height]', height);
    formData.append('cell[width]', width);
    formData.append('cell[deep]', length);
    formData.append('cell[max_weight]', weightBrutto);

    return formData;
}

export async function validateRow(response, isEdit = false) {
    const container = isEdit ? '#row-message-edit' : '#row-message';

    const fieldGroups = [
        {
            fields: [
                'racks',
                'floors',
                'cell_count',
                'cell.height',
                'cell.width',
                'cell.deep',
                'cell.max_weight',
            ],
            container,
        },
    ];

    await validateGeneric(response, fieldGroups, container);
}

// 🔹 Валідація друку
async function validatePrint(response) {
    const container = '#print-error';
    const fieldGroups = [
        {
            fields: ['rows_from_id', 'rows_to_id', 'print_id'],
            container,
        },
    ];

    await validateGeneric(response, fieldGroups, container);
}
