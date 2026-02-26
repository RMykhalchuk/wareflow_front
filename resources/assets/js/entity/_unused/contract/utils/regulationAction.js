import { sendRequest, sendResponseActionEdit } from './httpRequest.js';

export function createRegulationEvent(
    typeBlockParam,
    typeServiceSideParam,
    checkSelectValues,
    csrf,
    url,
    updateTrackChangesCallback
) {
    function createRegulation(
        typeBlockName,
        typeServiceSide,
        draft,
        settingCreate,
        regulationName,
        regulationType
    ) {
        const uriCreateRegulation = '/regulations';

        let formData = new FormData();

        formData.append('_token', csrf);
        formData.append('name', regulationName);
        formData.append('type', typeBlockName);
        formData.append('service_side', typeServiceSide);
        formData.append('parent_id', regulationType);
        formData.append('settings', JSON.stringify(settingCreate));
        formData.append('draft', draft);

        return sendRequest(url, uriCreateRegulation, 'POST', formData);
    }

    $(document).off('click', '#create-regulation');

    $(document).on('click', '#create-regulation', function (e) {
        var regulationName = $('#nameRetailInModal').val();

        var regulationType = $('#parentRegulationInModal').val();
        if (regulationType === 'parent') {
            regulationType = null;
        }

        var typeBlockName = typeBlockParam;
        var typeServiceSide = typeServiceSideParam;
        var draft = 0;
        const settingCreate = {};

        if (typeBlockName !== undefined && typeServiceSide !== undefined) {
            createRegulation(
                typeBlockName,
                typeServiceSide,
                draft,
                settingCreate,
                regulationName,
                regulationType
            )
                .then(() => {
                    updateTrackChangesCallback(false); // Оновити track_changes
                    // Інші дії після успішного створення
                })
                .catch((error) => {
                    console.error('Error:', error);
                    // Обробка помилок
                });

            checkSelectValues();
            $('.modal').modal('hide');
            $('#btn-cancel-changes').addClass('d-none');

            $('#btn-save').removeAttr('data-bs-toggle');
            $('#btn-save').removeAttr('data-bs-target');
        }
    });
}

export function updateRegulationEvent(
    typeBlockParam,
    typeServiceSideParam,
    checkSelectValues,
    csrf,
    url,
    updateTrackChangesCallback,
    getSelectedRegulation,
    isContractPageView = false
) {
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
        const uriEdit = `/regulations/${idRegulation}`;

        let formData = new FormData();

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

        return sendResponseActionEdit(url, uriEdit, 'POST', formData);
    }

    $(document).off('click', '#update-regulation');

    $(document).on('click', '#update-regulation', function (e) {
        var regulationName = $('#nameRetail').val();

        var regulationType = $('#parentRegulation').val();
        if (regulationType === 'parent') {
            regulationType = null;
        }
        var typeBlockName = typeBlockParam;
        var typeServiceSide = typeServiceSideParam;
        var draft = 0;

        const settingRegulation = {
            typePalet: $('#typePallet').val(),
            heightPalet: $('#heightPallet').val(),
            overheadTerm: $('#remainingTerm').val(),
            palletSheet: $('#palletLatter').prop('checked'),
            allowPrefabPallets: $('#allowPrefabricatedPallets').prop('checked'),
            allowSandwichPallet: $('#allowSendwichPallet').prop('checked'),
            stickering: $('#labeling').prop('checked'),
            allowHolding: $('#allowCondacting').prop('checked'),
        };

        //console.log(selected_regulation)
        editRegulation(
            typeBlockName,
            typeServiceSide,
            draft,
            settingRegulation,
            regulationName,
            regulationType,
            getSelectedRegulation()
        )
            .then(() => {
                updateTrackChangesCallback(false); // Оновити track_changes
                // Інші дії після успішного створення
            })
            .catch((error) => {
                console.error('Error:', error);
                // Обробка помилок
            });

        checkSelectValues();

        $('.modal').modal('hide');

        $('#btn-cancel-changes').addClass('d-none');

        $('#btn-save').removeAttr('data-bs-toggle');
        $('#btn-save').removeAttr('data-bs-target');

        if (isContractPageView) {
            window.location.reload();
        }
    });
}

