import { redirectWithLocale } from '../../utils/redirectWithLocale.js';
import { sendRequestBase } from '../../utils/sendRequestBase.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();

    const url =
        locale === 'en'
            ? `` // без префіксу
            : `${locale}/`;

    const warehouseId = $('#warehouse-container').data('id');

    $('#delete-modal-btn').on('click', async function () {
        let formData = getDataDelete();
        await sendRequestBase(`${url}warehouses/${warehouseId}`, formData, null, function () {
            redirectWithLocale('/warehouses');
        });
    });
});

function getDataDelete() {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    let formData = new FormData();

    formData.append('_token', csrf);
    formData.append('_method', 'DELETE');

    return formData;
}
