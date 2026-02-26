import { getLocalizedText } from '../../../localization/_unused/services/getLocalizedText.js';

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
        }).then(async (response) => {
            if ([200, 201].includes(response.status)) {
                document.location.href = url + `/services`;
            } else if ([500].includes(response.status)) {
                addError($('#alert-error'), $('.card'), getLocalizedText('responseText500'));
            } else {
                let text = await response.text();
                addError($('#alert-error'), $('.card'), text);
            }
        });
    });

    $('#create-save-as-draft-button').click(async function (e) {
        e.preventDefault();
        let formData = getData();
        formData.append('is_draft', 1);

        await fetch(url + `/services`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-Token': csrf,
            },
        }).then(async (response) => {
            if ([200, 201].includes(response.status)) {
                document.location.href = url + `/services`;
            } else if ([500].includes(response.status)) {
                addError($('#alert-error'), $('.card'), getLocalizedText('responseText500'));
            } else {
                let text = await response.text();
                addError($('#alert-error'), $('.card'), text);
            }
        });
    });

    $('#edit-save-button').click(async function (e) {
        e.preventDefault();
        let formData = getData();
        formData.append('_method', 'PUT');
        formData.append('is_draft', 0);

        await fetch(url + `/services/${service.id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-Token': csrf,
            },
        }).then(async (response) => {
            if ([200, 201].includes(response.status)) {
                document.location.href = url + `/services`;
            } else if ([500].includes(response.status)) {
                addError($('#alert-error'), $('.card'), getLocalizedText('responseText500'));
            } else {
                let text = await response.text();
                addError($('#alert-error'), $('.card'), text);
            }
        });
    });

    $('#edit-save-as-draft-button').click(async function (e) {
        e.preventDefault();
        let formData = getData();
        formData.append('_method', 'PUT');
        formData.append('is_draft', 1);

        await fetch(url + `/services/${service.id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-Token': csrf,
            },
        }).then(async (response) => {
            if ([200, 201].includes(response.status)) {
                document.location.href = url + `/services`;
            } else if ([500].includes(response.status)) {
                addError($('#alert-error'), $('.card'), getLocalizedText('responseText500'));
            } else {
                let text = await response.text();
                addError($('#alert-error'), $('.card'), text);
            }
        });
    });
});

function getData() {
    let formData = new FormData();
    formData.append('name', $('#basicInput').val());
    formData.append('comment', $('#exampleFormControlTextarea1').val());
    formData.append('category_id', $('#select-service-category').val());
    formData.append('is_draft', 0);

    return formData;
}

function addError(el, formEl, err) {
    el.text(err);
    el.removeClass('d-none');
    formEl.on('input ', function () {
        if (!el.hasClass('d-none')) {
            el.addClass('d-none');
        }
    });
}
