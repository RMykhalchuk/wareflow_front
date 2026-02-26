import { getLocalizedText } from '../../localization/auth/getLocalizedText.js';
import { hideInputGroup, showInputGroup, toggleInputGroups } from '../../utils/inputGroups.js';
import { submitFeedbackForm } from '../../utils/submitFeedbackForm.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';
import { validatePassword } from '../../utils/validatePassword.js';

const authPage = $('.auth');
const registerPage = $('.register');
const passRecoveryPage = $('.passRecovery');
const hidden = 'd-none';

// перехід на сторінку реєстрації
$('.link-to-register').click(function (e) {
    e.preventDefault();
    authPage.hide();
    if (registerPage.hasClass(hidden)) {
        registerPage.removeClass(hidden);
    }
    registerPage.show();
    focusFirstInputInsideElement(registerPage);
});

// перехід на сторінку  Відновлення паролю
$('.link-to-passRecovery').click(function (e) {
    e.preventDefault();
    authPage.hide();
    if (passRecoveryPage.hasClass(hidden)) {
        passRecoveryPage.removeClass(hidden);
    }
    passRecoveryPage.show();
    focusFirstInputInsideElement(passRecoveryPage);
});

// перехід на сторінку увійти в акаунт
$('.link-to-auth').click(function (e) {
    e.preventDefault();
    registerPage.hide();
    passRecoveryPage.hide();
    authPage.show();
    focusFirstInputInsideElement(authPage);
});

// prev-pages
$('.btn-to-registerPage').click(function () {
    changeSection($('#register_code'), $('#register_page'));
});

$('.btn-to-passRecoveryPage').click(function () {
    changeSection($('#passRecovery_code'), $('#passRecovery_page'));
});

$('.btn-to-passRecoveryPage-two').click(function () {
    changeSection($('#passRecovery_page_writeNew'), $('#passRecovery_page'));
});

