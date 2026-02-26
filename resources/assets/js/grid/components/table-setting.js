export function tableSetting(table, settingChange = '', minRowsHeight = '', maxRowsHeight = '') {
    if (minRowsHeight === '' && maxRowsHeight === '') {
        minRowsHeight = 35;
        maxRowsHeight = 55;
    }

    $(`#changeFonts${settingChange}`).click(function () {
        if ($(this).is(':checked')) {
            document.documentElement.style.setProperty('--jqx-font-size-light-wms', '18px');
        } else {
            document.documentElement.style.setProperty('--jqx-font-size-light-wms', '14px');
        }
    });

    $(`#changeCol${settingChange}`).click(function () {
        var checked = $(this).is(':checked');
        table.jqxGrid({ columnsresize: checked });
        table.jqxGrid('refreshdata');
        table.jqxGrid('gotopage', 0);
    });

    $('.changeMenu-2, .changeMenu-3').click(function () {
        var rowsheight = $(this).hasClass('changeMenu-2') ? minRowsHeight : maxRowsHeight;
        $(this).addClass('active-row-table').siblings().removeClass('active-row-table');
        table.jqxGrid({ rowsheight: rowsheight });
    });
}
