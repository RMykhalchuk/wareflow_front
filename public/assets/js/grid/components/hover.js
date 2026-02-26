export function hover(table, isRowHovered) {
    table.on('mouseenter', '[role="row"]', function (event) {
        if (!$(event.currentTarget).find('[role="checkbox"]').is(':checked')) {
            $(event.currentTarget).find('[role="gridcell"]').addClass('jqx-grid-row-hover');
            isRowHovered = true;
        }
    });

    table.on('mouseleave', '[role="row"]', function (event) {
        if (!$(event.currentTarget).find('[role="checkbox"]').is(':checked')) {
            $(event.currentTarget).find('[role="gridcell"]').removeClass('jqx-grid-row-hover');
            isRowHovered = false;
        }
    });

    table.on('click', '[role="checkbox"]', function (event) {
        if ($(this).is(':checked')) {
            $(this)
                .closest('[role="row"]')
                .find('[role="gridcell"]')
                .removeClass('jqx-grid-row-hover');
            isRowHovered = false;
        } else {
            if (!isRowHovered) {
                $(this)
                    .closest('[role="row"]')
                    .find('[role="gridcell"]')
                    .addClass('jqx-grid-row-hover');
                isRowHovered = true;
            }
        }
    });

    table.on('mousedown', '[role="gridcell"]', function (event) {
        event.stopPropagation();
    });

    table.on('mouseup', '[role="gridcell"]', function (event) {
        $(this).closest('[role="row"]').find('[role="gridcell"]').addClass('jqx-grid-row-hover');
        isRowHovered = true;
    });
}