$(document).ready(function () {
    let urlBase = window.location.origin;

    const locale = getCurrentLocaleFromUrl();

    const url =
        locale === 'en'
            ? `` // без префіксу
            : `${locale}/`;

    let csrf = document.querySelector('meta[name="csrf-token"]').content;

    const loginFormObject = {};
    //verify  authorization
    $('#login-form').on('submit', async function (e) {
        e.preventDefault();
        const inpEmail = $('#login-email-auth');
        const inpNumber = $('#authNumberInp');
        const loginValue =
            inpEmail.attr('aria-selected') === 'true' ? inpEmail.val() : inpNumber.val();

        Object.assign(loginFormObject, {
            _token: csrf,
            login: loginValue,
            password: $('#login-password').val(),
        });

        await fetch(urlBase + '/' + url + 'login', {
            method: 'POST',
            body: JSON.stringify(loginFormObject),
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
            },
        }).then(async (response) => {
            if (response.status === 204) {
                window.location = '/';
            } else if (response.status === 419) {
                addError(
                    $('#login-errors'),
                    $('#login-form'),
                    getLocalizedText('authErrorText419')
                );
            } else {
                let text = await response.text();

                addError($('#login-errors'), $('#login-form'), JSON.parse(text).message);
            }
        });
    });

    const registerFormObject = {};
    //verify existing login and send verify code on email\phone
    $('#register-login-form').on('submit', async function (e) {
        e.preventDefault();

        const passwordError = validatePassword($('#registerPassword').val(), locale);
        if (passwordError) {
            addError($('#register-errors'), $(this), passwordError);
            return;
        }

        const inpEmail = $('#registerEmailInp');
        const inpNumber = $('#registerNumberInp');

        const inpEmailVal = $('#registerEmailInp').val();
        const writeCurrentMail = $('#writeCurrentMail');

        const inputNumberVal = $('#registerNumberInp').val();
        const currentNumberVal = $('#showWriteNumberRegister');

        const loginValue =
            inpEmail.attr('aria-selected') === 'true' ? inpEmail.val() : inpNumber.val();

        chooseCorrectPageWizard('register', loginValue === inpEmail.val() ? 'email' : 'number');
        Object.assign(registerFormObject, {
            _token: csrf,
            login: loginValue,
            password: $('#registerPassword').val(),
            password_confirmation: $('#registerPasswordRepeat').val(),
        });

        await fetch(urlBase + '/' + url + 'register/send-code', {
            method: 'POST',
            body: JSON.stringify(registerFormObject),
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
            },
        }).then(async (response) => {
            if (response.status === 200) {
                changeSection($('#register_page'), $('#register_code'));
                if ($(inpEmail.attr('aria-selected') === 'true')) {
                    focusFirstInputInsideElement($('.register-code-by-phone-content'));
                }

                writeCurrentMail.text(inpEmailVal);
                currentNumberVal.text(inputNumberVal);
            } else {
                let text = await response.text();

                addError(
                    $('#register-errors'),
                    $('#register-login-form'),
                    JSON.parse(text).message
                );
            }
        });
    });

    //repeat send verify codes for register
    $('#refresh-email-code, #refresh-phone-code').on('click', async function () {
        await fetch(urlBase + '/' + url + 'register/send-code', {
            method: 'POST',
            body: JSON.stringify(registerFormObject),
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
            },
        }).then(async (response) => {
            if ([200, 201].includes(response.status)) {
                $('#toastContainer').removeClass(hidden).text(getLocalizedText('refreshCodeText'));
                setTimeout(() => {
                    $('#toastContainer').addClass(hidden);
                }, 4000);
            }
        });
    });

    //handle submitting form with verify codes for register
    $('#register-send-email-code, #register-send-phone-code').on('submit', async function (e) {
        e.preventDefault();
        let form = $(this);
        let alertElement = form.find('.alert');
        let code = '';

        $('#otp-register-code-by-email')
            .find('.otp__input')
            .each((index, value) => {
                code += value.value;
            });

        await fetch(urlBase + '/' + url + 'register/register', {
            method: 'POST',
            body: JSON.stringify({
                code,
                ...registerFormObject,
            }),
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
            },
        }).then(async (response) => {
            if ([200, 201].includes(response.status)) {
                window.location = '/onboarding';
            } else {
                let text = await response.text();

                addError(alertElement, form, JSON.parse(text).message);
            }
        });
    });

    const resetPasswordForm = {};

    //verify login and sending verify code on phone\email
    $('#reset-password-form').on('submit', async function (e) {
        e.preventDefault();
        let form = $(this);
        let alertElement = form.find('.alert');

        const inpEmail = $('#passRecoveryEmailInp');
        const inpNumber = $('#passRecoveryNumberInp');
        const loginValue =
            inpEmail.attr('aria-selected') === 'true' ? inpEmail.val() : inpNumber.val();

        const inpEmailVal = $('#passRecoveryEmailInp').val();
        const writeCurrentMail = $('#writeCurrentMailRestore');

        const inputNumberVal = $('#passRecoveryNumberInp').val();
        const CurrentMailVal = $('#showWriteNumber');

        chooseCorrectPageWizard('passRecovery', loginValue === inpEmail.val() ? 'email' : 'number');
        Object.assign(resetPasswordForm, {
            _token: csrf,
            login: loginValue,
        });

        await fetch(urlBase + '/' + url + 'password/send-code', {
            method: 'POST',
            body: JSON.stringify(resetPasswordForm),
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
            },
        }).then(async (response) => {
            if (response.status === 200) {
                changeSection($('#passRecovery_page'), $('#passRecovery_code'));
                if ($(inpEmail.attr('aria-selected') === 'true')) {
                    focusFirstInputInsideElement($('.passRecovery-code-by-phone-content'));
                }
                writeCurrentMail.text(inpEmailVal);
                CurrentMailVal.text(inputNumberVal);
            } else {
                let text = await response.text();
                addError(alertElement, form, JSON.parse(text).message);
            }
        });
    });

    //handle submitting form with verify codes for reset password
    $('#reset-password-code-email-form, #reset-password-code-phone-form').on(
        'submit',
        async function (e) {
            e.preventDefault();
            let form = $(this);
            let alertElement = form.find('.alert');
            let code = '';

            $(this)
                .find('.otp__input')
                .each((index, value) => {
                    code += value.value;
                });
            Object.assign(resetPasswordForm, {
                code: code,
            });

            await fetch(urlBase + '/' + url + 'verification/validate-code', {
                method: 'POST',
                body: JSON.stringify(resetPasswordForm),
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                },
            }).then(async (response) => {
                if (response.status === 200) {
                    changeSection($('#passRecovery_code'), $('#passRecovery_page_writeNew'));
                } else {
                    let text = await response.text();
                    addError(alertElement, form, JSON.parse(text).message);
                }
            });
        }
    );

    //repeat sending verify code listener for reset password
    $('#refresh-password-email-code, #refresh-password-phone-code').on('click', async function (e) {
        e.preventDefault();

        const inpEmail = $('#passRecoveryEmailInp');
        const inpNumber = $('#passRecoveryNumberInp');
        const loginValue =
            inpEmail.attr('aria-selected') === 'true' ? inpEmail.val() : inpNumber.val();

        Object.assign(resetPasswordForm, {
            _token: csrf,
            login: loginValue,
        });

        await fetch(urlBase + '/' + url + 'password/send-code', {
            method: 'POST',
            body: JSON.stringify(resetPasswordForm),
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
            },
        }).then(async (response) => {
            $('#toastContainer')
                .removeClass(hidden)
                .text(getLocalizedText('refreshPasswordCodeText'));
            setTimeout(() => {
                $('#toastContainer').addClass(hidden);
            }, 4000);
        });
    });

    //input new passwords listener for reset password
    $('#reset-new-password-form').on('submit', async function (e) {
        e.preventDefault();

        const passwordError = validatePassword($('#passRecovery-password').val(), locale);
        if (passwordError) {
            addError($(this).find('.alert'), $(this), passwordError);
            return;
        }

        await fetch(urlBase + '/' + url + 'password/reset', {
            method: 'POST',
            body: JSON.stringify({
                password: $('#passRecovery-password').val(),
                password_confirmation: $('#passRecovery-password-repeat').val(),
                ...resetPasswordForm,
            }),
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
            },
        }).then(async (response) => {
            if (response.status === 200) {
                window.location = '/';
            } else {
                let text = await response.text();

                addError($(this).find('.alert')[0], $(this), JSON.parse(text).message);
            }
        });
    });

    $('#feedback-form').on('submit', submitFeedbackForm);
});

