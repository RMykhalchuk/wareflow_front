import { hideAmendedChangesModal, regulationItem } from './utils/regulation-render.js';

import { toggleChildrenRegulations } from './utils/open-child-regulation.js';
import {
    checkContractSigned,
    hideAllVarElements,
    updateDateForContract,
} from './utils/updateDateForContract.js';
import { searchRegulation } from './utils/searchRegulation.js';
import { createRegulationEvent, updateRegulationEvent } from './utils/regulationAction.js';
import {
    setSelectedRegulation,
    getSelectedRegulation,
    checkTrack,
} from './utils/regulationState.js';

const csrf = document.querySelector('meta[name="csrf-token"]').content;
const url = window.location.origin;

let regulationsArray;

//true when initial values already exists
let track_changes = false;

let typeBlockNameUpdate = $('#typeContract').val();
let typeServiceSideUpdate = $('#side').val();

const contentBlocks = [
    '#initialConditions',
    '#missingRegulations',
    '#retail-list-regulations',
    '#one-retail-regulation',
];

const btnChangeRegulation = ['#btn-cancel-changes'];

const defaultHiddenEls = [...contentBlocks, ...btnChangeRegulation];

function updateTrackChanges(value) {
    track_changes = value;
}

// перше завантаження
function firstDownloadPage(selector) {
    selector.removeClass('d-none');
    checkContractSigned();
}

// Встановлення події зміни стану переключателя
$('#contractSigned').change(function () {
    checkContractSigned();
});

// Bind the change event to both selectors
$('#typeContract, #side').change(function () {
    typeBlockNameUpdate = $('#typeContract').val();
    typeServiceSideUpdate = $('#side').val();
    checkSelectValues();
    updateDateForContract($(this));
    searchRegulation(
        url,
        typeBlockNameUpdate,
        typeServiceSideUpdate,
        regulationsArray,
        checkSelectValues
    );
    createRegulationEvent(
        typeBlockNameUpdate,
        typeServiceSideUpdate,
        checkSelectValues,
        csrf,
        url,
        updateTrackChanges
    );
    updateRegulationEvent(
        typeBlockNameUpdate,
        typeServiceSideUpdate,
        checkSelectValues,
        csrf,
        url,
        updateTrackChanges,
        getSelectedRegulation
    );
});

