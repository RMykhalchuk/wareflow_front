// Вимкнення за межами offcanvas
export function offCanvasByBorder(table, idSettingEl = '') {
    const offcanvasElement = document.getElementById(`settingTable${idSettingEl}`);
    const bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement);

    document.addEventListener('click', function (event) {
        if (
            !event.target.closest('.offcanvas') &&
            !event.target.closest('[data-bs-toggle="offcanvas"]')
        ) {
            bsOffcanvas.hide();
            offcanvasElement.classList.remove('d-flex');
            //table.jqxGrid('refreshdata');
        }
    });
}
