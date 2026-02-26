<?php

return [
    'documents' => [
        'list' => [
            'title' => 'Список документів',
            'documents' => 'Документи',
            'select_warehouse' => 'Оберіть склад',
            'search_placeholder' => 'Пошук типу документа',
            'in_progress' => 'В роботі',
            'edit' => 'Редагувати',
            'view_documents' => 'Переглянути <br> документи',
            'create_document' => 'Створити <br> документ',
            'no_document_types' => 'Немає типів документів',
            'no_document_types_description' => 'Створіть перший тип документа, щоб почати роботу',
        ],

        'index' => [
            'title' => 'Документи',
            'breadcrumb_start' => 'Документи',
            'document_title' => ':name', // Документи :name
            'create_document' => 'Створити документ',
            'no_documents_message' => 'У вас ще немає документів цього типу!',
            'create_document_message' => 'Створіть новий документ',
            'create_document_no_message_btn' => 'Створити документ',
        ],

        'create' => [
            'title' => 'Створення документу',
            'documents' => 'Документи',
            'creating' => 'Створення',
            'save_as_draft' => 'Зберегти як чернетку',
            'save' => 'Зберегти',
            'new_document_title' => 'Новий документ',

            'sku' => 'Номенклатура',
            'add_sku' => 'Додати номенклатуру',
            'edit_sku' => 'Редагувати номенклатуру',
            'select_goods' => 'Виберіть товар та вкажіть кількість.',
            'goods_title' => 'Назва товару та кількість',
            'goods_name' => 'Назва товару',
            'goods_count' => 'Вкажіть кількість основних одиниць',
            'add' => 'Додати',
            'cancel' => 'Відміна',
        ],

        'update' => [
            'title' => 'Редагування документу',
            'breadcrumb_edit' => 'Редагування',
            'breadcrumb_view' => 'Перегляд "',
            'edit_document_title' => 'Редагувати документ',
        ],

        'view' => [
            'title' => 'Перегляд документу',
            'breadcrumb' => [
                'documents' => 'Документи',
                'view' => 'Перегляд',
            ],
            'data_info_title' => 'Основна інформація:',
            'action_with_document' => [
                'task' => 'Створити завдання',
                'manual' => 'Опрацювати вручну',
            ],
            'actions' => [
                'edit' => 'Редагування',
                'cancel_execution' => 'Скасувати виконання',
            ],
            'tabs' => [
                'contents' => 'Вміст',
                'tasks' => 'Завдання',
                'tasks_placeholder' => 'Після передачі документа в роботу, пов’язані завдання відображатимуться тут.',
                'formed_containers' => 'Сформовані контейнери',
                'containers' => 'Контейнери',
                'containers_placeholder' => 'Після завершення опрацювання документа, сформовані контейнери відображатимуться тут.',
            ],
            'sku_title' => 'Номенклатура',
            'free_selection_desc' => 'При ввімкненому вільному підборі документ можна опрацювати лише після опрацювання всіх позицій.',
            'free_selection_label' => 'Вільний підбір',
            'created' => 'Створено',
            'created_at' => 'Створено:',
            'author' => 'Автор:',
            'document_erp_number' => '№ Документу ERP',
            'document_erp_id' => 'ID Документу ERP',
        ],

        'fields' => [
            'arrival' => [
                'title' => [
                    'osnovna_informaciia' => 'Основна інформація',
                    'vimogi_do_transportu' => 'Вимоги до транспорту',
                    'informacia_pro_vantazh' => 'Інформація про вантаж',
                    'vidvantazhennia' => 'Відвантаження',
                    'rozvantazhennia' => 'Розвантаження',
                    'umovi_dostavky' => 'Умови доставки',
                    'informacia_pro_marshrut' => 'Інформація про маршрут',
                    'shapka' => 'Шапка',
                ],
                'label' => [
                    // Основні
                    'postacalnik' => 'Постачальник',
                    'komentar' => 'Коментар',
                    'misce_priimannia' => 'Місце приймання',

                    // Запит на транспорт
                    'operator' => 'Оператор',
                    'zamovnik' => 'Замовник',
                    'vantazhovidpravnik' => 'Вантажовідправник',
                    'vantazhootrymuvach' => 'Вантажоотримувач',
                    'kontaktna_osoba_vidpravnika' => 'Контактна особа відправника',
                    'kontaktna_osoba_otrymuvacha' => 'Контактна особа отримувача',
                    'lokacia_zavantazhennia' => 'Локація завантаження',
                    'lokacia_rozvantazhennia' => 'Локація розвантаження',
                    'tip_kuzova' => 'Тип кузова',
                    'visota_kuzova' => 'Висота кузова',
                    'temperaturniy_rezhim' => 'Температурний режим (С)',
                    'gidrobort' => 'Гідроборт',
                    'tip_vantazhu' => 'Тип вантажу',
                    'vaga_brutto' => 'Вага брутто (кг)',
                    'tip_pakuvannya' => 'Тип пакування',
                    'k_st_mist' => 'К-сть місць (пал/м3)',
                    'visota_palet' => 'Висота палет',
                    'navantazheni_palet' => 'Навантажені палети',
                    'data_gotovnosti' => 'Дата готовності',
                    'data_i_godini_vidvantazhennya' => 'Дата і години відвантаження',
                    'obid' => 'Обід (при наявності)',
                    'ocinocna_vartist_vantazhu' => 'Оціночна вартість вантажу (грн з ПДВ)',
                    'platnik' => 'Платник',
                    'povernennya_poddoniv' => 'Повернення піддонів',
                    'povernennya_dokumentiv' => 'Повернення документів',
                    'komentar_po_umovah' => 'Коментар по умовах',
                    'data_i_godini_rozvantazhennya' => 'Дата і години розвантаження',

                    // Запит на вантаж
                    'kompaniya' => 'Компанія',
                    'kontaktna_osoba' => 'Контактна особа',
                    'transport' => 'Транспорт',
                    'dodatkove_obladnannya' => 'Додаткове обладнання',
                    'vodiy' => 'Водій',
                    'misto_zavantazhennya' => 'Місто завантаження',
                    'misto_rozvantazhennya' => 'Місто розвантаження',
                    'maks_kilkist_tochok_zavantazhennya' => 'Максимальна к-сть точок завантаження',
                    'maks_kilkist_tochok_rozvantazhennya' => 'Максимальна к-сть точок розвантаження',
                    'data_i_chas_zavantazhennya' => 'Дата і час завантаження',
                    'data_i_chas_rozvantazhennya' => 'Дата і час розвантаження',
                    'vidhilennya_vid_lokacii_zavantazhennya' => 'Відхилення від локації завантаження',
                    'vidhilennya_vid_lokacii_rozvantazhennya' => 'Відхилення від локації розвантаження',
                    'mistkist_pal' => 'Місткість (пал)',
                    'maks_vartist_vantazhu' => 'Максимальна вартість вантажу (грн)',
                    'temperaturniy_rezhim_dodatkoviy' => 'Температурний режим',
                    'strahuvannya_vantazhu' => 'Страхування вантажу',
                    'nayavnist_gidrobortu' => 'Наявність гідроборту',

                    // Заявки на прийом
                    'diapazon' => 'Діапазон',
                    'kontaktna_osoba_zamovnika' => 'Контактна особа замовника',
                    'otrymuvach' => 'Отримувач',
                    'nayavnist_plomby' => 'Наявність пломби',
                    'tip_dostavky' => 'Тип доставки',
                    'planova_data_postuplennya' => 'Планова дата поступлення',
                    'chas_dostavky' => 'Час доставки',
                    'dnz_transportu' => 'ДНЗ транспорту',

                    // Заявка на відвантаження
                    'data_dostavky' => 'Дата доставки',

                    // Створення "ТТН"
                    'pidslava_dlya_ttn' => 'Підстава для ТТН',
                    'nomer_ttn' => 'Номер ТТН',
                    'data_ttn' => 'Дата ТТН',
                    'pereviznik' => 'Перевізник',
                    'kod_operatora' => 'Код оператора',
                    'vartist_reysu' => 'Вартість рейсу',
                    'sobivartist' => 'Собівартість',

                    // Замовлення
                    'kompaniya_postachalnik' => 'Компанія постачальник',
                    'kompaniya_otrymuvach' => 'Компанія отримувач',
                    'sklad_postachalnik' => 'Склад постачальник (завантаження)',
                    'sklad_otrymuvach' => 'Склад отримувач (розвантаження)',
                    'data_zavantazhennya' => 'Дата завантаження',
                    'data_vidvantazhennya' => 'Дата відвантаження',
                    'chas_zavantazhennya' => 'Час завантаження',
                    'chas_rozvantazhennya' => 'Час розвантаження',
                    'za_dogovorom' => 'За договором',
                    'data_stvorennya_dokumentu' => 'Дата створення документу',
                    'cina_z_pdv' => 'Ціна з ПДВ (грн)',
                    'cina_bez_pdv' => 'Ціна без ПДВ (грн)',

                    // Supplier Arrival
                    'data_stvorennya_cogo_dokumentu' => 'Дата створення цього документу',
                    'tovarnaya_nakladna' => 'Товарна накладна',
                    'valuta' => 'Валюта',
                    'sklad_otrymuvach_supplier' => 'Склад отримувач',
                    'odynyci' => 'Одиниці',
                    'sklad' => 'Склад',

                    // Debiting
                    'sklad_vidpravnik' => 'Склад відправник',

                    // Internal Transfer
                    'zamovlennya' => 'Замовлення',
                    'obladnannya' => 'Обладнання',

                    // Рахунки
                    'nomer_zamovlennya_dokumentu' => '№ замовлення (документу)',
                    'data_stvorennya_rakhunkiv' => 'Дата створення',
                    'palet' => 'Палет',
                ],
                'placeholder' => [
                    'vvedit_komentar' => 'Введіть коментар',
                    'viberit_znacennia' => 'Виберіть значення',
                    'vvedit_tekst' => 'Введіть текст',

                    'oberit_pereviznika' => 'Оберіть перевізника',
                    'oberit_zamovnika' => 'Оберіть замовника',
                    'oberit_vantazhovidpravnika' => 'Оберіть вантажовідправника',
                    'oberit_vantazhootrymuvacha' => 'Оберіть вантажоотримувача',
                    'oberit_kontaktnu_osobu_vidpravnika' => 'Оберіть контактну особу відправника',
                    'oberit_kontaktnu_osobu_otrymuvacha' => 'Оберіть контактну особу отримувача',
                    'oberit_lokaciu_zavantazhennya' => 'Оберіть локацію завантаження',
                    'oberit_lokaciu_rozvantazhennya' => 'Оберіть локацію розвантаження',
                    'oberit_tip_kuzova' => 'Оберіть тип кузова',
                    'vkazhit_visotu_kuzova' => 'Вкажіть висоту кузова',
                    'oberit_tip_vantazhu' => 'Оберіть тип вантажу',
                    'vkazhit_vagu_brutto' => 'Вкажіть вагу брутто (кг)',
                    'oberit_tip_pakuvannya' => 'Оберіть тип пакування',
                    'vkazhit_k_st_mist' => 'Вкажіть к-сть місць (пал/м3)',
                    'vkazhit_visotu_palet' => 'Вкажіть висоту палет',
                    'vkazhit_data_gotovnosti' => 'Вкажіть дату готовності',
                    'vkazhit_ocinocnu_vartist_vantazhu' => 'Вкажіть оціночну вартість вантажу',
                    'oberit_platnika' => 'Оберіть платника',

                    // Запит на вантаж
                    'oberit_kompaniyu' => 'Оберіть компанію',
                    'oberit_kontaktnu_osobu' => 'Оберіть контактну особу',
                    'oberit_transport' => 'Оберіть транспорт',
                    'oberit_dodatkove_obladnannya' => 'Оберіть додаткове обладнання',
                    'oberit_vodiya' => 'Оберіть водія',
                    'oberit_misto_zavantazhennya' => 'Оберіть місто завантаження',
                    'oberit_misto_rozvantazhennya' => 'Оберіть місто розвантаження',
                    'vkazhit_maks_kilkist_tochok_zavantazhennya' => 'Вкажіть максимальну к-сть точок завантаження',
                    'vkazhit_maks_kilkist_tochok_rozvantazhennya' => 'Вкажіть максимальну к-сть точок розвантаження',
                    'vkazhit_data_i_chas_zavantazhennya' => 'Вкажіть дату і час  завантаження',
                    'vkazhit_data_i_chas_rozvantazhennya' => 'Вкажіть дату і час розвантаження',
                    'vkazhit_vidhilennya_vid_lokacii_zavantazhennya' => 'Відхилення від локації завантаження',
                    'vkazhit_vidhilennya_vid_lokacii_rozvantazhennya' => 'Відхилення від локації розвантаження',
                    'vkazhit_mistkist_pal' => 'Вкажіть місткість (пал)',
                    'vkazhit_maks_vartist_vantazhu' => 'Вкажіть максимальну вартість вантажу (грн)',
                    'vkazhit_temperaturniy_rezhim_dodatkoviy' => 'Вкажіть температурний режим (необов\'язково)',
                ],
            ],
            'switch_invalid_text' => 'Поле обов\'язкове до заповнення',
        ],

        'cancel' => [
            'create' => [
                'modal' => [
                    'title' => 'Скасувати створення документу',
                    'content' => 'Ви точно впевнені що хочете вийти зі створення? <br> Внесені зміни не збережуться.',
                ],
            ],
            'edit' => [
                'modal' => [
                    'title' => 'Скасувати редагування документу',
                    'content' => 'Ви точно впевнені що хочете вийти із редагування? <br> Внесені зміни не збережуться.',

                ],
            ],

            'cancel_button' => 'Скасувати',
            'confirm_button' => 'Підтвердити',
        ],

    ],

    // Fields folder
    'documents_fields_range_from' => 'Від',
    'documents_fields_range_to' => 'До',

    'documents_fields_data_time_range' => 'Дата',
    'documents_fields_data_time_range_from' => 'Від',
    'documents_fields_data_time_range_to' => 'До',

    'documents_fields_data_time_date' => 'Дата',
    'documents_fields_data_time_time' => 'Час',

    'documents_fields_time_range_from' => 'Від',
    'documents_fields_time_range_to' => 'До',
];
