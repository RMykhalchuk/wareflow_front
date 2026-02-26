export function bindSelectToUnitText(selectId, targetInputId) {
    const select = $('#' + selectId);
    const unitSpan = document
        .querySelector('#' + targetInputId)
        ?.closest('.input-group')
        ?.querySelector('.input-group-text');

    if (!select.length || !unitSpan) return;

    function updateUnit() {
        const selected = select.select2('data')[0];
        const unitText = selected?.text?.trim() || '';
        unitSpan.textContent = unitText;
    }

    // викликати при старті
    updateUnit();

    // оновлювати при зміні
    select.on('change', updateUnit);
}
