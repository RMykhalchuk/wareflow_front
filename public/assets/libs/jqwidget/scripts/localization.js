let getLocalization = (culture) => {
    let localization = null;
    switch (culture) {
        case 'uk':
            localization =
                {
                    // separator of parts of a date (e.g. '/' in 11/05/1955)
                    '/': '/',
                    // separator of parts of a time (e.g. ':' in 05:44 PM)
                    ':': ':',
                    // the first day of the week (0 = Sunday, 1 = Monday, etc)
                    firstDay: 1,
                    days: {
                        // full day names
                        names: ['Неділя', 'Понеділок', 'Вівторок', 'Середа', 'Четвер', "П'ятниця", 'Субота'],
                        // abbreviated day names
                        namesAbbr: ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Нд'],
                        // shortest day names
                        namesShort: ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Нд']
                    },
                    months: {
                        // full month names (13 months for lunar calendards -- 13th month should be '' if not lunar)
                        names: ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень', ''],
                        // abbreviated month names
                        namesAbbr: ['Січ', 'Лют', 'Бер', 'Кві', 'Тра', 'Чер', 'Лип', 'Сер', 'Вер', 'Жов', 'Лис', 'Гру', '']
                    },
                    // AM and PM designators in one of these forms:
                    // The usual view, and the upper and lower case versions
                    //      [standard,lowercase,uppercase]
                    // The culture does not use AM or PM (likely all standard date formats use 24 hour time)
                    //      null
                    AM: null,
                    PM: null,
                    eras: [
                        // eras in reverse chronological order.
                        // name: the name of the era in this culture (e.g. A.D., C.E.)
                        // start: when the era starts in ticks (gregorian, gmt), null if it is the earliest supported era.
                        // offset: offset in years from gregorian calendar
                        {'name': 'A.D.', 'start': null, 'offset': 0}
                    ],
                    twoDigitYearMax: 2029,
                    patterns: {
                        // short date pattern
                        d: 'M/d/yyyy',
                        // long date pattern
                        D: 'dddd, MMMM dd, yyyy',
                        // short time pattern
                        t: 'h:mm tt',
                        // long time pattern
                        T: 'h:mm:ss tt',
                        // long date, short time pattern
                        f: 'dddd, MMMM dd, yyyy h:mm tt',
                        // long date, long time pattern
                        F: 'dddd, MMMM dd, yyyy h:mm:ss tt',
                        // month/day pattern
                        M: 'MMMM dd',
                        // month/year pattern
                        Y: 'yyyy MMMM',
                        // S is a sortable format that does not vary by culture
                        S: 'yyyy\u0027-\u0027MM\u0027-\u0027dd\u0027T\u0027HH\u0027:\u0027mm\u0027:\u0027ss',
                        // formatting of dates in MySQL DataBases
                        ISO: 'yyyy-MM-dd hh:mm:ss',
                        ISO2: 'yyyy-MM-dd HH:mm:ss',
                        d1: 'dd.MM.yyyy',
                        d2: 'dd-MM-yyyy',
                        d3: 'dd-MMMM-yyyy',
                        d4: 'dd-MM-yy',
                        d5: 'H:mm',
                        d6: 'HH:mm',
                        d7: 'HH:mm tt',
                        d8: 'dd/MMMM/yyyy',
                        d9: 'MMMM-dd',
                        d10: 'MM-dd',
                        d11: 'MM-dd-yyyy'
                    },
                    percentsymbol: '%',
                    currencysymbol: '$',
                    currencysymbolposition: 'before',
                    decimalseparator: '.',
                    thousandsseparator: ',',
                    pagergotopagestring: 'Перейти на сторінку:',
                    pagershowrowsstring: 'Показати рядки:',
                    pagerrangestring: ' з ',
                    pagerpreviousbuttonstring: 'попередні',
                    pagernextbuttonstring: 'наступні',
                    pagerfirstbuttonstring: 'перший',
                    pagerlastbuttonstring: 'останній',
                    groupsheaderstring: 'Перетягніть колонку і відпустіть для групування цієї колонки',
                    sortascendingstring: 'Сортування по зростанню',
                    sortdescendingstring: 'Сортування по спаданню',
                    sortremovestring: 'Очистити сортування',
                    groupbystring: 'Групувати за цією колонкою',
                    groupremovestring: 'Видалити групування',
                    filterclearstring: 'Скинути',
                    filterstring: 'Застосувати',
                    filtershowrowstring: 'Показати рядки, де:',
                    filterorconditionstring: 'Або',
                    filterandconditionstring: 'І',
                    filterapplystring: "Зберегти",
                    filtercancelstring: "Відмінити",
                    filterselectallstring: '(Вибрати всі)',
                    filterchoosestring: 'Будь ласка, виберіть:',
                    filtersearchstring: '',
                    filterstringcomparisonoperators: ['пусто', 'не пусто', 'містить', 'містить(врахувати регістр)',
                        'не містить', 'не містить(врахувати регістр)', 'починається з', 'починається з(врахувати регістр)',
                        'закінчується з', 'закінчується з(врахувати регістр)', 'дорівнює', 'дорівнює(врахувати регістр)', 'null', 'not null'],
                    filternumericcomparisonoperators: ['дорівнює', 'не дорівнює', 'менше ніж', 'менше ніж або дорівнює', 'більше ніж', 'більше ніж або дорівнює', 'null', 'not null'],
                    filterdatecomparisonoperators: ['дорівнює', 'не дорівнює', 'менше ніж', 'менше ніж або дорівнює', 'більше ніж', 'більше ніж або дорівнює', 'null', 'not null'],
                    filterbooleancomparisonoperators: ['дорівнює', 'не дорівнює'],
                    validationstring: 'Введене значеня є некоректним',
                    emptydatastring: 'Немає даних для відображення',
                    filterselectstring: 'Виберіть фільтр',
                    loadtext: 'Завантаження...',
                    clearstring: 'Очистити',
                    todaystring: 'Сьогодні'
                }
            break;
        case 'en':
            localization =
                {
                    // separator of parts of a date (e.g. '/' in 11/05/1955)
                    '/': '/',
                    // separator of parts of a time (e.g. ':' in 05:44 PM)
                    ':': ':',
                    // the first day of the week (0 = Sunday, 1 = Monday, etc)
                    firstDay: 0,
                    days: {
                        // full day names
                        names: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                        // abbreviated day names
                        namesAbbr: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                        // shortest day names
                        namesShort: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']
                    },
                    months: {
                        // full month names (13 months for lunar calendards -- 13th month should be '' if not lunar)
                        names: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', ''],
                        // abbreviated month names
                        namesAbbr: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', '']
                    },
                    // AM and PM designators in one of these forms:
                    // The usual view, and the upper and lower case versions
                    //      [standard,lowercase,uppercase]
                    // The culture does not use AM or PM (likely all standard date formats use 24 hour time)
                    //      null
                    AM: ['AM', 'am', 'AM'],
                    PM: ['PM', 'pm', 'PM'],
                    eras: [
                        // eras in reverse chronological order.
                        // name: the name of the era in this culture (e.g. A.D., C.E.)
                        // start: when the era starts in ticks (gregorian, gmt), null if it is the earliest supported era.
                        // offset: offset in years from gregorian calendar
                        {'name': 'A.D.', 'start': null, 'offset': 0}
                    ],
                    twoDigitYearMax: 2029,
                    patterns: {
                        // short date pattern
                        d: 'M/d/yyyy',
                        // long date pattern
                        D: 'dddd, MMMM dd, yyyy',
                        // short time pattern
                        t: 'h:mm tt',
                        // long time pattern
                        T: 'h:mm:ss tt',
                        // long date, short time pattern
                        f: 'dddd, MMMM dd, yyyy h:mm tt',
                        // long date, long time pattern
                        F: 'dddd, MMMM dd, yyyy h:mm:ss tt',
                        // month/day pattern
                        M: 'MMMM dd',
                        // month/year pattern
                        Y: 'yyyy MMMM',
                        // S is a sortable format that does not vary by culture
                        S: 'yyyy\u0027-\u0027MM\u0027-\u0027dd\u0027T\u0027HH\u0027:\u0027mm\u0027:\u0027ss',
                        // formatting of dates in MySQL DataBases
                        ISO: 'yyyy-MM-dd hh:mm:ss',
                        ISO2: 'yyyy-MM-dd HH:mm:ss',
                        d1: 'dd.MM.yyyy',
                        d2: 'dd-MM-yyyy',
                        d3: 'dd-MMMM-yyyy',
                        d4: 'dd-MM-yy',
                        d5: 'H:mm',
                        d6: 'HH:mm',
                        d7: 'HH:mm tt',
                        d8: 'dd/MMMM/yyyy',
                        d9: 'MMMM-dd',
                        d10: 'MM-dd',
                        d11: 'MM-dd-yyyy'
                    },
                    percentsymbol: '%',
                    currencysymbol: '$',
                    currencysymbolposition: 'before',
                    decimalseparator: '.',
                    thousandsseparator: ',',
                    pagergotopagestring: 'Go to page:',
                    pagershowrowsstring: 'Show rows:',
                    pagerrangestring: ' of ',
                    pagerpreviousbuttonstring: 'previous',
                    pagernextbuttonstring: 'next',
                    pagerfirstbuttonstring: 'first',
                    pagerlastbuttonstring: 'last',
                    groupsheaderstring: 'Drag a column and drop it here to group by that column',
                    sortascendingstring: 'Sort Ascending',
                    sortdescendingstring: 'Sort Descending',
                    sortremovestring: 'Remove Sort',
                    groupbystring: 'Group By this column',
                    groupremovestring: 'Remove from groups',
                    filterclearstring: 'Clear',
                    filterstring: 'Filter',
                    filtershowrowstring: 'Show rows where:',
                    filterorconditionstring: 'Or',
                    filterandconditionstring: 'And',
                    filterselectallstring: '(Select All)',
                    filterchoosestring: 'Please Choose:',
                    filtersearchstring: '',
                    filterstringcomparisonoperators: ['empty', 'not empty', 'enthalten', 'enthalten(match case)',
                        'does not contain', 'does not contain(match case)', 'starts with', 'starts with(match case)',
                        'ends with', 'ends with(match case)', 'equal', 'equal(match case)', 'null', 'not null'],
                    filternumericcomparisonoperators: ['equal', 'not equal', 'less than', 'less than or equal', 'greater than', 'greater than or equal', 'null', 'not null'],
                    filterdatecomparisonoperators: ['equal', 'not equal', 'less than', 'less than or equal', 'greater than', 'greater than or equal', 'null', 'not null'],
                    filterbooleancomparisonoperators: ['equal', 'not equal'],
                    validationstring: 'Entered value is not valid',
                    emptydatastring: 'No data to display',
                    filterselectstring: 'Select Filter',
                    loadtext: 'Loading...',
                    clearstring: 'Clear',
                    todaystring: 'Today'
                }
            break;
        default:
            localization =
                {
                    // separator of parts of a date (e.g. '/' in 11/05/1955)
                    '/': '/',
                    // separator of parts of a time (e.g. ':' in 05:44 PM)
                    ':': ':',
                    // the first day of the week (0 = Sunday, 1 = Monday, etc)
                    firstDay: 0,
                    days: {
                        // full day names
                        names: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                        // abbreviated day names
                        namesAbbr: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                        // shortest day names
                        namesShort: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']
                    },
                    months: {
                        // full month names (13 months for lunar calendards -- 13th month should be '' if not lunar)
                        names: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', ''],
                        // abbreviated month names
                        namesAbbr: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', '']
                    },
                    // AM and PM designators in one of these forms:
                    // The usual view, and the upper and lower case versions
                    //      [standard,lowercase,uppercase]
                    // The culture does not use AM or PM (likely all standard date formats use 24 hour time)
                    //      null
                    AM: ['AM', 'am', 'AM'],
                    PM: ['PM', 'pm', 'PM'],
                    eras: [
                        // eras in reverse chronological order.
                        // name: the name of the era in this culture (e.g. A.D., C.E.)
                        // start: when the era starts in ticks (gregorian, gmt), null if it is the earliest supported era.
                        // offset: offset in years from gregorian calendar
                        {'name': 'A.D.', 'start': null, 'offset': 0}
                    ],
                    twoDigitYearMax: 2029,
                    patterns: {
                        // short date pattern
                        d: 'M/d/yyyy',
                        // long date pattern
                        D: 'dddd, MMMM dd, yyyy',
                        // short time pattern
                        t: 'h:mm tt',
                        // long time pattern
                        T: 'h:mm:ss tt',
                        // long date, short time pattern
                        f: 'dddd, MMMM dd, yyyy h:mm tt',
                        // long date, long time pattern
                        F: 'dddd, MMMM dd, yyyy h:mm:ss tt',
                        // month/day pattern
                        M: 'MMMM dd',
                        // month/year pattern
                        Y: 'yyyy MMMM',
                        // S is a sortable format that does not vary by culture
                        S: 'yyyy\u0027-\u0027MM\u0027-\u0027dd\u0027T\u0027HH\u0027:\u0027mm\u0027:\u0027ss',
                        // formatting of dates in MySQL DataBases
                        ISO: 'yyyy-MM-dd hh:mm:ss',
                        ISO2: 'yyyy-MM-dd HH:mm:ss',
                        d1: 'dd.MM.yyyy',
                        d2: 'dd-MM-yyyy',
                        d3: 'dd-MMMM-yyyy',
                        d4: 'dd-MM-yy',
                        d5: 'H:mm',
                        d6: 'HH:mm',
                        d7: 'HH:mm tt',
                        d8: 'dd/MMMM/yyyy',
                        d9: 'MMMM-dd',
                        d10: 'MM-dd',
                        d11: 'MM-dd-yyyy'
                    },
                    percentsymbol: '%',
                    currencysymbol: '$',
                    currencysymbolposition: 'before',
                    decimalseparator: '.',
                    thousandsseparator: ',',
                    pagergotopagestring: 'Go to page:',
                    pagershowrowsstring: 'Show rows:',
                    pagerrangestring: ' of ',
                    pagerpreviousbuttonstring: 'previous',
                    pagernextbuttonstring: 'next',
                    pagerfirstbuttonstring: 'first',
                    pagerlastbuttonstring: 'last',
                    groupsheaderstring: 'Drag a column and drop it here to group by that column',
                    sortascendingstring: 'Sort Ascending',
                    sortdescendingstring: 'Sort Descending',
                    sortremovestring: 'Remove Sort',
                    groupbystring: 'Group By this column',
                    groupremovestring: 'Remove from groups',
                    filterclearstring: 'Clear',
                    filterstring: 'Filter',
                    filtershowrowstring: 'Show rows where:',
                    filterorconditionstring: 'Or',
                    filterandconditionstring: 'And',
                    filterselectallstring: '(Select All)',
                    filterchoosestring: 'Please Choose:',
                    filtersearchstring: '',
                    filterstringcomparisonoperators: ['empty', 'not empty', 'enthalten', 'enthalten(match case)',
                        'does not contain', 'does not contain(match case)', 'starts with', 'starts with(match case)',
                        'ends with', 'ends with(match case)', 'equal', 'equal(match case)', 'null', 'not null'],
                    filternumericcomparisonoperators: ['equal', 'not equal', 'less than', 'less than or equal', 'greater than', 'greater than or equal', 'null', 'not null'],
                    filterdatecomparisonoperators: ['equal', 'not equal', 'less than', 'less than or equal', 'greater than', 'greater than or equal', 'null', 'not null'],
                    filterbooleancomparisonoperators: ['equal', 'not equal'],
                    validationstring: 'Entered value is not valid',
                    emptydatastring: 'No data to display',
                    filterselectstring: 'Select Filter',
                    loadtext: 'Loading...',
                    clearstring: 'Clear',
                    todaystring: 'Today'
                }
            break;
    }
    return localization;
}
