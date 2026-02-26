import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';
import { validateFileInput } from '../../utils/logo/validateFileInput.js';
import { validateGeneric } from '../../utils/request/validate.js';
import { sendRequestBase } from '../../utils/sendRequestBase.js';
import { setupAvatarInput } from '../../utils/logo/avatarHandler.js';

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
        isDriver = false,
        new_user = 0;

    validateFileInput('#driving_license', function (file) {
        driving_license = file;
    });

    validateFileInput('#health_book', function (file) {
        health_book = file;
    });

    $('#generate-pin').click(function () {
        var text = '';
        var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        for (var i = 0; i < 16; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        //$('#pin').val(text);
    });

    $('#generate-password').on('click', function () {
        var text = '';
        let possibleBigLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        let possibleSmallLetters = 'abcdefghklmnopqrstuvwxyz';
        let numbers = '1234567890';
        let symbols = '!@#$%&';

        for (var i = 0; i < 4; i++) {
            text +=
                possibleBigLetters.charAt(Math.floor(Math.random() * possibleBigLetters.length)) +
                possibleSmallLetters.charAt(
                    Math.floor(Math.random() * possibleSmallLetters.length)
                ) +
                numbers.charAt(Math.floor(Math.random() * numbers.length)) +
                symbols.charAt(Math.floor(Math.random() * symbols.length));
        }
        $('input[name="password"]').val(text);
    });

    $('#generate-pin').on('click', function () {
        $('input[name="pin"]').val(Math.floor(Math.random() * (9999 - 1000 + 1) + 1000));
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

    $('#create_user').on('click', async function () {
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
        new_user = $('#new_user').val();
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
        formData.append('password', password);
        formData.append('pin', pin);
        formData.append('sex', sex);
        formData.append('position', position);
        formData.append('company_id', company_id);
        formData.append('role', role_key);

        const selectedValues = $('#warehouses').val() || [];

        if (selectedValues.includes('all')) {
            // Якщо обрано "Усі" — передаємо null
            formData.append('warehouse_ids[]', null);
        } else {
            // Інакше передаємо конкретні ID
            selectedValues.filter(Boolean).forEach((id) => formData.append('warehouse_ids[]', id));
        }

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

        // ✅ Редірект з локаллю
        let redirect = function () {
            if (new_user == 1) {
                localStorage.setItem('email', email);
                localStorage.setItem('password', password);
            }
            window.location.href = `${window.location.origin}/${locale}/users/all`;
        };

        // 🚀 Якщо все валідно — створення + редірект
        await sendRequestBase(url + 'users/create', formData, validate, redirect);
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
                // (ми не змінюємо валідацію — sendRequestBase залишається як є)
            ],
            container: '#work-data-message',
        },
    ];

    await validateGeneric(response, fieldGroups, '#private-data-message');
}
