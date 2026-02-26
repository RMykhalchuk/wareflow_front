import { redirectWithLocale } from '../../../utils/redirectWithLocale.js';
import { sendRequestBase } from '../../../utils/sendRequestBase.js';
import { getCurrentLocaleFromUrl } from '../../../utils/getCurrentLocaleFromUrl.js';
import { validateGeneric } from '../../../utils/request/validate.js';
import { pollLabelJob } from '../../../utils/pollLabelJob.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();

    const url =
        locale === 'en'
            ? `` // без префіксу
            : `${locale}/`;

    let currentZoneId;

    $('#add_zone_submit').on('click', async function () {
        let formData = getZoneData('add_');

        await sendRequestBase(
            `${url}warehouses/${warehouseId}/zones`,
            formData,
            validateZone,
            function () {
                redirectWithLocale(`/warehouses/${warehouseId}/zones`);
            }
        );
    });

    $('#edit_zone').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        currentZoneId = button.data('id');

        const hasLeftovers = button.data('has-leftovers');
        if (hasLeftovers) {
            $('#delete_edit_zone').prop('disabled', true);
        } else {
            $('#delete_edit_zone').prop('disabled', false);
        }

        const name = button.data('name');

        const color = button.data('color');
        const hasTemp = button.data('has-temp');
        const tempFrom = button.data('temp-from');
        const tempTo = button.data('temp-to');
        const hasHumidity = button.data('has-humidity');
        const humidityFrom = button.data('humidity-from');
        const humidityTo = button.data('humidity-to');
        const zone_type = button.data('zone_type');
        const zone_subtype = button.data('zone_subtype');

        $('#edit_zone_name').val(name);
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

        $('#edit_zone_type').val(zone_type).trigger('change');

        // 🔥 Імітуємо подію вибору товару, щоб розблокувати Select пакування
        $('#edit_zone_type').trigger({
            type: 'select2:select',
            params: { data: { id: zone_type } },
        });

        // === 2️⃣ Чекаємо, поки Select пакування стане активним, тоді виставляємо значення ===
        const waitForZoneType = setInterval(() => {
            if (!$('#edit_zone_type').prop('disabled')) {
                clearInterval(waitForZoneType);

                if (zone_subtype) {
                    $('#edit_zone_subtype').val(zone_subtype).trigger('change');

                    // Імітуємо подію вибору для Select2
                    $('#edit_zone_subtype').trigger({
                        type: 'select2:select',
                        params: { data: { id: zone_subtype } },
                    });
                }
            }
        }, 1000); // перевіряємо кожні 100мс
    });

    $('#save_edit_zone').on('click', async function () {
        let formData = getZoneData('edit_');
        formData.append('_method', 'PUT');

        await sendRequestBase(
            `${url}warehouses/${warehouseId}/zones/${currentZoneId}`,
            formData,
            (res) => validateZone(res, true),
            function () {
                redirectWithLocale(`/warehouses/${warehouseId}/zones`);
            }
        );
    });

    $('#delete_edit_zone').on('click', async function () {
        let formData = new FormData();
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        formData.append('_method', 'DELETE');
        formData.append('_token', csrf);

        await sendRequestBase(
            `${url}warehouses/${warehouseId}/zones/${currentZoneId}`,
            formData,
            null,
            function () {
                redirectWithLocale(`/warehouses/${warehouseId}/zones`);
            }
        );
    });

    $(document).on('click', '#delete_zone', async function (e) {
        e.preventDefault();

        const zoneId = $(this).data('id');
        let formData = new FormData();
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        formData.append('_method', 'DELETE');
        formData.append('_token', csrf);

        await sendRequestBase(
            `${url}warehouses/${warehouseId}/zones/${zoneId}`,
            formData,
            null,
            function () {
                redirectWithLocale(`/warehouses/${warehouseId}/zones`);
            }
        );
    });

    // 🔹 --- НОВИЙ БЛОК: логіка для модалки друку ---
    $('#print').on('click', async function (e) {
        e.preventDefault();

        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const itemFrom = $('#zones_from_id').val();
        const itemTo = $('#zones_to_id').val();
        // const printId = $('#print_id').val();

        let formData = new FormData();
        formData.append('_token', csrf);
        formData.append('type', 'zone');
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

    $(document).on('click', '#print_zone', async function (e) {
        e.preventDefault();

        const cellId = $(this).data('id');
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        let formData = new FormData();
        formData.append('_token', csrf);
        formData.append('type', 'zone');
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

function getZoneData(prefix = 'add_') {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const formData = new FormData();

    const getVal = (id) => $(`#${prefix}${id}`).val();

    const zoneName = getVal('zone_name');
    const zoneType = getVal('zone_type');
    const zoneSubtype = getVal('zone_subtype');
    const color = $(`input[name="${prefix}customColorRadio"]:checked`).val();
    const hasTemp = +$(`#${prefix}checkbox-1`).prop('checked');
    const hasHumidity = +$(`#${prefix}checkbox-2`).prop('checked');

    formData.append('_token', csrf);
    formData.append('name', zoneName);
    formData.append('zone_type', zoneType);
    formData.append('zone_subtype', zoneSubtype);
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

export async function validateZone(response, isEdit = false) {
    const container = isEdit ? '#zone-message-edit' : '#zone-message';

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
            fields: ['zones_from_id', 'zones_to_id', 'print_id'],
            container,
        },
    ];

    await validateGeneric(response, fieldGroups, container);
}
