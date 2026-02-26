import { getLocalizedText } from '../../../localization/document-type/getLocalizedText.js';
import { translateDocTypesFieldsName } from '../../../localization/document-type/translateDocTypesFieldsName.js';
import { translateDictionariesOption } from '../../../localization/utils/translateDictionariesOption.js';

export function generateListItem(field) {
    if (field.description === null) {
        field.description = getLocalizedText('configuratorGenerateListItemFieldDesc');
    }
    //console.log(field)
    const li = `
        <li class="group sortable-item" data-desc="${field.description}" data-system="${field.system}">
            <div class="accordion-header ui-accordion-header mb-0 bg-white" data-type="${field.type}">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center justify-content-start">
                        <img class="pe-1" src="/assets/icons/entity/document-type/${getIconSrc(field.type)}" alt="${field.type}">
                        <p class="system-title m-0" data-key="${field.key}">${translateDocTypesFieldsName(field.title)}</p>
                    </div>
                    ${field.system ? getSystemBadge(field.system) : getSystemBadge(field.system) + getRemoveButton()}
                </div>
            </div>
            <div class="document-field-accordion-body d-none" id="field-accordion-body">
                <div class="document-field-accordion-body-form">
                    <div class="mb-1">
                     <div class="js-validate-input">
                            <label class="form-label" for="titleInput_${field.system}_${field.type}">${getLocalizedText('configuratorGenerateListItemFieldLiInputTitle')}</label>
                            <input id="titleInput_${field.system}_${field.type}"
                            class="form-control required-field"
                            type="text"
                            placeholder="${getLocalizedText('configuratorGenerateListItemFieldLiInputPlaceholder')}"
                            value="${translateDocTypesFieldsName(field.title)}"
                            required>
                            <div class="valid-feedback">${getLocalizedText('configuratorGenerateListItemFieldLiInputValidFeedback')}</div>
                            <div class="invalid-feedback">${getLocalizedText('configuratorGenerateListItemFieldLiInputInvalidFeedback')}</div>
                        </div>
                    </div>
                    ${getAdditionalFields(field.type, field.system, field.description, field.id, field.parameters, field.model)}
                </div>
                <hr>
                <div class="document-field-accordion-body-footer d-flex align-items-center justify-content-end">

                 ${
                     field.type === 'text'
                         ? `
                    <div class="form-check form-check-warning pe-1">
                        <input type="checkbox" class="js-form-check-input-isNumber form-check-input" id="requiredCheckIsNumber_${field.system}_${field.type}">
                        <label class="js-form-check-label-isNumber form-check-label" for="requiredCheckIsNumber_${field.system}_${field.type}">${getLocalizedText('configuratorGenerateListItemFieldLiFooterIsNumber')}</label>
                    </div>`
                         : ''
                 }

                    <div class="form-check form-check-warning pe-1">
                        <input type="checkbox" class="js-form-check-input form-check-input" id="requiredCheck_${field.system}_${field.type}">
                        <label class="js-form-check-label form-check-label" for="requiredCheck_${field.system}_${field.type}">${getLocalizedText('configuratorGenerateListItemFieldLiFooterIsRequired')}</label>
                    </div>
                    <div>
                        <button type="button" id="removeButton_${field.system}_${field.type}" class="btn btn-flat-danger  d-flex align-items-center">
                            <img class="trash-red" src="/assets/icons/entity/document-type/trash-red2.svg" alt="trash-red2">
                            <span>${getLocalizedText('configuratorGenerateListItemFieldLiFooterDeleteText')}</span>
                        </button>
                    </div>
                </div>
            </div>
        </li>`;

    return $(li);
}

