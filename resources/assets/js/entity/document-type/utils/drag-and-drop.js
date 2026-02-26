import { togglePlaceholder } from './togglePlaceholder.js';

export function initializeSortable(
    selector,
    trashSelector,
    addFieldSelector,
    sections,
    options = {}
) {
    const {
        sortableItemClass = 'sortable-item', // новий параметр
    } = options;

    $(selector).sortable({
        connectWith: selector,
        revert: false,
        cursor: 'move',
        cursorAt: { top: 56, left: 56 },
        distance: 10,
        placeholder: 'sortable-placeholder',
        start: function (event, ui) {
            if (ui.item.hasClass('group')) {
                $(trashSelector).show();
                $(addFieldSelector).hide();
            } else {
                $(trashSelector).hide();
                $(addFieldSelector).show();
            }
            $(selector).addClass('sortableList-over');

            const showBodyButton = ui.item.find('.js-chevron-configurator');
            if (showBodyButton.length > 0) showBodyButton.addClass('d-none');

            const directorySelect_1_select = ui.item.find('.select2');
            if (directorySelect_1_select.length > 0) directorySelect_1_select.select2();
        },
        stop: function (event, ui) {
            if (ui.item.hasClass('group')) {
                $(trashSelector).hide();
                $(addFieldSelector).show();
            }
            $(selector).removeClass('sortableList-over');

            const showBodyButton = ui.item.find('.js-chevron-configurator');
            if (showBodyButton.length > 0) showBodyButton.removeClass('d-none');

            // тут вже використовуємо переданий sections
            sections.forEach((section) => {
                const $sortableList = $(`.sortableList#${section}_fields`);
                const $checkbox = $(`#${section}_checked`);
                const $defaultField = $(`#default_${section}_fields`);
                if ($sortableList.children('li').length !== 0) {
                    $checkbox.prop('checked', true);
                    $defaultField.removeClass('d-none');
                } else {
                    $checkbox.prop('checked', false);
                    $defaultField.addClass('d-none');
                }
            });
        },
        over: function (event, ui) {
            if (ui.item.hasClass(sortableItemClass)) {
                $(trashSelector).hide();
                $(addFieldSelector).show();
            }
        },
        out: function (event, ui) {
            if (ui.item.hasClass(sortableItemClass)) {
                $(trashSelector).hide();
                $(addFieldSelector).show();
            }
        },
        update: function (event, ui) {
            // можна додати додаткову логіку при зміні списку
        },
    });
}

export function makeDraggable(element, isList, options = {}) {
    const {
        trashSelector = '#trash',
        addFieldSelector = '#add-field',
        sortableSelector = '.sortableList',
        placeholderSelector = null, // 👈 новий параметр
    } = options;

    element.draggable({
        cursor: 'move',
        cursorAt: { top: 56, left: 56 },
        helper: 'clone',
        revert: 'invalid',
        connectWith: sortableSelector,
        clone: true,
        start: function (event, ui) {
            if (placeholderSelector) $(placeholderSelector).addClass('d-none');
            // console.log('makeDraggable start');

            $(trashSelector).hide();
            $(addFieldSelector).show();
            $(sortableSelector).addClass('sortableList-over');

            if (!isList) {
                var closeButton = ui.helper.find('.removeButtonBaseField');
                if (closeButton.length > 0) {
                    closeButton.addClass('d-none');
                }
            }
        },
        stop: function (event, ui) {
            $(trashSelector).hide();
            $(addFieldSelector).show();
        },
        over: function (event, ui) {
            if (!isList) {
                // Additional behavior specific to list items
            }
        },
    });
}

