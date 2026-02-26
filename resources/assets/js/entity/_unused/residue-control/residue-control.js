import { getLocalizedText } from '../../../localization/_unused/residue-control/getLocalizedText.js';

$(document).ready(function () {
    const document = $('body');

    const allRule = $('#all-rule');
    const listRule = $('#list-rule');
    const editViewRule = $('#edit-view-rule');

    const createRule = $('#create-rule');
    const createNewRule = $('#createNewRule');

    const saveEdit = $('#saveEdit');

    const deactivateRule = $('#deactivateRule');
    const activateRule = $('#activateRule');

    const archiveRule = $('#archiveRule');
    const unzippingRule = $('#unzippingRule');
    const deleteRule = $('#deleteRule');

    let selectedRule = null; // Змінна для збереження обраного об'єкту

    let arrRule = [
        {
            id: 1,
            status: 1,
            client: getLocalizedText('residueControlClientName_1'),
            goods: [{ name: getLocalizedText('residueControlGoodsName_1') }, { id: 2 }],
            party: '384629',
            goodsInvoice: getLocalizedText('residueControlGoodsInvoiceName_1'),
            expiryDate: [{ from: '2023-06-16' }, { to: '2023-06-18' }],
            forWhomRuleApplies: 1,
            customerTypeCondition: 1,
            typeClients: 1,
            ruleSeries: 1,
            batchCollectionType: 1,
            contentRule: '131232133213',
        },

        {
            id: 2,
            status: 2,
            client: getLocalizedText('residueControlClientName_2'),
            goods: [{ name: getLocalizedText('residueControlGoodsName_2') }, { id: 2 }],
            party: '384629',
            goodsInvoice: getLocalizedText('residueControlGoodsInvoiceName_2'),
            expiryDate: [{ from: '2023-06-16' }, { to: '2023-06-18' }],
            forWhomRuleApplies: 1,
            customerTypeCondition: 1,
            typeClients: 2,
            ruleSeries: 1,
            batchCollectionType: 1,
            contentRule: '131232133213',
        },

        {
            id: 3,
            status: 3,
            client: getLocalizedText('residueControlClientName_3'),
            goods: [{ name: getLocalizedText('residueControlGoodsName_3') }, { id: 2 }],
            party: '384629',
            goodsInvoice: getLocalizedText('residueControlGoodsInvoiceName_3'),
            expiryDate: [{ from: '2023-06-16' }, { to: '2023-06-18' }],
            forWhomRuleApplies: 2,
            customerTypeCondition: 1,
            typeClients: 2,
            ruleSeries: 1,
            batchCollectionType: 1,
            contentRule: '131232133213',
        },

        {
            id: 4,
            status: 1,
            client: getLocalizedText('residueControlClientName_4'),
            goods: [{ name: getLocalizedText('residueControlGoodsName_4') }, { id: 1 }],
            party: '384629',
            goodsInvoice: getLocalizedText('residueControlGoodsInvoiceName_4'),
            expiryDate: [{ from: '2023-06-16' }, { to: '' }],
            forWhomRuleApplies: 2,
            customerTypeCondition: 1,
            typeClients: 2,
            ruleSeries: 1,
            batchCollectionType: 1,
            contentRule: '131232113',
        },
    ];
    function renderBlocks() {
        let html = '';

        arrRule.forEach((item) => {
            html += `
            <li class="list-group-item border-bottom-0 gap-3  justify-content-between px-2" data-block-id="${item.id}" style="position: static">
                <div class="align-self-center">
                    <p class="m-0  d-flex gap-1 align-items-center">
                        <a href="#" class="list-group-item-action w-auto fw-bold">№${item.id}</a>
                        <span class="px-75 py-50 gap-25 badge ${item.status === 1 ? 'badge-light-success' : '' || item.status === 2 ? 'badge-light-danger' : '' || item.status === 3 ? 'badge-light-secondary' : ''} d-inline-flex align-items-center">${item.status === 1 ? getLocalizedText('residueControlRenderBlocksStatusActive') : '' || item.status === 2 ? getLocalizedText('residueControlRenderBlocksStatusInActive') : '' || item.status === 3 ? getLocalizedText('residueControlRenderBlocksStatusArchive') : ''}</span>
                    </p>
                    <div class="text-secondary line-height-condensed mt-1">
                        ${getLocalizedText('residueControlRenderBlocksRuleInfo_1')} <span class="text-dark">${item.client}</span>
                        ${getLocalizedText('residueControlRenderBlocksRuleInfo_2')} <span class="text-dark">${item.goods[0].name}</span>
                        ${getLocalizedText('residueControlRenderBlocksRuleInfo_3')} <span class="text-dark">${item.party}</span>,
                        ${getLocalizedText('residueControlRenderBlocksRuleInfo_4')} <span class="text-dark">${item.goodsInvoice}</span>
                    </div>
                    <div class="text-secondary line-height-condensed">${getLocalizedText('residueControlRenderBlocksRuleInfoValidity')}
                        <span class="text-dark">${item.expiryDate[0].from}</span>
                        -
                        <span class="text-dark">${item.expiryDate[1].to !== '' ? `${item.expiryDate[1].to}` : getLocalizedText('residueControlRenderBlocksRuleInfoValidityNoDate')}</span>
                    </div>
                </div>

                <div class="d-flex gap-1 align-items-center">
                    <div><i data-feather='arrow-right'></i></div>
                    <svg width="1" height="50" viewBox="0 0 1 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="1" height="50" fill="#DBDADE"/>
                    </svg>

                    <div class="d-inline-flex">
                        <a class="dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i data-feather='more-vertical'></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" id="dropdown-menu-type">
                            ${item.status !== 3 ? `<button ${item.status === 1 ? 'id="deactivateRule"' : '' || item.status === 2 ? 'id="activateRule"' : ''}  class="dropdown-item w-100">${item.status === 1 ? getLocalizedText('residueControlRenderBlocksRuleInfoActionDeactivate') : getLocalizedText('residueControlRenderBlocksRuleInfoActionActivate')}</button>` : ''}
                            <button data-bs-toggle="modal" ${item.status !== 3 ? `id="archive_button" data-bs-target="#archive_regulation"` : 'id="no_archive_button" data-bs-target="#no_archive_regulation"'}  type="submit" class="dropdown-item w-100">${item.status !== 3 ? getLocalizedText('residueControlRenderBlocksRuleInfoActionArchive') : getLocalizedText('residueControlRenderBlocksRuleInfoActionUnzip')}</button>
                            <button data-bs-toggle="modal" id="delete_button" data-bs-target="#delete_regulation" type="submit" class="text-danger dropdown-item w-100">${getLocalizedText('residueControlRenderBlocksRuleInfoActionDelete')}</button>
                        </div>
                    </div>
                </div>
            </li>`;
        });

        listRule.append(html);
        renderEditViewType();
    }

    // Виклик функції для рендеру блоків
    renderBlocks();

    // Обробник події для кнопки "Create New Rule"
    createNewRule.click(function () {
        const id = arrRule.length + 1; // Створення нового унікального ID

        // Зчитування даних з блоку "create-rule"
        //const client = $('#client').val();
        //const party = $('#party').val();
        //const goodsInvoice = $('#goodsInvoice').val();

        const goods = $('#goods').val();
        const from = $('#periodFrom').val();
        const to = $('#periodTo').val();

        const radioTypeClientsChecked = $('#radioTypeClients').prop('checked');
        // При виборі "Для типу клієнтів" присвоїмо змінній forWhomRuleApplies значення "clients"
        // Якщо вибрано "Для конкретного клієнта", тоді присвоїмо змінній forWhomRuleApplies значення "specific"
        const forWhomRuleApplies = radioTypeClientsChecked ? 1 : 2;

        const customerTypeCondition = $('#rule-type-client').val();
        const typeClients = $('#type-client').val();
        const ruleSeries = $('#rule-series').val();
        const batchCollectionType = $('#type-samples-party').val();
        const contentRule = $('#contentRule').val();

        // Створення нового об'єкту
        const newRule = {
            id: id,
            status: 1, // Припустимо, що за замовчуванням статус завжди 1
            client: getLocalizedText('residueControlCreateNewRuleClientName'),
            goods: [
                { name: getLocalizedText('residueControlCreateNewRuleGoodsName') },
                { id: goods },
            ],
            party: '2223',
            goodsInvoice: getLocalizedText('residueControlCreateNewRuleGoodsInvoice'),
            expiryDate: [{ from: from }, { to: to }],
            forWhomRuleApplies: forWhomRuleApplies,
            customerTypeCondition: customerTypeCondition,
            typeClients: typeClients,
            ruleSeries: ruleSeries,
            batchCollectionType: batchCollectionType,
            contentRule: contentRule,
        };

        // Додавання нового об'єкту до масиву arrRule
        arrRule.push(newRule);

        listRule.empty();
        renderBlocks();

        allRule.removeClass('d-none');
        createRule.addClass('d-none');

        // Очищування полів після додавання нового об'єкту до масиву arrRule (опційно)
        clearFields();

        // Виведення масиву arrRule в консоль (опційно)
        console.log('arrRule:', arrRule);
    });

    // Обробник події для кнопки "Save Edit"
    $(document).on('click', '#saveEdit', function () {
        const blockId = $('#edit-view-rule #title-rule')
            .text()
            .replace(getLocalizedText('residueControlSaveEditTitle'), ''); // Отримуємо id об'єкта для редагування
        const foundObject = arrRule.find((item) => item.id === parseInt(blockId)); // Знаходимо об'єкт з вказаним id
        //console.log(foundObject)
        if (foundObject) {
            //console.log(foundObject)
            // Отримуємо всі дані з полів форми редагування
            const goods = $('#edit-view-rule #goods-view').val();
            const from = $('#edit-view-rule #periodFromView').val();
            const to = $('#edit-view-rule #periodToView').val();
            const forWhomRuleApplies = $(
                '#edit-view-rule input[name="radioRuleType"]:checked'
            ).val();
            const customerTypeCondition = $('#edit-view-rule #rule-type-client-view').val();
            const typeClients = $('#edit-view-rule #type-client-view').val();
            const ruleSeries = $('#edit-view-rule #rule-series-view').val();
            const batchCollectionType = $('#edit-view-rule #type-samples-party-view').val();
            const contentRule = $('#edit-view-rule #contentRuleView').val();

            // Оновлюємо відповідні поля знайденого об'єкту
            foundObject.goods[1].id = parseInt(goods);
            foundObject.expiryDate[0].from = from;
            foundObject.expiryDate[1].to = to;
            foundObject.forWhomRuleApplies = parseInt(forWhomRuleApplies);
            foundObject.customerTypeCondition = parseInt(customerTypeCondition);
            foundObject.typeClients = parseInt(typeClients);
            foundObject.ruleSeries = parseInt(ruleSeries);
            foundObject.batchCollectionType = parseInt(batchCollectionType);
            foundObject.contentRule = contentRule;

            updateListRule(); // Оновлюємо список блоків
            allRule.removeClass('d-none');
            editViewRule.addClass('d-none');
        }
    });

    // Функція для перевірки та оновлення стану кнопки "Create New Rule"
    function updateCreateButtonStatus() {
        const allFieldsFilled = checkAllFieldsFilled();
        //console.log(allFieldsFilled)
        if (allFieldsFilled) {
            createNewRule.removeClass('disabled');
        }
        //console.log('Обнова')
    }

    // Функція для перевірки, чи всі поля заповнені
    function checkAllFieldsFilled() {
        //const client = $('#client').val();
        //const party = $('#party').val();
        //const goodsInvoice = $('#goodsInvoice').val();
        //const to = $('#periodTo').val();
        //const forWhomRuleApplies = $('#radioTypeClients').prop('checked');
        //const customerTypeCondition = $('#rule-type-client').val();

        const goods = $('#goods').val();
        const from = $('#periodFrom').val();
        const typeClients = $('#type-client').val();
        const ruleSeries = $('#rule-series').val();
        const batchCollectionType = $('#type-samples-party').val();
        const contentRule = $('#contentRule').val();

        return (
            //client !== '' &&
            //party !== '' &&
            //goodsInvoice !== '' &&

            goods !== '' &&
            from !== '' &&
            typeClients !== '' &&
            ruleSeries !== '' &&
            batchCollectionType !== '' &&
            contentRule !== ''
        );
    }

    // Функція для очищення полів форми (опційно)
    function clearFields() {
        //$('#client').val('');
        //$('#party').val('');
        //$('#goodsInvoice').val('');

        $('#goods').val('').trigger('change');
        $('#periodFrom').val('');
        $('#periodTo').val('');
        $('#rule-type-client').val('').trigger('change');
        $('#type-client').val('').trigger('change');
        $('#rule-series').val('').trigger('change');
        $('#type-samples-party').val('').trigger('change');
        $('#contentRule').val('');
    }

    // Обробники подій для полів форми, які можуть змінюватись
    $('input, select').on('input change', function () {
        updateCreateButtonStatus();
    });

    function renderEditViewType() {
        // Додамо обробник події кліку для переходу на інший блок
        const blockLinks = listRule.find('[data-block-id]');
        blockLinks.on('click', (event) => {
            event.preventDefault();
            const blockId = $(event.currentTarget).data('block-id');
            const foundObject = arrRule.find((item) => item.id === blockId);

            if (foundObject) {
                // Вивести всі дані знайденого об'єкту
                //console.log('Found Object:', foundObject);

                // Заповнимо поля на сторінці даними з об'єкту
                $('#edit-view-rule #title-rule').text(
                    `${getLocalizedText('residueControlRenderEditViewTypeTitle')}${foundObject.id}`
                );

                if (foundObject.status === 1) {
                    $('#edit-view-rule #status-rule')
                        .text(getLocalizedText('residueControlRenderEditViewTypeStatusActive'))
                        .addClass('badge-light-success')
                        .removeClass('badge-light-danger')
                        .removeClass('badge-light-secondary');
                } else if (foundObject.status === 2) {
                    $('#edit-view-rule #status-rule')
                        .text(getLocalizedText('residueControlRenderEditViewTypeStatusInActive'))
                        .addClass('badge-light-danger')
                        .removeClass('badge-light-success')
                        .removeClass('badge-light-secondary');
                } else if (foundObject.status === 3) {
                    $('#edit-view-rule #status-rule')
                        .text(getLocalizedText('residueControlRenderEditViewTypeStatusInArchive'))
                        .addClass('badge-light-secondary')
                        .removeClass('badge-light-success')
                        .removeClass('badge-light-danger');
                }

                if (foundObject.forWhomRuleApplies === 1) {
                    $('#radioTypeClientsView').prop('checked', true);
                    $('#radioSpecificClientsView').prop('checked', false);
                } else {
                    $('#radioTypeClientsView').prop('checked', false);
                    $('#radioSpecificClientsView').prop('checked', true);
                }

                $('#edit-view-rule #rule-type-client-view')
                    .val(foundObject.customerTypeCondition)
                    .trigger('change');

                $('#edit-view-rule #type-client-view')
                    .val(foundObject.typeClients)
                    .trigger('change');

                $('#edit-view-rule #goods-view').val(foundObject.goods[1].id).trigger('change');

                $('#edit-view-rule #rule-series-view')
                    .val(foundObject.ruleSeries)
                    .trigger('change');

                $('#edit-view-rule #type-samples-party-view')
                    .val(foundObject.batchCollectionType)
                    .trigger('change');

                $('#edit-view-rule #contentRuleView').val(foundObject.contentRule);

                if (foundObject.expiryDate[0].from !== '' || foundObject.expiryDate[0].to !== '') {
                    $('#edit-view-rule #periodFromView').val(foundObject.expiryDate[0].from);
                    $('#edit-view-rule #periodToView').val(foundObject.expiryDate[1].to);
                }
            } else {
                console.log('Object not found.');
            }
        });
    }

    // Функція для перевірки та оновлення стану кнопки "Create New Rule"
    function updateEditViewButtonStatus() {
        const allFieldsFilled = checkAllFieldsChangeView();
        //console.log(allFieldsFilled)
        if (allFieldsFilled) {
            saveEdit.removeClass('disabled');
        }
        //console.log('Обнова редагування')
    }

    // Функція для перевірки, чи всі поля заповнені
    function checkAllFieldsChangeView() {
        //const client = $('#client').val();
        //const party = $('#party').val();
        //const goodsInvoice = $('#goodsInvoice').val();
        //const to = $('#periodTo').val();
        //const forWhomRuleApplies = $('#radioTypeClients').prop('checked');
        //const customerTypeCondition = $('#rule-type-client').val();

        const goods = $('#goods-view').val();
        const from = $('#periodFromView').val();
        const typeClients = $('#type-client-view').val();
        const ruleSeries = $('#rule-series-view').val();
        const batchCollectionType = $('#type-samples-party-view').val();
        const contentRule = $('#contentRuleView').val();

        return (
            //client !== '' &&
            //party !== '' &&
            //goodsInvoice !== '' &&

            goods !== '' &&
            from !== '' &&
            typeClients !== '' &&
            ruleSeries !== '' &&
            batchCollectionType !== '' &&
            contentRule !== ''
        );
    }

    // Обробники подій для полів форми, які можуть змінюватись
    $('input, select').on('input change', function () {
        updateEditViewButtonStatus();
    });

    // Обробники подій для кнопок створення регламентів
    document.on('click', '#createNewRules', function () {
        allRule.addClass('d-none');
        createRule.removeClass('d-none');
    });

    document.on('click', '.back-to-all-rule', function () {
        allRule.removeClass('d-none');
        createRule.addClass('d-none');

        updateListRule(); // Оновлюємо список блоків
    });

    document.on('click', '.back-to-all-rule-1', function () {
        allRule.removeClass('d-none');
        editViewRule.addClass('d-none');

        saveEdit.addClass('disabled');
    });

    document.on('click', '.list-group-item', function (e) {
        var $target = $(e.target);
        //console.log($target)
        if (
            !$target.closest('.dropdown-toggle').length &&
            !$target.closest('.dropdown-menu').length &&
            !$target.closest('.dropdown-menu-group').length
        ) {
            editViewRule.removeClass('d-none');
            allRule.addClass('d-none');
            createRule.addClass('d-none');
            saveEdit.addClass('disabled');
        }
    });

    // Обробник події для кнопки "Deactivate Rule"
    document.on('click', '#deactivateRule', function () {
        const blockId = $(this).closest('.list-group-item').data('block-id');
        const foundObject = arrRule.find((item) => item.id === blockId);

        if (foundObject) {
            foundObject.status = 2; // Міняємо статус на "Неактивна"
            updateListRule(); // Оновлюємо список блоків
        }
    });

    // Обробник події для кнопки "Activate Rule"
    document.on('click', '#activateRule', function () {
        const blockId = $(this).closest('.list-group-item').data('block-id');
        const foundObject = arrRule.find((item) => item.id === blockId);

        if (foundObject) {
            foundObject.status = 1; // Міняємо статус на "Активна"
            updateListRule(); // Оновлюємо список блоків
        }
    });

    // Обробник події для кнопки "Archive Rule" у модальному вікні
    $(document).on('click', '#archiveRule', function () {
        if (selectedRule) {
            selectedRule.status = 3; // Міняємо статус на "Архів"
            updateListRule(); // Оновлюємо список блоків
        }
        selectedRule = null; // Збираємо дані про об'єкт після виконання операції
        $('.modal').modal('hide');
    });

    // Обробник події для кнопки "Unzipping Rule" у модальному вікні
    $(document).on('click', '#unzippingRule', function () {
        if (selectedRule) {
            selectedRule.status = 1; // Міняємо статус на "Активна"
            updateListRule(); // Оновлюємо список блоків
        }
        selectedRule = null; // Збираємо дані про об'єкт після виконання операції
        $('.modal').modal('hide');
    });

    // Обробник події для кнопки "Delete Rule" у модальному вікні
    $(document).on('click', '#deleteRule', function () {
        if (selectedRule) {
            const foundIndex = arrRule.findIndex((item) => item.id === selectedRule.id);
            if (foundIndex !== -1) {
                arrRule.splice(foundIndex, 1); // Видаляємо об'єкт із масиву за індексом
                updateListRule(); // Оновлюємо список блоків
            }
        }
        selectedRule = null; // Збираємо дані про об'єкт після виконання операції
        $('.modal').modal('hide');
    });

    // Обробник події для кнопок "Archive Button" та "Delete Button" для збереження обраного об'єкту
    $(document).on(
        'click',
        '[data-bs-target="#archive_regulation"], [data-bs-target="#delete_regulation"], [data-bs-target="#no_archive_regulation"]',
        function () {
            const blockId = $(this).closest('.list-group-item').data('block-id');
            selectedRule = arrRule.find((item) => item.id === blockId);
            //console.log(selectedRule)

            // Отримати елемент з класом titleRuleModal відносно кнопки, на яку було натиснуто
            const titleElement = $(document)
                .closest('.modal .modal-content')
                .find('.titleRuleModal');
            //console.log(titleElement)

            if (titleElement) {
                // Змінити текст у класі titleRuleModal на id обраного об'єкту
                $('.titleRuleModal').text(selectedRule.id);
            }
        }
    );

    // Функція для оновлення списку блоків на сторінці
    function updateListRule() {
        listRule.empty();
        renderBlocks();
    }

    //Пошук за .list-group-item-action
    $(function findListItem() {
        $('#searchTypeRules').on('input', function () {
            const searchValue = $(this).val().toLowerCase();
            console.log(searchValue);
            $('#list-rule li').each(function () {
                const listItemText = $(this).find('.list-group-item-action').text().toLowerCase();
                console.log(listItemText);
                if (listItemText.includes(searchValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });

    // Обробник події для радіокнопок "Для типу клієнтів" та "Для конкретного клієнта"
    $('input[name="radioTypeClients"]').change(function () {
        if ($(this).attr('id') === 'radioTypeClients') {
            $('#radioSpecificClients').prop('checked', false);
        } else {
            $('#radioTypeClients').prop('checked', false);
        }
    });

    // Обробник події для радіокнопок "Для типу клієнтів" та "Для конкретного клієнта" в режимі перегляду
    $('input[name="radioTypeClientsView"]').change(function () {
        if ($(this).attr('id') === 'radioTypeClientsView') {
            $('#radioSpecificClientsView').prop('checked', false);
        } else {
            $('#radioTypeClientsView').prop('checked', false);
        }
    });

    // Обробник події для радіокнопок "Для конкретного клієнта" та "Для типу клієнтів"
    $('input[name="radioSpecificClients"]').change(function () {
        if ($(this).attr('id') === 'radioSpecificClients') {
            $('#radioTypeClients').prop('checked', false);
        } else {
            $('#radioSpecificClients').prop('checked', false);
        }
    });

    // Обробник події для радіокнопок "Для конкретного клієнта" та "Для типу клієнтів" в режимі перегляду
    $('input[name="radioSpecificClientsView"]').change(function () {
        if ($(this).attr('id') === 'radioSpecificClientsView') {
            $('#radioTypeClientsView').prop('checked', false);
        } else {
            $('#radioSpecificClientsView').prop('checked', false);
        }
    });

    // Обробник події для радіокнопок "Для типу клієнтів" та "Для конкретного клієнта" в режимі перегляду
    $('input[name="radioTypeAllFilter"]').change(function () {
        if ($(this).attr('id') === 'radioTypeAllFilter') {
            $('#radioTypeClientsFilter').prop('checked', false);
            $('#radioTypeSpecificClientsFilter').prop('checked', false);

            $('#filter-rule-type-block').removeClass('d-none');
            $('#filter-rule-clients-block').removeClass('d-none');
            $('#filter-rule-goods-block').removeClass('d-none');
            $('#filter-rule-status-block').removeClass('d-none');
        } else {
            $('#radioTypeAllFilter').prop('checked', false);
        }
        $('#cancelFilterRules').click();
    });

    // Обробник події для радіокнопок "Для конкретного клієнта" та "Для типу клієнтів"
    $('input[name="radioTypeClientsFilter"]').change(function () {
        if ($(this).attr('id') === 'radioTypeClientsFilter') {
            $('#radioTypeSpecificClientsFilter').prop('checked', false);
            $('#radioTypeAllFilter').prop('checked', false);

            $('#filter-rule-type-block').removeClass('d-none');
            $('#filter-rule-clients-block').addClass('d-none');
            $('#filter-rule-goods-block').addClass('d-none');
            $('#filter-rule-status-block').addClass('d-none');
        } else {
            $('#radioTypeClientsFilter').prop('checked', false);
            $('#filter-rule-type-block').removeClass('d-none');
        }
        $('#cancelFilterRules').click();
    });

    // Обробник події для радіокнопок "Для конкретного клієнта" та "Для типу клієнтів"
    $('input[name="radioTypeSpecificClientsFilter"]').change(function () {
        if ($(this).attr('id') === 'radioTypeSpecificClientsFilter') {
            $('#radioTypeClientsFilter').prop('checked', false);
            $('#radioTypeAllFilter').prop('checked', false);

            $('#filter-rule-type-block').addClass('d-none');
            $('#filter-rule-clients-block').removeClass('d-none');
            $('#filter-rule-goods-block').addClass('d-none');
            $('#filter-rule-status-block').addClass('d-none');
        } else {
            $('#radioTypeSpecificClientsFilter').prop('checked', false);
            $('#filter-rule-clients-block').removeClass('d-none');
        }
        $('#cancelFilterRules').click();
    });

    // Обробник події для кнопки "Застосувати" фільтри
    $('#submitFilterRules').on('click', function () {
        const filterType = $('#filter-rule-type').val();
        const filterClients = $('#filter-rule-clients').val();
        const filterGoods = $('#filter-rule-goods').val();
        const filterStatus = $('#filter-rule-status').val();
        //console.log(filterClients)

        // Фільтрація масиву arrRule згідно обраних значень у випадаючих списках
        const filteredRules = arrRule.filter((rule) => {
            //console.log(arrRule)
            let typeCondition = false;
            let clientCondition = false;
            let goodsCondition = false;
            let statusCondition = false;

            // Фільтрація за типом
            if (filterType === '' || parseInt(filterType) === 1) {
                typeCondition = true;
            } else if (filterType === '' || parseInt(filterType) === 2) {
                typeCondition = rule.typeClients === 2;
            } else if (filterType === '' || parseInt(filterType) === 3) {
                typeCondition = rule.typeClients === 1;
            } else {
                typeCondition = rule.typeClients === parseInt(filterClients);
            }

            // Фільтрація за клієнтом +
            if (filterClients === '' || parseInt(filterClients) === 1) {
                clientCondition = true;
            } else if (filterClients === '' || parseInt(filterClients) === 2) {
                clientCondition =
                    rule.client === getLocalizedText('residueControlSubmitFilterRulesIsClient_1');
                //console.log(clientCondition)
            } else if (filterClients === '' || parseInt(filterClients) === 3) {
                clientCondition =
                    rule.client === getLocalizedText('residueControlSubmitFilterRulesIsClient_2');
                //console.log(clientCondition)
            } else {
                clientCondition = rule.client === parseInt(filterClients);
            }

            // Фільтрація за товаром +
            if (filterGoods === '' || parseInt(filterGoods) === 1) {
                goodsCondition = true;
            } else if (filterGoods === '' || parseInt(filterGoods) === 2) {
                goodsCondition = rule.goods[1].id === parseInt(filterGoods);
                //console.log(goodsCondition)
            } else {
                goodsCondition = rule.goods[1].id === parseInt(filterGoods);
            }

            // Фільтрація за статусом +
            if (filterStatus === '' || parseInt(filterStatus) === 1) {
                statusCondition = true;
            } else if (filterStatus === '' || parseInt(filterStatus) === 2) {
                statusCondition = rule.status === 1;
            } else if (filterStatus === '' || parseInt(filterStatus) === 3) {
                statusCondition = rule.status === 2;
            } else if (filterStatus === '' || parseInt(filterStatus) === 4) {
                statusCondition = rule.status === 1 || rule.status === 2; // Тут застосовано "або" (||) для двох статусів
            } else if (filterStatus === '' || parseInt(filterStatus) === 5) {
                statusCondition = rule.status === 3;
            } else {
                statusCondition = rule.status === parseInt(filterStatus);
            }

            return typeCondition && clientCondition && goodsCondition && statusCondition;
        });

        //console.log(filteredRules)

        // Приховати всі елементи
        $('#list-rule li').addClass('d-none');
        // Показати елементи, які відповідають фільтрованим правилам
        filteredRules.forEach((rule) => {
            $(`#list-rule li[data-block-id='${rule.id}']`).removeClass('d-none');
        });
    });

    // При кліку на кнопку "Скинути фільтри"
    $('#cancelFilterRules').on('click', function () {
        // Скидання значень випадаючих списків до початкових
        $('#filter-rule-type').val('').trigger('change');
        $('#filter-rule-clients').val('').trigger('change');
        $('#filter-rule-goods').val('').trigger('change');
        $('#filter-rule-status').val('').trigger('change');

        // Виклик обробника знову для відображення всіх правил без фільтрації
        $('#submitFilterRules').click();
    });
});