export function generateListItemCreateField(field) {
    if (field.description === null) {
        field.description = getLocalizedText('configuratorGenerateListItemCreateFieldFieldDesc');
    }
    const li = `
    <li class="group sortable-item group-create" data-desc="${field.description}" data-system="${field.system}">
      <div class="group-create-padding accordion-header ui-accordion-header mb-0 bg-white" data-type="${field.type}">
        <div class="d-flex group-create-flex-center justify-content-between align-items-center" id="contentCreate">
          <div class="group-create-flex d-flex align-items-center justify-content-start" id="titleElemCreate">
            <img class="group-create-padding-end pe-1" id="iconCreate" src="/assets/icons/entity/document-type/${getIconSrc(field.type)}" alt="${field.type}">
            <p class="system-title m-0 text-center" data-key="${field.key}">${translateDocTypesFieldsName(field.title)}</p>
          </div>
          ${field.system ? getSystemBadge(true) : getSystemBadge(false) + getRemoveButton()}
        </div>
      </div>
      <div class="document-field-accordion-body d-none" id="field-accordion-body">
        <div class="document-field-accordion-body-form">
          <div class="mb-1">
          <div class="js-validate-input">
                            <label class="form-label" for="titleInput_${field.system}_${field.type}">${getLocalizedText('configuratorGenerateListItemCreateFieldFieldLiInputTitle')}</label>
                            <input id="titleInput_${field.system}_${field.type}"
                            class="form-control required-field"
                            type="text"
                            placeholder="${getLocalizedText('configuratorGenerateListItemCreateFieldFieldLiInputPlaceholder')}"
                            value=""
                            required>
                            <div class="valid-feedback">${getLocalizedText('configuratorGenerateListItemCreateFieldFieldLiInputValidFeedback')}</div>
                            <div class="invalid-feedback">${getLocalizedText('configuratorGenerateListItemCreateFieldFieldLiInputInvalidFeedback')}</div>
                        </div>
                    </div>
                    ${getAdditionalFields(field.type, field.system, field.description, field.id, field.parameters, field.model)}
        </div>
        <hr>

        <div class="document-field-accordion-body-footer d-flex align-items-center justify-content-end">

                 ${
                     field.type === 'text'
                         ? `
                    <div class="form-check form-check-warning pe-1">
                        <input type="checkbox" class="js-form-check-input-isNumber form-check-input" id="requiredCheckIsNumber_${field.system}_${field.type}">
                        <label class="js-form-check-label-isNumber form-check-label" for="requiredCheckIsNumber_${field.system}_${field.type}">${getLocalizedText('configuratorGenerateListItemCreateFieldFieldLiFooterIsNumber')}</label>
                    </div>`
                         : ''
                 }

                    <div class="form-check form-check-warning pe-1">
                        <input type="checkbox" class="js-form-check-input form-check-input" id="requiredCheck_${field.system}_${field.type}">
                        <label class="js-form-check-label form-check-label" for="requiredCheck_${field.system}_${field.type}">${getLocalizedText('configuratorGenerateListItemCreateFieldFieldLiFooterIsRequired')}</label>
                    </div>
                    <div>
                        <button type="button" id="removeButton_${field.system}_${field.type}" class="btn btn-flat-danger  d-flex align-items-center">
                            <img class="trash-red" src="/assets/icons/entity/document-type/trash-red2.svg" alt="trash-red2">
                            <span>${getLocalizedText('configuratorGenerateListItemCreateFieldFieldLiFooterDeleteText')}</span>
                        </button>
                    </div>
        </div>
      </div>
    </li>`;
    return $(li);
}

function getIconSrc(type) {
    switch (type) {
        case 'text':
            return 'letter-case.svg';
        case 'range':
            return 'letter-case.svg';
        case 'date':
            return 'calendar-event.svg';
        case 'dateRange':
            return 'calendar-event.svg';
        case 'dateTime':
            return 'calendar-event.svg';
        case 'dateTimeRange':
            return 'calendar-event.svg';
        case 'timeRange':
            return 'clock.svg';
        case 'select':
            return 'arrow-down-circle.svg';
        case 'label':
            return 'label.svg';
        case 'switch':
            return 'checkbox.svg';
        case 'uploadFile':
            return 'upload.svg';
        case 'comment':
            return 'align-justified.svg';
        default:
            return 'users.svg';
    }
}

function getSystemBadge(system) {
    //console.log(system)
    return `
        <div class="d-flex d-none" id="header-badge">
            <div>
                <span class="badge badge-light-secondary mx-2 d-none" id="field-badge-required-isNumber">${getLocalizedText('configuratorGetSystemBadgeIsNumber')}</span>
                <span class="badge badge-light-secondary mx-2 d-none" id="field-badge-required">${getLocalizedText('configuratorGetSystemBadgeIsRequired')}</span>
                ${system ? '<span class="badge badge-light-secondary mx-2">' + getLocalizedText('configuratorGetSystemBadgeIsSystem') + '</span>' : ''}
            </div>
            <div class="js-chevron-configurator">
                <img id="accordion-chevron" width="16px" src="/assets/icons/entity/document-type/chevron-right.svg" alt="chevron">
            </div>
        </div>`;
}

