// Choose avatar start
import { redirectWithLocale } from '../../utils/redirectWithLocale';

$(document).ready(function () {
    let urlBase = window.location.origin;
    let csrf = document.querySelector('meta[name="csrf-token"]').content;

    $('#submit-workspace-detail').click(async function (e) {
        e.preventDefault();
        let formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('name', $('#workspace-username').val());

        await fetch(urlBase + `/workspaces/${workspace.id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-Token': csrf,
            },
        }).then(() => {
            location.reload();
        });
    });

    $('*[id*=workspace-change-link-]').on('click', async function (e) {
        e.preventDefault();

        let id = $(this).data('id');

        await fetch(urlBase + '/workspaces/change-selected-workspace', {
            method: 'POST',
            body: JSON.stringify({
                _token: csrf,
                workspace_id: id,
            }),
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
            },
        }).then(() => {
            redirectWithLocale('/');
        });
    });

    // Workspace settings show-hide tab content
    $(function showHideWorkspaceDetails() {
        $('.workspace-details').hide();
        $('#workspace-details-wrapper').click(function (e) {
            e.preventDefault();
            $('.workspace-tab').hide();
            $('.workspace-details').show();
        });
        $('.back-to-workspace-settings').click(function () {
            $('.workspace-details').hide();
            $('.workspace-tab').show();
        });
    });
});
