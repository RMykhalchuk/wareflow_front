import { sendRequest } from '../../utils/sendRequest.js';
import { redirectWithLocale } from '../../utils/redirectWithLocale.js';
import { validateFileInput } from '../../utils/logo/validateFileInput.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';
import { validateGeneric } from '../../utils/request/validate.js';
import { sendRequestBase } from '../../utils/sendRequestBase.js';
import { setupAvatarInput } from '../../utils/logo/avatarHandler.js';
import { validatePassword } from '../../utils/validatePassword.js';

$(function () {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;
    let urlBase = window.location.origin;

    const locale = getCurrentLocaleFromUrl();

    const url =
        locale === 'en'
            ? `` // без префіксу
            : `${locale}/`;

    let avatar = '',
        surname = '',
        firstName = '',
        patronymic = '',
        birthday = '',
        email = '',
        phone = '',
        position = '',
        company_id = '',
        role_key = '',
        password = '',
        sex = '',
        pin;

    let health_book = '',
        health_book_number = '',
        driving_license_number = '',
        driving_license = '',
        driver_license_date = '',
        health_book_date = '',
        isDriver = false;

    let user_id = $('#user-id').attr('data-id');

    $('#generate-code').click(function () {
        var text = '';
        var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        for (var i = 0; i < 16; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        $('#code').val(text);
    });

    $('#position').on('change', function () {
        if ($(this).find(':selected').val() === 'driver') {
            isDriver = true;
            $('#driver_block').css('display', 'flex');
        } else {
            isDriver = false;
            $('#driver_block').css('display', 'none');
        }
    });

    $('#change-password').click(function () {
        const newPassword = $('input[name="new_password"]').val();
        const passwordError = validatePassword(newPassword, getCurrentLocaleFromUrl());
        if (passwordError) {
            $('#change-password-message').text(passwordError).removeClass('d-none');
            return;
        }

        let formData = new FormData();
        formData.append('_token', csrf);
        //formData.append('login', $('#passwordEmail').val())
        formData.append('password', $('#old-password').val());
        formData.append('new_password', $('input[name="new_password"]').val());
        formData.append('confirm_password', $('input[name="confirm_password"]').val());
        let callback;
        callback = function () {
            $('input[name="password"]').val('');
            $('input[name="new_password"]').val('');
            $('input[name="confirm_password"]').val('');
        };
        sendRequest(
            '/users/change-password/' + user_id,
            formData,
            '#change-password-message',
            callback
        );
        $('#edit_user_pass').modal('hide');
    });

    validateFileInput('#driving_license', function (file) {
        driving_license = file;
    });

    validateFileInput('#health_book', function (file) {
        health_book = file;
    });

    $('#save').click(async function () {
        let formData = new FormData();
        const locale = getCurrentLocaleFromUrl();

        surname = $('#accountLastName').val();
        firstName = $('#accountFirstName').val();
        patronymic = $('#accountPatronymic').val();
        birthday = $('#birthday').val();
        email = $('#accountEmail').val();
        phone = $('#phone').val();
        password = $('#password').val();
        sex = $('#sex').val();
        company_id = $('#company_id').val();
        position = $('#position').val();
        role_key = $('#role').val();
        pin = $('#pin').val();

        formData.append('_token', csrf);
        if (avatar !== '') {
            formData.append('avatar', avatar);
        }
        formData.append('surname', surname);
        formData.append('name', firstName);
        formData.append('patronymic', patronymic);
        formData.append('birthday', birthday);
        formData.append('phone', phone);
        formData.append('email', email);

        formData.append('sex', sex);
        formData.append('position', position);
        formData.append('company_id', company_id);
        formData.append('role', role_key);

        formData.append('pin', pin);

        const selectedValues = $('#warehouses').val() || [];

        if (selectedValues.includes('all')) {
            // Якщо обрано "Усі" — передаємо null
            formData.append('warehouse_ids[]', null);
        } else {
            // Інакше передаємо конкретні ID
            selectedValues.filter(Boolean).forEach((id) => formData.append('warehouse_ids[]', id));
        }

        formData.append('need_file', $('#need_file').val());
        if (isDriver) {
            health_book_number = $('#health_book_number').val();
            driving_license_number = $('#driving_license_number').val();

            driver_license_date = $('#driver_license_date').val();
            health_book_date = $('#health_book_date').val();

            formData.append('health_book_number', health_book_number);
            formData.append('driving_license_number', driving_license_number);

            formData.append('health_book_date', health_book_date);
            formData.append('driver_license_date', driver_license_date);

            formData.append('health_book', health_book);
            formData.append('driving_license', driving_license);
        }

        // 🚀 Якщо все валідно — створення + редірект
        await sendRequestBase(
            url + 'users/account/update/' + user_id,
            formData,
            validate,
            function () {
                redirectWithLocale('/users/all');
            }
        );
    });

    setupAvatarInput('#account', {
        maxSizeMB: 0.8,
        emptySrc: '/assets/icons/entity/user/avatar_empty.png',
        onChange: (file) => {
            avatar = file;
        },
    });
});

async function validate(response) {
    const fieldGroups = [
        // 🧍‍♂️ Особисті дані
        {
            fields: [
                'surname',
                'name',
                'patronymic',
                'birthday',
                'email',
                'phone',
                'sex',
                'password',
            ],
            container: '#private-data-message',
        },

        // 💼 Робочі дані
        {
            fields: [
                'company_id',
                'role',
                'position',
                'pin',
                'driving_license_number',
                'driving_license_number_term',
                'driving_license',
                'health_book_number',
                'health_book_number_term',
                'health_book',
            ],
            container: '#work-data-message',
        },
    ];

    await validateGeneric(response, fieldGroups, '#private-data-message');
}
