import { getLocalizedText } from '../../localization/components/getLocalizedText.js';
import { selectDictionaries } from '../../utils/selectDictionaries.js';

export function toolbarRender(
    statusbar,
    table,
    simple,
    searchColumnStart,
    searchColumnEnd,
    idSettingEl = '',
    menuData = [] // ⬅️ тепер приходить з параметрів
) {
    // --- Створення DOM-структури тулбару ---
    const container = createToolbarContainer(idSettingEl, menuData);
    statusbar.append(container);

    if (menuData && menuData.length > 0) {
        // Рендеримо меню і вставляємо у поповер
        const popoverHtml = renderCustomPopoverContent(menuData);
        $('#filterCustomPopoverContent').html(popoverHtml);

        // ініціалізація jqxPopover
        $('#filterCustomPopoverContent').jqxPopover({
            selector: '#filterCustomPopoverBtn',
            position: 'bottom',
            showCloseButton: false,
            width: 250,
            theme: 'light-wms',
            offset: { left: 55, top: 1 },
        });

        let isPopoverOpen = false;

        $('#filterCustomPopoverBtn').on('click', function (e) {
            e.preventDefault();
            if (!isPopoverOpen) {
                $('#filterCustomPopoverContent').jqxPopover('open');
            } else {
                $('#filterCustomPopoverContent').jqxPopover('close');
            }
            isPopoverOpen = !isPopoverOpen;
        });

        // Обробники кліків для пунктів з підменю
        $('#filterCustomPopoverContent').on(
            'click',
            '#mainMenu li[data-has-submenu="true"]',
            function () {
                const submenuId = $(this).data('submenu');
                $('#mainMenu').hide();
                $('#' + submenuId).show();
            }
        );

        // Обробники кнопок назад (динамічний делегований)
        $('#filterCustomPopoverContent').on('click', '.backBtn', function () {
            const submenuId = $(this).data('submenu-id');
            $('#' + submenuId).hide();
            $('#mainMenu').show();
        });

        $('#filterCustomPopoverContent').on('change', '.form-check-input', function () {
            const checkbox = $(this);
            const isChecked = checkbox.is(':checked');

            const filterId = checkbox.attr('id');
            const dictionary = checkbox.val(); // ⬅️ тут словник
            const filterLabel = checkbox.siblings('label').text().trim();

            if (isChecked) {
                // Якщо селект із таким фільтрId ще не існує
                if ($(`#filters-container select[data-filter-id="${filterId}"]`).length === 0) {
                    const selectEl = createFilterSelect(filterId, filterLabel, dictionary);

                    $('#filters-container').prepend(selectEl);
                    selectEl.select2({});
                    selectDictionaries();
                }
            } else {
                // Видаляємо select із цим data-filter-id
                $(`#filters-container select[data-filter-id="${filterId}"]`)
                    .select2('destroy')
                    .remove();
            }
            updateScrollButtonsVisibility();
        });
    }

    // --- Прив’язка подій для скролу ---
    $('#scroll-left-btn').on('click', () => scrollFilters(-300));
    $('#scroll-right-btn').on('click', () => scrollFilters(300));
    $('#filters-scroll-wrapper').on('scroll', updateScrollButtonsVisibility);

    // --- Кеш елементів ---
    const searchInput = container.find('#search-input');
    const searchButton = container.find('#search-btn');
    const exportExcelButton = container.find('#export-excel-btn');

    // --- Ініціалізація кнопок (jqxButton) ---
    container.find('#setting-btn').jqxButton();
    exportExcelButton.jqxButton();

    // --- Прив'язка подій ---
    bindSearchHandlers(searchInput, searchButton, table, searchColumnStart, searchColumnEnd);
    bindExportExcelButton(exportExcelButton, table);

    // Оновлюємо стан кнопок прокрутки
    updateScrollButtonsVisibility();
}

