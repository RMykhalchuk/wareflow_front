import { hideAmendedChangesModal, regulationItem } from './utils/regulation-render.js';

import { toggleChildrenRegulations } from './utils/open-child-regulation.js';
import { hideAllVarElements } from './utils/updateDateForContract.js';
import { searchRegulation } from './utils/searchRegulation.js';
import { createRegulationEvent, updateRegulationEvent } from './utils/regulationAction.js';
import {
    setSelectedRegulation,
    getSelectedRegulation,
    checkTrack,
} from './utils/regulationState.js';

import { getLocalizedText } from '../../../localization/_unused/contract/getLocalizedText.js';

const csrf = document.querySelector('meta[name="csrf-token"]').content;
const url = window.location.origin;

let regulationsArray;

//true when initial values already exists
var track_changes = false;

let typeBlockNameUpdate = contractTypeReg.toString();
let typeServiceSideUpdate = contractRoleReg.toString();

// Блок розірвання треба доробити напевно
const blockInfo = ['#block-for-broken-info'];

const btnsAction = [
    '#btn-return-for-review',
    '#btn-reject',
    '#btn-reject-sign',
    '#btn-broke-contract',
    '#btn-send-to-sign',
    '#btn-return-from-sign',
];

const contentInTabs = [
    '#list-regulations-for-market',
    '#retail-list-regulations',
    '#one-retail-regulation',
    '#info-about-missing',
    '#one-retail-regulation-contractorTab',
    '#info-about-missing-contractorTab',
];

const defaultHiddenEls = [...blockInfo, ...btnsAction, ...contentInTabs];

function updateTrackChanges(value) {
    track_changes = value;
}

// створення нового договорору  по кліку зберегти на сторінці create
function createdNewContract() {
    $(
        '#status-contract-create, #bnt-action-edit, #bnt-action-delete, #btn-send-for-review, .list-regulations-for-market'
    ).removeClass('d-none');
}

createdNewContract();

