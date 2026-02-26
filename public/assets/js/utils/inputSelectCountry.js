export function inputSelectCountry(idInput) {
    const input = document.querySelector(idInput);
    var keyValue = '';
    const iti = window.intlTelInput(input, {
        initialCountry: 'auto',
        geoIpLookup: function (callback) {
            $.get('https://ipinfo.io', function () {}, 'jsonp').always(function (resp) {
                const countryCode = resp && resp.country ? resp.country : 'ua';
                callback(countryCode);
            });
        },
        utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.6/js/utils.js',
        onlyCountries: [
            'al',
            'ad',
            'at',
            'by',
            'be',
            'ba',
            'bg',
            'hr',
            'cz',
            'dk',
            'ee',
            'fo',
            'fi',
            'fr',
            'de',
            'gi',
            'gr',
            'va',
            'hu',
            'is',
            'ie',
            'it',
            'lv',
            'li',
            'lt',
            'lu',
            'mk',
            'mt',
            'md',
            'mc',
            'me',
            'nl',
            'no',
            'pl',
            'pt',
            'ro',
            'sm',
            'rs',
            'sk',
            'si',
            'es',
            'se',
            'ch',
            'ua',
            'gb',
        ],
    });

    function validateInput() {
        const dialCode = iti.getSelectedCountryData().dialCode;
        if (!input.value.startsWith(`+${dialCode}`)) {
            input.value = `+${dialCode}`;
        }

        const countryCode = iti.getSelectedCountryData().iso2;
        if (countryCode === 'ua' && input.value.length > 13) {
            input.value = input.value.slice(0, 13);
        }
    }

    input.addEventListener('input', function (e) {
        const selectedDialCode = iti.getSelectedCountryData().dialCode;
        const inputValue = input.value;

        if (inputValue.startsWith(`+${selectedDialCode}`)) {
            const codeLength = selectedDialCode.length + 1;
            const digitsOnly = inputValue.slice(codeLength).replace(/\D/g, '');
            input.value = `+${selectedDialCode}${digitsOnly}`;
        } else {
            input.value = `+${selectedDialCode}${keyValue}`;
        }

        validateInput();
    });

    input.addEventListener('blur', validateInput);

    input.addEventListener('countrychange', function () {
        const dialCode = iti.getSelectedCountryData().dialCode;
        if (!input.value.startsWith(`+${dialCode}`)) {
            input.value = `+${dialCode}`;
        }

        validateInput();
    });

    input.addEventListener('keydown', function (e) {
        const selectedDialCode = iti.getSelectedCountryData().dialCode;
        if (e.key === 'Backspace' || e.keyCode === 8) {
            const inputValue = input.value;
            if (inputValue === `+${selectedDialCode}`) {
                e.preventDefault(); // Забороняємо видалення
            }
        }
        var keyCode = e.keyCode || e.which;
        // Перевіряємо, чи є код клавіші цифрою
        if (keyCode >= 48 && keyCode <= 57) {
            keyValue = String.fromCharCode(keyCode);
        } else {
            keyValue = '';
        }
    });
}
