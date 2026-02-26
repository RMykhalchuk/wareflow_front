import { getLocalizedText } from '../../../../localization/_unused/contract/getLocalizedText.js';

// Function to update text based on selection
export function updateDateForContract($selector) {
    var selectedValue = $selector.val();
    var text = '';

    if ($selector.attr('id') === 'side') {
        text =
            selectedValue === '0'
                ? getLocalizedText('updateDateForContractTextSide0')
                : getLocalizedText('updateDateForContractTextSide1');
        $('#retail-list-regulations-side').text(text);
        $('#one-regulation-side-regulation').text(text);
    } else if ($selector.attr('id') === 'typeContract') {
        if (selectedValue === '0') {
            text = getLocalizedText('updateDateForContractTextTypeContract0');
        } else if (selectedValue === '1') {
            text = getLocalizedText('updateDateForContractTextTypeContract1');
        } else if (selectedValue === '2') {
            text = getLocalizedText('updateDateForContractTextTypeContract2');
        }
        $('#retail-list-regulations-type-text').text(text);
    }

    updateMissingRegulationsText();
}

function updateMissingRegulationsText() {
    var typeContractText = $('#retail-list-regulations-type-text').text().toLowerCase();
    var sideText = $('#retail-list-regulations-side').text();

    $('#missingRegulationsTitleType').text(typeContractText);
    $('#missingRegulationsTitleSide').text(sideText);
}

// disabled on fileinput, dateSigningContract
export function checkContractSigned() {
    if ($('#contractSigned').is(':checked')) {
        $('#dateSigningContract, #fileInput').prop('disabled', false);
    } else {
        $('#dateSigningContract, #fileInput').prop('disabled', true);
    }
}

export function hideAllVarElements(defaultHiddenElsArr) {
    $.each(defaultHiddenElsArr, function (i, el) {
        if (!$(el).hasClass('d-none')) {
            $(el).addClass('d-none');
        }
    });
}