export function makeDraggableLiElement(
    element,
    isSortableItem,
    additionalSettingsCustomFields = null,
    options = {}
) {
    const {
        sortableSelector = '.sortableList', // дефолт
        draggableClass = 'sortable-item', // новий параметр для класу
        placeholderSelector = null, // 👈 новий параметр
    } = options;

    element.draggable({
        connectToSortable: sortableSelector,
        cursor: 'move',
        cursorAt: { top: 56, left: 56 },
        distance: 10,
        revert: false,
        stop: function (event, ui) {
            if (ui.helper.closest(sortableSelector).length > 0) {
                ui.helper.removeClass(draggableClass); // замість хардкоду 'sortable-item'

                // Remove classes based on conditions
                if (isSortableItem) {
                    ui.helper.removeClass(`${draggableClass} group-create`);

                    var group = ui.helper.find('.accordion-header');
                    if (group.length > 0) group.removeClass('group-create-padding');

                    var contentCreate = ui.helper.find('#contentCreate');
                    if (contentCreate.length > 0)
                        contentCreate.removeClass('group-create-flex-center');

                    var titleElemCreate = ui.helper.find('#titleElemCreate');
                    if (titleElemCreate.length > 0)
                        titleElemCreate.removeClass('group-create-flex');

                    var iconCreate = ui.helper.find('#iconCreate');
                    if (iconCreate.length > 0) iconCreate.removeClass('group-create-padding-end');
                }

                // Remove classes
                ui.helper.find('#header-badge').removeClass('d-none');
                ui.helper.find('.removeButtonBaseField').removeClass('d-none');

                var itemCurrentKey = ui.helper.find('[data-key]');
                var dataCurrentKey = itemCurrentKey.data('key');
                var currentTypeBlock = ui.helper.closest(sortableSelector).data('type-block');

                var directorySelect_1_select = ui.helper.find('.select2');

                var blockDataParamSelect = ui.helper.find('.js-blockDataParam');
                var directoryBlockSelect = ui.helper.find('.js-directoryBlock');
                var parameterBtnShowSelect = ui.helper.find('.js-parameterBtnShow');
                var parameterInputParameterField = ui.helper.find('.js-input-parameter-field');

                var parameterBlockSelect = ui.helper.find('.js-parameterBlock');
                var addItemParameterSelect = ui.helper.find('.js-addItemParameter');
                var parameterListSelect = ui.helper.find('.js-parameter-list');
                var removeButtonParametersSelect = ui.helper.find('.js-removeButtonParameters');
                var addItemInDirectorySelect = ui.helper.find('.js-addItemInDirectory');

                let requiredCheckInput = ui.helper.find('.js-form-check-input');
                let requiredCheckLabel = ui.helper.find('.js-form-check-label');

                let requiredCheckInputIsNumber = ui.helper.find('.js-form-check-input-isNumber');
                let requiredCheckLabelIsNumber = ui.helper.find('.js-form-check-label-isNumber');

                // Process fields
                doctypeFields.forEach((field) => {
                    let countIdItem = 1;
                    let countIdItemIsNumber = 1;

                    if (
                        (field.type.startsWith('select') || field.type.startsWith('label')) &&
                        field.key === dataCurrentKey
                    ) {
                        // Генерація ідентифікаторів
                        let idDirectorySelect = generateId(
                            field,
                            countIdItem,
                            currentTypeBlock,
                            lastIdDirectorySelect,
                            'directorySelect'
                        );
                        let idBlockDataParam = generateId(
                            field,
                            countIdItem,
                            currentTypeBlock,
                            lastIdBlockDataParam,
                            'blockDataParam'
                        );
                        let idDirectoryBlock = generateId(
                            field,
                            countIdItem,
                            currentTypeBlock,
                            lastIdDirectoryBlock,
                            'directoryBlock'
                        );
                        let idParameterBtnShow = generateId(
                            field,
                            countIdItem,
                            currentTypeBlock,
                            lastIdParameterBtnShow,
                            'parameterBtnShow'
                        );
                        let idParameterBlock = generateId(
                            field,
                            countIdItem,
                            currentTypeBlock,
                            lastIdParameterBlock,
                            'parameterBlock'
                        );
                        let idAddItemParameter = generateId(
                            field,
                            countIdItem,
                            currentTypeBlock,
                            lastIdAddItemParameter,
                            'addItemParameter'
                        );
                        let idParameterList = generateId(
                            field,
                            countIdItem,
                            currentTypeBlock,
                            lastIdParameterList,
                            'parameter-list'
                        );
                        let idRemoveButtonParameters = generateId(
                            field,
                            countIdItem,
                            currentTypeBlock,
                            lastIdRemoveButtonParameters,
                            'removeButtonParameters'
                        );
                        let idAddItemInDirectory = generateId(
                            field,
                            countIdItem,
                            currentTypeBlock,
                            lastIdAddItemInDirectory,
                            'addItemInDirectory'
                        );
                        let idParameterInputParameterField = generateId(
                            field,
                            countIdItem,
                            currentTypeBlock,
                            lastIdParameterInputParameterField,
                            'inputParameter'
                        );

                        setFieldAttributes(directorySelect_1_select, 'id', idDirectorySelect);

                        setFieldAttributes(blockDataParamSelect, 'id', idBlockDataParam);
                        setFieldAttributes(directoryBlockSelect, 'id', idDirectoryBlock);
                        setFieldAttributes(parameterBtnShowSelect, 'id', idParameterBtnShow);
                        setFieldAttributes(parameterBlockSelect, 'id', idParameterBlock);
                        setFieldAttributes(addItemParameterSelect, 'id', idAddItemParameter);
                        setFieldAttributes(parameterListSelect, 'id', idParameterList);
                        setFieldAttributes(
                            removeButtonParametersSelect,
                            'id',
                            idRemoveButtonParameters
                        );
                        setFieldAttributes(addItemInDirectorySelect, 'id', idAddItemInDirectory);

                        setFieldAttributes(
                            parameterInputParameterField,
                            'id',
                            idParameterInputParameterField
                        );

                        setFieldModel(
                            directorySelect_1_select,
                            field,
                            additionalSettingsCustomFields,
                            isSortableItem
                        );
                    }

                    if (field.type.startsWith(field.type) && field.key === dataCurrentKey) {
                        let idRequired = generateId(
                            field,
                            countIdItem,
                            currentTypeBlock,
                            lastIdRequiredDictionary,
                            'requiredCheck'
                        );
                        let idRequiredIsNumber = generateId(
                            field,
                            countIdItemIsNumber,
                            currentTypeBlock,
                            lastIdRequiredIsNumberDictionary,
                            'requiredCheckIsNumber'
                        );

                        setFieldAttributes(requiredCheckInput, 'id', idRequired);
                        setFieldAttributes(requiredCheckLabel, 'for', idRequired);

                        setFieldAttributes(requiredCheckInputIsNumber, 'id', idRequiredIsNumber);
                        setFieldAttributes(requiredCheckLabelIsNumber, 'for', idRequiredIsNumber);
                    }
                });

                directorySelect_1_select.select2();
                sortParam($(`[id^="parameter-list"]`), false);
            }

            // ✅ тепер правильно — показує або ховає відповідно до кількості елементів
            if (placeholderSelector) {
                togglePlaceholder(sortableSelector, placeholderSelector);
            }
            // console.log('makeDraggableLiElement stop');

            $(sortableSelector).removeClass('sortableList-over');
        },
    });
}

