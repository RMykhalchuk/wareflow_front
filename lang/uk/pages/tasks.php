<?php

return [
    'tasks' => [
        'index' => [
            'title' => 'Завдання',
            'title_header' => 'Завдання',
            'add_button' => 'Додати завдання',
            'empty_message' => 'У вас ще немає жодного завдання!',
            'add_prompt' => 'Додайте нове завдання',
            'add_button_text' => 'Додати завдання',
        ],
        'create' => [
            'title' => 'Завдання',
            'titles' => [
                'internal_displacement' => 'Нове завдання "Внутрішнє переміщення"',
            ],
            'create_tasks' => 'Створити завдання',

            'main_information' => 'Основна інформація',
            'warehouse' => 'Склад',
            'select_warehouse' => 'Виберіть склад',

            'performer' => 'Виконавець',
            'choose_tasks_performers' => 'Виберіть виконавців завдання',

            'kind' => 'Вид',
            'select_kind' => 'Виберіть вид',

            'type' => 'Тип',
            'select_type' => 'Виберіть тип',

            'priority' => 'Пріоритет',

            'comment' => 'Коментар',

            'leftovers' => 'Залишки',

            'add_leftovers' => 'Додати залишок',
            'save' => 'Зберегти',

            'modal_add' => [
                'title' => 'Додати номенклатуру',
                'search_placeholder' => 'Пошук',
                'category' => 'Категорія',
                'category_placeholder' => 'Категорія',
                'goods' => 'Товари',
                'goods_placeholder' => 'Товари',
                'search_button' => 'Шукати',
                'sku' => 'Номенклатура',
                'name' => 'Назва',
                'barcode' => 'Штрих-код',
                'brand' => 'Бренд',
                'supplier' => 'Постачальник',
                'supplier_name' => 'Назва постачальника',
                'manufacturer' => 'Виробник',
                'manufacturer_name' => 'Виробник',
                'country' => 'Країна виробник',
                'ukraine' => 'Україна',
                'party' => 'Партія',
                'select_party' => 'Оберіть партію',
                'manufactured_date' => 'Виготовлення',
                'bb_date' => 'Вжити до',
                'choose_date' => 'Оберіть дату',
                'quantity' => 'Кількість',
                'quantity_placeholder' => 'Вкажіть кількість товару',
                'add_button' => 'Додати',
            ],

        ],

        'edit' => [
            'create_inventory' => 'Редагувати завдання',
        ],

        'view' => [
            'title' => 'Перегляд завдання',

            'breadcrumb' => [
                'tasks' => 'Завдання',
                'current' => 'Перегляд завдання',
            ],

            'actions' => [
                'cancel_task' => 'Скасувати завдання',
            ],

            'tasks' => 'Внутрішнє переміщення',

            'no_name' => 'Немає',

            'edit_icon_tooltip' => 'Редагувати завдання',

            'main_info' => 'Основна інформація',
            'warehouse' => 'Склад:',
            'place_to' => 'Місце до розміщення',
            'place_placement' => 'Місце розміщення',
            'place_from' => 'Місце відбору',
            'executors' => 'Виконавці:',
            'all' => 'Всі',
            'add_executor' => 'Додати виконавця',
            'executor_label' => 'Виконавець',
            'executor_placeholder' => 'Оберіть виконавця',
            'type' => 'Тип:',
            'priority' => 'Пріоритет',
            'send_to_work' => 'Передати в роботу',
            'status' => 'Статус',
            'pause' => 'Призупинити',
            'finish_early' => 'Завершити раніше',
            'finish_tasks' => 'Завершити завдання',
            'nomenclature' => 'Номенклатура',
            'leftovers_info' => 'Залишки',
            'goods' => 'Товар',
            'leftovers_empty' => 'Залишки з терміналу відсутні',
            'modal_delete' => [
                'title' => 'Деактивувати завдання',
                'confirmation' => 'Ви дійсно впевнені, що хочете деактивувати це завдання?',
                'cancel' => 'Скасувати',
                'submit' => 'Деактивувати',
            ],
            'modal_delete_confirm' => 'Деактивувати',
            'executor' => [
                'remove_executor' => 'Видалити виконавця',
            ],
            'close' => 'Закрити',
            'cancel' => [
                'cancel_button' => 'Скасувати',
            ],
        ],

        'modal' => [
            'title' => 'Виберіть тип завдання',
            'desc' => 'Тип завдання визначає його призначення та вплив на залишки',
            'full_title' => 'Внутрішнє переміщення',
            'full_description' => 'Збільшує кількість залишків на складі',
            'simple_title' => 'Просте завдання',
            'simple_description' => 'Не впливає на кількість залишків',
        ],

        'cancel' => [
            'create' => [
                'modal' => [
                    'title' => 'Скасувати створення завдання',
                    'content' => 'Ви точно впевнені що хочете вийти зі створення? <br> Внесені зміни не збережуться.',
                ],
            ],
            'edit' => [
                'modal' => [
                    'title' => 'Скасувати редагування завдання',
                    'content' => 'Ви точно впевнені що хочете вийти із редагування? <br> Внесені зміни не збережуться.',

                ],
            ],

            'cancel_button' => 'Скасувати',
            'confirm_button' => 'Підтвердити',
        ],
    ],
];
