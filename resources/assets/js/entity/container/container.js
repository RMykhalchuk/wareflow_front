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

    $('#create-container').click(async function () {
        let formData = getData();

        await sendRequestBase(`${url}containers`, formData, validateContainers, function () {
            redirectWithLocale('/containers');
        });
    });

    $('#edit-container').click(async function () {
        let formData = getData();
        formData.append('_method', 'PUT');

        await sendRequestBase(
            `${url}containers/${container.id}`,
            formData,
            validateContainers,
            function () {
                redirectWithLocale('/containers');
            }
        );
    });
});

function getData() {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    let formData = new FormData();
    formData.append('_token', csrf);
    formData.append('name', $('#name').val());
    formData.append('type_id', $('#type_id').val());
    formData.append('code_format', $('#code_format').val());
    formData.append('reversible', +$('#reversible')[0].checked);

    formData.append('weight', $('#weight').val());
    formData.append('max_weight', $('#max_weight').val());
    formData.append('height', $('#height').val());
    formData.append('width', $('#width').val());
    formData.append('length', $('#length').val());

    return formData;
}

async function validateContainers(response) {
    const fieldGroups = [
        { fields: ['name', 'type_id', 'code_format', 'reversible'], container: '#base-error' },
        {
            fields: ['weight', 'max_weight', 'height', 'width', 'length'],
            container: '#parameters-error',
        },
    ];

    await validateGeneric(response, fieldGroups, '#base-error');
}
