import { translit } from '../../utils/translit.js';

export function translateDocumentColumnName(selectorText) {
    var translateSelectorText = translit(selectorText);
    //console.log(translateSelectorText)

    const storedLanguage = localStorage.getItem('Language') || 'uk';
    const selectedLanguage = storedLanguage === 'uk' ? 'ua' : storedLanguage;

    const translations = {
        // Request for transportation
        operator: {
            ua: 'Оператор',
            en: 'Operator',
        },
        zamovnik: {
            ua: 'Замовник',
            en: 'Customer',
        },
        vantazhovidpravnik: {
            ua: 'Вантажовідправник',
            en: 'Consignee',
        },
        vantazhootrimuvach: {
            ua: 'Вантажоотримувач',
            en: 'Sender Contact Person',
        },
        kontaktna_osoba_vidpravnika: {
            ua: 'Контактна особа відправника',
            en: 'Sender Contact Person',
        },
        kontaktna_osoba_otrimuvacha: {
            ua: 'Контактна особа отримувача',
            en: 'Receiver Contact Person',
        },
        lokatsiya_zavantazhennya: {
            ua: 'Локація завантаження',
            en: 'Loading Location',
        },
        lokatsiya_rozvantazhennya: {
            ua: 'Локація розвантаження',
            en: 'Unloading Location',
        },

        // castom block
        tip_kuzova: {
            ua: 'Тип кузова',
            en: 'Body Type',
        },
        visota_kuzova: {
            ua: 'Висота кузова',
            en: 'Body Height',
        },
        'temperaturnij_rezhim_(s)': {
            ua: 'Температурний режим (С)',
            en: 'Temperature Mode (C)',
        },
        gidrobort: {
            ua: 'Гідроборт',
            en: 'Hydraulic Board',
        },

        tip_vantazhu: {
            ua: 'Тип вантажу',
            en: 'Cargo Type',
        },
        'vaga_brutto_(kg)': {
            ua: 'Вага брутто (кг)',
            en: 'Gross Weight (kg)',
        },
        tip_pakuvannya: {
            ua: 'Тип пакування',
            en: 'Packaging Type',
        },
        velikogabaritnij: {
            ua: 'Великогабаритний',
            en: 'Oversized',
        },
        "k-st'_mists'_(pal/m3)": {
            ua: 'К-сть місць (пал/м3)',
            en: 'Number of Places (pallets/m3)',
        },
        visota_palet: {
            ua: 'Висота палет',
            en: 'Pallet Height',
        },
        navantazheni_paleti: {
            ua: 'Навантажені палети',
            en: 'Loaded Pallets',
        },

        data_gotovnosti: {
            ua: 'Дата готовності',
            en: 'Readiness Date',
        },
        data_i_godini_vidvantazhennya: {
            ua: 'Дата і години відвантаження',
            en: 'Shipping Date and Hours',
        },
        'obid_(pri_nayavnosti)': {
            ua: 'Обід (при наявності)',
            en: 'Lunch Break (if available)',
        },

        "otsinochna_vartist'_vantazhu_(grn_z_pdv)": {
            ua: 'Оціночна вартість вантажу (грн з ПДВ)',
            en: 'Estimated Cargo Value (UAH with VAT)',
        },
        platnik: {
            ua: 'Платник',
            en: 'Payer',
        },
        povernennya_piddoniv: {
            ua: 'Повернення піддонів',
            en: 'Pallet Return',
        },
        povernennya_dokumentiv: {
            ua: 'Повернення документів',
            en: 'Document Return',
        },
        komentar_po_umovah: {
            ua: 'Коментар по умовах',
            en: 'Comment on Conditions',
        },
        data_i_godini_rozvantazhennya: {
            ua: 'Дата і години розвантаження',
            en: 'Date and hours of unloading',
        },

        //Запит на вантаж
        kompaniya: {
            ua: 'Компанія',
            en: 'Company',
        },
        kontaktna_osoba: {
            ua: 'Контактна особа',
            en: 'Contact Person',
        },
        transport: {
            ua: 'Транспорт',
            en: 'Transport',
        },
        dodatkove_obladnannya: {
            ua: 'Додаткове обладнання',
            en: 'Additional Equipment',
        },
        vodij: {
            ua: 'Водій',
            en: 'Driver',
        },

        misto_zavantazhennya: {
            ua: 'Місто завантаження',
            en: 'Loading City',
        },
        misto_rozvantazhennya: {
            ua: 'Місто розвантаження',
            en: 'Unloading City',
        },
        "maksimal'na_k-st'_tochok_zavantazhennya": {
            ua: 'Максимальна к-сть точок завантаження',
            en: 'Max Loading Points',
        },
        "maksimal'na_k-st'_tochok_rozvantazhennya": {
            ua: 'Максимальна к-сть точок розвантаження',
            en: 'Max Unloading Points',
        },
        data_i_chas__zavantazhennya: {
            ua: 'Дата і час  завантаження',
            en: 'Loading Date and Time',
        },
        data_i_chas_rozvantazhennya: {
            ua: 'Дата і час розвантаження',
            en: 'Unloading Date and Time',
        },
        vidhilennya_vid_lokatsii_zavantazhennya: {
            ua: 'Відхилення від локації завантаження',
            en: 'Deviation from Loading Location',
        },
        vidhilennya_vid_lokatsii_rozvantazhennya: {
            ua: 'Відхилення від локації розвантаження',
            en: 'Deviation from Unloading Location',
        },

        "mistkist'_(pal)": {
            ua: 'Місткість (пал)',
            en: 'Capacity (pallets)',
        },
        "maksimal'na_vartist'_vantazhu_(grn)": {
            ua: 'Максимальна вартість вантажу (грн)',
            en: 'Max Cargo Value (UAH)',
        },
        temperaturnij_rezhim: {
            ua: 'Температурний режим',
            en: 'Temperature Mode',
        },
        strahuvannya_vantazhu: {
            ua: 'Страхування вантажу',
            en: 'Cargo Insurance',
        },
        "nayavnist'_gidrobortu": {
            ua: 'Наявність гідроборту',
            en: 'Hydraulic Board Availability',
        },

        //Заявки на прийом
        diapazon: {
            ua: 'Діапазон',
            en: 'Range',
        },
        kontaktna_osoba_zamovnika: {
            ua: 'Контактна особа замовника',
            en: 'Customer Contact Person',
        },
        otrimuvach: {
            ua: 'Отримувач',
            en: 'Recipient',
        },
        "nayavnist'_plombi": {
            ua: 'Наявність пломби',
            en: 'Seal Availability',
        },
        "postachal'nik": {
            ua: 'Постачальник',
            en: 'Supplier',
        },
        tip_dostavki: {
            ua: 'Тип доставки',
            en: 'Delivery Type',
        },
        planova_data_postuplennya: {
            ua: 'Планова дата поступлення',
            en: 'Planned Arrival Date',
        },
        chas_dostavki: {
            ua: 'Час доставки',
            en: 'Delivery Time',
        },
        dnz_transportu: {
            ua: 'ДНЗ транспорту',
            en: 'Transport License Plate',
        },

        // Заявка на відвантаження
        data_dostavki: {
            ua: 'Дата доставки',
            en: 'Delivery Date',
        },

        // Створення "ТТН"
        pidstava_dlya_ttn: {
            ua: 'Підстава для ТТН',
            en: 'Reason for the CMR',
        },
        nomer_ttn: {
            ua: 'Номер ТТН',
            en: 'CMR Number',
        },
        data_ttn: {
            ua: 'Дата ТТН',
            en: 'CMR Date',
        },
        pereviznik: {
            ua: 'Перевізник',
            en: 'Carrier',
        },
        kod_operatora: {
            ua: 'Код оператора',
            en: 'Operator Code',
        },
        "vartist'_rejsu": {
            ua: 'Вартість рейсу',
            en: 'Trip Cost',
        },
        "sobivartist'": {
            ua: 'Собівартість',
            en: 'Prime Cost',
        },

        // Замовлення
        "kompaniya_postachal'nik": {
            ua: 'Компанія постачальник',
            en: 'Supplier Company',
        },
        kompaniya_otrimuvach: {
            ua: 'Компанія отримувач',
            en: 'Receiver Company',
        },
        "sklad_postachal'nik_(zavantazhennya)": {
            ua: 'Склад постачальник (завантаження)',
            en: 'Supplier Warehouse (Loading)',
        },
        'sklad_otrimuvach_(rozvantazhennya)': {
            ua: 'Склад отримувач (розвантаження)',
            en: 'Receiver Warehouse (Unloading)',
        },
        data_zavantazhennya: {
            ua: 'Дата завантаження',
            en: 'Loading Date',
        },
        data_vidvantazhennya: {
            ua: 'Дата відвантаження',
            en: 'Shipment Date',
        },
        chas_zavantazhennya: {
            ua: 'Час завантаження',
            en: 'Loading Time',
        },
        chas_rozvantazhennya: {
            ua: 'Час розвантаження',
            en: 'Unloading Time',
        },
        za_dogovorom: {
            ua: 'За договором',
            en: 'Under the Contract',
        },
        data_stvorennya_dokumentu: {
            ua: 'Дата створення документу',
            en: 'Document Creation Date',
        },
        komentar: {
            ua: 'Коментар',
            en: 'Comment',
        },
        'tsina_z_pdv_(grn)': {
            ua: 'Ціна з ПДВ (грн)',
            en: 'Price with VAT (UAH)',
        },
        'tsina_bez_pdv_(grn)': {
            ua: 'Ціна без ПДВ (грн)',
            en: 'Price without VAT (UAH)',
        },

        // Supplier Arrival
        "data_stvorennya_ts'ogo_dokumentu": {
            ua: 'Дата створення цього документу',
            en: 'Date of Document Creation',
        },
        tovarna_nakladna: {
            ua: 'Товарна накладна',
            en: 'Goods Invoice',
        },
        valyuta: {
            ua: 'Валюта',
            en: 'Currency',
        },
        sklad_otrimuvach: {
            ua: 'Склад отримувач',
            en: 'Receiver Warehouse',
        },
        mistse_prijmannya: {
            ua: 'Місце приймання',
            en: 'Receiving Location',
        },
        odinitsi: {
            ua: 'Одиниці',
            en: 'Units',
        },
        sklad: {
            ua: 'Склад',
            en: 'Warehouse',
        },

        // Debiting
        sklad_vidpravnik: {
            ua: 'Склад відправник',
            en: 'Sender Warehouse',
        },

        // Internal Transfer
        zamovlennya: {
            ua: 'Замовлення',
            en: 'Order',
        },
        obladnannya: {
            ua: 'Обладнання',
            en: 'Equipment',
        },

        // Рахунки
        data_i_chas_zavantazhennya: {
            ua: 'Дата і час завантаження',
            en: 'Date and time of loading',
        },
        '№_zamovlennya_(dokumentu)': {
            ua: '№ замовлення (документу)',
            en: 'Order number (document)',
        },
        data_stvorennya: {
            ua: 'Дата створення',
            en: 'Creation date',
        },
        palet: {
            ua: 'Палет',
            en: 'Pallet',
        },

        // Інші ключі та переклади...
    };

    // Переконайтесь, що translateSelectorText є ключем у translations
    const translation = translations[translateSelectorText];
    if (!translation) {
        return selectorText; // Якщо немає перекладу, повертаємо ключ як є
    }

    // Отримання перекладу відповідно до вибраної мови
    return translation[selectedLanguage] || translateSelectorText;
}
