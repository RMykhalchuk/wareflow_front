<?php

return [
    'inventory' => [
        'index' => [
            'title' => 'Завдання на інвентаризацію',
            'title_header' => 'Завдання на інвентаризацію',
            'add_location_button' => 'Створити',
            'empty_message' => 'У вас ще немає жодного завдання на інвентаризацію!',
            'add_location_prompt' => 'Створити нове завдання на інвентаризацію',
            'add_location_button_text' => 'Створити',

            'title_manual' => 'Ручна інвентаризація',
            'add_manual_inventory' => 'Додайте нову ручну інвентаризацію',
        ],
        'area' => [
            'web' => 'вручну',
            'api' => 'термінал'
        ],
        'create' => [
            'title' => 'Інвентаризація',
            'create_inventory' => 'Створити інвентаризацію',

            'partial' => 'Часткова',
            'main_information' => 'Основна інформація',
            'show_leftovers_on_terminal' => 'Показати залишки на терміналі',
            'restrict_goods_movement' => 'Блокувати рух товару по обʼєктах інвентаризації',
            'performer' => 'Виконавці',
            'choose_inventory_performers' => 'Виберіть виконавців інвентаризації',
            'start_date' => 'Дата і час початку',
            'end_date' => 'Дата і час завершення',
            'comment' => 'Коментар',
            'priority' => 'Пріоритет',

            'all' => 'Усі',

            'location' => 'Місце проведення',
            'warehouse' => 'Склад',
            'select_warehouse' => 'Виберіть склад',
            'warehouse_erp' => 'Склад ERP',
            'select_warehouse_erp' => 'Виберіть склад ERP',
            'zone' => 'Зона',
            'sector' => 'Сектор',
            'row' => 'Ряд',
            'cell' => 'Комірка',

            'select_zone' => 'Оберіть зону',
            'select_sector' => 'Оберіть сектор',
            'select_row' => 'Оберіть ряд',
            'select_cell' => 'Оберіть комірку',

            'nomenclature' => 'Номенклатура',
            'category_subcategory' => 'Категорія/Підкатегорія',
            'manufacturer' => 'Виробник',
            'brand' => 'Бренд',
            'supplier' => 'Постачальник',

            'select_category_subcategory' => 'Оберіть категорію/підкатегорію',
            'select_manufacturer' => 'Оберіть виробника',
            'select_brand' => 'Оберіть бренд',
            'select_supplier' => 'Оберіть Оберіть постачальника',
            'select_nomenclature' => 'Оберіть номенклатуру',

            'with_hint' => 'З підказкою',
            'block_goods_movement' => 'Блокувати рух товарів за об’єктами інвентаризації',

            'process_cell' => 'Опрацьовувати комірки',
            'without_restrictions' => 'Без обмежень',
            'empty_only' => 'Лише порожні',
            'filled_only' => 'Лише заповнені',

            'full' => 'Повна',
            'general_data' => 'Основна інформація',
        ],

        'edit' => [
            'create_inventory' => 'Редагувати інвентаризацію',
        ],

        'view' => [
            'title' => 'Перегляд інвентаризації',

            'breadcrumb' => [
                'inventory' => 'Інвентаризація',
                'current' => 'Перегляд інвентаризації',
            ],

            'header_actions' => [
                'edit' => 'Редагувати',
                'deactivate' => 'Скасувати',
                'cancel' => 'Скасувати',
            ],

            'kind' => [
                'planned' => 'Плановий',
                'manual'  => 'Ручний'
            ],

            'inventory' => 'Інвентаризація',


            'no_name' => 'Немає',

            'edit_icon_tooltip' => 'Редагувати інвентаризацію',

            'status' => 'Статус',
            'send_to_work' => 'Передати в роботу',
            'pause' => 'Призупинити',
            'finish_early' => 'Завершити достроково',
            'finish_inventory' => 'Завершити інвентаризацію',

            'tabs' => [
                'review' => 'Огляд',
                'an_animal' => 'Звірка',
                'an_animal_erp' => 'Звірка ERP',
            ],

            'review' => [
                'main_info' => 'Основна інформація',
                'general_data' => 'Основна інформація',
                'full' => 'Повна',
                'partial' => 'Часткова',

                'type' => 'Тип інвентаризації:',
                'start_datetime' => 'Час і дата старту:',
                'end_datetime' => 'Час і дата завершення:',
                'terminal_stock' => 'Залишки на терміналі:',
                'with_a_hint' => 'З підказкою:',
                'show' => 'Показувати',
                'hide' => 'Приховати',
                'movement' => 'Рух товару:',
                'no_stop' => 'Без зупинки',
                'stop' => 'Зупинити',
                'comment' => 'Коментар:',
                'executors' => 'Виконавці:',
                'priority' => 'Пріоритет',
            ],

            'statuses' => [
                'created' => 'Створено',
                'pending' => 'До виконання',
                'in_progress' => 'В процесі виконання',
                'paused' => 'Призупинено',
                'finished' => 'Завершено',
                'in_progress_an_animal' => 'В процесі обліку',
                'finished_before' => 'Виконано',
                'cancelled' => 'Скасовано',
            ],

            'place' => [
                'title' => 'Місце проведення',
                'warehouse' => 'Склад:',
                'row' => 'Ряд:',
                'erp_warehouse' => 'Склад ERP:',
                'cell_range' => 'Діапазон комірок:',
                'zone' => 'Зона:',
                'process_cells' => 'Опрацьовувати комірки:',
                'only_empty' => 'Тільки порожні',
                'all' => 'Без обмежень',
                'only_full' => 'Лише заповнені',
                'sector' => 'Сектор:',
            ],

            'nomenclature' => [
                'title' => 'Номенклатура',
                'category' => 'Категорія/підкатегорія:',
                'tile' => 'Плитка',
                'supplier' => 'Постачальник:',
                'manufacturer' => 'Виробник:',
                'nomenclature' => 'Номенклатура:',
                'brand' => 'Бренд:',
            ],

            'all' => 'Усі',

            'an_animal' => [
                'results' => 'Результати звірки',
                'empty_text' => 'Після початку інвентаризації тут з’являться результати звірки.',
                'cells_progress' => ':completed/:total комірок', // динамічний текст
            ],

            'an_animal_erp' => [
                'compare' => 'Порівняти залишки з ERP',
                'text' => 'Порівняння залишків WMS та ERP доступне після звірки в WMS',
                'button' => 'Порівняти',
            ],

            'modal_delete' => [
                'title' => 'Скасування інвентаризації',
                'confirmation' => 'Ви дійсно впевнені, що хочете скасувати цю інвентаризацію?',
                'cancel' => 'Ні, залишити',
                'submit' => 'Так, скасувати',
            ],

            'correction_quantity' => [
                'title' => 'Коригування кількості',
                'label_count' => 'Кількість на залишках:',
                'placeholder_count' => 'Вкажіть кориговану кількість',
                'available_packages' => 'Паковання',
                'count_of_available_packages' => 'Кількість',
                'cancel' => 'Скасувати',
                'apply' => 'Застосувати',
            ],

            'modals' => [
                'venue' => 'Місце проведення',
                'leftovers' => 'Залишки',

                'empty_cell' => 'Комірка порожня',
                'add_leftovers_button' => 'Додати',

                'add_leftovers' => [
                    'title' => 'Додати залишок',
                    'product_params' => 'Номенклатура',
                    'batch' => [
                        'label' => 'Партія',
                        'placeholder' => 'Вкажіть партію',
                    ],
                    'condition' => [
                        'label' => 'Кондиція',
                        'placeholder' => 'Кондиція',
                        'option_dmg' => 'Пошкоджена',
                        'option_no_dmg' => 'Не пошкоджена',
                    ],
                    'expiration' => [
                        'label' => 'Термін придатності',
                        'placeholder' => 'Термін придатності',
                        'unit' => 'діб',
                    ],
                    'manufacture_date' => 'Дата виготовлення',
                    'bb_date' => 'Вжити до',
                    'quantity_section' => 'Кількість',
                    'packaging' => [
                        'label' => 'Паковання',
                        'placeholder' => 'Оберіть паковання',
                    ],
                    'quantity' => [
                        'label' => 'Кількість',
                        'placeholder' => 'Вкажіть кількість',
                    ],
                    'placement' => 'Розміщення',
                    'container' => [
                        'label' => 'Контейнер',
                        'placeholder' => 'Виберіть контейнер',
                    ],
                ],

                'edit_leftovers' => [
                    'title' => 'Редагувати залишок',
                ],

                'cancel' => [
                    'title' => 'Скасувати інвентаризацію № :id?',
                    'text' => 'Усі внесені дані буде видалено, а залишки на складі залишаться без змін.',
                    'confirm_button' => 'Скасувати',
                ],

                'end' => [
                    'title' => 'Завершити інвентаризацію №:id достроково?',

                    'text_warning' => 'Не проінвентаризовано :count. Вони не будуть враховані у звірці.',
                    'cell_one' => 'комірка',
                    'cell_few' => 'комірки',
                    'cell_many' => 'комірок',

                    'text_success' => 'Усі комірки проінвентаризовано. Дані будуть зафіксовані.',

                    'confirm_button' => 'Завершити достроково',
                ],

                'buttons' => [
                    'cancel' => 'Скасувати',
                    'confirm' => 'Підтвердити',
                    'cancel_action' => 'Відмінити',

                    'continue_inventory' => 'Продовжити',
                    'back' => 'Назад',
                ],
            ],

        ],

        'modal' => [
            'select_type_title' => 'Виберіть тип інвентаризації',
            'full_title' => 'Повна',
            'full_description' => 'Звірка всіх залишків у межах усього складу',
            'partly_title' => 'Часткова',
            'partly_description' => 'Звірка залишків у вибраних ділянках складу або по вибраній номенклатурі',
        ],

        'cancel' => [
            'create' => [
                'modal' => [
                    'title' => 'Скасувати створення інвентаризації',
                    'content' => 'Ви точно впевнені що хочете вийти зі створення? <br> Внесені зміни не збережуться.',
                ],
            ],
            'edit' => [
                'modal' => [
                    'title' => 'Скасувати редагування інвентаризації',
                    'content' => 'Ви точно впевнені що хочете вийти із редагування? <br> Внесені зміни не збережуться.',

                ],
            ],

            'cancel_button' => 'Скасувати',
            'confirm_button' => 'Підтвердити',
        ],
    ],
];
