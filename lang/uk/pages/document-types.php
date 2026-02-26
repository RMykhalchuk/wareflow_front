<?php

return [
    'document_types' => [
        'create' => [
            'incoming' => 'Створення нового типу прихідного документу',
            'outgoing' => 'Створення нового типу розхідного документу',
            'internal' => 'Створення нового типу внутрішнього документу',
            'neutral' => 'Створення нового типу нейтрального документу',
        ],

        'edit' => [
            'incoming' => 'Редагування типу прихідного документу',
            'outgoing' => 'Редагування типу розхідного документу',
            'internal' => 'Редагування типу внутрішнього документу',
            'neutral' => 'Редагування типу нейтрального документу',
        ],


        'modal' => [
            'select_type_title' => 'Виберіть вид документа',
            'select_type_description' => 'Вид документа визначає його призначення та вплив на залишки',

            'incoming_title' => 'Прихідний',
            'incoming_description' => 'Збільшує кількість залишків на складі',

            'outgoing_title' => 'Розхідний',
            'outgoing_description' => 'Зменшує кількість залишків на складі',

            'internal_title' => 'Внутрішній',
            'internal_description' => 'Впливає на розміщення залишків, не впливає на їх кількість',

            'neutral_title' => 'Нейтральний',
            'neutral_description' => 'Не впливає на розміщення та кількість залишків',
        ],

        'tabs' => [
            'header' => 'Шапка',
            'documents' => 'Документи',
            'actions' => 'Дії',
            'tasks' => 'Завдання',
        ],

        'tasks' => [
            'descriptions' => [
                'unload' => 'Розвантаження транспорту',
                'accept' => 'Приймання товару на склад',
                'move_putaway' => 'Розміщення товару на склад',
                'move_replenishment' => 'Поповнення зони комплектації (3К)',
                'move_to_check' => 'Переміщення товару в зону контролю',
                'check' => 'Контроль відбору',
                'move_sorting' => 'Розподіл товару по контейнерах',
                'move_to_shipping' => 'Переміщення товарів у зону відвантаження',
                'ship' => 'Відвантаження клієнту',
                'pick' => 'Відбір товару',
                'move_internal' => 'Переміщення товарів усередині складу',
            ],
            'no_tasks_message' => 'Немає завдань',
        ],

        'documents' => [
            "add-field" => [
                'title' => 'Типи документів',
                'desc' => 'Перетягніть тип документу в блок ліворуч, щоб добавити його',
            ],
            "fields-list" => [
                'placeholder' => 'Перетягніть поле сюди щоб добавити можливість прикріпляти документи в цей тип документа',
            ]
        ],

        'action' => [
            "add-field" => [
                'title' => 'Всі дії',
                'desc' => 'Перетягніть дію в блок ліворуч щоб добавити його',

            ]
        ],


    ],

    // ---- Index
    'document_types_index_title' => 'Типи документів',
    'document_types_index_document_types' => 'Типи документів',
    'document_types_index_create_new' => 'Створити новий тип',
    'document_types_index_search_placeholder' => 'Пошук',
    'document_types_index_edit' => 'Редагувати',
    'document_types_index_archive' => 'Архівувати',
    'document_types_index_unarchive' => 'Розархівувати',
    'document_types_index_delete' => 'Видалити',

    'document_types_index_status_archieve' => 'Архів',
    'document_types_index_status_system' => 'Системний',
    'document_types_index_status_draft' => 'Чернетка',

    // ---- Create
    'document_types_create_title' => 'Створення типу документу',
    'document_types_create_breadcrumb_text_1' => 'Налаштування',
    'document_types_create_breadcrumb_text_2' => 'Створення типу документу',

    'document_types_create_save_draft' => 'Зберегти як чернетку',
    'document_types_create_save' => 'Зберегти',

    'document_types_create_name_placeholder' => 'Назва типу документу',
    'document_types_create_name_aria_label' => 'Назва типу документу',
    'document_types_create_valid_feedback' => 'Коректно',
    'document_types_create_invalid_feedback' => "Поле назва типу документу є обов'язковим для заповнення",

    'document_types_create_action_item_acordion_edit_title' => 'Редагування',
    'document_types_create_action_item_acordion_delete_title' => 'Видалення',
    'document_types_create_action_item_acordion_copy_title' => 'Копіювання',
    'document_types_create_action_item_acordion_carrying_out_title' => 'Проведення накладної',
    'document_types_create_action_item_acordion_action_title' => 'Дія з документом',
    'document_types_create_action_item_acordion_print_title' => 'Роздрук',

    'document_types_create_action_item_acordion_admin' => 'Адміністратор',
    'document_types_create_action_item_acordion_storekeeper' => 'Комірник',
    'document_types_create_action_item_acordion_driver' => 'Водій',
    'document_types_create_action_item_acordion_manager' => 'Менеджер',

    'document_types_create_add_new_fields_modal_header_title' => 'Створити кастомне поле',
    'document_types_create_add_new_fields_modal_header_subtitle' => 'Виберіть новий тип поля',

    'document_types_create_add_new_fields_modal_body_field_list_search_placeholder' => 'Напишіть назву типу поля',
    'document_types_create_add_new_fields_modal_body_field_list_title' => 'Типи',

    'document_types_create_add_new_fields_modal_body_field_list_type_text' => 'Текстове поле',
    'document_types_create_add_new_fields_modal_body_field_list_type_text_description' => 'Дозволяє вводити і редагувати текст.',

    'document_types_create_add_new_fields_modal_body_field_list_type_range' => 'Два текстові поля (діапазон)',
    'document_types_create_add_new_fields_modal_body_field_list_type_range_description' => 'Дозволяє вводити і редагувати текст для значень від і до.',

    'document_types_create_add_new_fields_modal_body_field_list_type_select' => 'Вибір значення зі списку',
    'document_types_create_add_new_fields_modal_body_field_list_type_select_description' => 'Дозволяє обрати одне значення зі списку.',

    'document_types_create_add_new_fields_modal_body_field_list_type_multiselect' => 'Вибір декількох значень зі списку',
    'document_types_create_add_new_fields_modal_body_field_list_type_multiselect_description' => 'Дозволяє обрати кілька значення зі списку.',

    'document_types_create_add_new_fields_modal_body_field_list_type_date' => 'Дата',
    'document_types_create_add_new_fields_modal_body_field_list_type_date_description' => 'Дозволяє вказати дату.',

    'document_types_create_add_new_fields_modal_body_field_list_type_date_range' => 'Період дат',
    'document_types_create_add_new_fields_modal_body_field_list_type_date_range_description' => 'Дозволяє вказати період дат від і до.',

    'document_types_create_add_new_fields_modal_body_field_list_type_date_time' => 'Дата і час',
    'document_types_create_add_new_fields_modal_body_field_list_type_date_time_description' => 'Дозволяє вказати дату та час.',

    'document_types_create_add_new_fields_modal_body_field_list_type_date_time_range' => 'Дата і часові рамки',
    'document_types_create_add_new_fields_modal_body_field_list_type_date_time_range_description' => 'Дозволяє вказати дату із часовим проміжком.',

    'document_types_create_add_new_fields_modal_body_field_list_type_time_range' => 'Часові рамки',
    'document_types_create_add_new_fields_modal_body_field_list_type_time_range_description' => 'Дозволяє вказати час від і до.',

    'document_types_create_add_new_fields_modal_body_field_list_type_switch' => 'Вмикач / вимикач значення',
    'document_types_create_add_new_fields_modal_body_field_list_type_switch_description' => 'Дозволяє робити вказану опцію активною або неактивною.',

    'document_types_create_add_new_fields_modal_body_field_list_type_upload_file' => 'Обрати файл',
    'document_types_create_add_new_fields_modal_body_field_list_type_upload_file_description' => 'Дозволяє завантажувати та вивантажувати файл.',

    'document_types_create_add_new_fields_modal_body_field_list_type_comment' => 'Коментар',
    'document_types_create_add_new_fields_modal_body_field_list_type_comment_description' => 'Дозволяє вводити і редагувати текст для примітки до документу.',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_text_title' => 'Назва поля',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_text_title_placeholder' => 'Вкажіть назву поля',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_text_desc' => 'Підказка',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_text_desc_placeholder' => 'Поясніть як користувачі можуть використовувати це поле',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_range_title' => 'Назва поля',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_range_title_placeholder' => 'Вкажіть назву поля',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_title' => 'Назва поля',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_title_placeholder' => 'Вкажіть назву поля',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_desc' => 'Підказка',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_desc_placeholder' => 'Поясніть як користувачі можуть використовувати це поле',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_directory' => 'Довідник',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_directory_placeholder' => 'Виберіть довідник для цього селекту',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_add_custom_options' => 'Додати власні опції',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_parameter' => 'Параметр',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_parameter_placeholder' => 'Вкажіть параметр',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_add' => 'Додати',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_add_directory' => 'Додати довідник',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_label_title' => 'Назва поля',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_label_title_placeholder' => 'Вкажіть назву поля',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_label_desc' => 'Підказка',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_label_desc_placeholder' => 'Поясніть як користувачі можуть використовувати це поле',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_label_directory' => 'Довідник',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_label_directory_placeholder' => 'Виберіть довідник для цього селекту',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_title' => 'Назва поля',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_title_placeholder' => 'Вкажіть назву поля',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_desc' => 'Підказка',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_desc_placeholder' => 'Поясніть як користувачі можуть використовувати це поле',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_range_title' => 'Назва поля',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_range_title_placeholder' => 'Вкажіть назву поля',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_time_title' => 'Назва поля',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_time_title_placeholder' => 'Вкажіть назву поля',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_time_range_title' => 'Назва поля',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_time_range_title_placeholder' => 'Вкажіть назву поля',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_time_range_title' => 'Назва поля',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_time_range_title_placeholder' => 'Вкажіть назву поля',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_switch_title' => 'Назва поля',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_switch_title_placeholder' => 'Вкажіть назву поля',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_upload_file_title' => 'Назва поля',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_upload_file_title_placeholder' => 'Вкажіть назву поля',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_comment_title' => 'Назва поля',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_comment_title_placeholder' => 'Вкажіть назву поля',

    'document_types_create_add_new_fields_modal_footer_next' => 'Далі',
    'document_types_create_add_new_fields_modal_footer_create' => 'Створити',
    'document_types_create_add_new_fields_modal_footer_back' => 'Назад',

    'document_types_create_document_fields_empty_header_error' => 'Введіть дані у полі "Основні дані"',
    'document_types_create_document_fields_main_info' => 'Основна інформація',
    'document_types_create_document_fields_add_block' => 'Додати новий блок',
    'document_types_create_document_fields_product' => 'Товар',
    'document_types_create_document_fields_category' => 'Категорія',
    'document_types_create_document_fields_service' => 'Послуга',
    'document_types_create_document_fields_name' => 'Найменування',
    'document_types_create_document_fields_quantity' => 'Кількість',
    'document_types_create_document_fields_container' => 'Тара',
    'document_types_create_document_fields_services' => 'Послуги',

    'document_types_create_document_fields' => 'Поля',
    'document_types_create_document_document' => 'Документи',
    'document_types_create_document_action' => 'Дії',

    'document_types_create_document_fields_drag_field' => 'Перетягніть поле в один з блоків ліворуч щоб добавити його',
    'document_types_create_document_fields_search_fields' => 'Пошук полів',
    'document_types_create_document_fields_create_field' => 'Створити поле',
    'document_types_create_document_fields_text_fields' => 'Текстові поля',
    'document_types_create_document_fields_date_time_fields' => 'Поля дати і часу',
    'document_types_create_document_fields_selection_fields' => 'Поля вибору',
    'document_types_create_document_fields_other_fields' => 'Інші поля',
    'document_types_create_document_fields_create_custom_field' => 'Створити кастомне поле',
    'document_types_create_document_fields_drag_to_remove' => 'Перетягніть сюди, щоб <br> прибрати поле',
    'document_types_create_document_fields_fields_removed' => 'Поля які перенесені сюди, зможуть <br> бути використані для формування <br> документів',

    // ---- Edit
    'document_types_edit_title' => 'Редагування типу документу',

    'document_types_edit_breadcrumb_text_1' => 'Налаштування',
    'document_types_edit_breadcrumb_text_2' => 'Редагування типу документу',

    'document_types_edit_save_draft' => 'Зберегти як чернетку',
    'document_types_edit_save' => 'Зберегти',

    'document_types_edit_name_placeholder' => 'Назва типу документу',
    'document_types_edit_name_aria' => 'Назва типу документу',
    'document_types_edit_name_valid_feedback' => 'Коректно',
    'document_types_edit_name_invalid_feedback' => 'Поле назва типу документу є обов\'язковим для заповнення',

    'document_types_edit_add_new_fields_modal_header_title' => 'Створити нове поле',
    'document_types_edit_add_new_fields_modal_header_subtitle' => 'Виберіть новий тип поля',

    'document_types_edit_add_new_fields_modal_body_field_list_search_placeholder' => 'Напишіть назву типу поля',
    'document_types_edit_add_new_fields_modal_body_field_list_types' => 'Типи',
    'document_types_edit_add_new_fields_modal_body_field_list_text_field' => 'Текстове поле',
    'document_types_edit_add_new_fields_modal_body_field_list_text_field_description' => 'Дозволяє вводити і редагувати текст.',
    'document_types_edit_add_new_fields_modal_body_field_list_text_range_field' => 'Два текстові поля (діапазон)',
    'document_types_edit_add_new_fields_modal_body_field_list_text_range_field_description' => 'Дозволяє вводити і редагувати текст для значень від і до.',
    'document_types_edit_add_new_fields_modal_body_field_list_select_field' => 'Вибір значення зі списку',
    'document_types_edit_add_new_fields_modal_body_field_list_select_field_description' => 'Дозволяє обрати одне значення зі списку.',
    'document_types_edit_add_new_fields_modal_body_field_list_multiselect_field' => 'Вибір декількох значень зі списку',
    'document_types_edit_add_new_fields_modal_body_field_list_multiselect_field_description' => 'Дозволяє обрати кілька значення зі списку.',
    'document_types_edit_add_new_fields_modal_body_field_list_date_field' => 'Дата',
    'document_types_edit_add_new_fields_modal_body_field_list_date_field_description' => 'Дозволяє вказати дату.',
    'document_types_edit_add_new_fields_modal_body_field_list_date_range_field' => 'Період дат',
    'document_types_edit_add_new_fields_modal_body_field_list_date_range_field_description' => 'Дозволяє вказати період дат від і до.',
    'document_types_edit_add_new_fields_modal_body_field_list_date_time_field' => 'Дата і час',
    'document_types_edit_add_new_fields_modal_body_field_list_date_time_field_description' => 'Дозволяє вказати дату та час.',
    'document_types_edit_add_new_fields_modal_body_field_list_date_time_range_field' => 'Дата і часові рамки',
    'document_types_edit_add_new_fields_modal_body_field_list_date_time_range_field_description' => 'Дозволяє вказати дату із часовим проміжком.',
    'document_types_edit_add_new_fields_modal_body_field_list_time_range_field' => 'Часові рамки',
    'document_types_edit_add_new_fields_modal_body_field_list_time_range_field_description' => 'Дозволяє вказати час від і до.',
    'document_types_edit_add_new_fields_modal_body_field_list_switch_field' => 'Вмикач / вимикач значення',
    'document_types_edit_add_new_fields_modal_body_field_list_switch_field_description' => 'Дозволяє робити вказану опцію активною або неактивною.',
    'document_types_edit_add_new_fields_modal_body_field_list_upload_file_field' => 'Обрати файл',
    'document_types_edit_add_new_fields_modal_body_field_list_upload_file_field_description' => 'Дозволяє завантажувати та вивантажувати файл.',
    'document_types_edit_add_new_fields_modal_body_field_list_comment_field' => 'Коментар',
    'document_types_edit_add_new_fields_modal_body_field_list_comment_field_description' => 'Дозволяє вводити і редагувати текст для примітки до документу.',

    'document_types_edit_add_new_fields_modal_body_additional_settings_text_title' => 'Назва поля',
    'document_types_edit_add_new_fields_modal_body_additional_settings_text_title_placeholder' => 'Вкажіть назву поля',
    'document_types_edit_add_new_fields_modal_body_additional_settings_text_desc' => 'Підказка',
    'document_types_edit_add_new_fields_modal_body_additional_settings_text_desc_placeholder' => 'Поясніть як користувачі можуть використовувати це поле',

    'document_types_edit_add_new_fields_modal_body_additional_settings_range_title' => 'Назва поля',
    'document_types_edit_add_new_fields_modal_body_additional_settings_range_title_placeholder' => 'Вкажіть назву поля',

    'document_types_edit_add_new_fields_modal_body_additional_settings_select_title' => 'Назва поля',
    'document_types_edit_add_new_fields_modal_body_additional_settings_select_title_placeholder' => 'Вкажіть назву поля',
    'document_types_edit_add_new_fields_modal_body_additional_settings_select_desc' => 'Підказка',
    'document_types_edit_add_new_fields_modal_body_additional_settings_select_desc_placeholder' => 'Поясніть як користувачі можуть використовувати це поле',
    'document_types_edit_add_new_fields_modal_body_additional_settings_directory' => 'Довідник',
    'document_types_edit_add_new_fields_modal_body_additional_settings_directory_placeholder' => 'Виберіть довідник для цього селекту',
    'document_types_edit_add_new_fields_modal_body_additional_settings_add_custom_options' => 'Додати власні опції',
    'document_types_edit_add_new_fields_modal_body_additional_settings_parameter' => 'Параметр',
    'document_types_edit_add_new_fields_modal_body_additional_settings_parameter_placeholder' => 'Вкажіть параметр',
    'document_types_edit_add_new_fields_modal_body_additional_settings_add' => 'Додати',
    'document_types_edit_add_new_fields_modal_body_additional_settings_add_directory' => 'Додати довідник',

    'document_types_edit_add_new_fields_modal_body_additional_settings_label_title' => 'Назва поля',
    'document_types_edit_add_new_fields_modal_body_additional_settings_label_title_placeholder' => 'Вкажіть назву поля',
    'document_types_edit_add_new_fields_modal_body_additional_settings_label_desc' => 'Підказка',
    'document_types_edit_add_new_fields_modal_body_additional_settings_label_desc_placeholder' => 'Поясніть як користувачі можуть використовувати це поле',
    'document_types_edit_add_new_fields_modal_body_additional_settings_label_parameter' => 'Параметр',
    'document_types_edit_add_new_fields_modal_body_additional_settings_label_parameter_placeholder' => 'Виберіть довідник для цього селекту',

    'document_types_edit_add_new_fields_modal_body_additional_settings_date_title' => 'Назва поля',
    'document_types_edit_add_new_fields_modal_body_additional_settings_date_title_placeholder' => 'Вкажіть назву поля',
    'document_types_edit_add_new_fields_modal_body_additional_settings_date_desc' => 'Підказка',
    'document_types_edit_add_new_fields_modal_body_additional_settings_date_desc_placeholder' => 'Поясніть як користувачі можуть використовувати це поле',

    'document_types_edit_add_new_fields_modal_body_additional_settings_dateRange_title' => 'Назва поля',
    'document_types_edit_add_new_fields_modal_body_additional_settings_dateRange_title_placeholder' => 'Вкажіть назву поля',

    'document_types_edit_add_new_fields_modal_body_additional_settings_dateTime_title' => 'Назва поля',
    'document_types_edit_add_new_fields_modal_body_additional_settings_dateTime_title_placeholder' => 'Вкажіть назву поля',

    'document_types_edit_add_new_fields_modal_body_additional_settings_dateTimeRange_title' => 'Назва поля',
    'document_types_edit_add_new_fields_modal_body_additional_settings_dateTimeRange_title_placeholder' => 'Вкажіть назву поля',

    'document_types_edit_add_new_fields_modal_body_additional_settings_timeRange_title' => 'Назва поля',
    'document_types_edit_add_new_fields_modal_body_additional_settings_timeRange_title_placeholder' => 'Вкажіть назву поля',

    'document_types_edit_add_new_fields_modal_body_additional_settings_switch_title' => 'Назва поля',
    'document_types_edit_add_new_fields_modal_body_additional_settings_switch_title_placeholder' => 'Вкажіть назву поля',

    'document_types_edit_add_new_fields_modal_body_additional_settings_uploadFile_title' => 'Назва поля',
    'document_types_edit_add_new_fields_modal_body_additional_settings_uploadFile_title_placeholder' => 'Вкажіть назву поля',

    'document_types_edit_add_new_fields_modal_body_additional_settings_comment_title' => 'Назва поля',
    'document_types_edit_add_new_fields_modal_body_additional_settings_comment_title_placeholder' => 'Вкажіть назву поля',

    'document_types_edit_add_new_fields_modal_footer_next' => 'Далі',
    'document_types_edit_add_new_fields_modal_footer_create' => 'Створити',
    'document_types_edit_add_new_fields_modal_footer_back' => 'Назад',

    // Document Fields
    'document_types_edit_document_fields_empty_header_error' => 'Введіть дані у полі "Основні дані"',
    'document_types_edit_document_fields_main_information' => 'Основна інформація',
    'document_types_edit_document_fields_add_block' => 'Додати новий блок',

    'document_types_edit_document_fields_item_goods' => 'Товар',
    'document_types_edit_document_fields_container' => 'Тара',
    'document_types_edit_document_fields_services' => 'Послуги',

    'document_types_edit_document_fields_category' => 'Категорія',
    'document_types_edit_document_fields_name' => 'Найменування',
    'document_types_edit_document_fields_quantity' => 'Кількість',
    'document_types_edit_document_fields_service' => 'Послуга',

    'document_types_edit_document_fields_trash_title' => 'Перетягніть сюди, щоб <br> прибрати поле',
    'document_types_edit_document_fields_trash_description' => 'Поля які перенесені сюди, зможуть <br> бути використані для формування <br> документів',

    // ---- Update fields
    'document_types_update_fields_text_field_badge_numeric' => 'Тільки числове значення',
    'document_types_update_fields_text_field_badge_required' => 'Обовʼязкове',
    'document_types_update_fields_text_field_badge_system' => 'Системне',
    'document_types_update_fields_text_input_title' => 'Назва поля ',
    'document_types_update_fields_text_input_title_placeholder' => 'Назва поля приклад',
    'document_types_update_fields_text_input_title_valid_feedback' => 'Коректно',
    'document_types_update_fields_text_input_title_invalid_feedback' => 'Заповніть назву поля',
    'document_types_update_fields_text_input_description' => 'Підказка',
    'document_types_update_fields_text_input_description_placeholder' => 'Поясніть як користувачі можуть використовувати це поле',
    'document_types_update_fields_text_checkbox_numeric' => 'Тільки числове значення',
    'document_types_update_fields_text_checkbox_required' => 'Обовʼязкове',
    'document_types_update_fields_text_button_delete' => 'Видалити',

    'document_types_update_fields_range_field_badge_required' => 'Обовʼязкове',
    'document_types_update_fields_range_field_badge_system' => 'Системне',
    'document_types_update_fields_range_input_title' => 'Назва поля ',
    'document_types_update_fields_range_input_title_placeholder' => 'Назва поля приклад',
    'document_types_update_fields_range_input_title_valid_feedback' => 'Коректно',
    'document_types_update_fields_range_input_title_invalid_feedback' => 'Заповніть назву поля',
    'document_types_update_fields_range_checkbox_required' => 'Обовʼязкове',
    'document_types_update_fields_range_button_delete' => 'Видалити',

    'document_types_update_fields_date_field_badge_required' => 'Обовʼязкове',
    'document_types_update_fields_date_field_badge_system' => 'Системне',
    'document_types_update_fields_date_input_title' => 'Назва поля ',
    'document_types_update_fields_date_input_title_placeholder' => 'Назва поля приклад',
    'document_types_update_fields_date_input_title_valid_feedback' => 'Коректно',
    'document_types_update_fields_date_input_title_invalid_feedback' => 'Заповніть назву поля',
    'document_types_update_fields_date_input_hint' => 'Підказка',
    'document_types_update_fields_date_input_hint_placeholder' => 'Поясніть як користувачі можуть використовувати це поле',
    'document_types_update_fields_date_checkbox_required' => 'Обовʼязкове',
    'document_types_update_fields_date_button_delete' => 'Видалити',

    'document_types_update_fields_date_range_field_badge_required' => 'Обовʼязкове',
    'document_types_update_fields_date_range_field_badge_system' => 'Системне',
    'document_types_update_fields_date_range_input_title' => 'Назва поля ',
    'document_types_update_fields_date_range_input_title_placeholder' => 'Назва поля приклад',
    'document_types_update_fields_date_range_input_title_valid_feedback' => 'Коректно',
    'document_types_update_fields_date_range_input_title_invalid_feedback' => 'Заповніть назву поля',
    'document_types_update_fields_date_range_checkbox_required' => 'Обовʼязкове',
    'document_types_update_fields_date_range_button_delete' => 'Видалити',

    'document_types_update_fields_date_time_field_badge_required' => 'Обовʼязкове',
    'document_types_update_fields_date_time_field_badge_system' => 'Системне',
    'document_types_update_fields_date_time_input_title' => 'Назва поля ',
    'document_types_update_fields_date_time_input_title_placeholder' => 'Назва поля приклад',
    'document_types_update_fields_date_time_input_title_valid_feedback' => 'Коректно',
    'document_types_update_fields_date_time_input_title_invalid_feedback' => 'Заповніть назву поля',
    'document_types_update_fields_date_time_checkbox_required' => 'Обовʼязкове',
    'document_types_update_fields_date_time_button_delete' => 'Видалити',

    'document_types_update_fields_date_time_range_field_badge_required' => 'Обовʼязкове',
    'document_types_update_fields_date_time_range_field_badge_system' => 'Системне',
    'document_types_update_fields_date_time_range_input_title' => 'Назва поля ',
    'document_types_update_fields_date_time_range_input_title_placeholder' => 'Назва поля приклад',
    'document_types_update_fields_date_time_range_input_title_valid_feedback' => 'Коректно',
    'document_types_update_fields_date_time_range_input_title_invalid_feedback' => 'Заповніть назву поля',
    'document_types_update_fields_date_time_range_checkbox_required' => 'Обовʼязкове',
    'document_types_update_fields_date_time_range_button_delete' => 'Видалити',

    'document_types_update_fields_time_range_field_badge_required' => 'Обовʼязкове',
    'document_types_update_fields_time_range_field_badge_system' => 'Системне',
    'document_types_update_fields_time_range_input_title' => 'Назва поля ',
    'document_types_update_fields_time_range_input_title_placeholder' => 'Назва поля приклад',
    'document_types_update_fields_time_range_input_title_valid_feedback' => 'Коректно',
    'document_types_update_fields_time_range_input_title_invalid_feedback' => 'Заповніть назву поля',
    'document_types_update_fields_time_range_checkbox_required' => 'Обовʼязкове',
    'document_types_update_fields_time_range_button_delete' => 'Видалити',

    'document_types_update_fields_select_required' => 'Обовʼязкове',
    'document_types_update_fields_select_system' => 'Системне',
    'document_types_update_fields_select_field_name_label' => 'Назва поля',
    'document_types_update_fields_select_field_name_placeholder' => 'Назва поля приклад',
    'document_types_update_fields_select_field_name_valid_feedback' => 'Коректно',
    'document_types_update_fields_select_field_name_invalid_feedback' => 'Заповніть назву поля',
    'document_types_update_fields_select_hint_label' => 'Підказка',
    'document_types_update_fields_select_hint_placeholder' => 'Поясніть як користувачі можуть використовувати це поле',
    'document_types_update_fields_select_directory_label' => 'Довідник',
    'document_types_update_fields_select_directory_placeholder' => 'Виберіть довідник для цього селекту',
    'document_types_update_fields_select_add_custom_options_button' => 'Додати власні опції',
    'document_types_update_fields_select_parameter_label' => 'Параметр',
    'document_types_update_fields_select_parameter_placeholder' => 'Вкажіть параметр',
    'document_types_update_fields_select_add_button' => 'Додати',
    'document_types_update_fields_select_default_label' => 'За замовчуванням',
    'document_types_update_fields_select_add_directory_button' => 'Додати довідник',
    'document_types_update_fields_select_required_checkbox' => 'Обовʼязкове',
    'document_types_update_fields_select_remove_button' => 'Видалити',

    'document_types_update_fields_label_required' => 'Обовʼязкове',
    'document_types_update_fields_label_system' => 'Системне',
    'document_types_update_fields_label_field_name_label' => 'Назва поля',
    'document_types_update_fields_label_field_name_placeholder' => 'Назва поля приклад',
    'document_types_update_fields_label_field_name_valid_feedback' => 'Коректно',
    'document_types_update_fields_label_field_name_invalid_feedback' => 'Заповніть назву поля',
    'document_types_update_fields_label_hint_label' => 'Підказка',
    'document_types_update_fields_label_hint_placeholder' => 'Поясніть як користувачі можуть використовувати це поле',
    'document_types_update_fields_label_directory_label' => 'Довідник',
    'document_types_update_fields_label_directory_placeholder' => 'Виберіть довідник для цього селекту',
    'document_types_update_fields_label_required_checkbox' => 'Обовʼязкове',
    'document_types_update_fields_label_remove_button' => 'Видалити',

    'document_types_update_fields_switch_required' => 'Обовʼязкове',
    'document_types_update_fields_switch_field_name_label' => 'Назва поля',
    'document_types_update_fields_switch_field_name_placeholder' => 'Назва поля приклад',
    'document_types_update_fields_switch_field_name_invalid_feedback' => 'Заповніть назву поля',
    'document_types_update_fields_switch_required_checkbox' => 'Обовʼязкове',
    'document_types_update_fields_switch_remove_button' => 'Видалити',

    'document_types_update_fields_upload_file_required' => 'Обовʼязкове',
    'document_types_update_fields_upload_file_system' => 'Системне',
    'document_types_update_fields_upload_file_field_name_label' => 'Назва поля',
    'document_types_update_fields_upload_file_field_name_placeholder' => 'Назва поля приклад',
    'document_types_update_fields_upload_file_field_name_valid_feedback' => 'Коректно',
    'document_types_update_fields_upload_file_field_name_invalid_feedback' => 'Заповніть назву поля',
    'document_types_update_fields_upload_file_required_checkbox' => 'Обовʼязкове',
    'document_types_update_fields_upload_file_remove_button' => 'Видалити',

    'document_types_update_fields_comment_required' => 'Обовʼязкове',
    'document_types_update_fields_comment_system' => 'Системне',
    'document_types_update_fields_comment_field_name_label' => 'Назва поля',
    'document_types_update_fields_comment_field_name_placeholder' => 'Назва поля приклад',
    'document_types_update_fields_comment_field_name_valid_feedback' => 'Коректно',
    'document_types_update_fields_comment_field_name_invalid_feedback' => 'Заповніть назву поля',
    'document_types_update_fields_comment_required_checkbox' => 'Обовʼязкове',
    'document_types_update_fields_comment_remove_button' => 'Видалити',

    //dictionary
    'document_types_update_fields_dictionary_adr' => 'АДР',
    'document_types_update_fields_dictionary_cell_type' => 'Тип комірки',
    'document_types_update_fields_dictionary_cell_status' => 'Статус комірки',
    'document_types_update_fields_dictionary_company_status' => 'Статус компанії',
    'document_types_update_fields_dictionary_country' => 'Країна',
    'document_types_update_fields_dictionary_download_zone' => 'Зона завантаження',
    'document_types_update_fields_dictionary_measurement_unit' => 'Одиниці вимірювання',
    'document_types_update_fields_dictionary_package_type' => 'Тип пакування',
    'document_types_update_fields_dictionary_position' => 'Роль користувача',
    'document_types_update_fields_dictionary_settlement' => 'Місто',
    'document_types_update_fields_dictionary_street' => 'Вулиця',
    'document_types_update_fields_dictionary_storage_type' => 'Тип сховища',
    'document_types_update_fields_dictionary_transport_brand' => 'Бренд транспорту',
    'document_types_update_fields_dictionary_transport_download' => 'Тип завантаження',
    'document_types_update_fields_dictionary_transport_kind' => 'Вид транспорту',
    'document_types_update_fields_dictionary_transport_type' => 'Тип транспорту',
    'document_types_update_fields_dictionary_company' => 'Компанії',
    'document_types_update_fields_dictionary_warehouse' => 'Склад',
    'document_types_update_fields_dictionary_transport' => 'Транспорт',
    'document_types_update_fields_dictionary_additional_equipment' => 'Додаткове обладнання',
    'document_types_update_fields_dictionary_user' => 'Користувачі',
    'document_types_update_fields_dictionary_document_order' => 'Замовлення (документи)',
    'document_types_update_fields_dictionary_document_goods_invoice' => 'Товарна накладна (документи)',
    'document_types_update_fields_dictionary_currencies' => 'Валюта',
    'document_types_update_fields_dictionary_cargo_type' => 'Тип вантажу',
    'document_types_update_fields_dictionary_delivery_type' => 'Тип доставки',
    'document_types_update_fields_dictionary_basis_for_ttn' => 'Підстава для ТТН',

    // document_types_name_option
    'document_types_name_option_1' => 'Товарна накладна',
    'document_types_name_option_2' => 'Прихід від постачальника',
    'document_types_name_option_3' => 'Внутрішнє переміщення',
    'document_types_name_option_4' => 'Запит на транспорт',
    'document_types_name_option_5' => 'Запит на вантаж',
    'document_types_name_option_6' => 'Заявки на прийом',
    'document_types_name_option_7' => 'Заявка на відвантаження',
    'document_types_name_option_8' => 'ТТН',
    'document_types_name_option_9' => 'Замовлення',
    'document_types_name_option_10' => 'Списання',
];