// надіслати на розгляд
$('#btn-send-for-review').click(function (e) {
    hideAllVarElements(defaultHiddenEls);
    fetch(window.location.origin + '/contracts/change-status', {
        method: 'POST',
        body: JSON.stringify({
            status_id: 1,
            contract_id: contractId,
        }),
        headers: {
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
    }).then(function () {
        window.location.reload();
    });
    $(
        '#btn-return-for-review, #status-contract-submitted-for-review, .list-regulations-for-market'
    ).removeClass('d-none');
    $('#tabsTab1').attr('data-forclick', 'open-menu-contractor');
});

//повернути з розгляду
$('#btn-return-for-review').click(function (e) {
    hideAllVarElements(defaultHiddenEls);
    createdNewContract();
    fetch(window.location.origin + '/contracts/change-status', {
        method: 'POST',
        body: JSON.stringify({
            status_id: 0,
            contract_id: contractId,
        }),
        headers: {
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
    }).then(function () {
        window.location.reload();
    });
});

// відкриваємо меню зі сторони контрагента
$(document).on('click', '[data-forclick="open-menu-contractor"]', function (e) {
    hideAllVarElements();
    $(
        '#btn-reject, #btn-sign, #status-contract-waiting-for-your-sign, #bnt-action-edit, .list-regulations-for-market'
    ).removeClass('d-none');
});

//  підписати договір зі сторони контрагента
$('#btn-sign').click(function () {
    if (!$(this)[0].hasAttribute('data-bs-toggle')) {
        hideAllVarElements(defaultHiddenEls);
        fetch(window.location.origin + '/contracts/change-status', {
            method: 'POST',
            body: JSON.stringify({
                status_id: 2,
                contract_id: contractId,
                counterparty_regulation_id: getSelectedRegulation(),
            }),
            headers: {
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
                Accept: 'application/json',
                'Content-Type': 'application/json',
            },
        }).then(function () {
            window.location.reload();
        });
        $(
            ' #status-contract-waiting-for-contractor-sign, #btn-reject-sign, .list-regulations-for-market'
        ).removeClass('d-none');
    }
});

$('#btn-reject-sign').click(function () {
    hideAllVarElements(defaultHiddenEls);
    fetch(window.location.origin + '/contracts/change-status', {
        method: 'POST',
        body: JSON.stringify({
            status_id: 1,
            contract_id: contractId,
        }),
        headers: {
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
    }).then(function () {
        window.location.reload();
    });
    $(
        ' #status-contract-waiting-for-contractor-sign, #btn-reject-sign, .list-regulations-for-market'
    ).removeClass('d-none');
});

$('#btn-second-sign').click(function () {
    hideAllVarElements(defaultHiddenEls);
    fetch(window.location.origin + '/contracts/change-status', {
        method: 'POST',
        body: JSON.stringify({
            status_id: 3,
            contract_id: contractId,
        }),
        headers: {
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
    }).then(function () {
        window.location.reload();
    });
    $(
        ' #status-contract-waiting-for-contractor-sign, #btn-reject-sign, .list-regulations-for-market'
    ).removeClass('d-none');
});

// підтвердити причину відхилення
$('#btn-cancel-contract').click(function () {
    var commentText = $(`#contractCancelledModal textarea`).val();

    if (commentText.trim() === '') {
        return;
    }

    onSubmitModalForm('#contractCancelledModal');

    fetch(window.location.origin + '/contracts/change-status', {
        method: 'POST',
        body: JSON.stringify({
            status_id: 4,
            contract_id: contractId,
            termination_reasons: commentText,
        }),
        headers: {
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
    }).then(function () {
        window.location.reload();
    });
});

$('#click-causes-of-rejection').click(function (e) {
    hideAllVarElements(defaultHiddenEls);
    let status = isContractor ? 5 : 6;
    var commentText = $(`#causesRejectionModal textarea`).val();

    onSubmitModalForm('#causesRejectionModal');

    fetch(window.location.origin + '/contracts/change-status', {
        method: 'POST',
        body: JSON.stringify({
            status_id: status,
            contract_id: contractId,
            decline_reasons: commentText,
        }),
        headers: {
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
    }).then(function () {
        window.location.reload();
    });
    $(
        ' #status-contract-waiting-for-contractor-sign, #btn-reject-sign, .list-regulations-for-market'
    ).removeClass('d-none');
});

$('#delete-contract-btn').click(function (e) {
    e.preventDefault();

    fetch(window.location.origin + `/contracts/${contractId}`, {
        method: 'delete',
        headers: {
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
        },
    }).then(async function (res) {
        if (res.status === 200) {
            window.location.href = window.location.origin + '/contracts/';
        } else {
            console.log((await res.json()).message);
        }
    });
});

//  додати коментар в модалці додати коментар
$('#btnAddComment').click(function () {
    onSubmitModalForm('#addCommentModal');
});

function onSubmitModalForm(modalId) {
    var commentText = $(`${modalId} textarea`).val();
    if (commentText.trim() === '') {
        return;
    }
    sendComment(modalId);
    $(modalId).modal('hide');
}

function sendComment(modalId) {
    var commentText = $(`${modalId} textarea`).val();

    let formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('comment', commentText);
    formData.append('contract_id', contractId);
    formData.append('company_id', commentCompany.id);

    fetch(window.location.origin + '/contracts/comment', {
        method: 'POST',
        body: formData,
    }).then(async function (res) {
        let response = await res.json();
        console.log(response);
    });

    // Get the current comment count from the HTML element
    let currentCommentCount = parseInt(document.getElementById('comment-count').innerHTML);
    // Increment the count
    document.getElementById('comment-count').innerHTML = currentCommentCount + 1;

    var commentHTML = `
        <div>
            <p class="f-15 fw-5 row mx-0">
                 <span class="col-8 ps-0">
                    ${commentCompany.name}
                 </span>
                 <span class="f-15 col-4 text-secondary pe-0 text-end">
                    ${getCurrentDate()} ${getLocalizedText('contractViewSendCommentTextAt')} ${getCurrentTime()}
                 </span>
            </p>
            <p>${commentText}</p>
        </div> `;
    $('#comments-container').append(commentHTML);
    $(`${modalId} textarea`).val('');
}

function getCurrentDate() {
    var currentDate = new Date();
    var day = currentDate.getDate();
    var month = currentDate.getMonth() + 1;
    var year = currentDate.getFullYear().toString();
    return padNumber(day) + '.' + padNumber(month) + '.' + year;
}

function getCurrentTime() {
    var currentTime = new Date();
    var hours = currentTime.getHours();
    var minutes = currentTime.getMinutes();
    let seconds = currentTime.getSeconds();
    return padNumber(hours) + ':' + padNumber(minutes) + ':' + padNumber(seconds);
}

function padNumber(num) {
    return num < 10 ? '0' + num : num;
}

// Функція для перевірки значень селекторів та показати ТОргові регламенти
async function checkSelectValues(regulationsArraySearch) {
    var typeContractValue = contractTypeReg;
    var sideValue = contractRoleReg;

    if (typeContractValue !== '' && sideValue !== '') {
        hideAllVarElements(defaultHiddenEls);
        $('#retail-list-regulations, #btn-reject ').removeClass('d-none');

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
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        } else {
            regulationsArray = regulationsArraySearch.regulations;
            processRegulations(regulationsArray, true);
        }
    } else {
        firstDownloadPage();
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

/// Універсальна функція для відкриття регламенту
async function openRegulation(idEl, parentId, isChildRegulation) {
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

        $('#typePallet').val(settings.typePalet).trigger('change');
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
        $('#btn-sign').prop('disabled', false);
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
    track_changes = false;
    $('#btn-sign').removeAttr('data-bs-toggle');
    $('#btn-sign').attr('data-bs-target');
});

// вертаємось до списку торгових регламентів
$('#link-to-back-retail-list').click(function () {
    hideAllVarElements(defaultHiddenEls);
    $('#retail-list-regulations, #btn-reject, #btn-sign').removeClass('d-none');
    $('#btn-reject').prop('disabled', true);
    $('#btn-sign').prop('disabled', true);
    track_changes = false;

    setSelectedRegulation(null);

    $('#btn-sign').removeAttr('data-bs-toggle');
    $('#btn-sign').attr('data-bs-target');
});

checkTrack();

checkSelectValues();

hideAmendedChangesModal();

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
    getSelectedRegulation,
    true
);
