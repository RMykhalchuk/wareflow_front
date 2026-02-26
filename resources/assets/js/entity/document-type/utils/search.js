// Універсальний пошук
export function findListItem(inputElement, containerSelector, itemSelectorFunction) {
    $(inputElement).on('input', function () {
        const searchValue = $(this).val().toLowerCase();
        console.log(searchValue);
        $(containerSelector).each(function () {
            const listItemText = itemSelectorFunction($(this));
            console.log(listItemText);
            if (listItemText.includes(searchValue)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
}
