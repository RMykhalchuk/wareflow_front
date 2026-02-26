$(function findListItemDocument() {
    $('#searchListDocument').on('input', function () {
        const searchValue = $(this).val().toLowerCase();
        console.log(searchValue);
        $('#listDocument div').each(function () {
            const listItemText = $(this).find('.card-title').text().toLowerCase();
            console.log(listItemText);
            if (listItemText.includes(searchValue)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
