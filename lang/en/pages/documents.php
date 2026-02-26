<?php

return [
    'documents' => [
        'list' => [
            'title' => 'Documents List',
            'documents' => 'Documents',
            'select_warehouse' => 'Select Warehouse',
            'search_placeholder' => 'Search document type',
            'in_progress' => 'In progress',
            'edit' => 'Edit',
            'view_documents' => 'View <br> documents',
            'create_document' => 'Create <br> document',
            'no_document_types' => 'No document types',
            'no_document_types_description' => 'Create the first document type to get started',
        ],

        'index' => [
            'title' => 'Documents',
            'breadcrumb_start' => 'Documents',
            'document_title' => ':name', // Documents :name
            'create_document' => 'Create document',
            'no_documents_message' => 'You don’t have any documents of this type yet!',
            'create_document_message' => 'Create a new document',
            'create_document_no_message_btn' => 'Create document',
        ],

        'create' => [
            'title' => 'Create Document',
            'documents' => 'Documents',
            'creating' => 'Creating',
            'save_as_draft' => 'Save as Draft',
            'save' => 'Save',
            'new_document_title' => 'New Document',

            'sku' => 'SKU',
            'add_sku' => 'Add SKU',
            'edit_sku' => 'Edit SKU',
            'select_goods' => 'Select a product and specify the quantity.',
            'goods_title' => 'Product Name and Quantity',
            'goods_name' => 'Product Name',
            'goods_count' => 'Specify the number of basic units',
            'add' => 'Add',
            'cancel' => 'Cancel',
        ],

        'update' => [
            'title' => 'Document Editing',
            'breadcrumb_edit' => 'Edit',
            'breadcrumb_view' => 'View "',
            'edit_document_title' => 'Edit Document',
        ],

        'view' => [
            'title' => 'View Document',
            'breadcrumb' => [
                'documents' => 'Documents',
                'view' => 'View',
            ],
            'data_info_title' => 'Basic Information:',
            'action_with_document' => [
                'task' => 'Create Task',
                'manual' => 'Process Manually',
            ],
            'actions' => [
                'edit' => 'Edit',
                'cancel_execution' => 'Cancel Execution',
            ],
            'tabs' => [
                'contents' => 'Contents',
                'tasks' => 'Tasks',
                'tasks_placeholder' => 'After the document is sent to work, related tasks will be displayed here.',
                'formed_containers' => 'Formed Containers',
                'containers' => 'Containers',
                'containers_placeholder' => 'After processing the document, the formed containers will be displayed here.',
            ],
            'sku_title' => 'SKU',
            'free_selection_desc' => 'With free selection enabled, the document can be processed only after all items have been processed.',
            'free_selection_label' => 'Free selection',
            'created' => 'Created',
            'created_at' => 'Created At:',
            'author' => 'Author:',
            'document_erp_number' => 'ERP Document No.',
            'document_erp_id' => 'ERP Document ID',
        ],

        'fields' => [
            'arrival' => [
                'title' => [
                    'osnovna_informaciia' => 'Basic Information',
                    'vimogi_do_transportu' => 'Transport Requirements',
                    'informacia_pro_vantazh' => 'Cargo Information',
                    'vidvantazhennia' => 'Shipment',
                    'rozvantazhennia' => 'Unloading',
                    'umovi_dostavky' => 'Delivery Conditions',
                    'informacia_pro_marshrut' => 'Route Information',
                    'shapka' => 'Header',
                ],
                'label' => [
                    // Basic
                    'postacalnik' => 'Supplier',
                    'komentar' => 'Comment',
                    'misce_priimannia' => 'Receiving Location',

                    // Transport Request
                    'operator' => 'Operator',
                    'zamovnik' => 'Customer',
                    'vantazhovidpravnik' => 'Shipper',
                    'vantazhootrymuvach' => 'Consignee',
                    'kontaktna_osoba_vidpravnika' => 'Shipper Contact Person',
                    'kontaktna_osoba_otrymuvacha' => 'Consignee Contact Person',
                    'lokacia_zavantazhennia' => 'Loading Location',
                    'lokacia_rozvantazhennia' => 'Unloading Location',
                    'tip_kuzova' => 'Body Type',
                    'visota_kuzova' => 'Body Height',
                    'temperaturniy_rezhim' => 'Temperature Mode (°C)',
                    'gidrobort' => 'Tail Lift',
                    'tip_vantazhu' => 'Cargo Type',
                    'vaga_brutto' => 'Gross Weight (kg)',
                    'tip_pakuvannya' => 'Packaging Type',
                    'k_st_mist' => 'Number of Places (pallet/m³)',
                    'visota_palet' => 'Pallet Height',
                    'navantazheni_palet' => 'Loaded Pallets',
                    'data_gotovnosti' => 'Ready Date',
                    'data_i_godini_vidvantazhennya' => 'Shipment Date and Time',
                    'obid' => 'Lunch (if available)',
                    'ocinocna_vartist_vantazhu' => 'Estimated Cargo Value (UAH incl. VAT)',
                    'platnik' => 'Payer',
                    'povernennya_poddoniv' => 'Return of Pallets',
                    'povernennya_dokumentiv' => 'Return of Documents',
                    'komentar_po_umovah' => 'Comment on Conditions',
                    'data_i_godini_rozvantazhennya' => 'Unloading Date and Time',

                    // Cargo Request
                    'kompaniya' => 'Company',
                    'kontaktna_osoba' => 'Contact Person',
                    'transport' => 'Transport',
                    'dodatkove_obladnannya' => 'Additional Equipment',
                    'vodiy' => 'Driver',
                    'misto_zavantazhennya' => 'Loading City',
                    'misto_rozvantazhennya' => 'Unloading City',
                    'maks_kilkist_tochok_zavantazhennya' => 'Max Loading Points',
                    'maks_kilkist_tochok_rozvantazhennya' => 'Max Unloading Points',
                    'data_i_chas_zavantazhennya' => 'Loading Date and Time',
                    'data_i_chas_rozvantazhennya' => 'Unloading Date and Time',
                    'vidhilennya_vid_lokacii_zavantazhennya' => 'Deviation from Loading Location',
                    'vidhilennya_vid_lokacii_rozvantazhennya' => 'Deviation from Unloading Location',
                    'mistkist_pal' => 'Capacity (pallets)',
                    'maks_vartist_vantazhu' => 'Max Cargo Value (UAH)',
                    'temperaturniy_rezhim_dodatkoviy' => 'Additional Temperature Mode',
                    'strahuvannya_vantazhu' => 'Cargo Insurance',
                    'nayavnist_gidrobortu' => 'Tail Lift Availability',

                    // Receiving Requests
                    'diapazon' => 'Range',
                    'kontaktna_osoba_zamovnika' => 'Customer Contact Person',
                    'otrymuvach' => 'Recipient',
                    'nayavnist_plomby' => 'Seal Availability',
                    'tip_dostavky' => 'Delivery Type',
                    'planova_data_postuplennya' => 'Planned Arrival Date',
                    'chas_dostavky' => 'Delivery Time',
                    'dnz_transportu' => 'Vehicle Plate',

                    // Shipment Request
                    'data_dostavky' => 'Delivery Date',

                    // Creating "TTN"
                    'pidslava_dlya_ttn' => 'Basis for TTN',
                    'nomer_ttn' => 'TTN Number',
                    'data_ttn' => 'TTN Date',
                    'pereviznik' => 'Carrier',
                    'kod_operatora' => 'Operator Code',
                    'vartist_reysu' => 'Trip Cost',
                    'sobivartist' => 'Cost Price',

                    // Orders
                    'kompaniya_postachalnik' => 'Supplier Company',
                    'kompaniya_otrymuvach' => 'Consignee Company',
                    'sklad_postachalnik' => 'Supplier Warehouse (Loading)',
                    'sklad_otrymuvach' => 'Consignee Warehouse (Unloading)',
                    'data_zavantazhennya' => 'Loading Date',
                    'data_vidvantazhennya' => 'Shipment Date',
                    'chas_zavantazhennya' => 'Loading Time',
                    'chas_rozvantazhennya' => 'Unloading Time',
                    'za_dogovorom' => 'Under Contract',
                    'data_stvorennya_dokumentu' => 'Document Creation Date',
                    'cina_z_pdv' => 'Price incl. VAT (UAH)',
                    'cina_bez_pdv' => 'Price excl. VAT (UAH)',

                    // Supplier Arrival
                    'data_stvorennya_cogo_dokumentu' => 'This Document Creation Date',
                    'tovarnaya_nakladna' => 'Invoice',
                    'valuta' => 'Currency',
                    'sklad_otrymuvach_supplier' => 'Consignee Warehouse',
                    'odynyci' => 'Units',
                    'sklad' => 'Warehouse',

                    // Debiting
                    'sklad_vidpravnik' => 'Sender Warehouse',

                    // Internal Transfer
                    'zamovlennya' => 'Order',
                    'obladnannya' => 'Equipment',

                    // Invoices
                    'nomer_zamovlennya_dokumentu' => 'Order Number (Document)',
                    'data_stvorennya_rakhunkiv' => 'Creation Date',
                    'palet' => 'Pallets',
                ],
                'placeholder' => [
                    'vvedit_komentar' => 'Enter comment',
                    'viberit_znacennia' => 'Select a value',
                    'vvedit_tekst' => 'Enter text',

                    'oberit_pereviznika' => 'Select carrier',
                    'oberit_zamovnika' => 'Select customer',
                    'oberit_vantazhovidpravnika' => 'Select shipper',
                    'oberit_vantazhootrymuvacha' => 'Select consignee',
                    'oberit_kontaktnu_osobu_vidpravnika' => 'Select shipper contact person',
                    'oberit_kontaktnu_osobu_otrymuvacha' => 'Select consignee contact person',
                    'oberit_lokaciu_zavantazhennya' => 'Select loading location',
                    'oberit_lokaciu_rozvantazhennya' => 'Select unloading location',
                    'oberit_tip_kuzova' => 'Select body type',
                    'vkazhit_visotu_kuzova' => 'Specify body height',
                    'oberit_tip_vantazhu' => 'Select cargo type',
                    'vkazhit_vagu_brutto' => 'Specify gross weight (kg)',
                    'oberit_tip_pakuvannya' => 'Select packaging type',
                    'vkazhit_k_st_mist' => 'Specify number of places (pallet/m³)',
                    'vkazhit_visotu_palet' => 'Specify pallet height',
                    'vkazhit_data_gotovnosti' => 'Specify ready date',
                    'vkazhit_ocinocnu_vartist_vantazhu' => 'Specify estimated cargo value',
                    'oberit_platnika' => 'Select payer',

                    // Cargo Request
                    'oberit_kompaniyu' => 'Select company',
                    'oberit_kontaktnu_osobu' => 'Select contact person',
                    'oberit_transport' => 'Select transport',
                    'oberit_dodatkove_obladnannya' => 'Select additional equipment',
                    'oberit_vodiya' => 'Select driver',
                    'oberit_misto_zavantazhennya' => 'Select loading city',
                    'oberit_misto_rozvantazhennya' => 'Select unloading city',
                    'vkazhit_maks_kilkist_tochok_zavantazhennya' => 'Specify max loading points',
                    'vkazhit_maks_kilkist_tochok_rozvantazhennya' => 'Specify max unloading points',
                    'vkazhit_data_i_chas_zavantazhennya' => 'Specify loading date and time',
                    'vkazhit_data_i_chas_rozvantazhennya' => 'Specify unloading date and time',
                    'vkazhit_vidhilennya_vid_lokacii_zavantazhennya' => 'Deviation from loading location',
                    'vkazhit_vidhilennya_vid_lokacii_rozvantazhennya' => 'Deviation from unloading location',
                    'vkazhit_mistkist_pal' => 'Specify capacity (pallets)',
                    'vkazhit_maks_vartist_vantazhu' => 'Specify max cargo value (UAH)',
                    'vkazhit_temperaturniy_rezhim_dodatkoviy' => 'Specify temperature mode (optional)',
                ],
            ],
            'switch_invalid_text' => 'Field is required',
        ],

        'cancel' => [
            'create' => [
                'modal' => [
                    'title' => 'Cancel document creation',
                    'content' => 'Are you sure you want to exit creation? <br> The changes will not be saved.',
                ],
            ],
            'edit' => [
                'modal' => [
                    'title' => 'Cancel document editing',
                    'content' => 'Are you sure you want to exit editing? <br> The changes will not be saved.',
                ],
            ],

            'cancel_button' => 'Cancel',
            'confirm_button' => 'Confirm',
        ],

    ],

    // Fields folder
    'documents_fields_range_from' => 'From',
    'documents_fields_range_to' => 'To',

    'documents_fields_data_time_range' => 'Date',
    'documents_fields_data_time_range_from' => 'From',
    'documents_fields_data_time_range_to' => 'To',

    'documents_fields_data_time_date' => 'Date',
    'documents_fields_data_time_time' => 'Time',

    'documents_fields_time_range_from' => 'From',
    'documents_fields_time_range_to' => 'To',
];
