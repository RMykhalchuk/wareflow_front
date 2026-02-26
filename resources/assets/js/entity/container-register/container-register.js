import { redirectWithLocale } from '../../utils/redirectWithLocale.js';
import { sendRequestBase } from '../../utils/sendRequestBase.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';
import { validateGeneric } from '../../utils/request/validate.js';
import { pollLabelJob } from '../../utils/pollLabelJob.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();

    const url =
        locale === 'en'
            ? `` // без префіксу
            : `${locale}/`;

    const containerRegisterId = $('#container-register-container').data('id');

    $('#create').click(async function () {
        let formData = getData();

        await sendRequestBase(
            `${url}container-register`,
            formData,
            validateContainersRegister,
            function () {
                redirectWithLocale('/container-register');
            }
        );
    });

    // ====== PRINT ======
    $('#print').click(async function (e) {
        e.preventDefault();

        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        let formData = new FormData();
        formData.append('_token', csrf);
        formData.append('type', 'container');
        formData.append('items[]', containerRegisterId);

        const $loader = $('#print-loader');

        // Показуємо лоадер
        $loader.removeClass('d-none').addClass('d-flex');

        await sendRequestBase(`${url}stickers/print-labels`, formData, null, function (response) {
            if (response?.job_id) {
                pollLabelJob(url, response.job_id, $loader);
            } else {
                $loader.removeClass('d-flex').addClass('d-none');
                alert('Не вдалося сформувати файл для друку.');
            }
        });
    });

    // ====== PRINT ======
    $('#view-print').click(async function (e) {
        e.preventDefault();

        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        let formData = new FormData();
        formData.append('_token', csrf);
        formData.append('type', 'container');
        formData.append('items[]', containerRegisterId);

        const $loader = $('#print-loader');

        // Показуємо лоадер
        $loader.removeClass('d-none').addClass('d-flex');

        await sendRequestBase(`${url}stickers/print-labels`, formData, null, function (response) {
            if (response?.job_id) {
                pollLabelJob(url, response.job_id, $loader);
            } else {
                $loader.removeClass('d-flex').addClass('d-none');
                alert('Не вдалося сформувати файл для друку.');
            }
        });
    });

    $('#delete-modal-btn').on('click', async function () {
        let formData = getDataDelete();
        await sendRequestBase(
            `${url}container-register/${containerRegisterId}`,
            formData,
            null,
            function () {
                redirectWithLocale('/container-register');
            }
        );
    });
});

function getData() {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    let formData = new FormData();
    formData.append('_token', csrf);
    formData.append('container_id', $('#type_id').val());
    formData.append('count', $('#count_container').val());

    return formData;
}

// ====== PRINT FORM DATA ======
function getPrintData() {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    let formData = new FormData();

    formData.append('_token', csrf);
    formData.append('container_type_id', $('#type_id_print').val());
    formData.append('count', $('#count').val());

    return formData;
}

async function validateContainersRegister(response) {
    const fieldGroups = [{ fields: ['container_id', 'count'], container: '#base-error' }];

    await validateGeneric(response, fieldGroups, '#base-error');
}

async function validateContainersPrint(response) {
    const fieldGroups = [{ fields: ['container_type_id', 'count'], container: '#print-error' }];

    await validateGeneric(response, fieldGroups, '#print-error');
}

function getDataDelete() {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    let formData = new FormData();

    formData.append('_token', csrf);
    formData.append('_method', 'DELETE');

    return formData;
}
