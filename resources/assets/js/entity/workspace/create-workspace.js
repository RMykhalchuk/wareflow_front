import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';
import { sendRequestBase } from '../../utils/sendRequestBase.js';
import { redirectWithLocale } from '../../utils/redirectWithLocale.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();

    const url =
        locale === 'en'
            ? `` // без префіксу
            : `${locale}/`;

    //Save data from create workspace form start
    $(function () {
        $('#workspace-form-next-btn').on('click', async function () {
            const link = window.location.href;
            const segments = link.split('/');
            const company_id = segments[segments.length - 1];

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            const workspaceName = $('#workspace-username');
            formData.append('name', workspaceName.val());

            await sendRequestBase(`${url}workspaces/` + company_id, formData, null, function () {
                redirectWithLocale('/');
            });
        });
    });
    //Save data from create workspace form end

    // Workspace settings show-hide tab content start
    $(function () {
        $('.workspace-details').hide();
        $('#workspace-details-wrapper').click(function () {
            $('.workspace-tab').hide();
            $('.workspace-details').show();
        });
        $('#back-to-workspace-settings').click(function () {
            $('.workspace-details').hide();
            $('.workspace-tab').show();
        });
    });

    $('#condition_submit').on('click', function () {
        $('#create-workspace').addClass('d-none');
        $('#workspace').removeClass('d-none');
        $('#animation').modal('hide');
    });
});
