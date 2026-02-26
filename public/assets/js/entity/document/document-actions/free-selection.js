import { getCurrentLocaleFromUrl } from '../../../utils/getCurrentLocaleFromUrl.js';
import { sendRequestBase } from '../../../utils/sendRequestBase.js';

$(document).on('change', '#free_selection', async function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const document_id = $('#document-container').data('id');

    const locale = getCurrentLocaleFromUrl();
    const url =
        locale === 'en'
            ? `` // без префіксу
            : `${locale}/`;

    const isChecked = $(this).prop('checked') ? 1 : 0;
    const formData = new FormData();
    formData.append('_token', csrf);
    formData.append('free_selection', isChecked);

    const endpoint = `${url}document/outcome/${document_id}/free-selection`;
    console.log('Sending request to:', endpoint, 'value:', isChecked);

    try {
        await sendRequestBase(endpoint, formData, null, () => {
            console.log('Free selection status updated successfully!');
        });
    } catch (error) {
        console.error('Помилка при оновленні free selection', error);
    }
});
