<?php

return [
    'user' => [
        'index' => [
            'title_table' => 'Users',
            'title_page' => 'Users',
            'btn_add_user' => 'Add User',
            'toast_success_message' => 'User added successfully',
            'btn_send_email' => 'Send',
            'btn_copy' => 'Copy',
            'empty_list_message' => 'You do not have any users yet!',
            'empty_list_submessage' => 'Once a user is added, they will appear here',
            'modal_title' => 'Add People to',
            'modal_subtitle' => 'Enter email or phone number to add a user.',
            'label_email' => 'Email Address',
            'link_use_phone_number' => 'Use Phone Number',
            'label_phone' => 'Phone',
            'link_login_with_email' => 'Login using email',
            'btn_cancel' => 'Cancel',
            'btn_add' => 'Add',
            'already_registered_message' => 'If the user is already registered in Wareflow, simply select them from the list.',
        ],

        'view' => [
            'profile_title' => 'User Profile',
            'users' => 'Users',
            'view' => 'View',
            'deactivate' => 'Deactivate',
            'online' => 'Online',
            'offline' => 'Offline',

            // Position
            'view_position_palet' => 'Palletizer',
            'view_position_complect1' => 'Picker Br 1',
            'view_position_complect2' => 'Picker Br 2',
            'view_position_complect3' => 'Picker Br 3',
            'view_position_complect4' => 'Picker Br 4',
            'view_position_complect5' => 'Picker Br 5',
            'view_position_driver' => 'Driver',
            'view_position_logist' => 'Logistician',
            'view_position_dispatcher' => 'Dispatcher',
            'no_position' => 'Position Unknown',

            'personal_data' => 'Personal Data',
            'birthday' => 'Date of Birth',
            'email' => 'Email',
            'phone' => 'Phone',
            'gender' => 'Gender',
            'permit' => 'Permit',
            'female' => 'Female',
            'male' => 'Male',
            'working_data' => 'Work Data',
            'company' => 'Company',
            'role' => 'System Role',

            // Role
            'role_system_administrator' => 'System Administrator',
            'role_administrator' => 'WMS Administrator',
            'role_user' => 'User',
            'role_logistics' => 'Logistician',
            'role_dispatcher' => 'Dispatcher',

            'warehouses' => 'Warehouse',
            'all' => 'All',

            'no_data' => 'No Data',
            'driver_license_number' => 'Driver License Number',
            'driver_license_expiry' => 'Driver License Expiry',
            'expires' => 'Expires',
            'driver_license' => 'Driver License',
            'health_book_number' => 'Health Book Number',
            'health_book_expiry' => 'Health Book Expiry',
            'health_book' => 'Health Book',
        ],

        'create' => [

            'title' => 'Create User',

            'breadcrumb' => [
                'users' => 'Users',
                'title' => 'Create User',
            ],

            'x_title' => 'Add New User',

            //Step 1
            'personal_data_title' => 'Personal Data',
            'last_name' => 'Last Name',
            'last_name_placeholder' => 'Enter your last name',
            'last_name_required' => 'Please enter last name',
            'first_name' => 'First Name',
            'first_name_placeholder' => 'Enter your first name',
            'first_name_required' => 'Please enter first name',
            'patronymic' => 'Middle Name',
            'patronymic_placeholder' => 'Enter your middle name',
            'patronymic_required' => 'Please enter middle name',
            'birthday_create' => 'Date of Birth',
            'birthday_placeholder' => 'YYYY-MM-DD',
            'email_create' => 'Email Address',
            'email_placeholder' => 'example@gmail.com',
            'email_required' => 'Please enter email address',
            'phone_create' => 'Phone Number',
            'phone_placeholder' => '+380666666666',
            'phone_required' => 'Please enter phone number',
            'sex' => 'Gender',
            'sex_placeholder' => 'Select gender',
            'sex_male' => 'Male',
            'sex_female' => 'Female',
            'password' => 'Temporary Password',
            'password_placeholder' => 'Create a temporary password',
            'generate_password' => 'Generate',

            //Step 2
            'working_data' => 'Work Data',
            'company_label' => 'Company',
            'select_company' => 'Select the company the user belongs to',
            'system_role' => 'System Role',
            'select_role' => 'Select role',
            'create_role_system_administrator' => 'System Administrator',
            'create_role_administrator' => 'WMS Administrator',
            'create_role_user' => 'User',
            'create_role_logistics' => 'Logistician',
            'create_role_dispatcher' => 'Dispatcher',
            'company_position' => 'Position in Company',
            'select_position' => 'Select position',
            'create_position_palet' => 'Palletizer',
            'create_position_complect1' => 'Picker Br 1',
            'create_position_complect2' => 'Picker Br 2',
            'create_position_complect3' => 'Picker Br 3',
            'create_position_complect4' => 'Picker Br 4',
            'create_position_complect5' => 'Picker Br 5',

            'warehouses' => 'Warehouse',
            'select_warehouses' => 'Select Warehouse',
            'all_warehouses' => 'All',

            'pin-title' => 'PIN Code',
            'pin-desc' => 'Used for simplified terminal login',

            'create_position_driver' => 'Driver',
            'create_position_logist' => 'Logistician',
            'create_position_dispatcher' => 'Dispatcher',
            'driver_license_number_label' => 'Driver License Number',
            'driver_license_placeholder' => 'AAA000000',
            'driver_license_required' => 'Enter number',
            'driver_license_term' => 'Driver license validity',
            'driver_license_date_format' => 'YYYY.MM.DD',
            'upload_driver_license' => 'Upload Driver License',
            'driver_license_select_file' => 'Select File',
            'health_book_number_label' => 'Health Book Number',
            'health_book_placeholder' => '000000',
            'health_book_required' => 'Enter number',
            'health_book_term' => 'Health Book Validity',
            'health_book_date_format' => 'YYYY.MM.DD',
            'upload_health_book' => 'Upload Health Book',
            'health_book_select_file' => 'Select File',

            'cancel_modal' => [
                'title' => 'Cancel User Creation',
                'confirmation' => 'Are you sure you want to exit creation? <br> Changes will not be saved.',
                'cancel' => 'Cancel',
                'submit' => 'Confirm',
            ],

            'btn_add_user' => 'Add User',
        ],

        'edit' => [
            'title' => 'Edit User',

            'breadcrumb' => [
                'users' => 'Users',
                'view_profile' => 'View',
                'edit_profile' => 'Edit User Profile',
            ],

            'x_title' => 'Edit User Profile',

            'cancel_modal' => [
                'title' => 'Cancel User Editing',
                'confirmation' => 'Are you sure you want to exit editing? <br> Changes will not be saved.',
                'cancel' => 'Cancel',
                'submit' => 'Confirm',
            ],

            'change_password' => 'Change Password',

            'password_modal' => [
                'title' => 'Change Password',

                'old_password' => 'Old Password',
                'enter_old_password' => 'Enter your old password',

                'new_password' => 'New Password',
                'enter_new_password' => 'Create a new password',
                'confirm_new_password' => 'Confirm new password',

                'cancel' => 'Cancel',
                'change' => 'Change Password',
            ],

            'save' => 'Save',
        ],
    ],
];
