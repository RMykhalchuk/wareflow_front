import { getLocalizedText } from '../../localization/warehouse/getLocalizedText.js';
import { redirectWithLocale } from '../../utils/redirectWithLocale.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';
import { sendRequestBase } from '../../utils/sendRequestBase.js';
import { sendRequest } from '../../utils/sendRequest.js';
import { validateGeneric } from '../../utils/request/validate.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();
    const url = locale === 'en' ? `` : `${locale}/`;

    let days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    let graphic = {};

    for (let i = 0; i < 7; i++) {
        $('#' + days[i] + '-check').on('click', function () {
            if ($('#' + days[i] + '-check')[0].checked) {
                for (let j = 1; j < 5; j++) {
                    $('#' + days[i] + '-' + j).val('');
                    $('#' + days[i] + '-' + j).prop('disabled', true);
                }
            } else {
                for (let j = 1; j < 5; j++) {
                    $('#' + days[i] + '-' + j).prop('disabled', false);
                }
            }
        });
    }

    function schedule() {
        for (let i = 0; i < 7; i++) {
            if ($('#' + days[i] + '-check')[0].checked) {
                graphic[days[i]] = 'holiday';
            } else {
                let graphicArray = [];
                for (let j = 0; j < 4; j++) {
                    graphicArray[j] = $('#' + days[i] + '-' + (j + 1)).val();
                }
                graphic[days[i]] = graphicArray;
            }
        }
    }

    $('#create').on('click', async function () {
        if ($('#working-data').is(':checked')) {
            if (checkHoursInSchedule('.two-input-for-schedule')) return;
        }

        schedule();

        if (!graphic) {
            alert(getLocalizedText('scheduleMustFilled'));
            return;
        }

        const canContinue = await maybeSavePattern(graphic);
        if (!canContinue) return; // ⛔ СТОП

        let formData = getData(false, graphic);
        await sendRequestBase(`${url}warehouses`, formData, validate, function () {
            redirectWithLocale('/warehouses');
        });
    });

    $('#update').on('click', async function () {
        let warehouseID = $('#edit-card').attr('data-id');

        if ($('#working-data').is(':checked')) {
            if (checkHoursInSchedule('.two-input-for-schedule')) return;
        }

        schedule();

        if (!graphic) {
            alert(getLocalizedText('scheduleMustFilled'));
            return;
        }

        const canContinue = await maybeSavePattern(graphic);
        if (!canContinue) return; // ⛔ СТОП

        let formData = getData(true, graphic); // з _method=PUT
        await sendRequestBase(`${url}warehouses/${warehouseID}`, formData, validate, function () {
            // Основні дані збережено
        });

        // Якщо не активовано робочі дані — редіректимо одразу
        if (!$('#working-data').is(':checked')) {
            redirectWithLocale('/warehouses');
            return;
        }

        // Оновлення умов (окремо) і лише після цього редіректимо
        let formDataConditions = getConditionsData(graphic);
        await sendRequestBase(
            `${url}warehouses/schedule/update/${warehouseID}`,
            formDataConditions,
            null,
            function () {
                redirectWithLocale('/warehouses');
            }
        );
    });

    $('#select_pattern').on('change', function () {
        var selectedPattern = $(this).find('option:selected');

        if (selectedPattern.hasClass('graphic-pattern')) {
            var schedule = JSON.parse(selectedPattern.attr('data-pattern'));

            for (var i = 0; i < 7; i++) {
                if (schedule[days[i]] === 'holiday') {
                    $('#' + days[i] + '-check')[0].checked = true;
                    for (var j = 1; j < 5; j++) {
                        $('#' + days[i] + '-' + j).val('');
                        $('#' + days[i] + '-' + j).prop('disabled', true);
                    }
                } else {
                    $('#' + days[i] + '-check')[0].checked = false;
                    for (var j = 0; j < 4; j++) {
                        $('#' + days[i] + '-' + (j + 1)).val(schedule[days[i]][j]);
                    }
                }
            }
        }
    });

    $('#graphic_save').on('click', function () {
        schedule();
        let pathArray = window.location.pathname.split('/');
        let formData2 = new FormData();
        formData2.append('_token', csrf);
        formData2.append('graphic', JSON.stringify(graphic));
        formData2.append('conditions', JSON.stringify(conditions));

        function redirect() {
            window.location.replace(url + '/warehouses/' + pathArray[pathArray.length - 1]);
        }

        sendRequest(
            '/warehouses/schedule/update/' + pathArray[pathArray.length - 1],
            formData2,
            null,
            redirect
        );
    });
});