// Функція для перевірки значень селекторів та показати регламенти відповідні
async function checkSelectValues(regulationsArraySearch) {
    var typeContractValue = $('#typeContract').val();
    var sideValue = $('#side').val();

    if (typeContractValue !== '' && sideValue !== '') {
        hideAllVarElements(defaultHiddenEls);
        $('#retail-list-regulations').removeClass('d-none');

        var $retailListRegulations = $('.container-for-market-list');
        $retailListRegulations.empty();

        if (!regulationsArraySearch) {
            fetch(
                window.location.origin +
                    `/regulations/search?type=${typeContractValue}&service_side=${sideValue}&without_trashed=1&without_draft=1`,
                {
                    method: 'GET',
                    headers: {
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
                    },
                }
            )
                .then((response) => response.json())
                .then((data) => {
                    regulationsArray = data.regulations;
                    processRegulations(regulationsArray, false);
                    // Зімітувати клік по елементу з відповідним data-id
                    $(
                        ".cont-for-market-item[data-id='" +
                            contractRegulation +
                            "'], .child-regulations-item[data-id='" +
                            contractRegulation +
                            "']"
                    )
                        .first()
                        .click();
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        } else {
            regulationsArray = regulationsArraySearch.regulations;
            processRegulations(regulationsArray, true);
        }
    } else {
        firstDownloadPage($('#retail-list-regulations'));
    }
}

function processRegulations(regulationsArray, isSearchResult) {
    var $retailListRegulations = $('.container-for-market-list');

    if (regulationsArray.length === 0 && !isSearchResult) {
        hideAllVarElements(defaultHiddenEls);
        $('#missingRegulations').removeClass('d-none');
    } else {
        regulationsArray.forEach(function (child) {
            var liElement = regulationItem(
                child.name,
                child.id,
                child.contracts_count,
                child.children
            );
            $retailListRegulations.append(liElement);
        });

        // відкрити дочірні елементи регламенту та зарендерити елемент
        toggleChildrenRegulations(regulationsArray);
    }
}

// Універсальна функція для відкриття регламенту
async function openRegulation(idEl, parentId, isChildRegulation) {
    //console.log(idEl)
    $('#one-retail-regulation')
        .removeClass('cont-for-market-item-view child-regulations-item-view')
        .addClass(isChildRegulation ? 'child-regulations-item-view' : 'cont-for-market-item-view')
        .attr('data-id', idEl)
        .attr('data-parent-id', parentId);

    var regulation;
    if (isChildRegulation) {
        var regulationObj = await regulationsArray.find(function (item) {
            return item.id === parentId;
        });
        regulation = regulationObj
            ? regulationObj.children.find(function (item) {
                  return item.id === idEl;
              })
            : null;
    } else {
        regulation = regulationsArray.find(function (item) {
            return item.id === idEl;
        });
    }

    if (regulation) {
        let settings = JSON.parse(regulation.settings);

        $('#one-regulation-name').text(regulation.name);

        $('#nameRetail').val(regulation.name);
        $('#parentRegulation').val(regulation.parent_id).trigger('change');

        $('#typePallet').val('evropaleta_120h80sm').trigger('change');
        $('#heightPallet').val(settings.heightPalet);
        $('#remainingTerm').val(settings.overheadTerm);

        $('#palletLatter').prop('checked', settings.palletSheet);
        $('#allowPrefabricatedPallets').prop('checked', settings.allowPrefabPallets);
        $('#allowSendwichPallet').prop('checked', settings.allowSandwichPallet);
        $('#labeling').prop('checked', settings.stickering);
        $('#allowCondacting').prop('checked', settings.allowHolding);
        track_changes = true;
        setSelectedRegulation(regulation.id);
    }
}

// Обробник подій для кліку по регламенту
$(document).on('click', '.cont-for-market-item, .child-regulations-item', async function (e) {
    var $target = $(e.target);
    if (
        !$target.hasClass('link-open-children-regulations') &&
        !$target.hasClass('link-open-children-regulations-img') &&
        !$target.closest('.dropdown-menu').length &&
        !$target.closest('.dropdown-menu-group').length
    ) {
        hideAllVarElements(defaultHiddenEls);

        $('#one-retail-regulation').removeClass('d-none');
        $('#btn-reject').prop('disabled', false);
        $('#btn-save').prop('disabled', false);
        // Видалити попередні класи, якщо вони були додані
        $('#one-retail-regulation').removeClass(
            'cont-for-market-item-view child-regulations-item-view'
        );
    }
    var idEl = $(this).data('id');
    var parentId = $(this).data('parentid');
    var isChildRegulation =
        $target.hasClass('child-regulations-item') ||
        $target.closest('li').hasClass('child-regulations-item');

    await openRegulation(idEl, parentId, isChildRegulation);
    $('#btn-cancel-changes').addClass('d-none');
});

// Обробник подій для #btn-cancel-changes
$(document).on('click', '#btn-cancel-changes', async function (e) {
    e.preventDefault();

    var idEl = $('#one-retail-regulation').data('id');
    var parentId = $('#one-retail-regulation').data('parent-id');
    var isChildRegulation = $('#one-retail-regulation').hasClass('child-regulations-item-view');

    await openRegulation(idEl, parentId, isChildRegulation);

    $(this).addClass('d-none');
});

// вертаємось до списку торгових регламентів
$('#link-to-back-retail-list').click(function () {
    hideAllVarElements(defaultHiddenEls);
    $('#retail-list-regulations').removeClass('d-none');
    $('#btn-reject').prop('disabled', true);
    $('#btn-save').prop('disabled', true);
    track_changes = false;

    setSelectedRegulation(null);

    $('#btn-save').removeAttr('data-bs-toggle');
    $('#btn-save').attr('data-bs-target');
});

function getContractUpdateData() {
    let formData = new FormData();

    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('_method', 'PUT');
    formData.append('type_id', $('#typeContract').val());
    formData.append('role', $('#side').val());
    formData.append('company_id', $('#yourCompany').val());
    formData.append('counterparty_id', $('#contractor').val());
    formData.append('expired_at', $('#validityPeriod').val());
    if ($('#dateSigningContract').val()) {
        formData.append('signed_at', $('#dateSigningContract').val());
    }
    let fileSelector = $('#fileInput')[0];
    if (fileSelector.files && fileSelector.files[0]) {
        formData.append('file', fileSelector.files[0]);
    }

    return formData;
}

$(document).on('click', '#btn-save', function (e) {
    e.preventDefault();

    // Перевірка, чи кнопка не має атрибуту 'data-bs-toggle'
    if (!$(this)[0].hasAttribute('data-bs-toggle')) {
        let formData = getContractUpdateData();
        formData.append('company_regulation_id', getSelectedRegulation());

        // Запит для оновлення контракту
        fetch(window.location.origin + '/contracts/' + contractId, {
            method: 'POST', // Змініть на PUT для оновлення
            body: formData,
        }).then(async function (res) {
            let response = await res.json();
            //console.log(response);
            window.location.href = window.location.origin + '/contracts/' + contractId;
        });
    }
});
checkTrack();

checkSelectValues(null, true);

hideAmendedChangesModal();

firstDownloadPage($('#retail-list-regulations'));

searchRegulation(
    url,
    typeBlockNameUpdate,
    typeServiceSideUpdate,
    regulationsArray,
    checkSelectValues
);

createRegulationEvent(
    typeBlockNameUpdate,
    typeServiceSideUpdate,
    checkSelectValues,
    csrf,
    url,
    updateTrackChanges
);

updateRegulationEvent(
    typeBlockNameUpdate,
    typeServiceSideUpdate,
    checkSelectValues,
    csrf,
    url,
    updateTrackChanges,
    getSelectedRegulation
);
