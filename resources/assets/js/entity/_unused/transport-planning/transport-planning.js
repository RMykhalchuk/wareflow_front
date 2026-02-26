$(document).ready(function () {
    let url = window.location.origin;
    let csrf = document.querySelector('meta[name="csrf-token"]').content;

    $('#create-save-button').click(async function (e) {
        e.preventDefault();

        await fetch(url + `/services`, {
            method: 'POST',
            body: getData(),
            headers: {
                'X-CSRF-Token': csrf,
            },
        }).then(() => {
            document.location.href = url + `/transport-planning`;
        });
    });

    $('#edit-save-button').click(async function (e) {
        e.preventDefault();
        let formData = getData();
        formData.append('_method', 'PUT');

        await fetch(url + `/services/${service.id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-Token': csrf,
            },
        }).then(() => {
            document.location.href = url + `/transport-planning`;
        });
    });
});

function getData() {
    let formData = new FormData();

    formData.append('company_provider_id', $('#select-company-provider').val());
    formData.append('company_carrier_id', $('#select-company-transporter').val());
    formData.append('transport_id', $('#select-transport').val());
    formData.append('additional_equipment_id', $('#select-equipment').val());
    formData.append('payer_id', $('#select-payer').val());
    formData.append('driver_id', $('#select-driver').val());
    formData.append('price', $('#price').val());
    formData.append('with_pdv', +$('#pdv')[0].checked);
    formData.append('comment', $('#comment').val());

    return formData;
}