/**
 * Формування основних даних
 * @param {boolean} isUpdate
 */
function getData(isUpdate = false, graphic) {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    let formData = new FormData();

    if (isUpdate) {
        formData.append('_method', 'PUT');
    }

    formData.append('_token', csrf);

    if ($('#working-data').is(':checked')) {
        appendConditions(formData);
        appendGraphic(formData, graphic);
    }

    formData.append('name', $('#name').val());
    const erpVals = $('#erp-warehouse').val();
    if (Array.isArray(erpVals)) {
        erpVals.forEach((v) => formData.append('warehouse_erp_id[]', v));
    } else if (erpVals) {
        formData.append('warehouse_erp_id[]', erpVals);
    }
    formData.append('location_id', $('#location').val());
    formData.append('type_id', $('#type').val());

    return formData;
}

/**
 * Дані лише для умов та графіка
 */
function getConditionsData(graphic) {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    let formData = new FormData();
    formData.append('_token', csrf);

    if ($('#working-data').is(':checked')) {
        appendConditions(formData);
        appendGraphic(formData, graphic);
    }

    return formData;
}

/**
 * Додає conditions у форматі масиву
 */
function appendConditions(formData) {
    conditions.forEach((c, index) => {
        Object.entries(c).forEach(([key, value]) => {
            formData.append(`conditions.${index}.${key}`, value);
        });
    });
}

/**
 * Додає graphic у форматі { day: [..] або "holiday" }
 */
function appendGraphic(formData, graphic) {
    Object.entries(graphic).forEach(([day, times], index) => {
        let value;

        if (times === 'holiday') {
            // залишаємо рядок для вихідних
            value = 'holiday';
        } else {
            // робочі дні — масив з 4 значень
            value = times;
        }

        formData.append(`graphic.${index}`, JSON.stringify({ [day]: value }));
    });
}

/**
 * Якщо обрано зберегти патерн графіка
 */
async function maybeSavePattern(graphic) {
    // якщо вибрано "зберегти як патерн", але назва пуста — СТОП
    if (document.getElementById('schedule_pattern').checked && !$('#pattern').val()) {
        alert(getLocalizedText('enterTemplateName'));
        return false;
    }

    if (document.getElementById('schedule_pattern').checked) {
        let csrf = document.querySelector('meta[name="csrf-token"]').content;
        let formDataPattern = new FormData();

        formDataPattern.append('_token', csrf);
        formDataPattern.append('name', $('#pattern').val());
        formDataPattern.append('schedule', JSON.stringify(graphic));
        formDataPattern.append('type', 'warehouse');

        await sendRequestBase('warehouses/schedule/pattern', formDataPattern);
    }

    return true;
}

/**
 * Валідація
 */
async function validate(response) {
    const fieldGroups = [
        {
            fields: ['name', 'warehouse_erp', 'location_id', 'type_id'],
            container: '#main-data-message',
        },
        { fields: ['graphic', 'conditions'], container: '#working-data-message' },
    ];

    await validateGeneric(response, fieldGroups, '#main-data-message');
}

function checkHoursInSchedule(blockClass) {
    let errorMessageShown = false;
    let hasErrors = false;

    $(blockClass).each(function () {
        const block = $(this);
        const inputs = block.find('input');
        const dayId = inputs.eq(0).attr('id').split('-')[0]; // Monday, Tuesday...

        // Очищаємо попередній бордер
        inputs.removeClass('border-error');

        const isHoliday = $('#' + dayId + '-check')[0].checked;

        // Якщо день вихідний — пропускаємо
        if (isHoliday) return;

        const start = toMinutes(inputs.eq(0).val());
        const end = toMinutes(inputs.eq(1).val());

        // Якщо порожні — помилка
        if (start === null || end === null) {
            inputs.eq(0).addClass('border-error');
            inputs.eq(1).addClass('border-error');

            if (!errorMessageShown) {
                alert(getLocalizedText('scheduleMustFilled'));
                errorMessageShown = true;
            }

            hasErrors = true;
            return;
        }

        // Якщо start >= end — помилка
        if (start >= end) {
            inputs.eq(0).addClass('border-error');
            inputs.eq(1).addClass('border-error');

            if (!errorMessageShown) {
                alert(getLocalizedText('invalidHours'));
                errorMessageShown = true;
            }

            hasErrors = true;
        }
    });

    return hasErrors;
}

function toMinutes(time) {
    if (!time) return null;
    const [h, m] = time.split(':');
    return parseInt(h) * 60 + parseInt(m);
}
