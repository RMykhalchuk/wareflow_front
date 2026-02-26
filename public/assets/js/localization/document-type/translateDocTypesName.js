export function translateDocTypesName(selectorText) {
    const selectedLanguage = localStorage.getItem('Language');

    const translations = {
        'Товарна накладна': 'Invoices',
        'Прихід від постачальника': 'Supplier Arrival',
        'Внутрішнє переміщення': 'Internal Transfer',
        'Запит на транспорт': 'Request for transportation',
        'Запит на вантаж': 'Cargo Request',
        'Заявки на прийом': 'Applications for cargo',
        'Заявка на відвантаження': 'Shipping Request',
        ТТН: 'E-Waybill',
        Замовлення: 'Order',
        Списання: 'Debiting',

        // a new type
        Прихід: 'Coming',
        Розхід: 'Discharge',
        Нейтральний: 'Neutral',
    };

    $(selectorText).each(function () {
        const currentText = $(this).text().trim(); // <-- Ось тут trim!
        const isQuoted = currentText.startsWith('"') && currentText.endsWith('"');

        let textToTranslate = currentText;
        if (isQuoted) {
            textToTranslate = currentText.slice(1, -1).trim();
        }

        const translatedText =
            selectedLanguage === 'uk'
                ? textToTranslate
                : translations[textToTranslate] || textToTranslate;

        $(this).text(isQuoted ? `"${translatedText}"` : translatedText);
    });
}
