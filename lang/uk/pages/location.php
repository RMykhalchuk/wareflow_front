<?php

return [
    'location' => [
        'index' => [
            'title' => 'Локації',
            'title_header' => 'Локації',
            'add_location_button' => 'Додати локацію',
            'empty_message' => 'У вас ще немає жодної локації!',
            'add_location_prompt' => 'Додайте нову локацію',
            'add_location_button_text' => 'Додати локацію',
        ],

        'create' => [
            'title' => 'Створення локації',
            'breadcrumb' => [
                'warehouses' => 'Локації',
                'add' => 'Нова локація',
            ],
            'save_button' => 'Зберегти',

            'page' => [
                'title' => 'Додавання нової локації',
            ],

            'block_1' => [
                'main_data_title' => 'Дані про локацію',
                'name_label' => 'Назва локації',
                'name_placeholder' => 'Введіть назву локації',
                'company_label' => 'Компанія',
                'company_placeholder' => 'Оберіть компанію',
                'country_label' => 'Країна',
                'country_placeholder' => 'Оберіть країну',
                'settlement_label' => 'Населений пункт',
                'settlement_placeholder' => 'Оберіть населений пункт',
                'street_label' => 'Вулиця',
                'street_placeholder' => 'Оберіть вулицю',
                'building_number_label' => 'Номер будинку',
                'building_number_placeholder' => 'Вкажіть номер будинку',
            ],

            'block_2' => [
                'title' => 'Адреса',
                'coordinates_label' => 'Посилання на Google map',
                'coordinates_placeholder' => 'Вкажіть посилання на Google map',
            ],

            'cancel_modal' => [
                'title' => 'Скасувати створення локації',
                'confirmation' => 'Ви дійсно впевнені, що хочете вийти?',
                'cancel' => 'Скасувати',
                'submit' => 'Підтвердити',
            ],
        ],

        'edit' => [
            'title' => 'Редагування локації',
            'breadcrumb' => [
                'warehouses' => 'Локації',
                'view_warehouse' => 'Перегляд локації :name',
                'edit_warehouse' => 'Редагування локації',
            ],
            'cancel_modal' => [
                'title' => 'Скасувати редагування локації',
                'message' => 'Ви впевнені, що хочете скасувати редагування? <br> Внесені зміни не будуть збережені.',
                'confirm_button' => 'Підтвердити',
                'cancel_button' => 'Скасувати',
            ],
            'save_button' => 'Зберегти',
        ],

        'view' => [
            'title' => 'Перегляд локації',
            'breadcrumb' => [
                'warehouses' => 'Локації',
                'current' => 'Перегляд локації',
            ],
            'no_name' => 'Немає',
            'edit_icon_tooltip' => 'Редагування',
            'modal_delete_confirm' => 'Деактивувати',
            'modal_delete' => [
                'title' => 'Видалення локації',
                'confirmation' => 'Ви дійсно впевнені, що хочете видалити цю локацію?',
                'cancel' => 'Скасувати',
                'submit' => 'Видалити',
            ],
            'main_data' => 'Основні дані',
            'address' => 'Адреса',
        ],
    ],
];
