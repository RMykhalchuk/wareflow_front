import { getLocalizedText } from '../../localization/document/getLocalizedText.js';
import { sendRequestBase } from '../../utils/sendRequestBase.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';
import { appendAlert } from '../../utils/appendAlert.js';
import { redirectWithLocale } from '../../utils/redirectWithLocale.js';

var header_ids = {};

$(document).ready(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const document_id = $('#document-container').data('id');
    const warehouse_id = $('#document-container').data('warehouse-id');
    const document_type_id = $('#document-container').data('document-type-id');
    const document_type_kind = $('#document-container').data('document-type-kind');

    const locale = getCurrentLocaleFromUrl();

    const url =
        locale === 'en'
            ? `` // без префіксу
            : `${locale}/`;

    $('.doctype-menu-item').click(function () {
        window.location.href = $(this).data('href');
    });

    function validateData(id) {
        let form = document.querySelector('#' + id);
        // Знаходимо всі обов'язкові поля, селекти, чекбокси та поля вводу файлів
        const requiredFields = form.querySelectorAll('.required-field');
        const requiredSelects = form.querySelectorAll('.required-field-select');
        const requiredCheckboxes = form.querySelectorAll('.required-field-switch');
        const requiredFileInputs = form.querySelectorAll('input[type="file"].required-field');

        // Перевіряємо, чи всі обов'язкові поля, селекти, чекбокси та поля вводу файлів заповнені
        let allFieldsValid = true;
        let allFieldsValidSelect = true;
        let allFieldsValidSwitch = true;
        let allFieldsValidFileInput = true;

        requiredFields.forEach((field) => {
            if (field.value === '') {
                allFieldsValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });

        requiredSelects.forEach((select) => {
            if (select.value === '') {
                allFieldsValidSelect = false;
                select.classList.add('error');
            } else {
                select.classList.remove('error');
            }
        });

        requiredCheckboxes.forEach((checkbox) => {
            if (!checkbox.checked) {
                allFieldsValidSwitch = false;
                checkbox.classList.add('is-invalid');
            } else {
                checkbox.classList.remove('is-invalid');
            }
        });

        requiredFileInputs.forEach((fileInput) => {
            if (fileInput.files.length === 0) {
                allFieldsValidFileInput = false;
                fileInput.classList.add('is-invalid');
            } else {
                fileInput.classList.remove('is-invalid');
            }
        });

        if (
            !allFieldsValid ||
            !allFieldsValidSelect ||
            !allFieldsValidSwitch ||
            !allFieldsValidFileInput
        ) {
            form.classList.add('was-validated');
        }

        //console.log(allFieldsValid, allFieldsValidSelect, allFieldsValidSwitch, allFieldsValidFileInput)

        return (
            allFieldsValid &&
            allFieldsValidSelect &&
            allFieldsValidSwitch &&
            allFieldsValidFileInput
        );
    }

    // Attach a change event listener to all select elements with the class 'required-field-select'
    $('.required-field-select').on('change', function () {
        // Check if the selected value is not empty and the element has the 'error' class
        if ($(this).val() !== '' && $(this).hasClass('error')) {
            // If not empty and has 'error' class, remove the 'error' class
            $(this).removeClass('error');
            $(this).addClass('success');
        }
    });

    async function sendRequest(status) {
        const elements = document.getElementsByClassName('custom-block');
        const validationContainer = document.getElementById('sku-error');
        let custom_blocks = {};

        let allFieldsValid = validateData('header_form');
        console.log(allFieldsValid);
        if (allFieldsValid) {
            // Якщо всі поля заповнені, надсилаємо форму на бекенд
            const formData = new FormData();
            let dataObject = {
                header: getFormObjectById('header_form', ''),
                header_ids,
            };

            for (let i = 0; i < elements.length; i++) {
                const element = elements[i];
                const id = element.id;
                custom_blocks[i] = getFormObjectById(id, '-custom');
                if (!validateData(id)) {
                    return;
                }
            }

            dataObject['custom_blocks'] = custom_blocks;

            // ✅ Додаємо дані з глобальної змінної sku_table (window.tableData)
            if (window.tableData && Array.isArray(window.tableData)) {
                dataObject['sku_table'] = window.tableData;
            } else {
                dataObject['sku_table'] = []; // або null, якщо хочеш
            }

            formData.append('_token', csrf);
            formData.append('data', JSON.stringify(dataObject));
            formData.append('status_id', status);

            // Отримуємо параметр складу з URL
            const urlParams = new URLSearchParams(window.location.search);
            const warehouseId = urlParams.get('warehouse_id');

            // Якщо є — додаємо його у formData
            if (warehouseId) {
                formData.append('warehouse_id', warehouseId);
            } else {
                console.warn('⚠️ warehouse_id відсутній у URL');
            }

            formData.append('type_id', $('#header_form').data('type'));

            const fileField = document.getElementsByClassName('upload-file');

            for (let i = 0; i < fileField.length; i++) {
                const files = fileField[i].files; // This gives you a FileList of File objects
                console.log(fileField[i]);

                for (let j = 0; j < files.length; j++) {
                    formData.append(fileField[i].name + '_' + j, files[j]);
                }
            }

            if (!validateTableData(validationContainer)) return;

            await sendRequestBase(`${url}document`, formData, validate, async function (response) {
                try {
                    // 1️⃣ Якщо outcome — робимо reserve
                    if (document_type_kind === 'outcome' && response?.document_id) {
                        const reserveFormData = new FormData();
                        reserveFormData.append('_token', csrf);

                        await sendRequestBase(
                            `${url}document/outcome/leftover/${response.document_id}/reserve`,
                            reserveFormData,
                            validate,
                            function () {
                                console.log('✅ Leftovers reserved for arrival document');
                            }
                        );
                    }

                    // 2️⃣ ТІЛЬКИ ПІСЛЯ цього — redirect
                    redirectWithLocale(
                        '/document/table/' + document_type_id + '?warehouse_id=' + warehouse_id
                    );
                } catch (e) {
                    console.error('❌ Error during reserve + redirect flow', e);
                    appendAlert(
                        document.getElementById('validation-message'),
                        'danger',
                        'Помилка при резервуванні залишків'
                    );
                }
            });
        }
    }

    async function sendRequestUpdate(status) {
        const elements = document.getElementsByClassName('custom-block');
        const validationContainer = document.getElementById('sku-error');

        let custom_blocks = {};

        let allFieldsValid = validateData('header_form');

        if (allFieldsValid) {
            const formData = new FormData();

            let dataObject = {
                header: getFormObjectByIdUpdate('header_form', ''),
                header_ids,
            };

            for (let i = 0; i < elements.length; i++) {
                const element = elements[i];
                const id = element.id;
                custom_blocks[i] = getFormObjectByIdUpdate(id, '-custom');
                if (!validateData(id)) {
                    return;
                }
            }

            dataObject['custom_blocks'] = custom_blocks;

            // ✅ Додаємо дані з глобальної змінної sku_table (window.tableData)
            if (window.tableData && Array.isArray(window.tableData)) {
                dataObject['sku_table'] = window.tableData;
            } else {
                dataObject['sku_table'] = []; // або null, якщо хочеш
            }

            formData.append('_token', csrf);
            formData.append('_method', 'PUT');
            formData.append('data', JSON.stringify(dataObject));
            formData.append('status_id', status);

            formData.append('warehouse_id', warehouse_id);

            if (!validateTableData(validationContainer)) return;

            await sendRequestBase(`${url}document/` + document_id, formData, validate, function () {
                redirectWithLocale(
                    '/document/table/' +
                        $('#header_form').data('type') +
                        '?warehouse_id=' +
                        warehouse_id
                );
            });
        }
    }

    $('#document-save').on('click', function () {
        sendRequest(1);
    });

    $('#update-save').on('click', function () {
        sendRequestUpdate(1);
    });
});

