import { translateDictionariesOption } from '../localization/utils/translateDictionariesOption.js';
import { getCurrentLocaleFromUrl } from './getCurrentLocaleFromUrl.js';

/**
 * @param {string} dictionary - id select або undefined для всіх select з data-dictionary
 * @param {string} textField - поле з об'єкта для тексту опцій (за замовчуванням "name")
 */

const locale = getCurrentLocaleFromUrl();

// Формування бази URL
const base =
    locale === 'en'
        ? window.location.origin // без префіксу
        : `${window.location.origin}/${locale}`; // з префіксом

export function selectDictionaries(dictionary, textField = 'name') {
    let selectElements;

    // Якщо передано значення словника, шукаємо всі select елементи з id, що дорівнює словнику
    if (dictionary) {
        selectElements = document.querySelectorAll("select[id='" + dictionary + "']");
    } else {
        // В іншому випадку шукаємо всі select елементи з атрибутом data-dictionary
        selectElements = document.querySelectorAll('[data-dictionary]');
    }

    // Якщо не знайдено жодного select елемента, припиняємо виконання
    if (selectElements.length === 0) {
        return;
    }

    try {
        const promises = Array.from(selectElements).map(async (selectElement) => {
            const dictionaryUrl = selectElement.getAttribute('data-dictionary');
            const parameterUrl =
                dictionaryUrl === 'street' || dictionaryUrl === 'settlement'
                    ? '/address/'
                    : '/dictionary/';

            const selectedId = selectElement.getAttribute('data-id');
            const isEdit = selectElement.dataset.edit === 'true';

            // Якщо не задано URL словника, припиняємо виконання для цього елемента
            if (!dictionaryUrl) {
                return;
            }

            const emptyOption = selectElement.querySelector('option[value=""]');
            const url = base + parameterUrl + dictionaryUrl;

            // Масив можливих контейнерів для dropdown
            const modalParents = [
                '.js-modal-form',
                '.js-modal-form2',
                '.js-modal-form3',
                '.js-modal-form4',
            ];

            // Знаходимо перший батьківський елемент, що підходить
            let dropdownParentElement;

            for (const parent of modalParents) {
                const found = selectElement.closest(parent);
                if (found) {
                    dropdownParentElement = $(parent);
                    break;
                }
            }

            const select2Settings = {
                dropdownParent: dropdownParentElement,
                placeholder: 'Пошук...',
            };

            select2Settings.ajax = {
                url,
                dataType: 'json',
                delay: 0,
                data: (params) => {
                    const data = { query: params.term ?? '' };

                    // додаємо warehouse_id для клітинок
                    if (dictionaryUrl === 'cells_by_warehouse_and_receiving_type') {
                        const container = document.getElementById('document-container');
                        const warehouseId =
                            container?.dataset?.warehouseId ||
                            localStorage.getItem('global_warehouse_id');
                        if (warehouseId) {
                            data.warehouse_id = warehouseId;
                        }
                    }

                    return data;
                },
                processResults: (data, params) => {
                    params.page = 1;

                    const results = data.data.map((item) => {
                        const result = {
                            id: item.id,
                            text: translateDictionariesOption(
                                item[textField] ?? item.name ?? item.code ?? item.id
                            ),
                        };

                        // Якщо у select є data-full-object="true", додаємо весь об’єкт
                        if (selectElement.dataset.fullObject === 'true') {
                            result.full = item;
                        }

                        return result;
                    });

                    // Додаємо кастомні опції з атрибуту data-custom-options
                    if (selectElement.dataset.customOptions) {
                        const customOptions = JSON.parse(selectElement.dataset.customOptions);
                        // Вставляємо на початок, не дублюючи
                        customOptions.forEach((opt) => {
                            if (!results.some((r) => r.id === opt.id)) results.unshift(opt);
                        });
                    }

                    return { results };
                },
                cache: true,
            };

            // Функція для отримання даних із сервера
            const fetchData = async () => {
                const response = await fetch(url);
                const data = await response.json();
                return data;
            };

            // Дізейблимо select поки завантажуються дані
            selectElement.disabled = true;

            let data;
            try {
                // Отримання даних та оновлення select елемента
                data = await fetchData();
            } finally {
                selectElement.disabled = false;
            }

            $(selectElement).empty();
            if (emptyOption) {
                selectElement.appendChild(emptyOption);
            }

            data.data.forEach((item) => {
                const option = document.createElement('option');
                option.value = item.id;
                option.text = translateDictionariesOption(
                    item[textField] ?? item.name ?? item.code ?? item.id
                );
                selectElement.appendChild(option);
            });

            // Ініціалізація select2 з налаштуваннями
            $(selectElement).select2(select2Settings);

            // Якщо задано selectedId, обробляємо його відповідно до типу даних
            if (selectedId) {
                if (isNumeric(selectedId) || isUUID(selectedId)) {
                    // Якщо це число або UUID — отримуємо ім’я напряму
                    const name = await fetchNameOption(url, selectedId, textField);
                    const nameTranslate = translateDictionariesOption(name);
                    $(selectElement).html(
                        `<option value="${selectedId}" selected>${nameTranslate}</option>`
                    );
                    $(selectElement).trigger('change');
                } else {
                    try {
                        let parsedIds;

                        // Кейс: [uuid] без лапок
                        if (/^\[[0-9a-f-]+\]$/i.test(selectedId)) {
                            const cleanId = selectedId.replace('[', '').replace(']', '');
                            parsedIds = [cleanId];
                        } else {
                            // Нормальний JSON
                            parsedIds = JSON.parse(selectedId);
                        }

                        $(selectElement).val(parsedIds).trigger('change');
                    } catch (e) {
                        console.warn('Невірний формат selectedId:', selectedId, e);
                    }
                }
            } else if (isEdit && selectElement.dataset.customOptions) {
                const customOptions = JSON.parse(selectElement.dataset.customOptions);
                const allOption = customOptions.find((opt) => opt.id === 'all');
                if (allOption) {
                    const optionEl = document.createElement('option');
                    optionEl.value = allOption.id;
                    optionEl.text = allOption.text;
                    optionEl.selected = true;
                    selectElement.appendChild(optionEl);
                    $(selectElement).val(allOption.id).trigger('change');
                }
            }
        });

        return Promise.all(promises);
    } catch (error) {
        console.error('Помилка при отриманні даних: ', error);
    }
}

// Перевірка чи значення є числом
function isNumeric(str) {
    return typeof str === 'string' && /^\d+$/.test(str);
}

// Перевірка чи значення є UUID
function isUUID(str) {
    return (
        typeof str === 'string' &&
        /^[0-9a-f]{8}-[0-9a-f]{4}-[1-7][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i.test(str)
    );
}

// Функція для отримання імені опції за її ID або UUID
async function fetchNameOption(url, id, textField) {
    try {
        const response = await fetch(`${url}?id=${encodeURIComponent(id)}`);
        const json = await response.json();

        // Підтримує три варіанти відповіді:
        // 1. { data: { id, name } }
        // 2. { data: [ { id, name } ] }
        // 3. [ { id, name } ]  ← якщо сервер повертає просто масив без "data"
        let item = json.data ?? json;

        if (Array.isArray(item)) {
            item = item[0]; // беремо перший елемент
        }

        return item?.[textField] ?? item?.name ?? item?.code ?? id;
    } catch (error) {
        console.error('Помилка при отриманні даних: ', error);
        return id;
    }
}
