$(function findListItemModal() {
    $('#searchTypeDoc').on('input', function () {
        const searchValue = $(this).val().toLowerCase();
        console.log(searchValue);
        $('#typeList li').each(function () {
            const listItemText = $(this).find('p a').text().toLowerCase();
            console.log(listItemText);
            const listItemHasP = $(this).find('p a').length > 0;
            if (listItemHasP && listItemText.includes(searchValue)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
