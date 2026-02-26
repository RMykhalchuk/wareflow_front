import { redirectWithLocale } from '../../utils/redirectWithLocale.js';
import { sendRequestBase } from '../../utils/sendRequestBase.js';
import { validateGeneric } from '../../utils/request/validate.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();

    const url =
        locale === 'en'
            ? `` // без префіксу
            : `${locale}/`;

    $('#submit').click(async function () {
        let formData = getData();

        await sendRequestBase(`${url}warehouse-erp`, formData, validate, function () {
            redirectWithLocale('/warehouse-erp');
        });
    });
});

function getData() {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    let formData = new FormData();
    formData.append('_token', csrf);

    formData.append('name', $('#name').val());
    formData.append('id_erp', $('#erp-id').val());

    return formData;
}

async function validate(response) {
    const fieldGroups = [
        {
            fields: ['name', 'id_erp'],
            container: '#message',
        },
    ];

    await validateGeneric(response, fieldGroups, '#message');
}