// --- Функції створення DOM ---
function createToolbarContainer(idSettingEl, menuData) {
    return $(`
        <div class="row mx-0 bg-white px-1" style="height: 100%;">
            <div id="toolbar-filters" class="d-flex col-3 col-sm-3 col-md-6 col-lg-9 align-items-center gap-1 px-0">
                <button id="scroll-left-btn" class="btn btn-sm border-0 p-50" style="display:none;">
                    <i data-feather="chevron-left" style="width: 16px; height: 16px;"></i>
                </button>

                <div id="filters-scroll-wrapper" class="d-flex align-items-center w-100 gap-1" style="overflow-x: auto; overflow-y: hidden; white-space: nowrap; vertical-align: middle;">

                <div class="custom-dropdown-wrapper">
                        ${
                            menuData && menuData.length > 0
                                ? `
                        <button id="filterCustomPopoverBtn" type="button" class="btn btn-sm" title="Додати фільтр">
                            <i data-feather="plus"></i> Додати фільтр
                        </button>
                         `
                                : ''
                        }

                        <div id="filterCustomPopoverContent" style="display:none; width: 250px; border: 1px solid #ccc; background: white; padding: 5px;">

                        </div>
                    </div>

                    <div id="filters-container" class="d-flex gap-1">
                        <!-- Тут будуть динамічні селекти фільтрів -->
                    </div>
                </div>

                <button id="scroll-right-btn" class="btn btn-sm border-0 p-50" style="display:none;">
                    <i data-feather="chevron-right" style="width: 16px; height: 16px;"></i>
                </button>
            </div>

            <div id="toolbar-actions" class="d-flex col-9 col-sm-9 col-md-6 col-lg-3 flex-row align-items-center gap-1">
                <div class="vr mx-0 my-25 bg-secondary-subtle"></div>

                <div class="input-group input-group-merge d-flex flex-col">
                    <input type="text" id="search-input" class="form-control ps-1" placeholder="${getLocalizedText('search')}" />
                    <button id="search-btn" class="input-group-text search-btn" title="${getLocalizedText('search')}">
                        <i data-feather="search"></i>
                    </button>
                </div>

                <div id="setting-btn"
                     class="btn p-0"
                     data-bs-toggle="offcanvas"
                     data-bs-target="#settingTable${idSettingEl}"
                     aria-controls="settingTable"
                     title="Налаштування"
                >
                    <img class="icon-setting" src="${window.location.origin}/assets/icons/table/settings.svg"/>
                </div>

                <div id="export-excel-btn" class="btn p-0" title="Експорт Excel">
                    <img src="${window.location.origin}/assets/icons/table/downloads.svg" alt="Excel" style="height: 20px;"/>
                </div>
            </div>
        </div>
    `);
}

// --- Обробники пошуку ---
function bindSearchHandlers(searchInput, searchButton, table, startCol, endCol) {
    let timeoutId;

    function performSearch() {
        const val = searchInput.val().trim();
        if (!val) {
            table.jqxGrid('refreshdata');
            return;
        }

        const columns = table.jqxGrid('columns').records.slice(startCol, endCol);
        columns.forEach((col) => {
            const filterGroup = new $.jqx.filter();
            const filter = filterGroup.createfilter('stringfilter', val, 'contains');
            filterGroup.addfilter(1, filter);
            table.jqxGrid('addfilter', col.datafield, filterGroup, false);
        });
        table.jqxGrid('applyfilters');

        // Прибираємо фільтри після застосування, щоб не заважали подальшим пошукам
        table.one('bindingcomplete', () => {
            columns.forEach((col) => table.jqxGrid('removefilter', col.datafield, false));
        });
    }

    function scheduleSearch() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(performSearch, 500);
    }

    searchInput.on('input', scheduleSearch);
    searchButton.click(performSearch);
}

// --- Обробник експорту Excel ---
function bindExportExcelButton(button, table) {
    button.click(() => {
        if (typeof window.exportTableToExcel === 'function') {
            window.exportTableToExcel(table);
        } else {
            alert(getLocalizedText('export_function_not_found') || 'Export function not found');
        }
    });
}

// --- Функція експорту таблиці в Excel ---
window.exportTableToExcel = function (table) {
    if (!table || !table.jqxGrid) {
        alert('Invalid table object');
        return;
    }

    const rows = table.jqxGrid('getrows');
    if (rows.length === 0) {
        alert('No data to export');
        return;
    }

    const columns = table.jqxGrid('columns').records;

    // Пропускаємо непотрібні колонки
    const filteredColumns = columns.filter(
        (col) => !['action', '_checkboxcolumn'].includes(col.datafield)
    );

    const headers = filteredColumns.map((col) => col.text || col.datafield);
    const language = typeof switchLang === 'function' ? switchLang() : 'en';

    const data = [headers];

    rows.forEach((row) => {
        const rowData = filteredColumns.map((col) => {
            if (col.datafield === 'name_and_code') {
                return `${row.name || ''} / ${row.code_format || ''}`;
            }
            if (col.datafield === 'status') {
                const statusObj = row.status || {};
                return language === 'en' ? statusObj.label_en || '' : statusObj.label || '';
            }
            let val = row[col.datafield];
            if (val && typeof val === 'object') val = JSON.stringify(val);
            return val ?? '';
        });
        data.push(rowData);
    });

    const worksheet = XLSX.utils.aoa_to_sheet(data);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Data');
    XLSX.writeFile(workbook, 'table_export.xlsx');
};

