import { getLocalizedText } from '../../localization/user/getLocalizedText.js';

$(document).ready(function () {
    if (localStorage.hasOwnProperty('email') && localStorage.hasOwnProperty('password')) {
        const email = localStorage.email;
        const password = localStorage.password;

        $('.toast').css('display', 'block');
        $('#alert-body')[0].innerHTML = `
            <p>${getLocalizedText('send_password_to_user')} <b>${email}</b> ?</p>
            <p>${getLocalizedText('password')} <b>${password}</b></p>
        `;

        localStorage.removeItem('email');
        localStorage.removeItem('password');

        // копіювання і надсилання працюють лише після визначення email і password
        $('#copy').on('click', function () {
            const temp = document.getElementById('temp');
            temp.value = `Email - ${email}\n${getLocalizedText('password')} - ${password}`;
            temp.select();
            document.execCommand('copy');
        });

        $('#send_email').on('click', function () {
            $.ajax({
                url: '/users/send-password',
                type: 'POST',
                data: {
                    _token: document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),
                    email: email,
                    password: password,
                },
            });
        });
    }

    $('.toast').on('hidden.bs.toast', function () {
        $(this).css('display', 'none');
    });
});
