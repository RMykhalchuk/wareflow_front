export function handleMatchingAddressChange() {
    if ($('#matchingAddress').is(':checked')) {
        $('#u_country').html(
            `<option value="${$('#country_2').val()}" selected>${$('#country_2 option:selected').text()}</option>`
        );
        $('#u_city').html(
            `<option value="${$('#city_2').val()}" selected>${$('#city_2 option:selected').text()}</option>`
        );
        $('#u_street').html(
            `<option value="${$('#street_2').val()}" selected>${$('#street_2 option:selected').text()}</option>`
        );

        $('#u_building_number').val($('#building_number_2').val());
        $('#u_flat').val($('#flat_2').val());
        $('#u_gln').val($('#gln_2').val());
    }
}