function getFormObjectById(formId, popUpID) {
    const formElement = document.getElementById(formId);
    const formElements = formElement.elements;
    const formObject = {};

    for (let i = 0; i < formElements.length; i++) {
        const element = formElements[i];

        if (!element.name) continue;

        const { name, value, type, tagName } = element;

        // =====================
        // 🔢 ARRAY FIELDS []
        // =====================
        if (name.includes('[]')) {
            const newKey = name.replace('[]', '');

            if (!formObject[newKey]) {
                formObject[newKey] = [];
            }

            if (value === '') continue;

            // ⏱ date / time / range inputs
            if (
                newKey.includes('dateTimeRange_') ||
                newKey.includes('dateTime_') ||
                newKey.includes('range_field_') ||
                tagName === 'INPUT'
            ) {
                formObject[newKey].push(value);
                continue;
            }

            // 🔽 select[]
            if (tagName === 'SELECT') {
                const option = element.options[element.selectedIndex];
                if (!option) continue;

                formObject[newKey].push(option.text);

                if (!header_ids[`${newKey}_id`]) {
                    header_ids[`${newKey}_id`] = [];
                }
                header_ids[`${newKey}_id`].push(option.value);
            }

            continue;
        }

        // =====================
        // 📎 FILE INPUT
        // =====================
        if (type === 'file' && name.includes('uploadFile_')) {
            if (!formObject[name]) {
                formObject[name] = [];
            }

            Array.from(element.files).forEach((file) => {
                formObject[name].push(file.name);
            });

            continue;
        }

        // =====================
        // 🔘 CHECKBOX / RADIO
        // =====================
        if (type === 'checkbox' || type === 'radio') {
            formObject[name] = element.checked;
            continue;
        }

        // =====================
        // 📅 DATE RANGE
        // =====================
        if (name.includes('dateRange') && value !== '') {
            const dates = value.split(' to ');
            formObject[name] = [dates[0], dates[1]];
            continue;
        }

        // =====================
        // 🔽 SINGLE SELECT
        // =====================
        if (name.includes('select_')) {
            if (popUpID === '-custom') {
                header_ids[`${name}_id`] = $(`#${name + popUpID}`).val();
                formObject[name] = $(`#${name}${popUpID}`).find('option:selected').text();
            } else {
                header_ids[`${name}_id`] = $(`#${name}`).val();
                formObject[name] = $(`#${name}${popUpID}`).find('option:selected').text();
            }

            continue;
        }

        // =====================
        // ✏️ DEFAULT INPUT
        // =====================
        formObject[name] = value;
    }

    return formObject;
}