// //дублювати дочірній регламент
// $(document).on(
//     "click",
//     ".content-for-market .duplicate-child-regulation",
//     function (e) {
//         var $contentForMarket = $(this).closest(".content-for-market");
//         var $childrenRegulationsList = $contentForMarket.find(
//             ".children-regulations-list"
//         );
//         var idEl = $(this).data("id");
//         var parentId = $(this).data("parentid");
//
//         var nameRegulation = regulationsArray.reduce(function (acc, regulation) {
//             if (regulation.id === parentId) {
//                 var childRegulation = regulation.children.find(function (item) {
//                     return item.id === idEl;
//                 });
//                 if (childRegulation) {
//                     return childRegulation.name;
//                 }
//             }
//             return acc;
//         }, "");
//
//         var existingNames = regulationsArray.reduce(function (acc, regulation) {
//             if (regulation.id === parentId) {
//                 regulation.children.forEach(function (child) {
//                     acc.push(child.name);
//                 });
//             }
//             return acc;
//         }, []);
//
//         var newName = nameRegulation;
//         var numberPart = "";
//
//         if (/\s(\d+)$/.test(nameRegulation)) {
//             var matches = nameRegulation.match(/(.+)\s(\d+)$/);
//             if (matches) {
//                 var textPart = matches[1];
//                 numberPart = matches[2];
//                 var incrementedNumber = parseInt(numberPart) + 1;
//                 newName = textPart + " " + incrementedNumber;
//             }
//         } else {
//             newName += " 1";
//         }
//
//         while (existingNames.includes(newName)) {
//             var matches = newName.match(/(.+)\s(\d+)$/);
//             if (matches) {
//                 var textPart = matches[1];
//                 var incrementedNumber = parseInt(matches[2]) + 1;
//                 newName = textPart + " " + incrementedNumber;
//             }
//         }
//
//         var childElement = inputNameNewRegulation(newName, parentId);
//
//         $childrenRegulationsList.append(childElement);
//
//         // Handle input editing and event listeners
//         var $newRegulationNameInput = $(".new-name-input-group:last #newRegulationNameInput");
//         $newRegulationNameInput.focus();
//
//         function handleClickKeys(e) {
//             if (e.which === 13) {
//                 markupItemClildRegulation(
//                     $newRegulationNameInput.val(),
//                     new Date().getTime(),
//                     0,
//                     parentId
//                 );
//                 $(this).off("keypress", handleClickKeys);
//                 $(document).off("click", handleClick);
//             }
//         }
//
//         $newRegulationNameInput.on("keypress", handleClickKeys);
//
//         $newRegulationNameInput.on("click", function (e) {
//             e.stopPropagation();
//         });
//
//         function handleClick(e) {
//             if (
//                 !$newRegulationNameInput.is(e.target) &&
//                 $newRegulationNameInput.has(e.target).length === 0
//             ) {
//                 markupItemClildRegulation(
//                     $newRegulationNameInput.val(),
//                     new Date().getTime(),
//                     0,
//                     parentId
//                 );
//
//                 $(document).off("click", handleClick);
//                 $newRegulationNameInput.off("keypress", handleClickKeys);
//             }
//         }
//
//         $(document).on("click", handleClick);
//
//         function markupItemClildRegulation(name, id, amount, parentId) {
//             if (name !== nameRegulation) {
//                 var childElement = regulationItemChild(name, id, amount, parentId);
//                 $childrenRegulationsList.append(childElement);
//                 $(".new-name-input-group").remove();
//                 $newRegulationNameInput.val("");
//                 regulationsArray.forEach(function (el) {
//                     if (el.id === parentId) {
//                         el.children.push({id, name, amount});
//                     }
//                 });
//             }
//         }
//     }
// );
//
// //видалити дочірній регламент
// $(document).on(
//     "click",
//     ".content-for-market .delete-child-regulation",
//     function (e) {
//         var idEl = $(this).data("id");
//         var parentId = $(this).data("parentid");
//
//         var parentRegulation = regulationsArray.find(function (regulation) {
//             return regulation.id === parentId;
//         });
//
//         if (parentRegulation) {
//             var childIndex = parentRegulation.children.findIndex(function (child) {
//                 return child.id === idEl;
//             });
//
//             if (childIndex !== -1) {
//                 parentRegulation.children.splice(childIndex, 1);
//
//                 $(this).closest(".child-regulations-item").remove();
//             }
//         }
//     }
// );
