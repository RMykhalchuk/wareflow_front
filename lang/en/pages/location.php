<?php

return [
    'location' => [
        'index' => [
            'title' => 'Locations',
            'title_header' => 'Locations',
            'add_location_button' => 'Add location',
            'empty_message' => 'You don’t have any locations yet!',
            'add_location_prompt' => 'Add a new location',
            'add_location_button_text' => 'Add location',
        ],

        'create' => [
            'title' => 'Create location',
            'breadcrumb' => [
                'warehouses' => 'Locations',
                'add' => 'New location',
            ],
            'save_button' => 'Save',

            'page' => [
                'title' => 'Adding a new location',
            ],

            'block_1' => [
                'main_data_title' => 'Location data',
                'name_label' => 'The name of the location',
                'name_placeholder' => 'Enter the location name',
                'company_label' => 'Company',
                'company_placeholder' => 'Select a company',
                'country_label' => 'Country',
                'country_placeholder' => 'Select a country',
                'settlement_label' => 'Settlement',
                'settlement_placeholder' => 'Select a settlement',
                'street_label' => 'Street',
                'street_placeholder' => 'Select a street',
                'building_number_label' => 'Building number',
                'building_number_placeholder' => 'Enter building number',
            ],

            'block_2' => [
                'title' => 'Address',
                'coordinates_label' => 'Reference to Google Map',
                'coordinates_placeholder' => 'Specify the reference to Google Map',
            ],

            'cancel_modal' => [
                'title' => 'Cancel location creation',
                'confirmation' => 'Are you sure you want to cancel the create?',
                'cancel' => 'Cancel',
                'submit' => 'Confirm',
            ],
        ],

        'edit' => [
            'title' => 'Edit location',
            'breadcrumb' => [
                'warehouses' => 'Locations',
                'view_warehouse' => 'View location :name',
                'edit_warehouse' => 'Edit location',
            ],
            'cancel_modal' => [
                'title' => 'Cancel location editing',
                'message' => 'Are you sure you want to cancel the editing? <br> The changes made will not be saved.',
                'confirm_button' => 'Confirm',
                'cancel_button' => 'Cancel',
            ],
            'save_button' => 'Save',
        ],

        'view' => [
            'title' => 'View location',
            'breadcrumb' => [
                'warehouses' => 'Locations',
                'current' => 'View location',
            ],
            'no_name' => 'None',
            'edit_icon_tooltip' => 'Edit',
            'modal_delete_confirm' => 'Deactivate',
            'modal_delete' => [
                'title' => 'Delete location',
                'confirmation' => 'Are you sure you want to delete this location?',
                'cancel' => 'Cancel',
                'submit' => 'Delete',
            ],
            'main_data' => 'Address',
            'address' => 'Address',
        ],
    ],
];