export function generateId(
    field,
    countIdItem,
    currentTypeBlock,
    dictionary,
    prefix = 'requiredCheck'
) {
    let id = `${prefix}_${field.system}_${field.type}_${field.id}_copy_${countIdItem}_${currentTypeBlock}`;
    if (!dictionary[currentTypeBlock]) {
        dictionary[currentTypeBlock] = [];
    }
    if (dictionary[currentTypeBlock].includes(id)) {
        countIdItem = findNextAvailableId(
            dictionary[currentTypeBlock],
            field,
            countIdItem,
            currentTypeBlock,
            prefix
        );
        id = `${prefix}_${field.system}_${field.type}_${field.id}_copy_${countIdItem}_${currentTypeBlock}`;
    }
    dictionary[currentTypeBlock].push(id);
    return id;
}

function findNextAvailableId(ids, field, countIdItem, currentTypeBlock) {
    let similarIds = ids.filter((existingId) => {
        let regex = new RegExp(
            `${field.system}_${field.type}_${field.id}_(copy_)?(\\d+)_${currentTypeBlock}$`
        );
        return regex.test(existingId);
    });
    if (similarIds.length > 0) {
        let maxCopyNumber = Math.max(
            ...similarIds.map((id) => parseInt(id.match(/copy_(\d+)_/)[1]))
        );
        countIdItem = maxCopyNumber + 1;
    }
    return countIdItem;
}

export function setFieldModel(element, field, additionalSettingsCustomFields, isSortableItem) {
    var labelParameters = isSortableItem ? field.model : additionalSettingsCustomFields.model;
    if (labelParameters !== null) {
        element.val(labelParameters).trigger('change');
    }
}

export function setFieldAttributes(elementSelector, attrValue = 'id', id) {
    elementSelector.attr(attrValue, id);
}

export function sortParam(selector, custom = true) {
    // Ініціалізація sortable
    selector.sortable({
        distance: 10,
        update: function () {
            clearAndRepopulateParameterListItems(custom);
        },
    });
}

// Функція для очищення масиву та запису даних заново з урахуванням нового порядку
function clearAndRepopulateParameterListItems(custom) {
    if (custom === true) {
        parameterListItemsCustom.length = 0; // Очищення масиву
    }

    // Отримання елементів зі списку та запис їх в parameterListItems
    $('.parameter-list .parameter-item').each(function () {
        const value = $(this).attr('data-value');
        const checked = $(this).find('input[type="checkbox"]').prop('checked') ? 1 : 0;

        if (custom === true) {
            // Додавання до parameterListItems
            parameterListItemsCustom.push([{ name: value, is_checked: checked }]);
        }
    });
}
