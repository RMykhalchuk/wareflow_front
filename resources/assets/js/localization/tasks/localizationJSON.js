export const localizationJSON = {
    uk: {
        // Table Index
        id: '№',
        type: 'Тип',
        type_of_task_formation: 'Тип формування завдання',
        kind: 'Вид',
        location: 'Склад',
        status_id: 'Статус',
        priority: 'Пріоритет',
        start: 'Старт',
        completion: 'Завершення',
        executor: 'Виконавець',
        created: 'Створено',

        document: 'Документ',

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

        view: {
            id: '№',
            name: 'Назва',
            party: 'Партія',
            manufactured: 'Виготовлення',
            expiry: 'Вжити до',
            unit: 'Пакінг',
            unit_1: 'м²',
            unit_2: 'Палета',
            condition: 'Кондиція',
            placing: 'Місце відбору',
            before_moving: 'К-сть до переміщення',
            moved: 'Переміщено',
            moved_responsible: 'Перемістив',

            action: {
                name: 'Дія',
                action: 'Дія',
            },
        },

        // Table Columns
        tableColumns: {
            name: 'Назва',
            batch: 'Партія',
            manufactured: 'Виготовлення',
            usedUntil: 'Вжити до',
            pack: 'Пакування',
            condition: 'Кондиція',
            placing: 'Розміщення',
            quantity: 'К-сть',
            // Quantity Units
            areaUnit: 'м²',
            palletsUnit: 'пал',
        },
    },
    en: {
        // Table Index
        id: '№',
        type: 'Type',
        type_of_task_formation: 'Type of task formation',
        kind: 'Kind',
        location: 'Warehouse',
        status_id: 'Status',
        priority: 'Priority',
        start: 'Start',
        completion: 'Completion',
        executor: 'Executor',
        created: 'Created',

        document: 'Document',

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

        view: {
            id: '№',
            name: 'Name',
            party: 'Batch',
            manufactured: 'Manufactured',
            expiry: 'Use by',
            unit: 'Packing',
            unit_1: 'm²',
            unit_2: 'Pallet',
            condition: 'Condition',
            placing: 'Place of selection',
            before_moving: 'Quantity to move',
            moved: 'Moved',
            moved_responsible: 'Moved',

            action: {
                name: 'Action',
                action: 'Action',
            },
        },

        // Table Columns
        tableColumns: {
            name: 'Name',
            batch: 'Batch',
            manufactured: 'Manufactured',
            usedUntil: 'Use until',
            pack: 'Packaging',
            condition: 'Condition',
            placing: 'Placing',
            quantity: 'Quantity',
            // Quantity Units
            areaUnit: 'm²',
            palletsUnit: 'pallets',
        },
    },
};
