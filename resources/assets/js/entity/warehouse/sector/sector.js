import { redirectWithLocale } from '../../../utils/redirectWithLocale.js';
import { sendRequestBase } from '../../../utils/sendRequestBase.js';
import { getCurrentLocaleFromUrl } from '../../../utils/getCurrentLocaleFromUrl.js';
import { validateGeneric } from '../../../utils/request/validate.js';
import { pollLabelJob } from '../../../utils/pollLabelJob.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();
    const url = locale === 'en' ? '' : `${locale}/`;

    let currentSectorId;

    // додавання
    $('#add_sector_submit').on('click', async function () {
        let formData = getSectorData('add_');

        await sendRequestBase(
            `${url}zones/${zoneId}/sectors`,
            formData,
            validateSector,
            function () {
                redirectWithLocale(`/zones/${zoneId}/sectors`);
            }
        );
    });

    // відкриття модалки для редагування
    $('#edit_sector').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        currentSectorId = button.data('id');

        const hasLeftovers = button.data('has-leftovers');
        if (hasLeftovers) {
            $('#edit_delete').prop('disabled', true);
        } else {
            $('#edit_delete').prop('disabled', false);
        }

        const name = button.data('name');
        const color = button.data('color');
        const hasTemp = button.data('has-temp');
        const tempFrom = button.data('temp-from');
        const tempTo = button.data('temp-to');
        const hasHumidity = button.data('has-humidity');
        const humidityFrom = button.data('humidity-from');
        const humidityTo = button.data('humidity-to');

        $('#edit_sector_name').val(name);
        $(`input[name="edit_customColorRadio"][value="${color}"]`).prop('checked', true);

        $('#edit_checkbox-1').prop('checked', !!hasTemp);
        if (hasTemp) {
            $('#edit_checkbox-1-block').show();
            const slider = document.getElementById('edit_slider-1');
            slider?.noUiSlider?.set([tempFrom, tempTo]);
        } else {
            $('#edit_checkbox-1-block').hide();
        }

        $('#edit_checkbox-2').prop('checked', !!hasHumidity);
        if (hasHumidity) {
            $('#edit_checkbox-2-block').show();
            const slider = document.getElementById('edit_slider-2');
            slider?.noUiSlider?.set([humidityFrom, humidityTo]);
        } else {
            $('#edit_checkbox-2-block').hide();
        }
    });

    // збереження редагування
    $('#save_edit_sector').on('click', async function () {
        let formData = getSectorData('edit_');
        formData.append('_method', 'PUT');

        await sendRequestBase(
            `${url}zones/${zoneId}/sectors/${currentSectorId}`,
            formData,
            (res) => validateSector(res, true),
            function () {
                redirectWithLocale(`/zones/${zoneId}/sectors`);
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
            `${url}zones/${zoneId}/sectors/${currentSectorId}`,
            formData,
            null,
            function () {
                redirectWithLocale(`/zones/${zoneId}/sectors`);
            }
        );
    });

    // видалення прямо з таблиці
    $(document).on('click', '#delete_sector', async function (e) {
        e.preventDefault();

        const sectorId = $(this).data('id');
        let formData = new FormData();
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        formData.append('_method', 'DELETE');
        formData.append('_token', csrf);

        await sendRequestBase(
            `${url}zones/${zoneId}/sectors/${sectorId}`,
            formData,
            null,
            function () {
                redirectWithLocale(`/zones/${zoneId}/sectors`);
            }
        );
    });

    // 🔹 --- МОДАЛКА ДРУКУ ---
    $('#print').on('click', async function (e) {
        e.preventDefault();

        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const itemFrom = $('#sectors_from_id').val();
        const itemTo = $('#sectors_to_id').val();
        // const printId = $('#print_id').val();

        let formData = new FormData();
        formData.append('_token', csrf);
        formData.append('type', 'sector');
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

    // 🔹 --- Кнопка друку конкретного сектору ---
    $(document).on('click', '#print_sector', async function (e) {
        e.preventDefault();

        const cellId = $(this).data('id');
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        let formData = new FormData();
        formData.append('_token', csrf);
        formData.append('type', 'sector');
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

function getSectorData(prefix = 'add_') {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const formData = new FormData();

    const getVal = (id) => $(`#${prefix}${id}`).val();

    const sectorName = getVal('sector_name');
    const color = $(`input[name="${prefix}customColorRadio"]:checked`).val();
    const hasTemp = +$(`#${prefix}checkbox-1`).prop('checked');
    const hasHumidity = +$(`#${prefix}checkbox-2`).prop('checked');

    formData.append('_token', csrf);
    formData.append('name', sectorName);
    formData.append('color', color);
    formData.append('has_temp', hasTemp);
    formData.append('has_humidity', hasHumidity);

    if (hasTemp) {
        const tempValues = document
            .getElementById(prefix === 'add_' ? 'slider-1' : `${prefix}slider-1`)
            .noUiSlider.get();
        formData.append('temp_from', tempValues[0]);
        formData.append('temp_to', tempValues[1]);
    }

    if (hasHumidity) {
        const humidityValues = document
            .getElementById(prefix === 'add_' ? 'slider-2' : `${prefix}slider-2`)
            .noUiSlider.get();
        formData.append('humidity_from', humidityValues[0]);
        formData.append('humidity_to', humidityValues[1]);
    }

    return formData;
}

export async function validateSector(response, isEdit = false) {
    const container = isEdit ? '#sector-message-edit' : '#sector-message';

    const fieldGroups = [
        {
            fields: [
                'name',
                'has_temp',
                'has_humidity',
                'color',
                'temp_from',
                'temp_to',
                'humidity_from',
                'humidity_to',
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
            fields: ['sectors_from_id', 'sectors_to_id', 'print_id'],
            container,
        },
    ];

    await validateGeneric(response, fieldGroups, container);
}
