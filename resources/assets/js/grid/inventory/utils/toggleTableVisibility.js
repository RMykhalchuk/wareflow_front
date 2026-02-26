export function toggleTableVisibility($table, data) {
    if (!data || data.length === 0) {
        $table.addClass('d-none');
        $('#empty-cell-block').removeClass('d-none');
        $('#add_leftovers_button_footer').addClass('d-none');
    } else {
        $table.removeClass('d-none');
        $('#empty-cell-block').addClass('d-none');
        $('#add_leftovers_button_footer').removeClass('d-none');
    }
}
