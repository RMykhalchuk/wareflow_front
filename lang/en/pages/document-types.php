<?php

return [
    'document_types' => [
        'create' => [
            'incoming' => 'Creation of a new incoming document type',
            'outgoing' => 'Creation of a new outgoing document type',
            'internal' => 'Creation of a new internal document type',
            'neutral' => 'Creation of a new neutral document type',
        ],

        'edit' => [
            'incoming' => 'Editing type of incoming document',
            'outgoing' => 'Editing type of outgoing document',
            'internal' => 'Editing type of internal document',
            'neutral' => 'Editing type of neutral document',
        ],

        'modal' => [
            'select_type_title' => 'Select document type',
            'select_type_description' => 'The document type determines its purpose and impact on stock',

            'incoming_title' => 'Incoming',
            'incoming_description' => 'Increases the stock quantity in the warehouse',

            'outgoing_title' => 'Outgoing',
            'outgoing_description' => 'Decreases the stock quantity in the warehouse',

            'internal_title' => 'Internal',
            'internal_description' => 'Affects stock placement, does not affect stock quantity',

            'neutral_title' => 'Neutral',
            'neutral_description' => 'Does not affect stock placement or stock quantity',
        ],

        'tabs' => [
            'header' => 'Header',
            'documents' => 'Documents',
            'actions' => 'Actions',
            'tasks' => 'Tasks',
        ],

        'tasks' => [
            'descriptions' => [
                'unload' => 'Vehicle unloading',
                'accept' => 'Goods receiving',
                'move_putaway' => 'Putaway to warehouse',
                'move_replenishment' => 'Replenishment of picking area (3P)',
                'move_to_check' => 'Move goods to inspection area',
                'check' => 'Picking inspection',
                'move_sorting' => 'Sorting goods into containers',
                'move_to_shipping' => 'Move goods to shipping area',
                'ship' => 'Customer shipment',
                'pick' => 'Selection of goods',
                'move_internal' => 'Internal warehouse movement',
            ],
            'no_tasks_message' => 'No tasks available',
        ],

        'documents' => [
            "add-field" => [
                'title' => 'Types of documents',
                'desc' => 'Drag the document type to the block left to add it',
            ],
            "fields-list" => [
                'placeholder' => 'Drag the field here to add the ability to attach documents to this type of document',
            ]
        ],

        'action' => [
            "add-field" => [
                'title' => 'All the actions',
                'desc' => 'Drag the action into the block left to add it',
            ]
        ],


    ],

    // ---- Index
    'document_types_index_title' => 'Types of documents',
    'document_types_index_document_types' => 'Document Types',
    'document_types_index_create_new' => 'Create New Type',
    'document_types_index_search_placeholder' => 'Search',
    'document_types_index_edit' => 'Edit',
    'document_types_index_archive' => 'Archive',
    'document_types_index_unarchive' => 'Unarchive',
    'document_types_index_delete' => 'Delete',

    'document_types_index_status_archieve' => 'Archive',
    'document_types_index_status_system' => 'System',
    'document_types_index_status_draft' => 'Draft',

    // ---- Create
    'document_types_create_title' => 'Create Document Type',
    'document_types_create_breadcrumb_text_1' => 'Settings',
    'document_types_create_breadcrumb_text_2' => 'Create Document Type',

    'document_types_create_header_title' => 'Create New Document Type',
    'document_types_create_save_draft' => 'Save as Draft',
    'document_types_create_save' => 'Save',

    'document_types_create_name_placeholder' => 'Document Type Name',
    'document_types_create_name_aria_label' => 'Document Type Name',
    'document_types_create_valid_feedback' => 'Valid',
    'document_types_create_invalid_feedback' => 'Document Type Name is required',

    'document_types_create_action_item_acordion_edit_title' => 'Edit',
    'document_types_create_action_item_acordion_delete_title' => 'Delete',
    'document_types_create_action_item_acordion_copy_title' => 'Copy',
    'document_types_create_action_item_acordion_carrying_out_title' => 'Conduct Invoice',
    'document_types_create_action_item_acordion_action_title' => 'Document Action',
    'document_types_create_action_item_acordion_print_title' => 'A print',

    'document_types_create_action_item_acordion_admin' => 'Administrator',
    'document_types_create_action_item_acordion_storekeeper' => 'Storekeeper',
    'document_types_create_action_item_acordion_driver' => 'Driver',
    'document_types_create_action_item_acordion_manager' => 'Manager',

    'document_types_create_add_new_fields_modal_header_title' => 'Create a custom field',
    'document_types_create_add_new_fields_modal_header_subtitle' => 'Select a New Field Type',

    'document_types_create_add_new_fields_modal_body_field_list_search_placeholder' => 'Enter field type name',
    'document_types_create_add_new_fields_modal_body_field_list_title' => 'Types',

    'document_types_create_add_new_fields_modal_body_field_list_type_text' => 'Text Field',
    'document_types_create_add_new_fields_modal_body_field_list_type_text_description' => 'Allows you to enter and edit text.',

    'document_types_create_add_new_fields_modal_body_field_list_type_range' => 'Two Text Fields (Range)',
    'document_types_create_add_new_fields_modal_body_field_list_type_range_description' => 'Allows you to enter and edit text for from-to values.',

    'document_types_create_add_new_fields_modal_body_field_list_type_select' => 'Select Value from List',
    'document_types_create_add_new_fields_modal_body_field_list_type_select_description' => 'Allows you to select one value from the list.',

    'document_types_create_add_new_fields_modal_body_field_list_type_multiselect' => 'Select Multiple Values from List',
    'document_types_create_add_new_fields_modal_body_field_list_type_multiselect_description' => 'Allows you to select multiple values from the list.',

    'document_types_create_add_new_fields_modal_body_field_list_type_date' => 'Date',
    'document_types_create_add_new_fields_modal_body_field_list_type_date_description' => 'Allows you to specify a date.',

    'document_types_create_add_new_fields_modal_body_field_list_type_date_range' => 'Date Range',
    'document_types_create_add_new_fields_modal_body_field_list_type_date_range_description' => 'Allows you to specify a date range from-to.',

    'document_types_create_add_new_fields_modal_body_field_list_type_date_time' => 'Date and Time',
    'document_types_create_add_new_fields_modal_body_field_list_type_date_time_description' => 'Allows you to specify a date and time.',

    'document_types_create_add_new_fields_modal_body_field_list_type_date_time_range' => 'Date and Time Range',
    'document_types_create_add_new_fields_modal_body_field_list_type_date_time_range_description' => 'Allows you to specify a date with a time range.',

    'document_types_create_add_new_fields_modal_body_field_list_type_time_range' => 'Time Range',
    'document_types_create_add_new_fields_modal_body_field_list_type_time_range_description' => 'Allows you to specify a time range from-to.',

    'document_types_create_add_new_fields_modal_body_field_list_type_switch' => 'Switch',
    'document_types_create_add_new_fields_modal_body_field_list_type_switch_description' => 'Allows you to toggle a specified option on or off.',

    'document_types_create_add_new_fields_modal_body_field_list_type_upload_file' => 'Upload File',
    'document_types_create_add_new_fields_modal_body_field_list_type_upload_file_description' => 'Allows you to upload and download a file.',

    'document_types_create_add_new_fields_modal_body_field_list_type_comment' => 'Comment',
    'document_types_create_add_new_fields_modal_body_field_list_type_comment_description' => 'Allows you to enter and edit text for document notes.',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_text_title' => 'Field Name',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_text_title_placeholder' => 'Enter field name',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_text_desc' => 'Hint',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_text_desc_placeholder' => 'Explain how users can use this field',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_range_title' => 'Field Name',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_range_title_placeholder' => 'Enter field name',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_title' => 'Field Name',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_title_placeholder' => 'Enter field name',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_desc' => 'Hint',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_desc_placeholder' => 'Explain how users can use this field',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_directory' => 'Directory',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_directory_placeholder' => 'Select directory for this select',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_add_custom_options' => 'Add Custom Options',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_parameter' => 'Parameter',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_parameter_placeholder' => 'Enter parameter',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_add' => 'Add',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_select_add_directory' => 'Add Directory',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_label_title' => 'Field Name',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_label_title_placeholder' => 'Enter field name',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_label_desc' => 'Hint',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_label_desc_placeholder' => 'Explain how users can use this field',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_label_directory' => 'Directory',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_label_directory_placeholder' => 'Select directory for this select',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_title' => 'Field Name',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_title_placeholder' => 'Enter field name',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_desc' => 'Hint',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_desc_placeholder' => 'Explain how users can use this field',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_range_title' => 'Field Name',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_range_title_placeholder' => 'Enter field name',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_time_title' => 'Field Name',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_time_title_placeholder' => 'Enter field name',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_time_range_title' => 'Field Name',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_date_time_range_title_placeholder' => 'Enter field name',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_time_range_title' => 'Field Name',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_time_range_title_placeholder' => 'Enter field name',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_switch_title' => 'Field Name',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_switch_title_placeholder' => 'Enter field name',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_upload_file_title' => 'Field Name',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_upload_file_title_placeholder' => 'Enter field name',

    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_comment_title' => 'Field Name',
    'document_types_create_add_new_fields_modal_body_additional_settings_field_type_comment_title_placeholder' => 'Enter field name',

    'document_types_create_add_new_fields_modal_footer_next' => 'Next',
    'document_types_create_add_new_fields_modal_footer_create' => 'Create',
    'document_types_create_add_new_fields_modal_footer_back' => 'Back',

    'document_types_create_document_fields_empty_header_error' => 'Enter data in the "Main Data" field',
    'document_types_create_document_fields_main_info' => 'Main Information',
    'document_types_create_document_fields_add_block' => 'Add a new block',
    'document_types_create_document_fields_product' => 'Product',
    'document_types_create_document_fields_category' => 'Category',
    'document_types_create_document_fields_service' => 'Service',
    'document_types_create_document_fields_name' => 'Name',
    'document_types_create_document_fields_quantity' => 'Quantity',
    'document_types_create_document_fields_container' => 'Container',
    'document_types_create_document_fields_services' => 'Services',

    'document_types_create_document_fields' => 'Fields',
    'document_types_create_document_document' => 'Documents',
    'document_types_create_document_action' => 'Action',

    'document_types_create_document_fields_drag_field' => 'Drag the field to one of the blocks on the left to add it',
    'document_types_create_document_fields_search_fields' => 'Search Fields',
    'document_types_create_document_fields_create_field' => 'Create Field',
    'document_types_create_document_fields_text_fields' => 'Text Fields',
    'document_types_create_document_fields_date_time_fields' => 'Date and Time Fields',
    'document_types_create_document_fields_selection_fields' => 'Selection Fields',
    'document_types_create_document_fields_other_fields' => 'Other Fields',
    'document_types_create_document_fields_create_custom_field' => 'Create Custom Field',
    'document_types_create_document_fields_drag_to_remove' => 'Drag here to <br> remove the field',
    'document_types_create_document_fields_fields_removed' => 'Fields moved here can be <br> used to create <br> documents',

    // ---- Edit
    'document_types_edit_title' => 'Edit Document Type',

    'document_types_edit_breadcrumb_text_1' => 'Settings',
    'document_types_edit_breadcrumb_text_2' => 'Edit Document Type',

    'document_types_edit_save_draft' => 'Save as Draft',
    'document_types_edit_save' => 'Save',

    'document_types_edit_name_placeholder' => 'Document Type Name',
    'document_types_edit_name_aria' => 'Document Type Name',
    'document_types_edit_name_valid_feedback' => 'Valid',
    'document_types_edit_name_invalid_feedback' => 'Document type name is required',

    'document_types_edit_add_new_fields_modal_header_title' => 'Create New Field',
    'document_types_edit_add_new_fields_modal_header_subtitle' => 'Select a new field type',

    'document_types_edit_add_new_fields_modal_body_field_list_search_placeholder' => 'Enter field type name',
    'document_types_edit_add_new_fields_modal_body_field_list_types' => 'Types',
    'document_types_edit_add_new_fields_modal_body_field_list_text_field' => 'Text Field',
    'document_types_edit_add_new_fields_modal_body_field_list_text_field_description' => 'Allows input and editing of text.',
    'document_types_edit_add_new_fields_modal_body_field_list_text_range_field' => 'Two Text Fields (Range)',
    'document_types_edit_add_new_fields_modal_body_field_list_text_range_field_description' => 'Allows input and editing of text for values from and to.',
    'document_types_edit_add_new_fields_modal_body_field_list_select_field' => 'Select Field',
    'document_types_edit_add_new_fields_modal_body_field_list_select_field_description' => 'Allows selection of one value from a list.',
    'document_types_edit_add_new_fields_modal_body_field_list_multiselect_field' => 'Multiselect Field',
    'document_types_edit_add_new_fields_modal_body_field_list_multiselect_field_description' => 'Allows selection of multiple values from a list.',
    'document_types_edit_add_new_fields_modal_body_field_list_date_field' => 'Date',
    'document_types_edit_add_new_fields_modal_body_field_list_date_field_description' => 'Allows selection of a date.',
    'document_types_edit_add_new_fields_modal_body_field_list_date_range_field' => 'Date Range',
    'document_types_edit_add_new_fields_modal_body_field_list_date_range_field_description' => 'Allows selection of a date range from and to.',
    'document_types_edit_add_new_fields_modal_body_field_list_date_time_field' => 'Date and Time',
    'document_types_edit_add_new_fields_modal_body_field_list_date_time_field_description' => 'Allows selection of a date and time.',
    'document_types_edit_add_new_fields_modal_body_field_list_date_time_range_field' => 'Date and Time Range',
    'document_types_edit_add_new_fields_modal_body_field_list_date_time_range_field_description' => 'Allows selection of a date with a time interval.',
    'document_types_edit_add_new_fields_modal_body_field_list_time_range_field' => 'Time Range',
    'document_types_edit_add_new_fields_modal_body_field_list_time_range_field_description' => 'Allows selection of a time range from and to.',
    'document_types_edit_add_new_fields_modal_body_field_list_switch_field' => 'Toggle Switch',
    'document_types_edit_add_new_fields_modal_body_field_list_switch_field_description' => 'Allows toggling an option on or off.',
    'document_types_edit_add_new_fields_modal_body_field_list_upload_file_field' => 'Upload File',
    'document_types_edit_add_new_fields_modal_body_field_list_upload_file_field_description' => 'Allows uploading and downloading a file.',
    'document_types_edit_add_new_fields_modal_body_field_list_comment_field' => 'Comment',
    'document_types_edit_add_new_fields_modal_body_field_list_comment_field_description' => 'Allows input and editing of text for document notes.',

    'document_types_edit_add_new_fields_modal_body_additional_settings_text_title' => 'Field Name',
    'document_types_edit_add_new_fields_modal_body_additional_settings_text_title_placeholder' => 'Enter field name',
    'document_types_edit_add_new_fields_modal_body_additional_settings_text_desc' => 'Hint',
    'document_types_edit_add_new_fields_modal_body_additional_settings_text_desc_placeholder' => 'Explain how users can use this field',

    'document_types_edit_add_new_fields_modal_body_additional_settings_range_title' => 'Field Name',
    'document_types_edit_add_new_fields_modal_body_additional_settings_range_title_placeholder' => 'Enter field name',

    'document_types_edit_add_new_fields_modal_body_additional_settings_select_title' => 'Field Name',
    'document_types_edit_add_new_fields_modal_body_additional_settings_select_title_placeholder' => 'Enter field name',
    'document_types_edit_add_new_fields_modal_body_additional_settings_select_desc' => 'Hint',
    'document_types_edit_add_new_fields_modal_body_additional_settings_select_desc_placeholder' => 'Explain how users can use this field',
    'document_types_edit_add_new_fields_modal_body_additional_settings_directory' => 'Directory',
    'document_types_edit_add_new_fields_modal_body_additional_settings_directory_placeholder' => 'Select directory for this select',
    'document_types_edit_add_new_fields_modal_body_additional_settings_add_custom_options' => 'Add custom options',
    'document_types_edit_add_new_fields_modal_body_additional_settings_parameter' => 'Parameter',
    'document_types_edit_add_new_fields_modal_body_additional_settings_parameter_placeholder' => 'Enter parameter',
    'document_types_edit_add_new_fields_modal_body_additional_settings_add' => 'Add',
    'document_types_edit_add_new_fields_modal_body_additional_settings_add_directory' => 'Add directory',

    'document_types_edit_add_new_fields_modal_body_additional_settings_label_title' => 'Field Name',
    'document_types_edit_add_new_fields_modal_body_additional_settings_label_title_placeholder' => 'Enter field name',
    'document_types_edit_add_new_fields_modal_body_additional_settings_label_desc' => 'Hint',
    'document_types_edit_add_new_fields_modal_body_additional_settings_label_desc_placeholder' => 'Explain how users can use this field',
    'document_types_edit_add_new_fields_modal_body_additional_settings_label_parameter' => 'Parameter',
    'document_types_edit_add_new_fields_modal_body_additional_settings_label_parameter_placeholder' => 'Select directory for this select',

    'document_types_edit_add_new_fields_modal_body_additional_settings_date_title' => 'Field Name',
    'document_types_edit_add_new_fields_modal_body_additional_settings_date_title_placeholder' => 'Enter field name',
    'document_types_edit_add_new_fields_modal_body_additional_settings_date_desc' => 'Hint',
    'document_types_edit_add_new_fields_modal_body_additional_settings_date_desc_placeholder' => 'Explain how users can use this field',

    'document_types_edit_add_new_fields_modal_body_additional_settings_dateRange_title' => 'Field Name',
    'document_types_edit_add_new_fields_modal_body_additional_settings_dateRange_title_placeholder' => 'Enter field name',

    'document_types_edit_add_new_fields_modal_body_additional_settings_dateTime_title' => 'Field Name',
    'document_types_edit_add_new_fields_modal_body_additional_settings_dateTime_title_placeholder' => 'Enter field name',

    'document_types_edit_add_new_fields_modal_body_additional_settings_dateTimeRange_title' => 'Field Name',
    'document_types_edit_add_new_fields_modal_body_additional_settings_dateTimeRange_title_placeholder' => 'Enter field name',

    'document_types_edit_add_new_fields_modal_body_additional_settings_timeRange_title' => 'Field Name',
    'document_types_edit_add_new_fields_modal_body_additional_settings_timeRange_title_placeholder' => 'Enter field name',

    'document_types_edit_add_new_fields_modal_body_additional_settings_switch_title' => 'Field Name',
    'document_types_edit_add_new_fields_modal_body_additional_settings_switch_title_placeholder' => 'Enter field name',

    'document_types_edit_add_new_fields_modal_body_additional_settings_uploadFile_title' => 'Field Name',
    'document_types_edit_add_new_fields_modal_body_additional_settings_uploadFile_title_placeholder' => 'Enter field name',

    'document_types_edit_add_new_fields_modal_body_additional_settings_comment_title' => 'Field Name',
    'document_types_edit_add_new_fields_modal_body_additional_settings_comment_title_placeholder' => 'Enter field name',

    'document_types_edit_add_new_fields_modal_footer_next' => 'Next',
    'document_types_edit_add_new_fields_modal_footer_create' => 'Create',
    'document_types_edit_add_new_fields_modal_footer_back' => 'Back',

    // Document Fields
    'document_types_edit_document_fields_empty_header_error' => 'Enter data in the "Main Information" field',
    'document_types_edit_document_fields_main_information' => 'Main Information',
    'document_types_edit_document_fields_add_block' => 'Add a new block',

    'document_types_edit_document_fields_item_goods' => 'Goods',
    'document_types_edit_document_fields_container' => 'Container',
    'document_types_edit_document_fields_services' => 'Services',

    'document_types_edit_document_fields_category' => 'Category',
    'document_types_edit_document_fields_name' => 'Name',
    'document_types_edit_document_fields_quantity' => 'Quantity',
    'document_types_edit_document_fields_service' => 'Service',

    'document_types_edit_document_fields_trash_title' => 'Drag here to <br> remove field',
    'document_types_edit_document_fields_trash_description' => 'Fields dragged here can <br> be used to generate <br> documents',

    // ---- Update fields
    'document_types_update_fields_text_field_badge_numeric' => 'Numeric value only',
    'document_types_update_fields_text_field_badge_required' => 'Required',
    'document_types_update_fields_text_field_badge_system' => 'System',
    'document_types_update_fields_text_input_title' => 'Field name',
    'document_types_update_fields_text_input_title_placeholder' => 'Field name example',
    'document_types_update_fields_text_input_title_valid_feedback' => 'Correct',
    'document_types_update_fields_text_input_title_invalid_feedback' => 'Fill in the field name',
    'document_types_update_fields_text_input_description' => 'Hint',
    'document_types_update_fields_text_input_description_placeholder' => 'Explain how users can use this field',
    'document_types_update_fields_text_checkbox_numeric' => 'Numeric value only',
    'document_types_update_fields_text_checkbox_required' => 'Required',
    'document_types_update_fields_text_button_delete' => 'Delete',

    'document_types_update_fields_range_field_badge_required' => 'Required',
    'document_types_update_fields_range_field_badge_system' => 'System',
    'document_types_update_fields_range_input_title' => 'Field name',
    'document_types_update_fields_range_input_title_placeholder' => 'Field name example',
    'document_types_update_fields_range_input_title_valid_feedback' => 'Correct',
    'document_types_update_fields_range_input_title_invalid_feedback' => 'Fill in the field name',
    'document_types_update_fields_range_checkbox_required' => 'Required',
    'document_types_update_fields_range_button_delete' => 'Delete',

    'document_types_update_fields_date_field_badge_required' => 'Required',
    'document_types_update_fields_date_field_badge_system' => 'System',
    'document_types_update_fields_date_input_title' => 'Field name',
    'document_types_update_fields_date_input_title_placeholder' => 'Field name example',
    'document_types_update_fields_date_input_title_valid_feedback' => 'Correct',
    'document_types_update_fields_date_input_title_invalid_feedback' => 'Fill in the field name',
    'document_types_update_fields_date_input_hint' => 'Hint',
    'document_types_update_fields_date_input_hint_placeholder' => 'Explain how users can use this field',
    'document_types_update_fields_date_checkbox_required' => 'Required',
    'document_types_update_fields_date_button_delete' => 'Delete',

    'document_types_update_fields_date_range_field_badge_required' => 'Required',
    'document_types_update_fields_date_range_field_badge_system' => 'System',
    'document_types_update_fields_date_range_input_title' => 'Field name',
    'document_types_update_fields_date_range_input_title_placeholder' => 'Field name example',
    'document_types_update_fields_date_range_input_title_valid_feedback' => 'Correct',
    'document_types_update_fields_date_range_input_title_invalid_feedback' => 'Fill in the field name',
    'document_types_update_fields_date_range_checkbox_required' => 'Required',
    'document_types_update_fields_date_range_button_delete' => 'Delete',

    'document_types_update_fields_date_time_field_badge_required' => 'Required',
    'document_types_update_fields_date_time_field_badge_system' => 'System',
    'document_types_update_fields_date_time_input_title' => 'Field name',
    'document_types_update_fields_date_time_input_title_placeholder' => 'Field name example',
    'document_types_update_fields_date_time_input_title_valid_feedback' => 'Correct',
    'document_types_update_fields_date_time_input_title_invalid_feedback' => 'Fill in the field name',
    'document_types_update_fields_date_time_checkbox_required' => 'Required',
    'document_types_update_fields_date_time_button_delete' => 'Delete',

    'document_types_update_fields_date_time_range_field_badge_required' => 'Required',
    'document_types_update_fields_date_time_range_field_badge_system' => 'System',
    'document_types_update_fields_date_time_range_input_title' => 'Field name',
    'document_types_update_fields_date_time_range_input_title_placeholder' => 'Field name example',
    'document_types_update_fields_date_time_range_input_title_valid_feedback' => 'Correct',
    'document_types_update_fields_date_time_range_input_title_invalid_feedback' => 'Fill in the field name',
    'document_types_update_fields_date_time_range_checkbox_required' => 'Required',
    'document_types_update_fields_date_time_range_button_delete' => 'Delete',

    'document_types_update_fields_time_range_field_badge_required' => 'Required',
    'document_types_update_fields_time_range_field_badge_system' => 'System',
    'document_types_update_fields_time_range_input_title' => 'Field name',
    'document_types_update_fields_time_range_input_title_placeholder' => 'Field name example',
    'document_types_update_fields_time_range_input_title_valid_feedback' => 'Correct',
    'document_types_update_fields_time_range_input_title_invalid_feedback' => 'Fill in the field name',
    'document_types_update_fields_time_range_checkbox_required' => 'Required',
    'document_types_update_fields_time_range_button_delete' => 'Delete',

    'document_types_update_fields_select_required' => 'Required',
    'document_types_update_fields_select_system' => 'System',
    'document_types_update_fields_select_field_name_label' => 'Field name',
    'document_types_update_fields_select_field_name_placeholder' => 'Field name example',
    'document_types_update_fields_select_field_name_valid_feedback' => 'Correct',
    'document_types_update_fields_select_field_name_invalid_feedback' => 'Fill in the field name',
    'document_types_update_fields_select_hint_label' => 'Hint',
    'document_types_update_fields_select_hint_placeholder' => 'Explain how users can use this field',
    'document_types_update_fields_select_directory_label' => 'Directory',
    'document_types_update_fields_select_directory_placeholder' => 'Select a directory for this select',
    'document_types_update_fields_select_add_custom_options_button' => 'Add custom options',
    'document_types_update_fields_select_parameter_label' => 'Parameter',
    'document_types_update_fields_select_parameter_placeholder' => 'Specify a parameter',
    'document_types_update_fields_select_add_button' => 'Add',
    'document_types_update_fields_select_default_label' => 'Default',
    'document_types_update_fields_select_add_directory_button' => 'Add directory',
    'document_types_update_fields_select_required_checkbox' => 'Required',
    'document_types_update_fields_select_remove_button' => 'Delete',

    'document_types_update_fields_label_required' => 'Required',
    'document_types_update_fields_label_system' => 'System',
    'document_types_update_fields_label_field_name_label' => 'Field name',
    'document_types_update_fields_label_field_name_placeholder' => 'Field name example',
    'document_types_update_fields_label_field_name_valid_feedback' => 'Correct',
    'document_types_update_fields_label_field_name_invalid_feedback' => 'Fill in the field name',
    'document_types_update_fields_label_hint_label' => 'Hint',
    'document_types_update_fields_label_hint_placeholder' => 'Explain how users can use this field',
    'document_types_update_fields_label_directory_label' => 'Directory',
    'document_types_update_fields_label_directory_placeholder' => 'Select a directory for this select',
    'document_types_update_fields_label_required_checkbox' => 'Required',
    'document_types_update_fields_label_remove_button' => 'Delete',

    'document_types_update_fields_switch_required' => 'Required',
    'document_types_update_fields_switch_field_name_label' => 'Field name',
    'document_types_update_fields_switch_field_name_placeholder' => 'Field name example',
    'document_types_update_fields_switch_field_name_invalid_feedback' => 'Fill in the field name',
    'document_types_update_fields_switch_required_checkbox' => 'Required',
    'document_types_update_fields_switch_remove_button' => 'Delete',

    'document_types_update_fields_upload_file_required' => 'Required',
    'document_types_update_fields_upload_file_system' => 'System',
    'document_types_update_fields_upload_file_field_name_label' => 'Field name',
    'document_types_update_fields_upload_file_field_name_placeholder' => 'Field name example',
    'document_types_update_fields_upload_file_field_name_valid_feedback' => 'Correct',
    'document_types_update_fields_upload_file_field_name_invalid_feedback' => 'Fill in the field name',
    'document_types_update_fields_upload_file_required_checkbox' => 'Required',
    'document_types_update_fields_upload_file_remove_button' => 'Delete',

    'document_types_update_fields_comment_required' => 'Required',
    'document_types_update_fields_comment_system' => 'System',
    'document_types_update_fields_comment_field_name_label' => 'Field name',
    'document_types_update_fields_comment_field_name_placeholder' => 'Field name example',
    'document_types_update_fields_comment_field_name_valid_feedback' => 'Correct',
    'document_types_update_fields_comment_field_name_invalid_feedback' => 'Fill in the field name',
    'document_types_update_fields_comment_required_checkbox' => 'Required',
    'document_types_update_fields_comment_remove_button' => 'Delete',

    //dictionary
    'document_types_update_fields_dictionary_adr' => 'ADR',
    'document_types_update_fields_dictionary_cell_type' => 'Cell Type',
    'document_types_update_fields_dictionary_cell_status' => 'Cell Status',
    'document_types_update_fields_dictionary_company_status' => 'Company Status',
    'document_types_update_fields_dictionary_country' => 'Country',
    'document_types_update_fields_dictionary_download_zone' => 'Download Zone',
    'document_types_update_fields_dictionary_measurement_unit' => 'Measurement Unit',
    'document_types_update_fields_dictionary_package_type' => 'Package Type',
    'document_types_update_fields_dictionary_position' => 'User Role',
    'document_types_update_fields_dictionary_settlement' => 'City',
    'document_types_update_fields_dictionary_street' => 'Street',
    'document_types_update_fields_dictionary_storage_type' => 'Storage Type',
    'document_types_update_fields_dictionary_transport_brand' => 'Transport Brand',
    'document_types_update_fields_dictionary_transport_download' => 'Loading Type',
    'document_types_update_fields_dictionary_transport_kind' => 'Transport Kind',
    'document_types_update_fields_dictionary_transport_type' => 'Transport Type',
    'document_types_update_fields_dictionary_company' => 'Companies',
    'document_types_update_fields_dictionary_warehouse' => 'Warehouse',
    'document_types_update_fields_dictionary_transport' => 'Transport',
    'document_types_update_fields_dictionary_additional_equipment' => 'Additional Equipment',
    'document_types_update_fields_dictionary_user' => 'Users',
    'document_types_update_fields_dictionary_document_order' => 'Order (Documents)',
    'document_types_update_fields_dictionary_document_goods_invoice' => 'Goods Invoice (Documents)',
    'document_types_update_fields_dictionary_currencies' => 'Currency',
    'document_types_update_fields_dictionary_cargo_type' => 'Cargo Type',
    'document_types_update_fields_dictionary_delivery_type' => 'Delivery Type',
    'document_types_update_fields_dictionary_basis_for_ttn' => 'Basis for TTN',

    // document_types_name_option
    'document_types_name_option_1' => 'Invoice',
    'document_types_name_option_2' => 'Supplier Arrival',
    'document_types_name_option_3' => 'Internal Transfer',
    'document_types_name_option_4' => 'Request for transportation',
    'document_types_name_option_5' => 'Cargo Request',
    'document_types_name_option_6' => 'Applications for cargo',
    'document_types_name_option_7' => 'Shipment Request',
    'document_types_name_option_8' => 'E-Waybill',
    'document_types_name_option_9' => 'Order',
    'document_types_name_option_10' => 'Debiting',
];
