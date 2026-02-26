<?php

return [
    'inventory' => [
        'index' => [
            'title' => 'Inventory task',
            'title_header' => 'Inventory task',
            'add_location_button' => 'Create',
            'empty_message' => 'You don’t have any inventory tasks yet!',
            'add_location_prompt' => 'Create a new inventory task',
            'add_location_button_text' => 'Create',

            'title_manual' => 'Manual Inventory',
            'add_manual_inventory' => 'Add New Manual Inventory',

        ],
        'area' => [
            'web' => 'manual',
            'api' => 'terminal',
        ],
        'create' => [
            'title' => 'Inventory',
            'create_inventory' => 'Create inventory',

            'partial' => 'Partial',
            'main_information' => 'Main information',
            'show_leftovers_on_terminal' => 'Show leftovers on terminal',
            'restrict_goods_movement' => 'Block the movement of goods by inventory objects',
            'performer' => 'Performers',
            'choose_inventory_performers' => 'Choose inventory performers',
            'start_date' => 'Start date and time',
            'end_date' => 'End date and time',
            'comment' => 'Comment',
            'priority' => 'Priority',

            'all' => 'All',

            'location' => 'Location',
            'warehouse' => 'Warehouse',
            'select_warehouse' => 'Select warehouse',
            'warehouse_erp' => 'Warehouse ERP',
            'select_warehouse_erp' => 'Select warehouse ERP',
            'zone' => 'Zone',
            'sector' => 'Sector',
            'row' => 'Row',
            'cell' => 'Cell',
            'select_zone' => 'Select zone',
            'select_sector' => 'Select sector',
            'select_row' => 'Select row',
            'select_cell' => 'Select cell',

            'nomenclature' => 'Nomenclature',
            'category_subcategory' => 'Category/Subcategory',
            'manufacturer' => 'Manufacturer',
            'brand' => 'Brand',
            'supplier' => 'Supplier',

            'select_category_subcategory' => 'Select category/subcategory',
            'select_manufacturer' => 'Select manufacturer',
            'select_brand' => 'Select brand',
            'select_supplier' => 'Select supplier',
            'select_nomenclature' => 'Select nomenclature',

            'with_hint' => 'With hint',
            'block_goods_movement' => 'Block goods movement by inventory objects',

            'process_cell' => 'Process cell',
            'without_restrictions' => 'Without restrictions',
            'empty_only' => 'Empty only',
            'filled_only' => 'Filled only',

            'full' => 'Full',
            'general_data' => 'Main information',
        ],

        'edit' => [
            'create_inventory' => 'Edit the inventory',
        ],

        'view' => [
            'title' => 'Inventory View',

            'breadcrumb' => [
                'inventory' => 'Inventory',
                'current' => 'Inventory View',
            ],

            'header_actions' => [
                'edit' => 'Edit',
                'deactivate' => 'Cancel',
                'cancel' => 'Cancel',
            ],

            'kind' => [
                'planned' => 'Scheduled',
                'manual'  => 'Manual'
            ],

            'inventory' => 'Inventory',

            'no_name' => 'No name',

            'edit_icon_tooltip' => 'Edit inventory',

            'status' => 'Status',
            'send_to_work' => 'Send to work',
            'pause' => 'Pause',
            'finish_early' => 'Finish early',
            'finish_inventory' => 'Finish inventory',

            'tabs' => [
                'review' => 'Review',
                'an_animal' => 'Reconciliation',
                'an_animal_erp' => 'ERP Reconciliation',
            ],

            'review' => [
                'main_info' => 'Main information',
                'general_data' => 'Main information',
                'full' => 'Full',
                'partial' => 'Partial',

                'type' => 'Inventory type:',
                'start_datetime' => 'Start date and time:',
                'end_datetime' => 'End date and time:',
                'terminal_stock' => 'Terminal stock:',
                'with_a_hint' => 'With a hint:',
                'show' => 'Show',
                'hide' => 'Hide',
                'movement' => 'Product movement:',
                'no_stop' => 'No stop',
                'stop' => 'Stop',
                'comment' => 'Comment:',
                'executors' => 'Executors:',
                'priority' => 'Priority',
            ],

            'statuses' => [
                'created' => 'Created',
                'pending' => 'Pending',
                'in_progress' => 'In progress',
                'paused' => 'Paused',
                'finished' => 'Finished',
                'in_progress_an_animal' => 'Counting in progress',
                'finished_before' => 'Completed early',
                'cancelled' => 'Cancelled',
            ],

            'place' => [
                'title' => 'Location',
                'warehouse' => 'Warehouse:',
                'row' => 'Row:',
                'erp_warehouse' => 'ERP Warehouse:',
                'cell_range' => 'Cell range:',
                'zone' => 'Zone:',
                'process_cells' => 'Process cells:',
                'only_empty' => 'Only empty',
                'all' => 'No restrictions',
                'only_full' => 'Only full',
                'sector' => 'Sector:',
            ],

            'nomenclature' => [
                'title' => 'Nomenclature',
                'category' => 'Category/Subcategory:',
                'tile' => 'Tile',
                'supplier' => 'Supplier:',
                'manufacturer' => 'Manufacturer:',
                'nomenclature' => 'Nomenclature:',
                'brand' => 'Brand:',
            ],

            'all' => 'All',

            'an_animal' => [
                'results' => 'Reconciliation results',
                'empty_text' => 'After the inventory starts, reconciliation results will appear here.',
                'cells_progress' => ':completed/:total cells', // динамічний текст
            ],

            'an_animal_erp' => [
                'compare' => 'Compare stock with ERP',
                'text' => 'Comparison of WMS and ERP stock will be available after reconciliation in WMS',
                'button' => 'Compare',
            ],

            'modal_delete' => [
                'title' => 'Cancel inventory',
                'confirmation' => 'Are you really sure you want to cancel this inventory?',
                'cancel' => 'No, leave',
                'submit' => 'Yes, cancel',
            ],

            'correction_quantity' => [
                'title' => 'Quantity Correction',
                'label_count' => 'Stock quantity:',
                'placeholder_count' => 'Enter corrected quantity',
                'available_packages' => 'Packaging',
                'count_of_available_packages' => 'Quantity',
                'cancel' => 'Cancel',
                'apply' => 'Apply',
            ],

            'modals' => [
                'venue' => 'Venue',
                'leftovers' => 'Leftovers',

                'empty_cell' => 'Cell is empty',
                'add_leftovers_button' => 'Add',

                'add_leftovers' => [
                    'title' => 'Add leftover',
                    'product_params' => 'Nomenclature',
                    'batch' => [
                        'label' => 'Batch',
                        'placeholder' => 'Enter batch',
                    ],
                    'condition' => [
                        'label' => 'Condition',
                        'placeholder' => 'Condition',
                        'option_dmg' => 'Damaged',
                        'option_no_dmg' => 'Not damaged',
                    ],
                    'expiration' => [
                        'label' => 'Expiration date',
                        'placeholder' => 'Expiration date',
                        'unit' => 'days',
                    ],
                    'manufacture_date' => 'Manufacture date',
                    'bb_date' => 'Best before',
                    'quantity_section' => 'Quantity',
                    'packaging' => [
                        'label' => 'Packaging',
                        'placeholder' => 'Select packaging',
                    ],
                    'quantity' => [
                        'label' => 'Quantity',
                        'placeholder' => 'Enter quantity',
                    ],
                    'placement' => 'Placement',
                    'container' => [
                        'label' => 'Container',
                        'placeholder' => 'Select container',
                    ],
                ],

                'edit_leftovers' => [
                    'title' => 'Edit leftover',
                ],

                'cancel' => [
                    'title' => 'Cancel inventory № :id?',
                    'text' => 'All entered data will be deleted, and the stock quantities will remain unchanged.',
                    'confirm_button' => 'Cancel',
                ],

                'end' => [
                    'title' => 'Finish inventory #:id early?',

                    'text_warning' => ':count have not been inventoried. They will not be included in the reconciliation.',

                    'cell_one' => 'cell',
                    'cell_few' => 'cells',
                    'cell_many' => 'cells',

                    'text_success' => 'All cells have been inventoried. The data will be finalized.',

                    'confirm_button' => 'Finish early',
                ],

                'buttons' => [
                    'cancel' => 'Cancel',
                    'confirm' => 'Confirm',
                    'cancel_action' => 'Cancel',

                    'continue_inventory' => 'Continue Inventory',
                    'back' => 'Back',
                ],
            ],

        ],


        'modal' => [
            'select_type_title' => 'Select inventory type',
            'full_title' => 'Full',
            'full_description' => 'Verification of all stock balances across the entire warehouse',
            'partly_title' => 'Partial',
            'partly_description' => 'Verification of stock balances in selected warehouse areas or for selected items',
        ],

        'cancel' => [
            'create' => [
                'modal' => [
                    'title' => 'Cancel inventory creation',
                    'content' => 'Are you sure you want to exit creation? <br> The changes will not be saved.',
                ],
            ],
            'edit' => [
                'modal' => [
                    'title' => 'Cancel inventory editing',
                    'content' => 'Are you sure you want to exit editing? <br> The changes will not be saved.',
                ],
            ],

            'cancel_button' => 'Cancel',
            'confirm_button' => 'Confirm',
        ],

    ],
];
