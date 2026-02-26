export function translateSystemTypeGoodsName(selectorText) {
    const selectedLanguage = localStorage.getItem('Language');

    const translations = {
        'Продукти харчування, б/а напої без дотримання температурного режиму':
            'Food products, non-alcoholic beverages without temperature control',
        'Продукти харчування з дотриманням температурного режиму':
            'Food products with temperature control',
        'Побутові та господарські товари': 'Household and domestic goods',
        'Продукція видобувної промисловості': 'Mining industry products',
        'Текстильні товари': 'Textile goods',
        'Будівельні матеріали, інструменти, сировина для будівництва, сантехніка':
            'Building materials, tools, raw materials for construction, plumbing',
        'Поліграфічна продукція': 'Printing products',
        'Спортивне приладдя та аксесуари для відпочинку':
            'Sports equipment and leisure accessories',
        'Нафтопродукти ADR': 'Petroleum products ADR',
        'Матеріали штучного походження(синтетичні, гумові, пластмасові)':
            'Artificial materials (synthetic, rubber, plastic)',
        'Вироби зі скла, фарфору, кераміки та інший крихкий вантаж':
            'Glass, porcelain, ceramics and other fragile cargo',
        'Електротехніка, деталі до електричних приладів, аксесуари':
            'Electrical appliances, parts for electrical appliances, accessories',
        Меблі: 'Furniture',
        'Природна сировина': 'Natural raw materials',
        'Цінні матеріали': 'Valuable materials',
        'Інші види вантажів, не віднесені до попередніх угруповань':
            'Other types of cargo not included in the previous groups',
        Сировина: 'Raw materials',
    };

    $(selectorText).each(function () {
        const currentText = $(this).text().trim(); // <-- Ось тут trim!

        const isQuoted = currentText.startsWith('"') && currentText.endsWith('"');

        if (isQuoted) {
            const trimmedText = currentText.slice(1, -1); // Remove quotes
            const translatedText =
                selectedLanguage === 'uk' ? trimmedText : translations[trimmedText] || trimmedText;
            $(this).text(`"${translatedText}"`);
        } else {
            const newText =
                selectedLanguage === 'uk' ? currentText : translations[currentText] || currentText;
            $(this).text(newText);
        }
    });
}