function getRemoveButton() {
    return `<div class="removeButtonBaseField removeButton"><img src="/assets/icons/entity/document-type/close-field-base.svg" alt="close-field-base"></div>`;
}

function getAdditionalFields(type, system, description, id, parameters = null, model = null) {
    let parameterListItems = []; // Оголошуємо порожній масив
    // Перевірити, чи є параметри та перетворити їх у бажаний формат
    //console.log(parameters)

    if (parameters !== null) {
        if (typeof parameters === 'string') {
            // Якщо parameters - рядок, то робимо JSON парсинг
            parameterListItems = JSON.parse(parameters);
        } else {
            // Якщо parameters вже є об'єктом, залишаємо його без змін
            parameterListItems = parameters;
        }
    }

    switch (type) {
        case 'text':
            return `
                <div class="mb-1">
                    <label class="form-label" for="descInput_${system}_${type}">${getLocalizedText('configuratorGetAdditionalFieldsSwitchTypeLabel')}</label>
                    <input id="descInput_${system}_${type}" class="form-control" type="text" placeholder="${getLocalizedText('configuratorGetAdditionalFieldsSwitchTypePlaceholder')}" value="${translateDocTypesFieldsName(description)}">
                </div>`;
        case 'date':
            return `
                <div class="mb-1">
                    <label class="form-label" for="descInput_${system}_${type}">${getLocalizedText('configuratorGetAdditionalFieldsSwitchTypeLabel')}</label>
                    <input id="descInput_${system}_${type}" class="form-control" type="text" placeholder="${getLocalizedText('configuratorGetAdditionalFieldsSwitchTypePlaceholder')}" value="${translateDocTypesFieldsName(description)}">
                </div>`;
        case 'select':
            return `
                <div class="mb-1">
                    <label class="form-label" for="descInput_${system}_${type}">${getLocalizedText('configuratorGetAdditionalFieldsSwitchTypeLabel')}</label>
                    <input id="descInput_${system}_${type}" class="form-control" type="text" placeholder="${getLocalizedText('configuratorGetAdditionalFieldsSwitchTypePlaceholder')}" value="${translateDocTypesFieldsName(description)}">
                </div>

                <div id="blockDataParam_${id}" class="js-blockDataParam">
                <div id="directoryBlock_${id}" class="js-error-select2 js-directoryBlock js-validate-select mb-1 ${model === null ? '' : 'd-none'}" >

                    <label class="form-label js-label-directorySelect" for="directorySelect_${system}_${type}">${getLocalizedText('configuratorGetAdditionalFieldsSwitchTypeLabelSelect')}</label>
                    <select id="directorySelect_${system}_${type}" class="select2 form-select required-field-select" required data-placeholder="${getLocalizedText('configuratorGetAdditionalFieldsSwitchTypePlaceholderSelect')}">
                        <option value=""></option>
                        ${generateSelectOptions(dictionaryList)}
                    </select>

                    <button id="parameterBtnShow_${id}" class="js-parameterBtnShow btn text-primary mt-1">${getLocalizedText('configuratorGetAdditionalFieldsSwitchTypeParameterBtnShow')}</button>
                </div>

                <div id="parameterBlock_${id}" class="js-parameterBlock mb-1 ${parameters === null ? 'd-none' : ''} ">
                <label class="form-label" for="inputParameter">${getLocalizedText('configuratorGetAdditionalFieldsSwitchTypeInputParameterLabel')}</label>
                <div class="d-flex row mx-0" style="gap: 16px">
                    <div class="col-9 px-0">
                        <input id="inputParameter_${id}" class="js-input-parameter-field form-control" type="text"
                               placeholder="${getLocalizedText('configuratorGetAdditionalFieldsSwitchTypeInputParameterPlaceholder')}"/>
                    </div>

                    <button id="addItemParameter_${id}"
                            class="js-addItemParameter btn btn-outline-primary flex-grow-1 col-2 text-primary">
                        ${getLocalizedText('configuratorGetAdditionalFieldsSwitchTypeInputAddItemParameter')}
                    </button>
                </div>
                <ul id="parameter-list_${id}" class="js-parameter-list  p-0 col-9">
                ${initParamBD(parameterListItems, id)}
                </ul>

                <button id="addItemInDirectory_${id}" class="js-addItemInDirectory btn text-primary mt-1">${getLocalizedText('configuratorGetAdditionalFieldsSwitchTypeInputAddItemInDirectory')}</button>

                </div>
                </div>
                `;

        case 'label':
            return `
                <div class="mb-1">
                    <label class="form-label" for="descInput_${system}_${type}">${getLocalizedText('configuratorGetAdditionalFieldsSwitchTypeLabel')}</label>
                    <input id="descInput_${system}_${type}" class="form-control" type="text" placeholder="${getLocalizedText('configuratorGetAdditionalFieldsSwitchTypePlaceholder')}" value="${translateDocTypesFieldsName(description)}">
                </div>

                <div class="mb-1 js-error-select2 js-validate-select">
                    <label class="form-label" for="directorySelect_${system}_${type}">${getLocalizedText('configuratorGetAdditionalFieldsSwitchTypeLabelSelect')}</label>
                    <select  id="directorySelect_${system}_${type}" class="select2 form-select required-field-select" data-placeholder="${getLocalizedText('configuratorGetAdditionalFieldsSwitchTypePlaceholderSelect')}" required>
                        <option value=""></option>
                        ${generateSelectOptions(dictionaryList)}
                    </select>
                </div>`;
        default:
            return `<div class="d-none"></div>`;
    }
}

