import { calculateVolume } from '../../utils/calculateVolume.js';
import { redirectWithLocale } from '../../utils/redirectWithLocale.js';
import { sendRequestBase } from '../../utils/sendRequestBase.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';
import { validateGeneric } from '../../utils/request/validate.js';
import { setupAvatarInput } from '../../utils/logo/avatarHandler.js';

$(function () {
    const locale = getCurrentLocaleFromUrl();

    const url =
        locale === 'en'
            ? `` // без префіксу
            : `${locale}/`;

    let transport_img = '';
    let csrf = document.querySelector('meta[name="csrf-token"]').content;

    $('#category').on('change', function () {
        if ($(this).val() == 2) {
            $('#additional-data').css('display', 'block');
        } else {
            $('#additional-data').css('display', 'none');
        }
    });

    $('#mark').on('change', async function () {
        let url = window.location.origin;

        let modelSelect = $('#model');
        modelSelect.removeAttr('disabled');
        modelSelect.empty();
        await fetch(url + '/transports/model-by-brand/' + $(this).val(), {
            method: 'GET',
        }).then(async (response) => {
            let res = await response.json();
            let disabledOption = document.createElement('option');
            disabledOption.setAttribute('disabled', '');
            disabledOption.setAttribute('selected', '');

            res.data.forEach((el) => {
                let option = document.createElement('option');
                option.value = el.id;
                option.innerHTML = el.name;
                modelSelect.append(option);
            });
        });
    });

    $('#country').on('change', function () {
        if ($(this).val() === 1) {
            $('#license_plate').attr('placeholder', 'AA0000AA');
        } else {
            $('#license_plate').attr('placeholder', '');
        }
    });

    function makeFormData() {
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        let formData = new FormData();

        const getVal = (id) => $(`#${id}`).val();

        formData.append('_token', csrf);
        formData.append('image', transport_img);
        formData.append('mark', getVal('mark'));
        formData.append('model', getVal('model'));
        formData.append('type', getVal('type'));
        formData.append('category', getVal('category'));
        formData.append('weight', getVal('weight'));
        formData.append('license_plate', getVal('license_plate').toUpperCase());

        formData.append('equipment', getVal('additional_equipment'));
        formData.append('registration_country', getVal('country'));
        formData.append('manufacture_year', getVal('manufacture_year'));
        formData.append('company', getVal('company'));
        formData.append('driver', getVal('driver'));

        // if ($('#country').val() == 1) {
        //     formData.append('license_plate', $('#license_plate').val().toUpperCase())
        // } else {
        //     formData.append('license_plate_without_mask', $('#license_plate').val().toUpperCase())
        // }

        if (getVal('category') == 2) {
            formData.append('download_methods', JSON.stringify($('#download_methods').val()));
            formData.append('adr', getVal('adr'));
            formData.append('carrying_capacity', getVal('carrying_capacity'));
            formData.append('length', getVal('length'));
            formData.append('width', getVal('width'));
            formData.append('height', getVal('height'));
            formData.append('volume', getVal('volume'));
            formData.append('capacity_eu', getVal('capacity_eu'));
            formData.append('capacity_am', getVal('capacity_am'));
            formData.append('hydroboard', $('#hydroboard').prop('checked'));
        }

        formData.append('spending_empty', getVal('spending_empty'));
        formData.append('spending_full', getVal('spending_full'));

        return formData;
    }

    $('#save').on('click', async function () {
        let formData = makeFormData();

        await sendRequestBase(`${url}transports`, formData, validate, function () {
            redirectWithLocale('/transports');
        });
    });

    $('#update').on('click', async function () {
        let formData = makeFormData();
        formData.append('_method', 'PUT');

        const id = $('#data_tab_1').attr('data-id');

        await sendRequestBase(`${url}transports/${id}`, formData, validate, function () {
            redirectWithLocale(`/transports/${id}`);
        });
    });

    document.getElementById('length').addEventListener('input', calculateVolume);
    document.getElementById('width').addEventListener('input', calculateVolume);
    document.getElementById('height').addEventListener('input', calculateVolume);

    setupAvatarInput('#image', {
        maxSizeMB: 0.8,
        emptySrc: '/assets/icons/entity/transport/default-truck-empty.svg',
        onChange: (file) => {
            transport_img = file;
        },
    });

    $('#update-transport-reset').on('click', function () {
        let url = window.location.origin;

        $.ajax({
            url: url + '/transports/delete-image/' + $('#data_tab_1').attr('data-id'),
            method: 'post',
            data: { _token: csrf },
            success: function () {
                location.reload();
            },
        });
    });
});

async function validate(response) {
    const fieldGroups = [
        {
            fields: [
                'image',
                'mark',
                'model',
                'type',
                'category',
                'weight',
                'license_plate',
                'registration_country',
                'spending_empty',
                'manufacture_year',
                'company',
                'driver',
                'spending_full',
            ],
            container: '#main-data-message',
        },
        {
            fields: [
                'download_methods',
                'adr',
                'carrying_capacity',
                'length',
                'width',
                'height',
                'volume',
                'capacity_eu',
                'capacity_am',
                'hydroboard',
            ],
            container: '#capacity-data-message',
        },
    ];

    await validateGeneric(response, fieldGroups, '#main-data-message');
}
