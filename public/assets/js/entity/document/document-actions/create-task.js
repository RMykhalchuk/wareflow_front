import { getUrlType } from '../utils/getUrlType.js';

$(document).ready(function () {
    let url = window.location.origin;
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    const documentId = $('#document-container').data('id');
    const urlType = getUrlType();

    $('#create_task').click(async function (e) {
        e.preventDefault();

        $.ajax({
            url: url + `/document/${urlType}/${documentId}/task`,
            type: 'POST',
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-Token': csrf,
            },
            success: function (data) {
                console.log(data);
                // todo перобити під оновлення таблиць
                location.reload();
            },
            error: function (error) {
                alert(error.responseJSON.error);
            },
        });
    });
});