function focusFirstInputInsideElement(el) {
    const element = el[0];
    const inputInsideEl = element.querySelector('input');
    inputInsideEl.focus();
}

function addError(el, formEl, err) {
    el.text(err);
    el.removeClass(hidden);
    formEl.on('input', function () {
        if (!el.hasClass(hidden)) {
            el.addClass(hidden);
        }
    });
}

function changeSection(from, to) {
    to.removeClass('d-none');
    if (!from.hasClass('d-none')) {
        from.addClass('d-none');
    }
    focusFirstInputInsideElement($(to));
}

// інпути з кодом перевірки ( логіка їх роботи)
function OTPInput(idGroupInputs) {
    const inputs = document.querySelectorAll(`#${idGroupInputs} > *[id]`);
    const formClassName = document.querySelector(`#${idGroupInputs}`).dataset.formclassname;
    const form = document.querySelector(formClassName);
    const buttonNext = form.querySelector('.btnDisabled');
    const lastInputIndex = inputs.length - 1;

    function focusNextInput(currentInput) {
        const index = Array.from(inputs).indexOf(currentInput);
        if (index !== -1 && index < lastInputIndex) {
            inputs[index + 1].focus();
        }
    }

    function handleInput(event, currentInput) {
        const pastedText = event.clipboardData.getData('text');
        const digits = pastedText.match(/\d/g);
        if (digits) {
            inputs.forEach((input) => {
                input.value = '';
                input.setAttribute('disabled', 'disabled');
            });

            let index = 0;
            for (const digit of digits) {
                inputs[index].value = digit;
                inputs[index].removeAttribute('disabled');
                index++;
                if (index >= inputs.length) {
                    break;
                }
            }
            focusNextInput(inputs[index - 1]);
            event.preventDefault();

            const isFormComplete = [...inputs].every((input) => input.value.trim() !== '');
            buttonNext.classList.toggle('disabled', !isFormComplete);
        }
    }

    for (let i = 0; i < inputs.length; i++) {
        const input = inputs[i];

        input.addEventListener('keydown', function (event) {
            const key = event.key;
            if (key === 'Backspace') {
                input.value = '';
                if (i !== 0) {
                    inputs[i - 1].focus();
                }
                for (let j = i + 1; j < inputs.length; j++) {
                    inputs[j].value = '';
                    inputs[j].setAttribute('disabled', 'disabled');
                }
            } else if ((key >= '0' && key <= '9') || (key >= 'Numpad0' && key <= 'Numpad9')) {
                input.value = key;
                if (i !== lastInputIndex) {
                    inputs[i + 1].removeAttribute('disabled');
                    inputs[i + 1].focus();
                }
                event.preventDefault();
            }

            const isFormComplete = [...inputs].every((input) => input.value.trim() !== '');
            buttonNext.classList.toggle('disabled', !isFormComplete);
        });

        input.addEventListener('input', function (event) {
            if (!/^\d+$/.test(input.value)) {
                input.value = '';
            }
            if (i === lastInputIndex) {
                if (input.value.length > 1) {
                    input.value = input.value.substring(0, 1);
                    if (inputs[i + 1]) {
                        inputs[i + 1].blur();
                    }
                }
            }

            const isFormComplete = [...inputs].every((input) => input.value.trim() !== '');
            buttonNext.classList.toggle('disabled', !isFormComplete);
        });

        input.addEventListener('paste', function (event) {
            handleInput(event, input);
        });

        if (i !== 0) {
            input.setAttribute('disabled', 'disabled');
        }
    }

    form.addEventListener('focusin', function (event) {
        if (
            inputs[lastInputIndex].value.trim() !== '' &&
            document.activeElement === inputs[lastInputIndex]
        ) {
            buttonNext.classList.remove('disabled');
        }
    });

    form.addEventListener('focusout', function (event) {
        if (
            inputs[lastInputIndex].value.trim() !== '' &&
            document.activeElement !== inputs[lastInputIndex]
        ) {
            buttonNext.classList.remove('disabled');
        }
    });
}
// =============

