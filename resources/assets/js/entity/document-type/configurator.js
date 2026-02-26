import { translit } from '../../utils/translit.js';
import { getLocalizedText } from '../../localization/document-type/getLocalizedText.js';
import {
    initializeSortable,
    makeDraggable,
    makeDraggableLiElement,
    sortParam,
} from './utils/drag-and-drop.js';
import {
    addParameterToList,
    generateListItem,
    generateListItemCreateField,
    initParam,
} from './utils/render.js';
import { bindTitleInput } from './utils/handler.js';
import { togglePlaceholder } from './utils/togglePlaceholder.js';

$(document).ready(function () {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;

    //console.log(doctypeFields)

    // Лічильник
    var click = 0;

    // Знаходимо елемент <ul>, до якого будемо додавати елементи <li>
    const ulElementSystemCustom = $('.arrTypeSystemFieldsCustom');

    const ulElementText = $('.arrTypeSystemFieldsListText');
    const ulElementRange = $('.arrTypeSystemFieldsListRange');

    const ulElementDate = $('.arrTypeSystemFieldsListDate');
    const ulElementDateRange = $('.arrTypeSystemFieldsListDateRange');
    const ulElementDateTimeRange = $('.arrTypeSystemFieldsListDateTimeRange');
    const ulElementTimeRange = $('.arrTypeSystemFieldsListTimeRange');
    const ulElementDateTime = $('.arrTypeSystemFieldsListTimeRange');

    const ulElementSelect = $('.arrTypeSystemFieldsListSelect');
    const ulElementLabel = $('.arrTypeSystemFieldsLabel');

    const ulElementSwitch = $('.arrTypeSystemFieldsListSwitch');
    const ulElementUploadFile = $('.arrTypeSystemFieldsListUploadFile');
    const ulElementComment = $('.arrTypeSystemFieldsListComment');

    const ulElements = {
        text: ulElementText,
        range: ulElementRange,

        select: ulElementSelect,
        label: ulElementLabel,

        date: ulElementDate,
        dateRange: ulElementDateRange,
        dateTimeRange: ulElementDateTimeRange,
        timeRange: ulElementTimeRange,
        dateTime: ulElementDateTime,

        switch: ulElementSwitch,
        uploadFile: ulElementUploadFile,
        comment: ulElementComment,
    };

    // Hover in Header Title
    var header_default = $('#accordion-field-header');
    var titleInput_default = $('#header-block-title-input');
    var titleH5_default = $('#accordion-field-title');

    const addItemParameterButton = $('#customAddItemParameter');
    const inputParameter = $('#inputParameter');
    const addItemDirectoryButton = $('#addItemInDirectory');
    const parameterBlock = $('#parameterBlock');
    const directoryBlock = $('#directoryBlock');
    const parameterList = $('.parameter-list');

    let parameterListItemsCustom = [
        [{ name: getLocalizedText('configuratorParameterListItemsCustomName_1'), is_checked: 1 }],
        [{ name: getLocalizedText('configuratorParameterListItemsCustomName_2'), is_checked: 0 }],
    ];

    const sections = ['nomenclature', 'container', 'services'];

    doctypeFields.forEach((field) => {
        const ulElement =
            field.system && field.key.startsWith('empty')
                ? ulElementSystemCustom
                : ulElements[field.type];
        const liElement =
            field.system && field.key.startsWith('empty')
                ? generateListItemCreateField(field)
                : generateListItem(field);

        ulElement.append(liElement);
    });

    $('#create-custom-btn').on('click', function () {
        const fieldTypes = [
            'text',
            'range',
            'select',
            'label',
            'date',
            'dateRange',
            'dateTime',
            'dateTimeRange',
            'timeRange',
            'switch',
            'uploadFile',
            'comment',
        ];

        for (const fieldType of fieldTypes) {
            //console.log(fieldType)
            const titleInput = $(`#additional-settings-field-type-${fieldType}-title`).val();
            // console.log(titleInput)

            const descInput = $(`#additional-settings-field-type-${fieldType}-desc`).val();
            //console.log(descInput)

            const key = fieldType + '_' + translit(titleInput);
            //console.log(key)

            let directory = null;
            let data = parameterListItemsCustom;

            if (titleInput !== '') {
                let formData = new FormData();
                formData.append('_token', csrf);
                formData.append('key', key);
                formData.append('description', descInput);
                formData.append('title', titleInput);
                formData.append('type', fieldType);
                formData.append('system', 0);

                if (fieldType === 'label') {
                    directory = $(`#additional-settings-field-type-${fieldType}-parameter`).val();
                    //console.log(directory)

                    // Додайте масив значень до formData
                    // formData.append(
                    //     "parameters", JSON.stringify(directory)
                    // );
                    formData.append('model', directory);
                } else if (fieldType === 'select') {
                    if ($('#parameterBlock').hasClass('d-none')) {
                        // If directoryBlock is hidden, read data from parameterBlock
                        // Example: Reading the parameter input data
                        // If directoryBlock is visible, read data from the select element
                        directory = $(`#additional-settings-field-type-${fieldType}-model`).val();
                        formData.append('model', directory);
                        // Add this parameter input data to formData or process as needed
                    } else {
                        formData.append('parameters', JSON.stringify(data));
                    }
                }

                fetch(window.location.origin + '/document-type/field', {
                    method: 'POST',
                    body: formData,
                    processData: false,
                    contentType: false,
                });

                addCustomFieldToList(titleInput, descInput, fieldType, key, directory, data);
                $(`#additional-settings-field-type-${fieldType}-title`).val('');
                $(`#additional-settings-field-type-${fieldType}-desc`).val('');
                directory = $(`#additional-settings-field-type-${fieldType}-model`)
                    .val('')
                    .trigger('change');

                parameterList.empty();
                parameterListItemsCustom = [
                    [
                        {
                            name: getLocalizedText(
                                'configuratorCreateCustomBtnParameterListItemsCustomName_1'
                            ),
                            is_checked: 1,
                        },
                    ],
                    [
                        {
                            name: getLocalizedText(
                                'configuratorCreateCustomBtnParameterListItemsCustomName_2'
                            ),
                            is_checked: 0,
                        },
                    ],
                ];
                initParam(parameterListItemsCustom);
            }
        }

        // todo заюзай resetModal
        $('.additional-settings, #create-custom-btn, #back-custom-btn').hide();
        $('.field-type-list').show();
        $('#custom-modal-title').text(
            getLocalizedText('configuratorCreateCustomBtnCustomModalTitle')
        );

        $('#customField').modal('hide');
        $('.modal-backdrop').remove();
        $('#directoryBlock').removeClass('d-none');
        $('#parameterBlock').addClass('d-none');
    });

    // Використання функції для налаштування перетягування групи та елемента li
    makeDraggable($('#add-field .group'), false, {
        trashSelector: '#trash',
        addFieldSelector: '#add-field',
        sortableSelector: '.sortableList',
    });

    makeDraggable($('#add-field-documents .group'), false, {
        trashSelector: '#trash-documents',
        addFieldSelector: '#add-field-documents',
        sortableSelector: '.sortableListDocuments',
        placeholderSelector: '#documents_placeholder', // 👈 передаємо ID плейсхолдера
    });

    makeDraggable($('#add-field-action .group'), false, {
        trashSelector: '#action-trash',
        addFieldSelector: '#add-field-action',
        sortableSelector: '.sortableListAction',
    });

    // Initialize draggable items for sortableList
    makeDraggableLiElement($('.sortable-item'), true, null, {
        sortableSelector: '.sortableList',
        draggableClass: 'sortable-item',
    });

    makeDraggableLiElement($('.sortable-item-document'), true, null, {
        sortableSelector: '.sortableListDocuments',
        draggableClass: 'sortable-item-document',
        placeholderSelector: '#documents_placeholder', // 👈 передаємо ID плейсхолдера
    });

    makeDraggableLiElement($('.sortable-item-action'), true, null, {
        sortableSelector: '.sortableListAction',
        draggableClass: 'sortable-item-action',
    });

    function addCustomFieldToList(title, desc, type, key, directory, param) {
        const additionalSettingsCustomFields = {
            title: title,
            description: desc,
            system: false,
            type: type,
            key: key,
        };

        if (directory !== null) {
            additionalSettingsCustomFields.model = directory;
            //console.log(directory)
        } else {
            additionalSettingsCustomFields.model = null;
        }

        if (param !== null) {
            additionalSettingsCustomFields.parameters = param;
            //console.log(directory)
        } else {
            additionalSettingsCustomFields.parameters = null;
        }

        doctypeFields.push(additionalSettingsCustomFields);
        const ulElement = ulElements[type];
        const liElement = generateListItem(additionalSettingsCustomFields);
        //console.log(liElement)

        ulElement.append(liElement);
        makeDraggable(liElement, false);
        makeDraggableLiElement(liElement, false, additionalSettingsCustomFields);
    }

    initializeSortable('.sortableList', '#trash', '#add-field', sections, {
        sortableItemClass: 'sortable-item',
    });

    initializeSortable(
        '.sortableListDocuments',
        '#trash-documents',
        '#add-field-documents',
        sections,
        { sortableItemClass: 'sortable-item-document' }
    );

    initializeSortable('.sortableListAction', '#action-trash', '#add-field-action', sections, {
        sortableItemClass: 'sortable-item-action',
    });

    function makeDroppableTrash(
        trashSelector,
        sortableSelector = null,
        placeholderSelector = null
    ) {
        $(trashSelector).droppable({
            drop: function (event, ui) {
                // Remove IDs from dictionaries
                removeFromDictionaries(ui.draggable);

                // console.log('droppable droppable');

                ui.draggable.remove();
                if (placeholderSelector && sortableSelector) {
                    // 🧩 Виконуємо після оновлення сортування
                    $(sortableSelector).sortable('refresh');

                    // 🕒 Мінімальна затримка дозволяє DOM завершити оновлення
                    requestAnimationFrame(() => {
                        togglePlaceholder(sortableSelector, placeholderSelector);
                    });
                }
            },
        });
    }

    function removeFromDictionaries(draggable) {
        var currentTypeBlock = draggable.closest('.sortableList').data('type-block');

        removeIdFromDictionary(
            lastIdDirectorySelect,
            currentTypeBlock,
            draggable.find('.select2').attr('id')
        );
        removeIdFromDictionary(
            lastIdRequiredDictionary,
            currentTypeBlock,
            draggable.find('.js-form-check-input').attr('id')
        );
        removeIdFromDictionary(
            lastIdRequiredIsNumberDictionary,
            currentTypeBlock,
            draggable.find('.js-form-check-input-isNumber').attr('id')
        );
    }

    function removeIdFromDictionary(dictionary, currentTypeBlock, idToRemove) {
        if (dictionary[currentTypeBlock]) {
            const index = dictionary[currentTypeBlock].indexOf(idToRemove);
            if (index !== -1) {
                dictionary[currentTypeBlock].splice(index, 1);
            }
        }
    }

    makeDroppableTrash('#trash');
    makeDroppableTrash('#trash-documents', '.sortableListDocuments', '#documents_placeholder');
    makeDroppableTrash('#action-trash');

    //Delete field in DB
    $('body').on('click', '.group .removeButton', function () {
        var dataKey = $(this).closest('.group').find('.system-title').attr('data-key');
        fetch(window.location.origin + '/document-type/field/' + dataKey, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Content-Type': 'application/json',
            },
        });

        $(this).closest('.group').remove();
    });

    //Delete field in create field structure
    $('body').on('click', '.group [id^="removeButton"]', function () {
        $(this).closest('.group').remove();
    });

    $('body').on('click', "input[type='checkbox']", function () {
        const id = $(this).attr('id');
        const isChecked = $(this).is(':checked');

        if (id.startsWith('requiredCheck_')) {
            if (isChecked) {
                $(this)
                    .closest('.group')
                    .find('#field-badge-required.d-none')
                    .removeClass('d-none');
            } else {
                $(this).closest('.group').find('#field-badge-required').addClass('d-none');
            }
        }
        if (id.startsWith('requiredCheckIsNumber_')) {
            // Обробка подій для requiredCheckIsNumber_
            if (isChecked) {
                $(this)
                    .closest('.group')
                    .find('#field-badge-required-isNumber.d-none')
                    .removeClass('d-none');
            } else {
                $(this).closest('.group').find('#field-badge-required-isNumber').addClass('d-none');
            }
        }
    });

    // Функція для обробки подій наведення курсора і відведення курсора
    function handleBlockHover(header, titleInput, titleH5, BtnDelete = null) {
        header.hover(
            function () {
                header.addClass('header-hover');
                titleInput.removeClass('d-none');
                titleH5.addClass('d-none');
                if (BtnDelete !== null) {
                    BtnDelete.removeClass('d-none');
                }
            },
            function () {
                var inputText = titleInput.val();
                titleInput.addClass('d-none');
                titleH5.removeClass('d-none').text(inputText);
                if (BtnDelete !== null) {
                    BtnDelete.addClass('d-none');
                }
                header.removeClass('header-hover');
            }
        );
    }

    handleBlockHover(header_default, titleInput_default, titleH5_default);

    $('#add-new-block-item').click(function () {
        click += 1;
        var inputValue = getLocalizedText('configuratorAddNewBlockItemInputValue');

        // Створення нового блоку div з вказаним вмістом за допомогою шаблонних рядків
        var newDiv = $(`
        <div class="new-fields-custom-block">
            <div class="accordion-field-header align-items-center d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <h4 class="m-0 header-block-title fw-bolder" data-id-input-value="custom_block-${click}">${inputValue}</h4>
                    <input type="text" class="m-0 fw-bolder p-0 w-100 header-block-title-input bg-transparent border-0 d-none" value="${inputValue}">
                </div>
                <button class="btn btn-flat-secondary p-25 d-none delete-new-block-item">
                    <img src="/assets/icons/entity/document-type/close-field-base.svg" alt="close-field-base">
                </button>
            </div>
            <ul class="sortableList sortableListStyle" data-type-block="custom-block-${click}" id="custom_block-${click}_fields"></ul>
        </div>`);

        var fieldsList = $('.fields-list');
        var lastCustomBlock = fieldsList.find('.new-fields-custom-block').last();

        if (lastCustomBlock.length) {
            lastCustomBlock.after(newDiv);
        } else {
            fieldsList.children('div').first().after(newDiv);
        }

        // ініціалізація
        var header = newDiv.find('.accordion-field-header');
        var titleInput = header.find('.header-block-title-input');
        var titleH5 = header.find('h4');
        var BtnDelete = header.find('.delete-new-block-item');

        // Виклик функції для обробки подій наведення курсора і відведення курсора
        handleBlockHover(header, titleInput, titleH5, BtnDelete);

        // Обробник події кліку на кнопку видалення
        BtnDelete.click(function () {
            newDiv.remove();
        });

        initializeSortable('.sortableList', '#trash', '#add-field', sections, {
            sortableItemClass: 'sortable-item',
        });

        // Ініціалізація draggable для нового блоку
        makeDraggableLiElement(newDiv.find('.sortable-item'), true);
    });

    // Обробка подій наведення курсора і відведення курсора для зарендерених блоків
    $('.new-fields-custom-block').each(function () {
        var renderEditDivCustomBlock = $(this);
        var header = renderEditDivCustomBlock.find('.accordion-field-header');
        var titleInput = header.find('.header-block-title-input');
        var titleH5 = header.find('h4');
        var BtnDelete = header.find('.delete-new-block-item');

        // Виклик функції для обробки подій наведення курсора і відведення курсора
        handleBlockHover(header, titleInput, titleH5, BtnDelete);

        // Обробник події кліку на кнопку видалення
        BtnDelete.off('click').on('click', function () {
            renderEditDivCustomBlock.remove();
        });
    });

    // Додаємо обробник події на клік по документу для всіх елементів з класом 'js-parameterBtnShow'
    $(document).on('click', '.js-parameterBtnShow', function () {
        // Знаходимо блок параметрів, який знаходиться в батьківському елементі кнопки, на яку було натиснуто
        var parameterBlock = $(this).closest('.js-blockDataParam').find('.js-parameterBlock');
        var directoryBlock = $(this).closest('.js-blockDataParam').find('.js-directoryBlock');

        // Перевіряємо, чи існує блок параметрів і чи він має клас 'd-none'
        if (parameterBlock.length > 0 && parameterBlock.hasClass('d-none')) {
            // Якщо так, прибираємо клас 'd-none', щоб показати блок
            parameterBlock.removeClass('d-none');
        }

        // Перевіряємо, чи існує блок параметрів і чи він має клас 'd-none'
        if (directoryBlock.length > 0 && !directoryBlock.hasClass('d-none')) {
            // Якщо так, прибираємо клас 'd-none', щоб показати блок
            directoryBlock.addClass('d-none');
        }
    });

    $(document).on('click', '.js-addItemInDirectory', function () {
        // Знаходимо блок параметрів, який знаходиться в батьківському елементі кнопки, на яку було натиснуто
        var parameterBlock = $(this).closest('.js-blockDataParam').find('.js-parameterBlock');
        var directoryBlock = $(this).closest('.js-blockDataParam').find('.js-directoryBlock');

        // Перевіряємо, чи існує блок параметрів і чи він має клас 'd-none'
        if (parameterBlock.length > 0 && !parameterBlock.hasClass('d-none')) {
            // Якщо так, додаємо клас 'd-none', щоб приховати блок параметрів
            parameterBlock.addClass('d-none');
        }

        // Перевіряємо, чи існує блок директорії і чи він має клас 'd-none'
        if (directoryBlock.length > 0 && directoryBlock.hasClass('d-none')) {
            // Якщо так, прибираємо клас 'd-none', щоб показати блок директорії
            directoryBlock.removeClass('d-none');
        }
    });

    $('body').on('click', '#parameterBtnShow', function () {
        parameterBlock.removeClass('d-none');
        directoryBlock.addClass('d-none');
    });

    $('body').on('click', '#addItemInDirectory', function () {
        parameterBlock.addClass('d-none');
        directoryBlock.removeClass('d-none');
    });

    initParam(parameterListItemsCustom);

    addItemParameterButton.on('click', () => {
        const value = inputParameter.val().trim();
        if (value) {
            const checked = 0;
            //console.log(checked)
            addParameterToList(value, checked, null);
            inputParameter.val('');

            parameterListItemsCustom.push([{ name: value, is_checked: 0 }]);
            //console.log(parameterListItemsCustom)
        }
    });

    // Handle clicks on the "Добавити параметр" buttons with the common class
    $('body').on('click', '[id^="addItemParameter"]', function () {
        const buttonId = $(this).attr('id');
        const startIndex = buttonId.indexOf('_') + 1; // Знайти позицію першого підкреслення і додати 1, щоб пропустити його
        const blockNumber = buttonId.substring(startIndex); // Отримати підстроку після першого підкреслення
        //console.log(blockNumber);

        // Extract the block number from the clicked button's class

        // Find the corresponding input element and checkbox
        const inputParameter = $(`#inputParameter_${blockNumber}`);

        const value = inputParameter.val().trim();
        if (value) {
            const checked = 0;
            addParameterToList(value, checked, blockNumber);
            inputParameter.val('');
        }

        sortParam($(`#parameter-list_${blockNumber}`), false);
    });

    sortParam($(`.parameter-list`));

    sortParam($(`[id^="parameter-list"]`), false);

    $('.parameter-list').on('click', '.removeButtonParemeters', function () {
        const parameterItem = $(this).closest('.parameter-item');
        const value = parameterItem.attr('data-value');
        const checked = parameterItem.find('input[type="radio"]').prop('checked');

        // Find and remove the element from parameterListItems
        for (let i = 0; i < parameterListItemsCustom.length; i++) {
            const group = parameterListItemsCustom[i];
            const index = group.findIndex((parameter) => parameter.name === value);
            if (index !== -1) {
                group.splice(index, 1);
                if (group.length === 0) {
                    parameterListItemsCustom.splice(i, 1); // Remove the empty sub-array
                }
                break; // Exit the loop if the item is found and removed
            }
        }

        parameterItem.remove();

        if (checked) {
            // If the removed item was checked, find the next available item and set it as checked
            let nextItemChecked = false;
            for (const parameterGroup of parameterListItemsCustom) {
                for (const parameter of parameterGroup) {
                    const item = $(`.parameter-item[data-value="${parameter.name}"]`);
                    if (item.length > 0 && !nextItemChecked) {
                        item.find('input[type="radio"]').prop('checked', true);
                        item.find('.js-input-parameter').addClass('checked-js-input-parameter');
                        item.attr('data-checked', 1); // Update data-checked attribute
                        nextItemChecked = true;
                    } else if (item.length > 0) {
                        item.attr('data-checked', 0); // Update data-checked attribute for other items
                    }
                }
            }

            if (!nextItemChecked) {
                // If there are no more available items, find the first one and set it as checked
                for (const parameterGroup of parameterListItemsCustom) {
                    for (const parameter of parameterGroup) {
                        const item = $(`.parameter-item[data-value="${parameter.name}"]`);
                        if (item.length > 0) {
                            item.find('input[type="radio"]').prop('checked', true);
                            item.find('.js-input-parameter').addClass('checked-js-input-parameter');
                            item.attr('data-checked', 1); // Update data-checked attribute
                            break;
                        }
                    }
                }
            }
        }
    });

    // Додаємо обробку подій на батьківський контейнер "parameter-list_56"
    $('body').on('click', '[class^="removeButtonParemeters"]', function () {
        const parameterItem = $(this).closest('.parameter-item');
        const value = parameterItem.attr('data-value');
        const checked = parameterItem.find('input[type="radio"]').prop('checked');

        // Remove the element parameterItem
        parameterItem.remove();

        if (checked) {
            // If the removed item was checked, find the next available item and set it as checked
            let nextItemChecked = false;
            const parameterItems = $('[class^="parameter-list"] .parameter-item');
            parameterItems.each(function (index, item) {
                const dataValue = $(item).attr('data-value');
                const dataChecked = $(item).attr('data-checked');

                if (!nextItemChecked && dataValue !== value && dataChecked !== '1') {
                    $(item).find('input[type="radio"]').prop('checked', true);
                    $(item).find('.js-input-parameter').addClass('checked-js-input-parameter');
                    $(item).attr('data-checked', 1); // Update data-checked attribute
                    nextItemChecked = true;
                } else {
                    $(item).attr('data-checked', 0); // Update data-checked attribute for other items
                }
            });

            if (!nextItemChecked) {
                // If there are no more available items, find the first one and set it as checked
                const firstItem = parameterItems.first();
                firstItem.find('input[type="radio"]').prop('checked', true);
                firstItem.find('.js-input-parameter').addClass('checked-js-input-parameter');
                firstItem.attr('data-checked', 1); // Update data-checked attribute
            }
        }
    });

    $('.parameter-list').on('change', 'input[type="radio"]', function () {
        const parameterItem = $(this).closest('.parameter-item');
        const parameterList = parameterItem.closest('.parameter-list');
        const checkboxes = parameterList.find('input[type="radio"]');
        const value = parameterItem.data('value');
        const isChecked = $(this).prop('checked');

        if (!isChecked) {
            // If the clicked radio is being unchecked, no need to update the data-checked attribute.
            return;
        }

        // Uncheck other radio inputs in the same parameter list and update data-checked attribute
        parameterList
            .find('.parameter-item')
            .not(parameterItem)
            .each(function () {
                $(this).find('input[type="radio"]').prop('checked', false);
                $(this).attr('data-checked', 0);
            });

        parameterList.find('.js-input-parameter').removeClass('checked-js-input-parameter');

        // Add the 'checked-js-input-parameter' class to the current element
        parameterItem.find('.js-input-parameter').addClass('checked-js-input-parameter');

        // Update the data-checked attribute for the current element
        parameterItem.attr('data-checked', isChecked ? 1 : 0);

        for (const parameterGroup of parameterListItemsCustom) {
            for (const parameter of parameterGroup) {
                if (parameter.name === value) {
                    parameter.is_checked = isChecked ? 1 : 0;
                } else {
                    parameter.is_checked = 0;
                }
            }
        }
    });

    $('body').on('change', 'input[type="radio"]', function () {
        const parameterItem = $(this).closest('.parameter-item');
        const parameterList = parameterItem.closest("[id^='parameter-list']");
        const isChecked = $(this).prop('checked');

        if (!isChecked) {
            // If the clicked radio is being unchecked, no need to update the data-checked attribute.
            return;
        }

        // Uncheck other radio inputs in the same parameter list and update data-checked attribute
        parameterList
            .find('.parameter-item')
            .not(parameterItem)
            .each(function () {
                $(this).find('input[type="radio"]').prop('checked', false);
                $(this).attr('data-checked', 0);
            });

        parameterList.find('.js-input-parameter').removeClass('checked-js-input-parameter');

        // Add the 'checked-js-input-parameter' class to the current element
        parameterItem.find('.js-input-parameter').addClass('checked-js-input-parameter');

        // Update the data-checked attribute for the current element
        parameterItem.attr('data-checked', isChecked ? 1 : 0);
    });

    // Для основного списку
    bindTitleInput('.fields-list', '[id^="titleInput_"]', '[id^="titleElem"] p.system-title');

    // Для іншого списку, створити поле
    bindTitleInput('.fields-list', '[id^="titleInput_"]', 'p.system-title');
});
