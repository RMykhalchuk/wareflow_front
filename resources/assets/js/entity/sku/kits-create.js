import { sendRequestBase } from '../../utils/sendRequestBase.js';
import { redirectWithLocale } from '../../utils/redirectWithLocale.js';
import { getCurrentLocaleFromUrl } from '../../utils/getCurrentLocaleFromUrl.js';
import { validateGeneric } from '../../utils/request/validate.js';
import { setupAvatarInput } from '../../utils/logo/avatarHandler.js';

$(document).ready(function () {
    const locale = getCurrentLocaleFromUrl();

    let avatar = '';

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

        // Parameters
        formData.append('measurement_unit_id', $('#measurement_unit_id').val());
        formData.append('height', $('#height').val());
        formData.append('width', $('#width').val());
        formData.append('length', $('#length').val());
        formData.append('weight_netto', $('#weight_netto').val());
        formData.append('weight_brutto', $('#weight_brutto').val());

        return formData;
    }

    function getData() {
        let formData = getFormData();
        let allPackets = tableData;
        let allKits = tableDataKits;

        // ключі, які завжди ігноруємо
        const skipKeys = ['canEdit', 'uuid', 'type_name'];
        const skipKeysKits = [
            'canEdit',
            'uniqueid',
            'boundindex',
            'visibleindex',
            'uid',
            'position',
            'local_id',
            'name',
            'package_name',
            'id',
            'isEdit',
        ];

        // додаємо пакети
        allPackets.forEach((item, index) => {
            for (const [key, value] of Object.entries(item)) {
                if (!skipKeys.includes(key)) {
                    formData.append(`packages[${index}][${key}]`, value);
                }
            }
        });

        // додаємо кітси
        allKits.forEach((item, index) => {
            // якщо є UUID — додаємо як goods_id
            if (item.id && isNaN(item.id)) {
                formData.append(`goods[${index}][goods_id]`, item.id);
            }

            for (const [key, value] of Object.entries(item)) {
                if (!skipKeysKits.includes(key)) {
                    formData.append(`goods[${index}][${key}]`, value);
                }
            }
        });

        return formData;
    }

    function getDataEditSku() {
        let formData = getFormData();
        formData.append('_method', 'PUT');

        let allPackets = tableData;
        let allKits = tableDataKits;

        // ключі, які завжди ігноруємо
        const skipKeys = ['canEdit', 'type_name'];
        const skipKeysKits = [
            'canEdit',
            'uniqueid',
            'boundindex',
            'visibleindex',
            'uid',
            'position',
            'local_id',
            'name',
            'package_name',
            'id',
            'isEdit',
        ];

        // пакети
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

        // кітси
        allKits.forEach((item, index) => {
            // якщо є id з бекенду (UUID) — додаємо його явно
            if (item.id && isNaN(item.id)) {
                formData.append(`goods[${index}][goods_id]`, item.id);
            }

            for (const [key, value] of Object.entries(item)) {
                if (!skipKeysKits.includes(key)) {
                    formData.append(`goods[${index}][${key}]`, value);
                }
            }
        });

        return formData;
    }

    async function validateSku(response) {
        const fieldGroups = [
            {
                fields: ['name', 'barcodes', 'category_id', 'avatar'],
                container: '#basic-data-message',
            },
            {
                fields: ['goods'],
                container: '#kits-message',
                includePrefix: 'goods',
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
        ];

        await validateGeneric(response, fieldGroups, '#basic-data-message');
    }

    $('#save').on('click', async function () {
        await sendRequestBase(`${url}sku/kits`, getData(), validateSku, function () {
            redirectWithLocale('/sku');
        });
    });

    $('#edit').on('click', async function (e) {
        const id = e.target.dataset.id;

        await sendRequestBase(`${url}sku/kits/${id}`, getDataEditSku(), validateSku, function () {
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
        },
    });
});
