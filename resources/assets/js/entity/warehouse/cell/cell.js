import { redirectWithLocale } from '../../../utils/redirectWithLocale.js';
import { sendRequestBase } from '../../../utils/sendRequestBase.js';
import { getCurrentLocaleFromUrl } from '../../../utils/getCurrentLocaleFromUrl.js';
import { validateGeneric } from '../../../utils/request/validate.js';
import { pollLabelJob } from '../../../utils/pollLabelJob.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();
    const url = locale === 'en' ? '' : `${locale}/`;

    let currentCellId;

    // Edit modal open
    $('#edit_cell').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        currentCellId = button.data('edit-id');

        const hasLeftovers = button.data('has-leftovers');
        if (hasLeftovers) {
            $('#edit_delete').prop('disabled', true);
        } else {
            $('#edit_delete').prop('disabled', false);
        }

        const height = button.data('cell-height');
        const width = button.data('cell-width');
        const deep = button.data('cell-length');
        const maxWeight = button.data('cell-max-weight');

        $('#cell_height').val(height);
        $('#cell_width').val(width);
        $('#cell_length').val(deep);
        $('#cell_max_weight').val(maxWeight);
    });

    $('#save_edit_cell').on('click', async function () {
        let formData = getCellData();
        formData.append('_method', 'PUT');

        await sendRequestBase(
            `${url}rows/${rowId}/cells/${currentCellId}`,
            formData,
            validateCell,
            function () {
                redirectWithLocale(`/rows/${rowId}/cells`);
            }
        );
    });

    $('#edit_delete').on('click', async function () {
        let formData = new FormData();
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        formData.append('_method', 'DELETE');
        formData.append('_token', csrf);

        await sendRequestBase(
            `${url}rows/${rowId}/cells/${currentCellId}`,
            formData,
            null,
            function () {
                redirectWithLocale(`/rows/${rowId}/cells`);
            }
        );
    });

    // Delete from dropdown
    $(document).on('click', '#delete_cell', async function (e) {
        e.preventDefault();

        const cellId = $(this).data('id');
        let formData = new FormData();
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        formData.append('_method', 'DELETE');
        formData.append('_token', csrf);

        await sendRequestBase(`${url}rows/${rowId}/cells/${cellId}`, formData, null, function () {
            redirectWithLocale(`/rows/${rowId}/cells`);
        });
    });

    // 🔹 --- НОВИЙ БЛОК: логіка для модалки друку ---
    $('#print').on('click', async function (e) {
        e.preventDefault();

        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const itemFrom = $('#cells_from_id').val();
        const itemTo = $('#cells_to_id').val();
        // const printId = $('#print_id').val();

        let formData = new FormData();
        formData.append('_token', csrf);
        formData.append('type', 'cell');
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

    // 🔹 Друк однієї комірки
    $(document).on('click', '#print_cell', async function (e) {
        e.preventDefault();

        const cellId = $(this).data('id');
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        let formData = new FormData();
        formData.append('_token', csrf);
        formData.append('type', 'cell');
        formData.append('items[]', cellId);

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

function getCellData() {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const formData = new FormData();

    const getVal = (id) => $(`#${id}`).val();

    formData.append('_token', csrf);
    formData.append('height', getVal('cell_height'));
    formData.append('width', getVal('cell_width'));
    formData.append('deep', getVal('cell_length'));
    formData.append('max_weight', getVal('cell_max_weight'));
    formData.append('apply_properties', +$('#cell_apply_properties').prop('checked'));

    return formData;
}

export async function validateCell(response) {
    const fieldGroups = [
        { fields: [], container: '#cell-message' }, // Порожній масив, всі поля підуть у defaultContainer
    ];

    await validateGeneric(response, fieldGroups, '#cell-message');
}

// 🔹 Валідація друку
async function validatePrint(response) {
    const container = '#print-error';
    const fieldGroups = [
        {
            fields: ['cells_from_id', 'cells_to_id', 'print_id'],
            container,
        },
    ];

    await validateGeneric(response, fieldGroups, container);
}
