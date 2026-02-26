import { calculateVolume } from '../../utils/calculateVolume.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';
import { redirectWithLocale } from '../../utils/redirectWithLocale.js';
import { sendRequestBase } from '../../utils/sendRequestBase.js';
import { validateGeneric } from '../../utils/request/validate.js';
import { setupAvatarInput } from '../../utils/logo/avatarHandler.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();

    const url =
        locale === 'en'
            ? `` // без префіксу
            : `${locale}/`;

    let additional_equipment_img = '';
    let csrf = document.querySelector('meta[name="csrf-token"]').content;

    $('#mark-equipment').on('change', async function () {
        let url = window.location.origin;
        let modelSelect = $('#model');
        modelSelect.removeAttr('disabled');
        modelSelect.empty();
        await fetch(url + '/equipment-model-by-brand/' + $(this).val(), {
            method: 'GET',
        }).then(async (response) => {
            let res = await response.json();
            let disabledOption = document.createElement('option');
            disabledOption.setAttribute('disabled', '');
            disabledOption.setAttribute('selected', '');

            //disabledOption.innerHTML = "Виберіть модель обладнання"
            //modelSelect.append(disabledOption)

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

    $('#save').on('click', async function () {
        let formData = makeFormData();

        await sendRequestBase(`${url}transport-equipments`, formData, validate, function () {
            redirectWithLocale('/transport-equipments');
        });
    });

    $('#edit').on('click', async function () {
        let formData = makeFormData();
        formData.append('_method', 'PUT');

        const id = $(this).attr('data-id');
        await sendRequestBase(`${url}transport-equipments/${id}`, formData, validate, function () {
            redirectWithLocale('/transport-equipments');
        });
    });

    document.getElementById('length').addEventListener('input', calculateVolume);
    document.getElementById('width').addEventListener('input', calculateVolume);
    document.getElementById('height').addEventListener('input', calculateVolume);

    function makeFormData() {
        let csrf = document.querySelector('meta[name="csrf-token"]').content;
        let formData = new FormData();

        formData.append('_token', csrf);
        formData.append('image', additional_equipment_img);

        const getVal = (id) => $(`#${id}`).val();

        formData.append('mark', getVal('mark-equipment'));
        formData.append('model', getVal('model'));
        formData.append('type', getVal('type-equipment'));

        // видалити на беку weight
        // formData.append('weight', $('#weight').val())

        formData.append('license_plate', getVal('license_plate').toUpperCase());

        // if ($('#country').val() == 1) {
        //     formData.append('license_plate', $('#license_plate').val().toUpperCase())
        // } else {
        //     formData.append('license_plate_without_mask', $('#license_plate').val().toUpperCase())
        // }

        formData.append('transport', getVal('transport'));
        formData.append('registration_country', getVal('country'));
        formData.append('manufacture_year', getVal('manufacture_year'));
        formData.append('company', getVal('company'));
        formData.append('download_methods', JSON.stringify($('#download_method').val()));
        formData.append('adr', getVal('adr'));
        formData.append('carrying_capacity', getVal('carrying_capacity'));
        formData.append('length', getVal('length'));
        formData.append('width', getVal('width'));
        formData.append('height', getVal('height'));
        formData.append('volume', getVal('volume'));
        formData.append('capacity_eu', getVal('capacity_eu'));
        formData.append('capacity_am', getVal('capacity_am'));
        formData.append('hydroboard', $('#hydroboard').prop('checked'));

        return formData;
    }

    setupAvatarInput('#image', {
        maxSizeMB: 0.8,
        emptySrc: '/assets/icons/entity/additional-equipment/default-truck-empty.svg',
        onChange: (file) => {
            additional_equipment_img = file;
        },
    });

    $('#update-equipment-reset').on('click', function () {
        let url = window.location.origin;

        $.ajax({
            url: url + '/transport-equipments/delete-image/' + $(this).attr('data-id'),
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
                'license_plate',
                'registration_country',
                'download_methods',
                'manufacture_year',
                'company',
                'transport',
            ],
            container: '#main-data-message',
        },
        {
            fields: [
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
