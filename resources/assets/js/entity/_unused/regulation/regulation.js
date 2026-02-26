import { translit } from '../../../utils/translit.js';
import { sendResponseActionEdit, sendRequest } from '../contract/utils/httpRequest.js';
import { getLocalizedText } from '../../../localization/_unused/regulation/getLocalizedText.js';

$(document).ready(function () {
    const body = $('body');
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const url = window.location.origin;

    //Endpoints
    const uriCreate = '/regulations';
    let uriEdit;
    let uriView;

    // Array of block names
    const blockRegulation = ['trade', 'warehouse', 'transport'];

    // Object mapping block names to images and titles
    const blockData = {
        trade: {
            image: 'assets/icons/entity/regulation/report-money.svg',
            title: getLocalizedText('regulationBlockDataTradeTitle'),
            description: getLocalizedText('regulationBlockDataTradeDescription'),
            customerTitle: getLocalizedText('regulationBlockDataTradeCustomerTitle'),
            providerTitle: getLocalizedText('regulationBlockDataTradeProviderTitle'),
        },
        warehouse: {
            image: 'assets/icons/entity/regulation/building-warehouse.svg',
            title: getLocalizedText('regulationBlockDataWarehouseTitle'),
            description: getLocalizedText('regulationBlockDataWarehouseDescription'),
            customerTitle: getLocalizedText('regulationBlockDataWarehouseCustomerTitle'),
            providerTitle: getLocalizedText('regulationBlockDataWarehouseProviderTitle'),
        },
        transport: {
            image: 'assets/icons/entity/regulation/truck-regulation.svg',
            title: getLocalizedText('regulationBlockDataTransportTitle'),
            description: getLocalizedText('regulationBlockDataTransportDescription'),
            customerTitle: getLocalizedText('regulationBlockDataTransportCustomerTitle'),
            providerTitle: getLocalizedText('regulationBlockDataTransportProviderTitle'),
        },
    };

    const blockArrTypePalets = [
        getLocalizedText('regulationBlockArrTypePalletsItem_1'),
        getLocalizedText('regulationBlockArrTypePalletsItem_2'),
        getLocalizedText('regulationBlockArrTypePalletsItem_3'),
        getLocalizedText('regulationBlockArrTypePalletsItem_4'),
    ];

    let arrRegulation;
    let regulation;

    let selectedRegulation = null; // Змінна для збереження обраного об'єкту

    // Функція для отримання даних
    async function getResponse(url, uri, method = 'GET') {
        try {
            const apiUrl = url + uri;
            const requestOptions = {
                method: method,
            };

            const response = await fetch(apiUrl, requestOptions);
            arrRegulation = await response.json();

            return arrRegulation;
        } catch (error) {
            console.error('Error:', error);
            // Обробити помилку тут
            throw error; // Звернення помилки для подальшого оброблення
        }
    }

    async function getResponseView(url, uri, method = 'GET') {
        const apiUrl = url + uri;
        const requestOptions = {
            method: method,
        };

        await fetch(apiUrl, requestOptions)
            .then((response) => response.json())
            .then((data) => {
                regulation = data;
                console.log('Success', data);
                // You can perform further actions here after successful creation
            })
            .catch((error) => {
                console.error('Error :', error);
                // Handle errors here
            });
    }

    // Function to render the blocks
    function renderBlocks() {
        // Loop through the block names

        for (var i = 0; i < blockRegulation.length; i++) {
            var blockName = blockRegulation[i];
            var block = blockData[blockName];

            // Generate the HTML code for the block
            var blockHTML = `
                  <div id="${blockName}-regulation">
                    <div class="p-2">
                      <div class="d-flex gap-1">
                        <img src="${window.location.origin + '/' + block.image}" alt="${blockName}-regulation">
                        <div class="d-flex gap-1 flex-column">
                          <h4 class="fw-bolder mb-0">${block.title}</h4>
                          <p class="mb-0">${block.description}</p>
                        </div>
                      </div>
                    </div>

                    <div class="d-flex py-2 justify-content-between px-2 list-document-effect"
                         id="${blockName}-regulation-customer-button">
                      <div><span class="fw-bold">${block.customerTitle}</span></div>
                      <div>
                      <img src="${window.location.origin}/assets/icons/entity/regulation/caret-right-regulation.svg" alt="caret-right-regulation">
                      </div>
                    </div>
                    <div class="d-flex py-2 justify-content-between px-2 list-document-effect"
                         id="${blockName}-regulation-provider-button">
                      <div><span class="fw-bold">${block.providerTitle}</span></div>
                      <div>
                      <img src="${window.location.origin}/assets/icons/entity/regulation/caret-right-regulation.svg" alt="caret-right-regulation">
                      </div>
                    </div>
                  </div>
                  <hr class="m-0">`;

            // Append the block HTML to the container element
            $('#list-regulation').append(blockHTML);

            $('#' + blockName + '-regulation-customer-button').click(
                (function (blockName, type) {
                    return async function () {
                        try {
                            let uriList;
                            if (blockName === 'trade') {
                                uriList = `/regulations/search?type=${0}&service_side=${0}`;
                            } else if (blockName === 'warehouse') {
                                uriList = `/regulations/search?type=${1}&service_side=${0}`;
                            } else if (blockName === 'transport') {
                                uriList = `/regulations/search?type=${2}&service_side=${0}`;
                            }

                            // Додайте await тут, щоб зачекати завершення запиту
                            await getResponse(url, uriList);
                            // Після додавання іконок до DOM

                            // Обробити дані
                            $('#selected-type').empty();
                            var selectedTypeBlock = renderSelectedType(blockName, type);
                            $('#selected-type').append(selectedTypeBlock);
                            $('#all-regulation').addClass('d-none');
                            $('#' + blockName + '-regulation-customer').removeClass('d-none');
                        } catch (error) {
                            console.log(error);
                            console.log('Дані пропали');
                        }
                    };
                })(blockName, 'customer')
            );

            $('#' + blockName + '-regulation-provider-button').click(
                (function (blockName, type) {
                    return async function () {
                        let uriList;
                        if (blockName === 'trade') {
                            uriList = `/regulations/search?type=${0}&service_side=${1}`;
                        } else if (blockName === 'warehouse') {
                            uriList = `/regulations/search?type=${1}&service_side=${1}`;
                        } else if (blockName === 'transport') {
                            uriList = `/regulations/search?type=${2}&service_side=${1}`;
                        }

                        await getResponse(url, uriList);

                        $('#selected-type').empty();
                        var selectedTypeBlock = renderSelectedType(blockName, type); // Pass blockName and type as arguments
                        // Append the selected type block to the container element
                        $('#selected-type').append(selectedTypeBlock);
                        // Add the 'd-none' class to the 'all-regulation' block
                        $('#all-regulation').addClass('d-none');
                        // Remove the 'd-none' class from the corresponding block
                        $('#' + blockName + '-regulation-provider').removeClass('d-none');
                    };
                })(blockName, 'provider')
            ); // Immediately invoke the function with blockName and type as arguments
        }
    }

    // Call the renderBlocks function to generate the blocks
    renderBlocks();

    function list(arrRegulation, blockName, type) {
        //console.log(arrRegulation)
        let parentRegulations = arrRegulation.regulations;

        let blockHTML = `
            <div class="container p-0" id="${blockName}-${type}-list">
            <ul class="list-group">
            ${parentRegulations
                .map((item, i) => {
                    const categoryName = item.name;
                    const regulationID = item.id;
                    const objectValues = item.draft;
                    const archiveRegulation = item.deleted_at;
                    const childRegulations = item.children;
                    return `
                    <li id="view-${blockName}-${type}-list-${regulationID}" data-id="${regulationID}" data-name="${categoryName}" class="list-group-item  border-bottom-0 d-flex flex-column py-0 px-0" style="line-height: 2.5em; position: static;">
                    <div class="d-flex justify-content-between w-100 list-group-item-parent py-50 px-2">
                                <div class="align-self-center">
                                    <p class="m-0 d-flex gap-1 jsTitleRegulation">
                                        <a href="#"  class="list-group-item-action w-auto fw-bold">${categoryName}</a>
                                        ${
                                            objectValues === 1 && archiveRegulation === null
                                                ? `<span class="px-75 py-50 gap-25 badge badge-light-danger d-inline-flex align-items-center flex-row-reverse">${getLocalizedText('regulationFunctionListStatusDraft')}</span>`
                                                : archiveRegulation !== null
                                                  ? `<span
                                            class="px-75 py-50 gap-25 badge badge-light-secondary d-inline-flex align-items-center flex-row-reverse">${getLocalizedText('regulationFunctionListStatusArchive')}</span>`
                                                  : objectValues === 0 &&
                                                      childRegulations.length > 0
                                                    ? `<span class="px-75 py-50 gap-25 badge badge-light-success d-inline-flex align-items-center flex-row-reverse"><img src="${window.location.origin}/assets/icons/entity/regulation/notes.svg" alt="notes">${item.contracts_count}</span>`
                                                    : ''
                                        }
                                    </p>

                                    <p class="mb-0 fw-bold text-secondary d-inline-flex align-items-center gap-1 js-title-parent-show">
                                        ${
                                            childRegulations.length !== 0
                                                ? ` ${getLocalizedText('regulationFunctionListChildRegulationsTitle')}
                                        (${childRegulations.length})
                                        <button class="btn p-0">
                                            <img class="chevron-icon rotate-180" src="${window.location.origin}/assets/icons/entity/regulation/chevron-up-regulation.svg" alt="chevron-up-regulation">
                                        </button>
                                        `
                                                : getLocalizedText(
                                                      'regulationFunctionListNoChildRegulationsTitle'
                                                  )
                                        }
                                    </p>
                                </div>
                                <div class="d-flex gap-1 align-items-center">
                                    <div>
                                        <img src="${window.location.origin}/assets/icons/entity/regulation/caret-right-regulation.svg" alt="caret-right-regulation">
                                    </div>
                                     <div>
                                       <img src="${window.location.origin}/assets/icons/entity/regulation/line-parent.svg" alt="line-parent">
                                    </div>
                                    <div class="d-inline-flex">
                                        <a class="dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <img src="${window.location.origin}/assets/icons/entity/regulation/dots-vertical-regulation.svg" alt="dots-vertical-regulation">
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end"
                                             id="dropdown-menu-type-${type}-${1}">
                                            <button id="duplicate_button" class="dropdown-item w-100">${getLocalizedText('regulationFunctionListDropdownAction_1')}</button>
                                            ${
                                                archiveRegulation === null &&
                                                childRegulations.length === 0
                                                    ? ` <button data-bs-toggle="modal" id="archive_button"
                                                    data-bs-target="#archive_regulation" type="submit"
                                                    class="dropdown-item w-100">${getLocalizedText('regulationFunctionListDropdownAction_2')}
                                            </button>`
                                                    : archiveRegulation !== null &&
                                                        childRegulations.length === 0
                                                      ? `  <button data-bs-toggle="modal" id="archive_button"
                                                    data-bs-target="#archive_regulation" type="submit"
                                                    class="dropdown-item w-100">${getLocalizedText('regulationFunctionListDropdownAction_3')}
                                            </button>
                                           `
                                                      : archiveRegulation === null &&
                                                          childRegulations.length !== 0
                                                        ? `<button data-bs-toggle="modal" id="no_archive_button"
                                                    data-bs-target="#no_archive_regulation" type="submit"
                                                    class="dropdown-item w-100">${getLocalizedText('regulationFunctionListDropdownAction_4')}
                                            </button>`
                                                        : `<button data-bs-toggle="modal" id="archive_button"
                                                    data-bs-target="#archive_regulation" type="submit"
                                                    class="dropdown-item w-100">${getLocalizedText('regulationFunctionListDropdownAction_5')}
                                            </button>`
                                            }

                                             ${
                                                 childRegulations.length === 0
                                                     ? `<button data-bs-toggle="modal" id="delete_button"
                                                    data-bs-target="#delete_regulation" type="submit"
                                                    class="text-danger dropdown-item w-100">${getLocalizedText('regulationFunctionListDropdownAction_6')}
                                            </button>`
                                                     : `<button data-bs-toggle="modal" id="no_delete_button"
                                                                data-bs-target="#no_delete_regulation" type="submit"
                                                                class="text-danger dropdown-item w-100">${getLocalizedText('regulationFunctionListDropdownAction_7')}
                                            </button>`
                                             }
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <ul class="ps-0 list-group child-list d-none">
                            ${
                                childRegulations.length > 0
                                    ? `
                            ${childRegulations
                                .map((childItem, j) => {
                                    const archiveItem = childItem.deleted_at;
                                    const objectValuesItem = childItem.draft;
                                    const childRegulationsItem = childItem.children;
                                    //console.log(childItem)
                                    return `
                                 <li id="view-${blockName}-${type}-list-${childItem.id}" data-id="${childItem.id}" data-name="${childItem.name}"
                                class="list-group-item list-group-item-child border-0 justify-content-between ps-3 pe-2"
                                style="line-height: 2.5em; position: static;">
                                <div class="align-self-center">
                                    <p class="m-0 d-flex align-items-center gap-1">
                                        <a href="#" class="list-group-item-action w-auto fw-bold">${childItem.name}</a>

                                      ${
                                          objectValuesItem === 1 && archiveItem === null
                                              ? `<span class="px-75 py-50 gap-25 badge badge-light-danger d-inline-flex align-items-center flex-row-reverse">${getLocalizedText('regulationFunctionListChildItemStatusDraft')}</span>`
                                              : archiveItem !== null
                                                ? `<span
                                            class="px-75 py-50 gap-25 badge badge-light-secondary d-inline-flex align-items-center flex-row-reverse">${getLocalizedText('regulationFunctionListChildItemStatusArchive')}</span>`
                                                : objectValuesItem === 0 &&
                                                    childRegulationsItem.length === 0
                                                  ? `<span class="px-75 py-50 gap-25 badge badge-light-success d-inline-flex align-items-center flex-row-reverse"><img src="${window.location.origin}/assets/icons/entity/regulation/notes.svg" alt="notes">${childItem.contracts_count}</span>`
                                                  : ''
                                      }

                                    </p>

                                </div>
                                <div class="d-flex gap-1 align-items-center jsTitleRegulation">
                                    <div>
                                        <img src="${window.location.origin}/assets/icons/entity/regulation/caret-right-regulation.svg" alt="caret-right-regulation">
                                    </div>
                                    <div>
                                       <img src="${window.location.origin}/assets/icons/entity/regulation/line-child.svg" alt="line-child">
                                    </div>
                                    <div class="d-inline-flex">
                                        <a class="dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <img src="${window.location.origin}/assets/icons/entity/regulation/dots-vertical-regulation.svg" alt="dots-vertical-regulation">
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end"
                                             id="dropdown-child-menu-type-${i}-${j}">
                                             <button id="duplicate_button" class="dropdown-item w-100">${getLocalizedText('regulationFunctionListChildItemDropdownAction_1')}</button>
                                            ${
                                                archiveItem === null &&
                                                childRegulationsItem.length === 0
                                                    ? ` <button data-bs-toggle="modal" id="archive_button"
                                                    data-bs-target="#archive_regulation" type="submit"
                                                    class="dropdown-item w-100">${getLocalizedText('regulationFunctionListChildItemDropdownAction_2')}
                                            </button>`
                                                    : archiveItem !== null &&
                                                        childRegulationsItem.length === 0
                                                      ? `  <button data-bs-toggle="modal" id="archive_button"
                                                    data-bs-target="#archive_regulation" type="submit"
                                                    class="dropdown-item w-100">${getLocalizedText('regulationFunctionListChildItemDropdownAction_3')}
                                            </button>
                                           `
                                                      : archiveItem === null &&
                                                          childRegulationsItem.length !== 0
                                                        ? `<button data-bs-toggle="modal" id="no_archive_button"
                                                    data-bs-target="#no_archive_regulation" type="submit"
                                                    class="dropdown-item w-100">${getLocalizedText('regulationFunctionListChildItemDropdownAction_4')}
                                            </button>`
                                                        : `<button data-bs-toggle="modal" id="archive_button"
                                                    data-bs-target="#archive_regulation" type="submit"
                                                    class="dropdown-item w-100">${getLocalizedText('regulationFunctionListChildItemDropdownAction_5')}
                                            </button>`
                                            }

                                             ${
                                                 childRegulationsItem.length === 0
                                                     ? `<button data-bs-toggle="modal" id="delete_button"
                                                    data-bs-target="#delete_regulation" type="submit"
                                                    class="text-danger dropdown-item w-100">${getLocalizedText('regulationFunctionListChildItemDropdownAction_6')}
                                            </button>`
                                                     : `<button data-bs-toggle="modal" id="no_delete_button"
                                                                data-bs-target="#no_delete_regulation" type="submit"
                                                                class="text-danger dropdown-item w-100">${getLocalizedText('regulationFunctionListChildItemDropdownAction_7')}
                                            </button>`
                                             }
                                        </div>
                                    </div>
                                </div>
                            </li>
                            `;
                                })
                                .join('')}
                `
                                    : ''
                            }
                    </li>
            </ul>
            `;
                })
                .join('')}
            </div>
            </ul>
`;
        $('body').off('click', `[id^="view-${blockName}-${type}-list-"]`);

        // Обробники подій для кнопок створення регламентів
        $('body').on('click', `[id^="view-${blockName}-${type}-list-"]`, async function (e) {
            var $target = $(e.target);
            var idParts = this.id.split('-');
            var i = idParts[idParts.length - 1];
            $('#view-regulation').empty();
            if (
                !$target.closest('.dropdown-toggle').length &&
                !$target.closest('.dropdown-menu').length &&
                !$target.closest('.dropdown-menu-group').length &&
                !$target.closest('.js-title-parent-show').length &&
                !$target.closest('.js-search-field').length
            ) {
                $('#view-regulation').empty();

                var titleRegulation = $(this).find('a').text();

                uriView = `/regulations/${i}`;

                await getResponseView(url, uriView);

                var viewTypeBlock = renderViewType(blockName, type, titleRegulation, regulation);
                $('#view-regulation').append(viewTypeBlock);
                $('#' + blockName + '-regulation-' + type).addClass('d-none');
                $('#view-' + blockName + '-regulation-' + type).removeClass('d-none');
            }
        });

        $('body').off('click', '.js-title-parent-show');

        $('body').on('click', '.js-title-parent-show', function (e) {
            const listItem = $(this).closest('.list-group-item');
            const childList = listItem.find('.child-list');
            const chevronIcon = $(this).find('.chevron-icon');

            childList.toggleClass('d-none');
            chevronIcon.toggleClass('rotate-180');
        });

        return blockHTML;
    }

    function renderSelectedType(blockName, type) {
        let headingText;
        let headingTextEmpty;

        if (type === 'customer') {
            if (blockName === 'trade') {
                headingText = getLocalizedText('regulationRenderSelectedTypeCustomerTradeTitle');
                headingTextEmpty = getLocalizedText(
                    'regulationRenderSelectedTypeCustomerTradeDesc'
                );
            } else if (blockName === 'warehouse') {
                headingText = getLocalizedText(
                    'regulationRenderSelectedTypeCustomerWarehouseTitle'
                );
                headingTextEmpty = getLocalizedText(
                    'regulationRenderSelectedTypeCustomerWarehouseDesc'
                );
            } else if (blockName === 'transport') {
                headingText = getLocalizedText(
                    'regulationRenderSelectedTypeCustomerTransportTitle'
                );
                headingTextEmpty = getLocalizedText(
                    'regulationRenderSelectedTypeCustomerTransportDesc'
                );
            }
        } else if (type === 'provider') {
            if (blockName === 'trade') {
                headingText = getLocalizedText('regulationRenderSelectedTypeProviderTradeTitle');
                headingTextEmpty = getLocalizedText(
                    'regulationRenderSelectedTypeProviderTradeDesc'
                );
            } else if (blockName === 'warehouse') {
                headingText = getLocalizedText(
                    'regulationRenderSelectedTypeProviderWarehouseTitle'
                );
                headingTextEmpty = getLocalizedText(
                    'regulationRenderSelectedTypeProviderWarehouseDesc'
                );
            } else if (blockName === 'transport') {
                headingText = getLocalizedText(
                    'regulationRenderSelectedTypeProviderTransportTitle'
                );
                headingTextEmpty = getLocalizedText(
                    'regulationRenderSelectedTypeProviderTransportDesc'
                );
            }
        }

        const headingHTML = `<h4 class="mb-0 fw-bolder">${headingText}</h4>`;
        const headingEmptyHTML = `<h4 class="fw-bolder">${headingTextEmpty}</h4>`;
        const heading = $(headingHTML);
        let listResult = null;
        if (arrRegulation.regulations.length === 0) {
            listResult = `
                <div class="d-flex justify-content-center align-items-center flex-column py-3">
                ${headingEmptyHTML}
                <p${getLocalizedText('regulationRenderSelectedDescCreate')}</p>
                 <button type="button" id="btn-create-${blockName}-regulation-${type}" class="btn btn-primary">
                 <img class="plus-icon" src="${window.location.origin}/assets/icons/entity/regulation/plus.svg" alt="plus">
                ${getLocalizedText('regulationRenderSelectedDescBtn')}
                </button>

                </div>`;
        } else {
            listResult = list(arrRegulation, blockName, type);
        }
        // Викликаємо функцію list і отримуємо результат
        const blockHTML = `
        <!-- Основний контейнер div -->
        <div class="d-none js-selected-regulation" data-blockname-id="${blockName}" data-type-id="${type}"id="${blockName}-regulation-${type}">
            <!-- Елементи внутрішнього блоку -->
            <div class="tab-title d-flex flex-row justify-content-between  align-items-center type-card-margin gap-50 mt-2 mb-1">
                <div class="d-flex align-items-center justify-content-center gap-50"><button class="btn back-to-all-regulation">
                    <img src="${window.location.origin}/assets/icons/entity/regulation/arrow-left-regulation.svg" alt="arrow-left-regulation">
                </button>
                <!-- Вставка згенерованого заголовку в h4 -->
                ${heading.prop('outerHTML')}</div>
                <div>
                ${
                    arrRegulation.regulations.length === 0
                        ? ``
                        : `
                <button type="button" id="btn-create-${blockName}-regulation-${type}" class="btn btn-flat-primary">
                ${getLocalizedText('regulationRenderSelectedDescBtn_2')}
                </button>`
                }
                </div>
            </div>

                ${
                    arrRegulation.regulations.length === 0
                        ? ``
                        : `<div class="tab-search type-card-margin mb-2" style="width: 300px;">
                <div class="input-group input-group-merge">
                    <span class="input-group-text" id="basic-addon-search2">
                        <img src="${window.location.origin}/assets/icons/entity/regulation/search-regulation.svg" alt="search-regulation">
                    </span>
                    <input type="text" class="form-control ps-1" placeholder="${getLocalizedText('regulationRenderSelectedSearchPlaceholder')}" aria-label="Search..."
                        aria-describedby="basic-addon-search2" id="search-regulation-${blockName}-${type}">
                </div>
            </div>`
                }

            <div class="card-body px-0 py-0">
                <div style="max-height: 707px; overflow-y: auto;">
                     ${listResult}
                </div>
            </div>
        </div>
        `;

        // Обробник події для кнопки "back-to-list-regulation"
        $('body').on('click', '.back-to-all-regulation', function () {
            $('#create-regulation').empty();
            $('#selected-type').empty();
            $('#all-regulation').removeClass('d-none');
            $('#' + blockName + '-regulation-provider').addClass('d-none');
            $('#' + blockName + '-regulation-customer').addClass('d-none');
        });
        $('body').off('click', '#btn-create-' + blockName + '-regulation-' + type);

        // Обробники подій для кнопок створення регламентів
        $('body').on(
            'click',
            '#btn-create-' + blockName + '-regulation-' + type,
            async function () {
                $('#create-regulation').empty();

                let uriList;
                if (blockName === 'trade' && type === 'customer') {
                    uriList = `/regulations/list?type=${0}&service_side=${0}`;
                } else if (blockName === 'trade' && type === 'provider') {
                    uriList = `/regulations/list?type=${0}&service_side=${1}`;
                } else if (blockName === 'warehouse' && type === 'customer') {
                    uriList = `/regulations/search?type=${1}&service_side=${0}`;
                } else if (blockName === 'warehouse' && type === 'provider') {
                    uriList = `/regulations/search?type=${1}&service_side=${1}`;
                } else if (blockName === 'transport' && type === 'customer') {
                    uriList = `/regulations/search?type=${2}&service_side=${0}`;
                } else if (blockName === 'transport' && type === 'provider') {
                    uriList = `/regulations/search?type=${2}&service_side=${1}`;
                }

                // Додайте await тут, щоб зачекати завершення запиту
                await getResponse(url, uriList);
                // Після додавання іконок до DOM

                var createTypeBlock = renderCreateType(blockName, type, arrRegulation, regulation); // Pass blockName and type as arguments
                // Append the selected type block to the container element
                $('#create-regulation').append(createTypeBlock);
                // Add the 'd-none' class to the 'all-regulation' block
                $('#' + blockName + '-regulation-' + type).addClass('d-none');
                $('#create-' + blockName + '-regulation-' + type).removeClass('d-none');
            }
        );

        $('body').on('input', `#search-regulation-${blockName}-${type}`, async function () {
            let typeSearch;
            let serviceSideSearch;

            if (type === 'customer') {
                serviceSideSearch = 0;
                if (blockName === 'trade') {
                    typeSearch = 0;
                } else if (blockName === 'warehouse') {
                    typeSearch = 1;
                } else if (blockName === 'transport') {
                    typeSearch = 2;
                }
            } else if (type === 'provider') {
                serviceSideSearch = 1;
                if (blockName === 'trade') {
                    typeSearch = 0;
                } else if (blockName === 'warehouse') {
                    typeSearch = 1;
                } else if (blockName === 'transport') {
                    typeSearch = 2;
                }
            }

            let searchValue = $(this).val();
            console.log(searchValue);

            let uriSearch = `/regulations/search?type=${typeSearch}&service_side=${serviceSideSearch}&name=${searchValue}`;
            let uriSearchAll = `/regulations/search?type=${typeSearch}&service_side=${serviceSideSearch}`;

            try {
                let searchResponse;

                // Перевірка наявності елементів у списку
                if ($('.list-group li').length > 0) {
                    searchResponse = await getResponse(url, uriSearch);
                    console.log(searchResponse);

                    $('.list-group li').each(function () {
                        const listItemText = $(this).find('a').text();
                        if (listItemText.includes(searchValue)) {
                            const listElement = $('#' + blockName + '-' + type + '-list');
                            listElement.empty();
                            listElement.html(list(searchResponse, blockName, type));
                        } else {
                            const listElement = $('#' + blockName + '-' + type + '-list');
                            listElement.empty();
                            listElement.html(list(searchResponse, blockName, type));
                        }
                    });
                } else {
                    if (searchValue.length < 1) {
                        searchResponse = await getResponse(url, uriSearchAll);
                    } else {
                        searchResponse = await getResponse(url, uriSearch);
                    }
                    $('#' + blockName + '-' + type + '-list')
                        .empty()
                        .html(list(searchResponse, blockName, type));
                }
            } catch (error) {
                console.error('Error:', error);
                // Handle the error here
            }
        });

        return blockHTML;
    }

    function renderCreateType(blockName, type, arrRegulation, regulation) {
        const typeName =
            type === 'customer'
                ? getLocalizedText('regulationRenderCreateTypeNameCustomer')
                : getLocalizedText('regulationRenderCreateTypeNameProvider');
        //console.log(regulation)
        const regulationTitles = {
            trade: getLocalizedText('regulationRenderCreateTypeRegulationTitlesTrade'),
            warehouse: getLocalizedText('regulationRenderCreateTypeRegulationTitlesWarehouse'),
            transport: getLocalizedText('regulationRenderCreateTypeRegulationTitlesTransport'),
        };

        const parentRegulations = arrRegulation.regulations;
        console.log(parentRegulations);
        const parentOptions = parentRegulations
            .map(
                (parent) => `
            <option ${arrRegulation.regulations.name ? 'selected' : ''} value="${parent.id}">${parent.name}</option>`
            )
            .join('');

        const typeOptions = blockArrTypePalets.map((type, index) => {
            return `<option value="${translit(type)}">${type}</option>`;
        });

        const block = `
        <div class="d-none" id="create-${blockName}-regulation-${type}">
            <div class="tab-title d-flex justify-content-between type-card-margin mt-2 mb-1">
                <div class="d-flex align-items-center gap-50">
                    <button class="btn back-to-list-regulation">
                    <img src="${window.location.origin}/assets/icons/entity/regulation/arrow-left-regulation.svg" alt="arrow-left-regulation">
                    </button>
                    <h4 class="mb-0 fw-bolder">
                        ${getLocalizedText('regulationRenderCreateTypeBlockTitle_1')} ${regulationTitles[blockName]} ${getLocalizedText('regulationRenderCreateTypeBlockTitle_2')}<br>(${typeName})
                    </h4>
                </div>
                <div class="d-flex align-items-center gap-50">
                    <button type="button" class="btn btn-flat-primary disabled" id="draft-regulation-btn">${getLocalizedText('regulationRenderCreateTypeBlockTitleBtnDraft')}</button>
                    <button type="button" class="btn btn-primary disabled" id="save-regulation-btn">${getLocalizedText('regulationRenderCreateTypeBlockTitleBtnSave')}</button>
                </div>
            </div>
            <hr class="mb-0">
            <div id="accordionWrapa1" role="tablist" aria-multiselectable="true">
                <div class="card mb-0">
                    <div class="card-body p-0">
                        <div class="accordion" id="accordionExample">
                            <!-- Accordion Item 1 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button fw-bolder px-2" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#accordionOne" aria-expanded="true"
                                        aria-controls="accordionOne">${getLocalizedText('regulationRenderCreateTypeBlockAccordionItemOneBtnTitle')}</button>
                                </h2>
                                <div id="accordionOne" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body ps-3 pt-1 pb-0">
                                        <div class="row mx-0">
                                            <div class="col-12 col-sm-6 mb-1">
                                                <input type="text" class="form-control" id="regulationName"
                                                    name="regulationName" placeholder="${getLocalizedText('regulationRenderCreateTypeBlockAccordionItemOneInputPlaceholder')}"
                                                    required>
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <select class="select2 hide-search form-select"  id="regulationType"
                                                    data-placeholder="${getLocalizedText('regulationRenderCreateTypeBlockAccordionItemOneSelectPlaceholder')}" >
                                                    <option value=""></option>
                                                    <option selected  value="0">${getLocalizedText('regulationRenderCreateTypeBlockAccordionItemOneSelectOptionParent')}</option>
                                                    ${parentOptions}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <!-- Accordion Item 2 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button fw-bolder px-2" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#accordionTwo" aria-expanded="true"
                                        aria-controls="accordionTwo">${getLocalizedText('regulationRenderCreateTypeBlockAccordionItemTwoBtnTitle')}</button>
                                </h2>
                                 <div id="accordionTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                     data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body ps-3 pt-1 pb-0">
                                        <div class="row mx-0">
                                             <div class="d-flex justify-content-between align-items-center col-12 mb-1">
                                             <label class=" mb-0 col-5">${getLocalizedText('regulationRenderCreateTypeBlockAccordionItemTwoLabel_1')}</label>

                                            <div class=" flex-grow-1 justify-content-end">
                                                <select class="select2 hide-search form-select" id="type"
                                                    data-placeholder="${getLocalizedText('regulationRenderCreateTypeBlockAccordionItemTwoSelectPlaceholder_1')}">
                                                    <option value=""></option>
                                                     ${typeOptions}
                                                </select>
                                            </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center col-12 mb-1"><label
                                                class=" mb-0 col-5">${getLocalizedText('regulationRenderCreateTypeBlockAccordionItemTwoLabel_2')}</label>
                                                <div class="row mx-0 flex-grow-1 justify-content-end">
                                                    <div class="col-12 px-0">
                                                        <div class="input-group">
                                                        <input type="number" class="form-control"
                                                               placeholder="${getLocalizedText('regulationRenderCreateTypeBlockAccordionItemTwoInputPlaceholder_2')}" maxLength="3"
                                                               id="height"><span class="input-group-text">${getLocalizedText('regulationRenderCreateTypeBlockAccordionItemTwoInputMeasureUnit_2')}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center col-12 mb-1"><label
                                                class=" mb-0 col-5">${getLocalizedText('regulationRenderCreateTypeBlockAccordionItemTwoLabel_3')}</label>
                                                <div class="row mx-0 flex-grow-1 justify-content-end">
                                                    <div class="col-12 px-0">
                                                        <div class="input-group"><input type="number" class="form-control"
                                                                                            placeholder="${getLocalizedText('regulationRenderCreateTypeBlockAccordionItemTwoInputPlaceholder_3')}" id="term"
                                                                                            maxLength="2"><span
                                                            class="input-group-text">${getLocalizedText('regulationRenderCreateTypeBlockAccordionItemTwoInputMeasureUnit_3')}</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 d-flex justify-content-between align-items-center col-12 mb-1"><label
                                                class="" for="pallet-sheet">${getLocalizedText('regulationRenderCreateTypeBlockAccordionItemTwoLabel_4')}</label>
                                                <div class="form-check form-check-warning form-switch"><input type="checkbox"
                                                                                                                  class="form-check-input checkbox"
                                                                                                                  id="pallet-sheet"></div>
                                            </div>
                                            <div class="mt-1 d-flex justify-content-between align-items-center col-12 mb-1"><label
                                                class="" for="prefabricated-pallets">${getLocalizedText('regulationRenderCreateTypeBlockAccordionItemTwoLabel_5')}</label>
                                                <div class="form-check form-check-warning form-switch"><input type="checkbox"
                                                                                                                  class="form-check-input checkbox"
                                                                                                                  id="prefabricated-pallets"></div>
                                            </div>
                                            <div class="mt-1 d-flex justify-content-between align-items-center col-12 mb-1"><label
                                                class="" for="sandwich-pallet">${getLocalizedText('regulationRenderCreateTypeBlockAccordionItemTwoLabel_6')}</label>
                                                <div class="form-check form-check-warning form-switch"><input type="checkbox"
                                                                                                                  class="form-check-input checkbox"
                                                                                                                  id="sandwich-pallet"></div>
                                            </div>
                                            <div class="mt-1 d-flex justify-content-between align-items-center col-12 mb-1"><label
                                                class="" for="stickering">${getLocalizedText('regulationRenderCreateTypeBlockAccordionItemTwoLabel_7')}</label>
                                                <div class="form-check form-check-warning form-switch"><input type="checkbox"
                                                                                                                  class="form-check-input checkbox"
                                                                                                                  id="stickering"></div>
                                            </div>
                                            <div class="mt-1 d-flex justify-content-between align-items-center col-12 mb-1"><label
                                                class="" for="allowHolding">${getLocalizedText('regulationRenderCreateTypeBlockAccordionItemTwoLabel_8')}</label>
                                                <div class="form-check form-check-warning form-switch"><input type="checkbox"
                                                                                                                  class="form-check-input checkbox"
                                                                                                                  id="allowHolding"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        // ... Event handlers and other code ...

        // Після додавання блоку до DOM:
        const blockElement = $(block); // Перетворення рядка розмітки на jQuery елемент
        blockElement.find('#regulationType').select2(); // Ініціалізація Select2 для першого select
        blockElement.find('#type').select2(); // Ініціалізація Select2 для другого select

        // ... ваш код обробників подій ...

        // Обробник події для кнопки "back-to-list-regulation"
        $('body').on('click', '.back-to-list-regulation', function () {
            $('#create-regulation').empty();
            $('#create-' + blockName + '-regulation-' + type).addClass('d-none');
            $('#' + blockName + '-regulation-' + type).removeClass('d-none');
        });

        $('body').off('click', '#save-regulation-btn');

        $('body').on('click', '#save-regulation-btn', async function (e) {
            var regulationName = $('#regulationName').val();

            var regulationType = $('#regulationType').val();
            if (regulationType === 'parent') {
                regulationType = null;
            }
            var typeBlockName = '';
            var typeServiceSide = '';
            var draft = 0;
            const settingCreate = {
                typePalet: $('#type').val(),
                heightPalet: $('#height').val(),
                overheadTerm: $('#term').val(),
                palletSheet: $('#pallet-sheet').prop('checked'),
                allowPrefabPallets: $('#prefabricated-pallets').prop('checked'),
                allowSandwichPallet: $('#sandwich-pallet').prop('checked'),
                stickering: $('#stickering').prop('checked'),
                allowHolding: $('#allowHolding').prop('checked'),
            };

            if (blockName === 'trade') {
                typeBlockName = 0;
            } else if (blockName === 'warehouse') {
                typeBlockName = 1;
            } else if (blockName === 'transport') {
                typeBlockName = 2;
            }

            if (type === 'customer') {
                typeServiceSide = 0;
            } else if (type === 'provider') {
                typeServiceSide = 1;
            }
            createRegulation(
                typeBlockName,
                typeServiceSide,
                draft,
                settingCreate,
                regulationName,
                regulationType
            );

            let uriList;
            if (blockName === 'trade') {
                if (type === 'customer') {
                    uriList = `/regulations/search?type=${0}&service_side=${0}`;
                } else if (type === 'provider') {
                    uriList = `/regulations/search?type=${0}&service_side=${1}`;
                }
            } else if (blockName === 'warehouse') {
                if (type === 'customer') {
                    uriList = `/regulations/search?type=${1}&service_side=${0}`;
                } else if (type === 'provider') {
                    uriList = `/regulations/search?type=${1}&service_side=${1}`;
                }
            } else if (blockName === 'transport') {
                if (type === 'customer') {
                    uriList = `/regulations/search?type=${2}&service_side=${0}`;
                } else if (type === 'provider') {
                    uriList = `/regulations/search?type=${2}&service_side=${1}`;
                }
            }

            arrRegulation = null;
            // Додайте await тут, щоб зачекати завершення запиту
            await getResponse(url, uriList);
            // Після додавання іконок до DOM

            // Обробити дані
            $('#selected-type').empty();
            var selectedTypeBlock = renderSelectedType(blockName, type);
            $('#selected-type').append(selectedTypeBlock);
            $('#create-regulation').empty();
            $('#' + blockName + '-regulation-' + type).removeClass('d-none');
            uriList = null;
            // Add the 'd-none' class to the 'all-regulation' block
        });

        $('body').off('click', '#draft-regulation-btn');

        $('body').on('click', '#draft-regulation-btn', async function (e) {
            var regulationName = $('#regulationName').val();

            var regulationType = $('#regulationType').val();
            if (regulationType === 'parent') {
                regulationType = null;
            }
            var typeBlockName = '';
            var typeServiceSide = '';
            var draft = 1;
            const settingCreate = {
                typePalet: $('#type').val(),
                heightPalet: $('#height').val(),
                overheadTerm: $('#term').val(),
                palletSheet: $('#pallet-sheet').prop('checked'),
                allowPrefabPallets: $('#prefabricated-pallets').prop('checked'),
                allowSandwichPallet: $('#sandwich-pallet').prop('checked'),
                stickering: $('#stickering').prop('checked'),
                allowHolding: $('#allowHolding').prop('checked'),
            };

            if (blockName === 'trade') {
                typeBlockName = 0;
            } else if (blockName === 'warehouse') {
                typeBlockName = 1;
            } else if (blockName === 'transport') {
                typeBlockName = 2;
            }

            if (type === 'customer') {
                typeServiceSide = 0;
            } else if (type === 'provider') {
                typeServiceSide = 1;
            }
            createRegulation(
                typeBlockName,
                typeServiceSide,
                draft,
                settingCreate,
                regulationName,
                regulationType
            );

            let uriList;
            if (blockName === 'trade') {
                if (type === 'customer') {
                    uriList = `/regulations/search?type=${0}&service_side=${0}`;
                } else if (type === 'provider') {
                    uriList = `/regulations/search?type=${0}&service_side=${1}`;
                }
            } else if (blockName === 'warehouse') {
                if (type === 'customer') {
                    uriList = `/regulations/search?type=${1}&service_side=${0}`;
                } else if (type === 'provider') {
                    uriList = `/regulations/search?type=${1}&service_side=${1}`;
                }
            } else if (blockName === 'transport') {
                if (type === 'customer') {
                    uriList = `/regulations/search?type=${2}&service_side=${0}`;
                } else if (type === 'provider') {
                    uriList = `/regulations/search?type=${2}&service_side=${1}`;
                }
            }

            arrRegulation = null;
            // Додайте await тут, щоб зачекати завершення запиту
            await getResponse(url, uriList);
            // Після додавання іконок до DOM

            // Обробити дані
            $('#selected-type').empty();
            var selectedTypeBlock = renderSelectedType(blockName, type);
            $('#selected-type').append(selectedTypeBlock);
            $('#create-regulation').empty();
            $('#' + blockName + '-regulation-' + type).removeClass('d-none');
            uriList = null;

            // Add the 'd-none' class to the 'all-regulation' block
        });

        return blockElement;
    }

    function createRegulation(
        typeBlockName,
        typeServiceSide,
        draft,
        settingCreate,
        regulationName,
        regulationType
    ) {
        let formData = new FormData();

        formData.append('_token', csrf);
        formData.append('name', regulationName);
        formData.append('type', typeBlockName);
        formData.append('service_side', typeServiceSide);
        formData.append('parent_id', regulationType);
        formData.append('settings', JSON.stringify(settingCreate));
        formData.append('draft', draft);

        sendRequest(url, uriCreate, 'POST', formData);
    }

    function renderEditType(blockName, type, titleRegulation, regulation, arrRegulation) {
        //console.log(arrRegulation)
        const typeName =
            type === 'customer'
                ? getLocalizedText('regulationRenderEditTypeNameCustomer')
                : getLocalizedText('regulationRenderEditTypeNameProvider');
        const settings = JSON.parse(regulation.regulation.settings);
        const parentRegulations = arrRegulation.regulations;
        const parentOptions = parentRegulations
            .map(
                (parent) => `
        <option ${regulation.parentName === arrRegulation.regulations.name ? 'selected' : ''} value="${parent.id}">${parent.name}</option>
    `
            )
            .join('');

        const typeOptions = blockArrTypePalets.map((type, index) => {
            const isSelected = settings.typePalet === translit(type) ? 'selected' : '';
            return `<option value="${translit(type)}" ${isSelected}>${type}</option>`;
        });

        const regulationTitles = {
            trade: getLocalizedText('regulationRenderEditTypeRegulationTitlesTrade'),
            warehouse: getLocalizedText('regulationRenderEditTypeRegulationTitlesWarehouse'),
            transport: getLocalizedText('regulationRenderEditTypeRegulationTitlesTransport'),
        };

        var targetRegulationName = regulation.regulation.name; // Замініть це значення на потрібне ім'я
        var boolChildren;

        // Знайдіть регламент за іменем у масиві регламентів
        var targetRegulation = null;
        for (var i = 0; i < arrRegulation.regulations.length; i++) {
            if (arrRegulation.regulations[i].name === targetRegulationName) {
                targetRegulation = arrRegulation.regulations[i];
                break;
            }
        }

        //console.log(targetRegulation)
        // Перевірте, чи знайдено регламент і чи має він дітей
        if (targetRegulation) {
            if (targetRegulation.children.length > 0) {
                //console.log("Регламент має дітей.");
                boolChildren = true;
            } else {
                //console.log("Регламент не має дітей.");
                boolChildren = false;
            }
        } else {
            //console.log("Регламент не знайдено.");
        }

        const block = `
        <div class="d-none js-view-regulation" data-id="${regulation.regulation.id}" data-blockname-id="${blockName}" data-type-id="${type}" id="edit-${blockName}-regulation-${type}">
            <div class="tab-title d-flex justify-content-between type-card-margin mt-2 mb-1">
                <div class="d-flex align-items-center gap-50">
                    <button class="btn back-to-list-regulation">
                    <img src="${window.location.origin}/assets/icons/entity/regulation/arrow-left-regulation.svg" alt="arrow-left-regulation">
                    </button>
                    <h4 class="mb-0 fw-bolder">
                        ${getLocalizedText('regulationRenderEditTypeBlockTitle_1')} ${regulationTitles[blockName]} ${getLocalizedText('regulationRenderEditTypeBlockTitle_2')} <br> ${regulation.regulation.name}(${typeName})
                    </h4>
                </div>
                  <div class="d-flex align-items-center gap-50">
                    <button type="button" class="btn btn-flat-primary disabled" id="edit-draft-regulation-btn">${getLocalizedText('regulationRenderEditTypeBlockTitleBtnDraft')}</button>
                   ${
                       boolChildren === true
                           ? `<button data-bs-toggle="modal" type="button" data-bs-target="#save_edit_regulation"  class="btn btn-primary disabled" id="edit-regulation-btn-modal">${getLocalizedText('regulationRenderEditTypeBlockTitleBtnModal')}</button>`
                           : `<button type="button" class="btn btn-primary disabled" id="edit-regulation-btn">${getLocalizedText('regulationRenderEditTypeBlockTitleBtnSave')}</button>`
                   }
                </div>
            </div>
            <hr class="mb-0">
            <div id="accordionWrapa1" role="tablist" aria-multiselectable="true">
                <div class="card mb-0">
                    <div class="card-body p-0">
                        <div class="accordion" id="accordionExample">
                            <!-- Accordion Item 1 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button fw-bolder px-2" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#accordionOne" aria-expanded="true"
                                        aria-controls="accordionOne">${getLocalizedText('regulationRenderEditTypeBlockAccordionItemOneBtnTitle')}</button>
                                </h2>
                                <div id="accordionOne" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body ps-3 pt-1 pb-0">
                                        <div class="row mx-0">
                                            <div class="col-12 col-sm-6 mb-1">
                                                <input type="text" class="form-control" id="regulationName"
                                                    name="regulationName" placeholder="${getLocalizedText('regulationRenderEditTypeBlockAccordionItemOneInputPlaceholder')}"
                                                    required value="${regulation.regulation.name}">
                                            </div>
                                            <div class="col-12 col-sm-6 mb-1">
                                                <select class="select2 hide-search form-select" id="regulationType"
                                                    data-placeholder="${getLocalizedText('regulationRenderEditTypeBlockAccordionItemOneSelectPlaceholder')}" >
                                                    <option value=""></option>
                                                    <option ${regulation.parentName === null ? 'selected' : ''} value="parent">${getLocalizedText('regulationRenderEditTypeBlockAccordionItemOneSelectOptionParent')}</option>
                                                    ${parentOptions}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Accordion Item 2 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button fw-bolder px-2" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#accordionTwo" aria-expanded="true"
                                        aria-controls="accordionTwo">${getLocalizedText('regulationRenderEditTypeBlockAccordionItemTwoBtnTitle')}</button>
                                </h2>
                                 <div id="accordionTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                     data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body ps-3 pt-1 pb-0">
                                        <div class="row mx-0">
                                             <div class="d-flex justify-content-between align-items-center col-12 mb-1"><label
                                                     class=" mb-0 col-5">${getLocalizedText('regulationRenderEditTypeBlockAccordionItemTwoLabel_1')}</label>

                                             <div class="flex-grow-1 justify-content-end">
                                                <select class="select2 hide-search form-select" id="type"
                                                    data-placeholder="${getLocalizedText('regulationRenderEditTypeBlockAccordionItemTwoSelectPlaceholder_1')}">
                                                    <option value=""></option>
                                                    ${typeOptions}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center col-12 mb-1"><label
                                            class=" mb-0 col-5">${getLocalizedText('regulationRenderEditTypeBlockAccordionItemTwoLabel_2')}</label>
                                            <div class="row mx-0 flex-grow-1 justify-content-end">
                                                <div class="col-12 px-0">
                                                    <div class="input-group"><input value="${settings.heightPalet}" type="number" class="form-control"
                                                                                        placeholder="${getLocalizedText('regulationRenderEditTypeBlockAccordionItemTwoInputPlaceholder_2')}" maxLength="3"
                                                                                        id="height"><span class="input-group-text">${getLocalizedText('regulationRenderEditTypeBlockAccordionItemTwoInputMeasureUnit_2')}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center col-12 mb-1"><label
                                            class=" mb-0 col-5">${getLocalizedText('regulationRenderEditTypeBlockAccordionItemTwoLabel_3')}</label>
                                            <div class="row mx-0 flex-grow-1 justify-content-end">
                                                <div class="col-12 px-0">
                                                    <div class="input-group"><input  value="${settings.overheadTerm}" type="number" class="form-control"
                                                                                        placeholder="${getLocalizedText('regulationRenderEditTypeBlockAccordionItemTwoInputPlaceholder_3')}" id="term"
                                                                                        maxLength="2"><span
                                                        class="input-group-text">${getLocalizedText('regulationRenderEditTypeBlockAccordionItemTwoInputMeasureUnit_3')}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-1 d-flex justify-content-between align-items-center col-12 mb-1"><label
                                            class="" for="pallet-sheet">${getLocalizedText('regulationRenderEditTypeBlockAccordionItemTwoLabel_4')}</label>
                                            <div class="form-check form-check-warning form-switch"><input ${settings.palletSheet ? 'checked' : ''}  type="checkbox"
                                                                                                              class="form-check-input checkbox"
                                                                                                              id="pallet-sheet"></div>
                                        </div>
                                        <div class="mt-1 d-flex justify-content-between align-items-center col-12 mb-1"><label
                                            class="" for="prefabricated-pallets">${getLocalizedText('regulationRenderEditTypeBlockAccordionItemTwoLabel_5')}</label>
                                            <div class="form-check form-check-warning form-switch"><input ${settings.allowPrefabPallets ? 'checked' : ''}  type="checkbox"
                                                                                                              class="form-check-input checkbox"
                                                                                                              id="prefabricated-pallets"></div>
                                        </div>
                                        <div class="mt-1 d-flex justify-content-between align-items-center col-12 mb-1"><label
                                            class="" for="sandwich-pallet">${getLocalizedText('regulationRenderEditTypeBlockAccordionItemTwoLabel_6')}</label>
                                            <div class="form-check form-check-warning form-switch"><input ${settings.allowSandwichPallet ? 'checked' : ''} type="checkbox"
                                                                                                              class="form-check-input checkbox"
                                                                                                              id="sandwich-pallet"></div>
                                        </div>
                                        <div class="mt-1 d-flex justify-content-between align-items-center col-12 mb-1"><label
                                            class="" for="stickering">${getLocalizedText('regulationRenderEditTypeBlockAccordionItemTwoLabel_7')}</label>
                                            <div class="form-check form-check-warning form-switch"><input ${settings.stickering ? 'checked' : ''} type="checkbox"
                                                                                                              class="form-check-input checkbox"
                                                                                                              id="stickering"></div>
                                        </div>
                                        <div class="mt-1 d-flex justify-content-between align-items-center col-12 mb-1"><label
                                            class="" for="allowHolding">${getLocalizedText('regulationRenderEditTypeBlockAccordionItemTwoLabel_8')}</label>
                                            <div class="form-check form-check-warning form-switch"><input ${settings.allowHolding ? 'checked' : ''} type="checkbox"
                                                                                                              class="form-check-input checkbox"
                                                                                                              id="allowHolding"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        // ... Event handlers and other code ...

        // Після додавання блоку до DOM:
        const blockElement = $(block); // Перетворення рядка розмітки на jQuery елемент
        blockElement.find('#regulationType').select2(); // Ініціалізація Select2 для першого select
        blockElement.find('#type').select2(); // Ініціалізація Select2 для другого select

        // ... ваш код обробників подій ...

        // Обробник події для кнопки "back-to-list-regulation"
        $('body').on('click', '.back-to-list-regulation', function () {
            $('#edit-' + blockName + '-regulation-' + type).addClass('d-none');
            $('#' + blockName + '-regulation-' + type).removeClass('d-none');
        });

        $('body').off('click', '#edit-regulation-btn');

        $('body').on('click', '#edit-regulation-btn', async function (e) {
            var idRegulation = regulation.regulation.id;
            var regulationName = $('#regulationName').val();

            var regulationType = $('#regulationType').val();
            if (regulationType === 'parent') {
                regulationType = null;
            }

            var typeBlockName = '';
            var typeServiceSide = '';
            var draft = 0;
            const settingCreate = {
                typePalet: $('#type').val(),
                heightPalet: $('#height').val(),
                overheadTerm: $('#term').val(),
                palletSheet: $('#pallet-sheet').prop('checked'),
                allowPrefabPallets: $('#prefabricated-pallets').prop('checked'),
                allowSandwichPallet: $('#sandwich-pallet').prop('checked'),
                stickering: $('#stickering').prop('checked'),
                allowHolding: $('#allowHolding').prop('checked'),
            };

            if (blockName === 'trade') {
                typeBlockName = 0;
            } else if (blockName === 'warehouse') {
                typeBlockName = 1;
            } else if (blockName === 'transport') {
                typeBlockName = 2;
            }

            if (type === 'customer') {
                typeServiceSide = 0;
            } else if (type === 'provider') {
                typeServiceSide = 1;
            }
            editRegulation(
                typeBlockName,
                typeServiceSide,
                draft,
                settingCreate,
                regulationName,
                regulationType,
                idRegulation
            );

            let uriList;
            if (blockName === 'trade') {
                if (type === 'customer') {
                    uriList = `/regulations/search?type=${0}&service_side=${0}`;
                } else if (type === 'provider') {
                    uriList = `/regulations/search?type=${0}&service_side=${1}`;
                }
            } else if (blockName === 'warehouse') {
                if (type === 'customer') {
                    uriList = `/regulations/search?type=${1}&service_side=${0}`;
                } else if (type === 'provider') {
                    uriList = `/regulations/search?type=${1}&service_side=${1}`;
                }
            } else if (blockName === 'transport') {
                if (type === 'customer') {
                    uriList = `/regulations/search?type=${2}&service_side=${0}`;
                } else if (type === 'provider') {
                    uriList = `/regulations/search?type=${2}&service_side=${1}`;
                }
            }

            arrRegulation = null;
            // Додайте await тут, щоб зачекати завершення запиту
            await getResponse(url, uriList);
            // Після додавання іконок до DOM

            // Обробити дані
            $('#selected-type').empty();
            var selectedTypeBlock = renderSelectedType(blockName, type);
            $('#selected-type').append(selectedTypeBlock);
            $('#edit-regulation').empty();
            $('#' + blockName + '-regulation-' + type).removeClass('d-none');
            uriList = null;
            $('#view-regulation').empty();
        });

        $('body').off('click', '#edit-draft-regulation-btn');

        $('body').on('click', '#edit-draft-regulation-btn', async function (e) {
            var idRegulation = regulation.regulation.id;
            var regulationName = $('#regulationName').val();

            var regulationType = $('#regulationType').val();
            if (regulationType === 'parent') {
                regulationType = null;
            }
            var typeBlockName = '';
            var typeServiceSide = '';
            var draft = 1;
            const settingCreate = {
                typePalet: $('#type').val(),
                heightPalet: $('#height').val(),
                overheadTerm: $('#term').val(),
                palletSheet: $('#pallet-sheet').prop('checked'),
                allowPrefabPallets: $('#prefabricated-pallets').prop('checked'),
                allowSandwichPallet: $('#sandwich-pallet').prop('checked'),
                stickering: $('#stickering').prop('checked'),
                allowHolding: $('#allowHolding').prop('checked'),
            };

            if (blockName === 'trade') {
                typeBlockName = 0;
            } else if (blockName === 'warehouse') {
                typeBlockName = 1;
            } else if (blockName === 'transport') {
                typeBlockName = 2;
            }

            if (type === 'customer') {
                typeServiceSide = 0;
            } else if (type === 'provider') {
                typeServiceSide = 1;
            }

            editRegulation(
                typeBlockName,
                typeServiceSide,
                draft,
                settingCreate,
                regulationName,
                regulationType,
                idRegulation
            );

            let uriList;
            if (blockName === 'trade') {
                if (type === 'customer') {
                    uriList = `/regulations/search?type=${0}&service_side=${0}`;
                } else if (type === 'provider') {
                    uriList = `/regulations/search?type=${0}&service_side=${1}`;
                }
            } else if (blockName === 'warehouse') {
                if (type === 'customer') {
                    uriList = `/regulations/search?type=${1}&service_side=${0}`;
                } else if (type === 'provider') {
                    uriList = `/regulations/search?type=${1}&service_side=${1}`;
                }
            } else if (blockName === 'transport') {
                if (type === 'customer') {
                    uriList = `/regulations/search?type=${2}&service_side=${0}`;
                } else if (type === 'provider') {
                    uriList = `/regulations/search?type=${2}&service_side=${1}`;
                }
            }

            arrRegulation = null;
            // Додайте await тут, щоб зачекати завершення запиту
            await getResponse(url, uriList);
            // Після додавання іконок до DOM

            // Обробити дані
            $('#selected-type').empty();
            var selectedTypeBlock = renderSelectedType(blockName, type);
            $('#selected-type').append(selectedTypeBlock);
            $('#edit-regulation').empty();
            $('#' + blockName + '-regulation-' + type).removeClass('d-none');
            uriList = null;
            $('#view-regulation').empty();
        });

        return blockElement;
    }

    function editRegulation(
        typeBlockName,
        typeServiceSide,
        draft,
        settingCreate,
        regulationName,
        regulationType,
        idRegulation,
        changeDescendants = 0
    ) {
        let formData = new FormData();
        const uriEdit = `/regulations/${idRegulation}`;

        formData.append('_token', csrf);
        formData.append('_method', 'PUT');
        formData.append('name', regulationName);
        formData.append('change_descendants', changeDescendants);
        formData.append('type', typeBlockName);
        formData.append('service_side', typeServiceSide);
        formData.append('parent_id', regulationType);
        if (settingCreate !== null) {
            formData.append('settings', JSON.stringify(settingCreate));
        }
        formData.append('draft', draft);

        sendResponseActionEdit(url, uriEdit, 'POST', formData);
    }

    function renderViewType(blockName, type, titleRegulation, regulation) {
        const typeTitle =
            type === 'customer'
                ? getLocalizedText('regulationRenderViewTypeNameCustomer')
                : getLocalizedText('regulationRenderViewTypeNameProvider');
        //console.log(regulation)
        const settings = JSON.parse(regulation.regulation.settings);
        //console.log(settings)

        const regulationTitles = {
            trade: getLocalizedText('regulationRenderViewTypeRegulationTitlesTrade'),
            warehouse: getLocalizedText('regulationRenderViewTypeRegulationTitlesWarehouse'),
            transport: getLocalizedText('regulationRenderViewTypeRegulationTitlesTransport'),
        };

        const translitItemArrTypePalets = blockArrTypePalets.map((item) => translit(item)); // Транслітеруємо всі значення з blockArrTypePalets
        const paletTranslit = settings.typePalet;

        //console.log(translitItemArrTypePalets); // Транслітеровані значення
        //console.log(palet);

        let currentItemTypePalets = null;

        // Порівнюємо palet з транслітерованими значеннями
        for (let i = 0; i < translitItemArrTypePalets.length; i++) {
            if (paletTranslit === translitItemArrTypePalets[i]) {
                currentItemTypePalets = blockArrTypePalets[i]; // Якщо співпадіння, записуємо в змінну
                break; // Виходимо з циклу, якщо знайдено співпадіння
            }
        }

        if (currentItemTypePalets !== null) {
            //console.log(currentItemTypePalets); // Виводимо значення, яке не транслітероване
        } else {
            //console.log("Співпадінь не знайдено");
        }

        const container = $(`
         <div class="d-none js-view-regulation" data-blockname-id="${blockName}" data-type-id="${type}" id="view-${blockName}-regulation-${type}">
                                <div class="tab-title d-flex justify-content-between type-card-margin mt-2 mb-1">
                                    <div class="d-flex align-items-center gap-50">
                                        <button class="btn back-to-list-regulation">
                                            <img src="${window.location.origin}/assets/icons/entity/regulation/arrow-left-regulation.svg" alt="arrow-left-regulation">
                                        </button>
                                        <h4 class="mb-0 fw-bolder">
                                            ${getLocalizedText('regulationRenderViewTypeBlockTitle_1')} ${regulationTitles[blockName]} ${getLocalizedText('regulationRenderViewTypeBlockTitle_2')}<br>“${regulation.regulation.name}”
                                            ${typeTitle}
                                        </h4>
                                    </div>
                                    <div>
                                        <button type="button" class="btn-flat-primary btn"
                                                id="edit-${blockName}-regulation-${type}">
                                            ${getLocalizedText('regulationRenderViewTypeBlockTitleBtnEdit')}
                                        </button>
                                    </div>
                                </div>
                                <hr class="mb-0">
                                <div id="accordionWrapa1" role="tablist" aria-multiselectable="true">
                                    <div class="card mb-0">
                                        <div class="card-body p-0">
                                            <div class="accordion" id="accordionExample">
                                                <!-- Accordion Item 1 -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingOne">
                                                        <button class="accordion-button fw-bolder px-2" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#accordionOne"
                                                                aria-expanded="true"
                                                                aria-controls="accordionOne">${getLocalizedText('regulationRenderViewTypeBlockAccordionItemOneBtnTitle')}
                                                        </button>
                                                    </h2>
                                                    <div id="accordionOne" class="accordion-collapse collapse show"
                                                         aria-labelledby="headingOne"
                                                         data-bs-parent="#accordionExample">
                                                        <div class="accordion-body ps-3 pt-1 pb-0">

                                                            <div class="row mx-0">
                                                                <div class="col-12 mb-1 d-flex justify-content-between">
                                                                    <h5 class="fw-normal text-secondary">${getLocalizedText('regulationRenderViewTypeBlockAccordionItemOneName')}</h5>
                                                                    <p class="fw-bold">${regulation.regulation.name}</p>
                                                                </div>
                                                                <div class="col-12 mb-1 d-flex justify-content-between">
                                                                    <h5 class="fw-normal text-secondary">${getLocalizedText('regulationRenderViewTypeBlockAccordionItemOneIsParent')}</h5>
                                                                        ${regulation.parentName !== null ? `<p class="fw-bold text-decoration-underline"><a id="parent-link-regulation">${regulation.parentName}</a></p>` : '-'}
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Accordion Item 1 -->
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingOne">
                                                        <button class="accordion-button fw-bolder px-2" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#accordionTwo"
                                                                aria-expanded="true"
                                                                aria-controls="accordionTwo">${getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoBtnTitle')}
                                                        </button>
                                                    </h2>
                                                    <div id="accordionTwo" class="accordion-collapse collapse "
                                                         aria-labelledby="headingTwo"
                                                         data-bs-parent="#accordionExample" style="">
                                                        <div class="accordion-body ps-3 pt-1 pb-0">
                                                            <div class="row mx-0">
                                                                <div class="col-12 mb-1 d-flex justify-content-between">
                                                                    <h5 class="fw-normal text-secondary">${getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoLabel_1')}</h5>
                                                                    <p class="fw-bold">${currentItemTypePalets === null ? '-' : currentItemTypePalets}</p>
                                                                </div>
                                                                <div class="col-12 mb-1 d-flex justify-content-between">
                                                                    <h5 class="fw-normal text-secondary">${getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoLabel_2')}</h5>
                                                                    <p class="fw-bold">${settings.heightPalet === '' ? `-` : `${settings.heightPalet + ' ' + getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoInputMeasureUnit_2')}`}</p>
                                                                </div>
                                                                <div class="col-12 mb-1 d-flex justify-content-between">
                                                                    <h5 class="fw-normal text-secondary">${getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoLabel_3')}</h5>
                                                                    <p class="fw-bold">${settings.overheadTerm === '' ? `-` : `${settings.overheadTerm + ' ' + getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoInputMeasureUnit_3')}`}</p>
                                                                </div>
                                                                <div class="col-12 mb-1 d-flex justify-content-between">
                                                                    <h5 class="fw-normal text-secondary">${getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoLabel_4')}</h5>
                                                                    <p class="fw-bold">${settings.palletSheet ? getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoLabelYes_4') : getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoLabelNo_4')}</p>
                                                                </div>
                                                                <div class="col-12 mb-1 d-flex justify-content-between">
                                                                    <h5 class="fw-normal text-secondary">${getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoLabel_5')}</h5>
                                                                    <p class="fw-bold">${settings.allowPrefabPallets ? getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoLabelYes_5') : getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoLabelNo_5')}</p>
                                                                </div>
                                                                <div class="col-12 mb-1 d-flex justify-content-between">
                                                                    <h5 class="fw-normal text-secondary">${getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoLabel_6')}</h5>
                                                                    <p class="fw-bold">${settings.allowSandwichPallet ? getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoLabelYes_6') : getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoLabelNo_6')}</p>
                                                                </div>
                                                                <div class="col-12 mb-1 d-flex justify-content-between">
                                                                    <h5 class="fw-normal text-secondary">
                                                                        ${getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoLabel_7')}</h5>
                                                                    <p class="fw-bold">${settings.stickering ? getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoLabelYes_7') : getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoLabelNo_7')}</p>
                                                                </div>
                                                                <div class="col-12 mb-1 d-flex justify-content-between">
                                                                    <h5 class="fw-normal text-secondary">${getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoLabel_8')}</h5>
                                                                    <p class="fw-bold">${settings.allowHolding ? getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoLabelYes_8') : getLocalizedText('regulationRenderViewTypeBlockAccordionItemTwoLabelNo_8')}</p>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
         </div>

     `);

        // Обробник події для кнопки "back-to-list-regulation"
        container.on('click', '.back-to-list-regulation', function () {
            $('#view-' + blockName + '-regulation-' + type).addClass('d-none');
            $('#' + blockName + '-regulation-' + type).removeClass('d-none');
            $('#view-regulation').empty();
        });

        // Обробник події для кнопки "edit-regulation"
        container.on('click', '#edit-' + blockName + '-regulation-' + type, function () {
            $('#edit-regulation').empty();
            var editTypeBlock = renderEditType(
                blockName,
                type,
                titleRegulation,
                regulation,
                arrRegulation
            ); // Передайте blockName і type як аргументи
            $('#edit-regulation').append(editTypeBlock);
            $('#view-' + blockName + '-regulation-' + type).addClass('d-none');
            $('#edit-' + blockName + '-regulation-' + type).removeClass('d-none');
        });

        return container;
    }

    // Обробники подій для кнопок створення регламентів
    $('body').on('click', `#parent-link-regulation`, async function (e) {
        // Отримати значення data-blockName-id і data-type-id
        var dataBlockNameId = $('.js-view-regulation').data('blockname-id');
        var dataTypeId = $('.js-view-regulation').data('type-id');
        //console.log(dataBlockNameId, dataTypeId)

        let i = regulation.regulation.parentId;
        $('#view-regulation').empty();

        var titleRegulation = $(this).find('a').text();

        uriView = `/regulations/${i}`;

        await getResponseView(url, uriView);

        var viewTypeBlock = renderViewType(
            dataBlockNameId,
            dataTypeId,
            titleRegulation,
            regulation
        );
        $('#view-regulation').append(viewTypeBlock);
        $('#' + dataBlockNameId + '-regulation-' + dataTypeId).addClass('d-none');
        $('#view-' + dataBlockNameId + '-regulation-' + dataTypeId).removeClass('d-none');
    });

    async function sendResponseActionModal(url, uri, method = 'GET') {
        try {
            const apiUrl = url + uri;
            const requestOptions = {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': csrf,
                },
            };

            const response = await fetch(apiUrl, requestOptions);
            const data = await response.json();

            return data;
        } catch (error) {
            console.error('Error:', error);
            // Обробити помилку тут
            throw error; // Звернення помилки для подальшого оброблення
        }
    }

    $(body).off('click', '#archiveRule');
    // Обробник події для кнопки "Archive Rule" у модальному вікні
    $(body).on('click', '#archiveRule', async function () {
        var dataBlockNameId = $('.js-selected-regulation').data('blockname-id');
        var dataTypeId = $('.js-selected-regulation').data('type-id');

        if (selectedRegulation) {
            const foundIndex = regulation.regulation.id;
            console.log(foundIndex);
            const uriArchive = `/regulations/archive/${foundIndex}`;

            await sendResponseActionModal(url, uriArchive, 'DELETE');

            let uriList;
            if (dataBlockNameId === 'trade') {
                if (dataTypeId === 'customer') {
                    uriList = `/regulations/search?type=${0}&service_side=${0}`;
                } else if (dataTypeId === 'provider') {
                    uriList = `/regulations/search?type=${0}&service_side=${1}`;
                }
            } else if (dataBlockNameId === 'warehouse') {
                if (dataTypeId === 'customer') {
                    uriList = `/regulations/search?type=${1}&service_side=${0}`;
                } else if (dataTypeId === 'provider') {
                    uriList = `/regulations/search?type=${1}&service_side=${1}`;
                }
            } else if (dataBlockNameId === 'transport') {
                if (dataTypeId === 'customer') {
                    uriList = `/regulations/search?type=${2}&service_side=${0}`;
                } else if (dataTypeId === 'provider') {
                    uriList = `/regulations/search?type=${2}&service_side=${1}`;
                }
            }

            arrRegulation = null;
            // Додайте await тут, щоб зачекати завершення запиту
            await getResponse(url, uriList);
            // Після додавання іконок до DOM

            $('#selected-type').empty();
            // Обробити дані
            var selectedTypeBlock = renderSelectedType(dataBlockNameId, dataTypeId);
            $('#selected-type').append(selectedTypeBlock);
            $('#' + dataBlockNameId + '-regulation-' + dataTypeId).removeClass('d-none');
            uriList = null;
        }

        selectedRegulation = null; // Збираємо дані про об'єкт після виконання операції
        $('.modal').modal('hide');
    });

    $(body).off('click', '#deleteRegulation');
    // Обробник події для кнопки "Delete Rule" у модальному вікні
    $(body).on('click', '#deleteRegulation', async function () {
        var dataBlockNameId = $('.js-selected-regulation').data('blockname-id');
        var dataTypeId = $('.js-selected-regulation').data('type-id');

        if (selectedRegulation) {
            const foundIndex = regulation.regulation.id;
            console.log(foundIndex);
            const uriDelete = `/regulations/${foundIndex}`;
            await sendResponseActionModal(url, uriDelete, 'DELETE');

            let uriList;
            if (dataBlockNameId === 'trade') {
                if (dataTypeId === 'customer') {
                    uriList = `/regulations/search?type=${0}&service_side=${0}`;
                } else if (dataTypeId === 'provider') {
                    uriList = `/regulations/search?type=${0}&service_side=${1}`;
                }
            } else if (dataBlockNameId === 'warehouse') {
                if (dataTypeId === 'customer') {
                    uriList = `/regulations/search?type=${1}&service_side=${0}`;
                } else if (dataTypeId === 'provider') {
                    uriList = `/regulations/search?type=${1}&service_side=${1}`;
                }
            } else if (dataBlockNameId === 'transport') {
                if (dataTypeId === 'customer') {
                    uriList = `/regulations/search?type=${2}&service_side=${0}`;
                } else if (dataTypeId === 'provider') {
                    uriList = `/regulations/search?type=${2}&service_side=${1}`;
                }
            }

            arrRegulation = null;
            // Додайте await тут, щоб зачекати завершення запиту
            await getResponse(url, uriList);
            // Після додавання іконок до DOM

            $('#selected-type').empty();
            // Обробити дані
            var selectedTypeBlock = renderSelectedType(dataBlockNameId, dataTypeId);
            $('#selected-type').append(selectedTypeBlock);
            $('#' + dataBlockNameId + '-regulation-' + dataTypeId).removeClass('d-none');
            uriList = null;
        }

        selectedRegulation = null; // Збираємо дані про об'єкт після виконання операції
        $('.modal').modal('hide');
    });

    $(body).off('click', '#duplicate_button');
    // Обробник події для кнопки "duplicate_button Rule" у модальному вікні
    $(body).on('click', '#duplicate_button', async function () {
        var dataBlockNameId = $('.js-selected-regulation').data('blockname-id');
        var dataTypeId = $('.js-selected-regulation').data('type-id');

        const blockId = $(this).closest('.list-group-item').data('id');
        const regName = $(this).closest('.list-group-item').data('name');

        const parentUl = $(this).closest('ul.list-group');

        let newNameTitle;

        let uriViewFirstClick = `/regulations/${blockId}`;
        await getResponseView(url, uriViewFirstClick);
        //console.log(regulation)

        let selectedRegulationFirstClick = regulation.regulation;

        // Створити новий input
        const inputHtml = $(`
        <li class="d-flex border-bottom-0 flex-column py-0 px-0 js-search-field" id="searchField" style="line-height: 2.5em; position: static;">
                    <div class="d-flex  border-bottom-0 justify-content-between w-100  py-50 px-2">
                                <div class="align-self-center">
                                    <p class="m-0 d-flex gap-1">
                                            <input style="padding-top:${selectedRegulationFirstClick.parent_id !== null ? '0.6rem' : '0.8rem'}; padding-bottom:${selectedRegulationFirstClick.parent_id !== null ? '0.6rem' : '0.8rem'}" type="text" class="form-control" id="newNameTitle" value="${regName + getLocalizedText('regulationDuplicateButtonInputHtmlValue')}" placeholder="${getLocalizedText('regulationDuplicateButtonInputHtmlPlaceholder')}">
                                    </p>
                                </div>
                                <div class="d-flex gap-1 align-items-center">
                                    <div>
                                        <img src="${window.location.origin}/assets/icons/entity/regulation/unactive-caret-right.svg" alt="unactive-caret-right">
                                    </div>
                                     <div>
                                     ${
                                         selectedRegulationFirstClick.parent_id === null
                                             ? `<img src="${window.location.origin}/assets/icons/entity/regulation/line-parent.svg" alt="line-parent"> `
                                             : `<img src="${window.location.origin}/assets/icons/entity/regulation/line-child.svg" alt="line-child">`
                                     }


                        </div>
                            <div class="d-inline-flex">
                                <a class="dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <img src="${window.location.origin}/assets/icons/entity/regulation/unactive-dots-vertical.svg"
                                         alt="unactive-dots-vertical">
                                </a>

                            </div>
                        </div>
                    </div>
        </li>`);

        parentUl.append(inputHtml);

        // Focus on the input
        parentUl.find('input#newNameTitle').focus();

        const inputElement = parentUl.find('input#newNameTitle');

        async function newNameRegulation(newNameValueInput) {
            // Get the input value
            const newNameValue = newNameValueInput;
            // Remove the input block
            $(this).closest('li.js-search-field').remove();

            // Store the input value in the newNameTitle variable
            newNameTitle = newNameValue;
            //console.log(newNameTitle); // You can use newNameTitle as needed

            // Add your code here to use the newNameTitle variable as needed

            uriView = `/regulations/${blockId}`;
            await getResponseView(url, uriView);
            //console.log(regulation)

            selectedRegulation = regulation.regulation;

            if (selectedRegulation) {
                const foundIndex = regulation.regulation.id;
                //console.log(foundIndex);
                const uriDuplicate = `/regulations/duplicate/${foundIndex}`;
                const responseId = await sendResponseActionModal(url, uriDuplicate, 'POST');
                let regulationNewId = responseId.regulation_id;
                //console.log(regulationNewId)

                let uriList;
                if (dataBlockNameId === 'trade') {
                    if (dataTypeId === 'customer') {
                        uriList = `/regulations/search?type=${0}&service_side=${0}`;
                    } else if (dataTypeId === 'provider') {
                        uriList = `/regulations/search?type=${0}&service_side=${1}`;
                    }
                } else if (dataBlockNameId === 'warehouse') {
                    if (dataTypeId === 'customer') {
                        uriList = `/regulations/search?type=${1}&service_side=${0}`;
                    } else if (dataTypeId === 'provider') {
                        uriList = `/regulations/search?type=${1}&service_side=${1}`;
                    }
                } else if (dataBlockNameId === 'transport') {
                    if (dataTypeId === 'customer') {
                        uriList = `/regulations/search?type=${2}&service_side=${0}`;
                    } else if (dataTypeId === 'provider') {
                        uriList = `/regulations/search?type=${2}&service_side=${1}`;
                    }
                }

                let typeBlockName = null;
                let typeServiceSide = null;
                if (dataBlockNameId === 'trade') {
                    typeBlockName = 0;
                } else if (dataBlockNameId === 'warehouse') {
                    typeBlockName = 1;
                } else if (dataBlockNameId === 'transport') {
                    typeBlockName = 2;
                }

                if (dataTypeId === 'customer') {
                    typeServiceSide = 0;
                } else if (dataTypeId === 'provider') {
                    typeServiceSide = 1;
                }

                let foundParentId = regulation.regulation.parent_id;

                $('#selected-type').empty();

                editRegulation(
                    typeBlockName,
                    typeServiceSide,
                    0,
                    null,
                    newNameTitle,
                    foundParentId,
                    regulationNewId,
                    0
                );

                arrRegulation = null;

                // Додайте await тут, щоб зачекати завершення запиту
                await getResponse(url, uriList);

                // Обробити дані
                var selectedTypeBlock = await renderSelectedType(dataBlockNameId, dataTypeId);
                $('#selected-type').append(selectedTypeBlock);
                $('#' + dataBlockNameId + '-regulation-' + dataTypeId).removeClass('d-none');

                uriList = null;
                selectedRegulation = null;
            }
        }

        // Attach a keypress event handler to the input
        parentUl.on('keypress', 'input#newNameTitle', async function (e) {
            if (e.which === 13) {
                // Check if the Enter key is pressed
                e.preventDefault(); // Prevent the default form submission
                const newNameValue = $(this).val();
                newNameRegulation(newNameValue);
            }
        });

        // Attach a blur event handler to the input
        inputElement.on('blur', function () {
            // Handle focus loss
            const newNameValue = $(this).val();
            newNameRegulation(newNameValue);
        });

        uriViewFirstClick = null;
        selectedRegulationFirstClick = null;
    });

    // Обробник події для кнопок "Archive Button" та "Delete Button" для збереження обраного об'єкту
    $(body).on(
        'click',
        '[data-bs-target="#archive_regulation"], [data-bs-target="#delete_regulation"], [data-bs-target="#no_archive_regulation"], [data-bs-target="#no_delete_regulation"]',
        async function () {
            const blockId = $(this).closest('.list-group-item').data('id');
            //console.log(blockId)

            uriView = `/regulations/${blockId}`;

            await getResponseView(url, uriView);
            //console.log(regulation)

            selectedRegulation = regulation.regulation;
            console.log(selectedRegulation);

            // Отримати елемент titleModalRegulationDelete відносно кнопки, на яку було натиснуто
            const titleElement = $(document)
                .closest('.modal .modal-content')
                .find('#titleModalRegulationDelete');
            const titleElementNoDelete = $(document)
                .closest('.modal .modal-content')
                .find('#titleModalNoRegulationDelete');
            const titleElementNoArchive = $(document)
                .closest('.modal .modal-content')
                .find('#titleModalNoArchive');
            const titleElementArchive = $(document)
                .closest('.modal .modal-content')
                .find('#titleModalArchive');

            //console.log(titleElement)

            if (
                titleElement ||
                titleElementNoDelete ||
                titleElementNoArchive ||
                titleElementArchive
            ) {
                // Змінити текст у titleModalRegulationDelete на name обраного об'єкту
                $('#titleModalRegulationDelete').text(
                    getLocalizedText('regulationTitleModalRegulationDelete') +
                        selectedRegulation.name
                );
                $('#titleModalNoRegulationDelete').text(
                    getLocalizedText('regulationTitleModalNoRegulationDelete') +
                        selectedRegulation.name
                );
                $('#titleModalNoArchive').text(
                    getLocalizedText('regulationTitleModalNoArchive') + selectedRegulation.name
                );
                $('#titleModalArchive').text(selectedRegulation.name);
            }
        }
    );

    // Обробник події для кнопок "Archive Button" та "Delete Button" для збереження обраного об'єкту
    $(body).on('click', '[data-bs-target="#save_edit_regulation"]', async function () {
        const blockId = $(this).closest('.js-view-regulation').data('id');
        //console.log(blockId)

        uriView = `/regulations/${blockId}`;

        await getResponseView(url, uriView);
        //console.log(regulation)

        selectedRegulation = regulation.regulation;
        console.log(selectedRegulation);

        // Отримати елемент titleModalRegulationDelete відносно кнопки, на яку було натиснуто
        const titleElement = $(document)
            .closest('.modal .modal-content')
            .find('#titleModalSaveEditRegulation');

        //console.log(titleElement)

        if (titleElement) {
            // Змінити текст у titleModalRegulationDelete на name обраного об'єкту
            $('#titleModalSaveEditRegulation').text(
                getLocalizedText('regulationTitleModalSaveEditRegulation') +
                    '"' +
                    selectedRegulation.name +
                    '"'
            );
        }
    });

    // Обробник події для кнопки "save-edit-regulation" у модальному вікні
    $(body).on('click', '#save-edit-regulation', async function () {
        var dataBlockNameId = $('.js-view-regulation').data('blockname-id');
        var dataTypeId = $('.js-view-regulation').data('type-id');

        const blockId = $('.js-view-regulation').data('id');

        const checkedSaveChildren = $('#save-children-regulation').prop('checked');

        let boolCheckedSaveChildren;

        if (checkedSaveChildren) {
            boolCheckedSaveChildren = 1;
        } else {
            boolCheckedSaveChildren = 0;
        }

        uriView = `/regulations/${blockId}`;

        console.log(uriView, blockId);

        await getResponseView(url, uriView);
        //console.log(regulation)

        selectedRegulation = regulation.regulation;

        if (selectedRegulation) {
            var idRegulation = regulation.regulation.id;
            var regulationName = $('#regulationName').val();

            var regulationType = $('#regulationType').val();
            if (regulationType === 'parent') {
                regulationType = null;
            }

            var typeBlockName = '';
            var typeServiceSide = '';
            var draft = 0;
            const settingCreate = {
                typePalet: $('#type').val(),
                heightPalet: $('#height').val(),
                overheadTerm: $('#term').val(),
                palletSheet: $('#pallet-sheet').prop('checked'),
                allowPrefabPallets: $('#prefabricated-pallets').prop('checked'),
                allowSandwichPallet: $('#sandwich-pallet').prop('checked'),
                stickering: $('#stickering').prop('checked'),
                allowHolding: $('#allowHolding').prop('checked'),
            };

            if (dataBlockNameId === 'trade') {
                typeBlockName = 0;
            } else if (dataBlockNameId === 'warehouse') {
                typeBlockName = 1;
            } else if (dataBlockNameId === 'transport') {
                typeBlockName = 2;
            }

            if (dataTypeId === 'customer') {
                typeServiceSide = 0;
            } else if (dataTypeId === 'provider') {
                typeServiceSide = 1;
            }
            await editRegulation(
                typeBlockName,
                typeServiceSide,
                draft,
                settingCreate,
                regulationName,
                regulationType,
                idRegulation,
                boolCheckedSaveChildren
            );
            let uriList;
            if (dataBlockNameId === 'trade') {
                if (dataTypeId === 'customer') {
                    uriList = `/regulations/search?type=${0}&service_side=${0}`;
                } else if (dataTypeId === 'provider') {
                    uriList = `/regulations/search?type=${0}&service_side=${1}`;
                }
            } else if (dataBlockNameId === 'warehouse') {
                if (dataTypeId === 'customer') {
                    uriList = `/regulations/search?type=${1}&service_side=${0}`;
                } else if (dataTypeId === 'provider') {
                    uriList = `/regulations/search?type=${1}&service_side=${1}`;
                }
            } else if (dataBlockNameId === 'transport') {
                if (dataTypeId === 'customer') {
                    uriList = `/regulations/search?type=${2}&service_side=${0}`;
                } else if (dataTypeId === 'provider') {
                    uriList = `/regulations/search?type=${2}&service_side=${1}`;
                }
            }

            arrRegulation = null;
            // Додайте await тут, щоб зачекати завершення запиту
            await getResponse(url, uriList);
            // Після додавання іконок до DOM

            // Обробити дані
            $('#selected-type').empty();
            var selectedTypeBlock = renderSelectedType(dataBlockNameId, dataTypeId);
            $('#selected-type').append(selectedTypeBlock);
            $('#edit-regulation').empty();
            $('#' + dataBlockNameId + '-regulation-' + dataTypeId).removeClass('d-none');
            uriList = null;
            $('#view-regulation').empty();
        }

        selectedRegulation = null; // Збираємо дані про об'єкт після виконання операції
        $('.modal').modal('hide');
    });

    function updateCreateButtonStatus() {
        const allFieldsFilledSave = checkAllFieldsFilledSave();
        const allFieldsFilledDraft = checkAllFieldsFilledSaveDraft();
        //console.log(allFieldsFilled)

        if (allFieldsFilledSave) {
            $('#save-regulation-btn').removeClass('disabled');
            //console.log("Y")
        }
        if (allFieldsFilledDraft) {
            $('#draft-regulation-btn').removeClass('disabled');
        }
        //console.log('Обнова')
    }

    function updateEditButtonStatus() {
        const allFieldsFilledEdit = checkAllFieldsFilledEditSave();
        const allFieldsFilledEditDraft = checkAllFieldsFilledEditSaveDraft();
        //console.log(allFieldsFilledEditDraft)

        if (allFieldsFilledEditDraft) {
            $('#edit-draft-regulation-btn').removeClass('disabled');
        }
        if (allFieldsFilledEdit) {
            $('#edit-regulation-btn').removeClass('disabled');
            $('#edit-regulation-btn-modal').removeClass('disabled');
        }
        //console.log('Обнова')
    }

    // Функція для перевірки, чи всі поля заповнені для створення
    function checkAllFieldsFilledSave() {
        var regulationName = $('#regulationName').val();

        // var typePalet = $("#type").val();
        // var heightPalet = $("#height").val();
        // var overheadTerm = $("#term").val();

        return (
            regulationName !== ''

            // && typePalet !== '' &&
            // heightPalet !== '' &&
            // overheadTerm !== ''
        );
    }

    // Функція для перевірки, чи всі поля заповнені для створення чернетки
    function checkAllFieldsFilledSaveDraft() {
        var regulationName = $('#regulationName').val();

        return regulationName !== '';
    }

    // Функція для перевірки, чи всі поля заповнені для редагування
    function checkAllFieldsFilledEditSave() {
        var regulationName = $('#regulationName').val();
        var typePalet = $('#type').val();
        var heightPalet = $('#height').val();
        var overheadTerm = $('#term').val();

        var palletSheet = $('#pallet-sheet').prop('checked');
        var allowPrefabPallets = $('#prefabricated-pallets').prop('checked');
        var allowSandwichPallet = $('#sandwich-pallet').prop('checked');
        var stickering = $('#stickering').prop('checked');
        var allowHolding = $('#allowHolding').prop('checked');

        return (
            regulationName !== '' ||
            typePalet !== '' ||
            heightPalet !== '' ||
            overheadTerm !== '' ||
            palletSheet !== '' ||
            allowPrefabPallets !== '' ||
            allowSandwichPallet !== '' ||
            stickering !== '' ||
            allowHolding !== ''
        );
    }

    // Функція для перевірки, чи всі поля заповнені для редагування чернетки
    function checkAllFieldsFilledEditSaveDraft() {
        var regulationName = $('#regulationName').val();
        var typePalet = $('#type').val();
        var heightPalet = $('#height').val();
        var overheadTerm = $('#term').val();

        var palletSheet = $('#pallet-sheet').prop('checked');
        var allowPrefabPallets = $('#prefabricated-pallets').prop('checked');
        var allowSandwichPallet = $('#sandwich-pallet').prop('checked');
        var stickering = $('#stickering').prop('checked');
        var allowHolding = $('#allowHolding').prop('checked');

        return (
            regulationName !== '' ||
            typePalet !== '' ||
            heightPalet !== '' ||
            overheadTerm !== '' ||
            palletSheet !== '' ||
            allowPrefabPallets !== '' ||
            allowSandwichPallet !== '' ||
            stickering !== '' ||
            allowHolding !== ''
        );
    }

    // Обробники подій для полів форми, які можуть змінюватись
    $('#create-regulation').on('input change', 'input, select', function () {
        updateCreateButtonStatus();
    });

    $('#edit-regulation').on('input change', 'input, select', function () {
        updateEditButtonStatus();
    });
});