// Прокрутка фільтрів на певну кількість пікселів
function scrollFilters(amount) {
    const wrapper = $('#filters-scroll-wrapper')[0];
    if (!wrapper) return;
    wrapper.scrollBy({ left: amount, behavior: 'smooth' });
}

// Оновлення видимості і стану кнопок скролу
function updateScrollButtonsVisibility() {
    const wrapper = $('#filters-scroll-wrapper')[0];
    if (!wrapper) return;

    const scrollLeftBtn = $('#scroll-left-btn');
    const scrollRightBtn = $('#scroll-right-btn');

    if (wrapper.scrollWidth > wrapper.clientWidth) {
        scrollLeftBtn.show();
        scrollRightBtn.show();
    } else {
        scrollLeftBtn.hide();
        scrollRightBtn.hide();
    }

    if (wrapper.scrollLeft <= 0) {
        scrollLeftBtn.prop('disabled', true);
    } else {
        scrollLeftBtn.prop('disabled', false);
    }

    if (wrapper.scrollLeft + wrapper.clientWidth >= wrapper.scrollWidth - 1) {
        scrollRightBtn.prop('disabled', true);
    } else {
        scrollRightBtn.prop('disabled', false);
    }
}

function createFilterSelect(id, label, dictionary, options = []) {
    const select = $(`
        <select class="select-2 filter-select"
                data-filter-id="${id}"
                data-dictionary="${dictionary}">
                <option>${label}</option>
        </select>
    `);

    return select;
}

// Функція рендеру меню з підменю
function renderCustomPopoverContent(menuItems) {
    let mainMenuHtml = '<ul id="mainMenu" class="p-0 m-0 list-unstyled">';
    let submenusHtml = '';

    menuItems.forEach((item, index) => {
        // Якщо формат зі старим label + submenu
        if (item.label && Array.isArray(item.submenu)) {
            const submenuId = `submenu${index + 1}`;
            mainMenuHtml += `
                <li data-has-submenu="true" data-submenu="${submenuId}"
                    class="d-inline-flex justify-content-between w-100 align-items-center p-1 cursor-pointer">
                    ${item.label}
                    <i data-feather="chevron-right"></i>
                </li>`;

            submenusHtml += `<ul id="${submenuId}" class="p-0 m-0 list-unstyled" style="display:none;">`;
            submenusHtml += `
                <li class="px-0 d-flex justify-content-end">
                    <button class="backBtn btn btn-flat-secondary p-25"
                            data-parent-menu="mainMenu"
                            data-submenu-id="${submenuId}">
                        <i data-feather="x"></i>
                    </button>
                </li>`;

            item.submenu.forEach((subitem, subIndex) => {
                const checkboxId = `submenu-${index + 1}-item-${subIndex + 1}`;
                submenusHtml += `
                    <li class="form-check p-1 d-flex align-items-center gap-25">
                        <input class="form-check-input mx-0"
                               type="checkbox"
                               value="${subitem.value || ''}"
                               id="${checkboxId}">
                        <label class="form-check-label" for="${checkboxId}">
                            ${subitem.label}
                        </label>
                    </li>`;
            });

            submenusHtml += `</ul>`;
        }
        // Якщо новий формат з option
        else if (Array.isArray(item.option)) {
            item.option.forEach((opt, optIndex) => {
                const checkboxId = `option-${index + 1}-item-${optIndex + 1}`;
                mainMenuHtml += `
                    <li class="form-check p-1 d-flex align-items-center gap-25">
                        <input class="form-check-input mx-0"
                               type="checkbox"
                               value="${opt.value || ''}"
                               id="${checkboxId}">
                        <label class="form-check-label" for="${checkboxId}">
                            ${opt.label}
                        </label>
                    </li>`;
            });
        }
    });

    mainMenuHtml += '</ul>';
    return `${mainMenuHtml}${submenusHtml}`;
}
