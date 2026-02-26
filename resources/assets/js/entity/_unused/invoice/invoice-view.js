import { getLocalizedText } from '../../../localization/_unused/invoice/getLocalizedText.js';

const statuses = [
    '#status-invoice-create',
    '#status-invoice-sent-for-payment',
    '#status-invoice-paid-by-contractor',
    '#status-invoice-paid-by-you',
    '#status-invoice-waiting-for-your-payment',
    '#status-invoice-rejected-by-you',
    '#status-invoice-rejected-by-contractor',
];

const btnsActionsDocuments = [
    '#btn-send-to-payment',
    '#btn-payment',
    '#btn-return-from-payment',
    '#btn-reject',
];

const $blockContentView = '#block-content-view';
const $blockActionsDocuments = '#block-actionsDocuments';
const $btnPaid = '#btn-paid';
const $btnActionsEdit = '#btn-actions-edit';

function hideAllVarElements() {
    const defaultHiddenEls = [...statuses, ...btnsActionsDocuments];

    $.each(defaultHiddenEls, function (i, el) {
        if (!$(el).hasClass('d-none')) {
            $(el).addClass('d-none');
        }
    });
}

// перше завантаження при виборі перегляду
function firstDownloadPage() {
    $('#status-invoice-waiting-for-your-payment, #btn-reject, #btn-payment').removeClass('d-none');
}

firstDownloadPage();

// завантажили квитанцію оплачено
$('#btn-paid').click(function (e) {
    e.preventDefault();
    const inputValue = $('#fileInput-receiptForPayment').val();
    if (!inputValue) return;

    hideAllVarElements();
    $($blockContentView).removeClass('col-xl-9');
    $($btnActionsEdit).addClass('d-none');
    $($blockActionsDocuments).addClass('d-none');
    $('#status-invoice-paid-by-you').removeClass('d-none');
    $('#receiptForPayment').modal('hide');

    var fileName = $('#fileInput-receiptForPayment').val().split('\\').pop();
    var fileInput = $('#fileInput-receiptForPayment').get(0);
    var file = fileInput.files[0];
    var fileUrl = URL.createObjectURL(file);
    var fileLink = $('#link-receiptForPayment')
        .attr('href', fileUrl)
        .attr('download', fileName)
        .text(fileName)
        .addClass('text-decoration-underline');
});

// робота з модалкою в перегляді

const blocks = ['#block-start-cost', '#block-add-correction-cost', '#block-selected-correction'];

function hideAllVarElementsInCorrectionsModal() {
    const defaultHiddenEls = [...blocks];

    $.each(defaultHiddenEls, function (i, el) {
        if (!$(el).hasClass('d-none')) {
            $(el).addClass('d-none');
        }
    });
}

$(document).ready(function () {
    $(document).on('click', 'a[data-object]', function () {
        hideAllVarElementsInCorrectionsModal();
        $('#block-selected-correction').removeClass('d-none');
        var dataObject = $(this).attr('data-object');
        var jsonData = JSON.parse(dataObject);

        $('#title-number-PO').text(jsonData.obligations);
        $('#number-info-PO').text(jsonData.obligations);
        $('#recipient-name-info').text(jsonData.recipient);
        $('#performer-name-info').text(jsonData.performer);
        $('#date-invoice-info').text(jsonData.date);
        $('#cost-invoice-info').text(jsonData.costWithoutPDV);

        var html = markupItemSelectCorrection(
            getLocalizedText('markupItemSelectCorrectionTypeCorrection'),
            getLocalizedText('markupItemSelectCorrectionReason'),
            getLocalizedText('markupItemSelectCorrectionAmount'),
            getLocalizedText('markupItemSelectCorrectionStatus'),
            getLocalizedText('markupItemSelectCorrectionLocation'),
            getLocalizedText('markupItemSelectCorrectionComment')
        );

        $('#list-selected-correction').empty().append(html);

        $('#link-to-add-correction').addClass('d-none');
        $('#btns-action-modal').empty();
        $('#btns-action-modal').append(`<button
                                class="btn btn-primary" data-bs-dismiss="modal"
                                aria-label="Close">
                                ${getLocalizedText('btnsActionModalText')}
                                </button>`);
        $('#modalPaymentObligation').modal('show');
    });
});

function markupItemSelectCorrection(typeCorrection, reason, amount, status, location, comment) {
    return `  <div class="item-selected-correction mb-2">
    <p class="fw-medium-c fs-5 mt-0">${typeCorrection} ${getLocalizedText('markupItemSelectCorrectionRenderTypeCorrectionBy')}</p>
    <div class="border rounded p-1 item-selected-correction-hover">

        <div class="d-flex justify-content-between align-items-center" style="margin-bottom:5px">
            <p class="fw-semibold fs-6 m-0">1. ${reason}
            </p>
            <div class="d-flex align-items-center gap-1">
                <p class="fw-semibold fs-6 m-0">${amount} ${getLocalizedText('markupItemSelectCorrectionRenderAmountUnit')}</p>
                <div class="d-flex gap-1 item-selected-actions visually-hidden"> <a href="#"
                        class="text-secondary"><i data-feather='edit'></i></a> <a href="#"
                        class="text-secondary"><i data-feather='trash-2'></i></a></div>
            </div>
        </div>
        <p class="mb-0" style="color:#A5A3AE;margin-bottom:5px">${getCurrentDate()} ${getLocalizedText('markupItemSelectCorrectionRenderTimeAt')} ${getCurrentTime()}</p>
        <p class="mb-1" style="color:#A5A3AE;">${status} /${location} </p>
        <p style="margin-bottom:5px" class="fw-medium-c m-0">${getLocalizedText('markupItemSelectCorrectionRenderComment')}</p>
        <p class="m-0" style="color:#A5A3AE;">${comment}</p>
    </div>
</div>`;
}

function getCurrentDate() {
    var currentDate = new Date();
    var day = currentDate.getDate();
    var month = currentDate.getMonth() + 1;
    var year = currentDate.getFullYear().toString().slice(-2);
    return padNumber(day) + '.' + padNumber(month) + '.' + year;
}

function getCurrentTime() {
    var currentTime = new Date();
    var hours = currentTime.getHours();
    var minutes = currentTime.getMinutes();
    return padNumber(hours) + ':' + padNumber(minutes);
}

function padNumber(num) {
    return num < 10 ? '0' + num : num;
}
