export function translateDocTypesFieldsName(fieldsName) {
    const selectedLanguage = localStorage.getItem('Language');

    const translations = {
        'Текстове поле': 'Text field',
        'Введіть текст': 'Enter text',
        'Поле вибору': 'Selection field',
        'Виберіть значення': 'Select value',
        'Поле етикетка': 'Label field',
        'Виберіть одне або декілька значень': 'Select one or multiple values',
        'Поле дати': 'Date field',
        'Виберіть дату': 'Select date',
        Діапазон: 'Range',
        'Задайте діапазон': 'Set range',
        'Період дат': 'Date range',
        'Вкажіть період': 'Specify period',
        'Дата і час': 'Date and time',
        'Вкажіть дату і час': 'Specify date and time',
        'Дата і часові рамки': 'Date and time frames',
        'Часові рамки': 'Time frames',
        'Вкажіть період часу': 'Specify time period',
        'Вмикач / вимикач значення': 'Toggle value',
        'Обрати файл': 'Choose file',
        'Виберіть файл': 'Select file',
        Коментар: 'Comment',
        'Введіть коментар': 'Enter comment',
        'Простий текст': 'Plain text',
        Параграф: 'Paragraph',
        Дата: 'Date',
        Селект: 'Select',
    };

    return selectedLanguage === 'uk' ? fieldsName : translations[fieldsName] || fieldsName;
}
