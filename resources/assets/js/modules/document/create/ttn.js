import { selectDictionaries } from '../../../utils/selectDictionaries';

$('#2label_field_2').prop('disabled', true);

$(document).ready(function () {
    $('#1select_field_1').on('select2:select', function (e) {
        // Get the selected value
        var selectedValue = $(this).val();

        if (selectedValue == '1') {
            $('#2label_field_2').prop('disabled', false); // Enable the element
            $('#2label_field_2').attr('data-dictionary', 'document_tn'); // Update the HTML attribute
        } else {
            $('#2label_field_2').prop('disabled', false); // Enable the element
            $('#2label_field_2').attr('data-dictionary', 'document_transport_request'); // Update the HTML attribute
        }

        selectDictionaries('2label_field_2');
    });
});
