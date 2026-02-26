<?php

return [
    'tasks' => [
        'index' => [
            'title' => 'Tasks',
            'title_header' => 'Tasks',
            'add_button' => 'Add task',
            'empty_message' => 'You don’t have any tasks yet!',
            'add_prompt' => 'Add a new task',
            'add_button_text' => 'Add task',
        ],
        'create' => [
            'title' => 'Task',
            'titles'  => [
                'internal_displacement' => 'New task "Internal movement"',
            ],
            'create_tasks' => 'Create task',

            'main_information' => 'Main information',
            'warehouse' => 'Warehouse',
            'select_warehouse' => 'Select warehouse',

            'performer' => 'Performer',
            'choose_tasks_performers' => 'Select task performers',

            'kind' => 'Kind',
            'select_kind' => 'Select kind',

            'type' => 'Type',
            'select_type' => 'Select type',

            'priority' => 'Priority',

            'comment' => 'Comment',

            'leftovers' => 'Leftovers',

            'add_leftovers' => 'Add Leftovers',
            'save' => 'Save',

            'modal_add' => [
                'title' => 'Add Nomenclature',
                'search_placeholder' => 'Search',
                'category' => 'Category',
                'category_placeholder' => 'Category',
                'goods' => 'Goods',
                'goods_placeholder' => 'Goods',
                'search_button' => 'Search',
                'sku' => 'Nomenclature',
                'name' => 'Name',
                'barcode' => 'Barcode',
                'brand' => 'Brand',
                'supplier' => 'Supplier',
                'supplier_name' => 'Supplier name',
                'manufacturer' => 'Manufacturer',
                'manufacturer_name' => 'Manufacturer',
                'country' => 'Country of origin',
                'ukraine' => 'Ukraine',
                'party' => 'Batch',
                'select_party' => 'Select batch',
                'manufactured_date' => 'Manufactured date',
                'bb_date' => 'Best before',
                'choose_date' => 'Choose date',
                'quantity' => 'Quantity',
                'quantity_placeholder' => 'Enter product quantity',
                'add_button' => 'Add',
            ],
        ],

        'edit' => [
            'create_inventory' => 'Edit task',
        ],

        'view' => [
            'title' => 'View Task',

            'breadcrumb' => [
                'tasks' => 'Tasks',
                'current' => 'View Task',
            ],

            'actions' => [
                'cancel_task' => 'Cancel tasks',
            ],

            'tasks' => 'Internal Transfer',

            'no_name' => 'None',

            'edit_icon_tooltip' => 'Edit Task',

            'main_info' => 'Main Information',
            'warehouse' => 'Warehouse:',
            'place_to' => 'Placement Location',
            'place_placement' => 'Placement Location',
            'place_from' => 'Source Location',
            'executors' => 'Executors:',
            'all' => 'All',
            'add_executor' => 'Add Executor',
            'executor_label' => 'Executor',
            'executor_placeholder' => 'Select Executor',
            'type' => 'Type:',
            'priority' => 'Priority',
            'send_to_work' => 'Transfer to terminal',
            'status' => 'Status',
            'pause' => 'Pause',
            'finish_early' => 'Finish Early',
            'finish_tasks' => 'Complete Task',
            'nomenclature' => 'Nomenclature',
            'leftovers_info' => 'Leftovers',
            'goods' => 'Goods',
            'leftovers_empty' => 'No leftovers from terminal',
            'modal_delete' => [
                'title' => 'Deactivate Task',
                'confirmation' => 'Are you sure you want to deactivate this task?',
                'cancel' => 'Cancel',
                'submit' => 'Deactivate',
            ],
            'modal_delete_confirm' => 'Deactivate',
            'executor' => [
                'remove_executor' => 'Remove Executor',
            ],
            'close' => 'Close',
            'cancel' => [
                'cancel_button' => 'Cancel',
            ],
        ],

        'modal' => [
            'title' => 'Select task type',
            'desc' => 'The task type determines its purpose and impact on stock',
            'full_title' => 'Internal transfer',
            'full_description' => 'Increases stock quantities in the warehouse',
            'simple_title' => 'Simple task',
            'simple_description' => 'Does not affect stock quantities',
        ],

        'cancel' => [
            'create' => [
                'modal' => [
                    'title' => 'Cancel task creation',
                    'content' => 'Are you sure you want to exit creation? <br> Changes will not be saved.',
                ],
            ],
            'edit' => [
                'modal' => [
                    'title' => 'Cancel task editing',
                    'content' => 'Are you sure you want to exit editing? <br> Changes will not be saved.',
                ],
            ],

            'cancel_button' => 'Cancel',
            'confirm_button' => 'Confirm',
        ],
    ],
];
