import { getLocalizedText } from '../../../localization/_unused/invoice/getLocalizedText.js';

const blocks = ['#block-start-cost', '#block-add-correction-cost', '#block-selected-correction'];

function hideAllVarElements() {
    const defaultHiddenEls = [...blocks];

    $.each(defaultHiddenEls, function (i, el) {
        if (!$(el).hasClass('d-none')) {
            $(el).addClass('d-none');
        }
    });
}

function firstRender() {
    $('#block-start-cost').removeClass('d-none');
}

firstRender();

// додати нове коригування
$('#btn-add-correction, #link-to-add-correction').click(function () {
    hideAllVarElements();
    $('#block-add-correction-cost').removeClass('d-none');
});

//
$('#link-to-back-start-cost').click(function () {
    hideAllVarElements();
    firstRender();
});

// вибираємо сторону  більше чи меншу
$(document).ready(function () {
    $('input[name="checkedWay"]').change(function () {
        var selectedRadio = $('input[name="checkedWay"]:checked').attr('id');

        if (selectedRadio === 'increaseCost') {
            $('#block-reason, #block-amountOfAdditionalCost').removeClass('d-none');
        } else if (selectedRadio === 'reduceCost') {
            $('#block-type-of-problem, #block-amountPenalty').removeClass('d-none');
        }
        if (
            !$('#block-reason, #block-amountOfAdditionalCost').hasClass('d-none') &&
            selectedRadio !== 'increaseCost'
        ) {
            $('#block-reason, #block-amountOfAdditionalCost').addClass('d-none');
        }
        if (
            !$('#block-type-of-problem, #block-amountPenalty').hasClass('d-none') &&
            selectedRadio !== 'reduceCost'
        ) {
            $('#block-type-of-problem, #block-amountPenalty').addClass('d-none');
        }
    });
});

// ховер і з‘являються видалення та редагування
$(document).ready(function () {
    $(document).on('mouseenter', '.item-selected-correction-hover', function () {
        var $itemSelectedActions = $(this).find('.item-selected-actions');
        $itemSelectedActions.removeClass('visually-hidden');
    });

    $(document).on('mouseleave', '.item-selected-correction-hover', function () {
        var $itemSelectedActions = $(this).find('.item-selected-actions');
        $itemSelectedActions.addClass('visually-hidden');
    });
});

$(document).ready(function () {
    $('#add-to-correction-list').click(function () {
        var selectedRadio = $('input[name="checkedWay"]:checked').attr('id');
        var status = $('#status').val();
        var location = $('#location').val();
        var comment = $('#comment').val();
        if (selectedRadio === 'increaseCost') {
            var amount = $('#amountOfAdditionalCost').val();
            if (amount === '') {
                return;
            } else {
                var typeCorrection = 'Додано';
                var reason = $('#reason').val();
                nextPage(typeCorrection, reason, amount, status, location, comment);
            }
        } else if (selectedRadio === 'reduceCost') {
            var amount = $('#amountPenalty').val();
            if (amount === '') {
                return;
            } else {
                var typeCorrection = 'Віднято';
                var reason = $('#type-of-problem').val();
                nextPage(typeCorrection, reason, amount, status, location, comment);
            }
        }
    });

    function nextPage(typeCorrection, reason, amount, status, location, comment) {
        var html = markupItemSelectCorrection(
            typeCorrection,
            reason,
            amount,
            status,
            location,
            comment
        );
        $('#list-selected-correction').append(html);
        $('#btn-save-PO').prop('disabled', false);
        hideAllVarElements();
        $('#block-selected-correction').removeClass('d-none');
    }
});

function markupItemSelectCorrection(typeCorrection, reason, amount, status, location, comment) {
    return `  <div class="item-selected-correction mb-2">
    <p class="fw-medium-c fs-5 mt-0">${typeCorrection} ${getLocalizedText('markupItemSelectCorrectionRender2TypeCorrectionBy')}</p>
    <div class="border rounded p-1 item-selected-correction-hover">

        <div class="d-flex justify-content-between align-items-center" style="margin-bottom:5px">
            <p class="fw-semibold fs-6 m-0">1. ${reason}
            </p>
            <div class="d-flex align-items-center gap-1">
                <p class="fw-semibold fs-6 m-0">${amount} ${getLocalizedText('markupItemSelectCorrectionRender2AmountUnit')}</p>
                <div class="d-flex gap-1 item-selected-actions visually-hidden"> <a href="#"
                        class="text-secondary"><i data-feather='edit'></i></a> <a href="#"
                        class="text-secondary remove-item-selected-correction"><i data-feather='trash-2'></i></a></div>
            </div>
        </div>
        <p class="mb-0" style="color:#A5A3AE;margin-bottom:5px">${getCurrentDate()} ${getLocalizedText('markupItemSelectCorrectionRender2TimeAt')} ${getCurrentTime()}</p>
        <p class="mb-1" style="color:#A5A3AE;">${status} /${location} </p>
        <p style="margin-bottom:5px" class="fw-medium-c m-0">${getLocalizedText('markupItemSelectCorrectionRender2Comment')}</p>
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

$(document).ready(function () {
    $(document).on('click', 'a[data-object]', function () {
        hideAllVarElements();
        $('#block-start-cost').removeClass('d-none');
        var dataObject = $(this).attr('data-object');
        var jsonData = JSON.parse(dataObject);

        $('#title-number-PO').text(jsonData.obligations);
        $('#number-info-PO').text(jsonData.obligations);
        $('#recipient-name-info').text(jsonData.recipient);
        $('#performer-name-info').text(jsonData.performer);
        $('#date-invoice-info').text(jsonData.date);
        $('#cost-invoice-info').text(jsonData.costWithoutPDV);

        $('#modalPaymentObligation').modal('show');
    });
});

$(document).on('click', '.remove-item-selected-correction', function () {
    $(this).closest('.item-selected-correction').remove();
});