function generateSelectOptions(dictionaryList) {
    let options = '';
    for (let key in dictionaryList) {
        options += `<option value="${key}">${translateDictionariesOption(dictionaryList[key])}</option>`;
    }
    return options;
}

// --------- parameter
function initParamBD(parameterListItemsCustom, id = null) {
    // Initialize the parameter list
    let parameterListHTML = '';

    for (const parameterGroup of parameterListItemsCustom) {
        for (const parameter of parameterGroup) {
            parameterListHTML += createParameterItem(parameter.name, parameter.is_checked, id);
        }
    }

    return parameterListHTML;
}

export function initParam(parameterListItemsCustom, id = null) {
    // Initialize the parameter list
    for (const parameterGroup of parameterListItemsCustom) {
        for (const parameter of parameterGroup) {
            addParameterToList(parameter.name, parameter.is_checked, id);
        }
    }
}

// Function to add a new parameter to the list
export function addParameterToList(value, checked, id = null) {
    let parameterList;
    if (id === null) {
        parameterList = $('.parameter-list');
    } else {
        parameterList = $(`#parameter-list_${id}`);
    }
    const newParameter = createParameterItem(value, checked, id);
    parameterList.append(newParameter);
}

export function createParameterItem(value, checked, id = null) {
    const uniqueId = Math.random().toString(36).substring(2, 15);
    let checkboxId;
    let buttonRemoveId;

    if (id === null) {
        checkboxId = `requiredCheck_${uniqueId}`;
        buttonRemoveId = 'removeButtonParemeters';
    } else {
        checkboxId = `requiredCheck_${id}_${uniqueId}`;
        buttonRemoveId = `removeButtonParemeters_${id}`;
    }

    let newParameterItem = `
    <li class="parameter-item" data-value="${value}" data-checked="${checked}">
    <div class="parameter-item-title">
        <img class="" src="/assets/icons/entity/document-type/grip-vertical.svg" alt="grip-vertical"> ${value}
    </div>
    <div class='removeButtonBaseFieldParam align-items-center'>
        <div class="js-input-parameter ${checked ? 'checked-js-input-parameter' : ''}">
            <div class="form-check form-check-warning pe-1">
                <input type="radio" class="form-check-input" id="${checkboxId}" ${checked ? 'checked' : ''}>
                <label class="form-check-label" for="${checkboxId}">${getLocalizedText('configuratorCreateParameterItemLabelText')}</label>
            </div>
        </div>
        <div class="${buttonRemoveId}">
            <img src='/assets/icons/entity/document-type/close-field-base.svg' alt='close-field-base'>
        </div>
    </div>
   </li>
    `;
    return newParameterItem;
}
