import { redirectWithLocale } from '../../utils/redirectWithLocale.js';
import { sendRequestBase } from '../../utils/sendRequestBase.js';
import { findListItem } from './utils/search.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();

    const url =
        locale === 'en'
            ? `` // без префіксу
            : `${locale}/`;

    let field_id = 1;
    let documentTypeId = $('#edit-card').attr('data-id');

    findListItem('#searchBarItem', '#actionDoc div', function (element) {
        return element.find('label').text().toLowerCase();
    });

    findListItem('#searchCreateFields', '.document-new-fields ul li', function (element) {
        return element.find('div.accordion-header div p').text().trim().toLowerCase();
    });

    findListItem('#searchCreateFieldsDocuments', '.document-new-fields ul li', function (element) {
        return element.find('div.accordion-header div p').text().trim().toLowerCase();
    });

    findListItem('#searchCreateFieldsAction', '.document-new-fields ul li', function (element) {
        return element.find('div.accordion-header div p').text().trim().toLowerCase();
    });

    findListItem('#searchBarItemModal', '#modalFieldTypeList li', function (element) {
        return element.find('h5').text().toLowerCase();
    });

    function getSettings() {
        let field_blocks = ['header'];
        let field_blocks_custom_header = [];
        console.log(field_blocks_custom_header);
        let field_names = [];
        let settings = {};

        // Зчитування значень з блоків та заповнення масиву
        $('.new-fields-custom-block').each(function () {
            let value = $(this).find('.header-block-title');
            field_blocks_custom_header.push(String(value.attr('data-id-input-value')));
            field_names.push(value.html());
        });

        const params = new URLSearchParams(window.location.search);
        settings['document_kind'] = params.get('document_kind');

        settings['document_type'] = [];

        $('#documents_fields')
            .children('li') // беремо тільки прямі діти
            .not('#documents_placeholder') // виключаємо плейсхолдер
            .each(function () {
                settings['document_type'].push({
                    id: $(this).data('id'),
                    name: $(this).find('.system-title').text().trim(),
                });
            });

        settings['actions'] = {};

        $('#action_fields li').each(function () {
            let actionKey = $(this).data('system'); // edit, delete, copy...
            settings['actions'][actionKey] = {};

            $(this)
                .find("input[type='checkbox']")
                .each(function () {
                    let roleId = $(this).attr('id'); // admin_edit
                    let role = roleId.split('_')[0]; // admin
                    settings['actions'][actionKey][role] = $(this).is(':checked');
                });
        });

        settings['tasks'] = [];

        $('#task-list .js-group-data').each(function (index) {
            const $item = $(this);

            const task = {
                key: $item.data('key'), // унікальний ключ
                type: $item.data('type'), // тип завдання (наприклад 'unload', 'ship' і т.д.)
                order: index + 1, // новий порядок (як у DOM)
                original: $item.data('original'), // початковий порядок
                fixedPosition: $item.data('fixed') || null,
                title: $item.data('title'), // без іконки
            };

            const $switch = $item.find('.form-check-input');
            if ($switch.length) {
                task.enabled = $switch.is(':checked');
            }

            settings['tasks'].push(task);
        });

        settings['fields'] = {};

        settings['header_name'] = $('#accordion-field-title').text();
        field_blocks.forEach((item) => {
            let list = $('#' + item + '_fields').children();
            //console.log(list)
            settings['fields'][item] = getSettingsArray(list);
        });

        settings['custom_blocks'] = {};
        settings['block_names'] = [];
        for (let i = 0; i < field_blocks_custom_header.length; i++) {
            let list = $('#' + field_blocks_custom_header[i] + '_fields').children();
            settings['custom_blocks'][i] = getSettingsArray(list);
            settings['block_names'].push(field_names[i]);
        }

        return settings;
    }

    function getSettingsArray(list) {
        const settingsArray = {};

        for (let i = 0; i < list.length; i++) {
            const li = list.eq(i);
            const type = li.find('.accordion-header').data('type');
            const system = li.data('system');
            const title = li.find('#titleInput_' + system + '_' + type).val();
            const hint = li.find('#descInput_' + system + '_' + type).val() || '';
            const required = li.find('[id^="requiredCheck_"]').prop('checked');
            const requiredIsNumber = li.find('[id^="requiredCheckIsNumber_"]').prop('checked');

            //console.log(type, system)
            const settings = {
                id: field_id,
                name: title,
                type: type,
                system: system,
                required: required,
                hint: hint,
            };

            if (type === 'text') {
                settings.requiredIsNumber = requiredIsNumber;
            }

            if (type === 'select' || type === 'label') {
                const directorySelect = li.find('[id^="directorySelect"]');
                const directoryBlock = li.find('[id^="directoryBlock"]');
                const selectedValue = $(directorySelect).val() || '';
                if (directoryBlock.hasClass('d-none')) {
                    const parameterList = li.find('[id^="parameter-list"]' + ' .parameter-item');
                    const parameterData = [];
                    parameterList.each(function () {
                        const parameterItem = $(this);
                        const parameterName = parameterItem.data('value');
                        const is_checked = parameterItem.data('checked');
                        parameterData.push([
                            {
                                name: `${parameterName}`,
                                is_checked: is_checked,
                            },
                        ]);
                    });
                    settings.data = parameterData;
                    //Виправити на null або прибрати
                    //settings.directory = null;
                } else {
                    settings.directory = selectedValue;
                    //settings.data = null;
                }
            }
            settingsArray[field_id + li.find('[data-key]').data('key') + '_' + field_id] = settings;
            field_id++;
        }

        return settingsArray;
    }

    /**
     * handleDoctypeActions тільки перевіряє форму перед відправкою
     */

    function handleDoctypeActions() {
        const $sectionsCheck = $('.sortableList');
        let isValid = true;

        // Перевірка інпутів
        $sectionsCheck.each(function () {
            const $inputs = $(this).find('input[id*="titleInput"]');
            let isEmptyInput = false;

            $inputs.each(function () {
                if ($(this).val().trim() === '') {
                    isEmptyInput = true;
                    isValid = false;
                    // Показати розділ, якщо селект порожній
                    $(this)
                        .closest('.document-field-accordion-body')
                        .removeClass('d-none')
                        .addClass('d-block');

                    $(this)
                        .closest('.document-field-accordion-body-form')[0]
                        .scrollIntoView({ behavior: 'smooth', block: 'end' });

                    return false; // break $inputs.each
                }
            });

            if (isEmptyInput) {
                // Scroll to the first select with an error within this section
                validateData('.js-validate-input', this);
                return false; // Вихід з кожної функції each
            }
        });

        // Перевірка селектів
        $sectionsCheck.each(function () {
            const $selects = $(this).find('select[id*="directorySelect"]');
            let isSelectEmpty = false;

            $selects.each(function (index, select) {
                // Перевірка чи вибрано значення в поточному селекті
                if ($(select).val() === '') {
                    isSelectEmpty = true;
                    isValid = false;
                    // Показати розділ, якщо селект порожній
                    $(this)
                        .closest('.document-field-accordion-body')
                        .removeClass('d-none')
                        .addClass('d-block');

                    // Прокрутка до розділу, якщо селект пустий
                    $(this)
                        .closest('.document-field-accordion-body-form')[0]
                        .scrollIntoView({ behavior: 'smooth', block: 'end' });

                    return false; // break $selects.each
                } else {
                    $(select).removeClass('error');
                }
            });

            if (isSelectEmpty) {
                validateData('.js-validate-select', this);
                return false; // Вихід з кожної функції each
            }
        });

        return isValid;
    }

    function validateData(id, context) {
        // Знаходимо всі обов'язкові поля, селекти, чекбокси та поля вводу файлів
        let form = document.querySelector(id);

        const requiredInputs = context
            ? context.querySelectorAll('.required-field')
            : form.querySelectorAll('.required-field');
        const requiredSelects = context
            ? context.querySelectorAll('.required-field-select')
            : form.querySelectorAll('.required-field-select');
        const selectsWithError = context
            ? context.querySelectorAll('.required-field-select.error')
            : form.querySelectorAll('.required-field-select.error');
        const requiredCheckboxes = context
            ? context.querySelectorAll('.required-field-switch')
            : form.querySelectorAll('.required-field-switch');
        const requiredFileInputs = context
            ? context.querySelectorAll('input[type="file"].required-field')
            : form.querySelectorAll('input[type="file"].required-field');

        // Перевіряємо, чи всі обов'язкові поля, селекти, чекбокси та поля вводу файлів заповнені
        let allFieldsValidInput = true;
        let allFieldsValidSelect = true;
        let allFieldsValidSwitch = true;
        let allFieldsValidFileInput = true;

        // Reset error class for all required inputs and selects
        requiredInputs.forEach((input) => {
            input.classList.remove('error');
        });

        selectsWithError.forEach((select) => {
            select.classList.remove('error');
        });

        // Validate required inputs
        requiredInputs.forEach((input) => {
            if (input.value.trim() === '') {
                allFieldsValidInput = false;
                input.classList.add('error');
            }
        });

        // Validate required selects
        requiredSelects.forEach((select) => {
            if (select.value === '') {
                allFieldsValidSelect = false;
                select.classList.add('error');
            }
        });

        // Validate required checkboxes
        requiredCheckboxes.forEach((checkbox) => {
            if (!checkbox.checked) {
                allFieldsValidSwitch = false;
            }
        });

        // Validate required file inputs
        requiredFileInputs.forEach((fileInput) => {
            if (fileInput.files.length === 0) {
                allFieldsValidFileInput = false;
            }
        });

        if (
            !allFieldsValidInput ||
            !allFieldsValidSelect ||
            !allFieldsValidSwitch ||
            !allFieldsValidFileInput
        ) {
            // Add the 'was-validated' class to the parent with the class 'js-validate-select'
            if (context) {
                context.querySelectorAll(id).forEach((element) => {
                    element.classList.add('was-validated');
                });
            } else {
                form.classList.add('was-validated');
            }
        }

        return (
            allFieldsValidInput &&
            allFieldsValidSelect &&
            allFieldsValidSwitch &&
            allFieldsValidFileInput
        );
    }

    $('#doctype-save').click(async function () {
        if (!handleDoctypeActions()) return;

        let formData = getData();

        $.ajax({
            url: window.location.origin + '/document-type',
            // url: `${url}document-type`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                redirectWithLocale('/document-type');
            },
            error: function (error) {
                validateDoctype();
            },
        });

        // await sendRequestBase(
        //     `document-type`,
        //     formData,
        //     handleDoctypeActions,
        //     function () {
        //         // redirectWithLocale('/document-type');
        //     }
        // );
    });

    $('#doctype-edit').click(async function () {
        if (!handleDoctypeActions()) return;

        let formData = getData();
        formData.append('_method', 'PUT');

        $.ajax({
            url: window.location.origin + `/document-type/${documentTypeId}`,
            // url: `${url}document-type/${documentTypeId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                redirectWithLocale('/document-type');
            },
            error: function (error) {
                validateDoctype();
            },
        });

        // await sendRequestBase(
        //     `document-type/${documentTypeId}`,
        //     formData,
        //     validateDoctype,
        //     function () {
        //         redirectWithLocale('/document-type');
        //     }
        // );
    });

    $('#draft-save').click(async function () {
        let formData = getData();
        await sendRequestBase(`document-type/draft`, formData, validateDoctype, function () {
            redirectWithLocale('/document-type');
        });
    });

    $('#draft-edit').click(async function () {
        let formData = getData();
        formData.append('_method', 'PUT');

        await sendRequestBase(
            `document-type/draft/${documentTypeId}`,
            formData,
            validateDoctype,
            function () {
                redirectWithLocale('/document-type');
            }
        );
    });

    function getData() {
        let csrf = document.querySelector('meta[name="csrf-token"]').content;
        let settings = getSettings();
        const params = new URLSearchParams(window.location.search);

        let formData = new FormData();
        formData.append('_token', csrf);
        formData.append('name', $('#document-type-name').val());
        formData.append('kind', params.get('document_kind'));
        formData.append('settings', JSON.stringify(settings));

        return formData;
    }

    async function validateDoctype(response) {
        // Твої старі перевірки validateData можна адаптувати сюди
        validateData('#document-type-name-form', null);
    }

    // Attach a change event listener to all select elements with the class 'required-field-select'
    $('.required-field-select').on('change', function () {
        // Check if the selected value is not empty and the element has the 'error' class
        if ($(this).val() !== '' && $(this).hasClass('error')) {
            // If not empty and has 'error' class, remove the 'error' class
            $(this).removeClass('error');
            $(this).addClass('success');
            $('#document-type-switch').addClass('d-none');
        }
    });
});
