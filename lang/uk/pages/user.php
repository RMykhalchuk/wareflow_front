<?php

return [
    'user' => [
        'index' => [
            'title_table' => 'Користувачі',
            'title_page' => 'Користувачі',
            'btn_add_user' => 'Додати користувача',
            'toast_success_message' => 'Користувача успішно додано',
            'btn_send_email' => 'Відправити',
            'btn_copy' => 'Копіювати',
            'empty_list_message' => 'У вас ще немає жодного користувача!',
            'empty_list_submessage' => 'Як тільки користувача буде додано він буде відображатися тут',
            'modal_title' => 'Додайте людей до',
            'modal_subtitle' => 'Введіть електронну пошту або номер телефону щоб додати користувача.',
            'label_email' => 'Електронна адреса',
            'link_use_phone_number' => 'Використати номер телефону',
            'label_phone' => 'Телефон',
            'link_login_with_email' => 'Увійти використовуючи e-mail',
            'btn_cancel' => 'Скасувати',
            'btn_add' => 'Додати',
            'already_registered_message' => 'Якщо користувач вже зареєстрований в Wareflow просто виберіть його з списку.',
        ],

        'view' => [
            // ---- View
            'profile_title' => 'Профіль користувача',
            'users' => 'Користувачі',
            'view' => 'Перегляд',
            'deactivate' => 'Деактивувати',
            'online' => 'Онлайн',
            'offline' => 'Оффлайн',

            // Position
            'view_position_palet' => 'Палетувальник',
            'view_position_complect1' => 'Комплектувальник Бр 1',
            'view_position_complect2' => 'Комплектувальник Бр 2',
            'view_position_complect3' => 'Комплектувальник Бр 3',
            'view_position_complect4' => 'Комплектувальник Бр 4',
            'view_position_complect5' => 'Комплектувальник Бр 5',
            'view_position_driver' => 'Водій',
            'view_position_logist' => 'Логіст',
            'view_position_dispatcher' => 'Диспечер',
            'no_position' => 'Посада невідома',

            'personal_data' => 'Особисті дані',
            'birthday' => 'Дата народження',
            'email' => 'Електронна пошта',
            'phone' => 'Телефон',
            'gender' => 'Стать',
            'permit' => 'Перепустка',
            'female' => 'Жіноча',
            'male' => 'Чоловіча',
            'working_data' => 'Робочі дані',
            'company' => 'Компанія',
            'role' => 'Роль в системі',

            // Role
            'role_system_administrator' => 'Системний адміністратор',
            'role_administrator' => 'Адміністратор WMS',
            'role_user' => 'Користувач',
            'role_logistics' => 'Логіст',
            'role_dispatcher' => 'Диспетчер',

            'warehouses' => 'Склад',
            'all' => 'Усі',

            'no_data' => 'Немає даних',
            'driver_license_number' => 'Номер посвідчення водія',
            'driver_license_expiry' => 'Термін дії посвідчення водія',
            'expires' => 'До',
            'driver_license' => 'Посвідчення водія',
            'health_book_number' => 'Номер особової медичної книжки',
            'health_book_expiry' => 'Термін дії особової медичної книжки',
            'health_book' => 'Особова медична книжка',

        ],

        'create' => [

            'title' => 'Створення користувача',

            'breadcrumb' => [
                'users' => 'Користувачі',
                'title' => 'Створення користувача',

            ],

            'x_title' => 'Додавання нового користувача',

            //Step 1
            'personal_data_title' => 'Особисті дані',
            'last_name' => 'Прізвище',
            'last_name_placeholder' => 'Вкажіть ваше прізвище',
            'last_name_required' => 'Будь ласка, введіть прізвище',
            'first_name' => 'Ім’я',
            'first_name_placeholder' => 'Вкажіть ваше ім’я',
            'first_name_required' => 'Будь ласка, введіть ім’я',
            'patronymic' => 'По батькові',
            'patronymic_placeholder' => 'Вкажіть ваше ім’я по батькові',
            'patronymic_required' => 'Будь ласка, введіть ім’я по батькові',
            'birthday_create' => 'Дата народження',
            'birthday_placeholder' => 'РРРР-ММ-ДД',
            'email_create' => 'Адреса електронної пошти',
            'email_placeholder' => 'example@gmail.com',
            'email_required' => 'Будь ласка, введіть адресу електронної пошти',
            'phone_create' => 'Номер телефону',
            'phone_placeholder' => '+380666666666',
            'phone_required' => 'Будь ласка, введіть номер телефону',
            'sex' => 'Стать',
            'sex_placeholder' => 'Оберіть стать',
            'sex_male' => 'Чоловік',
            'sex_female' => 'Жінка',
            'password' => 'Тимчасовий пароль',
            'password_placeholder' => 'Придумайте тимчасовий пароль',
            'generate_password' => 'Згенерувати',

            //Step 2
            'working_data' => 'Робочі дані',
            'company_label' => 'Компанія',
            'select_company' => 'Оберіть компанію, до якої належить користувач',
            'system_role' => 'Роль в системі',
            'select_role' => 'Оберіть роль',
            'create_role_system_administrator' => 'Системний адміністратор',
            'create_role_administrator' => 'Адміністратор WMS',
            'create_role_user' => 'Користувач',
            'create_role_logistics' => 'Логіст',
            'create_role_dispatcher' => 'Диспетчер',
            'company_position' => 'Посада в компанії',
            'select_position' => 'Оберіть посаду',
            'create_position_palet' => 'Палетувальник',
            'create_position_complect1' => 'Комплектувальник Бр 1',
            'create_position_complect2' => 'Комплектувальник Бр 2',
            'create_position_complect3' => 'Комплектувальник Бр 3',
            'create_position_complect4' => 'Комплектувальник Бр 4',
            'create_position_complect5' => 'Комплектувальник Бр 5',

            'warehouses' => 'Склад',
            'select_warehouses' => 'Оберіть склад',
            'all_warehouses' => 'Усі',

            'pin-title' => 'Пін код',
            'pin-desc' => 'Використовується для спрощеного входу в термінал',

            'create_position_driver' => 'Водій',
            'create_position_logist' => 'Логіст',
            'create_position_dispatcher' => 'Диспечер',
            'driver_license_number_label' => 'Номер посвідчення водія',
            'driver_license_placeholder' => 'ААА000000',
            'driver_license_required' => 'Введіть номер',
            'driver_license_term' => 'Термін дії водійського посвідчення',
            'driver_license_date_format' => 'РРРР.ММ.ДД',
            'upload_driver_license' => 'Завантажити посвідчення водія',
            'driver_license_select_file' => 'Вибрати файл',
            'health_book_number_label' => 'Номер особової медичної книжки',
            'health_book_placeholder' => '000000',
            'health_book_required' => 'Введіть номер ',
            'health_book_term' => 'Термін дії особової медичної книжки',
            'health_book_date_format' => 'РРРР.ММ.ДД',
            'upload_health_book' => 'Завантажити санітарну книжку',
            'health_book_select_file' => 'Вибрати файл',

            'cancel_modal' => [
                'title' => 'Скасувати створення користувача',
                'confirmation' => 'Ви точно впевнені що хочете вийти з створення? <br> Внесені зміни не збережуться.',
                'cancel' => 'Скасувати',
                'submit' => 'Підтвердити',
            ],

            'btn_add_user' => 'Додати користувача',

        ],

        'edit' => [
            'title' => 'Редагування користувача',

            'breadcrumb' => [
                'users' => 'Користувачі',
                'view_profile' => 'Перегляд',
                'edit_profile' => 'Редагування профілю користувача',
            ],

            'x_title' => 'Редагування профілю користувача',

            'cancel_modal' => [
                'title' => 'Скасувати редагування користувача',
                'confirmation' => 'Ви точно впевнені що хочете вийти з редагування? <br> Внесені зміни не збережуться.',
                'cancel' => 'Скасувати',
                'submit' => 'Підтвердити',
            ],

            'change_password' => 'Змінити пароль',

            'password_modal' => [
                'title' => 'Змінити пароль',

                'old_password' => 'Старий пароль',
                'enter_old_password' => 'Введіть ваш старий пароль',

                'new_password' => 'Новий пароль',
                'enter_new_password' => 'Придумайте новий пароль',
                'confirm_new_password' => 'Повторіть новий пароль',

                'cancel' => 'Скасувати',
                'change' => 'Змінити пароль',
            ],

            'save' => 'Зберегти',

        ],
    ],
];
