import { redirectWithLocale } from '../../utils/redirectWithLocale.js';
import { sendRequestBase } from '../../utils/sendRequestBase.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';
import { validateGeneric } from '../../utils/request/validate.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();

    const url =
        locale === 'en'
            ? `` // без префіксу
            : `${locale}/`;

    $('#create').on('click', async function () {
        let formData = getData();

        await sendRequestBase(`${url}locations`, formData, validate, function () {
            redirectWithLocale('/locations');
        });
    });

    $('#update').on('click', async function () {
        let formData = getData();
        formData.append('_method', 'PUT');

        await sendRequestBase(`${url}locations/${locations.id}`, formData, validate, function () {
            redirectWithLocale('/locations');
        });
    });

    $('#delete-modal-btn').on('click', async function () {
        let formData = getDataDelete();
        await sendRequestBase(`${url}locations/${locations.id}`, formData, null, function () {
            redirectWithLocale('/locations');
        });
    });
});

function getData() {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    let formData = new FormData();

    const getVal = (id) => $(`#${id}`).val();

    let name = getVal('name'),
        company = getVal('company_id'),
        country = getVal('country_id'),
        settlement = getVal('settlement_id'),
        building_number = getVal('building_number'),
        street = getVal('street_id'),
        url = getVal('map-input');

    formData.append('_token', csrf);
    formData.append('name', name);
    formData.append('company_id', company);
    formData.append('country_id', country);
    formData.append('settlement_id', settlement);
    formData.append('street_info[title]', street);
    formData.append('street_info[building]', building_number);

    formData.append('url', url);
    return formData;
}

function getDataDelete() {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    let formData = new FormData();

    formData.append('_token', csrf);
    formData.append('_method', 'DELETE');

    return formData;
}

async function validate(response) {
    const fieldGroups = [
        { fields: ['name', 'company_id'], container: '#main-data-message' },
        {
            fields: ['country_id', 'settlement_id', 'street_id', 'building_number', 'url'],
            container: '#address-message',
        },
    ];

    await validateGeneric(response, fieldGroups, '#main-data-message');
}