function getFormObjectByIdUpdate(formId, popUpID) {
    const formElement = document.getElementById(formId);
    const formData = new FormData(formElement);

    const formObject = {};

    for (const [key, value] of formData.entries()) {
        if (key.includes('[]')) {
            const newKey = key.replace('[]', '');

            if (value === '') continue;

            // ⏱ date / time / range inputs
            if (
                newKey.includes('dateTimeRange_') ||
                newKey.includes('dateTime_') ||
                newKey.includes('range_field_')
            ) {
                if (!formObject[newKey]) {
                    formObject[newKey] = [];
                }
                formObject[newKey].push(value);
                continue;
            }

            // 🔽 select[]
            const $select = $(`select[name="${key}"]`);

            if ($select.length) {
                const text = $select.find(`option[value="${value}"]`).text();

                if (!formObject[newKey]) {
                    formObject[newKey] = [];
                    header_ids[`${newKey}_id`] = [];
                }

                formObject[newKey].push(text);
                header_ids[`${newKey}_id`].push(value);
            } else {
                // 🧠 fallback — якщо це input[]
                if (!formObject[newKey]) {
                    formObject[newKey] = [];
                }
                formObject[newKey].push(value);
            }
        }

        // 📎 files
        else if (key.includes('uploadFile_') && value !== '') {
            if (!formObject[key]) formObject[key] = [];
            formObject[key].push(value.name);
        }

        // 🔘 switches
        else if (key.includes('switch_')) {
            formObject[key] = document.getElementById(key)?.checked ?? false;
        }

        // 📅 dateRange
        else if (key.includes('dateRange') && value !== '') {
            const dates = value.split(' to ');
            formObject[key] = [dates[0], dates[1]];
        }

        // 🔽 single select
        else if (key.includes('select_field_')) {
            const $select = $(`select[name="${key}"]`);
            formObject[key] = $select.find('option:selected').text();
            header_ids[`${key}_id`] = $select.val();
        }

        // ✏️ everything else
        else {
            formObject[key] = value;
        }
    }

    return formObject;
}

async function validate(response) {
    const validationContainer = document.getElementById('validation-message');

    try {
        // Якщо відповідь не OK — парсимо JSON з помилками
        if (!response.ok) {
            const data = await response.json().catch(() => ({}));

            // Якщо сервер повернув errors
            if (data.errors) {
                // Проходимося по кожному ключу помилки
                Object.entries(data.errors).forEach(([field, messages]) => {
                    appendAlert(
                        validationContainer,
                        'danger',
                        messages.join('<br>') // Якщо кілька повідомлень
                    );
                });
            } else {
                // Якщо errors немає — показуємо загальне повідомлення
                appendAlert(
                    validationContainer,
                    'danger',
                    getLocalizedText('documentSendRequestError') ||
                        'Unknown error. Check input data.'
                );
            }
        }
    } catch (error) {
        // Якщо сталася помилка при парсингу або запиті
        appendAlert(
            validationContainer,
            'danger',
            error.message || getLocalizedText('documentSendRequestError') || 'Unknown error.'
        );
    }
}

function validateTableData(validationContainer) {
    if (!window.tableData || !Array.isArray(window.tableData) || window.tableData.length === 0) {
        appendAlert(
            validationContainer,
            'danger',
            getLocalizedText('createNomenclature') || 'Створіть номенклатуру'
        );
        return false; // ❌ некоректно — таблиця порожня
    }
    return true; // ✅ все ок
}
