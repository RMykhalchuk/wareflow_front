export function translateDictionariesOption(optionText) {
    const selectedLanguage = localStorage.getItem('Language');

    const translations = {
        // goods category
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

        //doc types
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

        // container type
        'Тип 1': 'Type 1',
        'Тип 2': 'Type 2',
        'Тип 3': 'Type 3',
        'Тип 4': 'Type 4',
        'Тип 5': 'Type 5',
        'Тип 6': 'Type 6',

        // service category
        'Прийом товару': 'Goods Receipt',
        'Зберігання товару': 'Goods Storage',
        'Відвантаження товару': 'Goods Unloading',
        'Комплектація товару': 'Goods Assembly',
        'Стікерування товару': 'Goods Labeling',
        'Копакінг товару': 'Goods Co-packing',
        Кросдок: 'Cross-docking',

        //country
        Україна: 'Ukraine',
        'Сполучені Штати Америки': 'United States of America',
        Англія: 'England',
        Польща: 'Poland',
        Німеччина: 'Germany',

        //company category
        Виробник: 'Manufacturer',
        Постачальник: 'Supplier',
        Дистрибютор: 'Distributor',
        Супермаркет: 'Supermarket',
        Перевізник: 'Carrier',
        '3PL - оператор': '3PL Operator',

        //category transport
        Вантажівка: 'Truck',
        'Вантажівка з причіпом': 'Truck with Trailer',
        Тягач: 'Tractor',
        Бус: 'Van',

        // measure unit for sku
        Пачка: 'Bundle',

        //doc type select dictionaries
        АДР: 'ADR',
        'Тип комірки': 'Cell type',
        'Статус комірки': 'Cell status',
        'Статус компанії': 'Company status',
        Країна: 'Country',
        'Зона завантаження': 'Loading zone',
        'Одиниці вимірювання': 'Units of measurement',
        'Тип пакування': 'Packaging type',
        'Роль користувача': 'User role',
        Місто: 'City',
        Вулиця: 'Street',
        'Тип сховища': 'Storage type',
        'Бренд транспорту': 'Transport brand',
        'Тип завантаження': 'Loading type',
        'Вид транспорту': 'Type of transport',
        'Тип транспорту': 'Transport type',
        Компанії: 'Companies',
        Склад: 'Warehouse',
        Транспорт: 'Transport',
        'Додаткове обладнання': 'Additional equipment',
        Користувачі: 'Users',
        'Замовлення (документи)': 'Orders (documents)',
        'Товарна накладна (документи)': 'Invoice (documents)',
        Валюта: 'Currency',
        'Тип вантажу': 'Cargo type',
        'Тип доставки': 'Delivery type',
        'Підстава для ТТН': 'Reason for consignment note',

        // Статус компанії
        'Компанія на розгляді': 'Company under review',
        'Прийнята компанія': 'Accepted company',
        'Відхилена компанія': 'Rejected company',

        // Роль користувача
        Палетувальник: 'Palletizer',
        'Комплектувальник Бр 1': 'Picker Br 1',
        'Комплектувальник Бр 2': 'Picker Br 2',
        'Комплектувальник Бр 3': 'Picker Br 3',
        'Комплектувальник Бр 4': 'Picker Br 4',
        'Комплектувальник Бр 5': 'Picker Br 5',
        Водій: 'Driver',
        Логіст: 'Logistician',
        Диспечер: 'Dispatcher',

        // Warehouse type
        'Власний склад': 'Own warehouse',
        'Найманий склад': 'Rented warehouse',
    };

    return selectedLanguage === 'uk' ? optionText : translations[optionText] || optionText;
}