// функція для вибору сторінки перевірки коду ( задежить чи ввели емейл)

function chooseCorrectPageWizard(page, type = 'email') {
    const pageCodeByEmailContent = $(`.${page}-code-by-email-content`);
    const pageCodeByPhoneContent = $(`.${page}-code-by-phone-content`);
    if (type === 'email') {
        pageCodeByEmailContent.removeClass(hidden);
        if (!pageCodeByPhoneContent.hasClass(hidden)) {
            pageCodeByPhoneContent.addClass(hidden);
        }
    } else {
        pageCodeByPhoneContent.removeClass(hidden);
        if (!pageCodeByEmailContent.hasClass(hidden)) {
            pageCodeByEmailContent.addClass(hidden);
        }
    }
}

// ======

// перехід між інпутом емейл і номер

// функція для считування інпутів в формі ( передаємо елемент форму) віддає по name, тому обовязково кемелкейсом!
function valuesFromInputs(paramsEl) {
    const formData = new FormData(paramsEl);
    const valuesFromInputs = {};
    for (let [key, value] of formData) {
        valuesFromInputs[key] = value;
    }

    return valuesFromInputs;
}

// функція зняття disabled зкнопки форми якщо введені інпути  та порівняння паролів

const enableButtonOnFormCompletion = (formClass) => {
    const forms = document.getElementsByClassName(formClass);

    [...forms].forEach((form) => {
        const inputs = form.getElementsByTagName('input');
        const passwordInput = form.querySelector('input[name="password"]');
        const confirmPasswordInput = form.querySelector('input[name="confirmPassword"]');
        const button = form.querySelector('.btnDisabled');

        if (passwordInput && confirmPasswordInput) {
            [...inputs].forEach((input) => {
                input.addEventListener('input', () => {
                    let isFormComplete = true;
                    for (const input of inputs) {
                        if ($(input).attr('aria-selected') === 'false') {
                            continue;
                        }

                        if (input.value.trim() === '') {
                            isFormComplete = false;
                            break;
                        }
                    }

                    const passwordsMatch = passwordInput.value === confirmPasswordInput.value;

                    button.classList.toggle('disabled', !(isFormComplete && passwordsMatch));
                });
            });

            // логіка алертів
            let timeoutId;
            confirmPasswordInput.addEventListener('input', () => {
                setAlert();
            });

            passwordInput.addEventListener('input', () => {
                setAlert();
            });

            function setAlert() {
                clearTimeout(timeoutId);
                const passwordsMatch = passwordInput.value === confirmPasswordInput.value;

                const atrForm = confirmPasswordInput.dataset.form;
                timeoutId = setTimeout(() => {
                    if (!passwordsMatch && confirmPasswordInput.value && passwordInput.value) {
                        if ($(`.alert-${atrForm}`).hasClass(hidden)) {
                            $(`.alert-${atrForm}`).removeClass(hidden);
                        }
                    } else {
                        if (!$(`.alert-${atrForm}`).hasClass(hidden)) {
                            $(`.alert-${atrForm}`).addClass(hidden);
                        }
                    }
                }, 1000);
            }

            //
        } else {
            [...inputs].forEach((input) => {
                input.addEventListener('input', () => {
                    let isFormComplete = true;
                    for (const input of inputs) {
                        if ($(input).attr('aria-selected') === 'false') {
                            continue;
                        }

                        if (input.value.trim() === '') {
                            isFormComplete = false;
                            break;
                        }
                    }

                    button.classList.toggle('disabled', !isFormComplete);
                });
            });
        }
    });
};

// Hide input groups initially
hideInputGroup('.input-number-group');
hideInputGroup('.input-number-group-modal');

// Show email input groups initially
showInputGroup('.input-email-group');

// Attach event handlers for switching
toggleInputGroups('.input-email-group', '.input-number-group', '.link-to-numberInput');
toggleInputGroups(
    '.input-email-group-modal',
    '.input-number-group-modal',
    '.link-to-numberInputModal'
);
toggleInputGroups('.input-number-group', '.input-email-group', '.link-to-emailInput');
toggleInputGroups(
    '.input-number-group-modal',
    '.input-email-group-modal',
    '.link-to-emailInputModal'
);

OTPInput('otp-register-code-by-email');
OTPInput('otp-register-code-by-phone');

OTPInput('otp-passRecovery-code-by-email');
OTPInput('otp-passRecovery-code-by-phone');

enableButtonOnFormCompletion('auth-login-form');
enableButtonOnFormCompletion('register-form');
enableButtonOnFormCompletion('passRecovery-form');
enableButtonOnFormCompletion('passRecovery-writeNew-form');
