import { selectDictionaries } from '../selectDictionaries.js';

// Дизейбл select
function disableSelect(selectEl) {
    if (selectEl) {
        $(selectEl).empty().append('<option value=""></option>').trigger('change');
        selectEl.disabled = true;
    }
}

// Активуємо select
function enableSelect(selectEl) {
    if (selectEl) selectEl.disabled = false;
}

// Блокуємо всі залежні select
function disableAllDependents(parentId) {
    const dependents = document.querySelectorAll(`select[data-dependent="${parentId}"]`);
    dependents.forEach((child) => {
        disableSelect(child);
        disableAllDependents(child.id);
    });
}

// Підтягуємо дані для select
async function populateSelect(childEl, selectedValue) {
    if (!childEl) return;

    enableSelect(childEl);

    // 🔹 Якщо вибрана опція "all" — не підвантажуємо дані
    if (selectedValue === 'all') {
        const customOption = childEl.querySelector('option[value="all"]');
        $(childEl).empty();
        if (customOption) $(childEl).append(customOption);
        $(childEl).val('all').trigger('change');
        return; // виходимо без AJAX
    }

    // Очищаємо перед підтяганням
    $(childEl).empty();

    const baseDictionary = childEl.getAttribute('data-dictionary-base');
    const dependentParam = childEl.getAttribute('data-dependent-param');
    const textField = childEl.getAttribute('data-dictionary-textfield') ?? 'name';
    let dictionaryUrl = baseDictionary;

    if (dependentParam && selectedValue) {
        // 🔹 Якщо dependentParam вже містить шлях (починається зі "/")
        if (dependentParam.startsWith('/')) {
            // Якщо закінчується '=', просто додаємо значення
            const paramUrl = dependentParam.endsWith('=')
                ? `${dependentParam}${selectedValue}`
                : `${dependentParam}=${selectedValue}`;

            // 🔹 Правильне об'єднання з baseDictionary
            dictionaryUrl = `${baseDictionary}${paramUrl}`;
        } else {
            // стандартний варіант
            dictionaryUrl += `?${dependentParam}=${selectedValue}`;
        }
    }

    childEl.setAttribute('data-dictionary', dictionaryUrl);

    await selectDictionaries(childEl.id, textField);

    const relatedIdAttr = childEl.getAttribute('data-related-id');
    const isEdit = childEl.dataset.edit === 'true';
    let hasRelated = false;
    let relatedIds = [];

    if (relatedIdAttr) {
        try {
            relatedIds = JSON.parse(relatedIdAttr);
            if (Array.isArray(relatedIds) && relatedIds.length > 0) {
                hasRelated = true;
            }
        } catch {
            if (relatedIdAttr.trim() !== '') {
                relatedIds = [relatedIdAttr];
                hasRelated = true;
            }
        }
    }

    if (hasRelated) {
        $(childEl).val(relatedIds).trigger('change');
    } else if (isEdit && childEl.dataset.customOptions) {
        const customOptions = JSON.parse(childEl.dataset.customOptions);
        const allOption = customOptions.find((opt) => opt.id === 'all');
        // if (allOption) {
        //     const optionEl = document.createElement('option');
        //     optionEl.value = allOption.id;
        //     optionEl.text = allOption.text;
        //     optionEl.selected = true;
        //     childEl.appendChild(optionEl);
        //     $(childEl).val(allOption.id).trigger('change');
        // }
    } else {
        // Якщо немає ні relatedId, ні кастомної опції "all" або edit=false — пусто
        $(childEl).val('').trigger('change');
    }
}

// Рекурсивне підтягання при редагуванні
async function populateRecursive(childEl) {
    if (!childEl) return;
    const parentId = childEl.getAttribute('data-dependent');
    const parentEl = document.getElementById(parentId);
    const parentValue = parentEl?.value || childEl.getAttribute('data-parent-id');
    await populateSelect(childEl, parentValue);

    const childDependents = document.querySelectorAll(`select[data-dependent="${childEl.id}"]`);
    for (const dep of childDependents) {
        await populateRecursive(dep);
    }
}

export function initDependentDictionaries() {
    const dependentSelects = document.querySelectorAll('select[data-dependent]');
    const handledParents = new Set();

    dependentSelects.forEach((childEl) => {
        const parentId = childEl.getAttribute('data-dependent');
        const parentEl = parentId ? document.getElementById(parentId) : null;

        const relatedId = childEl.getAttribute('data-related-id');

        // Створення → блокуємо якщо немає relatedId
        if (!relatedId) disableSelect(childEl);

        // Обробка зміни батька — реєструємо лише один раз на батька
        if (parentEl && !handledParents.has(parentId)) {
            handledParents.add(parentId);
            const handler = async function (e) {
                const selectedValue = e?.params?.data?.id ?? this.value;
                disableAllDependents(parentId);

                // 🔹 Якщо обрано "all" — пропускаємо підвантаження дочірніх
                if (!selectedValue || selectedValue === 'all') return;

                const childDependents = document.querySelectorAll(
                    `select[data-dependent="${parentEl.id}"]`
                );
                for (const dep of childDependents) {
                    await populateSelect(dep, selectedValue);
                }
            };
            $(parentEl).on('select2:select', handler);
        }

        // Редагування → рекурсивно підтягуємо relatedId
        if (relatedId) {
            populateRecursive(childEl);
        }
    });
}

// Ініціалізація
initDependentDictionaries();
