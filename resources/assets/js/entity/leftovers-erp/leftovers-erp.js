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

        await sendRequestBase(`${url}leftovers-erp`, formData, validateLeftovers, function () {
            redirectWithLocale('/leftovers-erp');
        });
    });
});

function getData() {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    let formData = new FormData();
    formData.append('_token', csrf);

    formData.append('warehouse_erp_id', $('#warehouse_erp_id').val());
    formData.append('goods_erp_id', $('#goods_erp_id').val());
    formData.append('batch', $('#batch').val());
    formData.append('quantity', $('#quantity').val());

    return formData;
}

async function validateLeftovers(response) {
    const fieldGroups = [
        {
            fields: ['warehouse_erp_id', 'goods_erp_id', 'batch', 'quantity'],
            container: '#message',
        },
    ];

    await validateGeneric(response, fieldGroups, '#message');
}
