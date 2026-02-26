<?php

return [
    'warehouse' => [
        'zone_types_label' => 'Types',
        'zone_subtypes_label' => 'Subtypes',
        'zone_types' => [
            'storage' => 'Storage Area',
            'operation' => 'Operations Area'
        ],
        'zone_subtypes' => [
            'pallet_storage' => 'Pallet Storage',
            'box_storage' => 'Box Storage',
            'covered_warehouse' => 'Covered Warehouse',
            'outdoor_storage' => 'Outdoor Storage',
            'receiving' => 'Receiving',
            'picking' => 'Picking',
            'quality_control' => 'Quality Control',
            'shipping' => 'Shipping',
            'cooling' => 'Cooling',
            'repalletizing' => 'Repalletizing',
        ],
        'index' => [
            'title' => 'Warehouses',
            'title_header' => 'Warehouses',
            'add_location_button' => 'Add Warehouse',
            'empty_message' => 'You don\'t have any warehouses yet!',
            'add_location_prompt' => 'Add a new warehouse',
            'add_location_button_text' => 'Add Warehouse',
        ],

        'create' => [
            'title' => 'Create Warehouse',
            'breadcrumb' => [
                'warehouses' => 'Warehouses',
                'add' => 'Add Warehouse',
            ],
            'save_button' => 'Save',

            'block_1' => [
                'main_data_title' => 'Main Data',
                'name_label' => 'Warehouse Name',
                'name_placeholder' => 'Enter warehouse name',
                'location_label' => 'Location',
                'location_placeholder' => 'Select location',
                'erp_warehouse_label' => 'ERP Warehouse',
                'erp_warehouse_placeholder' => 'Select warehouse(s) in ERP system',
                'type_label' => 'Warehouse Type',
                'type_placeholder' => 'Select warehouse type',
            ],

            'block_3' => [
                'work_schedule_title' => 'Work Schedule',
                'work_hours_title' => 'Working Hours',
                'lunch_title' => 'Lunch',
                'weekend_title' => 'Weekends',
                'monday_label' => 'Mon',
                'tuesday_label' => 'Tue',
                'wednesday_label' => 'Wed',
                'thursday_label' => 'Thu',
                'friday_label' => 'Fri',
                'saturday_label' => 'Sat',
                'sunday_label' => 'Sun',
                'use_patterns_label' => 'Use Templates',
                'select_pattern_placeholder' => 'Select template',
                'save_pattern_checkbox_label' => 'Save this schedule as a template',
                'pattern_name_placeholder' => 'Enter template name',

                'special_conditions_title' => 'Special Conditions',
                'special_conditions_added_text' => 'Added special conditions to your schedule will be <br> displayed here',
                'add_special_condition_button' => 'Add',
            ],

            'add_condition_modal' => [
                'title' => 'Add Special Condition',
                'condition_name_label' => 'Condition Name',
                'condition_name_placeholder' => 'Select condition',
                'day_off' => 'Day Off',
                'hospital' => 'Sick Leave',
                'short_day' => 'Shortened Day',
                'holiday' => 'Public Holiday',
                'select_period_one_day_label' => 'One Day',
                'select_period_period_label' => 'Time Period',
                'one_day_placeholder' => 'YYYY-MM-DD',
                'date_1_placeholder' => 'YYYY-MM-DD',
                'date_2_placeholder' => 'YYYY-MM-DD',
                'work_schedule_label' => 'Work Day',
                'break_label' => 'Lunch',
                'time_placeholder' => '00:00',
                'cancel_button' => 'Cancel',
                'save_button' => 'Save',
            ],

            'edit_condition_modal' => [
                'title' => 'Edit Special Condition',
                'condition_name_label' => 'Condition Name',
                'condition_name_placeholder' => 'Select condition',
                'day_off' => 'Day Off',
                'hospital' => 'Sick Leave',
                'short_day' => 'Shortened Day',
                'holiday' => 'Public Holiday',
                'select_period_one_day_label' => 'One Day',
                'select_period_period_label' => 'Time Period',
                'one_day_placeholder' => 'YYYY-MM-DD',
                'date_1_placeholder' => 'YYYY-MM-DD',
                'date_2_placeholder' => 'YYYY-MM-DD',
                'work_schedule_label' => 'Work Day',
                'break_label' => 'Lunch',
                'time_placeholder' => '00:00',
                'cancel_button' => 'Cancel',
                'save_button' => 'Save',
            ],

            'cancel_modal' => [
                'title' => 'Cancel Warehouse Creation',
                'message' => 'Are you sure you want to cancel creating? <br> Changes will not be saved.',
            ]
        ],

        'edit' => [
            'title' => 'Edit Warehouse',
            'breadcrumb' => [
                'warehouse' => 'Warehouses',
                'view_warehouse' => 'View Warehouse :name',
                'edit_warehouse' => 'Edit Warehouse',
            ],
            'cancel_button' => 'Cancel',
            'save_button' => 'Save',

            'cancel_modal' => [
                'title' => 'Cancel Warehouse Editing',
                'message' => 'Are you sure you want to cancel editing? <br> Changes will not be saved.',
                'confirm_button' => 'Confirm',
                'cancel_button' => 'Cancel',
            ],

            'render_conditions_work_day' => 'Work Day',
            'block_3_render_conditions_lunch' => 'Lunch',
        ],

        'view' => [
            'title' => 'View Warehouse',
            'breadcrumb' => [
                'warehouses' => 'Warehouses',
                'current' => 'View Warehouse',
            ],
            'no_name' => 'None',
            'edit_icon_tooltip' => 'Edit',
            'modal_delete_confirm' => 'Deactivate',

            'modal_delete' => [
                'title' => 'Delete Warehouse',
                'confirmation' => 'Are you sure you want to delete this warehouse?',
                'cancel' => 'Cancel',
                'submit' => 'Delete',
            ],

            'main_data' => 'Main Data',
            'erp_warehouse' => 'ERP Warehouse',
            'type' => 'Type of warehouse',
            'components_warehouse' => 'Components of the warehouse',
            'map_warehouse' => 'Map of the warehouse',

            'add' => 'Add',

            'no_erp_warehouse' => 'None',
            'working_hours_title' => 'Work Schedule',

            'calendar_header_prev_button' => 'Previous Week',
            'calendar_header_next_button' => 'Next Week',
            'calendar_table_day_column' => 'Day',
            'calendar_table_workday_column' => 'Work Day',
            'calendar_table_lunch_column' => 'Lunch',
            'calendar_no_schedule' => 'None',
            'floor_cell' => 'Floor cell'
        ],

        // Separately for condition names (for convenience)
        'conditions' => [
            'day_off' => 'Day Off',
            'hospital' => 'Sick Leave',
            'short_day' => 'Shortened Day',
            'holiday' => 'Public Holiday',
        ],

        'zone' => [
            'title' => 'Zone',
            'components' => 'Warehouse components',
            'create_zone' => 'Create zone',
            'edit_zone' => 'Edit zone',
            'add_zone' => 'Add zone',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'save' => 'Save',
            'cancel' => 'Cancel',
            'name' => 'Name',
            'type' => 'Type',
            'subtype' => 'Subtype',
            'temperature_regime' => 'Temperature regime',
            'humidity' => 'Humidity',
            'color' => 'Zone color',
            'no' => 'None',
            'floor_cell' => 'Floor cell',
            'print_label' => 'Print label',
            'view_content' => 'View content',
            'print_action' => 'Print',

            'cancel_modal' => 'Exit warehouse components',
            'cancel_modal_content'=> 'Are you sure you want to exit warehouse components?',
            'cancel_modal_cancel'=> 'Cancel',
            'cancel_modal_confirm'=> 'Confirm',

            'modal' => [
                'add' => [
                    'header' => 'Create zone',
                    'name_label' => 'Name',
                    'color_label' => 'Color',
                    'temperature_label' => 'Temperature regime',
                    'humidity_label' => 'Humidity',
                    'save_button' => 'Save',
                    'cancel_button' => 'Cancel',
                ],
                'edit' => [
                    'header' => 'Edit zone',
                    'name_label' => 'Name',
                    'color_label' => 'Color',
                    'temperature_label' => 'Temperature regime',
                    'humidity_label' => 'Humidity',
                    'save_button' => 'Save',
                    'cancel_button' => 'Cancel',
                    'delete_button' => 'Delete',
                ],
                'print' => [
                    'header' => 'Print label',
                    'from_label' => 'Zone from',
                    'from_placeholder' => 'From',
                    'to_label' => 'Zone to',
                    'to_placeholder' => 'To',
                    'printer_label' => 'Printer',
                    'printer_placeholder' => 'Printer',
                    'cancel_button' => 'Cancel',
                    'print_button' => 'Print',
                    'loader_text' => 'Please wait, labels are being generated...',
                ],
            ],
        ],

        'sector' => [
            'title' => 'Sectors',
            'breadcrumb' => [
                'zone' => 'Zone',
            ],
            'create_sector' => 'Create sector',
            'edit_sector' => 'Edit sector',
            'add_sector' => 'Add sector',
            'color' => 'Sector color',
            'no' => 'None',
            'name' => 'Name',
            'code' => 'Code',
            'temperature_regime' => 'Temperature regime',
            'humidity' => 'Humidity',
            'floor_cell' => 'Floor cell',
            'print_label' => 'Print label',
            'view_content' => 'View content',
            'print_action' => 'Print',

            'cancel' => 'Cancel',
            'save' => 'Save',

            'modal' => [
                'add' => [
                    'header' => 'Create sector',
                    'name_label' => 'Name',
                    'color_label' => 'Sector color',
                    'temperature_label' => 'Temperature regime',
                    'humidity_label' => 'Humidity',
                ],
                'edit' => [
                    'header' => 'Edit sector',
                    'name_label' => 'Name',
                    'color_label' => 'Sector color',
                    'temperature_label' => 'Temperature regime',
                    'humidity_label' => 'Humidity',
                    'delete_button' => 'Delete',
                ],
                'print' => [
                    'header' => 'Print label',
                    'from_label' => 'Sector from',
                    'from_placeholder' => 'From',
                    'to_label' => 'Sector to',
                    'to_placeholder' => 'To',
                    'printer_label' => 'Printer',
                    'printer_placeholder' => 'Printer',
                    'cancel_button' => 'Cancel',
                    'print_button' => 'Print',
                    'loader_text' => 'Please wait, labels are being generated...',
                ],
            ],
        ],

        'row' => [
            'title' => 'Rows',
            'breadcrumb' => [
                'zone' => 'Zone',
                'sector' => 'Sector',
            ],
            'name' => 'Name',
            'code' => 'Code',
            'floors' => 'Floors',
            'racks' => 'Racks',
            'cells_in_rack' => 'Cells per rack',
            'create_row' => 'Create row',
            'edit_row' => 'Edit row',
            'add_row' => 'Add row',
            'number' => 'Number',
            'number_of_rows' => 'Number of rows',
            'number_of_racks' => 'Number of racks',
            'number_of_floors' => 'Number of floors',
            'number_of_cells' => 'Number of cells in rack',

            'cell_properties' => 'Cell properties',
            'weight_brutto' => 'Maximum weight',
            'height' => 'Height',
            'width' => 'Width',
            'length' => 'Length',

            'print_label' => 'Print label',
            'print_action' => 'Print',

            'unit' => [
                'kg' => 'kg',
                'cm' => 'cm',
            ],

            'print_modal' => [
                'header' => 'Print label',
                'rows_from_label' => 'Row from',
                'rows_from_placeholder' => 'From',
                'rows_to_label' => 'Row to',
                'rows_to_placeholder' => 'To',
                'printer_label' => 'Printer',
                'printer_placeholder' => 'Printer',
                'cancel_button' => 'Cancel',
                'print_button' => 'Print',
                'loader_text' => 'Please wait, labels are being generated...',
            ],
        ],

        'cells' => [
            'title' => 'Cells',
            'breadcrumb' => [
                'zone' => 'Zone',
                'sector' => 'Sector',
                'row' => 'Row',
            ],
            'name' => 'Name',
            'code' => 'Code',
            'type' => 'Type',
            'width' => 'Width',
            'height' => 'Height',
            'length' => 'Length',
            'max_weight' => 'Max weight',
            'edit_cell' => 'Edit cell',
            'select_type' => 'Select type',
            'apply_properties' => 'Apply properties to all following',

            'print_label' => 'Print label',
            'view_content' => 'View content',
            'print_action' => 'Print',

            'unit' => [
                'kg' => 'kg',
                'cm' => 'cm',
            ],

            'print_modal' => [
                'header' => 'Print label',
                'cells_from_label' => 'Cell from',
                'cells_from_placeholder' => 'From',
                'cells_to_label' => 'Cell to',
                'cells_to_placeholder' => 'To',
                'printer_label' => 'Printer',
                'printer_placeholder' => 'Printer',
                'cancel_button' => 'Cancel',
                'print_button' => 'Print',
                'loader_text' => 'Please wait, labels are being generated...',
            ],
        ]
    ],
];
