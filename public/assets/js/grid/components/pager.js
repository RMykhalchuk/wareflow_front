import { getLocalizedText } from '../../localization/components/pager/getLocalizedText.js';

/* =========================
   URL helpers
========================= */
export function getQueryParams() {
    const params = new URLSearchParams(window.location.search);

    return {
        page: parseInt(params.get('page'), 10) || 1,
        pageSize: parseInt(params.get('pageSize'), 10) || 10,
    };
}

export function setQueryParams({ page, pageSize }) {
    const params = new URLSearchParams(window.location.search);

    if (page) params.set('page', page);
    if (pageSize) params.set('pageSize', pageSize);

    window.history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);
}

const ALLOWED_PAGE_SIZES = [5, 10, 20, 30, 50];

const normalizePageSize = (value) => {
    if (ALLOWED_PAGE_SIZES.includes(value)) return value;

    // шукаємо найближчий
    return ALLOWED_PAGE_SIZES.reduce((prev, curr) =>
        Math.abs(curr - value) < Math.abs(prev - value) ? curr : prev
    );
};

/* =========================
   Pager renderer
========================= */
export function pagerRenderer(table) {
    const query = getQueryParams();
    let pageSize = normalizePageSize(query.pageSize);
    let initialPage = query.page - 1;

    const $pager = $("<div class='custom-pager'></div>");
    const $pageInfo = $("<div class='custom-pager-page-info col-auto'></div>");

    const $pageSizeWrapper = $(`
        <div class='custom-pager-select-size'>
            <div class='custom-pager-select-text col-auto'>
                ${getLocalizedText('lines_per_page')}:
            </div>
            <div class='col-4'>
                <select class='custom-pager-page-size'>
                    <option value='5'>5</option>
                    <option value='10'>10</option>
                    <option value='20'>20</option>
                    <option value='30'>30</option>
                    <option value='50'>50</option>
                </select>
            </div>
        </div>
    `);

    const $select = $pageSizeWrapper.find('.custom-pager-page-size');
    $select.val(pageSize); // встановлюємо значення з URL

    $select.on('change', () => {
        pageSize = parseInt($select.val(), 10);
        setQueryParams({ page: 1, pageSize });

        // Обробник bindingcomplete для оновлення таблиці
        const handleBindingComplete = () => {
            table.jqxGrid('pagesize', pageSize);
            table.jqxGrid('gotopage', 0);
            $pageInfo.html(getPageInfo());
            table.off('bindingcomplete', handleBindingComplete); // видаляємо, щоб не викликалося повторно
        };

        table.on('bindingcomplete', handleBindingComplete);

        // Якщо таблиця вже закінчила завантаження, тригеримо вручну
        if (table.jqxGrid('getdatainformation').rowscount !== undefined) {
            handleBindingComplete();
        }
    });

    // Створюємо кнопки навігації
    const createBtn = (className, iconPath, alt) =>
        $(`
        <button style="width:28px;height:28px;" class="border-radius-15 p-0 btn ${className}">
            <img src="${window.location.origin}${iconPath}" alt="${alt}">
        </button>
    `);

    const $prevBtn = createBtn(
        'custom-pager-prev-btn',
        '/assets/icons/table/chevron-left-pager.svg',
        'Previous'
    );
    const $nextBtn = createBtn(
        'custom-pager-next-btn',
        '/assets/icons/table/chevron-right-pager.svg',
        'Next'
    );

    const getPageInfo = () => {
        const dataInfo = table.jqxGrid('getdatainformation');
        const totalRows = dataInfo.rowscount;
        const currentPage = dataInfo.paginginformation.pagenum + 1;
        const startRow = totalRows === 0 ? 0 : (currentPage - 1) * pageSize + 1;
        const endRow = Math.min(startRow + pageSize - 1, totalRows);
        return `${startRow} - ${endRow} ${getLocalizedText('from')} ${totalRows}`;
    };

    const updatePage = (page) => {
        table.jqxGrid('gotopage', page);
        setQueryParams({ page: page + 1, pageSize });
        $pageInfo.html(getPageInfo());
    };

    $prevBtn.click(() => {
        const page = table.jqxGrid('getpaginginformation').pagenum;
        if (page > 0) updatePage(page - 1);
    });

    $nextBtn.click(() => {
        const info = table.jqxGrid('getdatainformation');
        const page = table.jqxGrid('getpaginginformation').pagenum;
        const totalPages = Math.ceil(info.rowscount / pageSize);
        if (page < totalPages - 1) updatePage(page + 1);
    });

    // Контейнер для кнопок сторінок
    const $pageNumbers = $("<div class='custom-pager-page-numbers d-flex gap-50'></div>");

    const renderPageButtons = () => {
        $pageNumbers.empty();
        const info = table.jqxGrid('getdatainformation');
        const totalPages = Math.ceil(info.rowscount / pageSize);
        const currentPage = info.paginginformation.pagenum + 1;
        const maxButtons = window.innerWidth < 992 ? 3 : 5;

        let startPage = Math.max(currentPage - Math.floor(maxButtons / 2), 1);
        startPage = Math.min(startPage, Math.max(totalPages - maxButtons + 1, 1));

        for (let i = startPage; i <= Math.min(startPage + maxButtons - 1, totalPages); i++) {
            const $btn = $(
                `<button style='width:28px;height:28px;' class='btn p-0 custom-pager-page-btn text-truncate'>${i}</button>`
            );
            if (i === currentPage) $btn.addClass('custom-pager-page-btn-active');
            $btn.click(() => updatePage(i - 1));
            $pageNumbers.append($btn);
        }
    };

    const $center = $("<div class='custom-pager-center d-flex gap-50 align-items-center'></div>");
    $center.append($pageSizeWrapper, $pageInfo, $prevBtn, $pageNumbers, $nextBtn);
    $pager.append($center);

    // Флаг для уникального bindingcomplete
    let isBindingCompleteHandled = false;
    table.on('bindingcomplete', () => {
        if (!isBindingCompleteHandled) {
            table.jqxGrid('pagesize', pageSize);
            const info = table.jqxGrid('getdatainformation');
            const totalPages = Math.ceil(info.rowscount / pageSize);
            const safePage = Math.min(Math.max(initialPage, 0), totalPages - 1);

            table.jqxGrid('gotopage', safePage);

            // синхронізуємо URL
            setQueryParams({
                page: safePage + 1,
                pageSize,
            });

            initialPage = -1;

            $pageInfo.html(getPageInfo());
            renderPageButtons();

            isBindingCompleteHandled = true;
        }
    });

    return $pager;
}
