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

        await sendRequestBase(`${url}leftovers`, formData, validateLeftovers, function () {
            redirectWithLocale('/leftovers');
        });
    });
});

function getData() {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    let formData = new FormData();
    formData.append('_token', csrf);

    formData.append('goods_id', $('#goods_id').val());
    formData.append('package_id', $('#packages_id').val());
    formData.append('batch', $('#batch').val());
    formData.append('manufacture_date', $('#manufacture_date').val());
    formData.append('expiration_term', $('#expiration_term').val());
    formData.append('bb_date', $('#bb_date').val());
    formData.append('quantity', $('#quantity').val());
    formData.append('container_id', $('#container_registers_id').val());
    formData.append('has_condition', $('#has_condition').is(':checked') ? 1 : 0);
    formData.append('warehouse_id', $('#warehouses_id').val());
    formData.append('cell_id', $('#cell_id').val());

    return formData;
}

async function validateLeftovers(response) {
    const fieldGroups = [
        {
            fields: [
                'goods_id',
                'package_id',
                'batch',
                'manufacture_date',
                'expiration_term',
                'bb_date',
                'quantity',
                'container_id',
                'has_condition',
                'warehouse_id',
                'cell_id',
            ],
            container: '#message',
        },
    ];

    await validateGeneric(response, fieldGroups, '#message');
}
