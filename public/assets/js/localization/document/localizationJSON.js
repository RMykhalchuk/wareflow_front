export const localizationJSON = {
    uk: {
        //document.js
        documentSendRequestError: 'Щось не так, оновіть сторінку',
        createNomenclature: 'Створіть номенклатуру',

        //Document table
        tableDocumentID: '№',
        tableDocumentStatus: 'Статус',
        statusCreated: 'Створено',
        tableDocumentAction: 'Дії',
        tableDocumentActionView: 'Перегляд документу',
        tableDocumentActionEdit: 'Редагувати документ',
        tableDocumentActionDelete: 'Видалити документ',

        //Preview Document SKU table
        tablePreviewSkuID: 'ID',
        tablePreviewSkuName: 'Назва',
        tablePreviewSkuPackage: 'Одиниці',
        tablePreviewSkuCount: 'Кількість',
        tablePreviewSkuLeftovers: 'Залишки',
        tablePreviewSkuLeftoversAction: 'Опрацювати',
        tablePreviewSkuLeftoversActionView: 'Переглянути',

        // tasks
        id: '№',
        type: 'Тип',
        type_of_task_formation: 'Тип формування завдання',
        kind: 'Вид',
        status_id: 'Статус',
        priority: 'Пріоритет',
        start: 'Старт',
        completion: 'Завершення',
        executor: 'Виконавець',
        created: 'Створено',

        btnActionView: 'Переглянути',
        unit: 'позицій',

        // Statuses
        status_planned: 'Запланована',
        status_rescan: 'Перерахунок товару',
        status_inventoried: 'Товар проінвенторизовано',
        status_erp_check: 'Звірка з ERP (Опційно)',
        status_completed: 'Завершена',
        status_completed_early: 'Завершена достроково',
        status_paused: 'Пауза',
        status_unknown: 'Невідомо',

        sku_table: {
            id: '№',
            name: 'Назва',
            barcode: 'Штрих-код',
            brand: 'Бренд',
            supplier: 'Постачальник',
            manufacturer: 'Виробник',
            country: 'Країна',
            unit: 'Од. виміру',
            quantity: 'Кількість',
            actions: {
                add: 'Додати',
                edit: 'Редагувати',
                update: 'Оновити',
                delete: 'Видалити',
            },
            quantity_required: 'Будь ласка, заповніть усі обов’язкові поля',
            duplicate_goods: 'Цей товар вже додано до документу',
        },

        kits_table: {
            id: '№',
            name: 'Назва',
            quantity: 'Кількість',
            package: 'Паковання',
            actions: {
                title: 'Дії',
                add: 'Додати',
                edit: 'Редагувати',
                update: 'Оновити',
                delete: 'Видалити',
            },
            confirm_delete_kit: 'Ви впевнені, що хочете видалити цей запис?',
        },

        container: {
            id: '№',
            code: 'Код',
            created: 'Створено',
        },
    },

    en: {
        //document.js
        documentSendRequestError: 'Something went wrong, please refresh the page',
        createNomenclature: 'Create a nomenclature',

        // Document table
        tableDocumentID: '№',
        tableDocumentStatus: 'Status',
        statusCreated: 'Created',
        tableDocumentAction: 'Actions',
        tableDocumentActionView: 'View Document',
        tableDocumentActionEdit: 'Edit Document',
        tableDocumentActionDelete: 'Delete Document',

        // Preview Document SKU table
        tablePreviewSkuID: 'ID',
        tablePreviewSkuName: 'Name',
        tablePreviewSkuPackage: 'Unit',
        tablePreviewSkuCount: 'Quantity',
        tablePreviewSkuLeftovers: 'Leftovers',
        tablePreviewSkuLeftoversAction: 'To process',
        tablePreviewSkuLeftoversActionView: 'View',

        // Table Index
        id: '№',
        type: 'Type',
        type_of_task_formation: 'Type of task formation',
        kind: 'Kind',
        status_id: 'Status',
        priority: 'Priority',
        start: 'Start',
        completion: 'Completion',
        executor: 'Executor',
        created: 'Created',

        btnActionView: 'View',
        unit: 'positions',

        // Statuses
        status_planned: 'Planned',
        status_rescan: 'Rescan',
        status_inventoried: 'Inventoried',
        status_erp_check: 'ERP Check (Optional)',
        status_completed: 'Completed',
        status_completed_early: 'Completed Early',
        status_paused: 'Paused',
        status_unknown: 'Unknown',

        sku_table: {
            id: '№',
            name: 'Name',
            barcode: 'Barcode',
            brand: 'Brand',
            supplier: 'Supplier',
            manufacturer: 'Manufacturer',
            country: 'Country',
            unit: 'Unit',
            quantity: 'Quantity',
            actions: {
                add: 'Add',
                edit: 'Edit',
                update: 'Update',
                delete: 'Delete',
            },
            quantity_required: 'Please fill in all required fields',
            duplicate_goods: 'This product is already added to the document',
        },

        kits_table: {
            id: '№',
            name: 'Name',
            quantity: 'Quantity',
            package: 'Package',
            actions: {
                title: 'Actions',
                add: 'Add',
                edit: 'Edit',
                update: 'Update',
                delete: 'Delete',
            },
            confirm_delete_kit: 'Are you sure you want to delete this entry?',
        },

        container: {
            id: '№',
            code: 'Code',
            created: 'Created',
        },
    },
};
