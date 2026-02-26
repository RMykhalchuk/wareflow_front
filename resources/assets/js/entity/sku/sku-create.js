import { sendRequestBase } from '../../utils/sendRequestBase.js';
import { redirectWithLocale } from '../../utils/redirectWithLocale.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';
import { validateGeneric } from '../../utils/request/validate.js';
import { setupAvatarInput } from '../../utils/logo/avatarHandler.js';
import { getLocalizedText } from '../../localization/sku/getLocalizedText.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();

    let avatar = '';
    let hasUnsavedChanges = false;

    const url =
        locale === 'en'
            ? `` // без префіксу
            : `${locale}/`;

    function getFormData() {
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const formData = new FormData();

        if (avatar !== '') {
            formData.append('image', avatar);
        }

        formData.append('_token', csrf);

        // Basic Data
        formData.append('name', $('#name').val());

        const barcodes = getTagsFromHiddenInput('barcodes');
        barcodes.forEach((val, idx) => {
            formData.append(`barcodes[${idx}]`, val);
        });

        formData.append('category_id', $('#category_id').val());
        formData.append('brand', $('#brand').val());
        formData.append('provider', $('#provider').val());

        formData.append('manufacturer', $('#manufacturer').val());
        formData.append('manufacturer_country_id', $('#manufacturer_country_id').val());

        const expirationDates = getTagsFromHiddenInput('expiration_date');
        expirationDates.forEach((val, idx) => {
            formData.append(`expiration_date[${idx}]`, val);
        });

        // Switches
        formData.append('is_batch_accounting', $('#is_batch_accounting').is(':checked') ? 1 : 0);
        formData.append('is_weight', $('#is_weight').is(':checked') ? 1 : 0);

        // Parameters
        formData.append('measurement_unit_id', $('#measurement_unit_id').val());
        formData.append('height', $('#height').val());
        formData.append('width', $('#width').val());
        formData.append('length', $('#length').val());
        formData.append('weight_netto', $('#weight_netto').val());
        formData.append('weight_brutto', $('#weight_brutto').val());

        // ADR
        formData.append('adr_id', $('#adr_id').val());

        // Temperature
        if ($('#temp_regime').is(':checked')) {
            formData.append('temp_from', $('#temp_from').val());
            formData.append('temp_to', $('#temp_to').val());
            // formData.append('temp_regime', 1);
        }

        // Humidity
        if ($('#humidity').is(':checked')) {
            formData.append('humidity_from', $('#humidity_from').val());
            formData.append('humidity_to', $('#humidity_to').val());
            // formData.append('humidity', 1);
        }

        // Dustiness
        if ($('#dustiness').is(':checked')) {
            formData.append('dustiness_from', $('#dustiness_from').val());
            formData.append('dustiness_to', $('#dustiness_to').val());
            // formData.append('dustiness', 1);
        }

        return formData;
    }

    function getData() {
        let formData = getFormData();
        let allPackets = tableData;

        // ключі, які завжди ігноруємо
        const skipKeys = ['canEdit', 'uuid', 'type_name'];

        allPackets.forEach((item, index) => {
            for (const [key, value] of Object.entries(item)) {
                // пропускаємо id
                if (!skipKeys.includes(key)) {
                    formData.append(`packages[${index}][${key}]`, value);
                }
            }
        });

        return formData;
    }

    function getDataEditSku() {
        let formData = getFormData();
        formData.append('_method', 'PUT');

        let allPackets = tableData;

        // ключі, які завжди ігноруємо
        const skipKeys = ['canEdit', 'type_name'];

        allPackets.forEach((item, index) => {
            // якщо є id з бекенду (UUID) — додаємо його явно
            if (item.id && isNaN(item.id)) {
                formData.append(`packages[${index}][id]`, item.id);
            }

            for (const [key, value] of Object.entries(item)) {
                if (!skipKeys.includes(key)) {
                    formData.append(`packages[${index}][${key}]`, value);
                }
            }
        });

        return formData;
    }

    async function validateSku(response) {
        const fieldGroups = [
            {
                fields: [
                    'name',
                    'barcodes',
                    'category_id',
                    'brand',
                    'manufacturer',
                    'manufacturer_country_id',
                    'expiration_date',
                    'is_batch_accounting',
                    'is_weight',
                    'avatar',
                    'provider',
                ],
                container: '#basic-data-message',
            },
            {
                fields: [
                    'measurement_unit_id',
                    'height',
                    'width',
                    'length',
                    'weight_netto',
                    'weight_brutto',
                ],
                container: '#parameters-message',
            },
            {
                fields: ['packages'],
                container: '#pak-message',
                includePrefix: 'packages.',
            },
            {
                fields: [
                    'adr_id',
                    'temp_regime',
                    'temp_from',
                    'temp_to',
                    'humidity',
                    'humidity_from',
                    'humidity_to',
                    'dustiness',
                    'dustiness_from',
                    'dustiness_to',
                ],
                container: '#storage-conditions-message',
            },
        ];

        await validateGeneric(response, fieldGroups, '#basic-data-message');
    }

    $('#save').on('click', async function () {
        await sendRequestBase(`${url}sku`, getData(), validateSku, function () {
            hasUnsavedChanges = false;
            redirectWithLocale('/sku');
        });
    });

    $('#edit').on('click', async function (e) {
        const id = e.target.dataset.id;

        await sendRequestBase(`${url}sku/${id}`, getDataEditSku(), validateSku, function () {
            hasUnsavedChanges = false;
            redirectWithLocale('/sku');
        });
    });

    function getTagsFromHiddenInput(name) {
        const hiddenInput = $(`#hidden-${name}`);
        if (!hiddenInput.length) return [];

        const raw = hiddenInput.val() || '[]';

        try {
            return JSON.parse(raw);
        } catch (e) {
            console.warn(`JSON parse error for hidden input #hidden-${name}:`, e);
            return [];
        }
    }

    setupAvatarInput('#logo', {
        maxSizeMB: 0.8,
        emptySrc: '/assets/icons/entity/goods/avatar-default.svg', // тепер гнучко
        onChange: (file) => {
            avatar = file;
            hasUnsavedChanges = true;
        },
    });

    $(document).on('input change', 'input, textarea, select', function () {
        hasUnsavedChanges = true;
    });

    window.addEventListener('beforeunload', function (e) {
        if (!hasUnsavedChanges) return;

        e.preventDefault();
        e.returnValue = '';
    });

    $(document).on('click', 'a', function (e) {
        if (!hasUnsavedChanges) return;

        const href = $(this).attr('href');

        // якщо це кнопка, якорь або js
        if (!href || href.startsWith('#') || href.startsWith('javascript:')) {
            return;
        }

        e.preventDefault();

        const ok = confirm(getLocalizedText('alert_confirm'));

        if (ok) {
            hasUnsavedChanges = false;
            window.location.href = href;
        }
    });
});
